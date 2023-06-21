<?php 
class job_jianliControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$jianli=M("mod_job_jianli")->selectRow("userid=".$userid);
		if($jianli){
			$jianli["trueimgurl"]=images_site($jianli["imgurl"]);
		}
		$xueliList=MM("job","job_jianli")->xueliList;
		$this->smarty->goAssign(array(
			"data"=>$jianli,
			"xueliList"=>$xueliList
		));
		$this->smarty->display("job_jianli/my.html");
	}
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$jianli=M("mod_job_jianli")->selectRow("userid=".$userid);
		$data=M("mod_job_jianli")->postData();
		$data["gender"]=max(1,$data["gender"]);
		if(empty($data["nickname"])){
			$this->goAll("请填写名字",1);
		}
		if(!$data["age"]){
			$this->goAll("请选择年龄",1);
		}
		if($jianli){
			M("mod_job_jianli")->update($data,"id=".$jianli["id"]);
		}else{
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			M("mod_job_jianli")->insert($data);
		}
		$this->goAll("保存成功");
	}
	
	public function onGet(){
		$userid=get("userid","i");
		$data=M("mod_job_jianli")->selectRow("userid=".$userid);
		$data["gender_title"]=$data["gender"]==1?'男':'女';
		$data["imgurl"]=images_site($data["imgurl"]);
		$data["age"]=date("Y")-$data["age"]+1;
		$xueliList=MM("job","job_jianli")->xueliList;
		$data["xueli"]=$xueliList[$data["xueli"]];
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"data"=>$data
			)
		));
	}
	
}