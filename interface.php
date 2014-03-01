<?php
// interface.php - contains functions for drawing form and table elements of main pages
// James Hayes
// 12-16-02
// Modified 11-22-04 by Matthew Hancock
//  *added export to excel spreadsheet

include "export.php";

function drawBox($data)
//data is an array of the following format: 0 is the header, 1 is the main data row 1, 2 is row two, etc
{
	print "<table class='S_MENU' width='100%'>\n<tr>\n<td>\n";
	print "<table class='S_MENU_HEAD' width='100%'>\n";
	print "<tr>\n<td class='StandardHeader'>\n";
	print $data[0] . "\n";
	print "</td>\n</tr>\n</table>\n";

	print "<table class='S_MENU_BODY' width='100%'>\n";
	print "<tr>\n<td class='StandardHeader'>\n";
	print "</td>\n</tr>\n";

	$size = sizeof($data);

	for($i = 1;$i < $size;$i++)
	{
		print "<tr>\n<td class='StandardBlock'>\n";
		print $data[$i] . "\n";
		print "	</td>\n</tr>\n";
	}
	print "</table>\n";
	print "</td>\n</tr>\n</table>\n";
}

function drawAddScout(){
	$data[0] = "Add Scout";
	$data[1] = "<form method='POST' action='system.php'>
	<input type='hidden' name='option' value='2'>
	<input type='hidden' name='action' value='5'>
	First Name <input type='text' name='f_name'> 
	Last Name <input type='text' name='l_name'>
	<input type='submit' value='add'></form>";
	//m.hancock - 11/10/2007 - Added combo for t-shirt selection (child's small to adult XL)
	// n.fahrig - 11/10/2010 - T-shirts are currently not being offered at MBC any more, commented out below section
	#$data[1] = "$data[1] 
	#T-Shirt <select name='tshirt'>
	#	<option value = 'None' selected>None</option>
	#	<option value = 'Child Small'>Child Small</option>
	#	<option value = 'Child Medium'>Child Medium</option>
	#	<option value = 'Child Large'>Child Large</option>
	#	<option value = 'Adult Small'>Adult Small</option>
	#	<option value = 'Adult Medium'>Adult Medium</option>
	#	<option value = 'Adult Large'>Adult Large</option>
	#	<option value = 'Adult X-Large'>Adult X-Large</option>
	#	</select>
	#</br>
	

	drawBox($data);
}


function drawAddClass(){
	$data[0] = "Add Class";
	$data[1] = "<form method='POST' action='system.php'>
	<input type='hidden' name='option' value='7'>
	<input type='hidden' name='action' value='2'>
	Name <input type='text' name='name'> </br>
	section <input type='text' name='section' size='1'></br>
	Capacity <input type='text' name='capacity' size='3'> </br>
	Teacher <input type='text' name='teach'></br>
	Room <input type='text' name='room'></br>
	Period <select name='period'><option>1<option>2<option>3</select></br>
	Length <select name='length'><option>1<option>2<option>3</select></br>
	<input type='submit' value='add'></form>";
	drawBox($data);
}


function drawEditClass($id){
	getClassInfo($id,$info);

	$data[0] = "Edit Class";
	$data[1] = "<form method='POST' action='system.php'>
	<input type='hidden' name='option' value='7'>
	<input type='hidden' name='action' value='11'>
	<input type='hidden' name='id' value='$id'>
	<input type='hidden' name='period' value='$info[period]'>
	<input type='hidden' name='length' value='$info[length]'>
	Name <input type='text' name='name' value='$info[name]'></br>
	Section <input type='text' name='section' size='1' value='$info[section]'></br>
	Capacity <input type='text' name='capacity' size='3' value=$info[capacity]></br>
	Teacher <input type='text' name='teach' value='$info[teacher]'></br>
	Room <input type='text' name='room' value='$info[room]'></br>
	<input type='submit' value='update'></form>";
	drawBox($data);
}


function drawAdminMenu()
{
	$data[0] = "Admin Menu";
	$data[1] = "<a href='system.php?option=6'>Select Troop</a>";
	$data[2] = "<a href='system.php?option=8'>Add Session</a>";
	$data[3] = "<a href='system.php?option=7'>Edit Classes</a>";
	$data[4] = "<a href='system.php?option=11'>Open / Close Registration</a>";
	drawBox($data);
}


