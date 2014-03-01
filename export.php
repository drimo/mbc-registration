<?php
//export.php
// created 11-22-04 by Matthew Hancock
// *provides functions to select and export data to excel spreadsheet
require_once("mysql.php");
if(isset($export))
{
	switch($option)
	{
		case 1: 
			//all classes
			exportExcel("1","");
			break;
		case 2: 
			//specific class
			exportExcel("2", $id);
			break;
		case 3: 
			//troop contacts
			exportExcel("3","");
			break;
		case 4: 
			//specific troop
			exportExcel("4", $id);
			break;
		case 5:
			//m.hancock - 11/11/2007 - Added tshirt order report
			exportExcel("5", $id);
			break;
		case 6:
			//m.hancock - 11/11/2007 - Added troop tshirt selection report
			exportExcel("6", $id);
			break;
		case 7:
			//m.hancock - 11/11/2007 - Added troop tshirt selection report
			exportExcel("7", $id);
			break;
		default:
			//all classes
			//exportExcel("1","");
			break;
	}
}

//runs a query for the selected data (all class rosters, a specific class roster, or all troop contact info)
//Matthew Hancock 11-22-04
function getExportData($option, $data)
{
	switch($option)
	{
		case 1:
			//all class rosters
			$query = "
			select period, section, name, room, troop, l_name, f_name
			from class, register, student
			where (class.id = register.class1_id or class.id = register.class2_id or class.id = register.class3_id) and register.student_id = student.id
			order by period, name, l_name
			";
		break;
		case 2:
			//specific class roster
			$query = "
			select period, section, name, room, troop, l_name, f_name
			from class, register, student
			where (class.id = register.class1_id or class.id = register.class2_id or class.id = register.class3_id) and register.student_id = student.id and class.id = '$data'
			order by section, l_name
			";
		break;
		case 3:
			//contact information
			$query = "
			SELECT T.troop, T.c_name, T.c_addr1, T.c_addr2, T.c_zip, T.c_city, T.c_state, T.c_phone, T.c_email, count(*) as NumRegistered
			FROM troop T
			INNER JOIN student S ON T.troop = S.troop
			INNER JOIN register R ON S.id = R.student_id
			GROUP BY T.troop
			ORDER BY T.troop";		
		break;
		case 4:
			//troop information (schedule for each troop)
			$query = "
			select l_name, f_name, period, name, section, room
			from student, register, class
			where student.troop = '$data' and student.id = register.student_id and (register.class1_id = class.id or register.class2_id = class.id or register.class3_id = class.id )
			order by l_name, f_name, period
			";		
		break;
		case 5:
			//m.hancock - 11/11/2007 - Added tshirt order report
			$query = "
			SELECT tshirt AS TShirt, count(*) AS NumOrdered
			FROM student
			INNER JOIN register ON student.id = register.student_id
			GROUP BY tshirt
			ORDER BY tshirt
			";
		break;
		case 6:
			//m.hancock - 11/11/2007 - Added troop tshirt selection report detailed
			$query = "
			SELECT S.troop AS Troop, S.l_name AS LastName, S.f_name AS FirstName, tshirt AS TShirt
			FROM student S
			INNER JOIN register R ON S.id = R.student_id
			ORDER BY S.troop, S.l_name, S.f_name
			";
		break;
		case 7:
			//m.hancock - 11/11/2007 - Added troop tshirt selection report summary
			$query = "
			SELECT 
			S.troop, 
			(SELECT COUNT(*) FROM student INNER JOIN register ON student.id = register.student_id WHERE student.tshirt = 'None' AND student.troop = S.troop) AS TShirt_None,
			(SELECT COUNT(*) FROM student INNER JOIN register ON student.id = register.student_id WHERE student.tshirt = 'Child Small' AND student.troop = S.troop) AS TShirt_ChildSmall,
			(SELECT COUNT(*) FROM student INNER JOIN register ON student.id = register.student_id WHERE student.tshirt = 'Child Medium' AND student.troop = S.troop) AS TShirt_ChildMedium,
			(SELECT COUNT(*) FROM student INNER JOIN register ON student.id = register.student_id WHERE student.tshirt = 'Child Large' AND student.troop = S.troop) AS TShirt_ChildLarge,
			(SELECT COUNT(*) FROM student INNER JOIN register ON student.id = register.student_id WHERE student.tshirt = 'Adult Small' AND student.troop = S.troop) AS TShirt_AdultSmall,
			(SELECT COUNT(*) FROM student INNER JOIN register ON student.id = register.student_id WHERE student.tshirt = 'Adult Medium' AND student.troop = S.troop) AS TShirt_AdultMedium,
			(SELECT COUNT(*) FROM student INNER JOIN register ON student.id = register.student_id WHERE student.tshirt = 'Adult Large' AND student.troop = S.troop) AS TShirt_AdultLarge,
			(SELECT COUNT(*) FROM student INNER JOIN register ON student.id = register.student_id WHERE student.tshirt = 'Adult X-Large' AND student.troop = S.troop) AS TShirt_AdultXLarge
			FROM student S
			INNER JOIN register R ON S.id = R.student_id
			GROUP BY S.troop
			ORDER BY S.troop
			";
		break;
		default:
		break;
	}
	return mysql_query($query);
}

/* Export selected data (class lists, entire MBC roster, Troop Contact Info) to Excel
Written by Dan Zarrella. Some additional tweaks provided by JP Honeywell
pear excel package has support for fonts and formulas etc.. more complicated
this is good for quick table dumps (deliverables)
modified by Matthew Hancock 11-22-04 */
function exportExcel($option, $record)
{
	$result = getExportData($option, $record); //MH 11-22-04
	$count = mysql_num_fields($result);

	for ($i = 0; $i < $count; $i++){
		$header .= mysql_field_name($result, $i)."\t";
	}
	
	while($row = mysql_fetch_row($result)){
	  $line = '';
	  foreach($row as $value){
		if(!isset($value) || $value == ""){
		  $value = "\t";
		}else{
	# important to escape any quotes to preserve them in the data.
		  $value = str_replace('"', '""', $value);
	# needed to encapsulate data in quotes because some data might be multi line.
	# the good news is that numbers remain numbers in Excel even though quoted.
		  $value = '"' . $value . '"' . "\t";
		}
		$line .= $value;
	  }
	  $data .= trim($line)."\n";
	}
	# this line is needed because returns embedded in the data have "\r"
	# and this looks like a "box character" in Excel
	  $data = str_replace("\r", "", $data);
	
	
	# Nice to let someone know that the search came up empty.
	# Otherwise only the column name headers will be output to Excel.
	if ($data == "") {
	  $data = "\nno matching records found\n";
	}
	
	# This line will stream the file to the user rather than spray it across the screen
	header("Content-type: application/octet-stream");
	
	# replace excelfile.xls with whatever you want the filename to default to
	header("Content-Disposition: attachment; filename=excelfile.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	echo $header."\n".$data;
}
?>
