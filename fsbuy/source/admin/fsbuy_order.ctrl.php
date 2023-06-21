<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsbuy_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get('type','h');
			$url="/moduleadmin.php?m=fsbuy_order&type=".$type;
			$limit=24;
			$start=get("per_page","i");
			
			switch($type){
				case "unpay":
					
					$where=" status=0 AND ispay=0 ";
					break;
				case "new":
					$where=" status=0 AND ispay=1 ";
					break;
				case "finish":
					$where=" status=3 ";
					break;
				case "cancel":
					$where=" status=4 ";
					break;
				default:
					$where=" status in(0,1,2,3,4) ";
					break;
			}
			$fsid=get("fsid","i");
			if($fsid){
				$where.=" AND fsid=".$fsid;
				$url.="&fsid=".$fsid;
				$fsbuy=M("mod_fsbuy")->selectRow(array(
					"where"=>"fsid=".$fsid,
					"fields"=>"fsid,title"
				));
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fsbuy_order")->select($option,$rscount);
			$statuslist=MM("fsbuy","fsbuy_order")->statuslist();
			 
			if($data){
				foreach($data as $v){
					$ids[]=$v['fsid'];
					$uids[]=$v["userid"];
				}
				$fsbuys=MM("fsbuy","fsbuy")->getListByIds($ids);
				$us=M("user")->getUserByIds($uids); 
				foreach($data as $k=>$v){
					$v['fsbuy']=$fsbuys[$v['fsid']];
				 	$v['timeago']=$v['createtime'];
					$v['status_name']=$v["ispay"]?$statuslist[$v['status']]:'待支付';
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["user_head"]=$us[$v["userid"]]["user_head"];
					$data[$k]=$v;
				}
				 
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
					"fsbuy"=>$fsbuy,
					"fsid"=>$fsid
				)
			);
			$this->smarty->display("fsbuy_order/index.html");
		}
		
		public function onShow(){
			$orderid=get_post('orderid',"i");
			$data=MM("fsbuy","fsbuy_order")->selectRow(array("where"=>"orderid=".$orderid));
			$statuslist=MM("fsbuy","fsbuy_order")->statuslist();
			$data['status_name']=$statuslist[$data['status']];
			$data['ispay_name']=$data['ispay']?"已支付":"未支付";
			$data['timeago']=$data['createtime'];
			$fsbuy=MM("fsbuy","fsbuy")->selectRow("fsid=".$data['fsid']);
			$fsbuy['imgurl']=images_site($fsbuy['imgurl']);
			$loglist=M("mod_fsbuy_order_log")->select(array(
				"where"=>" orderid=".$orderid
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"fsbuy"=>$fsbuy,
				"loglist"=>$loglist
			));
			$this->smarty->display("fsbuy_order/show.html");
		}
		public function onConfirm(){
			$orderid=get_post('orderid',"i");
			$order=M("mod_fsbuy_order")->selectRow(array("where"=>"orderid=".$orderid));
			if($order["ispay"]!=1){
				$this->goAll("未支付无法处理",1);
			}
			if($order["status"]>2){
				$this->goAll("处理过了",1);
			}
			$status=1;
			M("mod_fsbuy_order")->update(array("status"=>$status),"orderid=".$orderid);
			$rdata=array(
				"status_name"=>"已确认"
			);
			$content=get_post("content","h");
			M("mod_fsbuy_order_log")->insert(array(
				"orderid"=>$orderid,
				"content"=>$content,
				"createtime"=>date("Y-m-d H:i:s")
			));
			$this->goall("确认成功",0,$rdata);
		}
		
		public function onSend(){
			$orderid=get_post('orderid',"i");
			$order=M("mod_fsbuy_order")->selectRow(array("where"=>"orderid=".$orderid));
			if($order["ispay"]!=1){
				$this->goAll("未支付无法处理",1);
			}
			if($order["status"]>2){
				$this->goAll("处理过了",1);
			}
			$status=2;
			M("mod_fsbuy_order")->update(array("status"=>$status),"orderid=".$orderid);
			$rdata=array(
				"status_name"=>"已发货"
			);
			$content=get_post("content","h");
			M("mod_fsbuy_order_log")->insert(array(
				"orderid"=>$orderid,
				"content"=>$content,
				"createtime"=>date("Y-m-d H:i:s")
			));
			$this->goall("发货成功",0,$rdata);
		}
		
		public function onFinish(){
			$orderid=get_post('orderid',"i");
			$status=3;
			$order=M("mod_fsbuy_order")->selectRow(array("where"=>"orderid=".$orderid));
			if($order["ispay"]!=1){
				$this->goAll("未支付无法处理",1);
			}
			if($order["status"]>2){
				$this->goAll("处理过了",1);
			}
			M("mod_fsbuy_order")->update(array("status"=>$status),"orderid=".$orderid);
			$rdata=array(
				"status_name"=>"已完成"
			);
			$content=get_post("content","h");
			M("mod_fsbuy_order_log")->insert(array(
				"orderid"=>$orderid,
				"content"=>$content,
				"createtime"=>date("Y-m-d H:i:s")
			));
			//处理邀请用户赏金
			MM("fsbuy","fsbuy_order")->doInvite($order);
			$this->goall("确认完成",0,$rdata);
		}
		
		public function onCancel(){
			$orderid=get_post('orderid',"i");
			$order=M("mod_fsbuy_order")->selectRow(array("where"=>"orderid=".$orderid));
			if($order["status"]!=0){
				$this->goAll("无法取消",1);
			}
			$status=4;
			M("mod_fsbuy_order")->begin();
			M("mod_fsbuy_order")->update(array("status"=>$status),"orderid=".$orderid);
			$rdata=array(
				"status_name"=>"已取消"
			);
			$content=get_post("content","h");
			M("mod_fsbuy")->changenum("buynum",-1,"fsid=".$order["fsid"]);
			M("mod_fsbuy_order_log")->insert(array(
				"orderid"=>$orderid,
				"content"=>$content,
				"createtime"=>date("Y-m-d H:i:s")
			));
			M("mod_fsbuy_order")->commit();
			$this->goall("取消完成",0,$rdata);
		}
		 
		
		
	}

?>