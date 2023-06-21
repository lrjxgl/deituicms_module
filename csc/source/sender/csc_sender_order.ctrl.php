<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_sender_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" senderid=".SENDERID;
			$url="/sender.php?m=csc_sender_order&a=default";
			$limit=20;
			$start=get("per_page","i");
			$type=get("type","h");
			switch(get('type')){
			 
				case "new":
					$url.="&type=new";
					$where.=" AND status=0 ";
					break;
				 
				case "unsend":
					$url.="&type=send";
					$where.=" AND status =1 ";
					break;
				case "unreceived":
					$url.="&type=unreceived";
					$where.=" AND status =2 ";
					break;	
				case "finish":
					$url.="&type=finish";
					$where.=" AND status=3 ";
					break;
				default:
					$type="all";
					$where.=" AND status in(0,1,2,3,4) ";
					break;
				
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ptorderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("csc","csc_sender_order")->Dselect($option,$rscount);
			 
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
					"type"=>$type
				)
			);
			$this->smarty->display("csc_sender_order/index.html");
		}
		
		public function onShow(){
			$ptorderid=get_post("ptorderid","i");
			$data=MM("csc","csc_sender_order")->get($ptorderid);
			/*$data=M("mod_csc_sender_order")->selectRow(array("where"=>"ptorderid=".$ptorderid));
			$statusList=MM("csc","csc_sender_order")->statusList(); 
			$data["status_name"]=$statusList[$data["status"]];
			$orderid=$data["orderid"];
			$order=MM("csc","csc_order")->selectRow("orderid=".$orderid);
			$orderdata=MM("csc","csc_order_data")->get($orderid);
			 
			$order["status_name"]=MM("csc","csc_order")->getStatus($order);
			$order["timeago"]=timeago(strtotime($order["createtime"]));
			*/
			$this->smarty->goassign($data);
			$this->smarty->display("csc_sender_order/show.html");
		}
	 
		
		public function onConfirm(){
			$ptorderid=get("ptorderid","i");
			$data=M("mod_csc_sender_order")->selectRow(array("where"=>"ptorderid=".$ptorderid));
			if($data["status"]!=0){
				$this->goAll("无法处理",1);
			}
			M("mod_csc_sender_order")->update(array(
				"status"=>1,
				"stime"=>time()
			),"ptorderid=".$ptorderid);
			
			$this->goAll("确认接单");
		}
		public function onCancel(){
			$ptorderid=get("ptorderid","i");
			$data=M("mod_csc_sender_order")->selectRow(array("where"=>"ptorderid=".$ptorderid));
			if($data["status"]!=0){
				$this->goAll("无法处理",1);
			}
			M("mod_csc_sender_order")->update(array(
				"status"=>4			 
			),"ptorderid=".$ptorderid);
			M("mod_csc_sender_order")->update(array(
				"senderid"=>0,
			),"orderid=".$data["orderid"]);
			$this->goAll("确认取消");
		}
		public function onSend(){
			$ptorderid=get("ptorderid","i");
			$data=M("mod_csc_sender_order")->selectRow(array("where"=>"ptorderid=".$ptorderid));
			if($data["status"]!=1){
				$this->goAll("无法处理",1,$data);
			}
			M("mod_csc_sender_order")->update(array(
				"status"=>2
				 
			),"ptorderid=".$ptorderid);
			M("mod_csc_sender_order")->update(array(
				"status"=>2,
			),"orderid=".$data["orderid"]);
			$this->goAll("确认配送完成");
		}
		
	}

?>