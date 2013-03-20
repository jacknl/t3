<?php 
require_once '../../tabelas/Cidade.php';
session_start();

if(!$_SESSION['cidade'] || !$_SESSION['estado']){
	echo 'erro';
	return;
}
	
$cidade = Cidade::selectCidade(addslashes($_POST['estado']));
$cid = array();

while($linha = mysql_fetch_assoc($cidade)) $cid[$linha['id']] = utf8_encode($linha['nome']);

echo json_encode($cid);

?>