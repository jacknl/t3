<?php
require_once '../../admin/connect_bd.php';

class Usuario{
	
	public static function add($dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("INSERT INTO `usuario` (`id`, `cidade`, `nome`, `login`, `senha`, `data_nascimento`, `endereco`, `complemento`, `cpf`, `cep`, `master`, `email`) VALUES (NULL, '".implode("','", $dados)."')"))) 
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function edit($dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
			
		if(!(mysql_query("UPDATE `usuario` SET `cidade`='".$dados['cidade']."', `nome`='".$dados['nome']."', `login`='".$dados['login']."', `senha`='".$dados['senha']."', `data_nascimento`='".$dados['data_nascimento']."', `endereco`='".$dados['endereco']."',`complemento`='".$dados['complemento']."', `cpf`='".$dados['cpf']."', `cep`='".$dados['cep']."', `master`='".$dados['master']."', `email`='".$dados['email']."' WHERE id='".$dados['id']."'")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function remove($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("DELETE FROM `usuario` WHERE `id`=$id")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function select($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if($query = mysql_query("SELECT * FROM `usuario` WHERE `id`=$id")) return mysql_fetch_assoc($query);
		else return connect_bd::error();
			
		mysql_close($con);
	}

	public static function getSenha($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT `senha` FROM `usuario` WHERE `id`='".$id."'")){
			$senha = mysql_fetch_assoc($query);
			return $senha['senha'];
		}
		else return connect_bd::error();
			
		mysql_close($con);
	}

	public static function selectByEmail($email){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `usuario` WHERE `email`='".$email."'")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}

	public static function selectByCpf($cpf){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `usuario` WHERE `cpf`='".$cpf."'")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}

	public static function selectByLogin($login){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `usuario` WHERE `login`='".$login."'")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectAll(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `usuario`")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
}
?>