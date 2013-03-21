<?php 
require_once '../../admin/page.php';
require_once '../../tabelas/Jogo.php';
//cabecalho
inicio(
	'<link rel="stylesheet" href="../../public/slides/slides.css" type="text/css" media="screen" title="default">
	<script src="../../public/slides/slides.js" type="text/javascript"></script>
	<link rel="stylesheet" href="index.css" type="text/css" media="screen" title="default">'
);

if(isset($_GET['plataforma'])){//consulta no banco de dados os ultimos 5 jogos mais recentes da plataforma selecionada
	$jogos = Jogo::ultimosJogosByPlataforma(addslashes($_GET['plataforma']));
}
else{//consulta no banco de dados os ultimos 5 jogos mais recentes
	$jogos = Jogo::ultimosJogos();
}

?>

<div class="slides" style="margin-top: 15px; margin-bottom: 15px;"></div>

<?php 
if(mysql_num_rows($jogos) > 0){
	//cria o slide com os jogos retornados do banco
	echo
'<script type="text/javascript">
		$(document).ready(function(){
		slidesMod({
			\'elemento\': \'.conteudo .slides\',
			\'imagens\': [';
	
	$tmp = array();
	while($linha = mysql_fetch_assoc($jogos)){
		//se o jogo tiver uma imagem, ela estara na pasta 'public/img/jogo', sendo o nome da imagem o codigo do jogo
		//se nao tiver imagem, pega uma imagem padrao 'defalut.jpg'
		echo	'{
					\'imagem\': \'../../public/img/jogo/'.(is_file('../../public/img/jogo/'.$linha['codigo'].'.jpg') ? $linha['codigo'] : 'default').'.jpg\',
					\'link\': \'../jogo/jogo.php?codigo='.$linha['codigo'].'\',
					\'titulo\': \''.$linha['nome'].'\',
					\'descricao\': \''.$linha['nome'].'\'
				},';
	}
	
	echo	'],
			\'tempo\': 2000
		});
	});
</script>';
}
else{//retornou nenhum jogo do banco
	echo '<div class="mensagemErro">Nenhum jogo para a plataforma '.$_GET['plataforma'].' foi encontrado.</div>';
}

fim(); 
?>