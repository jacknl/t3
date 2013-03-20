<?php
require_once '../../admin/connect_bd.php';

class jogoGet{
	
	public static function adicionar($tabela, $nome){
		$con = connect_bd::connect();
		if(!$con) return $con;
		
		if(mysql_query("INSERT INTO `$tabela` (`id`, `nome`) VALUES (NULL, '$nome')")){
			$id = mysql_insert_id();
			mysql_close($con);
			return $id;
		}
		else{
			mysql_close($con);
			return false;
		}
	}
	
	public static function remover($tabela, $id){
		$con = connect_bd::connect();
		if(!$con) return $con;
	
		if(mysql_query("DELETE FROM `$tabela` WHERE `id`='$id'")){
			mysql_close($con);
			return true;
		}
		else{
			mysql_close($con);
			return false;
		}
	}

}
?>