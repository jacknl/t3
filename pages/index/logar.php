<?php
require_once '../../tabelas/Usuario.php';
require_once '../../admin/funcoes.php';
//corrige a acentacao
header("Content-type: text/html; charset=iso-8859-1");
session_start();

//caso o usuario esteja conectado, retorna as opcoes do mesmo
if(isset($_SESSION['logado']) && $_SESSION['logado']){
	$_SESSION['logout'] = funcoes::stringRand(32);
	echo json_encode(Logar());
	return;
}

//caso o campo usuario ou senha nao existam
if(!isset($_POST['usuario']) || !isset($_POST['senha'])) return;

//pega os dados pelo login do usuario
$usuario = Usuario::selectByLogin(addslashes($_POST['usuario']));
$dados = mysql_fetch_assoc($usuario);

//verifica se a senha sao iguais
if(substr($dados['senha'], 21, 32) == md5($_POST['senha'])){
	//usuario esta logado
	$_SESSION['logado'] = true;
	unset($dados['senha']);
	$dados['data_nascimento'] = funcoes::toDataBR($dados['data_nascimento']);
	//guarda os dados do usuario
	$_SESSION['usuario'] = $dados;
	//guarda um codigo pra fazer o logout
	$_SESSION['logout'] = funcoes::stringRand(32);

	echo json_encode(logar());
}

//retorna as opcoes do usuario
function logar(){
	if($_SESSION['usuario']['master']){
		$logar = array(
				'nome' => $_SESSION['usuario']['nome'],
				'links' => array(
						'Editar Perfil' => '../cadastro/editar.php',
						'Cadastrar Jogo' => '../jogo/cadastro.php',
						'Listar Jogo' => '../jogo/index.php',
						utf8_encode('Listar Gêneros') => '../genero/index.php'
				),
				'sair' => $_SESSION['logout']
		);
	}
	else{
		$logar = array(
				'nome' => $_SESSION['usuario']['nome'],
				'links' => array(
						'Editar Perfil' => '../cadastro/editar.php'
				),
				'sair' => $_SESSION['logout']
		);
	}

	return $logar;
}

?>