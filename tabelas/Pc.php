<?php
require_once '../../admin/connect_bd.php';

class Pc{
	
	public static function add($dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("INSERT INTO `pc` (`id_jogo`, `requisitos_minimos`, `requisitos_recomendados`) VALUES (".$dados['id_jogo'].", '".$dados['requisitos_minimos']."', '".$dados['requisitos_recomendados']."')"))) 
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function edit($id_jogo, $dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("UPDATE `pc` SET `requisitos_minimos`='".$dados['requisitos_minimos']."', `requisitos_recomendados`='".$dados['requisitos_recomendados']."' WHERE id_jogo= $id_jogo")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function remove($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("DELETE FROM `pc` WHERE `id`=$id")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function select($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if($query = mysql_query("SELECT * FROM `pc` WHERE `id_jogo`=$id")) return mysql_fetch_assoc($query);
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectAll(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `pc`")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
}
?>