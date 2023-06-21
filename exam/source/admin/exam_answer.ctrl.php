<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exam_answerControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=exam_answer&a=default";
			$type=get("type","h");
			$url="&type=".$type;
			$order="id DESC";
			switch($type){
				case "raty":
					$where.=" AND israty=1 ";
					$order="grade DESC";
					break;
				case "unraty":
					$where.=" AND israty=0 ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exam_answer")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$exids[]=$v["exid"];
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				$exs=MM("exam","exam")->getListByIds($exids,"exid,title");
				foreach($data as $k=>$v){
					$v["exam_title"]=$exs[$v["exid"]]["title"];
					$v["nickname"]=$us[$v["userid"]]["nickname"];
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
			$this->smarty->display("exam_answer/index.html");
		}
		public function onraty(){
			$id=get_post("id","i");
			$answer=M("mod_exam_answer")->selectRow(array("where"=>"id=".$id));
			if(empty($answer)){
				$this->goAll("数据出错",1);
			}
			$andata=str2arr($answer["content"]);
			$ratyData=str2arr($answer["ratycontent"]); 
			$userid=$answer["userid"];
			$exid=$answer["exid"];
			$exam=M("mod_exam")->selectRow("exid=".$exid);
			if(empty($exam)){
				$this->goAll("数据出错",1);
			}
			$askList=M("mod_exam_ask")->select(array(
				"where"=>" exid=".$exid,
				"order"=>"orderindex ASC",
				
			));
			if(empty($askList)){
				$this->goAll("数据出错",1);
			}
			 
			foreach($askList as $v){
				$topicids[]=$v["topicid"];
			}
			$data=MM("exam","exam_topic")->getListByIds($topicids,"*");
			$typeList=MM("exam","exam_topic")->typeList();
			$topics=array();
			if($data){
				foreach($data as $k=>$v){
					$v["typeid_title"]=$typeList[$v["typeid"]];
					$a=str2arr($v["jsondata"]);
					$v["ask"]=$a["ask"];
					unset($v["jsondata"]);
					//判断是否正确
					$v["user_answer"]=$andata[$v["topicid"]];
					if($v["typeid"]=='radio'){
						if($andata[$v["topicid"]]==$v["answer"]){
							$v["user_answer_result"]=true;
						}
					}elseif($v["typeid"]=='checkbox'){
						if(!is_array($andata[$v["topicid"]])){
							continue;
							unset($data[$k]);
						}else{
							$eanswer=implode("",$andata[$v["topicid"]]);
							if($eanswer==$v["answer"]){
								$v["user_answer_result"]=true;
							}
						}
						
					}elseif($v["typeid"]=='text'){
						$uak=0;
						foreach($v['ask'] as $ak=>$av){
							if($av["type"]=='input'){
								$av["user_answer"]=$v["user_answer"][$uak];
								$uak++;
								$v['ask'][$ak]=$av;
							}
							
						}
					}
					
					$topics[$v["topicid"]]=$v;
				}
			}
			
			foreach($askList as $k=>$v){
				 
				$topic=$topics[$v["topicid"]];
				$topic["grade"]=$v["grade"];
				$topic["id"]=$v["id"];
				$topic["raty_grade"]=$ratyData[$v["id"]];
				$askList[$k]=$topic;
			}
			 
			$this->smarty->goassign(array(
				"exam"=>$exam,
				"answer"=>$answer,
				"list"=>$askList
			));
			$this->smarty->display("exam_answer/raty.html");
		}
		
		public function onRatySave(){
			$id=get_post("id","i");
			$answer=M("mod_exam_answer")->selectRow(array("where"=>"id=".$id));
			if(empty($answer)){
				$this->goAll("数据出错",1);
			}
			 
			$ratyGrade=post("ratyGrade","i");
			$grade=0;
			foreach($ratyGrade as $k=>$v){
				$grade+=$v;
			}
			$ratycontent=arr2str($ratyGrade);
			M("mod_exam_answer")->update(array(
				"israty"=>1,
				"grade"=>$grade,
				"ratycontent"=>$ratycontent
			),"id=".$id);
			
			$this->goAll("保存成功");
		}
		public function onDelete(){
			$id=get("id");
			M("mod_exam_answer")->update(array(
				"status"=>11
			),"id=".$id);
			$this->goAll("success");
		} 
		
	}

?>