function drawAdminNotify()
{
	$data[0] = "Admin Mode";
	drawBox($data);
}


function drawAddSession()
{
	$data[0] = "Add Session";
	$data[1] = "<form method='POST' action='system.php'>
<input type='hidden' name='option' value='8'>
<input type='hidden' name='action' value='3'>
Year <input type='text' name='new_year' length='4'> Chair Person  <input type='text' name='new_chair'></br>
<input type='submit' value='add'></form>";
	drawBox($data);

}


function drawClassRoster($classID,$year){

}

function drawContactInfo()
{
	$data[0] = "Contact Information";
	$data[1] = "You can contact us via our email at <a href='mailto:mbc_committee@epsilontaupi.org'>mbc_committee@epsilontaupi.org</a>.";
	drawBox($data);
}


function drawCurrentTroop($troop){
	$data[0] = "Troop $troop";

	drawBox($data);
}


function drawCurrentYear($year){
	$data[0] = "Current Year: $year";

	drawBox($data);
}


function drawHome()
{
	$data[0] = "Merit Badge College Home";
	$data[1] = "First, add your scouts to the roster user edit roster.  Second, click register for MBC to add classes to your scouts' schedules.</br>";

	drawBox($data);
}


function drawListClasses()
//$classes: id name section capacity teacher
{
	$data[0] = "Class List";

	$query = "select * from class order by name";
	$result = mysql_query($query);
	$data[1] = "<table class='list' width='100%'>\n";
	$data[1] = "$data[1] <tr><td>Class Name</td><td>Section</td><td>Capacity</td><td>Current</td><td>Teacher</td><td>Room</td><td>Period</td><td>Length</td</tr>";

	while($classes=mysql_fetch_array($result)){
		$reged = getClassRegNum($_SESSION['year'],$classes[id]);
		$data[1] = "$data[1] <tr>\n<td>$classes[name]</td><td> $classes[section]</td><td> $classes[capacity]</td><td>$reged</td><td> $classes[teacher]</td><td>$classes[room]</td><td>$classes[period]</td><td>$classes[length]</td><td><a href='system.php?option=7&id=$classes[id]&action=12'>Edit</a></td><td><a href='system.php?option=7&id=$classes[id]&action=4'>Delete</a></td>\n</tr>\n";
	}
	$data[1] = "$data[1] </table>\n";
	drawBox($data);
}



function drawListRegistered($year,$troop)
{
	$data[0] = "Scouts Registered for $year";

	if(!troopExists($troop)){
		$data[1] = "Troop $troop does not exist in database.";
	}
	else{

	$query = "select * from student,register where student.troop='$troop' && register.year='$year' && student.id=register.student_id order by l_name";
	$result = mysql_query($query);
	$data[1] = "<table class='list' width='100%'>\n";
	$data[1] = "$data[1] <tr><td>Last Name</td><td>First Name</td><td>Class 1</td><td>Class 2</td><td>Class 3</td></tr>";

	while($info=mysql_fetch_array($result)){
		$c1 = getClassName($info[class1_id]);
		$c2 = getClassName($info[class2_id]);
		$c3 = getClassName($info[class3_id]);

		$data[1] = "$data[1] <tr>\n<td>$info[l_name]</td><td>$info[f_name]</td><td> $c1</td><td> $c2</td><td>$c3</td>
	<td><a href='system.php?option=3&id=$info[student_id]&action=7'>Remove</a></td>
	<td><a href='system.php?option=3&id=$info[student_id]&action=8'>Edit</a></td>\n</tr>";

	}
	$data[1] = "$data[1] </table>\n";
	}
	drawBox($data);
}


function drawLogin()
{
	$data[0] = "Merit Badge College Login";
	$data[1] = "<form method='POST' action='login.php'></br>
	Troop <input type='text' name='troop'> Password <input type='password' name='password'></br>
	<input type='submit' value='login'>";

	drawBox($data);

}


