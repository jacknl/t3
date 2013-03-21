$(document).ready(function(){
	//mostra o calendario para o campo de data de nascimento do usuario
	$(".conteudo #data_nascimento").datepicker();
	
	//abilita os campos de select e multiselect
	selectMod($('.conteudo table #estado'));
	//abilita os campos de select e retorna uma funcao que adiciona novos valores para o campo cidade
	var cidade = selectMod($('.conteudo table #cidade'));
	
	//adiciona novas cidades
	$('.conteudo table #estado .multiselect_one p').click(function(){
		//faz uma requisicao para retornar as cidade de um determinado estado
		$.ajax({
	        type: "POST",
	        url: 'getCidade.php',
	        dataType: 'json',
	        data: {'estado': $('input', $(this).parent()).val()},
	    }).done(function(data){//dados recebidos com sucesso
	    	//adicinona as cidades
	    	//parametro da funcao([dados a serem inseridos], [abilita o botao de remover], [exclui as opcaes que foram adicionadas anteriormente]) 
			cidade(data, false, true);
		}).error(function(){//ouve algum erro no envio dos dados
			alert('Não foi possível realiza a operação. Verifique a sua conecção com a internet ou você não tem permissão.'); 
		});
	});
	
});
