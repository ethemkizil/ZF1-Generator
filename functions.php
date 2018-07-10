<?php

function getModelTemplate()
{
	$my_file = 'template_model.php';
	$handle = fopen($my_file, 'r');
	$data = fread($handle,filesize($my_file));
	return $data;
}

function getMapperTemplate()
{
	$my_file = 'template_mapper.php';
	$handle = fopen($my_file, 'r');
	$data = fread($handle,filesize($my_file));
	return $data;
}

function getDbtableTemplate()
{
	$my_file = 'template_dbtable.php';
	$handle = fopen($my_file, 'r');
	$data = fread($handle,filesize($my_file));
	return $data;
}

function createFile($filename, $data)
{
	$handle = fopen($filename, 'w');
	fwrite($handle, $data);
}

function tableNameOptmize($tableName)
{
	return str_replace(" ","",ucwords(str_replace("_"," ",$tableName)));
}

function connectDb($dbName)
{
	$host = "localhost";
	$dbUser = "root";
	$dbPassword = "";
	

	$link = mysqli_connect ( $host, $dbUser, $dbPassword ) or die ();
	mysqli_select_db ($link, $dbName );
	mysqli_query ($link, "SET NAMES 'utf8'" );	
	return $link;
}
