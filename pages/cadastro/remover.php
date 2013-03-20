<?php 
require_once '../../admin/page.php';
require_once '../../admin/funcoes.php';
require_once '../../tabelas/Usuario.php';
session_start();

//verifica se esta online
if(!isset($_SESSION['logado']) || !$_SESSION['logado']) header('Location: ../index/index.php');

inicio(
	'<link rel="stylesheet" href="../../public/select/select.css" type="text/css" media="screen" title="default">
	<link rel="stylesheet" href="cadastro.css" type="text/css" media="screen" title="default">
	<script src="../../public/select/select.js" type="text/javascript"></script>
	<script src="cadastro.js" type="text/javascript"></script>'
);

$erro = array();

if(isset($_POST['remover'])){
	//verifica senha
	$senhaAtual = Usuario::getSenha($_SESSION['usuario']['id']);
	if(md5($_POST['senha']) != substr($senhaAtual, 21, 32)){
		$erro['senha'] = 'Senha inválida.';
	}
		
	if(count($erro) == 0){
		//remover usuario
		Usuario::remove($_SESSION['usuario']['id']);
		//limpa o session do usuario
		unset($_SESSION['usuario']);
		unset($_SESSION['logado']);
		unset($_SESSION['logout']);
	}	
}

?>

<div class="titulo">Remover Usuário</div>

<form action="remover.php" method="post">
	<table>
		<tr>
			<td>Senha</td>
			<td>
				<input type="password" name="senha" size="30" placeholder="Senha"
				<?php if(isset($_POST['senha']) && isset($erro['senha'])) echo 'style="border-color: #D90000;" ';?>
				>
				<div class="erro"><?php echo @$erro['senha']; ?></div>
			</td>
			<td style="font-size: 13px;">
				<input class="remover" type="submit" name="remover" value="Remover Conta">
			</td>
		</tr>
	</table>
</form>

<?php 
fim(); 
?>