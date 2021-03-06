<?php
class ProfilesDB {
    
    // adds the specified Profile object to the database
    public static function addProfile($profile) {
        $returnProfileID = -1;
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "insert into Profiles (email, phone, gender, dob, password, accountID)
                values (:email, :phone, :gender, :dob, :password, :accountID)"
            );
            $stmt->execute(array(
                ":email" => $profile->getEmail(),
                ":phone" => $profile->getPhoneNumber(),
                ":gender" => $profile->getGender(),
                ":dob" => $profile->getDOB(),
            	":password" => $profile->getPassword(),
                ":accountID" =>$profile->getAccountID()
            ));
            $returnProfileID = $db->lastInsertId("profileID");
            
        } catch (PDOException $e) {
            $profile->setError("profilesDB", "ADD_PROFILE_FAILED");
            return $e->getMessage();
        } catch (RuntimeException $e) {
            $profile->setError("database", "DB_CONFIG_NOT_FOUND");
            return $e->getMessage();
        }
        
        return $returnProfileID;
    }
    
    public static function editProfile($profile) {
    	
    	try {
			$db = Database::getDB ();
			
			/*if (is_null($profile) || $profile->getErrorCount() > 0)
				return $profile;
			
			$checkProfile = ProfilesDB::getProfileBy('profileID', $profile->getProfileID());
			if (empty($checkUser))
				$profile->setError('profileID', 'USER_DOES_NOT_EXIST');

			if ($profile->getErrorCount() > 1)
				return $profile; */
			
			$query = "UPDATE Profiles SET password = :password
										WHERE profileID = :profileID";
					/*accountID = :accountID, password = :password, address = :address, middleName = :middleName, 
					email = :email, gender = :gender, SSN = :SSN, lastName = :lastName, dob = :dob, phone = :phone, 
					dateCreated = :dateCreated, isEmployee = :isEmployee, passwordChanged = :passwordChanged
	                 WHERE profileID = :profileID";*/
			
			
			$statement = $db->prepare ($query);
			$statement->bindValue(":password", $profile->getPassword());
			$statement->bindValue(":profileID", $profile->getProfileID());
			/*$statement->bindValue(":email", $profile->getEmail());
			$statement->bindValue(":SSN", $profile->getSSN());
			$statement->bindValue(":gender", $profile->getGender());
			$statement->bindValue(":dob", $profile->getDOB());
			$statement->bindValue(":phone", $profile->getPhoneNumber());
			$statement->bindValue(":address", $profile->getAddress());
			$statement->bindValue(":accountID", $profile->getAccountID());
			$statement->bindValue(":dateCreated ", $profile->getDateCreated ());
			$statement->bindValue(":isEmployee", $profile->getIsEmployee());
			$statement->bindValue(":passwordChanged", $profile->getPasswordChanged());
			$statement->bindValue(":SSN", $profile->getSSN());*/
			$statement->execute ();
			$statement->closeCursor();
			
        } catch (PDOException $e) {
            $profile->setError('profilesDB', 'PROFILE_EDIT_FAILED');
        } catch (RuntimeException $e) {
            $profile->setError("database", "DB_CONFIG_NOT_FOUND");
        }
        
        return $profile;
    }
    
    // returns an array of Profile objects for all profiles in the database
    public static function getAllProfiles() {
        $allProfiles = array();
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare("select * from Profiles");
            $stmt->execute();
        
            foreach ($stmt as $row) {
                $profile = new Profile($row);

                if (!is_object($profile) || $profile->getErrorCount()!=0)
                    throw new PDOException("Failed to create valid profile");
                
                $allProfiles[] = $profile;
            }
            
        } catch (PDOException $e) {
            $profile->setError('profilesDB', 'PROFILE_GET_ALL_FAILED');
        } catch (RuntimeException $e) {
            if (isset($profile))
                $profile->setError("database", "DB_CONFIG_NOT_FOUND");
        }
        
        return $allProfiles;
    }
    
    // returns a Profile object whose $type field has value $value
    public static function getProfileBy($type, $value) {
        $allowed = ['profileID', 'email'];
        $profile = null;
        
        try {
            if (!in_array($type, $allowed))
                throw new PDOException("$type not allowed search criterion for Profile");
            
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select * from Profiles
                where ($type = :$type)");
            $stmt->execute(array(":$type" => $value));
            
            if(count($stmt) > 1)
                throw new PDOException("Error: multiple results returned");
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false)
                $profile = new Profile($row);
            
        } catch (PDOException $e) {
            $profile->setError('profilesDB', 'PROFILE_GET_FAILED');
        } catch (RuntimeException $e) {
            $profile->setError("database", "DB_CONFIG_NOT_FOUND");
        }
        
        return $profile;
    }
    
    public static function deleteProfileBy($type, $value) {
        $allowed = ['profileID', 'email', 'SSN'];
        $profile = null;
        $success = true;
        
        try {
            if (!in_array($type, $allowed))
                throw new PDOException("$type not allowed search criterion for Profile");
        
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select * from Profiles
                where ($type = :$type)"
            );
            $stmt->execute(array(":$type" => $value));
        
            if(count($stmt) > 1)
                throw new PDOException("Error: multiple results returned");
        
            $stmt = $db->prepare(
                "delete from Profiles
                where ($type = :$type)"
            );
            $stmt->execute(array(":$type" => $value));
        
        } catch (PDOException $e) {
            $success = false;
        } catch (RuntimeException $e) {
            $success = false;
        }
        
        return $success;
    }
    
    public static function deleteProfilesBy($type, $value) {
        $allowed = ['profileID', 'email', 'SSN'];
        $profile = null;
        $success = true;
    
        try {
            if (!in_array($type, $allowed))
                throw new PDOException("$type not allowed search criterion for Profile");
    
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select * from Profiles
                where ($type = :$type)"
            );
            $stmt->execute(array(":$type" => $value));
    
            if(count($stmt) == 0)
                throw new PDOException("Error: no results returned");
    
            $stmt = $db->prepare(
                "delete from Profiles
                where ($type = :$type)"
            );
            $stmt->execute(array(":$type" => $value));
    
        } catch (PDOException $e) {
            $success = false;
        } catch (RuntimeException $e) {
            $success = false;
        }
    
        return $success;
    }
    
    public static function logIn($profileID) {
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "update Profiles set isLoggedIn = true
                where profileID = :profileID"
            );
            $stmt->execute(array(":profileID" => $profileID));
            
            $profile = self::getProfileBy('profileID', $profileID);
        
        } catch (PDOException $e) {
            return false;
        } catch (RuntimeException $e) {
            return false;
        }
        
        return $profile;
    }
    
    public static function logOut($profileID) {
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "update Profiles set isLoggedIn = false
                where profileID = :profileID"
            );
            $stmt->execute(array(":profileID" => $profileID));
    
        } catch (PDOException $e) {
            return false;
        } catch (RuntimeException $e) {
            return false;
        }
    
        return true;
    }
    
}
?>