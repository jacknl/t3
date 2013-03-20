<?php
require_once '../../admin/connect_bd.php';

class Organizar{
	
	public static function add($dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(!(mysql_query("INSERT INTO `organizar` (`id`, `jogo`, `plataforma`) VALUES (NULL, '".implode("','", $dados)."')")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function addMulti($jogo, $dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		$tmp = array();
		foreach($dados as $value){
			if(strlen($value)) $tmp[] = "(NULL, $jogo, '".$value."')";
		}
		
		if(!(mysql_query("INSERT INTO `organizar` (`id`, `jogo`, `plataforma`) VALUES ".implode(',', $tmp))))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function edit($dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("UPDATE `organizar` SET `jogo`='".$dados['jogo']."', `plataforma`='".$dados['plataforma']."' WHERE id= $dados[id]")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function editMulti($jogo, $adicionados, $dados){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		//adiciona novas desenvolvedoras(as que foram marcadas)
		foreach($dados as $key=>$value){
			if(strlen($value) != 0 && isset($adicionados[$value])){
				unset($dados[$key]);
				unset($adicionados[$value]);
			}
			else{
				self::add(array($jogo, $value));
			}
		}
		
		//remove as desenvolvedoras(as que foram desmarcadas)
		foreach($adicionados as $value){
			self::remove($value);
		}
	}
	
	public static function remove($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(!(mysql_query("DELETE FROM `organizar` WHERE `id`=$id")))
			return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function select($id){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if($query = mysql_query("SELECT * FROM `organizar` WHERE `id`=$id")) return mysql_fetch_assoc($query);
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectAll(){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if($query = mysql_query("SELECT * FROM `organizar`")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
	public static function selectByJogo($jogo){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if($query = mysql_query("SELECT * FROM `organizar` WHERE `jogo`=$jogo")) return $query;
		else return connect_bd::error();
			
		mysql_close($con);
	}
	
}
?>