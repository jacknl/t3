<?php 
require_once '../../admin/page.php';
require_once '../../tabelas/Jogo.php';
inicio(
	'<link rel="stylesheet" href="../../public/slides/slides.css" type="text/css" media="screen" title="default">
	<script src="../../public/slides/slides.js" type="text/javascript"></script>
	<link rel="stylesheet" href="index.css" type="text/css" media="screen" title="default">
	<script src="index.js" type="text/javascript"></script>'
);

$jogos = Jogo::ultimosJogos();

?>

<div class="slides" style="margin-top: 15px; margin-bottom: 15px;"></div>


<script type="text/javascript">
<?php 
echo
'$(document).ready(function(){
	slidesMod({
		\'elemento\': \'.conteudo .slides\',
		\'imagens\': [';

$tmp = array();
while($linha = mysql_fetch_assoc($jogos)){
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
});';

?>
</script>

<?php 
fim(); 
?>