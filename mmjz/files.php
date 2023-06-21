<?php
$from="mmjz";
$to="mmjz";
$files=array();
$dirs=array();
$arr=readAll("./");
//$arr=readAll("./source/");
function readAll($dir){
	global $files;
	global $dirs;
	$dh=opendir($dir);
	while(($file=readdir($dh))!==false){
		if($file=="." || $file=="..") continue;
		if(is_file($dir.$file)){
			$files[]=$dir.$file;
		}else{
			$dirs[]=$dir.$file;
			readAll($dir.$file."/");
		}
	}
	return $files;
}

foreach($arr as $file){
	$c=file_get_contents($file);
	$c=str_replace($from,$to,$c);
	
	file_put_contents($file,$c);
	$bname=basename($file);
	$newfile=str_replace($bname,str_replace($from,$to,$bname),$file);
	rename($file,$newfile) ;
}
foreach($dirs as $file){
	$bname=basename($file);
	$newfile=str_replace($bname,str_replace($from,$to,$bname),$file);
	rename($file,$newfile) ;
}
echo "修改成功";