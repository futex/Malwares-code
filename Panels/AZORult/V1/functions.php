<?php
function FileToString($path)
{
	$handle = fopen($path, "r");
	if (FALSE === $handle) {
		exit("Error: not file");
	}
	$result = '';
	while (!feof($handle)) {
		$result .= fread($handle, 8192);
	}
	fclose($handle);
	return $result;

};


function AddToFile($path, $data)
{
	$fileopen=fopen($path, "a+");
	fwrite($fileopen,$data);
	fclose($fileopen);
};

function WriteToFile($path, $data)
{
	$fileopen=fopen($path, "w");
	fwrite($fileopen,$data);
	fclose($fileopen);
};

function pars($html,$t_,$_t){
$e = explode($t_,$html);
$e = explode($_t,$e[1]);
return $e[0];
}


function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

?>