function drawMenu()
{
	$data[0] = "Merit Badge College Menu";

	//m.hancock - 11/2/2005
	//We need to check if registration is open.
	//If open, allow all actions. If closed, only allow a subset of actions.
	//In either case, if admin, all actions are allowed.
	if(isRegistrationOpen() || isset($_SESSION['admin'])) //Registration is open or we're an admin
	{
	        $data[1] = "<a class='StandardRef' href='system.php?option=1'>Home</a>";
		$data[2] = "<a class='StandardRef' href='system.php?option=2'>Troop Roster</a>";
		$data[3] = "<a class='StandardRef' href='system.php?option=3'>Register For Classes</a>";
		$data[4] = "<a class='StandardRef' href='system.php?option=4'>Update Contact Information</a>";
		$data[5] = "<a class='StandardRef' href='system.php?option=12'>View Schedule</a>";
		$data[6] = "<a class='StandardRef' href='logout.php'>Logout</a>";
	}
	else //Registration is closed
	{
	        $data[1] = "<a class='StandardRef' href='system.php?option=1'>Home</a>";
		$data[2] = "<a class='StandardRef' href='system.php?option=4'>Update Contact Information</a>";
		$data[3] = "<a class='StandardRef' href='system.php?option=12'>View Schedule</a>";
		$data[4] = "<a class='StandardRef' href='logout.php'>Logout</a>";
	}

	drawBox($data);
}


function drawFileLink($file)
{
	if(file_exists($file))//check if the file exists for the student number
	{

		$data[0] = "Link to File (right click to save)";
		$data[1] = "<a target='_blank' href='$file'>$file</a>";
		drawBox($data);
	}
	else
	{
		$data[0] = "An error has occurred.";
		$data[1] = "File could not be created.  Please ensure the social security number entered is correct and try again.";
		drawBox($data);
		genReport();
	}

}


function drawSelectTroop(){
	$data[0] = "Select Troop";
	$data[1] = "<form method='POST' action='system.php'>
	<input type='hidden' name='option' value='6'>
	<input type='hidden' name='action' value='1'>
	Troop <input type='text' name='newtroop'></br>
	<input type='submit' value='Select'></form>";
	drawBox($data);

}



function drawTroopRoster($troop)
{
	$data[0] = "Troop Roster";

	$query = "select * from student where troop='$troop' order by l_name";
	$result = mysql_query($query);
	$data[1] = "<table class='list' width='100%'>\n";
	//m.hancock - 11/10/2007 - Added t-shirt selection
	// n.fahrig -11/10/2010 - removed t-shirt selection insert after first name to put back:  <td>T-Shirt</td>
	$data[1] = "$data[1] <tr><td>Last Name</td><td>First Name</td><td></td></tr>";

	while($scouts=mysql_fetch_array($result)){
		$data[1] = "$data[1] <tr>\n
		<td>$scouts[l_name]</td>
		<td> $scouts[f_name]</td>
		//<td>$scouts[tshirt]</td>
		<td><a href='system.php?option=2&id=$scouts[id]&action=6'>Delete</a></td>
		\n</tr>\n";
	}
	$data[1] = "$data[1] </table>\n";
	drawBox($data);

}


function drawTroopList($year){
	$data[0] = "Troop List";
	getTroopInfo($year,&$info);

	$t_num_roster = 0;
	$t_num_reg = 0;
	$t_num_troops = sizeof($info);

	$data[1] = "
<table>
<tr><td>Troop Number</td><td>Contact Person</td><td>Email</td><td># Scouts on Roster</td><td># Scouts Registered</td></tr>";

	for($i=0;$i<sizeof($info);$i++){
		$troop 		= $info[$i][troop];
		$c_name 	= $info[$i][c_name];
		$email		= $info[$i][c_email];
		$num_scouts	= $info[$i][num_scouts];
		$t_num_roster+=$num_scouts;
		$cur_session	= $info[$i][cur_session];
		$t_num_reg+=$cur_session;
		$data[1] = "$data[1] <tr><td>$troop</td><td>$c_name</td><td>$email</td><td>$num_scouts</td><td>$cur_session</td></tr>";
	}

	$data[1] = "$data[1]
<tr><td>Totals</td></tr>
<tr><td>$t_num_troops</td><td> </td><td> </td><td>$t_num_roster</td><td>$t_num_reg</td></tr></table>";

	drawBox($data);

}


