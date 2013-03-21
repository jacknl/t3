function selectMod(elemento){
	//objeto com as funcoes
	var select = {};
	
	select.overflow = function(){
		var tam = parseInt($('table', elemento).css('width'));
		//caso a altura da tabela com as opcoes seja maior do que 250px, coloca overflow como auto(abilita a barra de rolagem)
		if(parseInt($('table', elemento).css('height')) > 250){
			$('tbody', elemento).css({
				'height': '250px',
				'overflow': 'auto'
			});
			
			//no css, a largura maxima da tabela de opcoes e 400px
			//caso o tamanha seja menor e ocorer um overflow acima, ele aumente a largura em + 15px,
			//para que nao precise da barra de rolagem horizontal
			if(parseInt($('table', elemento).css('width')) == tam){
				$('table', elemento).css({
					'width': (parseInt($('table', elemento).css('width')) + 15) + 'px'
				});
			}
		}
	};
	
	//quando uma opcao é maraca ou desmarcada
	select.opcoes = function(tag){//recebe a opcao marcada
		//pega o input onde estao as opcoes marcadas
		var tmp = $(tag).parent().parent().parent().parent().parent().parent();
		
		if($(tag).attr('checked')){
			//opcao foi marcada, adiciona o value da opcao do input com todas as opcoes
			$('>input', tmp).val($('>input', tmp).val() + ',' + $(tag).val());
		}
		else {
			//opcao foi desmarcada, remove o value da opcao do input com todas as opcoes
			$('>input', tmp).val($('>input', tmp).val().replace(',' + $(tag).val(), ''));
		}
	};
	
	//quando uma opcao do select for clicado
	//troca o nome do select pelo nome da opcao selecionado
	//e muda o valor do input com os valores
	select.selecionado = function(tag){
		var tmp = $(tag).parent().parent().parent().parent().parent();
		$('>p', tmp).html($(tag).html());
		$('>p', tmp).css({'background-image': 'url("../../public/img/seta_baixo.png")'});
		$('>input', tmp).val($('input', $(tag).parent()).val());
		$('table', tmp).css({'display': 'none'});
	};
	
	//quando o botao remover for clicado
	select.remover = function(tag){
		//exibe um alerta pra confirmar a remocao
		if(confirm('Tem certeza que deseja remover este item?')){
			var tmp = $(tag).parent().parent();
			tag = $(tmp).parent().parent().parent();

			//faz uma requisicao a uma pagina, e mandao como parametro:
			//opcao: remover
			//id: valor da opcao
			$.post($('>input', $(tag).parent()).attr('id'), {'opcao': 'remover', 'id': $('input', tmp).val()}, function(data){
				//caso haja um erro, exibe um alert informando que houve erro
				var erro = data.toString().split('erro');
				if(erro.length > 1 || data == '' || data == null || data == undefined || data.lenght == 0){
					alert('Ouve um erro ao excluir o dado.');
					return;
				}
				
				//remove uma opcao do select do input de opcoes e muda o nome do select para 'Selecione' que e o padra
				if(!$('label', tmp).html() && $('>p', tag).html() == $('p', tmp).html()){
					$('>p', tag).html('Selecione');
					$('>input', tag).val('');
				}
				//remove uma opcao do multiselect que tem algum valor do input de opcoes
				else if($('>input', tag).val()) $('>input', tag).val($('>input', tag).val().replace(',' + $('input', tmp).val(), ''));
				
				//remove a opcao da lista de opcoes
				tmp.fadeOut(300, function(){ 
					tmp.remove();
				});
			}).error(function(){ 
				//erro na coneccao ou com o servidor
				alert('Não foi possível realiza a operação. Verifique a sua concção com a internet.'); 
			});
		}
	};
	
	//adiciona uma nova opcao
	select.adicionar = function(tag){
		//faz uma requisicao a uma pagina, e mandao como parametro:
		//opcao: adicionar
		//nome: nome da nova opcao
		$.post($('>input', $(tag).parent()).attr('id'), {'opcao': 'adicionar', 'nome': $('>input', $(tag).parent()).val()}, function(data){
			//caso haja um erro, exibe um alert informando que houve erro
			var tmp = data.toString().split('erro');
			if(tmp.length > 1 || data == '' || data == null || data == undefined || data.lenght == 0){
				alert('Ouve um erro ao adicionar o novo dado.');
				return;
			}
			
			var html = '';
			if($('table', $(tag).parent()).attr('class') == 'multiselect_one'){//adiciona uma nova opcao no select
				html = '<tr>\
							<td>\
								<p>' + $('>input', $(tag).parent()).val() + '</p>\
								<input type="hidden" value="' + data + '">\
							</td>\
							<td><div class="remover">x</div></td>\
						</tr>';
				
				html = $(html);
				//evento para quando o botao da opcao for clicado
				$('p', html).click(function(){
					select.selecionado(this);
				});
			}
			else{//adiciona uma nova opcao no multiselect
				html = '<tr>\
							<td>\
								<label for="' + $('>div>input', $(tag).parent()).attr('name') + data + '">\
									<input type="checkbox" id="' + $('>div>input', $(tag).parent()).attr('name') + data + '" value="' + data + '">' + $('>input', $(tag).parent()).val() +
								'</label>\
							</td>\
							<td><div class="remover">x</div></td>\
						</tr>';
				
				html = $(html);
				//evento para quando o botao da opcao for clicado
				$('input', html).click(function(){
					select.opcoes(this);
				});
			}
			
			//evento para quando o botao de remover for clicado
			$('.remover', html).click(function(){
				select.remover(this);
			});
			
			//insere a nova opcao na pagina
			$('tbody', $(tag).parent()).append(html.fadeIn());
			$('>input', $(tag).parent()).val('');
		}).error(function(){ 
			//erro na coneccao ou com o servidor
			alert('Não foi possível realiza a operação. Verifique a sua concção com a internet.'); 
		});
	};
	
	//mostra o select/multiselect
	$('.select', elemento).css({'display': 'table'});
	
	//mostra as opcoes
	$('.select .multiselect>p', elemento).click(function(){
		if($('table', $(this).parent()).css('display') == 'none'){
			$('table', $(this).parent()).fadeIn(250);
			$(this).css({'background-image': 'url("../../public/img/seta_cima.png")'});
		}
		else{
			$('table', $(this).parent()).fadeOut(250);
			$(this).css({'background-image': 'url("../../public/img/seta_baixo.png")'});
		}
		select.overflow();
	});
	//adicionar uma nova opcao
	$('.select>.botao', elemento).click(function(){
		if($('>input', $(this).parent()).val()){
			select.adicionar(this);
		}
	});
	
	//quando selecionou uma opcao do select(aquele que pode selecionar apenas uma opcao)
	$('.select .multiselect .multiselect_one p', elemento).click(function(){
		select.selecionado(this);
	});
	
	//remover a opcao
	$('.select .remover', elemento).click(function(){
		select.remover(this);
	});
	
	//quando uma opcao do multiselect(aquele que pode selecionar mais de uma opcao) e marcada
	$('.select .multiselect input', elemento).click(function(){
		select.opcoes(this);
	});

	//retorna uma funcao para, se por acaso, desejasse adiciona ou mudar as opcoes
	return function(dados, remover, limpar){
		//limpa as opcoes
		if(limpar) $('tbody', elemento).html('');
		
		var html = '';
		if($('table', elemento).attr('class') == 'multiselect_one'){//insere opcao(oes) no select
			for(var key in dados){
				html += '<tr>\
							<td>\
								<p>' + dados[key] + '</p>\
								<input type="hidden" value="' + key + '">\
							</td>'
							+ (remover ? '<td><div class="remover">x</div></td>' : '') +
						'</tr>';
			}
			
			html = $(html);
			//evento para quando o botao da opcao for clicado
			$('p', html).click(function(){
				select.selecionado(this);
			});
		}
		else{//insere opcao(oes) no multiselect
			var name = $('.multiselect>input', elemento).attr('name');
			for(var key in dados){
				html += '<tr>\
							<td>\
								<label for="' + name + key + '">\
									<input type="checkbox" id="' + name + key + '" value="' + key + '">' + dados[key] +
								'</label>\
							</td>'
							+ (remover ? '<td><div class="remover">x</div></td>' : '') +
						'</tr>';
			}
			
			html = $(html);
			//evento para quando o botao da opcao for clicado
			$('input', html).click(function(){
				select.opcoes(this);
			});
		}
		
		//evento para o botao de remover
		$('.remover', html).click(function(){
			select.remover(this);
		});
		
		//insere opcoes na pagina
		$('tbody', elemento).append(html.fadeIn());
		select.overflow();
	};
}