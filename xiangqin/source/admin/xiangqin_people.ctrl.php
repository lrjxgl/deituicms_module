<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class xiangqin_peopleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) ";
			$type=get("type","h");
			$url="moduleadmin.php?m=xiangqin_people&type=".$type;
			
			switch($type){
				case "new":
					$where=" status=0 ";
					break;
				case "pass":
					$where=" status=1 ";
					break;
				case "forbid":
					$where=" status=2 ";
					break;
			}
			$truename=get("truename","h");
			if(!empty($truename)){
				$where.=" AND truename='".$truename."' ";
				$url.="&truename=".urlencode($truename);
			}
			$gender=get("gender","i");
			if($gender){
				$where.=" AND gender=".$gender;
				$url.="&gender=".$gender;
			}
			$income=get("income","i");
			if($income>0){
				$where.=" AND income>=".$income;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			if($type=="pass"){
				$mm=MM("xiangqin","xiangqin_people");
			}else{
				$mm=MM("xiangqin","xiangqin_people_new");
			}
			$data=$mm->Dselect($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"type"=>$type,
					"truename"=>$truename,
					"gender"=>$gender,
					"income"=>$income
				)
			);
			$this->smarty->display("xiangqin_people/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$type=get("type","h");
			if($type=="pass"){
				$mm=MM("xiangqin","xiangqin_people");
			}else{
				$mm=MM("xiangqin","xiangqin_people_new");
			}
			$data=$mm->selectRow(array("where"=>"id=".$id));
			 
			$data["age"]=date("Y")-substr($data["birthday"],0,4)+1;
			$data["gender_title"]=$data["gender"]==1?'男':'女';
			$data["imgurl"]=images_site($data["imgurl"]);
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("xiangqin_people/show.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_xiangqin_people_new")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("xiangqin_people/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_xiangqin_people_new")->postData();
			if($id){
				M("mod_xiangqin_people_new")->update($data,"id='$id'");
			}else{
				M("mod_xiangqin_people_new")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_xiangqin_people_new")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_xiangqin_people_new")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onPass(){
			$id=get_post('id',"i");
			M("mod_xiangqin_people_new")->update(array("status"=>1),"id=".$id);
			$row=M("mod_xiangqin_people_new")->selectRow("id=".$id);
			//判断是否加入平台
			$u=M("mod_xiangqin_join")->selectRow("userid=".$row["userid"]);
			if(empty($u)){
				M("mod_xiangqin_join")->insert(array(
					"userid"=>$row["userid"]
				));
			}
			//更新线上
			$p=M("mod_xiangqin_people")->selectRow("userid=".$row["userid"]);
			unset($row["id"]);
			unset($row["grade"]);
			$row["status"]=1;
			if(!empty($p)){
				unset($row["createtime"]);
				M("mod_xiangqin_people")->update($row,"id=".$p["id"]);
			}else{
				M("mod_xiangqin_people")->insert($row);
			}
			
			$this->goall("审核通过");
		}
		
		public function onForbid(){
			$id=get_post('id',"i");
			M("mod_xiangqin_people_new")->update(array("status"=>2),"id=".$id);
			$this->goall("禁止成功");
		}
		
		
	}

?>