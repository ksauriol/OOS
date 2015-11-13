<?php
class GPSDatum extends GenericModelObject {
    
    private $arguments;
    
    private $gpsID;
    private $profileID;
    private $longitude;
    private $latitude;
    private $altitude;
    private $dateAndTime;
    
    const MAX_LONGITUDE = 50;
    const MAX_LATITUDE = 50;
    const MAX_ALTITUDE = 15;
    
    public function __construct($args = null) {
        $this->arguments = $args;
        Messages::reset();
        $this->initialize();
    }
    
    public function getProfileID() {
        return $this->profileID;
    }
    
    public function getID() {
        return $this->gpsID;
    }
    
    public function setID($newGPSID) {
        $this->gpsID = $newGPSID;
    }
    
    public function getProfileID() {
        return $this->profileID;
    }
    
    public function getLongitude() {
        return $this->longitude;
    }
    
    public function getLatitude() {
        return $this->latitude;
    }
    
    public function getAltitude() {
        return $this->altitude;
    }
    
    public function getDateAndTime() {
        return $this->dateAndTime;
    }
    
    // Returns data fields as an associative array
    public function getParameters() {
        // convert date/time output to ISO format, which should be easy to parse on the front-end
        $isoDateTime = $this->datetime->format('Y-m-d H:i');
        $isoDateTime[10] = 'T';
        $paramArray = array(
            'gpsID' => $this->gpsID,
            'profileID' => $this->profileID,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'altitude' => $this->altitude,
            'dateAndTime' => $isoDateTime
        );
        
        return $paramArray;
    }
    
    public function __toString() {
        // convert date/time output to ISO format, which should be easy to parse on the front-end
        $isoDateTime = $this->datetime->format('Y-m-d H:i');
        $isoDateTime[10] = 'T';
        $str =
            "GPS ID: [" . $this->gpsID . "]\n" .
            "Profile ID: [" . $this->profileID . "]\n" .
            "Longitude: [" . $this->longitude . "]\n" .
            "Latitude: [" . $this->latitude . "]\n" .
            "Altitude: [" . $this->altitude . "]\n" .
            "Date/Time: [" . $this->dateAndTime . "]";
        
        return $str;
    }
    
    protected function initialize() {
        $this->errorCount = 0;
        $this->errors = array();
        
        if (is_null($this->arguments)) {
            $this->gpsID = '';
            $this->profileID = '';
            $this->longitude = '';
            $this->latitude = '';
            $this->altitude = '';
            $this->dateAndTime = '';
        }
        else {
            $this->validateGPSID();
            $this->validateProfileID();
            $this->validateLongitude();
            $this->validateLatitude();
            $this->validateAltitude();
            $this->validateDateAndTime();
        }
    }
    
    private function validateGPSID() {
        $this->gpsID = $this->extractForm($this->arguments, "gpsID");
    }
    
    private function validateProfileID() {
        $this->profileID = $this->extractForm($this->arguments, "profileID");
    }
    
    private function validateLongitude() {
        $this->longitude = $this->extractForm($this->arguments, "longitude");
        if (empty($this->longitude)) {
            $this->setError("longitude", "LONGITUDE_EMPTY");
            return;
        }
        
        if (strlen($this->longitude) > self::MAX_LONGITUDE) {
            $this->setError("longitude", "LONGITUDE_TOO_LONG");
            return;
        }
    }
    
    private function validateLatitude() {
        $this->latitude = $this->extractForm($this->arguments, "latitude");
        if (empty($this->latitude)) {
            $this->setError("latitude", "LATITUDE_EMPTY");
            return;
        }
        
        if (strlen($this->latitude) > self::MAX_LATITUDE) {
            $this->setError("latitude", "LATITUDE_TOO_LONG");
            return;
        }
    }
    
    private function validateAltitude() {
        $this->altitude = $this->extractForm($this->arguments, "altitude");
        if (empty($this->altitude)) {
            $this->setError("altitude", "ALTITUDE_EMPTY");
            return;
        }
        
        if (strlen($this->altitude) > self::MAX_ALTITUDE) {
            $this->setError("altitude", "ALTITUDE_TOO_LONG");
            return;
        }
    }
    
    private function validateDateAndTime() {
        // the date and time may be present as a single value or as separate values
        if (array_key_exists('dateAndTime', $this->formInput)) {
            $datetime = $this->extractForm($this->formInput, "dateAndTime");
            list($date, $time) = preg_split("/ /", $datetime);
        } else {
            $date = $this->extractForm($this->formInput, "date");
            $time = $this->extractForm($this->formInput, "time");
        }
        $this->datetime = new DateTime();
        
        if (empty($date)) {
            $this->setError("dateAndTime", "DATE_EMPTY");
            return;
        }
        
        if (empty($time)) {
            $this->setError("dateAndTime", "TIME_EMPTY");
            return;
        }
        
        $options = array("options" => array("regexp" => "/^((\d{4}[\/-]\d\d[\/-]\d\d)|(\d\d[\/-]\d\d[\/-]\d{4}))$/"));
        if (!filter_var($date, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("dateAndTime", "DATE_HAS_INVALID_CHARS");
            return;
        }
        
        $options = array("options" => array("regexp" => "/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?( ((am|pm)|(AM|PM)))?$/"));
        if (!filter_var($time, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("dateAndTime", "TIME_HAS_INVALID_CHARS");
            return;
        }
        
        try { $dt = new DateTime($date . ' ' . $time); }
        catch (Exception $e) {
            $this->setError("dateAndTime", "DATE_AND_TIME_INVALID");
            return;
        }
        
        $this->datetime = $dt;
    }
}
?>