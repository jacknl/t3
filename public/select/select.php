<?php
/*
 * Na funcao de select e multiselect recebe como parametro
 * vamos definir elemento como o campo de select/multiselect
 * funcao(1, 2, 3, 4, 5)
 * 1: id do elemento, deve ser unico na pagina;
 * 2: serao as opcoes que serao exibidas, por padrao sera uma array vazia. Recebe uma array do seguinte formato arra('id' => valor(value) da opcao, 'nome' => nome da opcao que sera exibida);
 * 3: abilitar a opcao(input) para adicionar uma nova opcao e o botao de remover, por padrao é falso. Recebe uma string com o link para a pagina onde sera adicionado a nova opcao no banco;
 * 4: recebe o nome que sera exibido no input, por padrao e 'Adicionar';
 * 5: recebe a(s) opcao(oes) que serao marcadas as opcoes, por padrao sera false. Recebe uma string com o(s) valor(es) da(s) opcao(oes) que aparecem no indice 'id' da array do item 2, recebe multiplos valores separados por virgula;
 */


//marca a opcao
function multiselectMarcaOpcao($marca, $opcoes){
	foreach($opcoes as $value){
		if($value == $marca) return 'checked';
	}
	return '';
}

function multiselect($id, $dados = null, $input = false, $nomeInput = 'Adicionar', $selecionado = false){
	if($selecionado != false) $tmp = explode(',', htmlentities($selecionado));

	$html = '<div class="select">
				<div class="multiselect">
					<p>Opções</p>
					<input type="hidden" name="'.$id.'"'.($selecionado != false ? ' value="'.htmlentities($selecionado).'"' : '').'>
					<table>
						<tbody>';
	
	if($dados != null && mysql_num_rows($dados) > 0){
		while($linha = mysql_fetch_assoc($dados)){
			$html .= 		'<tr>
								<td>
									<label for="'.$id.$linha['id'].'">
										<input type="checkbox" id="'.$id.$linha['id'].'" value="'.$linha['id'].'" '.($selecionado != false ? multiselectMarcaOpcao($linha['id'], $tmp) : '').'>'.$linha['nome'].'
									</label>
								</td>'.
								($input ? '<td><div class="remover">x</div></td>' : '')
						   .'</tr>';
		}
	}

	if($input){
		$html .= 	    '</tbody>
					</table>
				</div>
				<input type="text" id="'.$input.'" placeholder="'.$nomeInput.'" size="25">
				<div class="botao">Adicionar</div>
			</div>';
	}
	else{
		$html .= 	'</table>
				</div>
			</div>';
	}
	
 	echo $html;
}

function select($id, $dados = null, $input = false, $nomeInput = 'Adicionar', $selecionado = null){
	$html = '<div class="select">
					<div class="multiselect">
						<p>'.(isset($selecionado[1]) ? $selecionado[1] : 'Selecione').'</p>
						<input type="hidden" name="'.$id.'"'.(isset($selecionado[0]) ? ' value="'.htmlentities($selecionado[0]).'"' : '').'>
						<table class="multiselect_one">
							<tbody>';
	
	if($dados != null && mysql_num_rows($dados) > 0){
		while($linha = mysql_fetch_assoc($dados)){
			$html .=			'<tr>
									<td>
										<p>'.$linha['nome'].'</p>
										<input type="hidden" value="'.$linha['id'].'">
									</td>'.
									($input ? '<td><div class="remover">x</div></td>' : '')
							  .'</tr>';
		}
	}
	
	if($input){
		$html .=		    '</tbody>
						</table>
					</div>
					<input type="text" id="'.$input.'" placeholder="'.$nomeInput.'" size="25">
					<div class="botao">Adicionar</div>
				</div>';
	}
	else{
		$html .=		'</table>
					</div>
				</div>';
	}
	
	echo $html;
}
?>