<?php
require_once '../../admin/connect_bd.php';

class Estado{
	
	public static function add($nome){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("INSERT INTO `estado` (`id`, `nome`) VALUES (NULL, '".$nome."')"))) 
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function edit($dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("UPDATE `estado` SET `nome`='".$dados['nome']."' WHERE id= $dados[id]")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function remove($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("DELETE FROM `estado` WHERE `id`=$id")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function select($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if($query = mysql_query("SELECT * FROM `estado` WHERE `id`=$id")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function getNome($estado){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT `nome` FROM `estado` WHERE `id`=".addslashes($estado))){
			$nome = mysql_fetch_assoc($query);
			return $nome['nome'];
		}
		else return connect_bd::error();
			
		mysql_close($con);
	}

	public static function getIdByCidade($cidade){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query(
			"SELECT `estado`.`id` FROM `estado` 
			LEFT JOIN `cidade` ON `cidade`.`estado` = `estado`.`id`
			WHERE `cidade`.`id`=".addslashes($cidade))){
					
			$id = mysql_fetch_assoc($query);
			return $id['id'];
		}
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function isEstado($estado, $cidade){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query(
			"SELECT * FROM `estado` 
			LEFT JOIN `cidade` ON `cidade`.`estado` = `estado`.`id` 
			WHERE `cidade`.`id`=$cidade AND `estado`.`id`=$estado"
		)) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectAll(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `estado`")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
}
?>