<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Profile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\ProfilesDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class MemberProfilesDBTest extends PHPUnit_Framework_TestCase {
    
    /** @expectedException Exception
     * @expectedExceptionMessage Missing argument
     */
    public function testAddMemberProfileWithNoParameters() {
        ProfilesDB::addProfile();
    }
    
    public function testAddMemberProfileWithValidParameter() {
        $input = array(
            "firstName" => "Nathan",
            "middleName" => null,
            "lastName" => "Martin",
            "email" => "namar@email.com",
            "gender" => "male",
            "phone" => "210-555-4917",
            "address" => "201 Sunset Ave, San Antonio, TX 78249",
            "dob" => "1983-11-26",
        	"profileType" => "member"
        );
        $profile = new Profile($input);
        $this->dbQuery("delete from Profiles where email = 'namar@email.com'");
        
        $rowsBeforeAdd = $this->dbSelect("select * from Profiles where email = 'namar@email.com'");
        $profileID = ProfilesDB::addProfile($profile);
        $rowsAfterAdd = $this->dbSelect("select * from Profiles where email = 'namar@email.com'");
        
        $this->assertEmpty($rowsBeforeAdd,
            'It should not have the provided member profile until the profile has been added');
        $this->assertCount(1, $rowsAfterAdd,
            'It should have a new row in the Profiles table of the database when the profile parameter is provided');
        $this->assertArrayHasKey('firstName', $rowsAfterAdd[0],
            'It should have a new row in the Profiles table of the database when the profile parameter is provided');
        $this->assertEquals("namar@email.com", $rowsAfterAdd[0]["email"],
            'It should have a new row in the Profiles table of the database when the profile parameter is provided');
        $this->assertTrue(is_numeric($profileID),
            'It should return the profile ID of the added member profile when the profile parameter is provided');
        $this->assertNotEquals(-1, $profileID,
            'It should return a valid profile ID of the added member profile when the profile parameter is provided');
    }
    
    public function testGetAllMemberProfiles() {
        $profiles = ProfilesDB::getAllProfiles();
        
        $this->assertNotEmpty($profiles,
            'It should return a non-empty array');
        
        foreach ($profiles as $profile) {
        	//print_r($Profile->__toString());
            $this->assertInstanceOf('Profile', $profile,
                'It should return an array of Profile objects');
            $this->assertCount(0, $profile->getErrors(),
                'It should return an array of Profile objects without errors');
            $this->assertEquals(0, $profile->getErrorCount(),
                'It should return an array of Profile objects with an error count of 0');
        }
    }
    
    public function testGetAllMemberProfilesWithNullParameter() {
        $profiles = ProfilesDB::getAllProfiles(null);
        
        $this->assertNotEmpty($profiles,
            'It should return a non-empty array when null input is provided');
        
        foreach ($profiles as $profile) {
            $this->assertInstanceOf('Profile', $profile,
                'It should return an array of Profile objects when null input is provided');
            $this->assertCount(0, $profile->getErrors(),
                'It should return an array of Profile objects without errors when null input is provided');
            $this->assertEquals(0, $profile->getErrorCount(),
                'It should return an array of Profile objects with an error count of 0 when null input is provided');
        }
    }
    
    /** @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage Missing argument
     */
    public function testGetMemberProfileByWithNoParameters() {
        $profile = ProfilesDB::getProfileBy();
    }
    
    /** @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage Missing argument
     */
    public function testGetMemberProfileByWithNoValueParameter() {
        $profile = ProfilesDB::getProfileBy('phone');
    }
    
    public function testGetMemberProfileByWithValidParameters() {
        $profile = ProfilesDB::getProfileBy('email', 'namar@email.com');
        
        $this->assertInstanceOf('Profile', $profile,
            'It should return a Profile object when valid parameters are provided');
        $this->assertEquals('namar@email.com', $profile->getEmail(),
            'It should return a Profile object whose email field matches the provided input when valid input is provided');
        $this->assertCount(0, $profile->getErrors(),
            'It should return a Profile object without errors when valid input is provided:' . "\n" . array_shift($profile->getErrors()));
        $this->assertEquals(0, $profile->getErrorCount(),
            'It should return a Profile object with an error count of 0 when valid input is provided');
    }
    
    public function testGetMemberProfileByWithNoResults() {
        $profile = ProfilesDB::getProfileBy('email', 'invalidEmail@email.com');
        
        $this->assertNull($profile,
            'It should return NULL when an unknown attribute-value pair is provided');
    }
    
    private function dbQuery($query) {
        try {
            $db = Database::getDB();
            $stmt = $db->prepare($query);
            $stmt->execute();
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }
    
    private function dbSelect($query) {
        try {
            $db = Database::getDB();
            $stmt = $db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    
        return $rows;
    }
    
    private function checkSession() {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION))
            $_SESSION = array();
        if (!isset($_SESSION['dbName']) || $_SESSION['dbName'] !== 'dhma_testDB')
            $_SESSION['dbName'] = 'dhma_testDB';
        if (!isset($_SESSION['configFile']) || $_SESSION['configFile'] !== 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini')
            $_SESSION['configFile'] = 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini';
    }
}
?>