<?php
//utility.php - basic functions used in most web pages

function genRandomPass($length){
	$salt ="abcdefghijklmnopqrstuvwxyz0123456789";
	srand((double)microtime()*1000000);
	$i=0;
	while($i <= $length){
		$num = rand() % 33;
		$tmp = substr($salt,$num,1);
		$pass = $pass . $tmp;
		$i++;
	}
	return $pass;
}
?>