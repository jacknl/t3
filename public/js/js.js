var index = {};
var menu = false;

index.pesquisar = function(){
	if($('.top .menu .pesquisa').val())	window.location.href = '../index/index.php?q=' + $('.top .menu .pesquisa').val();
};

index.menuOver = function(){
	if(menu) $('.top .menu_top, .menu_top_borda').css({'opacity': '1'});
};

index.menuLeave = function(){
	if(menu) $('.top .menu_top, .menu_top_borda').css({'opacity': '0.8'});
};

index.ajustaMenu = function(){
	if($(document).scrollTop() > 130){
		$('.top .menu_top').css({
			'position': 'fixed',
			'top': '0px',
			'opacity': '0.9'
		});
		$('.menu_top_borda').css({
			'position': 'fixed',
			'top': '60px',
			'opacity': '0.9'
		});
		menu = true;
	}
	else{
		$('.top .menu_top').css({
			'position': 'relative',
			'top': 'initial',
			'opacity': '1'
		});
		$('.menu_top_borda').css({
			'position': 'relative',
			'top': '0px',
			'opacity': '1'
		});
		menu = false;
	}
};

//ajusta o tamanho minimo para a div .conteudo
index.ajustaConteudo = function(){
	$('.conteudo>div').css({'min-height': $(window).height() - 240});
};

index.logar = function(){
	$.ajax({
        type: "POST",
        url: '../index/logar.php',
        dataType: 'json',
        data: {
        	'usuario': $('.login input[name="usuario"]').val(),
        	'senha': $('.login input[name="senha"]').val()
        }
    }).done(function(data){
    	if(data != null && data.nome != undefined && data.links != undefined){
    		//tira os campos de login e insere as opcoes
    		var html = '<div class="opcoes">\
    						<p style="background-image: url(\'../../public/img/seta_baixo.png\');">Opções</p>\
    						<table>';
    		
    		for(var linha in data.links){
		    	html +=		   '<tr>\
									<td>\
										<a href="' + data.links[linha] + '">' + linha + '</a>\
									</td>\
								</tr>';
    		}
    		
		    html +=			   '<tr>\
									<td>\
										<a onclick="index.deslogar(\'' + data.sair + '\');">Sair</a>\
									</td>\
								</tr>\
							</table>\
						</div>\
						<div class="nome">' + data.nome	 + '</div>';
    							
    		
    		var elemento = $(html);
    		//mostra as opcoes
    		$('>p', elemento).click(function(){
    			if($('table', $(this).parent()).css('display') == 'none'){
    				$('table', $(this).parent()).fadeIn(250);
    				$('.top .login .opcoes>p').css({
    					'background-image': 'url("../../public/img/seta_cima.png")',
    					'background-color': '#111'
    				});
    			}
    			else{
    				$('table', $(this).parent()).fadeOut(250);
    				$('.top .login .opcoes>p').css({'background-image': 'url("../../public/img/seta_baixo.png")',
    					'background-color': 'transparent'
    				});
    			}
    		});
    		
    		$('.top .login').html(elemento);
    	}
    	else{
    		if(!$('.top .login>table').html()) index.deslogar();
    		
    		$('.login input[name="senha"]').val('');
    	}
    }).error(function(){ alert('Não foi possível logar. Verifique a sua conecção com a internet.'); });
};

index.deslogar = function(id){
	$.ajax({
        type: "POST",
        url: '../index/deslogar.php',
        data: {
        	'logout': id
        }
    }).done(function(data){
    	if(data != null){
    		//mostra os campos para login
    		var html = '<table>\
    						<tr>\
    							<td>Usuário: </td>\
    							<td><input type="text" name="usuario" placeholder="usuário"></td>\
    						</tr>\
    						<tr>\
    							<td>Senha: </td>\
    							<td><input type="password" name="senha" placeholder="senha"></td>\
    						</tr>\
    					</table>\
    					<div class="logar" onclick="index.logar();">Logar</div>\
    					<a href="../esqueceu_senha.php" class="senha">Esqueceu a senha?</a>\
    					<a href="../cadastro/cadastro.php" class="registro">Registre-se</a>';
    		
    		$('.top .login').html(html);
    	}
    	else{
    		alert('Não foi possível deslogar. Verifique a sua conecção com a internet.');
    	}
	
    }).error(function(){ alert('Não foi possível deslogar. Verifique a sua conecção com a internet.'); });

};

$(document).ready(function(){
	//ajusta menu de acordo com o tamanho da tela
	$(document).scroll(index.ajustaMenu);
	$(window).resize(function(){
		index.ajustaMenu();
		index.ajustaConteudo();
	});

	index.ajustaConteudo();
	
	//passa o mouse por cima do menu
	//$('.top .menu_top, .menu_top_borda').mouseover(index.menuOver);
	//$('.top .menu_top, .menu_top_borda').mouseleave(index.menuLeave);
	
	//aminacao de descer o menu de opcoes
	$(document).ready(function(){
		$('.top').css({
			'height': '190px',
			'transition': 'height 0.7s',
			'-webkit-transition': 'height 0.7s',
			'-moz-transition': 'height 0.7s',
			'-o-transition': 'height 0.7s',
			'-ms-transition': 'height 0.7s'
		});
		/*
		setInterval(function(){
			$('.top').css({'overflow': 'initial'});
		}, 700);
		*/
	});
	
	//tira a transparencia do menu quando fizer alguma pesquisa(estiver com o campo input selecionado)
	$('.top .menu .pesquisa').focus(index.menuOver);
	$('.top .menu .pesquisa').focusout(index.menuLeave);
	
	index.logar();
	
	//quando tecla enter no campo pesquisa for clicado
	$('.top .menu .pesquisa').keypress(function(e){
		if(e.which == 13) index.pesquisar();
	});
	
});
