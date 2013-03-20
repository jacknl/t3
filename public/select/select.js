function selectMod(elemento){
	var select = {};
	
	select.overflow = function(){
		var tam = parseInt($('table', elemento).css('width'));
		
		if(parseInt($('table', elemento).css('height')) > 250){
			$('tbody', elemento).css({
				'height': '250px',
				'overflow': 'auto'
			});
			
			if(parseInt($('table', elemento).css('width')) == tam){
				$('table', elemento).css({
					'width': (parseInt($('table', elemento).css('width')) + 15) + 'px'
				});
			}
		}
	};

	select.opcoes = function(tag){
		var tmp = $(tag).parent().parent().parent().parent().parent().parent();
		
		if($(tag).attr('checked')) $('>input', tmp).val($('>input', tmp).val() + ',' + $(tag).val());
		else $('>input', tmp).val($('>input', tmp).val().replace(',' + $(tag).val(), ''));
	};
	
	select.selecionado = function(tag){
		var tmp = $(tag).parent().parent().parent().parent().parent();
		$('>p', tmp).html($(tag).html());
		$('>p', tmp).css({'background-image': 'url("../../public/img/seta_baixo.png")'});
		$('>input', tmp).val($('input', $(tag).parent()).val());
		$('table', tmp).css({'display': 'none'});
	};
	
	select.remover = function(tag){
		if(confirm('Tem certeza que deseja remover este item?')){
			var tmp = $(tag).parent().parent();
			tag = $(tmp).parent().parent().parent();
			
			$.post($('>input', $(tag).parent()).attr('id'), {'opcao': 'remover', 'id': $('input', tmp).val()}, function(data){
				var erro = data.toString().split('erro');
				if(erro.length > 1 || data == '' || data == null || data == undefined || data.lenght == 0){
					alert('Ouve um erro ao excluir o dado.');
					return;
				}
				
				//select
				if(!$('label', tmp).html() && $('>p', tag).html() == $('p', tmp).html()){
					$('>p', tag).html('Selecione');
					$('>input', tag).val('');
				}
				//multiselect que tem algum valor
				else if($('>input', tag).val()) $('>input', tag).val($('>input', tag).val().replace(',' + $('input', tmp).val(), ''));
				
				tmp.fadeOut(300, function(){ tmp.remove();});
			}).error(function(){ alert('Não foi possível realiza a operação. Verifique a sua concção com a internet.'); });
		}
	};
	
	select.adicionar = function(tag){
		$.post($('>input', $(tag).parent()).attr('id'), {'opcao': 'adicionar', 'nome': $('>input', $(tag).parent()).val()}, function(data){
			var tmp = data.toString().split('erro');
			if(tmp.length > 1 || data == '' || data == null || data == undefined || data.lenght == 0){
				alert('Ouve um erro ao adicionar o novo dado.');
				return;
			}
			
			var html = '';
			if($('table', $(tag).parent()).attr('class') == 'multiselect_one'){
				html = '<tr>\
							<td>\
								<p>' + $('>input', $(tag).parent()).val() + '</p>\
								<input type="hidden" value="' + data + '">\
							</td>\
							<td><div class="remover">x</div></td>\
						</tr>';
				
				html = $(html);
				$('p', html).click(function(){
					select.selecionado(this);
				});
			}
			else{
				html = '<tr>\
							<td>\
								<label for="' + $('>div>input', $(tag).parent()).attr('name') + data + '">\
									<input type="checkbox" id="' + $('>div>input', $(tag).parent()).attr('name') + data + '" value="' + data + '">' + $('>input', $(tag).parent()).val() +
								'</label>\
							</td>\
							<td><div class="remover">x</div></td>\
						</tr>';
				
				html = $(html);
				$('input', html).click(function(){
					select.opcoes(this);
				});
			}
			
			$('.remover', html).click(function(){
				select.remover(this);
			});
									
			$('tbody', $(tag).parent()).append(html.fadeIn());
			$('>input', $(tag).parent()).val('');
		}).error(function(){ alert('Não foi possível realiza a operação. Verifique a sua concção com a internet.'); });
	};
	
	$('.select', elemento).css({'display': 'table'});
	
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
	
	$('.select>.botao', elemento).click(function(){
		if($('>input', $(this).parent()).val()){
			select.adicionar(this);
		}
	});
	
	$('.select .multiselect .multiselect_one p', elemento).click(function(){
		select.selecionado(this);
	});
	
	$('.select .remover', elemento).click(function(){
		select.remover(this);
	});
	
	$('.select .multiselect input', elemento).click(function(){
		select.opcoes(this);
	});
	
	return function(dados, multi, limpar){
		if(limpar) $('tbody', elemento).html('');
		
		var html = '';
		if($('table', elemento).attr('class') == 'multiselect_one'){

			for(var key in dados){
				html += '<tr>\
							<td>\
								<p>' + dados[key] + '</p>\
								<input type="hidden" value="' + key + '">\
							</td>'
							+ (multi ? '<td><div class="remover">x</div></td>' : '') +
						'</tr>';
			}
			
			html = $(html);
			$('p', html).click(function(){
				select.selecionado(this);
			});
		}
		else{
			var name = $('.multiselect>input', elemento).attr('name');
			for(var key in dados){
				html += '<tr>\
							<td>\
								<label for="' + name + key + '">\
									<input type="checkbox" id="' + name + key + '" value="' + key + '">' + dados[key] +
								'</label>\
							</td>'
							+ (multi ? '<td><div class="remover">x</div></td>' : '') +
						'</tr>';
			}
			
			html = $(html);
			$('input', html).click(function(){
				select.opcoes(this);
			});
		}
		
		$('.remover', html).click(function(){
			select.remover(this);
		});
								
		$('tbody', elemento).append(html.fadeIn());
		select.overflow();
	};
}