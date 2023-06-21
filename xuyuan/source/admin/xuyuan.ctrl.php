<?php
class xuyuanControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	 
	public function onDefault(){
		$where=" 1 ";
		$type=get("type","h");
		$type_name="全部愿望";
		switch($type){
			case "new":
				$where=" status=0 ";
				$type_name="待审愿望";
				break;
			case "pass":
				$where=" status=1 ";
				$type_name="上架愿望";
				break;
			case "forbid":
				$where=" status=2 ";
				$type_name="下架愿望";
				break;
			 
			default:
				
				$where=" status in(0,1,2) ";
				break;
		}
		$url="/moduleadmin.php?m=xuyuan";
		$limit=get('limit')?get('limit','i'):48;
		$page=get('page','i');
		$start=max(0,($page-1)*$limit);
		$nickname=get_post('nickname','h');
		$keyword=get_post('keyword','h');
		
		if($nickname){
			$where.=" AND nickname='$nickname'";
			$url.="&nickname=".urlencode($nickname);
		}
		if($keyword){
			$where.=" AND content like '%".$keyword."%' ";
			$url.="&keyword=".urlencode($keyword);
		}
		$start_time=get('start_time','h');
		$end_time=get('end_time','h');
		if($start_time){
			$where.=" AND dateline>".strtotime($start_time)." ";
			$url.="&start_time=".$start_time;
		}
		
		if($end_time){
			$where.=" AND dateline<".strtotime($end_time)." ";
			$url.="&end_time=".$end_time;
		}
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_xuyuan")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['timeago']=timeago($v['dateline']);
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->assign(array(
			"pagelist"=>$pagelist,
			"rscount"=>$rscount,
			"data"=>$data,
			"type"=>$type,
			"type_name"=>$type_name
		));
		$this->smarty->display("xuyuan/index.html");
	}
	
	public function onAdd(){
		$id=get_post('id','i');
		$data=M("mod_xuyuan")->selectRow("id=".$id);
		if(empty($data)) $this->goAll("数据出错",1,0,"/moduleadmin.php?m=xuyuan");
		$this->smarty->assign(array(
			"data"=>$data
		));
		$this->smarty->display("xuyuan/add.html");
	}
	
	public function onSave(){
		$content=post('content','h');
		$id=post('id','i');
		if(empty($content) or !$id) $this->goall("内容不能为空",1);
		$data=M("mod_xuyuan")->postData();
		M("mod_xuyuan")->update($data,"id=".$id);
		$this->goall("许愿成功");
	}
	
	public function onDelete(){
		$id=get('id','i');
		M("mod_xuyuan")->delete("id=".$id);
		exit(json_encode(array("error"=>0)));
	}
	
	public function onStatus(){
		$id=get_post('id',"i");
		$row=M("mod_xuyuan")->selectRow("id=".$id);
		$status=1;
		if($row["status"]==1){
			$status=2;
		}
		M("mod_xuyuan")->update(array(
			"status"=>$status
		),"id=".$id);
		$this->goAll("success",0,$status);
	}
}

?>