<?php
class olprint_book_favControl extends skymvc
{
	 
	public function __construct(){
		parent::__construct();
		 
		
	}
	
	public function onInit(){
		M("login")->checklogin();
	}
	
	public function onDefault(){
		$this->smarty->display("fav/index.html");
	}
	public function onMy(){
		$tablename=get("tablename","h");
		$this->smarty->goAssign(array(
			"tablename"=>$tablename
		));
		$this->smarty->display("fav/my.html");
	}
	
	public function onMyList(){
		$tablename="mod_olprint_book";
		 
		$userid=M("login")->userid;
		$start=get_post('per_page','i');
		$limit=24;
		$sql="select a.* 
			from ".table('fav')." as f 
			left join ".table($tablename)." as a  
			on f.objectid=a.bookid  
			where f.userid=".$userid." AND f.tablename='".$tablename."' 
			order by f.id DESC
			limit ".$start.",".$limit." 
		";
		 
		$rscount=M("fav")->selectOne(array(
			"where"=>" userid=".$userid." AND tablename='".$tablename."'",
			"fields"=>" count(*) "
		)); 
		$list=M($tablename)->getAll($sql);
		 
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page
		));
	}
	
	public function onToggle(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$objectid=get_post("objectid","i");
		$tablename=get_post("tablename","h");
		$row=M("fav")->selectRow("userid=".$userid." AND objectid=".$objectid." AND tablename='".$tablename."' ");
		//获取表结构
		$fields=M($tablename)->getFields();
		$idField=$fields[0]['Field'];
		if($row){
			M("fav")->delete("id=".$row['id']);
			$action="delete";
			M($tablename)->changenum("fav_num",-1,$idField."=".$objectid);
		}else{
			M("fav")->insert(array(
				"objectid"=>$objectid,
				"userid"=>$userid,
				"tablename"=>$tablename
			));
			$action="add";
			M($tablename)->changenum("fav_num",1,$idField."=".$objectid);
		}
		$this->goAll("success",0,$action);
	}
	
	 
	
	 
	
}
?>