<?php
class funcoes{
	
	//converte o formato da data de brasileiro para ingles
	public static function toDataUS($data){
		$tmp = explode('/', $data);
		return $tmp[2].'-'.$tmp[1].'-'.$tmp[0];
	}

	//converte o formato da data de ingles para brasileiro
	public static function toDataBR($data){
		$tmp = explode('-', $data);
		return $tmp[2].'/'.$tmp[1].'/'.$tmp[0];
	}
	
	//gera uma string randomica do tamanho $max
	public static function stringRand($max){
		$str = 'abcdefghijklmnopqrstuvxwyzABCDEFGHIJKLMNOPQRSTUVXWYZ1234567890';
		$len = strlen($str) - 1;
		$tmp = '';
		for($i = 0; $i < $max; $i++) $tmp .= $str[rand(0, $len)];
		
		return $tmp;
	}
	
	//faz o addslashes e htmlentities de string ou array e retorna uma string ou array, respectivamente.
	public static function validarCamposBD($dados){
		if(is_array($dados)){
			$campos = array();
			foreach($dados as $key=>$value) $campos[$key] = htmlentities(addslashes($value));
		}
		else $campos = htmlentities(addslashes($dados));
		
		return $campos;
	}
	
	//valida os campos para o formulario de cadastro e edicao de jogo.
	public static function validarCamposjogo($dados, $pc){
		$erro = array();
	
		if(isset($dados['nome'])){
			if(strlen($dados['nome']) < 3 || !self::isNameJogo($dados['nome'])){
				$erro['nome'] = 'Nome inválido. Deve conter somente letras com no mínimo 3 caracteres.';
			}
		}

		if(isset($dados['data_lancamento'])){
			$tmp = explode('/', $dados['data_lancamento']);
			if(strlen($dados['data_lancamento']) != 10 || count($tmp) != 3 || strlen($tmp[0]) != 2 || strlen($tmp[1]) != 2 || strlen($tmp[2]) != 4){
				$erro['data_lancamento'] = 'Data inválida.';
			}
		}

		if(isset($dados['genero']) && strlen($dados['genero']) == 0){
			$erro['genero'] = 'Genêro inválido.';
		}

		if(isset($dados['distribuidora']) && strlen($dados['distribuidora']) == 0){
			$erro['distribuidora'] = 'Distribuidora inválido.';
		}

		if(isset($dados['desenvolvedora']) && strlen($dados['desenvolvedora']) == 0){
			$erro['desenvolvedora'] = 'Desenvolvedora inválido.';
		}
		
		if(isset($dados['descricao']) && strlen($dados['descricao']) < 20){
			$erro['descricao'] = 'Descrição inválido.';
		}

		if(isset($dados['plataforma'])){
			if(strlen($dados['plataforma']) == 0){
				$erro['plataforma'] = 'Plataforma inválido.';
			}
			else{
				//verifica se a opcao 'Computador' esta marcada
				$tmp = explode(',', $dados['plataforma']);
				$aux = false;
				
				foreach($tmp as $value){
					if($value == $pc){
						$aux = true;
						break;
					}
				}
				
				if($aux){//caso a opcao 'Computador' esta marcada
					if(isset($dados['requisitos_minimos']) && strlen($dados['requisitos_minimos']) < 20){
						$erro['requisitos_minimos'] = 'Requisitos mínimos inválido.';
					}

					if(isset($dados['requisitos_recomendados']) && strlen($dados['requisitos_recomendados']) < 20){
						$erro['requisitos_recomendados'] = 'Requisitos recomendados inválido.';
					}

					if(isset($dados['so']) && strlen($dados['so']) == 0){
						$erro['so'] = 'Sistema operacional inválido.';
					}
				}
			}
		}

		if(isset($dados['download']) && strlen($dados['download']) == 0){
			$erro['download'] = 'Download inválido. Seleciona um opção.';
		}

		if(isset($dados['online']) && strlen($dados['online']) == 0){
			$erro['online'] = 'Online inválido. Seleciona um opção.';
		}

		if(isset($dados['multiplayer']) && strlen($dados['multiplayer']) == 0){
			$erro['multiplayer'] = 'Multiplayer inválido. Seleciona um opção.';
		}
		
		return $erro;
	}
	
