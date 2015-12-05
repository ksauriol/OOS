<?php
class View{
	public static function run() {
		$profiles = ProfilesDB::getAllProfiles();
		$accounts = AccountsDB::getAllAccounts();
		echo "<!DOCTYPE html><html><style type=\"text/css\">a { font-size: 1.3em; }</style><head></head><body>";
		?>
        Other Views: <a href="/BankingSystem/view/login">Login View</a> | <a href="/BankingSystem/view/gps">GPS View</a>
        <?php
		echo "<h1>BankSystem profile list</h1>";
		echo "<table>";
		echo "<thead>";
		echo "<tr><th>ProfilID</th><th>AccountID</th><th>Email</th> <th>Password</th><th>isLoggedIn</th><th>isEmployee</th><th>isPasswordChanged</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		
		foreach($profiles as $profile) {
			echo '<tr>';
			echo '<td>'.$profile->getProfileID().'</td>';
			echo '<td>'.$profile->getAccountID().'</td>';
			echo '<td>'.$profile->getEmail().'</td>';
			echo '<td>'.$profile->getPassword().'</td>';
			echo '<td>'.$profile->isLoggedIn().'</td>';
			echo '<td>'.$profile->isEmployee().'</td>';
			echo '<td>'.$profile->isPasswordChanged().'</td>';
			echo '</tr>';
			$personalAccounts = AccountsDB::getAccountsBy('bankID', $profile->getAccountID()); 
			if (!empty($personalAccounts)){
				echo "<tr> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
				        <th>Account Id</th>
				        <th>SSN</th>
				        <th>First Name</th>
				        <th>Last Name</th>
				        <th>Balance</th>
				      </tr>";
				foreach ($personalAccounts as $acc){
					if (!is_null($acc)){
					//	print_r('<br>'.$acc);
						echo '<tr> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>';
						echo '<td>   '.$acc->getAccountID().'</td>';
						echo '<td>   '.$acc->getSSN().'</td>';
						echo '<td>   '.$acc->getFirstName().'</td>';
						echo '<td>   '.$acc->getLastName().'</td>';
						echo '<td>   '.$acc->getBalance().'</td>';
						echo '</tr>';
					}
				}
			}
		}
		echo "</tbody>";
		echo "</table>";
		echo "<br><br>";
		echo "<h1>BankSystem Account list</h1>";
		echo "<table>";
		echo "<thead>";
		echo "<tr><td></td><th>Account Id</th><td></td><th>First Name</th><th>Last Name</th><th>Balance</th><th>SSN</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		foreach ($accounts as $acc){
			echo '<tr><td></td>';
			echo '<td>'.$acc->getAccountID().'</td><td></td>';
			echo '<td>'.$acc->getFirstName().'</td>';
			echo '<td>'.$acc->getLastName().'</td>';
			echo '<td>'.$acc->getBalance().'</td>';
			echo '<td>'.$acc->getSSN().'</td>';
			echo '</tr>';
		}
		echo "</tbody>";
		echo "</table>";
		echo "</body></html>";
	}
}