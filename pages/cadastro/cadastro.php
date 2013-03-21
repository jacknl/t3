<?php 
require_once '../../admin/page.php';
require_once '../../admin/funcoes.php';
require_once '../../tabelas/Cidade.php';
require_once '../../tabelas/Estado.php';
require_once '../../tabelas/Usuario.php';
require_once '../../public/select/select.php';
//converte os caracteres para o formato ISO-8859-1
header("Content-type: text/html; charset=iso-8859-1");
session_start();

inicio(//cabecalho do site
	'<link rel="stylesheet" href="../../public/select/select.css" type="text/css" media="screen" title="default">
	<link rel="stylesheet" href="cadastro.css" type="text/css" media="screen" title="default">
	<script src="../../public/select/select.js" type="text/javascript"></script>
	<script src="cadastro.js" type="text/javascript"></script>'
);

//pega os estado do banco
$estado = Estado::selectAll();
//autoriza que o arquivo getCidade pode fazer um consulta no banco
$_SESSION['cidade'] = true;
$_SESSION['estado'] = true;
$cadastro = false;

//ouve um post
if(isset($_POST['cadastrar'])){
	//valida os campos
	$erro = funcoes::validarCampos($_POST);
	
	if(count($erro) == 0){//caso nao tenha nenhum erro de validacao
		$dados = $_POST;
		unset($dados['cadastar']);
		unset($dados['senha']);
		unset($dados['confirmar_senha']);
	
		//converte os caracteres para utf8, por causa dos acentos
		$dados['nome'] = utf8_encode($dados['nome']);
		$dados['endereco'] = utf8_encode($dados['endereco']);
		$dados['complemento'] = utf8_encode($dados['complemento']);
		
		//faz o addslashes e htmlentities
		$dados = funcoes::validarCamposBD($dados);
		//virifica novamente os campos por causa do addslashes e htmlentities
		$erro = funcoes::validarCampos($_POST);
		
		if(count($erro) == 0){
			//caso o e-mail ja exista, retorna um erro
			if(mysql_num_rows(Usuario::selectByEmail($dados['email'])) != 0){
				$erro['email'] = 'Email já existe.';
			}
			//caso o login ja exista, retorna um erro
			if(mysql_num_rows(Usuario::selectByLogin($dados['login'])) != 0){
				$erro['login'] = 'Login já existe.';
			}
			//caso o cpf ja exista, retorna um erro
			if(mysql_num_rows(Usuario::selectByCpf($dados['cpf'])) != 0){
				$erro['cpf'] = 'Cpf já existe.';
			}
			//caso a cidade na pertenca ao estado e vice-versa, retorna um erro
			if(mysql_num_rows(Estado::isEstado($dados['estado'], $dados['cidade'])) == 0){
				$erro['estado'] = 'Estado e/ou Cidade inválido(s).';
				$erro['cidade'] = 'Estado e/ou Cidade inválido(s).';
			}
			
			if(count($erro) == 0){
				//senha possui 128 caraceteres
				//em que somente da posicao 20 à 52 esta a senha criptografada em md5
				//os outro sao caracteres randomicos 
				$str = "abcdef1234567890";
				$len = strlen($str) - 1;
				
				$senha = '';
				for($i = 0; $i < 96; $i++){
					$senha .= $str[rand(0, $len)];
					if($i == 20) $senha .= md5($_POST['senha']);
				}
				
				//adiciona o usuario
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
				//limpa o post e desabilita o getCidade
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
if(is_string($estado)) echo '<div class="mensagemErro">'.connect_bd::erro().'</div>';
else{
?>

<form action="cadastro.php" method="post">
	<table>
		<tr>
			<td>Nome *</td>
			<td>
				<input type="text" name="nome" size="40" placeholder="Nome"
				<?php 
				if(isset($_POST['nome'])){//caso exista um post
					if(isset($erro['nome'])) echo 'style="border-color: #D90000;" ';//caso haja um erro, coloca o campo em vermelho
					echo 'value="'.$_POST['nome'].'"';//mostra o valor do campo
				}
				?>
				>
				<div class="erro">
				<?php
					//mostra o erro, se tiver
					echo @$erro['nome'];
				?>
				</div>
			</td>
		</tr>
		<tr>
			<td>Email *</td>
			<td>
				<input type="text" name="email" placeholder="Ex: jogo@jogo.com" size="30"
				<?php //idem nome
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
				<?php  //idem nome
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
				<?php //idem nome
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
				<?php  //idem nome
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
				<?php  //idem nome
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
				<?php //idem nome
				$selecionado = null;
				if(isset($_POST['estado']) && strlen($_POST['estado']) > 0){
					$selecionado = array($_POST['estado'], Estado::getNome($_POST['estado']));
				}
				
				//mostra o select dos estados
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
				
				if(isset($_POST['estado']) && strlen($_POST['estado']) > 0){//caso o estado estaja selecionado
					$cid = Cidade::selectCidade(addslashes($_POST['estado']));//pega as cidades de acordo com o estado
					if(isset($_POST['cidade']) && strlen($_POST['cidade']) > 0){
						//caso a cidade esteja marcada
						$selecionado = array($_POST['cidade'], Cidade::getNome($_POST['cidade']));
					}
				}
				
				//mostra o select das cidades
				select('cidade', $cid, false, null, $selecionado); 
				?>
				<div class="erro"><?php echo @$erro['cidade']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Endereço *</td>
			<td>
				<input type="text" name="endereco" size="50" placeholder="Ex: Rua Fulano, 5, Centro"
				<?php  //idem nome
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
				<?php  //idem nome
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
//rodape
fim(); 
?>