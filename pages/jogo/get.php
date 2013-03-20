<?php 
require_once '../../admin/funcoes.php';
require_once '../../tabelas/jogoGet.php';

session_start();
//somente usuarios logados como master
if(!isset($_SESSION['logado']) || !$_SESSION['logado'] || !isset($_SESSION['usuario']['master']) || $_SESSION['usuario']['master'] == 0) return;

$opcao = false;
if(isset($_POST['opcao'])) $opcao = funcoes::validarCamposBD($_POST['opcao']);
$tabela = false;
if(isset($_GET['t'])) $tabela = funcoes::validarCamposBD($_GET['t']);

if($opcao && $tabela){	
	if($opcao == 'adicionar' && isset($_POST['nome'])){
		$nome = funcoes::validarCamposBD($_POST['nome']);
		$add = jogoGet::adicionar($tabela, $nome);
		if($add != false) echo $add;
		else return;
		
	}
	else if($opcao == 'remover' && isset($_POST['id'])){
		$id = funcoes::validarCamposBD($_POST['id']);
		if(jogoGet::remover($tabela, $id)) echo $id;
		else return;
	}
	
}

return;
?>