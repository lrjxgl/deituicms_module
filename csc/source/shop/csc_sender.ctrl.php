<?php
	class csc_senderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) AND shopid=".SHOPID;
			$url="/shopadmin.php?m=csc_sender";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
			}
			$option=array(
				"where"=>$where,
				"order"=>"senderid DESC"
			);
			$rscount=true;
			$data=M("mod_csc_sender")->select($option,$rscount);
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
			$this->smarty->display("csc_sender/index.html");
		}
		 
		/*
		public function onAdd(){
			$senderid=get_post('senderid','i');
			if($senderid){
				$data=M("mod_csc_sender")->selectRow("senderid=".$senderid);
			}
			 
			$this->smarty->goAssign(array(
				"data"=>$data
				 
			));
			$this->smarty->display("csc_sender/add.html");
		}
		
		public function onSave(){
			$senderid=get_post('senderid','i');
			$data=M("mod_csc_sender")->postData();
			if($senderid){
				$row=M("mod_csc_sender")->selectRow("senderid=".$senderid);
				if($row['shopid']!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				unset($data['url']);
				M("mod_csc_sender")->update($data,"senderid=".$senderid);
			}else{
				$data['shopid']=SHOPID;
				$senderid=M("mod_csc_sender")->insert($data);
			}
			$redata=array(
				"senderid"=>$senderid
			);
			$this->goAll("保存成功",0,$redata);
		}
		*/
		public function onAddrSave(){
			$send_addr=post("send_addr");
			$senderid=post('senderid','i');
			$row=M("mod_csc_sender")->selectRow("senderid=".$senderid);
			if($row['shopid']!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_csc_sender")->update(array(
				"send_addr"=>$send_addr
			),"senderid=".$senderid);
			 
			$this->goAll("success");
		}
	   
		public function onStatus(){
			$_GET['ajax']=1;
			$status=get('status','i');
			$senderid=get('senderid','i');
			$row=M("mod_csc_sender")->selectRow("senderid=".$senderid);
			if($row['shopid']!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if($row['status']==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_csc_sender")->update(array(
				"status"=>$status
			),"senderid=".$senderid);
			 
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$_GET['ajax']=1;
			$senderid=get_post('senderid','i');
			$status=11;
			$row=M("mod_csc_sender")->selectRow("senderid=".$senderid);
			 
			if($row['shopid']!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_csc_sender")->update(array("shopid"=>0),"senderid=".$senderid);
			$this->goAll("解绑成功");
		}
	}
?>