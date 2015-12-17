<?php

// Connexion à la base
require_once('config.inc');
$dblink=mysql_connect($host,$username,$password);
mysql_select_db($dbname);

?>
<table width=100% border=1>
	<tr>
	<td>NOMBRE D'ANNONCE PAR UTILISATEUR <hr>
			<?php
			$sql="SELECT 
`users`.*, 
count(`announcements`.`ID`) AS annonces, 
count(annonces_validated.`ID`) AS annonces_validees,  
count(annonces_pending.`ID`) AS annonces_attente  
FROM `users` 
LEFT JOIN `announcements` on (`users`.`ID`=`announcements`.`USER_ID`) 
LEFT JOIN (SELECT `announcements`.* FROM `announcements` WHERE `announcements`.`STATE_ID`='4' GROUP BY `USER_ID`) AS annonces_validated on `users`.`ID`=annonces_validated.`USER_ID`
LEFT JOIN (SELECT `announcements`.* FROM `announcements` WHERE `announcements`.`STATE_ID`='2' GROUP BY `USER_ID`) AS annonces_pending on `users`.`ID`=annonces_pending.`USER_ID`
GROUP BY `users`.`ID`
";
			//GROUP BY `users`.`ID` 
			
			$res=mysql_query($sql,$dblink); 
			echo mysql_error();
			echo "
			<table border=1>
			<tr>
			<td>ID</td>
			<td>USERNAME</td>
			<td></td>
			<td>MAIL</td>
			<td>IS_ACTIVE</td>
			<td>IS_MAIL_VERIFIED</td>
			<td>nb annonces</td>
			<td>validées</td>
			<td>en attente</td>
			</tr>
			";
			while($data = mysql_fetch_object($res)){
			//foreach ($res as $key => $value) {
				if($data->ROLE_ID==3) {$background="bgcolor='DDDDDD'";$role="PRO";}
				else {$background="";$role="";}
				echo "<tr $background>";
				
				echo "<td>$data->ID</td>";
				echo "<td>$data->USERNAME</td>";
				echo "<td>$role</td>";
				echo "<td>$data->MAIL</td>";
				echo "<td>$data->IS_ACTIVE</td>";
				echo "<td>$data->IS_MAIL_VERIFIED</td>";
				echo "<td>$data->annonces</td>";
				echo "<td>$data->annonces_validees</td>";
				echo "<td>$data->annonces_attente</td>";
				
				
				echo "<tr>";
			}
			echo "<table>";
			?>		
	</td>
	<td>ANNONCES PARTICULIERS PAR DEPARTEMENT<hr>
			<?php
			$sql="SELECT `announcements`.`DEPARTMENT_ID`, count(`announcements`.`ID`) AS annonces FROM `announcements` GROUP BY `announcements`.`DEPARTMENT_ID` ";
			
			$res=mysql_query($sql,$dblink); 
			echo mysql_error();
			echo "
			<table border=1>
			<tr>
			<td>DEPARTMENT_ID</td>
			<td>nb annonces</td>
			</tr>
			";
			while($data = mysql_fetch_object($res)){
			//foreach ($res as $key => $value) {
				echo "<tr>";
				
				echo "<td>$data->DEPARTMENT_ID</td>";
				echo "<td>$data->annonces</td>";
				
				echo "<tr>";
			}
			echo "<table>";
			?>		
	</td>	
	</tr>
	
</table>