function drawRegisterScout()
{
	$query = "select * from student,register where student.id='$id' && student.id=register.student_id";
	$result = mysql_query($query);
	$info = mysql_fetch_array($result);
	$s1 = makeClassOption(1,$info[class1_id]);
	$s2 = makeClassOption(2,$info[class2_id]);
	$s3 = makeClassOption(3,$info[class3_id]);
	$scouts = makeScoutOption($_SESSION['troop'],$_SESSION['year']);

	$data[0] = "Register Scouts";
	$data[1] = "<form method='POST' action='system.php'>
	<input type='hidden' name='option' value='3'>
	<input type='hidden' name='action' value='10'>
	<input type='hidden' name='id' value='$id'>
	<select name='id' size='1'>$scouts</select>
	<table border=0>
	<tr>
	<td>Class 1</td><td>Class 2</td><td>Class 3</td>
	</tr>
	<tr>
	<td><select name='c1' size='1'>$s1</select></td>
	<td><select name='c2' size='1'>$s2</select></td>
	<td><select name='c3' size='1'>$s3</select></td>
	</tr>
	</table>
	<input type='submit' value='Select'></form>";

	drawBox($data);
}


function drawStatistics($troop,$year)
{
	$data[0] = "Troop $troop Statistics";
	$numScouts = getNumScouts($troop);
	$regedScouts = getNumScoutsReg($troop,$year);

	$data[1] = "You have $numScouts scouts in your troop roster.</br>
	There are $regedScouts registered for the current term.</br>";

	drawBox($data);
}


function drawUpdateScoutReg($id,$year)
{
	$query = "select * from student,register where student.id='$id' && student.id=register.student_id";
	$result = mysql_query($query);
	$info = mysql_fetch_array($result);
	$s1 = makeClassOption(1,$info[class1_id]);
	$s2 = makeClassOption(2,$info[class2_id]);
	$s3 = makeClassOption(3,$info[class3_id]);

	$data[0] = "Update Scout:$info[l_name], $info[f_name]";
	$data[1] = "<form method='POST' action='system.php'>
	<input type='hidden' name='option' value='3'>
	<input type='hidden' name='action' value='9'>
	<input type='hidden' name='id' value='$id'>
	<select name='c1' size='1'>$s1</select>
	<select name='c2' size='1'>$s2</select>
	<select name='c3' size='1'>$s3</select>
	<input type='submit' value='Select'></form>";

	drawBox($data);

}



function drawWelcome()
{
	$data[0] = "Welcome";
	$data[1] = "Welcome to the Epsilon Tau Pi Merit Badge College registration site.  Here you can register your troop for our annual Merit Badge College event.";
	drawBox($data);
}


function drawInWelcome()
{
	$data[0] = "Welcome";
	$data[1] = "Follow these steps to register your scouts:</br>
<ul>
<li>Go to Troop Roster
<li>Add your scouts to your troop roster
<li>Go to register for classes
<li>Add your scouts from the list to your registered list.  If you need to remove them or edit them, please click the appropriate option on the right.
</ul>

Thats it!";

	drawBox($data);

}

function drawWelcomeMenu()
{
	$data[0] = "Menu";
	$data[1] = "<a href='register.php'>Register Your Troop</a>";
	drawBox($data);
}

function changePass()
{
	$CClass = new CandClass;
	$data[0] = "Change Password";
	$data[1] = "<table border='0' cellspacing='1' cellpadding='1'>
			<form action='regadmin.php' method='POST'>
			<tr>
			<td>
			Old Password:
			</td>
			<td>
			<input type='password' name='oldPass'>
			</td>
			</tr>
			<tr>
			<td>
			New Password:
			</td>
			<td>
			<input type='password' name='newPass1'>
			</td>
			</tr>
			<tr>
			<td>
			New Password (Again) :
			</td>
			<td>
			<input type='password' name='newPass2'>
			<input type='hidden' name='option' value='6'>
			</td>
			</tr>

			<tr>
			<td><input type='submit' name='submit' value='submit'></td>
			</tr>
			</table>
			</form>";

	drawBox($data);

}


function printMessage($mesg){
	$data[0] = "Message";
	$data[1] = $mesg;
	drawBox($data);
}


function drawErrors($errors)
{
	$data[0] = "Form Errors";
	if(sizeof($errors) == 0)
	{
		$data[1] ="No Errors Found.\n";
	}
	else
	{
		$data[1] ="There were some problems with your form:\n</br>";

		foreach($errors as $value)
		{
			$data[1] = "$data[1] $value[msg]\n</br>\n";
		}
	}
	drawBox($data);
}


