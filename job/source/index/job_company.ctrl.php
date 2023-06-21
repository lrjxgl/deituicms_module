<?php 
class job_companyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$company=M("mod_job_company")->selectRow("userid=".$userid);
		if($company){
			$company["trueimgurl"]=images_site($company["imgurl"]);
		}
		 
		$this->smarty->goAssign(array(
			"data"=>$company,
			 
		));
		$this->smarty->display("job_company/my.html");
	}
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$company=M("mod_job_company")->selectRow("userid=".$userid);
		$data=M("mod_job_company")->postData();
		 
		if(empty($data["title"])){
			$this->goAll("请填写公司名称",1);
		}
		 
		if($company){
			M("mod_job_company")->update($data,"comid=".$company["comid"]);
		}else{
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			M("mod_job_company")->insert($data);
		}
		$this->goAll("保存成功");
	}
	
}