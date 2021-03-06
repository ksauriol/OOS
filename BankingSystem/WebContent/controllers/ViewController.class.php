<?php
class ViewController {
    
    public static function run($arguments) {
        if (is_null($arguments) || count($arguments) == 0) {
            self::message('Arguments expected');
            return;
        }
        
        // determine action requested
        $action = array_shift($arguments);
        
        switch ($action) {
            case 'gps': self::gps($arguments); break;
            case 'login': self::login($arguments); break;
            case 'all':
            default:
                ViewsView::show();
                return;
        }
    }
    
    private static function login($arguments) {
        $allData = array();
        $allData['profiles'] = ProfilesDB::getAllProfiles();
        LoginView::show($allData);
    }
    
    private static function gps($arguments) {
        $allData = array();
        $allData['profiles'] = ProfilesDB::getAllProfiles();
        $allData['gps'] = GPSDataDB::getAllGPSData();
        
        GPSView::show($allData);
    }
    
    private static function message($message) {
        echo "<p>$message</p>";
    }
}
?>