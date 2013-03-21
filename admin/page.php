<?php

//cabecalho da pagina, recebe como parametro arquivos que serao adicionados no head
function inicio($head = ''){
echo'<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<link rel="stylesheet" href="../../public/css/style.css" type="text/css" media="screen" title="default">
	<link rel="stylesheet" href="../../public/css/jquery-ui/jquery-ui.css" type="text/css" media="screen" title="default">
	<script src="../../public/js/jquery.js" type="text/javascript"></script>
	<script src="../../public/js/jquery-ui.js" type="text/javascript"></script>
	<script src="../../public/js/js.js" type="text/javascript"></script>
	'.$head.'
	<title>GM Game Store</title>
	</head>
	<body>
		<div class="top">
			<div class="icon_top">
				<div class="icon">
				<a href="../index/index.php">
					<img src="../../public/img/icon_2.png" alt="logo">
				</a>
				<p>GM Game Store</p>
				<div class="login">
					<!-- Campos de login -->
				</div>
			</div>
			</div>
			<div class="menu_top">
				<div class="menu">
					<a '.(@$_GET['plataforma'] == 'PC' ? 'class="aba_selecionada"' : '').' href="../index/index.php?plataforma=PC">PC</a>
					<a '.(@$_GET['plataforma'] == 'X360' ? 'class="aba_selecionada"' : '').' href="../index/index.php?plataforma=X360">X360</a>
					<a '.(@$_GET['plataforma'] == 'PS3' ? 'class="aba_selecionada"' : '').' href="../index/index.php?plataforma=PS3">PS3</a>
					<a '.(@$_GET['plataforma'] == 'Wii' ? 'class="aba_selecionada"' : '').' href="../index/index.php?plataforma=Wii">Wii</a>
					<input class="pesquisa" placeholder="Pesquisar" name="pesquisar">
					<img src="../../public/img/pesquisa.png" alt="" onclick="index.pesquisar();">
				</div>
			</div>
		</div>
		<div class="menu_top_borda"></div>
		<div class="conteudo">
			<div>';
}

//rodape da pagina
function fim(){
	echo   '</div>
		</div>
		<div class="rodape">
			<div>2013. Desenvolvido por Gustavo Henrique knob (sir.jacknl@gmail.com), Maikiel Diones Roos (maikiel_roos@hotmail.com).</div>
		</div>
	</body>
	</html>';
}
?>