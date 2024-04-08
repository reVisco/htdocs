<?php

if(isset($_POST["submit"])){

	$selector = bin2hex(random_bytes(8));
	$token = random_bytes(32);


} else {
	header("Location: ../../index.php");
}