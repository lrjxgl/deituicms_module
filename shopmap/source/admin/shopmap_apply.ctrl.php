<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class shopmap_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();		 
		}
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$where=" status=0 ";
			$url="/moduleadmin.php?m=shopmap_apply&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id ASC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shopmap_apply")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("shopmap_apply/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_shopmap_apply")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("shopmap_apply/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_shopmap_apply")->postData();
			$data['status']=2;
			if($id){
				M("mod_shopmap_apply")->update($data,"id='$id'");
			}else{
				$data['createtime']=date("Y-m-d H:i:s");
				M("mod_shopmap_apply")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_shopmap_apply")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_shopmap_apply")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		public function onPass(){
			$id=get_post('id',"i");
			
			
			$row=M("mod_shopmap_apply")->selectRow("id=".$id);
			if($row["status"]!=0){
				$this->goAll("当前申请已经处理过了",1);
			}
			
			//同步
			$_POST=$row;
			unset($_POST["id"]);
			$shopmap=M("mod_shopmap")->postData();
			$shopmap["createtime"]=date("Y-m-d H:i:s");
			$shopmap["status"]=1;
			M("mod_shopmap")->insert($shopmap);
			//更新用户
			$money=1+rand(1,100)*0.1;
			
			if($money>2){
				$a=rand(1,1000);
				if($a>10){
					$money=1;
				}
			}
			
			M("mod_shopmap_apply")->update(array(
				"status"=>1,
				"hbmoney"=>$money
			),"id=$id");
			$spUser=M("mod_shopmap_user")->selectRow("userid=".$row["userid"]);
			M("mod_shopmap_user")->update(array(
				"pass_num"=>$spUser["pass_num"]+1,
				"money"=>$spUser["money"]+$money,
			),"id=".$spUser["id"]);
			//
			$content="您添加的商家《".$row["title"]."》已经审核通过了";
			M("notice")->add(array(
				"content"=>$content,
				"template_id"=>1,
				"userid"=>$row["userid"],
				
			));
			//发送红包
			$wx=M("weixin")->selectRow("status=1");
			include "api/wxpay/lib/WxAppPay.Config.php"; 
			WxPayConfig::init($wx);
			require ROOT_PATH."api/wxpay/lib/WxPayHongbao.php";
			$hb=new WxPayHongbao();
			
			$openlogin=M("openlogin")->selectRow("xfrom='weixin' AND userid=".$row["userid"]);
			$res=$hb->send(array(
				"re_openid"=>$openlogin['openid'],
				"total_amount"=>$money*100,
				"total_num"=>1,
				"send_name"=>"福鼎互联网+活动",
				"wishing"=>"感谢您参加福鼎“互联网+”商家上网活动，祝您生活愉快！"
			));
			M("mod_shopmap_hongbao_sendlog")->insert(array(
				"userid"=>$row["userid"],
				"money"=>$money,
				"dateline"=>time(),
				"status"=>$res['result_code'],
				"msg"=>$res['err_code_des'],
				"content"=>base64_encode(json_encode($res))
			));
			//end红包
			$this->goAll("审核成功");
		}
		
		public function onForbid(){
			$id=get_post('id',"i");
			$row=M("mod_shopmap_apply")->selectRow("id=".$id);
			$content="您添加的商家《".$row["title"]."》信息不完整，审核未通过";
			M("notice")->add(array(
				"content"=>$content,
				"template_id"=>1,
				"userid"=>$row["userid"],
				
			));
			M("mod_shopmap_apply")->update(array("status"=>2),"id=$id");
			$this->goAll("禁止成功");
		}
		
		
	}

?>