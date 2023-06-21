<?php
class fsbuy_ksControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$fsid=get("fsid","i");
		$fsbuy=M("mod_fsbuy")->selectRow(array(
			"where"=>" fsid=".$fsid,
			"fields"=>"fsid,title"
		));
		$this->smarty->goAssign(array(
			"fsbuy"=>$fsbuy
		));
		$this->smarty->display("fsbuy_ks/index.html");
	}
	public function onJlist(){
		$fsid=get_post('fsid','i');
		$data=M('mod_fsbuy_ks')->select(array("where"=>" fsid=".$fsid));
		exit(json_encode($data));
	}
	public function onSave(){
		$ksid=get_post("ksid","i");
		$data=M('mod_fsbuy_ks')->postData();
		
		if($ksid){
			 
			$row=M('mod_fsbuy_ks')->selectRow("ksid=".$ksid);
			M('mod_fsbuy_ks')->update($data,"ksid=".$ksid);
		}else{
			$data['createtime']=date("Y-m-d H:i:s");
			$ksid=M('mod_fsbuy_ks')->insert($data);
		}
		$rdata=array(
			"ksid"=>$ksid
		);
		//
		M("mod_fsbuy")->update(array(
			"isksid"=>1
		),"fsid=".$data["fsid"]);
		$this->goAll('保存成功',0,$rdata);
	}
	
	public function ondelete(){
		$id=get_post('ksid','i');
		$row=M("mod_fsbuy_ks")->selectRow("ksid=".$id);
		if(empty($row)){
			$this->goAll("参数出错",1);
		}
		M('mod_fsbuy_ks')->delete("ksid=".$id);
		//
		$kslist=M("mod_fsbuy_ks")->selectRow(array(
			"where"=>" fsid=".$row["fsid"]
		));
		if(empty($kslist)){
			M("mod_fsbuy")->update(array(
				"isksid"=>0
			),"fsid=".$row["fsid"]);
		}
		
		$this->goAll('删除成功');
	}
}