<?php 
require_once '../../tabelas/Cidade.php';
session_start();

//se tiver permissao para perquisar a cidade
if(!$_SESSION['cidade'] || !$_SESSION['estado']){
	echo 'erro';
	return;
}

//busca as cidades
$cidade = Cidade::selectCidade(addslashes($_POST['estado']));
$cid = array();
//cria uma array com o indice sendo o id da cidade e o valor como o nome da cidade
while($linha = mysql_fetch_assoc($cidade)) $cid[$linha['id']] = utf8_encode($linha['nome']);

//retorna as cidade em json
echo json_encode($cid);

?>