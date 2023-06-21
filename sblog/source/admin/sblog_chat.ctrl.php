<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class sblog_chatControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=sblog_chat&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_sblog_chat")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["content"]=$this->parse($v["content"]);
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("sblog_chat/index.html");
		}
		public function parse($con){
			preg_match("/\[([^=]*)=(.*)\]/iUs",$con,$arr);
			if(isset($arr[1])){
				switch($arr[1]){
					case "img":
						$con='<img class="chat-img" src="'.$arr[2].'" />';
						break;
					case "audio":
						$con='<audio controls class="chat-audio" src="'.$arr[2].'"></audio>';
						break;
					case "video":
						$con='<video controls class="chat-video" src="'.$arr[2].'"></video>';
						break;
					
				}
			}else{
				preg_match_all("/emo([\d]+)[\W]/iUs",$con." ",$arr);
			 
				if(isset($arr[1])){
					foreach($arr[1] as $v){
						$con=str_replace("\\emo".$v,'<span class="imEmo-'.$v.'"></span>',$con);
					}
				}
			}
			return $con;
			
		}
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_sblog_chat")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_sblog_chat")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>