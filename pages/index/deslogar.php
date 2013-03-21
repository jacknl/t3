<?php 
session_start();

//caso o usuario estaja logado e os codigos para deslogar sejam iguais
if(isset($_POST['logout']) && isset($_SESSION['logout']) && $_SESSION['logout'] == $_POST['logout']){
	unset($_SESSION['logado']);
	unset($_SESSION['usuario']);
	unset($_SESSION['logout']);
	
	echo $_POST['logout'];
}

?>