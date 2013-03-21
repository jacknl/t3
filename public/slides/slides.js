/*
 * funcao que exibe um slide com imagem mandadas por parametro
 * a funcao deve ser chamada da seguinte forma
 * EX: 
 * slidesMod({
		'elemento': string do elemento onde sera exibido o slide,
		'tempo': tempo para exibicao de cada imagem, em milesegundos,
		'imagens': array de objetos com as imagens, normalmente usa-se no maximo 5 imagem
			[{
				'imagem': string com o caminho da imagem
				'link': string com um link, que quando a imagem é clicada, redimensiona a pagina para aquele link
				'titulo': strind do titulo da imagem, quando o mouse é passado em cima da imagem
				'texto': string com um texto que sera exibido no canto inferior da imagem
			},
			{
				'imagem': '../slides/img/img_2.jpg',
				'link': '',
				'titulo': 'disturbed 2',
				'texto': 'Disturbed 2'
			},
			...
	});
 */

function slidesMod(dados){
	var slides = {};
	dados.atual = 1;
	
	//atribui css para o determindo elemento
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
	
	//seta o intervalo para a exibicao das imagens
	slides.intervalo = function(){
		dados.intervalo = setInterval(function(){
			//troca para a proxima imagem
			slides.seleciona(parseInt(dados.atual + 1)); 
		}, dados.tempo == undefined ? 1000 : dados.tempo);
		
		dados.timeout = false;
	};
	
	//cria o html do slide
	slides.inicio = function(){
		dados.img = []; //vetor com os elementos da imagem
		dados.descricao = []; //vetor com os elementos da descricao(texto)
		dados.lista_imagem = []; //vetor com os elementos das imagens pequenas do lado direito
		
		for(var i in dados.imagens){
			dados.img[i] = $('<a href="' + dados.imagens[i].link + '"><img src="' + dados.imagens[i].imagem + '" height="453" width="807" alt="" title="' + dados.imagens[i].titulo + '"></a>');
			dados.descricao[i] = $('<p class="descricao">' + dados.imagens[i].descricao + '</p>');
			dados.lista_imagem[i] = $('<div id="slides_' + dados.elemento.replace(' ', '') + '_' + i + '"><img src="' + dados.imagens[i].imagem + '" height="87" width="155" alt=""></div>').click(function(){
				//quando clicado em uma das imagens do lado direito, exibe a imagem selecionada
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
		
		//insere o slide na pagina
		$('.imagens>div:FIRST-CHILD', dados.imagens).html(dados.img);
		$('.imagens', dados.imagens).append(dados.descricao);
		$('.lista_imagens', dados.imagens).html(dados.lista_imagem);
		
		//seleciona a primeira imagem para exibir
		slides.seleciona(0); 
		//ativa o intervalo das imagens
		slides.intervalo();
	};
	
	//muda a imagem do slide
	slides.seleciona = function(img){
		//quando a imagemm clicada é a mesma que esta sendo exibida
		if(dados.atual == img) return;
		//troca o indice do vetor com as imagens
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
	
	//cria e inicia o slide
	slides.inicio();
	
}