<?php
require_once '../../admin/connect_bd.php';

class Cidade{
	
	public static function add($nome){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("INSERT INTO `cidade` (`id`, `estado`, `nome`) VALUES (NULL, '".$nome."')"))) 
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function edit($dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("UPDATE `cidade` SET `estado`='".$dados['estado']."', `nome`='".$dados['nome']."' WHERE id= $dados[id]")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function remove($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("DELETE FROM `cidade` WHERE `id`=$id")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function select($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if($query = mysql_query("SELECT * FROM `cidade` WHERE `id`=$id")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function getNome($cidade){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT `nome` FROM `cidade` WHERE `id`=".addslashes($cidade))){
			$nome = mysql_fetch_assoc($query);
			return $nome['nome'];
		}
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectCidade($estado){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT `id`, `nome` FROM `cidade` WHERE `estado`=$estado ORDER BY `nome`")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectAll(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `cidade`")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
}
?>