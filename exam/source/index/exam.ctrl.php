<?php
class examControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		
	}

	public function onDefault(){
		$where=" status=1 AND isonline=1 ";
		$url="/module.php?m=exam&a=default";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" exid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_exam")->select($option,$rscount);
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
		$this->smarty->display("index.html");
	}
	public function onTest(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$exid=get("exid","i");
		$exam=M("mod_exam")->selectRow("exid=".$exid);
		if(empty($exam) || !$exam["isonline"] || $exam["status"]!=1){
			$this->goAll("数据出错",1);
		}
		$askList=M("mod_exam_ask")->select(array(
			"where"=>" exid=".$exid,
			"order"=>"orderindex ASC",
			
		));
		if(empty($askList)){
			$this->goAll("考题为空，数据出错",1);
		}
		$answer=M("mod_exam_answer")->selectRow("userid=".$userid." AND exid=".$exid);
		
		foreach($askList as $v){
			$topicids[]=$v["topicid"];
		}
		$data=MM("exam","exam_topic")->getListByIds($topicids,"*");
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
		$isanswer=0;
		if($exam["isone"]==1 && $answer){
			$isanswer=1;
		}
		$this->smarty->goAssign(array(
				"list"=>$data,
				"exam"=>$exam,
				"isanswer"=>$isanswer
		));
		if($exam["tpl"]=='fullpage'){
			$this->smarty->display("exam/test_fullpage.html");
		}else{
			$this->smarty->display("exam/test.html");
		}
		
	}
	
	public function onTestSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$exid=get_post("exid","i");
		$exam=M("mod_exam")->selectRow("exid=".$exid);
		if(empty($exam)){
			$this->goAll("数据出错",1);
		}
		$answer=M("mod_exam_answer")->selectRow("userid=".$userid." AND exid=".$exid);
		if($answer && $exam["isone"]==1){
			$this->goAll("你已经答题过了",1);
		}
		$tp=post("tp","h");
		$answerid=M("mod_exam_answer")->insert(array(
			"userid"=>$userid,
			"exuserid"=>$exam["userid"],
			"exid"=>$exid,
			"content"=>arr2str($tp),
			"createtime"=>date("Y-m-d H:i:s")
		));
		$this->goAll("测试提交成功",0,$answerid);
		
	}
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$where=" userid=".$userid." AND status in(0,1,2) ";
		$url="/module.php?m=exam&a=my";
		$limit=24;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" exid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_exam")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v["ewm"]=HTTP_HOST."/index.php?m=qrcode&title=".$v["title"]."||扫码考试&content=".urlencode(HTTP_HOST."/module.php?m=exam&a=test&exid=".$v["exid"]);
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
		$this->smarty->display("exam/my.html");
	}
	public function onAdd(){
		M("login")->checkLogin();
		$exid=get_post("exid","i");
		if($exid){
			$data=M("mod_exam")->selectRow(array("where"=>"exid=".$exid));
			$userid=M("login")->userid; 
			if($data["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
		}
		$this->smarty->goassign(array(
			"data"=>$data
		));
		$this->smarty->display("exam/add.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$exid=get_post("exid","i");
		$data=M("mod_exam")->postData();
		$userid=M("login")->userid;
		$data["userid"]=$userid;
		$data["isone"]=post("isone","i");
		if($exid){
			$row=M("mod_exam")->selectRow("userid=".$userid);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_exam")->update($data,"exid='$exid'");
		}else{
			M("mod_exam")->insert($data);
		}
		$this->goall("保存成功");
	}
	
	public function onDelete(){
		M("login")->checkLogin();
		$exid=get_post('exid',"i");
		$userid=M("login")->userid;
		$row=M("mod_exam")->selectRow("userid=".$userid);
		if($row["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		M("mod_exam")->update(array("status"=>11),"exid=$exid");
		$this->goAll("删除成功");
		 
	}
	
	public function onOnline(){
		M("login")->checkLogin();
		$exid=get_post('exid',"i");
		$userid=M("login")->userid;
		$row=M("mod_exam")->selectRow("userid=".$userid);
		if($row["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		M("mod_exam")->update(array("isonline"=>1,"status"=>1),"exid=$exid");
		$this->goAll("发布成功");
	}
}

?>