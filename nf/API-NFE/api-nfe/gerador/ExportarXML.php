<?php

/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

$mes = ($_REQUEST["mes"])? $_REQUEST["mes"] : date("m");
$ano = date("y");
$modelo = ($_REQUEST["modelo"])? $_REQUEST["modelo"] : 65;
$nomecomeza = $_REQUEST["codigoUF"].$ano.$mes.$_REQUEST["cnpj"].$modelo;
$files_to_zip = array();

$directory = './xml/autorizadas';
$scanned_directory = scandir($directory);
foreach($scanned_directory as $d){

    if (strpos($d, $nomecomeza) !== false) {
        $files_to_zip[] = $directory.'/'.$d;
    } 
    
}

$nameA = '/zip/NF-'.$_REQUEST["cnpj"].'-'.$modelo.'-'.$ano.'-'.$mes.'-'.date("ymdss").'.zip';
$result = create_zip($files_to_zip, '.'.$nameA);

if($result) $url = 'http://' . $_SERVER['HTTP_HOST'].'/gerador'.$nameA; 


	header("Content-Type: application/json");
	echo json_encode(array("result" => $result, "url" => $url));        
