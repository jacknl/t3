<?php 
require_once '../../admin/page.php';
require_once '../../admin/funcoes.php';
require_once '../../tabelas/Cidade.php';
require_once '../../tabelas/Estado.php';
require_once '../../tabelas/Usuario.php';
require_once '../../public/select/select.php';
header("Content-type: text/html; charset=iso-8859-1");
session_start();

inicio(
	'<link rel="stylesheet" href="../../public/select/select.css" type="text/css" media="screen" title="default">
	<link rel="stylesheet" href="cadastro.css" type="text/css" media="screen" title="default">
	<script src="../../public/select/select.js" type="text/javascript"></script>
	<script src="cadastro.js" type="text/javascript"></script>'
);

$cidade = Cidade::selectAll();
$estado = Estado::selectAll();
$_SESSION['cidade'] = true;
$_SESSION['estado'] = true;
$cadastro = false;

if(isset($_POST['cadastrar'])){
	$erro = funcoes::validarCampos($_POST);
	
	if(count($erro) == 0){
		$dados = $_POST;
		unset($dados['cadastar']);
		unset($dados['senha']);
		unset($dados['confirmar_senha']);
		//acentuacao
		$dados['nome'] = utf8_encode($dados['nome']);
		$dados['endereco'] = utf8_encode($dados['endereco']);
		$dados['complemento'] = utf8_encode($dados['complemento']);
		
		$dados = funcoes::validarCamposBD($dados);
		$erro = funcoes::validarCampos($_POST);
		
		if(count($erro) == 0){
			if(mysql_num_rows(Usuario::selectByEmail($dados['email'])) != 0){
				$erro['email'] = 'Email já existe.';
			}
			
			if(mysql_num_rows(Usuario::selectByLogin($dados['login'])) != 0){
				$erro['login'] = 'Login já existe.';
			}
			
			if(mysql_num_rows(Usuario::selectByCpf($dados['cpf'])) != 0){
				$erro['cpf'] = 'Cpf já existe.';
			}
			
			if(mysql_num_rows(Estado::isEstado($dados['estado'], $dados['cidade'])) == 0){
				$erro['estado'] = 'Estado e/ou Cidade inválido(s).';
				$erro['cidade'] = 'Estado e/ou Cidade inválido(s).';
			}
			
			if(count($erro) == 0){
				$str = "abcdef1234567890";
				$len = strlen($str) - 1;
				
				$senha = '';
				for($i = 0; $i < 96; $i++){
					$senha .= $str[rand(0, $len)];
					if($i == 20) $senha .= md5($_POST['senha']);
				}
				
				Usuario::add(array(
					$dados['cidade'],
					$dados['nome'],
					$dados['login'],
					$senha,
					funcoes::toDataUS($dados['data_nascimento']),
					$dados['endereco'],
					$dados['complemento'] == '' ? null: $dados['complemento'],
					$dados['cpf'],
					$dados['cep'],
					false,
					$dados['email'],
				));
				
				$cadastro = true;
				
				$_POST = array();
				unset($_SESSION['cidade']);
				unset($_SESSION['estado']);
			}
		}
	}
}

?>

<div class="titulo">Cadastrar Usuário</div>

