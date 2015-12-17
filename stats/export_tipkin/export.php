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
			$sql="SELECT `users`.*, count(`announcements`.`ID`) AS annonces FROM `users` LEFT JOIN `announcements` on (`users`.`ID`=`announcements`.`USER_ID`) GROUP BY `users`.`ID` ";
			
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
			<td>Mailing liste</td>
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
				echo "<td>$data->MAILINGSTATE</td>";
				
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

<table width=100% border=1>
	<tr>
	<td>Mailing liste <hr>
			<?php
			$sql="SELECT USERNAME,MAIL FROM `users` WHERE `MAILINGSTATE`=1";
			
			$res=mysql_query($sql,$dblink); 
			echo mysql_error();
			echo "
			<table border=1>
			<tr>
			<td>USERNAME</td>
			<td>MAIL</td>
			</tr>
			";
			while($data = mysql_fetch_object($res)){
			//foreach ($res as $key => $value) {
				echo "<td>$data->USERNAME</td>";
				echo "<td>$data->MAIL</td>";
				
				echo "<tr>";
			}
			echo "<table>";
			?>		

