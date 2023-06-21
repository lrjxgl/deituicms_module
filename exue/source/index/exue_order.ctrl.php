<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			 
		}
		
		public function onOrder(){
			$courseid=post("courseid","i");
			$userid=M("login")->userid;
			$course=M("mod_exue_course")->selectRow("courseid=".$courseid." AND status=1 ");
			if(!$course){
				$this->goAll("当前课程已经关闭",1);
			}
			//判断是否有相同的
			$order=M("mod_exue_order")->selectRow("userid=".$userid." AND courseid=".$courseid);
			if($order){
				//$this->goAll("你已经报名过了",1);
			}
			
			$nickname=post("nickname","h");
			$telephone=post("telephone","h");
			if(empty($nickname) || !is_tel($telephone)){
				$this->goAll("请填写联系方式",1);
			}
			
			if($course["price"]==0){
				MM("exue","exue_order")->add(array(
					"userid"=>$userid,
					"courseid"=>$courseid,
					"nickname"=>$nickname,
					"telephone"=>$telephone,
					"money"=>$course["price"],
					"ispay"=>1
				));	
				$this->goAll("报名成功",11);
			}
			
			//生成支付
			if(ALIPAY!=1 && WXPAY!=1){
				$this->goAll("支付未配置无法进行支付操作",1);
			}
			if(INWEIXIN==true && WXPAY==1){
				$pay_type="wxpay";
			}else{
				$pay_type="alipay";
			}
			 
			$order_product="报名《".$course["title"]."》";
			$orderno="re".M("maxid")->get();
			$backurl="/module.php?m=exue_order&a=success&courseid=".$courseid;
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					MM("exue","exue_order")->add(array(
						"userid"=>'.$userid.',
						"courseid"=>'.$courseid.',
						"nickname"=>\''.$nickname.'\',
						"telephone"=>\''.$telephone.'\',
						"money"=>'.$course["price"].'
					));			
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata));
			$money=$course['price'];
			/*****插入充值表******/
			M("recharge")->insert(array(
				"userid"=>$userid,
				"money"=>$money,
				"pay_type"=>$pay_type,
				"orderno"=>$orderno,
				"orderinfo"=>$order_product, 
				"type_id"=>1,
				"tablename"=>"",
				"dateline"=>time(),
				"status"=>2,
				
				"orderdata"=>$orderdata,
			));
			
			/*插入充值表结束*/
			
			$bank_type="";
			$order_info=$order_product;
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
			if(!get("ajax")){
				header("Location: ".$url);
				exit;
			}
			$this->goALl("正在前往支付",0,$redata,$url);
			exit;
		}
		
		public function onSuccess(){
			$this->smarty->display("exue_order/success.html");
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			
			$where=" userid=".$userid ;
			$url="/module.php?m=exue_order&a=my";
			$limit=20;
			$start=get("per_page","i");
			$type=get("type","h");
			switch($type){
				case "baoming":
					$where.=" AND status in(0,1) ";
					break;
				case "raty":
					$where.=" AND status=3 AND israty=0 ";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exue_order")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$cids[]=$v["courseid"];
				}
				$cols=MM("exue","exue_course")->getListByIds($cids);
				foreach($data as $k=>$v){
					$p=$cols[$v["courseid"]];
					$p["price"]=$v["money"];
					$p["createtime"]=$v["createtime"];
					$p["orderid"]=$v["orderid"];
					 
					$data[$k]=$p;
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
			$this->smarty->display("exue_order/my.html");
		}
		
		public function onShow(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$orderid=get_post("orderid","i");
			$order=M("mod_exue_order")->selectRow(array("where"=>"orderid=".$orderid));
			if($order["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$order["status_name"]=MM("exue","exue_order")->getStatus($order["status"]);
			$course=M("mod_exue_course")->selectRow(array(
				"where"=>" courseid=".$order["courseid"],
				"fields"=>"*"
			));
			$course["imgurl"]=images_site($course["imgurl"]);
			$shop=M("mod_exue_shop")->selectRow(array(
				"where"=>" shopid=".$order["shopid"],
				"fields"=>"*"
			));
			$shop["imgurl"]=images_site($shop["imgurl"]);
			$stList=MM("exue","exue_student")->Dselect(array(
				"where"=>" userid=".$userid." AND status=1 "
			));
			$student=array();
			if($order["stid"]){
				$student=MM("exue","exue_student")->get($order["stid"]);
			}
			$teacher=array();
			if($order["tcid"]){
				$teacher=MM("exue","exue_teacher")->get($order["tcid"]);
			}
			$this->smarty->goassign(array(
				"order"=>$order,
				"course"=>$course,
				"shop"=>$shop,
				"stList"=>$stList,
				"student"=>$student,
				"teacher"=>$teacher
			));
			$this->smarty->display("exue_order/show.html");
		}
		
		public function onBindStudent(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$orderid=post("orderid","i");
			$stid=post("stid","i");
			$order=M("mod_exue_order")->selectRow("orderid=".$orderid);
			if($order["stid"]){
				$this->goAll("该课程已绑定学生",1);
			}
			if($order["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_exue_order")->begin();
			M("mod_exue_order")->update(array(
				"stid"=>$stid
			),"orderid=".$orderid);
			//绑定学校学生
			M("mod_exue_shop")->changenum("student_num",1,"shopid=".$order["shopid"]);
			$st=M("mod_exue_shop_student")->selectRow("shopid=".$order["shopid"]." AND stid=".$stid);
			if($st){
				M("mod_exue_shop_student")->update(array(
					"updatetime"=>date("Y-m-d H:i:s")
				),"id=".$st["id"]);	
			}else{
				M("mod_exue_shop_student")->insert(array(
					"userid"=>$userid,
					"shopid"=>$order["shopid"],
					"stid"=>$stid,
					"createtime"=>date("Y-m-d H:i:s"),
					"updatetime"=>date("Y-m-d H:i:s")
				));
			}
			M("mod_exue_order")->commit();
			$this->goAll("绑定成功");
		}
		
		public function onBaoMing(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$orderid=get_post("orderid","i");			 
			$order=M("mod_exue_order")->selectRow("orderid=".$orderid);			 
			if($order["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($order["status"]>=2){
				$this->goAll("已经报过名了",1);
			}
			M("mod_exue_order")->begin();
			MM("exue","exue_order")->BaoMing($orderid,$order);
			M("mod_exue_order")->commit();
			$this->goAll("报名成功");
		}
		
		public function onRaty(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$orderid=get_post("orderid","i");			 
			$order=M("mod_exue_order")->selectRow("orderid=".$orderid);			 
			if($order["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($order["status"]<2){
				$this->goAll("还未确认报名，无法评价",1);
			}
			$this->smarty->goAssign(array(
				"order"=>$order
			));
			$this->smarty->display("exue_order/raty.html");
		}
		public function onRatySave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$orderid=get_post("orderid","i");			 
			$order=M("mod_exue_order")->selectRow("orderid=".$orderid);			 
			if($order["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($order["status"]<2){
				$this->goAll("还未确认报名，无法评价",1);
			}
			$raty_content=post("raty_content","h");
			$raty_grade=post("raty_grade","i");
			M("mod_exue_order")->update(array(
				"raty_content"=>$raty_content,
				"raty_grade"=>$raty_grade,
				"raty_time"=>date("Y-m-d H:i:s"),
				"israty"=>1
			),"orderid=".$orderid);
			$this->goAll("评价成功");
		}
		
		
	}
	
	

?>