function slidesMod(dados){
	var slides = {};
	dados.atual = 1;
	
	$(dados.elemento).css({
		'height': '455px',
		'width': '976px',
		'position': 'relative',
		'background-color': '#FFF',
		'margin-left': 'auto',
		'margin-right': 'auto',
		'border': '1px solid #888',
		'padding': '11px'
	});
	
	slides.intervalo = function(){
		dados.intervalo = setInterval(function(){
			slides.seleciona(parseInt(dados.atual + 1)); 
		}, dados.tempo == undefined ? 1000 : dados.tempo);
		
		dados.timeout = false;
	};
	
	slides.inicio = function(){
		dados.img = [];
		dados.descricao = [];
		dados.lista_imagem = [];
		
		for(var i in dados.imagens){
			dados.img[i] = $('<a href="' + dados.imagens[i].link + '"><img src="' + dados.imagens[i].imagem + '" height="453" width="807" alt="" title="' + dados.imagens[i].titulo + '"></a>');
			dados.descricao[i] = $('<p class="descricao">' + dados.imagens[i].descricao + '</p>');
			dados.lista_imagem[i] = $('<div id="slides_' + dados.elemento.replace(' ', '') + '_' + i + '"><img src="' + dados.imagens[i].imagem + '" height="87" width="155" alt=""></div>').click(function(){
				var tmp = $(this).attr('id').split('_');
				clearInterval(dados.intervalo);
				slides.seleciona(parseInt(tmp[tmp.length - 1]));
				if(!dados.timeout){
					dados.timeout = true;
					setTimeout(slides.intervalo, 1000);
				}
			});
		}
		
		dados.imagens = $(dados.elemento).html(
			'<div class="slider-content">\
				<div class="imagens">\
					<div>\
					</div>\
					<div class="transparente"></div>\
				</div>\
				<div class="lista_imagens">\
				</div>\
			</div>'
		);
		
		$('.imagens>div:FIRST-CHILD', dados.imagens).html(dados.img);
		$('.imagens', dados.imagens).append(dados.descricao);
		$('.lista_imagens', dados.imagens).html(dados.lista_imagem);
		
		slides.seleciona(0); 
		slides.intervalo();
	};
	
	slides.seleciona = function(img){
		if(dados.atual == img) return;
		if(dados.lista_imagem[img] == undefined) img = 0;
		//imagem a ser mostrada
		$('img', dados.lista_imagem[dados.atual]).css({'opacity': '0.5'});
		$('img', dados.lista_imagem[img]).css({'opacity': '1'});
		//troca imagem
		$(dados.img[dados.atual]).fadeOut();
		$(dados.img[img]).fadeIn();
		//troca texto debaixo da imagem
		$(dados.descricao[dados.atual]).fadeOut();
		$(dados.descricao[img]).fadeIn();
		//gurda a posicao da imagem atual
		dados.atual = img;
	};
	
	slides.inicio();
	
}