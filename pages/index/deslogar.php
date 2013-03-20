<?php 
session_start();
	
if(isset($_POST['logout']) && isset($_SESSION['logout']) && $_SESSION['logout'] == $_POST['logout']){
	unset($_SESSION['logado']);
	unset($_SESSION['usuario']);
	unset($_SESSION['logout']);

	return $_POST['logout'];
}

?>