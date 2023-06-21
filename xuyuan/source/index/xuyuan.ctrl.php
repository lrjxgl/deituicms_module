<?php
class xuyuanControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		M("login")->checkLogin();
	}
	
	
	public function onDefault(){
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("index.html");
	}
	
	public function onList(){
		$limit=24;
		$strt=get('per_page','i');
		$where=" status in(0,1) ";
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND content like '%".$keyword."%' ";
		}
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC"
		);
		$rscount=true;
		$data=MM("xuyuan","xuyuan")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['timeago']=timeago($v['dateline']);
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$rscount<$per_page?0:$per_page;
		$data=array(
			"list"=>$data,
			"per_page"=>$per_page
		);
		$this->goAll("success",0,$data);
		 
	}
	
	public function onAdd(){
		
		$this->smarty->display("xuyuan/add.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$content=post('content','h');
		if(empty($content)) $this->goall("内容不能为空",1);
		$shopid=get_post('shopid','i');
		$nickname=post('nickname','h');
		$data=array(
			"userid"=>$userid,
			 
			"content"=>$content,
			"nickname"=>$nickname,
			"dateline"=>time()
		);
		MM("xuyuan","xuyuan")->insert($data);
		$this->goall("许愿成功");
	}
}

?>