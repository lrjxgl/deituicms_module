<?php
	class paotuiControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		 
		
		public function onInit(){
			M("login")->checkLogin(true);
			
		}
		public function onDefault(){
		  
			$pconfig=MM("paotui","paotui_config")->get();
			
			$this->smarty->goAssign(array(
				"data"=>1,
				"pconfig"=>$pconfig
			));
			$this->smarty->display("paotui/index.html");
		}
		
		public function onShow(){
			
			$id=get('id','i');
			$data=M("mod_paotui")->selectRow("id=".$id);
			$this->smarty->goAssign(array(
				"data"=>$data
			));
			$this->smarty->display("paotui/show.html");
		}
		public function onAdd(){
			$userid=M("login")->userid;
			$user=M("user")->getUser($userid,"userid,nickname,user_head,telephone");
			$id=get('id','i');
			if($id){
				$data=M("mod_paotui")->selectRow("id=".$id);
			}else{
				$data=array();
			}
			$typelist=MM("paotui","paotui")->typelist();
			$sender_num=M("mod_paotui_sender")->selectOne(array(
				"fields"=>" count(*) as num"
			));
			$catList=MM("paotui","paotui_category")->Dselect(array(
				"where"=>" status=1 ",
				"order"=>"orderindex ASC"
			));
			$pconfig=MM("paotui","paotui_config")->get();
			$pconfig["fee_desc"]=nl2br($pconfig["fee_desc"]);
			$this->smarty->goAssign(array(
				"user"=>$user,
				"data"=>$data,
				"typelist"=>$typelist,
				"typeid"=>$typelist[1]["typeid"],
				"catList"=>$catList,
				"sender_num"=>$sender_num,
				"pconfig"=>$pconfig
			));
			$this->smarty->display("paotui/add.html");
		}
		
		public function onMy(){
			$status_list=MM("paotui","paotui")->status_list();
			$typelist=MM("paotui","paotui")->typelist();
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$type=get("type","h");
			switch($type){
				case "finish":
					$where.=" AND status=3 ";
					break;
				case "unorder":
					$where.=" AND  status=0 ";
					break;
				case "unsend":
					$where.=" AND  status=1 ";
					break;	
				case "unreceive":
					$where.=" AND  status=2 ";
					break;
				default:
					$where.=" AND  status <7 ";
			}
			$list=M("mod_paotui")->select(array(
				"where"=>$where,
				"order"=>"createtime DESC"
			));
			if($list){
				$sdids=[];
				foreach($list as $v){
					$sdids[]=$v["senderid"];
				}
				$sds=MM("paotui","paotui_sender")->getListByIds($sdids);
				foreach($list as $k=>$v){
					if($v["status"]==0 && $v["ispay"]==0){
						$v["status_name"]="待支付";
					}else{
						$v['status_name']=$status_list[$v['status']];
					}
					if(isset($sds[$v["senderid"]])){
						$v["sender"]=$sds[$v["senderid"]];
					}else{
						$v["sender"]=[];
					}
					$v['ispay_name']=$v['ispay']==2?"已支付":"未支付";
					$v["typeid_name"]=$typelist[$v["typeid"]]["title"];
					$v["fromaddr"]=json_decode($v["fromaddr"]);
					$v["toaddr"]=json_decode($v["toaddr"]);
					$list[$k]=$v;
				}
			}
			$this->smarty->goAssign(array(
				"list"=>$list
			));
			$this->smarty->display("paotui/my.html");

		}
		
		public function onSave(){
			$data=M("mod_paotui")->postData();
			//print_r($data);
			unset($data['status']);
			unset($data['ispay']);
			$userid=M("login")->userid;
			$data['userid']=$userid;
			$data['createtime']=date("Y-m-d H:i:s");
			$data['updatetime']=date("Y-m-d H:i:s");
			if(in_array($data["typeid"],array(1,2,4,5))){
				if(!$data["fromaddrid"]){
					$this->goAll("请选择取货地址",1);
				}
				$fr=M("mod_paotui_addr")->selectRow(array(
					"where"=>"addrid=".$data["fromaddrid"],
					"fields"=>"addrid,truename,telephone,address"
				)); 
				$data["fromaddr"]=addslashes(json_encode($fr)); 
			}
			if(in_array($data["typeid"],array(1,2,3))){
				if(!$data["toaddrid"]){
					$this->goAll("请选择收货地址",1);
				}
				$to=M("mod_paotui_addr")->selectRow(array(
					"where"=>"addrid=".$data["toaddrid"],
					"fields"=>"addrid,truename,telephone,address"
				)); 
				$data["toaddr"]=addslashes(json_encode($to)); 
			}
			if($data['money']<1){
				$this->goAll("赏金必须大于1元",1);
			}
			
			
			$user=M("user")->selectRow("userid=".$userid);
			$ispay=0;
			if($user['money']>=$data['money']){
				$ispay=1;
				M("user")->addMoney(array(
					"userid"=>$userid,
					"content"=>"发布跑腿任务，赏金[money]元,",
					"money"=>-$data['money']
				));
			}
			$data["ispay"]=$ispay;	
			$data['status']=0;
			$id=M("mod_paotui")->insert($data);
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
			$paotui=M("mod_paotui")->selectRow("id=".$id);
			if($paotui["status"]!=0 || $paotui["ispay"]==1){
				$this->goAll("当前订单无法支付",1);
			}
			$backurl=get_post("backurl","x");
			if($backurl==""){
				$backurl="/module.php?m=paotui&a=success&id=".$id; 
			}
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					MM("paotui","paotui")->update(array(
						"ispay"=>1,
						"recharge_id"=>"$recharge_id",
						"paytype"=>"'.$paytype.'",
					),"id='.$id.'");
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$orderinfo=date("Y-m-d H:i:s")."发布跑腿任务";
			$order_product=date("Y-m-d H:i:s")."发布跑腿任务";
			$fromapp=get("fromapp");
			$money= $paotui['money'];
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
		
		public function onAddmoney(){
			$id=get('id','i');
			$userid=M("login")->userid;
			$row=M("mod_paotui")->selectRow("id=".$id);
			$money=get("money","i");
			if($money<1){
				$this->goAll("追加赏金必须大于1元",1);
			}
			if($row['status']!=0){
				$this->goAll("任务已处理，无需曾加赏金",1);
			}
			if($row['userid']!=$userid){
				$this->goAll("暂无权限",1);
			}
			$user=M("user")->selectRow("userid=".$userid);
			if($user['money']<$money){
				$this->goAll("余额不足，请先充值",1);
			}
			M("user")->begin();
			M("user")->addMoney(array(
				"userid"=>$userid,
				"content"=>"跑腿任务，赏金加{$money}元,",
				"money"=>-$money
			));
			M("mod_paotui")->update(array(
				"money"=>$row['money']+$money,
				"updatetime"=>date("Y-m-d H:i:s")
			),"id=".$id);
			M("user")->commit();
			$this->goAll("成功",0,$row['money']+$money);
		}
		
		public function onCancel(){
			$id=get('id','i');
			$userid=M("login")->userid;
			$row=M("mod_paotui")->selectRow("id=".$id);
			if($row['status']!=0){
				$this->goAll("任务已处理，无法取消，请联系客服",1);
			}
			if($row['userid']!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_paotui")->begin();
			M("mod_paotui")->update(array(
				"status"=>8
			),"id=".$id);
			if($row["ispay"]){
				M("user")->addMoney(array(
					"userid"=>$userid,
					"content"=>"跑腿任务取消，回收赏金加[money]元,",
					"money"=>$row['money']
				));
			}
			
			M("mod_paotui")->commit();			
			$this->goAll("取消成功");
		}
		
		
		public function onFinish(){
			$id=get('id','i');
			$userid=M("login")->userid;
			$row=M("mod_paotui")->selectRow("id=".$id);
			if($row['status']!=2){
				$this->goAll("任务还未办理完成",1);
			}
			if($row['userid']!=$userid){
				$this->goAll("暂无权限",1);
			}
			$order=M("mod_paotui_order")->selectRow("ptid=".$id." AND status=2");
			if(empty($order)){
				$this->goAll("接单数据出错",1);
			}
			M("mod_paotui")->begin();
			M("mod_paotui")->update(array(
				"status"=>3
			),"id=".$id);
			M("mod_paotui_order")->update(array(
				"status"=>3
			),"id=".$order["id"]);
			$pconfig=MM("paotui","paotui_config")->get();
			$send_money=$order["money"]*(100-$pconfig["per_money"])*0.01;
			MM("paotui","paotui_sender")->addMoney(array(
				"senderid"=>$order["senderid"],
				"content"=>"跑腿任务完成，获得赏金".$send_money."元,",
				"money"=>$send_money
			));
			//给配送员发通知
			MM("paotui","paotui_sender_notice")->add(array(
				"senderid"=>$order["senderid"],
				"content"=>"用户验收完成",
				"linkurl"=>array(
					"path"=>"/sender.php",
					"m"=>"paotui_order",
					"a"=>"show",
					"param"=>"id=".$order["id"]
				)
			));
			M("mod_paotui")->commit();			
			$this->goAll("验收成功");
		}
		
		/**
		 * 超时收货 自动完成
		 */
		public function onAutoFinish(){
			$pconfig=MM("paotui","paotui_config")->get();
			$time=date("Y-m-d H:i:s",time()-3600*24);
			$order=M("mod_paotui_order")->selectRow("  status=2 AND updatetime<'".$time."' ");
			if(empty($order)){
				exit("全部完成");
			}
			$id=$order["ptid"];
			M("mod_paotui")->begin();
			M("mod_paotui")->update(array(
				"status"=>3
			),"id=".$id);
			M("mod_paotui_order")->update(array(
				"status"=>3
			),"id=".$order["id"]);
			
			$send_money=$order["money"]*(100-$pconfig["per_money"])*0.01;
			MM("paotui","paotui_sender")->addMoney(array(
				"senderid"=>$order["senderid"],
				"content"=>"跑腿任务完成，获得赏金".$send_money."元,",
				"money"=>$send_money
			));
			M("mod_paotui")->commit();	
			exit("处理成功");
		}
		
		public function onRatySave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$paotui=M("mod_paotui")->selectRow("id=".$id);
			if($paotui["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($paotui["status"]!=3 || $paotui["israty"]){
				$this->goAll("无法评价",1);
			}
			$grade=post("grade","i");
			$content=post("content","h");
			M("mod_paotui_raty")->insert(array(
				"userid"=>$userid,
				"ptid"=>$id,
				"grade"=>$grade,
				"content"=>$content,
				"createtime"=>date("Y-m-d H:i:s")
			));
			M("mod_paotui")->update(array(
				"israty"=>1
			),"id=".$id);
			M("mod_paotui_order")->update(array(
				"israty"=>1,
				"raty_grade"=>$grade,
				"raty_content"=>$content
			),"ptid=".$id);
			$this->goAll("success");
		}
		
	}
?>