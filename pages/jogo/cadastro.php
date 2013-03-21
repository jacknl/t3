<?php 
require_once '../../admin/page.php';
require_once '../../admin/funcoes.php';
require_once '../../public/select/select.php';
require_once '../../tabelas/Genero.php';
require_once '../../tabelas/Distribuidora.php';
require_once '../../tabelas/Desenvolvedora.php';
require_once '../../tabelas/Plataforma.php';
require_once '../../tabelas/Serie.php';
require_once '../../tabelas/Jogo.php';
require_once '../../tabelas/Controi.php';
require_once '../../tabelas/Organizar.php';
require_once '../../tabelas/Pc.php';
require_once '../../tabelas/Executa.php';
require_once '../../tabelas/SistemaOperacional.php';
header("Content-type: text/html; charset=iso-8859-1");
session_start();

//somente usuarios logados como master(administradores)
if(!isset($_SESSION['logado']) || !$_SESSION['logado'] || !isset($_SESSION['usuario']['master']) || $_SESSION['usuario']['master'] == 0) header('location: ../index/index.php');

inicio(
	'<link rel="stylesheet" href="../../public/select/select.css" type="text/css" media="screen" title="default">
	<link rel="stylesheet" href="cadastro.css" type="text/css" media="screen" title="default">
	<script src="../../public/select/select.js" type="text/javascript"></script>
	<script src="cadastro.js" type="text/javascript"></script>'
);

//pega os dados das tabelas necessarios para o cadastro
$genero = Genero::selectAll();
$distribuidora = Distribuidora::selectAll();
$desenvolvedora = Desenvolvedora::selectAll();
$plataforma = Plataforma::selectAll();
$serie = Serie::selectAll();
$so = SistemaOperacional::selectAll();
$cadastro = false;

if(isset($_POST['cadastrar'])){
	$idPc = Plataforma::gedIdPd('Computador');
	$erro = funcoes::validarCamposJogo($_POST, $idPc);
	
	if(count($erro) == 0){
		$dados = $_POST;
		unset($dados['cadastar']);
		//acentuacao
		$dados['requisitos_minimos'] = utf8_encode($dados['requisitos_minimos']);
		$dados['requisitos_recomendados'] = utf8_encode($dados['requisitos_recomendados']);
		$dados['descricao'] = utf8_encode($dados['descricao']);
		
		$dados = funcoes::validarCamposBD($dados);
		$erro = funcoes::validarCamposJogo($_POST, $idPc);
		
		if(count($erro) == 0){
			//insere o jogo
			Jogo::add(array(
				$dados['nome'],
				$dados['genero'],
				$dados['distribuidora'],
				funcoes::toDataUS($dados['data_lancamento']),
				$dados['descricao'],
				$dados['multiplayer'] == 'true' ? true : false,
				$dados['download'] == 'true' ? true : false,
				$dados['online'] == 'true' ? true : false
			), strlen($dados['serie']) == 0 ? 'NULL' : $dados['serie']);
			
			//id do jogo adicionado
			$idJogo = Jogo::ultimoId();
			
			//insere a(s) desenvolvedora(s) do jogo
			$tmp = explode(',', $dados['desenvolvedora']);
			Controi::addMulti($idJogo, $tmp);
			
			//insere a(s) plataforma(s) do jogo
			$tmp = explode(',', $dados['plataforma']);
			Organizar::addMulti($idJogo, $tmp);
			
			//verifica se a opcao a suporte ao Computador foi abilitada
			$aux = false;
			for($x = 0; $x < count($tmp); $x++){
				if($tmp[$x] == $idPc){
					$aux = true;
					break;
				}
			}
			
			//caso a opcao Computador estaja marcada
			if($aux){
				//insere os requisitos do jogo
				Pc::add(array('id_jogo' => $idJogo, 'requisitos_minimos' => $dados['requisitos_minimos'], 'requisitos_recomendados' => $dados['requisitos_recomendados']));
				
				//insere os sistemas opercionais suportados
				$tmp = explode(',', $dados['so']);
				Executa::addMulti($idJogo, $tmp);
			}
			
			//cadastrado com sucesso
			$cadastro = true;
			$_POST = array();
		}
		
	}
}

