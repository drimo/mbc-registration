<?php
//regadmin.php - state system for the administration section
//James Hayes

session_start();
if(!session_is_registered("SESSION"))
{
	//if session check fails, invoke error handler
	header("Location: error.php?e=2");
	exit();
}

require_once("interface.php");
require_once("dbase.class.inc");
?>

<html>
<head>
<title>Registrar Form 7 Administration</title>
<basefont face="Verdana">
</head>

<body marginewidth="0" topmargin="0" leftmargin="0" rightmargin="0">

<!--Start Header Table-->
<table class="Header" border="0" width="100%" cellspacing="0" cellpadding="1">
<tr>
<td class="Header" colspan="3">University of Dayton - Candidacy for Registration Form 7 Administration</td>
</tr>
</table>

<!-- Start Main Table -->
<table class="Header" border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="2%"> </td>
<td width="22%">
<!--Lefthand Menu-->
<?
drawMenu();
?>
</td>
<td width="1%"> </td>

<td width="73%" valign="top">

<!--Case statement...chooses what menu stuff we want and were the data should go-->
<?
if(!isset($option))
//if option is not set, send to stats page
{
	$option = 1;
}

$CClass = new CandClass;

switch($option){
	case 1:
		drawStats();
		break;
	case 2:
		genReport();
		break;
	case 3:
		drawContactView();
		break;
	case 4:
		drawFTPView();
		break;
	case 5:
		changePass();
		break;
	case 6:
		//Update System Password
		$status = $CClass->setPassword($oldPass,$newPass1,$newPass2);

		if($status == -1)
		{
			//Old Password IS Wrong
			print("Old Password does not match system password.\n");
		}
		else if($status == 0 )
		{
			print("New passwords do not match.\n");
		}
		else if($status == 1)
		{
			print("Password updated successfully.\n");
		}
		break;
	case 7:
		//Generate PDF
		$CClass->makePDF($SSN);
		drawFileLink("./files/$SSN.pdf");
		break;
	case 9:
		//Update FTP INFO
		$info[FTPPass] = $FTPPass;
		$info[FTPAccount] = $FTPAccount;
		$info[FTPHost] = $FTPHost;
		$CClass->setFTP($info);
		drawFTPView();
		break;
	case 10:
		drawFTPEdit();
		break;
	case 11:
		drawContactEdit();
		break;
	case 12:
		//Update Contact Stuff
		$info[ContactName] = $ContactName;
		$info[ContactEmail] = $ContactEmail;
		$info[ContactPhone] = $ContactPhone;
		$info[ErrorEmail] = $ErrorEmail;
		$CClass->setContact($info);
		drawContactView();
		break;
	case 13:
		//Delete Student
		deleteStudent();
		break;
	case 14:
		//Confirm Delete
		confirmDelete($SSN);

		break;
	case 15:
		//actually delete student
		$CClass->deleteCandidate($SSN);
		break;
}

?>
</td>
<td width="2%"> </td>
</tr>
</table>

<p align="right> <font size="-1"><a href="logout.php">Log Out</a></font>
</body>
</html>
