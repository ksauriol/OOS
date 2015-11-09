<?php
class View{
	public static function run() {
		$profiles = ProfilesDB::getAllProfiles();
		$accounts = AccountsDB::getAllAccounts();
		echo "<!DOCTYPE html><html><head>";
		echo "<h1>BankSystem profile list</h1>";
		echo "<table>";
		echo "<thead>";
		echo "<tr><th>Profile Id</th><th>Name</th> <th>Email</th><th>SSN</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		
		foreach($profiles as $profile) {
			echo '<tr>';
			echo '<td>'.$profile->getProfileID().'</td>';
			echo '<td>'.$profile->getFirstName().'</td>';
			echo '<td>'.$profile->getEmail().'</td>';
			echo '<td>'.$profile->getSSN().'</td>';
			echo '</tr>';
			$personalAccounts = AccountsDB::getAccountsBy('profileID', $profile->getProfileID()); 
			if (!empty($personalAccounts)){
				echo "<tr><td></td><td></td><td></td><td></td><th>Account Id</th><th>Profile ID</th><th>SSN</th></tr>";
				foreach ($personalAccounts as $acc){
					if (!is_null($acc)){
					//	print_r('<br>'.$acc);
						echo '<tr><td></td><td></td><td></td><td></td>';
						echo '<td>'.$acc->getAccountID().'</td>';
						echo '<td>   '.$acc->getProfileID().'</td>';
						echo '<td>   '.$acc->getSSN().'</td>';
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
		echo "<tr><td></td><th>Account Id</th><td></td><th>Profile ID</th><th>SSN</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		foreach ($accounts as $acc){
			echo '<tr><td></td>';
			echo '<td>'.$acc->getAccountID().'</td><td></td>';
			echo '<td>'.$acc->getProfileID().'</td>';
			echo '<td>   '.$acc->getSSN().'</td>';
			echo '</tr>';
		}
		echo "</tbody>";
		echo "</table>";
		echo "</body></html>";
	}
}