<?php
$arr=glob("./fonts/*");
 
foreach($arr as $v){
	$name=str_replace(array(" ",".ttf","-","_0","_1",".ttc",".otf"),"",basename($v));
	echo '"'.$name.'"=>"'.str_replace("./fonts/","module/imgdiy/fonts/",$v).'"'.",\r\n";
}
