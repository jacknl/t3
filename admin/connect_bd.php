<?php
class connect_bd{
	
	public static function connect(){ //connecta com o bd

		/*
		$host = 'jacknl-db.my.phpcloud.com:3306';
		$usuario = 'jacknl';
		$senha = '';
		$banco_dados = '';
		*/
		
		$host = 'localhost';
		$usuario = 'root';
		$senha = '';
		$banco_dados = 'game';
		$db = '';
		
		if(!($db = mysql_connect($host,$usuario,$senha))){ //erro na coneccao com o bd
			return 'Ocorreu um erro na conec��o com o Banco de Dados';
		}
		
		if(!(mysql_select_db($banco_dados, $db))){ //erro na coneccao com a tabela do bd
			return 'Ocorreu um erro na conec��o com a tabela do Banco de Dados';
			mysql_close($db);
		}
		
		return $db;
	}
	
	public static function error(){
		return 'Ocorreu um erro na execu��o da query no Banco de dados';
	}
	
	public static function erro(){
		return 'Ocorreu um erro inesperado em rela��o ao Banco de dados';
	}
	
}
?>