function drawRegForm($info)
//Initial troop registration form
{
	//m.hancock - 11/2/2005 - Check registration status; if closed, prevent registration
	if(!isRegistrationOpen())
	{
	        printMessage("Sorry, registration is closed.  Please try back later.");
	        return;
	}

	$time = getdate();
	$currentYear = $time[year];
	$data[0] = "Registration Form";
	$data[1] = "
	<center>
	<table border='0' cellspacing='5' cellpadding='2'>
	<form action='register.php' method='POST'>
	<input type='hidden' name='running' value='yes'>
	<tr>
		<td>Troop Number:</td>
		<td><input type='text' name='troop' value='$info[troop]'></td>
	</tr>
	<tr>
		<td>Contact Name:</td>
		<td><input type='text' name='c_name' value='$info[c_name]'></td>
	</tr>
	<tr>
		<td>Contact Email Address:</td>
		<td><input type='text' name='c_email' value='$info[c_email]'></td>
	</tr>

	<tr>
		<td>Contact Address (Line 1):</td>
		<td><input type='text' name='c_addr1' value='$info[c_addr1]'></td>
	</tr>
	<tr>
		<td>Contact Address (Line 2):</td>
		<td><input type='text' name='c_addr2' value='$info[c_addr2]'></td>
	</tr>
	<tr>
		<td>Contact City:</td>
		<td><input type='text' name='c_city' value='$info[c_city]'></td>
		<td>Contact State:</td>
		<td><input type='text' name='c_state' value='$info[c_state]'></td>
	</tr>
	<tr>
		<td>Contact Zip Code:</td>
		<td><input type='text' name='c_zip' value='$info[c_zip]'></td>
		<td>Contact Phone Number: </br> (xxx)-xxx-xxxx</td>
		<td><input type='text' name='c_phone' value='$info[c_phone]'></td>
	</tr>
	<tr>
	<td>
	<input type='submit' value='submit'>
	<input type='reset' value='reset'>
	</td>
	</tr>
	</form>
	</table>
	</center>";

	drawBox($data);
}


function drawRegEditForm($info)
//Initial troop registration form
{
	$currentYear = $time[year];
	$data[0] = "Update Contact Information";
	$data[1] = "
	<center>
	<table border='0' cellspacing='5' cellpadding='2'>
	<form action='system.php' method='POST'>
	<input type='hidden' name='option' value='4'>
	<input type='hidden' name='action' value='13'>
	<input type='hidden' name='troop' value='$troop'>
	<tr>
		<td>Troop Number:$troop</td>
	<tr>
		<td>Contact Name:</td>
		<td><input type='text' name='c_name' value='$info[c_name]'></td>
	</tr>
	<tr>
		<td>Contact Email Address:</td>
		<td><input type='text' name='c_email' value='$info[c_email]'></td>
	</tr>

	<tr>
		<td>Contact Address (Line 1):</td>
		<td><input type='text' name='c_addr1' value='$info[c_addr1]'></td>
	</tr>
	<tr>
		<td>Contact Address (Line 2):</td>
		<td><input type='text' name='c_addr2' value='$info[c_addr2]'></td>
	</tr>
	<tr>
		<td>Contact City:</td>
		<td><input type='text' name='c_city' value='$info[c_city]'></td>
		<td>Contact State:</td>
		<td><input type='text' name='c_state' value='$info[c_state]'></td>
	</tr>
	<tr>
		<td>Contact Zip Code:</td>
		<td><input type='text' name='c_zip' value='$info[c_zip]'></td>
		<td>Contact Phone Number: </br> (xxx)-xxx-xxxx</td>
		<td><input type='text' name='c_phone' value='$info[c_phone]'></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type='text' name='password' value='$info[password]'></td>
	</tr>
	<tr>
	<td>
	<input type='submit' value='submit'>
	<input type='reset' value='reset'>
	</td>
	</tr>
	</form>
	</table>
	</center>";

	drawBox($data);
}


function makeClassOption($period,$current){
	$query = "select * from class where period='$period' order by name";
	$result = mysql_query($query);

	$data = "<option value=-1>None\n";

	while($info=mysql_fetch_array($result)){
		if(!classIsFull($info[id],$_SESSION['year'],$info[capacity])){
			if($current == $info[id])
			{
				$data="$data <option value='$info[id]' selected>$info[name]\n";
			}
			else
			{
				$data="$data <option value='$info[id]'>$info[name]\n";
			}
		}
		else
		{
			if($current == $info[id])
			{
				$data="$data <option value='$info[id]' selected>$info[name]\n";
			}
		}
	}
	return $data;
}


