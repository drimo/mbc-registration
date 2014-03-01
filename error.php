<?php
include("interface.php");
?>
<html>
<head>
<link rel=stylesheet type=text/css href=style.css>
<title>Merit Badge College - Error</title>
</head>
<body>
<!-- Main content is here -->

<table width="100%">
<tr>
<td>
<?php
$data[0] = "Error";
$data[1] = "Your password has not been accepted.  Please check your troop and password and try again.</br>
<a href='homepage.php'>Click here to return home</a>";
drawBox($data);
?>
</td>
</tr>
<tr>
<td>
<?php drawLogin();?>
</td>
</tr>
</table>
<!-- End Main content     -->
</body>
</html>
