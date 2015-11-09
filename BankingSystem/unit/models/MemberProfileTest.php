<?php

require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Profile.class.php'; 
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class MemberProfileTest extends PHPUnit_Framework_TestCase {
    
    private static $validInput = array(
        "firstName" => "Nathan",
        "middleName" => null,
        "lastName" => "Davis",
        "email" => "nathan@email.com",
        "gender" => "male",
        "phone" => "281-555-4937",
        "address" => "4210 Naples Dr, San Antonio, TX, 78249",
        "dob" => "1983-11-02",
    	"profileType" => "member"
    );
    
    private static $validInput2 = array(
    		"firstName" => "testsmall",
    		"lastName"  => "testing",
    		"email"     => "eamil@unique.com"
    );
    
    public function testCreateValidMemberProfile() {
        $validProfile = new Profile(MemberProfileTest::$validInput);
        $validProfile2 = new Profile(MemberProfileTest::$validInput2);
     //   print_r($validProfile->__toString());
        $this->assertInstanceOf('Profile', $validProfile,
            'It should create a Profile object when valid input is provided');
        $this->assertEquals(0, $validProfile->getErrorCount(),
            'It should not have errors when valid input is provided');
        $this->assertInstanceOf('Profile', $validProfile2,
        		'It should create a Profile object when valid input is provided');
        $this->assertEquals(0, $validProfile2->getErrorCount(),
        		'It should not have errors when valid input is provided');
      // foreach($validProfile->getErrors() as $err){
        //	print_r($validProfile->getErrorCount()."<-----------");
        //}
    }
    
    public function testParameterExtraction() {
        $validProfile = new Profile(MemberProfileTest::$validInput);
        $params = $validProfile->getParameters();
        
        $this->assertArrayHasKey('firstName', $params,
            'It should return a parameter array with "firstName" as a key');
        $this->assertArrayHasKey('middleName', $params,
            'It should return a parameter array with "middleName" as a key');
        $this->assertArrayHasKey('lastName', $params,
            'It should return a parameter array with "lastName" as a key');
        $this->assertArrayHasKey('email', $params,
            'It should return a parameter array with "email" as a key');
        $this->assertArrayHasKey('gender', $params,
            'It should return a parameter array with "gender" as a key');
        $this->assertArrayHasKey('phone', $params,
            'It should return a parameter array with "phone" as a key');
        $this->assertArrayHasKey('address', $params,
            'It should return a parameter array with "address" as a key');
        $this->assertArrayHasKey('dob', $params,
            'It should return a parameter array with "dob" as a key');
       
        $this->assertEquals(MemberProfileTest::$validInput['firstName'], $params['firstName'],
            'It should return a parameter array with a value for key "firstName" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['middleName'], $params['middleName'],
            'It should return a parameter array with a value for key "middleName" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['lastName'], $params['lastName'],
            'It should return a parameter array with a value for key "lastName" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['email'], $params['email'],
            'It should return a parameter array with a value for key "email" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['gender'], $params['gender'],
            'It should return a parameter array with a value for key "gender" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['phone'], $params['phone'],
            'It should return a parameter array with a value for key "phone" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['address'], $params['address'],
            'It should return a parameter array with a value for key "address" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['dob'], $params['dob'],
            'It should return a parameter array with a value for key "dob" that matches the provided input');
    }
    
    public function testAccessorMethods() {
        $profile = new Profile(MemberProfileTest::$validInput);
        
        $this->assertEquals(MemberProfileTest::$validInput['firstName'],$profile->getFirstName(),
        		'It should return a value for field "firstName" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['middleName'], $profile->getMiddleName(),
            'It should return a value for field "middleName" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['lastName'], $profile->getLastName(),
            'It should return a value for field "lastName" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['email'], $profile->getEmail(),
            'It should return a value for field "email" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['gender'], $profile->getGender(),
            'It should return a value for field "gender" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['phone'], $profile->getPhoneNumber(),
            'It should return a value for field "phone" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['address'], $profile->getAddress(),
            'It should return a value for field "address" that matches the provided input');
        $this->assertEquals(MemberProfileTest::$validInput['dob'], $profile->getDOB(),
            'It should return a value for field "dob" that matches the provided input');
    }
    
    public function testNullInput() {
        $profile = new Profile(null);
    
        $this->assertInstanceOf('Profile', $profile,
            'It should create a Profile object when null input is provided');
        $this->assertCount(10, $profile->getParameters(),
            'It should have attributes when null input is provided');
        $this->assertCount(0, $profile->getErrors(),
            'It should not have errors when null input is provided');
        
        $this->assertEmpty($profile->getFirstName(),
            'It should have an empty value for the first name field of the Profile object when null input is provided');
        $this->assertEmpty($profile->getMiddleName(),
            'It should have an empty value for the middle name field of the Profile object when null input is provided');
        $this->assertEmpty($profile->getLastName(),
            'It should have an empty value for the last name field of the Profile object when null input is provided');
        $this->assertEmpty($profile->getEmail(),
            'It should have an empty value for the email field of the Profile object when null input is provided');
        $this->assertEmpty($profile->getPhoneNumber(),
            'It should have an empty value for the phone number field of the Profile object when null input is provided');
        $this->assertEmpty($profile->getGender(),
            'It should have an empty value for the gender field of the Profile object when null input is provided');
        $this->assertEmpty($profile->getAddress(),
            'It should have an empty value for the address field of the Profile object when null input is provided');
        $this->assertEmpty($profile->getDOB(),
            'It should have an empty value for the DOB field of the Profile object when null input is provided');
    }
    
    public function testNoInput() {
        $profile = new Profile();
        
        $this->assertInstanceOf('Profile', $profile,
            'It should create a Profile object when no input is provided');
        $this->assertCount(10, $profile->getParameters(),
            'It should have attributes when no input is provided');
        $this->assertCount(0, $profile->getErrors(),
            'It should not have errors when no input is provided');
        
        $this->assertEmpty($profile->getFirstName(),
            'It should have an empty value for the first name field of the Profile object when no input is provided');
        $this->assertEmpty($profile->getMiddleName(),
            'It should have an empty value for the middle name field of the Profile object when no input is provided');
        $this->assertEmpty($profile->getLastName(),
            'It should have an empty value for the last name field of the Profile object when no input is provided');
        $this->assertEmpty($profile->getEmail(),
            'It should have an empty value for the email field of the Profile object when no input is provided');
        $this->assertEmpty($profile->getPhoneNumber(),
            'It should have an empty value for the phone number field of the Profile object when no input is provided');
        $this->assertEmpty($profile->getGender(),
            'It should have an empty value for the gender field of the Profile object when no input is provided');
        $this->assertEmpty($profile->getAddress(),
            'It should have an empty value for the address field of the Profile object when no input is provided');
        $this->assertEmpty($profile->getDOB(),
            'It should have an empty value for the DOB field of the Profile object when no input is provided');
    }
    
    public function testEmptyInput() {
        $emptyInputValues = array(
            "firstName" => "",
            "middleName" => "",
            "lastName" => "",
            "email" => "",
            "gender" => "",
            "phone" => "",
            "address" => "",
            "dob" => ""
        );
        $profile = new Profile($emptyInputValues);
        
        $this->assertInstanceOf('Profile', $profile,
            'It should create a Profile object when empty input is provided');
        $this->assertCount(10, $profile->getParameters(),
            'It should have attributes when empty input is provided');
        $this->assertEquals(0, $profile->getErrorCount(),
            'It should have 1 error when empty input is provided');
     //   $this->assertEmpty($profile->getErrors(),
          //  'It should not have errors when empty input is provided');
        
        $this->assertEmpty($profile->getFirstName(),
            'It should have an empty value for the first name field of the Profile object when empty input is provided');
        $this->assertEmpty($profile->getMiddleName(),
            'It should have an empty value for the middle name field of the Profile object when empty input is provided');
        $this->assertEmpty($profile->getLastName(),
            'It should have an empty value for the last name field of the Profile object when empty input is provided');
        $this->assertEmpty($profile->getEmail(),
            'It should have an empty value for the email field of the Profile object when empty input is provided');
        $this->assertEmpty($profile->getPhoneNumber(),
            'It should have an empty value for the phone number field of the Profile object when empty input is provided');
        $this->assertEmpty($profile->getGender(),
            'It should have an empty value for the gender field of the Profile object when empty input is provided');
        $this->assertEmpty($profile->getAddress(),
            'It should have an empty value for the address field of the Profile object when empty input is provided');
        $this->assertEmpty($profile->getDOB(),
            'It should have an empty value for the DOB field of the Profile object when empty input is provided');
    }    
}
?>