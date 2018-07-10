<?php

include "functions.php";

$dbName = "takipci_panelkedi_com";
	
$link = connectDb($dbName);

$sql = "SHOW TABLES FROM $dbName";
$result = mysqli_query($link,$sql);

$templateModel = getModelTemplate();
$templateMapper = getMapperTemplate();
$templateDbtable = getDbtableTemplate();

while ($row = mysqli_fetch_row($result)) {
	$tableName = $row[0]; //tablo adi
	$modelname = tableNameOptmize($tableName);

	//Create Model Start
	$filename = "models/".$modelname.".php";
	$properties_phpdoc = "";
	$columnRes = mysqli_query($link, 'DESCRIBE '.$tableName);
	while ( $row = mysqli_fetch_array ( $columnRes ) ) {
		$properties_phpdoc.= "* @property mixed ".$row[0]."\n";
	}
	$new_file_model = str_replace("{properties_phpdoc}", $properties_phpdoc, $templateModel);
	$properties	= "";
	$columnRes = mysqli_query($link, 'DESCRIBE '.$tableName);
	while ( $row = mysqli_fetch_array ( $columnRes ) ) {
		$properties	.= "            '".$row[0]."' => null,\n";
	}
	$new_file_model = str_replace("{properties}", $properties, $new_file_model);
	$new_file_model = str_replace("{modelname}", $modelname, $new_file_model);
	createFile($filename,$new_file_model);
	//Create Model Finish
	echo $filename." successfully created \n <br>";
	
	//Create Mapper Start
	$filenameMapper = "models/Mapper/".$modelname.".php";
	$new_file_mapper = str_replace("{modelname}", $modelname, $templateMapper);
	createFile($filenameMapper,$new_file_mapper);
	//Create Mapper Finish
	echo $filenameMapper." successfully created \n <br>";
	
	//Create DbTable Start
	$filenameDbtable = "models/DbTable/".$modelname.".php";
	$new_file_dbtable = str_replace("{modelname}", $modelname, $templateDbtable);
	$new_file_dbtable = str_replace("{tablename}", $tableName, $new_file_dbtable);
	createFile($filenameDbtable,$new_file_dbtable);
	//Create DbTable Finish
	echo $filenameDbtable." successfully created \n <br>";
	
	echo "------------------- \n <br>";
}
?>