?>

<div class="titulo">Cadastrar Jogo</div>

<?php
if($cadastro) echo '<div class="mensagemOk">Cadastro realizado com sucesso!</div>'; 
//erro na busca dos dados no banco de dados ou na connecção
if(is_string($genero) || 
	is_string($distribuidora) ||
	is_string($desenvolvedora) ||
	is_string($plataforma) ||
	is_string($serie)||
	is_string($so)
	) echo '<div class="mensagemErro">'.connect_bd::erro().'</div>';
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
			<td>Data de Lançamento *</td>
			<td>
				<input type="text" name="data_lancamento" id="data_lancamento" size="10"
				<?php 
				if(isset($_POST['data_lancamento'])){
					if(isset($erro['data_lancamento'])) echo 'style="border-color: #D90000;" ';
					echo 'value="'.$_POST['data_lancamento'].'"';
				}
				?>
				>
				<div class="erro"><?php echo @$erro['data_lancamento']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Gênero *</td>
			<td>
				<?php
				$selecionado = null;
				if(isset($_POST['genero']) && strlen($_POST['genero']) > 0){
					$selecionado = array($_POST['genero'], Genero::getNome($_POST['genero']));
				}
				
				select('genero', $genero, 'get.php?t=genero', 'Adicionar Gênero', $selecionado); 
				?>
				<div class="erro"><?php echo @$erro['genero']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Distribuidora *</td>
			<td>
				<?php
				$selecionado = null;
				if(isset($_POST['distribuidora']) && strlen($_POST['distribuidora']) > 0){
					$selecionado = array($_POST['distribuidora'], Distribuidora::getNome($_POST['distribuidora']));
				}
				
				select('distribuidora', $distribuidora, 'get.php?t=distribuidora', 'Adicionar Distribuidora', $selecionado); 
				?>	
				<div class="erro"><?php echo @$erro['distribuidora']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Desenvolvedora *</td>
			<td>
				<?php
				$selecionado = false;
				if(isset($_POST['desenvolvedora']) && strlen($_POST['desenvolvedora']) > 0){
					$selecionado = $_POST['desenvolvedora'];
				}
				
				multiselect('desenvolvedora', $desenvolvedora, 'get.php?t=desenvolvedora', 'Adicionar Desenvolvedora', $selecionado); 
				?>
				<div class="erro"><?php echo @$erro['desenvolvedora']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Série</td>
			<td>
				<?php
				$selecionado = null;
				if(isset($_POST['serie']) && strlen($_POST['serie']) > 0){
					$selecionado = array($_POST['serie'], Serie::getNome($_POST['serie']));
				}
				
				select('serie', $serie, 'get.php?t=serie', 'Adicionar Série', $selecionado); 
				?>	
				<div class="erro"><?php echo @$erro['serie']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Descrição *</td>
			<td>
				<?php 
				echo '<textarea name="descricao" rows="5" cols="45" placeholder="Descrição do Jogo"';
				if(isset($_POST['descricao'])){
					if(isset($erro['descricao'])) echo ' style="border-color: #D90000;"';	
					echo '>'.$_POST['descricao'].'</textarea>';
				}
				else echo '></textarea>';
				?>
				<div class="erro"><?php echo @$erro['descricao']; ?></div>
			</td>
		</tr>
		<tr id="plataforma">
			<td>Plataforma *</td>
			<td>
				<?php
				$selecionado = false;
				if(isset($_POST['plataforma']) && strlen($_POST['plataforma']) > 0){
					$selecionado = $_POST['plataforma'];
				}
				
				multiselect('plataforma', $plataforma, false, null, $selecionado); 
				?>	
				<div class="erro"><?php echo @$erro['plataforma']; ?></div>
			</td>
		</tr>
		<tr class="pc">
			<td>Requisitos Mínimos *</td>
			<td>
				<?php 
				echo '<textarea name="requisitos_minimos" rows="5" cols="40" placeholder="Requisitos Mínimos do Jogo"';
				if(isset($_POST['requisitos_minimos'])){
					if(isset($erro['requisitos_minimos'])) echo ' style="border-color: #D90000;"';	
					echo '>'.$_POST['requisitos_minimos'].'</textarea>';
				}
				else echo '></textarea>';
				?>
				<div class="erro"><?php echo @$erro['requisitos_minimos']; ?></div>
			</td>
		</tr>
		<tr class="pc">
			<td>Requisitos Recomendados *</td>
			<td>
				<?php 
				echo '<textarea name="requisitos_recomendados" rows="5" cols="40" placeholder="Requisitos Recomendados do Jogo"';
				if(isset($_POST['requisitos_recomendados'])){
					if(isset($erro['requisitos_recomendados'])) echo ' style="border-color: #D90000;"';	
					echo '>'.$_POST['requisitos_recomendados'].'</textarea>';
				}
				else echo '></textarea>';
				?>
				<div class="erro"><?php echo @$erro['requisitos_recomendados']; ?></div>
			</td>
		</tr>
		<tr class="pc">
			<td>Sistema Operacional *</td>
			<td>
				<?php
				$selecionado = null;
				if(isset($_POST['so']) && strlen($_POST['so']) > 0){
					$selecionado = $_POST['so'];
				}
				
				multiselect('so', $so, 'get.php?t=sistemaoperacional', 'Adicionar Sistema Operacional', $selecionado); 
				?>	
				<div class="erro"><?php echo @$erro['so']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Online *</td>
			<td>
				<label for="online_true">
					<input type="radio" name="online" id="online_true" value="true"
						<?php if(isset($_POST['online']) && $_POST['online'] == 'true') echo 'checked="checked"'; ?>
					>Sim
				</label>
				<label for="online_false">
					<input type="radio" name="online" id="online_false" value="false"
						<?php if(isset($_POST['online']) && $_POST['online'] == 'false') echo 'checked="checked"'; ?>
					>Não
				</label>
				<div class="erro"><?php echo @$erro['online']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Possui Multiplayer *</td>
			<td>
				<label for="multiplayer_true">
					<input type="radio" name="multiplayer" id="multiplayer_true" value="true"
						<?php if(isset($_POST['multiplayer']) && $_POST['multiplayer'] == 'true') echo 'checked="checked"'; ?>
					>Sim
				</label>
				<label for="multiplayer_false">
					<input type="radio" name="multiplayer" id="multiplayer_false" value="false"
						<?php if(isset($_POST['multiplayer']) && $_POST['multiplayer'] == 'false') echo 'checked="checked"'; ?>
					>Não
				</label>
				<div class="erro"><?php echo @$erro['multiplayer']; ?></div>
			</td>
		</tr>
		<tr>
			<td>Suporte a Download *</td>
			<td>
				<label for="download_true">
					<input type="radio" name="download" id="download_true" value="true"
						<?php if(isset($_POST['download']) && $_POST['download'] == 'true') echo 'checked="checked"'; ?>
					>Sim
				</label>
				<label for="download_false">
					<input type="radio" name="download" id="download_false" value="false" 
						<?php if(isset($_POST['download']) && $_POST['download'] == 'false') echo 'checked="checked"'; ?>
					>Não
				</label>
				<div class="erro"><?php echo @$erro['download']; ?></div>
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
//se a opcao 'Computador' estiver marcada, mostra os campos de requisitos e sistema operacional
echo '<script> opcoesPc(); </script>';

}
fim(); 
?>