<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class wmo2o_shop_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h"); 
			$url="/moduleadmin.php?m=wmo2o_shop_apply&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			switch($type){
				case "pass":
					$where=" status=1 ";
					break;
				case "forbid":
					$where=" status=2 ";
					break;
				default:
						$where=" status=0 ";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_wmo2o_shop_apply")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					 
					$v["yyzz"]=images_site($v["yyzz"]);
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
			$this->smarty->display("wmo2o_shop_apply/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_wmo2o_shop_apply")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_wmo2o_shop_apply")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onPass(){
			$id=get("id","i");
			$row=M("mod_wmo2o_shop_apply")->selectRow("id=".$id);
			if($row["status"]!=0){
				$this->goAl("已经处理过了",1);
			}
			$_POST=$row;
			$indata=M("mod_wmo2o_shop")->postData();
			$indata["shopname"]=$row["title"];
			$indata["status"]=0;
			unset($indata["id"]);
			 
			$shopid=M("mod_wmo2o_shop")->insert($indata);
			$adminname=M("maxid")->get();
			$salt=rand(1000,9000);
			M("mod_wmo2o_admin")->insert(array(
				"shopid"=>$shopid,
				"adminname"=>$adminname,
				"salt"=>$salt,
				"password"=>umd5($salt.$salt),				
			));
			M("mod_wmo2o_shop_safephone")->insert(array(
				"shopid"=>$shopid,
				"telephone"=>$row["telephone"],
			));
			$content="【".SMS_QIANMING."】商家申请审核通过，账号：{$adminname},密码：{$salt}";
			$content=array(
				"content"=>$content,
				"tpl"=>"shop_apply_pass"
				
			);
			$res=M("sms")->sendSms($row["telephone"],$content);
			
			M("mod_wmo2o_shop_apply")->update(array(
				"status"=>1
			),"id=".$id);
			//插入团
			$this->goAll("审核成功");
		}
		
		public function onForbid(){
			$id=get("id","i");
			$row=M("mod_wmo2o_shop_apply")->selectRow("id=".$id);
				if($row["status"]!=0){
				$this->goAl("已经处理过了",1);
			}
			M("mod_wmo2o_shop_apply")->update(array(
				"status"=>2
			),"id=".$id);
			$this->goAll("禁止成功");
		}
	}

?>