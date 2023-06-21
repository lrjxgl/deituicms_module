<?php
class group_tagsControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	
	public function onAdmin(){
		$gid=get_post("gid","i");
		$group=MM("group","group")->get($gid);
		if(!$group['isadmin']){
			$this->goAll("您无权限",1);
		}
		$where=" status<8 AND gid=".$gid;
		$url="/module.php?m=group_tags&a=admin";
		 
		
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>intval(get_post('per_page')),
			"limit"=>$limit,
			"order"=>" tagid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("group","mod_group_tags")->select($option,$rscount);
	 
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goassign(
			array(
				"list"=>$data,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"group"=>$group,
				"isadmin"=>1
			)
		);
		$this->smarty->display("group_tags/admin.html");
	}
	
	public function onSave(){
		$tagid=get_post('tagid','i');
		$data=M("mod_group_tags")->postData();
		$group=MM("group","group")->get($data['gid']);
		if(!$group['isadmin']){
			$this->goAll("您无权限",1);
		}
		$data['status']=1;
		if(empty($data['tagname'])) $this->goAll("名称不能为空");
		if($tagid){
			M("mod_group_tags")->update($data,"tagid=".$tagid);
		}else{
			M("mod_group_tags")->insert($data);
		}
		$this->goAll("保存成功");
	}
	
	public function onDelete(){
		$tagid=get('tagid','i');
		$tag=M("mod_group_tags")->selectRow("tagid=".$tagid);
		$group=MM("group","group")->get($tag['gid']);
		if(!$group['isadmin']){
			$this->goAll("您无权限",1);
		}
		M("mod_group_tags")->delete("tagid=".$tagid);
		$this->goAll("删除成功");
	}
}

?>