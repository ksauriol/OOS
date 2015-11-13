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
                values (:profileID, :longitude, :latitude, :altitude, :dateAndtime)"
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
            $gpsDatum->setError("GPSDataDB", "ADD_GPS_FAILED");
        } catch (RuntimeException $e) {
            $gpsDatum->setError("database", "DB_CONFIG_NOT_FOUND");
        }
        
        return $returnGPSID;
    }
    
    public static function editGPS($gpsDatum) {
        
        try {
            $db = Database::getDB ();
            if (is_null($gpsDatum) || $gpsDatum->getErrorCount() > 0)
                return $gpsDatum;
            
            $checkForExisting = GPSDataDB::getGPSBy('gpsID', $gpsDatum->getID());
            if (empty($checkUser))
                $gpsDatum->setError('gpsID', 'GPSID_DOES_NOT_EXIST');

            if ($gpsDatum->getErrorCount() > 1)
                return $gpsDatum;
            
            $stmt = $db->prepare(
                "update GPSData
                set longitude = :longitude, latitude = :latitude,
                    altitude = :altitude, dateAndTime = :dateAndTime
                where profileID = :profileID
                and dateAndTime = :dateAndTime"
            );
            $stmt->execute(array(
                ":longitude" => $gpsDatum->getLongitude(),
                ":latitude" => $gpsDatum->getLatitude(),
                ":altitude" => $gpsDatum->getAltitude(),
                ":dateAndTime" => $gpsDatum->getDateAndTime(),
                ":profileID" => $gpsDatum->getProfileID()
            ));
            
        } catch (PDOException $e) {
            $gpsDatum->setError('GPSDataDB', 'GPS_EDIT_FAILED');
        } catch (RuntimeException $e) {
            $gpsDatum->setError("database", "DB_CONFIG_NOT_FOUND");
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
    
    // returns a GPSDatum object whose $type field has value $value
    public static function getGPSBy($type, $value) {
        $allowed = ['gpsID'];
        $gps = null;
        
        try {
            if (!in_array($type, $allowed))
                throw new PDOException("$type not allowed search criterion for GPS data");
            
            $db = Database::getDB();
            $stmt = $db->prepare("select * from GPSData where ($type = :$type)");
            $stmt->execute(array(":$type" => $value));
            
            if(count($stmt) > 1)
                throw new PDOException("Error: multiple results returned");
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false)
                $gps = new GPSDatum($row);
            
        } catch (PDOException $e) {
            $gpsDatum->setError('GPSDataDB', 'GPS_GET_FAILED');
        } catch (RuntimeException $e) {
            $gpsDatum->setError("database", "DB_CONFIG_NOT_FOUND");
        }
        
        return $gps;
    }
    
    public static function deleteGPSBy($type, $value) {
        $allowed = ['gpsID'];
        
        try {
            if (!in_array($type, $allowed))
                throw new PDOException("$type not allowed search criterion for GPS data");
        
            // make sure target exists
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
        
        } catch (PDOException $e) {
            $gpsDatum->setError('GPSDataDB', 'GPS_DELETE_FAILED');
            return false;
        } catch (RuntimeException $e) {
            $gpsDatum->setError("database", "DB_CONFIG_NOT_FOUND");
            return false;
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