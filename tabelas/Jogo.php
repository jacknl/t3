<?php
require_once '../../admin/connect_bd.php';

class Jogo{
	
	public static function add($dados, $serie){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("INSERT INTO `jogo` (`codigo`, `nome`, `genero`, `distribuidora`, `data_lancamento`, `descricao`, `multiplayer`, `download`, `online`, `serie`) VALUES (NULL, '".implode("','", $dados)."', $serie)"))) 
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function edit($codigo, $dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("UPDATE `jogo` SET `nome`='".$dados['nome']."', `genero`='".$dados['genero']."', `distribuidora`='".$dados['distribuidora']."', `data_lancamento`='".$dados['data_lancamento']."', `descricao`='".$dados['descricao']."', `multiplayer`='".$dados['multiplayer']."', `download`='".$dados['download']."', `serie`=".$dados['serie'].", `online`='".$dados['online']."' WHERE codigo = $codigo")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function remove($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("DELETE FROM `jogo` WHERE `id`=$id")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function select($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if($query = mysql_query("SELECT * FROM `jogo` WHERE `codigo`=$id")) return mysql_fetch_assoc($query);
		else return connect_bd::error();
			
		mysql_close($con);
	}

	public static function ultimoId(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT MAX(codigo) as id FROM `jogo`")){
			$id = mysql_fetch_assoc($query);
			return $id['id'];
		}
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectAll(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `jogo`")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function ultimosJogos(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `jogo` ORDER BY `data_lancamento` DESC LIMIT 5")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
}
?>