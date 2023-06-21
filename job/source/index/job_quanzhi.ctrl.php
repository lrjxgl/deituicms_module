<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class job_quanzhiControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=job_quanzhi&a=default";
			$catid=get("catid","i");
			if($catid){
				$cids=MM("job","job_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
			}
			$moneyList=array(
				1=>"3000元以下",
				2=>"3000-5000元",
				3=>"5000-8000元",
				4=>"8000以上"
			);
			$moneyChoice=get("money_choice","h");
			if(!empty($moneyChoice)){
				$arr=explode("-",$moneyChoice);
				if(!isset($arr[1])){
					$min=intval($arr[0]);
					if($min<8000){
						$where.=" AND money<".$min;
					}else{
						$where.=" AND money>".$min;
					}
				}else{
					$min=intval($arr[0]);
					$max=intval($arr[1]);
					$where.=" AND ( money>=".$min." AND money<".$max.")";
				}
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_job_quanzhi")->select($option,$rscount);
			$catList=MM("job","job_category")->children("quanzhi",0,1);
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
					"catList"=>$catList,
					"moneyList"=>$moneyList
				)
			);
			$this->smarty->display("job_quanzhi/index.html");
		}
		
		public function onList(){
			$catid=get("catid","i");
			$where=" status =1 ";
			if($catid){
				$cids=MM("job","job_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
			}
			$cat=MM("job","job_category")->selectRow("catid=".$catid);
			$url="/module.php?m=job_quanzhi&a=list&catid=".$catid;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_job_quanzhi")->select($option,$rscount);
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
					"cat"=>$cat
				)
			);
			$this->smarty->display("job_quanzhi/list.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_job_quanzhi")->selectRow(array("where"=>"id=".$id));
			//判断是否报名了
			$userid=M("login")->userid;
			$isBaoming=false;
			if($userid){
				$row=M("mod_job_quanzhi_baoming")->selectRow("objectid=".$id." AND userid=".$userid);
				if(!empty($row)){
					$isBaoming=true;
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"isBaoming"=>$isBaoming,
				"userid"=>$userid
			));
			$this->smarty->display("job_quanzhi/show.html");
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=job_quanzhi&a=my";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_job_quanzhi")->select($option,$rscount);
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
			$this->smarty->display("job_quanzhi/my.html");
		}
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			if($id){
				$data=M("mod_job_quanzhi")->selectRow(array("where"=>"id=".$id));
				
			}
			$catList=MM("job","job_category")->children("quanzhi");
			$this->smarty->goassign(array(
				"data"=>$data,
				"catList"=>$catList
			));
			$this->smarty->display("job_quanzhi/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_job_quanzhi")->postData();
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			$row=M("mod_job")->selectRow("1");
			$payMoney=intval($row["qz_money"]);
			$user=M("user")->selectRow(array(
				"fields"=>"money",
				"where"=>" userid=".$userid
			));
			$action="pay";
			if($payMoney<$user['money'] && 1==2){
				$data['ispay']=1;
				$action="finish";
				M("user")->addMoney(array(
					"userid"=>$userid,
					"money"=>-$money,
					"content"=>"你发布了一个兼职，花了￥{$payMoney}元 "
				));
			}
			$data["paymoney"]=$payMoney;
			if($id){
				M("mod_job_quanzhi")->update($data,"id='$id'");
			}else{
				$id=M("mod_job_quanzhi")->insert($data);
			}
			$rdata=array(
				"action"=>$action,
				"id"=>$id
			);
			if(!$data['ispay']){
				$_GET['id']=$id;
				$res=$this->onPay(1);
				$rdata['payurl']=$res['payurl'];
				$rdata['orderno']=$res['orderno'];
			}
			$this->goall("保存成功",0,$rdata);
		}
		
		public function onPay($return=0){
			$orderno="Re".M("maxid")->get();
			$id=get("id",'i');
			$ask=M("mod_job_quanzhi")->selectRow("id=".$id);
			$backurl="/module.php?m=job_quanzhi&a=show&id=".$id;
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					M("mod_job_quanzhi")->update(array(
						"ispay"=>1,
						"status"=>1
					),"id='.$id.'");
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$orderinfo="发布招聘信息";
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$fromapp=get("fromapp");
			$money= $ask['paymoney'];
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
			$order_product="发布招聘信息";
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
		
		public function onStatus(){
			$id=get_post('id',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_job_quanzhi")->update(array("bstatus"=>$bstatus),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_job_quanzhi")->update(array("bstatus"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>