<?php
require_once '../../admin/connect_bd.php';

class jogador{
	
	public static function add($nome){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("INSERT INTO `jogador` (`id`, `jogo`, `usuario`) VALUES (NULL, '".$nome."')"))) 
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function edit($dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("UPDATE `jogador` SET `jogo`='".$dados['jogo']."', `usuario`='".$dados['usuario']."' WHERE id= $dados[id]")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function remove($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("DELETE FROM `jogador` WHERE `id`=$id")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function select($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if($query = mysql_query("SELECT * FROM `jogador` WHERE `id`=$id")) return mysql_fetch_assoc($query);
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectAll(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `jogador`")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
}
?>