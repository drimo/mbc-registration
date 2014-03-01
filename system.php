<?php
//system.php - controller for mbc system, for user level interaction
//James Hayes
//Modified 11-22-04 by Matthew Hancock
//	*added export to excel spreadsheet menu

session_start();
if(!session_is_registered("SESSION"))
{
	//if session check fails, invoke error handler
	header("Location: error.php?e=2");
	exit();
}

require_once("interface.php");
require_once("mysql.php");


//actions that must occur first
switch($action){
	case 1://change troop
		if(isset($_SESSION['admin'])){
			$_SESSION['troop'] = $newtroop;

		}
		break;
	case 2: //add class
		if(isset($_SESSION['admin'])){
			addClass($name,$section,$capacity,$teach,$room,$period,$length);
		}
		break;
	case 3: //add session
		if(isset($_SESSION['admin'])){
			addSession($new_year,$new_chair);
		}
		break;
	case 4: //delete class
		if(isset($_SESSION['admin'])){
			deleteClass($id);
		}
		break;
	case 5: //add scout
		//m.hancock - 11/10/2007 - Added tshirt field
  
		addScout($f_name,$l_name,$tshirt,$_SESSION['troop']);
		break;
	case 6: //delete scout
		deleteScout($id);
		break;
	case 7: //remove scout from current roster
		removeScout($id,$_SESSION['year']);
		break;
	case 8: ///reserved
		break;

	case 9:
		updateRegisterScout($id,$_SESSION['year'],$c1,$c2,$c3);
		break;
	case 10:
		registerScout($id,$_SESSION['year'],$c1,$c2,$c3);
		break;
	case 11:
		editClass($name,$section,$capacity,$teach,$room,$period,$length,$id);
		break;
	case 12://reserved
		break;
	case 13://update registration info
		update_contact($contact);
		break;
	case 14:
		//m.hancock - 11/2/2005 - Open / Close Registration
		toggleRegistration($status);
	default:
		break;
}

?>

<html>
<head>
<title>etpevents.org - Merit Badge College Scout Registration</title>
<link rel=stylesheet type=text/css href=style.css>
</head>

<body>

<!--Start Header Table-->
<table class="Header" border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td class="Header" colspan="3">Merit Badge College Scout Registration</td>
</tr>
</table>

<!-- Start Main Table -->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="2%"> </td>
<td valign='top' width="22%">
<!--Lefthand Menu-->
<?

//m.hancock - 11/2/2005 - Display registration status
drawRegistrationStatus();

if(isset($_SESSION['admin'])) drawAdminNotify();
drawCurrentTroop($_SESSION['troop']);
drawCurrentYear($_SESSION['year']);
drawMenu();
if(isset($_SESSION['admin']))
{
	drawAdminMenu();
	drawAdminExportMenu();
}
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


switch($option){
	case 1:
		drawInWelcome();
		drawStatistics($_SESSION['troop'],$_SESSION['year']);
		break;
	case 2:
		if(troopExists($_SESSION['troop']))
		{
			 drawTroopRoster($_SESSION['troop']);
			drawAddScout();
		}
		break;
	case 3:
		if(troopExists($_SESSION['troop'])){
			drawRegisterScout();
			if($action == '8'){
				drawUpdateScoutReg($id,$_SESSION['year']);
			}
			drawListRegistered($_SESSION['year'],$_SESSION['troop']);
		}
		break;
	case 4:
		if(troopExists($_SESSION['troop'])){
			getContactInfo($_SESSION['troop'],&$info);
			 drawRegEditForm($info);
		}
		break;
	case 5:
		if(isset($_SESSION['admin'])){
			drawAddClass();
		}
		break;
	case 6:
		if(isset($_SESSION['admin'])){
			drawSelectTroop();
			drawTroopList($_SESSION['year']);
		}
		break;
	case 7:
		if(isset($_SESSION['admin'])){
			drawAddClass();
			if($action == '12')
				drawEditClass($id);
			drawListClasses();
		}
		break;
	case 8:
		if(isset($_SESSION['admin'])){
			drawAddSession();
		}
		break;
	//MH 11-22-04
	case 9:
		if(isset($_SESSION['admin'])){
			//specific class
			drawExportSpecificClass();
		}
		break;
	//MH 11-22-04
	case 10:
		if(isset($_SESSION['admin'])){
			//specific troop
			drawExportSpecificTroop();
		}
		break;
	 //m.hancock - 11/2/2005
	case 11:
		if(isset($_SESSION['admin'])){
			//open / close registration
			drawToggleRegistration();
		}
		break;
	//m.hancock - 11/2/2005
	case 12:
		if(troopExists($_SESSION['troop']))
			drawListRegistered($_SESSION['year'],$_SESSION['troop']);
		break;
	default:
		break;
}



?>
</td>
<td width="2%"> </td>
</tr>
<tr>
<td> </td>
<td colspan='3'>
<?drawContactInfo();?>
</td>
</tr>
</table>
</body>
</html>
