$(document).ready(function(){
	$(".conteudo #data_nascimento").datepicker();
	
	selectMod($('.conteudo table #estado'));
	var cidade = selectMod($('.conteudo table #cidade'));
	
	$('.conteudo table #estado .multiselect_one p').click(function(){
		$.ajax({
	        type: "POST",
	        url: 'getCidade.php',
	        dataType: 'json',
	        data: {'estado': $('input', $(this).parent()).val()},
	    }).done(function(data){
			cidade(data, false, true);
		}).error(function(){ alert('Não foi possível realiza a operação. Verifique a sua conecção com a internet ou você não tem permissão.'); });
	});
	
});