function makeScoutOption($troop,$year){
	$query = "select * from student where troop='$troop' order by l_name";
	$result = mysql_query($query);

	while($info=mysql_fetch_array($result)){
		if(!scoutIsRegistered($info[id],$year))
		{
			$data="$data <option value='$info[id]'>$info[l_name], $info[f_name]\n";
		}
	}
	return $data;
}

//Matthew Hancock 11-22-04
//menu for type of export to excel
function drawAdminExportMenu()
{
	$data[0] = "Export Menu";
	$data[1] = "<a href='export.php?export=1&option=1'>All Classes</a>";
	$data[2] = "<a href='system.php?option=9'>Specific Class</a>";
	$data[3] = "<a href='export.php?export=1&option=3'>Troop Contacts</a>";
	$data[4] = "<a href='system.php?option=10'>Specific Troop</a>";
	//m.hancock - 11/11/2007 - Added T-Shirt reports
	//n.fahrig - 11/10/2010 - removed t-shirt reports
	//$data[5] = "<a href='export.php?export=1&option=5'>T-Shirts: Ordered</a>";
	//$data[6] = "<a href='export.php?export=1&option=7'>T-Shirts: Troop Selections Summary</a>";	
	//$data[7] = "<a href='export.php?export=1&option=6'>T-Shirts: Troop Selections Detailed</a>";	
	drawBox($data);
}

//Matthew Hancock 11-22-04
//draws list of classes to select class for exporting
function drawExportSpecificClass()
{
	$data[0] = "Export Specific Class Roster";

	$query = "select * from class order by name";
	$result = mysql_query($query);
	$data[1] = "<table class='list' width='100%'>\n";
	$data[1] = "$data[1] <tr><td>Class Name</td><td>Section</td><td>Period</td><td>Capacity</td><td>Current</td><td>Teacher</td><td>Room</td><td>Length</td></tr>";

	while($classes=mysql_fetch_array($result)){
		$reged = getClassRegNum($_SESSION['year'],$classes[id]);
		$data[1] = "$data[1] <tr>\n<td>$classes[name]</td><td> $classes[section]</td><td>$classes[period]</td><td> $classes[capacity]</td><td>$reged</td><td> $classes[teacher]</td><td>$classes[room]</td><td>$classes[length]</td><td><a href='export.php?export=1&option=2&id=$classes[id]'>Export</a></td>\n</tr>\n";
	}
	$data[1] = "$data[1] </table>\n";
	drawBox($data);
}

//Matthew Hancock 11-22-04
//draws lits of troops to select troop for exporting
function drawExportSpecificTroop()
{
	$data[0] = "Export Specific Troop Schedule";

	$query = "select * from troop order by troop";
	$result = mysql_query($query);

	$data[1] = "<table class='list' width='100%'>\n";
	$data[1] = "$data[1] <tr><td>Troop Number</td></tr>";

	while($troops=mysql_fetch_array($result))
	{
		$data[1] = "$data[1] <tr>\n<td>$troops[troop]</td><td><a href='export.php?export=1&option=4&id=$troops[troop]'>Export</a></td>\n</tr>\n";
	}
	$data[1] = "$data[1] </table>\n";
	drawBox($data);
}

//m.hancock - 11/2/2005
//We need a way to toggle the registration status so troops don't register early or after the deadline.
//Field is year.register_status; O = open (default), C = closed
//This is called by system.php?$option=11
function drawToggleRegistration()
{
        //Display the header
	$data[0] = "Open or Close Registration";
	$data[1] = "Registration is currently... <form method='POST' action='system.php?action=14'>";

        //Display the radios for registration status
	//Close registration
	$data[2] = "<input type='radio' value='O' name='status'> Open";
        $data[3] = "<input type='radio' value='C' name='status' checked> Closed";
        if(isRegistrationOpen())
        {
                $data[2] = "<input type='radio' value='O' name='status' checked> Open";
	        $data[3] = "<input type='radio' value='C' name='status'> Closed";
        }

        //Clean up
        $data[4] = "<input type='submit' value='Submit'></form>";
	drawBox($data);
}

//m.hancock - 11/2/2005
//Display the current registration status
function drawRegistrationStatus()
{
	if(isRegistrationOpen())
	        $status = "Open";
	else
	        $status = "Closed";

	$data[0] = "Registration Status: $status";
	drawBox($data);
}

?>
