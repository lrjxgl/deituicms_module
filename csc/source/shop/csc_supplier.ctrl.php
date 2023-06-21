<?php
	class csc_supplierControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) AND shopid=".SHOPID;
			$url="/shopadmin.php?m=csc_supplier";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
			}
			$option=array(
				"where"=>$where,
				"order"=>"supid DESC"
			);
			$rscount=true;
			$data=M("mod_csc_supplier")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$data[$k]=$v;
				}
			}  
			$per_page=$start+$limit;
	 		$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goAssign(array(
				"list"=>$data,
				 "per_page"=>$per_page
			));
			$this->smarty->display("csc_supplier/index.html");
		}
		 
		
		public function onAdd(){
			$supid=get_post('supid','i');
			if($supid){
				$data=M("mod_csc_supplier")->selectRow("supid=".$supid);
			}
			 
			$this->smarty->goAssign(array(
				"data"=>$data
				 
			));
			$this->smarty->display("csc_supplier/add.html");
		}
		
		public function onSave(){
			$supid=get_post('supid','i');
			$data=M("mod_csc_supplier")->postData();
			if($supid){
				$row=M("mod_csc_supplier")->selectRow("supid=".$supid);
				if($row['shopid']!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				unset($data['url']);
				M("mod_csc_supplier")->update($data,"supid=".$supid);
			}else{
				$data['shopid']=SHOPID;
				$supid=M("mod_csc_supplier")->insert($data);
			}
			$redata=array(
				"supid"=>$supid
			);
			$this->goAll("保存成功",0,$redata);
		}
		
		public function onStatus(){
			$_GET['ajax']=1;
			$status=get('status','i');
			$supid=get('supid','i');
			$row=M("mod_csc_supplier")->selectRow("supid=".$supid);
			if($row['shopid']!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if($row['status']==2){
				$status=0;
			}else{
				$status=2;
			}
			M("mod_csc_supplier")->update(array(
				"status"=>$status
			),"supid=".$supid);
			$rdata=array(
				"status"=>$status
			);
			$this->goAll("success",0,$rdata);
		}
		
		public function onDelete(){
			$_GET['ajax']=1;
			$supid=get_post('supid','i');
			$status=11;
			$row=M("mod_csc_supplier")->selectRow("supid=".$supid);
			 
			if($row['shopid']!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_csc_supplier")->update(array("status"=>$status),"supid=".$supid);
			$this->goAll("删除成功");
		}
	}
?>