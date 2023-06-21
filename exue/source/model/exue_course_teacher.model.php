<?php
class exue_course_teacherModel extends model{
	public $table="mod_exue_course_teacher";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option=array(),&$rscount=false){
		$res=parent::select($option,$rscount);
		if($res){
			foreach($res as $rs){
				$tcids[]=$rs["tcid"];
			}
			$tcs=MM("exue","exue_teacher")->Dselect(array(
				"where"=>" tcid in("._implode($tcids).") AND status=1  "
			));
			return $tcs;
		}
		
	}
	
}