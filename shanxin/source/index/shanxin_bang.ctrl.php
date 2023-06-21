<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class shanxin_bangControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$sid=get_post("sid","i");
			$shanxin=M("mod_shanxin")->selectRow(array("where"=>"sid=".$sid));
			$where=" sid=".$sid." AND ispay=1 AND status in(0,1,2)";
			$url="module.php?m=shanxin_bang&sid=".$sid;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shanxin_bang")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["user_head"]=images_site($us[$v["userid"]]["user_head"]);
					$v["nickname"]=$us[$v["userid"]]["nickname"];
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
					"url"=>$url,
					"shanxin"=>$shanxin
				)
			);
			$this->smarty->display("shanxin_bang/index.html");
		}
		
		public function onAdd(){
			$sid=get_post("sid","i");
			$shanxin=M("mod_shanxin")->selectRow(array("where"=>"sid=".$sid));
			 
			$this->smarty->goassign(array(
				 
				"shanxin"=>$shanxin
			));
			$this->smarty->display("shanxin_bang/add.html");
		}
		
		public function onSave(){
			$sid=get_post("sid","i");
			$shanxin=M("mod_shanxin")->selectRow(array("where"=>"sid=".$sid));
			$userid=M("login")->userid;
			$data=M("mod_shanxin_bang")->postData();
			/*
			$data["createtime"]=time();
			$data["userid"]=$userid;
			M("mod_shanxin_bang")->insert($data);
			*/
			$orderno="Re".M("maxid")->get();
			$backurl=get_post("backurl","x");
			if($backurl==""){
				$backurl="/module.php?m=shanxin_bang&a=success&sid=".$data["sid"]; 
			}
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$money= $shanxin['money']*$data["num"];
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					MM("shanxin","shanxin_bang")->add(array(
						"ispay"=>1,
						"recharge_id"=>"$recharge_id",
						"paytype"=>"'.$paytype.'",
						"userid"=>"'.$userid.'",
						"sid"=>"'.$sid.'",
						"num"=>"'.$data["num"].'",
						"money"=>"'.$money.'",
						"description"=>"'.$data["description"].'"
					));
					M("mod_shanxin")->changenum("juan_num",1,"sid='.$sid.'");
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$orderinfo=date("Y-m-d H:i:s").$shanxin["product"];
			$order_product=date("Y-m-d H:i:s").$shanxin["product"];
			$fromapp=get("fromapp");
			
			$openid=get('openid','h');
			//固定支付
			$rechargeid=M("recharge")->insert(array(
				"orderno"=>$orderno,
				"userid"=>$userid,
				"money"=>$money,
				"pay_type"=>$pay_type,
				"dateline"=>time(),
				"openid"=>$openid,
				"orderinfo"=>$orderinfo, 
				"orderdata"=>$orderdata,
				"status"=>2,
			));
			$bank_type="";
			
			$url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
			$url.="&orderno=$orderno";
			$url.="&bank_type=".$bank_type;
			$url.="&order_product=".urlencode($order_product);
			$url.="&order_price=".$money;
			$url.="&order_info=".urlencode($order_info);
			$url.="&backurl=".urlencode($backurl);
			$redata=array(
				"payurl"=>$url,
				"action"=>"pay",
				"orderno"=>$orderno
			);
			if($return){
				return $redata;
			}
			//end 固定支付
			$this->goALl("正在前往支付",0,$redata,$url);
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_shanxin_bang")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_shanxin_bang")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>