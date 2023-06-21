<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class jieti_askControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=2 ";
			$url="/module.php?m=jieti_ask&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" askid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("jieti","jieti_ask")->Dselect($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("jieti_ask/index.html");
		}
		
		public function onList(){
			$where=" status=2 ";
			$url="/module.php?m=jieti_ask&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" askid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("jieti","jieti_ask")->Dselect($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("jieti_ask/index.html");
		}
		
		public function onShow(){
			$askid=get_post("askid","i");
			$data=M("mod_jieti_ask")->selectRow(array("where"=>"askid={$askid}"));
			$data['imgurl']=images_site($data['imgurl']);
			$author=M("user")->getUser($data['userid']);
			if($data['answerid']){
				$data['isanswer_name']="已解决";
				$answer=M("mod_jieti_answer")->selectRow("answerid=".$data['answerid']);
				$answer['imgurl']=images_site($answer['imgurl']);
			}else{
				$data['isanswer_name']="未解决";
			}
			 $data['timeago']=timeago(strtotime($data['createtime']));
			$this->smarty->goassign(array(
				"ask"=>$data,
				"answer"=>$answer,
				"author"=>$author
			));
			$this->smarty->display("jieti_ask/show.html");
		}
		public function onAdd(){
			$catList=MM("jieti","jieti_category")->children();
			$typeList=MM("jieti","jieti_ask")->typeList();
			$this->smarty->goAssign(array(
				"catList"=>$catList,
				"typeList"=>$typeList
			));
			$this->smarty->display("jieti_ask/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			 
			$data=M("mod_jieti_ask")->postData();
			$userid=M("login")->userid;
			$data['createtime']=date("Y-m-d H:i:s");
			$data['userid']=$userid;
			$typeList=MM("jieti","jieti_ask")->typeList();
			$money=$typeList[$data['typeid']]['money'];
			$user=M("user")->selectRow(array(
				"fields"=>"money",
				"where"=>" userid=".$userid
			));
			$data['money']=$money;
			$action="pay";
			if($money<$user['money'] && 1==2){
				$data['ispay']=1;
				$action="finish";
				M("user")->addMoney(array(
					"userid"=>$userid,
					"money"=>-$money,
					"content"=>"你发起了一个问题，花了￥{$money}元 "
				));
			}
			$askid=M("mod_jieti_ask")->insert($data);
			$rdata=array(
				"action"=>$action,
				"askid"=>$askid
			);
			if(!$data['ispay']){
				$_GET['askid']=$askid;
				$res=$this->onPay(1);
				$rdata['payurl']=$res['url'];
				$rdata['orderno']=$res['orderno'];
			}
			
			
			$this->goall("保存成功",0,$rdata);
		}
		
		public function onPay($return=0){
			$orderno="Re".M("maxid")->get();
			$askid=get("askid",'i');
			$ask=M("mod_jieti_ask")->selectRow("askid=".$askid);
			$backurl="/module.php?m=jieti_ask&a=show&askid=".$askid;
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					M("mod_jieti_ask")->update(array(
					"ispay"=>1,
					),"askid='.$askid.'");
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$orderinfo="问题解答";
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$fromapp=get("fromapp");
			$money= $ask['money'];
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
			$order_product="教程购买";
			$url=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/index.php?m=recharge_{$pay_type}&a=go";
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
			$this->goALl("正在前往支付",0,$redata);
		}
		 
		public function onMy(){
			$userid=M("login")->userid;
			$where=" status in(0,1,2) AND userid=".$userid;
			$url="/module.php?m=jieti_ask&a=my";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" askid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("jieti","jieti_ask")->Dselect($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"askList"=>$data,
					"adImg"=>images_site("/static/images/mjietilogo.png"),
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("jieti_ask/my.html");
		}
		
	}

?>