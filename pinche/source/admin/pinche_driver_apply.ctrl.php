<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pinche_driver_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=pinche_driver_apply&a=default";
			$truename=get("truename","h");
			$telephone=get("telephone","h");
			if($truename){
				$where.=" AND truename='".$truename."' ";
			}
			if($telephone){
				$where.=" AND telephone='".$telephone."' ";
			}
			$carno=get("carno","h");
			if($carno){
				$where.=" AND carno='".$carno."' ";
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
			$data=M("mod_pinche_driver_apply")->select($option,$rscount);
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
					"truename"=>$truename,
					"telephone"=>$telephone,
					"carno"=>$carno
				)
			);
			$this->smarty->display("pinche_driver_apply/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_pinche_driver_apply")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("pinche_driver_apply/show.html");
		}
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_pinche_driver_apply")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_pinche_driver_apply")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onPass(){
			$id=get("id","i");
			$row=M("mod_pinche_driver_apply")->selectRow("id=".$id);
			if($row["status"]!=0){
				$this->goAll("已经审核过了",1);
			}
			
			$_POST=$row;
			$rs=M("mod_pinche_driver")->selectRow("telephone='".$row["telephone"]."' or userno='".$row["userno"]."' ");
			if($rs){
				M("mod_pinche_driver_apply")->update(array(
					"status"=>2
				),"id=".$id);
				 
				$this->goAll("你已经加入了",1);
			}
			M("mod_pinche_driver_apply")->begin();
			$data=M("mod_pinche_driver")->postData();
			$data["createtime"]=date("Y-m-d H:i:s");
			M("mod_pinche_driver")->insert($data);
			M("mod_pinche_driver_apply")->update(array(
				"status"=>1
			),"id=".$id);
			M("mod_pinche_driver_apply")->commit();
			$this->goAll("审核成功");
		}
		
		public function onForbid(){
			$id=get("id","i");
			$row=M("mod_pinche_driver_apply")->selectRow("id=".$id);
			if($row["status"]!=0){
				$this->goAll("已经审核过了",1);
			}
			M("mod_pinche_driver_apply")->update(array(
				"status"=>2
			),"id=".$id);
			$this->goAll("审核成功"); 
		}
		
	}

?>