	//valida os campos de cadastro e edicao de usuario
	public static function validarCampos($dados){
		$erro = array();
		
		if(isset($dados['nome'])){
			if(strlen($dados['nome']) < 6 || !self::isName($dados['nome'])){
				$erro['nome'] = 'Nome inválido. Deve conter somente letras com no mínimo 6 caracteres.';
			}
		}

		if(isset($dados['email'])){
			if(strlen($dados['email']) < 10 || !self::isEmail($dados['email'])){
				$erro['email'] = 'Email inválido.';
			}
		}
		
		if(isset($dados['login'])){
			if(strlen($dados['login']) < 5 || !self::isLogin($dados['login'])){
				$erro['login'] = 'Login inválido. Deve conter mais de 4 caracteres.';
			}
		}
		
		if(isset($dados['senha_atual'])){
			if(isset($dados['senha']) && isset($dados['confirmar_senha']) && (strlen($dados['senha']) != 0 || strlen($dados['confirmar_senha']) != 0)){
				if(strlen($dados['senha']) < 6 || $dados['senha'] != $dados['confirmar_senha']){
					$erro['senha'] = 'Senha inválida. Deve conter mais de 5 caracteres e devem ser iguais.';
					$erro['confirmar_senha'] = '';
				}
			}
		}
		else{
			if(isset($dados['senha']) && isset($dados['confirmar_senha'])){
				if(strlen($dados['senha']) < 6 || $dados['senha'] != $dados['confirmar_senha']){
					$erro['senha'] = 'Senha inválida. Deve conter mais de 5 caracteres e devem ser iguais.';
					$erro['confirmar_senha'] = '';
				}
			}
		}
		
		if(isset($dados['cpf'])){
			if(strlen($dados['cpf']) != 11){
				$erro['cpf'] = 'CPF inválido.';
			}
			else if($dados['cpf'] == '00000000000' ||
					$dados['cpf'] == '11111111111' ||
					$dados['cpf'] == '22222222222' ||
					$dados['cpf'] == '33333333333' ||
					$dados['cpf'] == '44444444444' ||
					$dados['cpf'] == '55555555555' ||
					$dados['cpf'] == '66666666666' ||
					$dados['cpf'] == '77777777777' ||
					$dados['cpf'] == '88888888888' ||
					$dados['cpf'] == '99999999999'){
				$erro['cpf'] = 'CPF inválido.';
			}
			else{
				// Calcula os digitos verificadores(os dois ultimos digitos)
				for($t = 9; $t < 11; $t++){
					for($d = 0, $c = 0; $c < $t; $c++){
						$d += $dados['cpf']{$c} * (($t + 1) - $c);
					}
					$d = ((10 * $d) % 11) % 10;
					if($dados['cpf']{$c} != $d){
						$erro['cpf'] = 'CPF inválido.';
					}
				}
			}
		}

		if(isset($dados['data_nascimento'])){
			$tmp = explode('/', $dados['data_nascimento']);
			if(strlen($dados['data_nascimento']) != 10 || count($tmp) != 3 || strlen($tmp[0]) != 2 || strlen($tmp[1]) != 2 || strlen($tmp[2]) != 4){
				$erro['data_nascimento'] = 'Data inválida.';
			}
			else if((int)$tmp[2] > ((int)date('Y') - 10)){
				$erro['data_nascimento'] = 'Data inválida.';
			}
		}

		if(isset($dados['estado']) && strlen($dados['estado']) == 0){
			$erro['estado'] = 'Selecione um Estado.';
		}

		if(isset($dados['cidade']) && strlen($dados['cidade']) == 0){
			$erro['cidade'] = 'Selecione uma Cidade.';
		}

		if(isset($dados['endereco']) && strlen($dados['endereco']) < 10){
			$erro['endereco'] = 'Endereço inválido. Deve conter mais de 10 caracteres.';
		}

		if(isset($dados['cep']) && strlen($dados['cep']) != 8){
			$erro['cep'] = 'CEP inválido.';
		}

		if(isset($dados['master']) && strlen($dados['master']) == 0){
			$erro['master'] = 'Permissão inválido.';
		}
		
		return $erro;
	}
	
	//valida o nome do jogo
	public static function isNameJogo($nome){
		$pattern = '/^[ .a-zA-ZáéíóúÁÉÍÓÚ()0-9]+$/';
		if(preg_match($pattern, $nome)){
			return true;
		}else{
			return false;
		}
	}
	
	//valida o nome do usuario
	public static function isName($nome){
		$pattern = '/^[ .a-zA-ZáéíóúÁÉÍÓÚ]+$/';
		if(preg_match($pattern, $nome)){
			return true;
		}else{
			return false;
		}
	}
	
	//valida o login do usuario
	public static function isLogin($login){
		$pattern = '/^[_a-zA-Z-0-9]+$/';
		if(preg_match($pattern, $login)){
			return true;
		}else{
			return false;
		}
	}
	
	//valida o e-mail do usuario
	public static function isEmail($email){
		$pattern = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
		if(preg_match($pattern, $email)){
			return true;
		}else{
			return false;
		}
	}
	
}
?>