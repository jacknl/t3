<?php
require_once '../../admin/connect_bd.php';

class Plataforma{
	
	public static function add($nome){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("INSERT INTO `plataforma` (`id`, `nome`, `pc`) VALUES (NULL, '".$nome."')"))) 
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function edit($dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("UPDATE `plataforma` SET `nome`='".$dados['nome']."', `pc`='".$dados['pc']."' WHERE id= $dados[id]")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function remove($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("DELETE FROM `plataforma` WHERE `id`=$id")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function select($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if($query = mysql_query("SELECT * FROM `plataforma` WHERE `id`=$id")) return mysql_fetch_assoc($query);
		else return connect_bd::error();
			
		mysql_close($con);
	}

	public static function getIdPc($pc){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT `id` FROM `plataforma` WHERE `nome`='".$pc."'")){
			$tmp = mysql_fetch_assoc($query);
			return $tmp['id'];
		}
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectAll(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `plataforma`")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function gedIdPd($pc){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT id FROM `plataforma` WHERE `nome`='$pc'")){
			$id = mysql_fetch_assoc($query);
			return $id['id'];
		}
		else return connect_bd::error();
			
		mysql_close($con);
	}
}
?>