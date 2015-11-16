<?php
class BankController {

  public static function run($arguments = null) {

                // determine requested action
                $action = array_shift($arguments);
                //  print_r($arguments);
                //$_SESSION['arguments'] = $arguments;
                switch ($action) {
                        case 'create': self::createAccount($arguments);break;
                        default:View::run();
                                // self::echoMessage('failed', "$action is not a valid action for profiles.");
                                return;
                }

                View::run();
        }

        public static function createAccount($arguments){
                $hardCode = array("profileID", "SSN", "firstName","middleName","lastName","address","tel");
                $parts =  GenericInput::stripInput($arguments[0], $hardCode);
                //print_r($parts[0].'<br>'.$parts[1]);
                $num =intval($parts[0]);
                $parts[0] =$num;
               // print_r((intval($parts[0])).'<br>'.$parts[1]);
				
                 if ($parts[0]<0){
                        print_r("Error. accountID cannot be smaller or equal to 0<br>");
                } else if ($parts[0]>999999999){
                        print_r("Error. accountID cannot be that large<br>");
                } else
                        $account = AccountInfoDB::getAccountsBy('accountID', $parts[0]);
                 print_r($parts[1].'<br>'.$parts[2]);
                if (is_null($account[0])){
                        $param = array();
                        $param['accountID'] = $parts[0];
                        $param['SSN'] = intval($parts[2]);
                        if (!is_null($parts[3]))
                        	$param['firstName'] = $parts[3];
                        if (!is_null($parts[4]))
                        	$param['middleName'] = $parts[4];
                        if (!is_null($parts[5]))
                        	$param['lastName'] = $parts[5];
                        if (!is_null($parts[6]))
                        	$param['address'] = $parts[6];
                        if (!is_null($parts[7]))
                        	$param['tel'] = $parts[7];
                   //     print_r(($parts[0]).'*<br>**'.$parts[1].'<br>***'.$parts[2]);
                        $ProfileDB= ProfilesDB::getProfileBy('profileID',intval($parts[1]));
                        if (!is_null($ProfileDB)){
                          //    print_r($account[0]->getProfileID()."***".$account[0]->getAccountID());
                          //      if (is_null($account[0]->getProfileID())){
                                $param['profileID'] =  $ProfileDB->getProfileID();
                                $param['SSN'] = $ProfileDB->getSSN();
                                $account = new Account($param);
                                $accountInfo = new AccountInfo($param);
                                AccountInfoDB::addAccount($accountInfo);
                                AccountsDB::addAccount($account);
                              
                        //      }else{
                        //              print_r("Error. Bank Account already has owner");
                        //      }
                        } else {
                        	//print_r( ($param['SSN']+3).'+<br>');
                        	$ProfileDB= ProfilesDB::getProfileBy('SSN',$param['SSN']);
                       // 	print_r($ProfileDB->getProfileID().'&&<br>');
                        	if (is_null($ProfileDB)){
                        //        $account = new Account($param);
                                $accountInfo = new AccountInfo($param);
                            //    print_r( $account->getSSN().'+<br>');
                                AccountInfoDB::addAccount($accountInfo);
                            //    AccountsDB::addAccountNoOwner($account);
                            
                        	} else print_r("ERROR. Profile with that SSN already exists");
                        }
              } else print_r("Error. This Bank Account is already created<br>");
      }
}
?>
          