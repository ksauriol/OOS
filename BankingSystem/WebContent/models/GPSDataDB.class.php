<?php
class GPSDataDB {
    
    // adds the specified GPSDatum object to the database
    public static function addGPS($gpsDatum) {
        $returnGPSID = -1;
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "insert into GPSData (profileID, longitude, latitude,
                    altitude, dateAndTime)
                values (:profileID, :longitude, :latitude, :altitude, :dateAndTime)"
            );
            $stmt->execute(array(
                ":profileID" => $gpsDatum->getProfileID(),
                ":longitude" => $gpsDatum->getLongitude(),
                ":latitude" => $gpsDatum->getLatitude(),
                ":altitude" => $gpsDatum->getAltitude(),
                ":dateAndTime" => $gpsDatum->getDateAndTime()
            ));
            $returnGPSID = $db->lastInsertId("gpsID");
            
        } catch (PDOException $e) {
            $gpsDatum->setError('GPSDataDB', 'GPS_ADD_FAILED');
            return $e->getMessage();
        } catch (RuntimeException $e) {
            $gpsDatum->setError("database", "DB_CONFIG_NOT_FOUND");
            return $e->getMessage();
        }
        
        return $returnGPSID;
    }
    
    public static function editGPS($gpsDatum) {
        
        try {
            $db = Database::getDB();
            if (is_null($gpsDatum) || $gpsDatum->getErrorCount() > 0) {
                $gpsDatum->setError('GPSDataDB', 'GPS_EDIT_FAILED');
                return 'GPSDataDB::editGPS : gpsDatum argument was null or had errors';
            }
            
            $checkForExisting = self::getGPSByID($gpsDatum->getID());
            if ($checkForExisting === false) {
                $gpsDatum->setError('gpsID', 'GPSID_DOES_NOT_EXIST');
                return "A GPS entry with ID of " . $gpsDatum->getID() . " was not found.";
            } else if (!($checkForExisting instanceof GPSDatum))
                throw new PDOException($checkForExisting);
            
            $stmt = $db->prepare(
                "update GPSData
                set profileID = :profileID, longitude = :longitude, latitude = :latitude,
                    altitude = :altitude, dateAndTime = :dateAndTime
                where gpsID = :gpsID"
            );
            $stmt->execute(array(
                ":profileID" => $gpsDatum->getProfileID(),
                ":longitude" => $gpsDatum->getLongitude(),
                ":latitude" => $gpsDatum->getLatitude(),
                ":altitude" => $gpsDatum->getAltitude(),
                ":dateAndTime" => $gpsDatum->getDateAndTime(),
                ":gpsID" => $gpsDatum->getID()
            ));
            
            if ($stmt->rowCount() != 1)
                throw new PDOException("" . $stmt->rowCount() . " row affected. Expected 1.");
            
        } catch (PDOException $e) {
            $gpsDatum->setError('GPSDataDB', 'GPS_EDIT_FAILED');
            return $e->getMessage();
        } catch (RuntimeException $e) {
            $gpsDatum->setError("database", "DB_CONFIG_NOT_FOUND");
            return $e->getMessage();
        }

        return $gpsDatum;
    }
    
    // returns an array of GPSDatum objects for all GPS data in the database
    public static function getAllGPSData() {
        $allGPSData = array();
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare("select * from GPSData");
            $stmt->execute();
        
            foreach ($stmt as $row) {
                $gps = new GPSDatum($row);
                if (!is_object($gps) || $gps->getErrorCount() != 0)
                    throw new PDOException("Failed to create valid GPS datum from database entry.");
                
                $allGPSData[] = $gps;
            }
        } catch (PDOException $e) {
            $gpsDatum->setError('GPSDataDB', 'GPS_GET_ALL_FAILED');
        } catch (RuntimeException $e) {
            $gpsDatum->setError("database", "DB_CONFIG_NOT_FOUND");
        }
        
        return $allGPSData;
    }
    
    // returns an array of GPSDatum objects associated with the specified profileID, or an error message on failure
    public static function getGPSByMember($profileID) {
        $allGPSData = array();
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select gpsID, profileID, longitude, latitude, altitude, dateAndTime
                from Profiles join GPSData using (profileID)
                where profileID = :profileID"
            );
            $stmt->execute(array(":profileID" => $profileID));
            
            foreach ($stmt as $row) {
                $gps = new GPSDatum($row);
                if (!is_object($gps) || $gps->getErrorCount() != 0)
                    throw new PDOException("Failed to create valid GPS datum from database entry.");
            
                $allGPSData[] = $gps;
            }
        
        } catch (PDOException $e) {
            return $e->getMessage();
        } catch (RuntimeException $e) {
            return $e->getMessage();
        }
        
        return $allGPSData;
    }
    
    // returns a GPSDatum object whose $type field has value $value.
    // returns false when data not found, and returns an error message on error
    public static function getGPSByID($gpsID) {
        $gps = false;
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare("select * from GPSData where gpsID = :gpsID");
            $stmt->execute(array(":gpsID" => $gpsID));
            
            if(count($stmt) > 1)
                throw new PDOException("Error: multiple results returned");
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false)
                $gps = new GPSDatum($row);
            
        } catch (PDOException $e) {
            return $e->getMessage();
        } catch (RuntimeException $e) {
            return $e->getMessage();
        }
        
        return $gps;
    }
    
    // returns true on success, or an error message on failure
    public static function deleteGPSBy($type, $value) {
        $allowed = ['gpsID'];
        
        try {
            if (!in_array($type, $allowed))
                throw new PDOException("$type not allowed search criterion for GPS data");
        
            // make sure a single target exists
            $db = Database::getDB();
            $stmt = $db->prepare("select * from GPSData where ($type = :$type)");
            $stmt->execute(array(":$type" => $value));
            if(count($stmt) > 1)
                throw new PDOException("Error: multiple results returned");
            else if (count($stmt) === 0)
                throw new PDOException("Error: delete target not found.");
        
            // delete the data
            $stmt = $db->prepare("delete from GPSData where ($type = :$type)");
            $stmt->execute(array(":$type" => $value));
            
            if ($stmt->rowCount() != 1)
                throw new PDOException("" . $stmt->rowCount() . " rows affected. Expected 1.");
        
        } catch (PDOException $e) {
            $gpsDatum->setError('GPSDataDB', 'GPS_DELETE_FAILED');
            return $e->getMessage();
        } catch (RuntimeException $e) {
            $gpsDatum->setError("database", "DB_CONFIG_NOT_FOUND");
            return $e->getMessage();
        }
        
        return true;
    }
    
    public static function deleteMultipleGPSDataBy($type, $value) {
        $allowed = ['gpsID', 'profileID'];
    
        try {
            if (!in_array($type, $allowed))
                throw new PDOException("$type not allowed search criterion for GPS data");
    
            // make sure at least one target was found
            $db = Database::getDB();
            $stmt = $db->prepare("select * from GPSData where ($type = :$type)");
            $stmt->execute(array(":$type" => $value));
            if(count($stmt) == 0)
                throw new PDOException("Error: no results returned");
    
            // delete the data
            $stmt = $db->prepare("delete from GPSData where ($type = :$type)");
            $stmt->execute(array(":$type" => $value));
    
        } catch (PDOException $e) {
            $gpsDatum->setError('GPSDataDB', 'GPS_DELETE_FAILED');
            return false;
        } catch (RuntimeException $e) {
            $gpsDatum->setError("database", "DB_CONFIG_NOT_FOUND");
            return false;
        }
    
        return true;
    }
    
}
?>