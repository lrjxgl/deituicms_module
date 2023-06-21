<?php
class vote_joinControl extends skymvc{
	public $shop_app;
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		 
		$vid=get_post("vid","i");
		$vote=M("mod_vote")->selectRow("id=".$vid);
		$this->smarty->assign(array(
			"vote"=>$vote
		));
	}
	
	public function onDefault(){
		$vid=get('vid','i');
		$url="/moduleadmin.php?m=vote_join&vid=".$vid;	
		$where=" 1 ";
		$start=get('per_page','i');
		$limit=24;
		if($vid){
			$where .="  AND vid=".$vid." ";
			$url.="&vid=".$vid;
			$vote=M("mod_vote")->selectRow("id=".$vid);
		}
		 
		$order="id DESC";
		switch(get('op')){
			case "none":
				$where.=" AND status=0 ";
				break;
			case "forbid":
				$where.=" AND status=2 ";
				break;
			default:
				$where.=" AND status=1 ";
				$order="vote_num DESC";
				break;
		}
		$url.="&op=".get('op','h');
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>$order,
			"where"=>$where
		);
		$rscount=true;
		$data=MM("vote","mod_vote_join")->select($option,$rscount);
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
			"vote"=>$vote
			
		));
		$this->smarty->display("vote_join/index.html");
	}
	
	public function onAdd(){
		$id=get('id','i');
		$data=M("mod_vote_join")->selectRow("id=".$id);
		$imgs=array();
		if(!empty($data["imgsdata"])){
			$ims=explode(",",$data["imgsdata"]);
			
			foreach($ims as $im){
				if(!empty($im)){
					$imgs[]=array(
						"imgurl"=>htmlspecialchars($im),
						"trueimgurl"=>images_site(htmlspecialchars($im))
					);
				}
			}
			 
		}
		$data["trueimgurl"]=images_site($data["imgurl"]);
		$data["trueurl"]=images_site($data["url"]);
		$this->smarty->assign(array(
			"data"=>$data,
			"imgsdata"=>$imgs
		));
		$this->smarty->display("vote_join/add.html");
	}
	
	public function onSave(){
		$vid=get_post("vid","i");
		$id=post("id",'i');
		 
		$vote=M("mod_vote")->selectRow("id=".$vid);
		if(empty($vote)){
			$this->goAll("参数出错",1);	
		}
		$row=M("mod_vote_join")->selectRow("id=".$id);
	 	$data=M("mod_vote_join")->postData();
		$data['vid']=$vid;
		$data['dateline']=time();
		
		if(!empty($data["imgsdata"])){
			$ims=explode(",",$data["imgsdata"]);
			$imgs=array();
			foreach($ims as $im){
				if(!empty($im)){
					$imgs[]=htmlspecialchars($im);
				}
			}
			$data["imgsdata"]=implode(",",$imgs);
		}
		if(!is_tel($data['telephone'])){
			$this->goAll("请正确填写手机号码",1);
		}
		if(empty($data['nickname'])){
			$this->goAll("请正确填写联系人",1);
		}
		if(empty($data['title'])){
			$this->goAll("请正确填写主题",1);
		}
	 
		if($row){
			M("mod_vote_join")->update($data,"id=".$row['id']);
			$i=$row['id'];
		}else{
			$i=M("mod_vote_join")->insert($data);
			M("mod_vote")->update(array(
				"join_num"=>$vote['join_num']+1		
			),"id=".$vid);
		}
		 
		$this->goAll("保存成功",0);
	}
	
	public function onStatus(){
		$id=get('id','i');
		$status=0;
		$row=M("mod_vote_join")->selectRow("id=".$id);
		if($row["status"]==0 || $row["status"]==2){
			$status=1;
		}else{
			$status=2;
		}
		M("mod_vote_join")->update(array("status"=>$status),"id=".$id);
		$this->goAll("保存成功",0,$status);
	}
	
	public function onRecommend(){
		$id=get('id','i');
		$status=0;
		$row=M("mod_vote_join")->selectRow("id=".$id);
		if($row["isreommend"]==0 ){
			$status=1;
		}else{
			$status=2;
		}
		M("mod_vote_join")->update(array("isrecommend"=>$status),"id=".$id);
		$this->goAll("保存成功",0,$status);
	}
	
	public function onDelete(){
		$id=get('id','i');
	 
		M("mod_vote_join")->update(array("status"=>11),"id=".$id);
		$this->goAll("删除成功");
	}
	
}

?>