<?php
if($cadastro) echo '<div class="mensagemOk">Cadastro realizado com sucesso!</div>'; 
//erro na busca dos dados no banco de dados ou na connecção
if(is_string($cidade) || is_string($estado)) echo '<div class="mensagemErro">'.connect_bd::erro().'</div>';
else{
?>

<form action="cadastro.php" method="post">
	<table>
		<tr>
			<td>Nome *</td>
			<td>
				<input type="text" name="nome" size="40" placeholder="Nome"
				<?php 
				if(isset($_POST['nome'])){
					if(isset($erro['nome'])) echo 'style="border-color: #D90000;" ';
					echo 'value="'.$_POST['nome'].'"';
				}
				?>
				>
				<div class="erro"><?php echo @$erro['nome']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Email *</td>
			<td>
				<input type="text" name="email" placeholder="Ex: jogo@jogo.com" size="30"
				<?php 
				if(isset($_POST['email'])){
					if(isset($erro['email'])) echo 'style="border-color: #D90000;" ';
					echo 'value="'.$_POST['email'].'"';
				}
				?>
				>
				<div class="erro"><?php echo @$erro['email']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Login *</td>
			<td>
				<input type="text" name="login" size="25" placeholder="Login"
				<?php 
				if(isset($_POST['login'])){
					if(isset($erro['login'])) echo 'style="border-color: #D90000;" ';
					echo 'value="'.$_POST['login'].'"';
				}
				?>
				>
				<div class="erro"><?php echo @$erro['login']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Senha *</td>
			<td>
				<input type="password" name="senha" size="20" placeholder="Senha"
				<?php 
				if(isset($_POST['senha'])){
					if(isset($erro['senha'])) echo 'style="border-color: #D90000;" ';
					echo 'value="'.$_POST['senha'].'"';
				}
				?>
				>
				<div class="erro"><?php echo @$erro['senha']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Confirmar Senha *</td>
			<td>
				<input type="password" name="confirmar_senha" size="20" placeholder="Confirmar senha" 
					<?php echo isset($erro['confirmar_senha']) ? 'style="border-color: #D90000;" ' : '';?>
				>
			</td>
		</tr>
		<tr>
			<td>CPF *</td>
			<td>
				<input type="text" name="cpf"  placeholder="Ex: 00000000000"  size="15"
				<?php 
				if(isset($_POST['cpf'])){
					if(isset($erro['cpf'])) echo 'style="border-color: #D90000;" ';
					echo 'value="'.$_POST['cpf'].'"';
				}
				?>
				>
				<div class="erro"><?php echo @$erro['cpf']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Data de Nascimento *</td>
			<td>
				<input type="text" name="data_nascimento" id="data_nascimento" size="10"
				<?php 
				if(isset($_POST['data_nascimento'])){
					if(isset($erro['data_nascimento'])) echo 'style="border-color: #D90000;" ';
					echo 'value="'.$_POST['data_nascimento'].'"';
				}
				?>
				>
				<div class="erro"><?php echo @$erro['data_nascimento']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Estado *</td>
			<td id="estado">
				<?php
				$selecionado = null;
				if(isset($_POST['estado']) && strlen($_POST['estado']) > 0){
					$selecionado = array($_POST['estado'], Estado::getNome($_POST['estado']));
				}
				
				select('estado', $estado, false, null, $selecionado); 
				?>
				<div class="erro"><?php echo @$erro['estado']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Cidade *</td>
			<td id="cidade">
				<?php
				$selecionado = null;
				$cid = null;
				
				if(isset($_POST['estado']) && strlen($_POST['estado']) > 0){
					$cid = Cidade::selectCidade(addslashes($_POST['estado']));
					if(isset($_POST['cidade']) && strlen($_POST['cidade']) > 0){
						$selecionado = array($_POST['cidade'], Cidade::getNome($_POST['cidade']));
					}
				}
				
				select('cidade', $cid, false, null, $selecionado); 
				?>
				<div class="erro"><?php echo @$erro['cidade']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Endereço *</td>
			<td>
				<input type="text" name="endereco" size="50" placeholder="Ex: Rua Fulano, 5, Centro"
				<?php 
				if(isset($_POST['endereco'])){
					if(isset($erro['endereco'])) echo 'style="border-color: #D90000;" ';
					echo 'value="'.$_POST['endereco'].'"';
				}
				?>
				>
				<div class="erro"><?php echo @$erro['endereco']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Complemento</td>
			<td>
				<input type="text" name="complemento" size="50">
				<div class="erro"><?php echo @$erro['complemento']; ?></div>
			</td>
		</tr>
		<tr>
			<td>CEP *</td>
			<td>
				<input type="text" name="cep" size="10" placeholder="Ex: 00000000" 
				<?php 
				if(isset($_POST['cep'])){
					if(isset($erro['cep'])) echo 'style="border-color: #D90000;" ';
					echo 'value="'.$_POST['cep'].'"';
				}
				?>
				>
				<div class="erro"><?php echo @$erro['cep']; ?></div>
			</td>
		</tr>
		<tr>
			<td style="font-size: 13px;">* Campos Obrigatórios</td>
			<td>
				<input class="botao" type="submit" name="cadastrar" value="Cadastrar">
				<div class="erro"></div>
			</td>
		</tr>
	</table>
</form>

<?php 
} 
fim(); 
?>