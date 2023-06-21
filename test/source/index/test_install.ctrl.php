<?php
class test_installControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$res=M("article")->getAll("show tables");
		 
		$mds=array();
		if($res){
			foreach($res as $rs){
				$table= array_shift($rs);
				
				if(substr($table,0,8)!="sky_mod_"){
					continue;
				}
				$ss=explode("_",str_replace("sky_mod_","",$table));
				$module=$ss[0];
				$create=M("article")->getRow("show create table $table");
				$mds[$module][]=array(
					"create"=>$create["Create Table"].";\r\n",
					"drop"=>"drop table $table ;\r\n"
				);			
			}
		}
		foreach($mds as $md=>$tables){
			if(!file_exists("module/".$md)){
				continue;
			}
			$createsql="";
			$dropsql="";
			foreach($tables as $table){
				$createsql.=$table["create"];
				$dropsql.=$table["drop"];
			}
$createsql='<?php
$content=<<<eof
'.$createsql.'
eof;
?>'; 
$dropsql='<?php
$content=<<<eof
'.$dropsql.'
eof;
?>'; 
			file_put_contents("module/".$md."/install.sql.php",$createsql);
			file_put_contents("module/".$md."/uninstall.sql.php",$dropsql);
			echo $md."完成\r\n";
			 
		}
		
		echo "完成"; 
	}
	
}
?>