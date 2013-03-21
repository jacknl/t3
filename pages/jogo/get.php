<?php 
require_once '../../admin/funcoes.php';
require_once '../../tabelas/jogoGet.php';

//nos campos de select e multiselect, pode ter um input para adicionar ou remover um item das opcoes

session_start();
//somente usuarios logados como master(administradores)
if(!isset($_SESSION['logado']) || !$_SESSION['logado'] || !isset($_SESSION['usuario']['master']) || $_SESSION['usuario']['master'] == 0) return;

$opcao = false;
if(isset($_POST['opcao'])) $opcao = funcoes::validarCamposBD($_POST['opcao']);
$tabela = false;
if(isset($_GET['t'])) $tabela = funcoes::validarCamposBD($_GET['t']);


//os campos opcao(adicionar ou remover) estao validos
if($opcao && $tabela){
	//adiciona no banco a nova opcao e retorna o id da insercao
	if($opcao == 'adicionar' && isset($_POST['nome'])){
		$nome = funcoes::validarCamposBD($_POST['nome']);
		$add = jogoGet::adicionar($tabela, $nome);
		if($add != false) echo $add;
		else return;
		
	}
	//remove uma opcao e retorna o id da insercao
	else if($opcao == 'remover' && isset($_POST['id'])){
		$id = funcoes::validarCamposBD($_POST['id']);
		if(jogoGet::remover($tabela, $id)) echo $id;
		else return;
	}
	
}

return;
?>