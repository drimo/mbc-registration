<?php
// login.php - validates sessio
include("mysql.php");

//authenticate using form in homepage.php
//$status = authenticate($_POST['troop'],$_POST['password']);
$status = authenticate($troop,$password);

if($status == 1)
{
        //initiate a session
        session_start();

        //register some session session vars
        session_register("SESSION");
	$_SESSION['troop'] = $troop;

	if($troop == 0){
		$_SESSION['admin'] = "yes";
	}

	//get current year
	$_SESSION['year'] = getSessionYear();

        //redirect to protected page
        header("Location: system.php");
        exit();
}

//user check failed
else
{
        //redirect to error page
        header("Location: error.php?e=$status");
        exit();
}

?>
