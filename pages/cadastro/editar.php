<?php 
require_once '../../admin/page.php';
require_once '../../admin/funcoes.php';
require_once '../../tabelas/Cidade.php';
require_once '../../tabelas/Estado.php';
require_once '../../tabelas/Usuario.php';
require_once '../../public/select/select.php';
header("Content-type: text/html; charset=iso-8859-1");
session_start();

//verifica se esta online
if(!isset($_SESSION['logado']) || !$_SESSION['logado']) header('Location: ../index/index.php');

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
$editar = false;

if(isset($_POST['alterar'])){
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
		
		$senha = $dados['senha_atual'];
		unset($dados['senha_atual']);
		
		$dados = funcoes::validarCamposBD($dados);
		$erro = funcoes::validarCampos($_POST);
		
		if(count($erro) == 0){
			if($dados['email'] != $_SESSION['usuario']['email'] && mysql_num_rows(Usuario::selectByEmail($dados['email'])) != 0){
				$erro['email'] = 'Email já existe.';
			}
			
			if($dados['login'] != $_SESSION['usuario']['login'] && mysql_num_rows(Usuario::selectByLogin($dados['login'])) != 0){
				$erro['login'] = 'Login já existe.';
			}
			
			if($dados['cpf'] != $_SESSION['usuario']['cpf'] && mysql_num_rows(Usuario::selectByCpf($dados['cpf'])) != 0){
				$erro['cpf'] = 'Cpf já existe.';
			}
			
			if(mysql_num_rows(Estado::isEstado($dados['estado'], $dados['cidade'])) == 0){
				$erro['estado'] = 'Estado e/ou Cidade inválido(s).';
				$erro['cidade'] = 'Estado e/ou Cidade inválido(s).';
			}
			
			$senhaAtual = Usuario::getSenha($_SESSION['usuario']['id']);
			if(md5($senha) != substr($senhaAtual, 21, 32)){
				$erro['senha_atual'] = 'Senha inválida.';
			}
			
			if(count($erro) == 0){
				if(strlen($_POST['senha']) != 0){
					$str = "abcdef1234567890";
					$len = strlen($str) - 1;
					
					$senha = '';
					for($i = 0; $i < 96; $i++){
						$senha .= $str[rand(0, $len)];
						if($i == 20) $senha .= md5($_POST['senha']);
					}
				}
				else $senha = $senhaAtual;
				
				$dados = array(
					'id' => $_SESSION['usuario']['id'],
					'cidade' => $dados['cidade'],
					'nome' => $dados['nome'],
					'login' => $dados['login'],
					'senha' => $senha,
					'data_nascimento' => funcoes::toDataUS($dados['data_nascimento']),
					'endereco' => $dados['endereco'],
					'complemento' => $dados['complemento'] == '' ? null: $dados['complemento'],
					'cpf' => $dados['cpf'],
					'cep' => $dados['cep'],
					'master' => $dados['master'] == 'true' ? true : false,
					'email' => $dados['email'],
				);
				
				Usuario::edit($dados);
				
				//atualiza dados do session
				unset($dados['senha']);
				$dados['data_nascimento'] = funcoes::toDataBR($dados['data_nascimento']);
				$_SESSION['usuario'] = $dados;
				
				$editar = true;
				
				unset($_POST['senha_atual']);
				unset($_POST['senha']);
				unset($_POST['confirmar_senha']);
				unset($_SESSION['cidade']);
				unset($_SESSION['estado']);
			}
		}
	}
}
else{
	$_POST = $_SESSION['usuario'];
	$_POST['master'] = $_SESSION['usuario']['master'] ? 'true' : 'false';
	$_POST['estado'] = Estado::getIdByCidade($_SESSION['usuario']['cidade']);
}

?>

<div class="titulo">Editar Usuário</div>

<?php
if($editar) echo '<div class="mensagemOk">Edição realizada com sucesso!</div>'; 
//erro na busca dos dados no banco de dados ou na connecção
if(is_string($cidade) || is_string($estado)) echo '<div class="mensagemErro">'.connect_bd::erro().'</div>';
else{
?>

<form action="editar.php" method="post">
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
				<input type="password" name="senha_atual" size="20" placeholder="Senha"
				<?php 
				if(isset($_POST['senha_atual'])){
					if(isset($erro['senha_atual'])) echo 'style="border-color: #D90000;" ';
					echo 'value="'.$_POST['senha_atual'].'"';
				}
				?>
				>
				<div class="erro"><?php echo @$erro['senha_atual']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Nova Senha</td>
			<td>
				<input type="password" name="senha" size="20" placeholder="Nova senha"
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
			<td>Confirmar Nova Senha</td>
			<td>
				<input type="password" name="confirmar_senha" size="20" placeholder="Confirmar nova senha" 
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
				<input type="text" name="complemento" size="50" value="<?php echo isset($_POST['complemento']) ? $_POST['complemento'] : ''?>">
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
		<?php if(isset($_SESSION['logado']) && $_SESSION['logado'] && isset($_SESSION['usuario']['master']) && $_SESSION['usuario']['master'] == 1){?>
		<tr>
			<td>Permissão do usuário *</td>
			<td>
				<label for="permissao_true">
					<input type="radio" name="master" id="permissao_true" value="true"
						<?php if(isset($_POST['master']) && $_POST['master'] == 'true') echo 'checked="checked"'; ?>
					>Master
				</label>
				<label for="permissao_false">
					<input type="radio" name="master" id="permissao_false" value="false" 
						<?php if(isset($_POST['master']) && $_POST['master'] == 'false') echo 'checked="checked"'; ?>
					>Comum
				</label>
				<div class="erro"><?php echo @$erro['master']; ?></div>
			</td>
		</tr>
		<?php }?>
		<tr>
			<td style="font-size: 13px;">* Campos Obrigatórios</td>
			<td>
				<input class="botao" type="submit" name="alterar" value="Alterar">
				<div class="erro"></div>
			</td>
		</tr>
	</table>
</form>

<?php 
} 
fim(); 
?>