<?php
class exam_askControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$exid=get("exid","i");
		$exam=M("mod_exam")->selectRow("exid=".$exid);
		$this->smarty->goassign(array(
			"exam"=>$exam
		));
		$this->smarty->display("exam_ask/index.html");
	}
	public function onList(){
		$exid=get("exid","i");
		$ops=array(
			"where"=>" exid=".$exid
		);
		$ask=M("mod_exam_ask")->select($ops);
		if($ask){
			foreach($ask as $v){
				$topicids[]=$v["topicid"];
			}
			$typeList=MM("exam","exam_topic")->typeList();
			$topics=MM("exam","exam_topic")->getListByIds($topicids);
			foreach($ask as $k=>$v){
				$v["title"]=$topics[$v["topicid"]]["title"];
				$v["typeid"]=$topics[$v["topicid"]]["typeid"];
				$v["typeid_title"]=$typeList[$v["typeid"]];
				$ask[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$ask
		));
	}
	public function onSave(){
		$id=get_post("id","i");
		$data=M("mod_exam_ask")->postData();
		if($id){
			$row=M("mod_exam_ask")->selectRow("id=".$id);
			$topicid=$row["topicid"];
			$exid=$row["exid"];
		}else{
			$exid=get_post("exid","i");
			$topicid=$data["topicid"];
			$row=M("mod_exam_ask")->selectRow("exid=".$exid." AND topicid=".$topicid);
			if($row){
				$this->goAll("该考题已经添加过了",1);
			}
		}
		
		$exam=M("mod_exam")->selectRow("exid=".$exid);
		
		
		if($id){
			M("mod_exam_ask")->update($data,"id=".$id);
		}else{
			$data["exid"]=$exid;
			M("mod_exam_ask")->insert($data);
		}
		
		$this->goAll("保存成功");
	}
	
	public function onDelete(){
		$id=get_post("id","i");
		$row=M("mod_exam_ask")->selectRow("id=".$id);
		M("mod_exam_ask")->delete("id=".$id);
		$this->goAll("删除成功");
	}
	
}
?>