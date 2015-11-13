<?php
/* 
 * Expected call format (preceded with http://hostname/BankingSystem):
 * /gps/add?email=EMAIL&password=PASSWORD&latitude=LATITUDE&longitude=LONGITUDE&altitude=ALTITUDE&dateAndTime=DATEANDTIME
 * /gps/edit?email=EMAIL&password=PASSWORD&gpsid=GPSID&latitude=LATITUDE&longitude=LONGITUDE&altitude=ALTITUDE&dateAndTime=DATEANDTIME
 * /gps/delete?email=EMAIL&password=PASSWORD&gpsid=GPSID
 * /gps/getall?email=EMAIL&password=PASSWORD
 * 
 * Example add string:
 * /gps/add?email=bob@email.com&password=pass123&latitude=32.03565623929663&longitude=-98.07892084121704&altitude=48.07584934121844&dateAndTime=2015-11-12T23:45:25
 * Example edit string:
 * /gps/edit?email=bob@email.com&password=pass123&gpsid=3&latitude=22.03565623929663&longitude=-88.07892084121704&altitude=33.07584934121844&dateAndTime=2015-11-22T23:44:25
 * Example delete string:
 * /gps/delete?email=bob@email.com&password=pass123&gpsid=3
 * Example getall string:
 * /gps/getall?email=bob@email.com&password=pass123
 */

class GPSController {
    
    // codes for reply messages
    const CODE_SUCCESS = 200;
    const CODE_BAD_REQUEST = 400;
    const CODE_UNAUTHORIZED = 401;
    const CODE_NOT_FOUND = 404;
    const CODE_INTERNAL_SERVER_ERROR = 500;
    
    // arguments expected: ...
    public static function run($arguments = null) {
        if (!isset($_GET) || empty($_GET) || is_null($arguments) || empty($arguments)) {
            self::outputMessage(self::CODE_BAD_REQUEST, 'Wrong number of arguments received', 'Arguments expected: ...');
            return;
        }
        
        // determine action requested
        $action = array_shift($arguments);
        
        switch (strtolower($action)) {
            case 'add': self::add(); break;
            case 'edit': self::edit(); break;
            case 'delete': self::delete(); break;
            case 'getall': self::getAll(); break;
            default:
                self::outputMessage(self::CODE_BAD_REQUEST, 'Action argument invalid', "The first argument should be either 'add', 'edit', 'delete', or 'getall'. Argument '$action' invalid.");
                return;
        }
    }
    
    private static function add() {
        $profileID = 3;                   // TODO verify email and password, and retreive associated profileID
        $_GET['profileID'] = $profileID;
        
        // create new GPSDatum object
        $gps = new GPSDatum($_GET);
        if ($gps->getErrorCount() > 0) {
            self::outputMessage(self::CODE_BAD_REQUEST, 'GPS data invalid', array_shift($gps->getErrors()));
            return;
        }
        
        // add new GPS datum to database and store the auto-assigned gpsID
        $gpsID = GPSDataDB::addGPS($gps);
        if ($gps->getErrorCount() > 0) {
            self::outputMessage(self::CODE_INTERNAL_SERVER_ERROR, 'Failed to add GPS data', $gpsID);
            return;
        }
        $gps->setID($gpsID);
        
        // return success message, which includes a JSON copy of the GPSDatum object
        self::outputMessage(self::CODE_SUCCESS, 'GPS data added successfully', 'GPS data added successfully', $gps);
    }
    
    private static function edit() {
        $profileID = 3;                   // TODO verify email and password, and retreive associated profileID
        $_GET['profileID'] = $profileID;
        
        // check for gpsid argument (because the GPSDatum constructor doesn't throw an error when gpsid is missing)
        if (!isset($_GET['gpsid'])) {
            self::outputMessage(self::CODE_BAD_REQUEST, 'Missing GPS ID', 'Argument gpsid expected for editing a GPS entry');
            return;
        }
        
        // create new GPSDatum object
        $gps = new GPSDatum($_GET);
        if ($gps->getErrorCount() > 0) {
            self::outputMessage(self::CODE_BAD_REQUEST, 'GPS data invalid', array_shift($gps->getErrors()));
            return;
        }
        
        // edit GPS entry in database
        $return = GPSDataDB::editGPS($gps);
        if ( !($return instanceof GPSDatum) || $gps->getErrorCount() > 0) {
            self::outputMessage(self::CODE_INTERNAL_SERVER_ERROR, 'Failed to edit GPS data', $return);
            return;
        }
        
        // return success message, which includes a JSON copy of the newly edited GPSDatum object
        self::outputMessage(self::CODE_SUCCESS, 'GPS data edited successfully', 'GPS data edited successfully', $gps);
    }
    
    private static function delete() {
        
    }
    
    // outputs a json-encoded response according to the RESTful API (I think)
    private static function outputMessage($code, $cause, $description, $data = null) {
        $object = new stdClass();
        $object->meta = new stdClass();
        $object->meta->code = $code;
        $object->meta->cause = $cause;
        $object->meta->description = $description;
        $object->data = $data; // specific to endpoints. may be null for some endpoints.
        $json = json_encode($object, JSON_PRETTY_PRINT);
        echo '<pre>' . $json . '</pre>';
    }
    
}

?>