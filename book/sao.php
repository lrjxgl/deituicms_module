<?php
header("Access-Control-Allow-Origin: *;");
if(isset($_POST['content'])){
	file_put_contents("sao.txt",$_POST['content']);
	echo json_encode(array("error"=>0,"message"=>"success"));
}else{
	$c=file_get_contents("sao.txt");
	echo '<style>textarea{width:400px; height:400px;}</style>';
	echo '<textarea>'.$c.'</textarea>';
}
?>