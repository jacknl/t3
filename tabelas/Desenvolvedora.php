<?php
require_once '../../admin/connect_bd.php';

class Desenvolvedora{
	
	public static function add($nome){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("INSERT INTO `desenvolvedora` (`id`, `nome`) VALUES (NULL, '".$nome."')"))) 
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function edit($dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("UPDATE `desenvolvedora` SET `nome`='".$dados['nome']."' WHERE id= $dados[id]")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function remove($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("DELETE FROM `desenvolvedora` WHERE `id`=$id")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function select($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if($query = mysql_query("SELECT * FROM `desenvolvedora` WHERE `id`=$id")) return mysql_fetch_assoc($query);
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectAll(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `desenvolvedora`")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
}
?>