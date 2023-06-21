<?php
class test_tableControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onChange(){
		//alter table sky_ad default charset=utf8mb4
		$res=M("article")->getAll("show tables");
		foreach($res as $rs){
			$table= array_shift($rs);
			M("article")->query("alter table {$table} default charset=utf8mb4;");
		}
		echo "修改完毕";
	}
	public function onDefault(){
		$key=get("key","h");
		if(!$key){
			$key="sky_mod_b2c";
		}
		$type=get("type","h");
		if(!$type){
			$type="create";
		}
		$len=strlen($key);
		$res=M("article")->getAll("show tables");
		$tables=array();
		$createTables="";
		if($res){
			foreach($res as $rs){
				$table= array_shift($rs);
				if(substr($table,0,$len)==$key){
					if($key=="b2c" && substr($table,0,$len+2)=="b2cbx" ){
						continue;
					}
					$tables[]=$table;
					//获取表结构
					$rs=M("article")->getRow("show create table $table");
					
					if($type=="create"){
						$createTables.=$rs["Create Table"].";\r\n";
					}else{
						$createTables.="drop table $table ;\r\n";
					}
					 
				}
			}
		}
		
		echo $createTables;
	}
	
}
?>