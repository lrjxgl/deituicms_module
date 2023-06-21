<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exam_topicControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onInit(){
			if(!in_array(get("a"),array("default"))){
				M("login")->checkLogin();
			}
			
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/modulea.php?m=exam_topic&a=default";
			$limit=20;
			$start=get("per_page","i");
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
				$url.="&keyword=".urlencode($keyword);
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" topicid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exam_topic")->select($option,$rscount);
			$typeList=MM("exam","exam_topic")->typeList();
			if($data){
				foreach($data as $k=>$v){
					$v["typeid_title"]=$typeList[$v["typeid"]];
					$a=str2arr($v["jsondata"]);
					$v["ask"]=$a["ask"];
					unset($v["jsondata"]);
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
			$this->smarty->display("exam_topic/index.html");
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=exam_topic&a=my";
			$limit=20;
			$start=get("per_page","i");
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
				$url.="&title=".urlencode($keyword);
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" topicid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exam_topic")->select($option,$rscount);
			$typeList=MM("exam","exam_topic")->typeList();
			if($data){
				foreach($data as $k=>$v){
					$v["typeid_title"]=$typeList[$v["typeid"]];
					$data[$k]=$v;
				}
			}
			
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"keyword"=>$keyword,
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("exam_topic/my.html");
		}
		
		public function onAdd(){
			$userid=M("login")->userid;
			$topicid=get_post("topicid","i");
			if($topicid){
				$data=M("mod_exam_topic")->selectRow(array("where"=>"topicid=".$topicid));
				
			}
			$typeList=MM("exam","exam_topic")->typeList();
			$this->smarty->goassign(array(
				"data"=>$data,
				"typeList"=>$typeList
			));
			$this->smarty->display("exam_topic/add.html");
		}
		
		public function onSave(){
			$userid=M("login")->userid;
			$topicid=get_post("topicid","i");
			$data=M("mod_exam_topic")->postData();
			$data["content"]=str_replace(array("&gt;"),array(">"),$data["content"]);
			//解析数据
			switch($data["typeid"]){
				case "radio":
				case "checkbox":
					if(!empty($data["content"])){
						$arr=explode("\n",$data["content"]);
				 
						$ask=array();
						foreach($arr as $a){
							$ex=explode("=>",$a);
							if(isset($ex[1])){
								$ask[$ex[0]]=$ex[1];
							}
						}
						$jsondata=array(
							"ask"=>$ask,
							"answer"=>$data["answer"]
						);
						 
						$data["jsondata"]=arr2str($jsondata);
					}
					
					
				break;
				case "text":
					//获取问题
					$res=preg_replace("/\[kv=.*\]/iUs","|@@@@@|@@@",$data["content"]);
					$arr=explode("|@@@",$res);
					//获取答案
					preg_match_all("/\[kv=(.*)\]/iUs",$data["content"],$as);
					$answer=$as[1]; 
					$ask=array();
					$index=0;
					foreach($arr as $v){
						if($v=='@@'){
							$ask[]=array(
								"type"=>"input",
								 "answer"=>$answer[$index]
							);
							$index++;
						}else{
							$ask[]=array(
								"type"=>"text",
								"content"=>$v
							);
						}
						
					}
					
					$arr = preg_split("/\[kv=.*\]/iUs", $data["content"],-1,PREG_SPLIT_DELIM_CAPTURE);
					 
					$jsondata=array(
						"ask"=>$ask,
						"answer"=>$answer
					);
					$data["jsondata"]=arr2str($jsondata);
				break;
				
				
			}
			if($topicid){
				$topic=M("mod_exam_topic")->selectRow("userid=".$userid);
				if($topic["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_exam_topic")->update($data,"topicid='$topicid'");
			}else{
				$data["userid"]=$userid;
				M("mod_exam_topic")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$topicid=get_post('topicid',"i");
			$status=1;
			$row=M("mod_exam_topic")->selectRow("topicid=".$topicid);
			if($row["status"]==1){
				$status=2;
			}
			$userid=M("login")->userid;
			$topic=M("mod_exam_topic")->selectRow("userid=".$userid);
			if($topic["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_exam_topic")->update(array("status"=>$status),"topicid=$topicid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$topicid=get_post('topicid',"i");
			$userid=M("login")->userid;
			$topic=M("mod_exam_topic")->selectRow("userid=".$userid);
			if($topic["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_exam_topic")->update(array("status"=>11),"topicid=$topicid");
			$this->goAll("删除成功");
			 
		}
	}
?>		