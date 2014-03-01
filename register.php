<?php
// register.php - state machine controlling registration process
//11-29-04-modified to send a different env. header.
//Modified 11-22-04 by Matthew Hancock
// *changed mail command to add from address to emails
//Modified 11-29-04 by Ed Frey
// *change mail command to add envelope address

require_once("mysql.php");
require_once("interface.php");
require_once("FormValidator.class.inc");
require_once("utility.php");
?>

<head>
<title>Merit Badge College Troop Registration</title>
<link rel=stylesheet type=text/css href=style.css>
</head>
<body marginewidth="0" topmargin="0" leftmargin="0" rightmargin="0">


<!--Start Header Table-->
<table class="Header" align="center" colspan="3" width="100%">
<tr>
	<td class="Header">Epsilon Tau Pi - Merit Badge College Troop Registration</td>
</tr>
</table>
<!--Start Main Table-->
<table class="Standard" border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
<td align="center" width="100%">
<?

//Do validation if not the first time through
if(isset($running))
{
	//validate input data
	$fv = new FormValidator;

	$fv->isEmpty("c_name","Contact Name field requires an entry.");
	$fv->isEmpty("c_email","Contact email field requires an entry.");
	$fv->isEmpty("c_phone","Contact Phone field requires an entry.");
	$fv->isEmpty("c_addr1","Contact Address Line 1 field requires an entry.");
	$fv->isEmpty("c_city","Contact City field requires an entry.");
	$fv->isEmpty("c_state","Contact State field requires an entry.");
	$fv->isEmpty("c_zip","Contact Zip Code field requires an entry.");

	$errors = $fv->getErrorList();

	if(sizeof($errors) == 0 )
	//Go into the second round of testing
	{
		$fv->isEmailAddress("c_email","Email Address Is not valid.");
		$fv->isZIP("c_zip","Contact Zip code is not valid.");
		$fv->isPhone("c_phone","Contact phone number is not valid.");
		$fv->isNumber("troop","Troop number is not valid.");

		$errors = $fv->getErrorList();
	}
}

if(!isset($running))
{
	//Draw form without error checking
	drawRegForm($contact);
}
else if(sizeof($errors) > 0) //there are errors
{
	//Draw form with error checking
	drawErrors($errors);
	drawRegForm($contact);
}
else
{
	//generate password
	$contact[password] = genRandomPass(7);
	//Data is good, put it in the dbase, email recipiant, then give success
	$data[0] = "Registration Result";

	if(add_contact($contact)){

		$data[1] = "Your information has been added to the database.</br>";
		//email user password
		$emailMessage = "Thank you for registering troop $troop for Epsilon Tau Pi Merit badge college.  Your password is: $contact[password]

Logon to update your scout roster and select classes at http://www.etpevents.org/mbc

To sign up to receive updates through email, go to http://listserv.epsilontaupi.org/mailman/listinfo/mbc_info and follow the directions under 'Subscribing to Mbc_info'.

Sincerely,
The ETP MBC Team";
		$emailSubject = "Merit Badge College Registration";

		//MH 11-22-04
		//added from field to set proper address for email messages
		//if(mail($c_email,$emailSubject,$emailMessage)){    //original
		//m.hancock - 11/9/2010 3:13:48 PM - Modified mail function to use new header information.
		$headers = 'From: mbc_committee@epsilontaupi.org' . "\r\n" .
		'Reply-To: mbc_committee@epsilontaupi.org' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		if(mail($c_email, $emailSubject, $emailMessage, $headers))
		{
			$data[1] = "$data[1] An email has been sent to $c_email with your password for troop $troop.  Please use that to log in to register your scouts for Merit Badge College.";
		}
		else{
			$data[1] = "$data[1] We were unable to send you your conformation email at this time.  Please come back and select \"Need Password\" from the options in the main menu.";

		}
	}
	else{
		$data[1] = "There was a problem adding your information to the database.  Your troop has probably already registered.  Please contact us for further information or try again later.";
	}

	$data[1] = "$data[1]</br><a href='homepage.php'>Click here to return home.</a>";

	drawBox($data);

}
?>
</td>
</tr>
</table>
</body>
</html>
