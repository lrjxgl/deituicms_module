<?php
class exue_course_teacherControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$courseid=get("courseid","i");
		$course=M("mod_exue_course")->selectRow(array(
			"where"=>" courseid=".$courseid,
			"fields"=>"courseid,title"
		));
		$this->smarty->goAssign(array(
			"course"=>$course
		));
		$this->smarty->display("exue_course_teacher/index.html");
	}
	public function onlist(){
		$courseid=get("courseid","i");
		$course=M("mod_exue_course")->selectRow(array(
			"where"=>" courseid=".$courseid,
			"fields"=>"courseid,title"
		));
		$list=MM('exue','exue_teacher')->Dselect(array(
			"where"=>" shopid=".SHOPID
		));
		$tcids=M("mod_exue_course_teacher")->selectCols(array(
			"where"=>" courseid=".$courseid,
			"fields"=>"tcid"
		));
		if($list){
			foreach($list as $k=>$v){
				if(!empty($tcids) && in_array($v["tcid"],$tcids)){
					$v["ischeck"]=1;
				}else{
					$v["ischeck"]=0;
				}
				
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"course"=>$course,
			"list"=>$list
		));
		
		$this->goAll("success",0,$data);
	}
	public function onSave(){
		$courseid=get_post("courseid","i");
		$tcid=get("tcid","i");
		$ischeck=get("ischeck","i");
		$row=M('mod_exue_course_teacher')->selectRow("courseid=".$courseid." AND tcid=".$tcid);
		if($row){
			M('mod_exue_course_teacher')->delete("id=".$row["id"]);
		}else{
			 
			$id=M('mod_exue_course_teacher')->insert(array(
				"tcid"=>$tcid,
				"courseid"=>$courseid,
				"createtime"=>date("Y-m-d H:i:s"),
				"shopid"=>SHOPID
			));
		}
		$rdata=array(
			"id"=>$id
		);
		 
		$this->goAll('保存成功',0,$rdata);
	}
	
	public function ondelete(){
		$id=get_post('id','i');
		$row=M("mod_exue_course_teacher")->selectRow("id=".$id);
		if(empty($row)){
			$this->goAll("参数出错",1);
		}
		M('mod_exue_course_teacher')->delete("id=".$id);
		 
		
		$this->goAll('删除成功');
	}
}