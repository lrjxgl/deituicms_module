<?php
class csc_paotuiControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$addrList=M("user_address")->select(array(
			"where"=>" userid=".$userid." AND status=2 "
		));
		if(get("user_address_id")){
			$user_address_id=get("user_address_id","i");
		}elseif($addrList){
			$user_address_id=$addrList[0]["id"];
		}else{
			$user_address_id=0;
		}
		$shop=MM("csc","csc_shop")->get(SHOPID,"shopid,paotui_money,shopname");
		$this->smarty->assign(array(
			"seo"=>array(
				"title"=>"帮买菜--想煮什么帮您送到家",
				"description"=>"帮买菜--想煮什么帮您送到家"
			)
		));
		
		$this->smarty->goAssign(array(
			"addrList"=>$addrList,
			"user_address_id"=>$user_address_id,
			"shop"=>$shop
		));
		$this->smarty->display("csc_paotui/index.html");
	}
	
	public function onMy(){
		$status_list=MM("csc","csc_paotui")->status_list();
		 
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$type=get("type","h");
		switch($type){
			case "finish":
				$where.=" AND status=3 ";
				break;
			case "unorder":
				$where.=" AND  status=0 AND ispay=1 ";
				break;
			case "unsend":
				$where.=" AND  status=1 ";
				break;	
			case "unreceive":
				$where.=" AND  status=2 ";
				break;
			case "unpay":
				$where.=" AND  status=0 AND ispay=0 ";
				break;	
			default:
				$where.=" AND  status <7 ";
		}
		$list=M("mod_csc_paotui")->select(array(
			"where"=>$where,
			"order"=>"createtime DESC"
		));
		if($list){
			foreach($list as $k=>$v){
				$v["createtime"]=date("m-d H:i",$v["createtime"]);
				if($v["status"]==0 && $v["ispay"]==0){
					$v["status_name"]="待支付";
				}else{
					$v['status_name']=$status_list[$v['status']];
				}
				
				$v['ispay_name']=$v['ispay']==2?"已支付":"未支付";
				 
				 
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		 
		$this->smarty->display("csc_paotui/my.html");
	} 
	 
	public function onSuccess(){
		
		$this->smarty->display("csc_paotui/success.html");
	}
	
	public function onSave(){
		$userid=M("login")->userid;
		$data=M("mod_csc_paotui")->postData();
		$carts=M("mod_csc_paotui_lmshop_cart")->select(array(
			"where"=>" userid=".$userid." AND shopid=".SHOPID,
			"fields"=>"*"
		));
		if(empty($data["content"]) && empty($carts)){
			$this->goAll("请输入想买的菜",1);
		}
		$data["createtime"]=time();
		$data["userid"]=$userid;
		$data["shopid"]=SHOPID;
		
		$shop=MM("csc","csc_shop")->get(SHOPID,"shopid,paotui_money");
		$data["paotui_money"]=$shop["paotui_money"];
		$ispay=0;
		$user=M("user")->selectRow("userid=".$userid);
		if($user['money']>=$data['paotui_money']){
			$ispay=1;
			M("user")->addMoney(array(
				"userid"=>$userid,
				"content"=>"发布买菜任务，赏金[money]元,",
				"money"=>-$data['paotui_money']
			));
		}
		$data["ispay"]=$ispay;
		 
		$addr=M("user_address")->selectRow("id=".$data["user_address_id"]);
		$data["telephone"]=$addr["telephone"];
		$data["truename"]=$addr["truename"];
		$data["address"]=$addr["pct_address"];
		
		//插入代买商品
		
		if($carts){
			$lmids=array();
			foreach($carts as $rs){
				$lmids[]=$rs["lmid"];
				$proids[]=$rs["productid"];
			}	
			$lms=MM("csc","csc_paotui_lmshop")->getListByIds($lmids,"lmid,shopname");
			$pros=MM("csc","csc_paotui_lmshop_product")->getListByIds($proids,"productid,title");
			$sps=array();
			foreach($carts as $rs){
				$rs["title"]=$pros[$rs["productid"]]["title"];
				$rs["shopname"]=$lms[$rs["lmid"]]["shopname"];
				if(!isset($sps[$rs["lmid"]])){
					$sps[$rs["lmid"]]["shopname"]=$rs["shopname"];
				}
				$sps[$rs["lmid"]]["list"][]=$rs;
				
				
			}
			foreach($sps as $k=>$sp){
				$data["content"].="---".$sp["shopname"]."---\r\n";
				foreach($sp["list"] as $v){
					$data["content"].=$v["title"]." x ".$v["num"]."\r\n";
				}
			}
			
		}
		$data["content"]=nl2br($data["content"]);
		$ptid=M("mod_csc_paotui")->insert($data);
		if($carts){
			foreach($carts as $rs){
				M("mod_csc_paotui_goods")->insert(array(
					"productid"=>$rs["productid"],
					"num"=>$rs["num"],
					"userid"=>$rs["userid"],
					"dateline"=>time(),
					"shopid"=>$rs["shopid"],
					"lmid"=>$rs["lmid"],
					"ptid"=>$ptid
				));
			}
			M("mod_csc_paotui_lmshop_cart")->delete(" userid=".$userid." AND shopid=".SHOPID);
		}
		$action="pay";
		$rdata=array(
			"action"=>$action,
			"id"=>$id
		);
		if(!$ispay){
			$_GET["id"]=$id;
			$res=$this->onPay(1);
			$rdata['payurl']=$res['payurl'];
			$rdata['orderno']=$res['orderno'];
		}else{
			$rdata=array(
				"action"=>"success",
				"orderid"=>$orderid
			);
		}
		$this->goAll("发布成功，请等待",0,$rdata);
	}
	
	public function onPay($return=0){
		$id=get('id','i');
		$userid=M("login")->userid;
		$orderno="Re".M("maxid")->get();
		//生成支付
		$paotui=M("mod_csc_paotui")->selectRow("id=".$id);
		if($paotui["status"]!=0 || $paotui["ispay"]==1){
			$this->goAll("当前订单无法支付",1);
		}
		$backurl=get_post("backurl","x");
		if($backurl==""){
			$backurl="/module.php?m=csc_paotui&a=success&id=".$id; 
		}
		$pay_type=INWEIXIN?"wxpay":"alipay";
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("csc","csc_paotui")->update(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$paytype.'",
				),"id='.$id.'");
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata)); 
		$orderinfo=date("Y-m-d H:i:s")."发布跑腿帮买菜任务";
		$order_product=date("Y-m-d H:i:s")."发布跑腿帮买菜任务";
		$fromapp=get("fromapp");
		$money= $paotui['paotui_money'];
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
	}
	public function onCancel(){
		$id=get('id','i');
		 
		$row=M("mod_csc_paotui")->selectRow("id=".$id);
		if($row['status']!=0){
			$this->goAll("任务已处理，无法取消，请联系客服",1);
		}
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		} 
		M("mod_csc_paotui")->update(array(
			"status"=>8
		),"id=".$id);
		M("user")->addMoney(array(
			"userid"=>$row["userid"],
			"content"=>"跑腿任务取消，回收赏金加[money]元,",
			"money"=>$row['paotui_money']
		));
					
		$this->goAll("取消成功");
	}
	 
}
?>