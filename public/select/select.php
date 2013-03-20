<?php

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