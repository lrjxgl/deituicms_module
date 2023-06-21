<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class xiangqin_biaobaiControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		
		public function onDefault(){
			
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$type=get("type","h");
			if($type=="receive"){
				$where=" touserid=".$userid;
			}else{
				$where=" userid=".$userid;
			}
			 
			$url="module.php?m=xiangqin_biaobai&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=M("mod_xiangqin_biaobai")->select($option,$rscount);
			if(!empty($list)){
				foreach($list as $v){
					$uids[]=$v["userid"];
				}
				$pps=MM("xiangqin","xiangqin_people")->getListByUids($uids);
				$statusList=array(
					0=>"未阅读",
					1=>"已接受",
					2=>"已拒绝"
				);
				foreach($list as $k=>$v){
					$v["people"]=$pps[$v["userid"]];
					$v["status_name"]=$statusList[$v["status"]];
					$list[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("xiangqin_biaobai/my.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_xiangqin_biaobai")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("xiangqin_biaobai/add.html");
		}
		
		public function onSave(){
	 
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$touserid=post("touserid","i");
			$time=date("Y-m-d H:i:s",time()-3600*24);
			$row=M("mod_xiangqin_biaobai")->selectRow("userid=".$userid." AND touserid=".$touserid." ");
			if(!empty($row)){
				$this->goAll("你已经打过招呼了",1);
			}
			$data=M("mod_xiangqin_biaobai")->postData();
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			M("mod_xiangqin_biaobai")->insert($data);
			$this->goall("表白发送成功");
		}
		
		public function onPass(){
			$id=get_post('id',"i");
			$userid=M("login")->userid;
			$row=M("mod_xiangqin_biaobai")->selectRow("id=".$id);
			if($row["touserid"]!=$userid){
				$this->goAll("无权处理",1);
			}
			M("mod_xiangqin_friend")->begin();
			
			M("mod_xiangqin_biaobai")->update(array(
				"status"=>1,			 
			),"id=".$id);
			M("xiangqin_friend")->commit();
			$this->goall("处理成功");
		}
		
		public function onForbid(){
			$id=get_post('id',"i");
			$userid=M("login")->userid;
			$row=M("mod_xiangqin_biaobai")->selectRow("id=".$id);
			if($row["touserid"]!=$userid){
				$this->goAll("无权处理",1);
			}
			M("mod_xiangqin_biaobai")->update(array(
				"status"=>2,
				 
			),"id=".$id);
			$this->goall("处理成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_xiangqin_biaobai")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_xiangqin_biaobai")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>