<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class flk_queueControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=flk_queue&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" dateline DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_flk_queue")->select($option,$rscount);
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
			$this->smarty->display("flk_queue/index.html");
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/module.php?m=flk_queue&a=my";	
			$limit=24;
			$start=get("per_page","i");
			$type=get("type","h");
			switch($type){
				case "uncheck":
					$where.=" AND ischeck=0 ";
					break;
				
				case "wait":
					$where.=" AND ischeck=1 AND isback=0 AND isfinish=0 ";
					break;
				case "doing":
					$where.=" AND ischeck=1 AND isback=1 AND isfinish=0 ";
					break;	
				case "finish":
					$where.=" AND ischeck=1 AND  isfinish=1 ";
					break;	
				default:
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_flk_queue")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$shopids[]=$v["shopid"];
				}
				$sps=MM("flk","flk_shop")->getListByIds($shopids);
				foreach($data as $k=>$v){
					$v["shop_name"]=$sps[$v["shopid"]]["shopname"];
					$v["can_daxin"]=time()-$v["dateline"]>3600*24*30?1:0;
					if($v["isnew"]){
						$v["status_name"]="已打新";
					}else{
						if($v["isfinish"]==1){
							$v["status_name"]="已完成";
						}elseif($v["ischeck"]==0){
							$v["status_name"]="未生效";
						}elseif($v["isback"]==0){
							$v["status_name"]="排队中";
						}else{
							$v["status_name"]="返还中";
						}
						
					}
					
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
			$this->smarty->display("flk_queue/my.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_flk_queue")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("flk_queue/show.html");
		}
		
		public function onEwm(){
			$orderid=get("orderid","i");
			$order=M("mod_flk_order")->selectRow("orderid=".$orderid);
			$shop=M("mod_flk_shop")->selectRow("shopid=".$order["shopid"]);
			$content=HTTP_HOST."/module.php?m=flk_shop&shopid=".$order["shopid"];
			$type=get("type","h");
			
			if($type=='share'){
				$title=$shop["shopname"]."||"."我消费的".$order["shop_money"]."元，全部报销啦||惊喜不断，快来购买";
			}else{
				$title=$shop["shopname"]."||"."我刚去了这家店，真的很不错||推荐大家去看看";
				$content.="&set_invite_orderid=".$order["orderid"];
			}
			$ewm=HTTP_HOST."/index.php?m=qrcode&content=".urlencode($content)."&title=".urlencode($title);
			echo json_encode(array(
				"error"=>0,
				"ewm"=>$ewm
			));
		}
		
		public function onCancel(){
			$id=get_post("id","i");
			$data=M("mod_flk_queue")->selectRow(array("where"=>"id=".$id));
			if($data["status"]!=0 || $data["isback"]){
				$this->goAll("暂时无法退出",1);
			}
			if($data["ischeck"] && $data["isback"]==0){
				MM("flk","flk_queue")->update(array(
					"isfinish"=>1,
					"status"=>4,
					"isback"=>1
				),"id=".$id);
				//退金额
				MM("flk","flk_account")->addMoney(array(
					"userid"=>$data["userid"],
					"money"=>$data["flk_money"],
					"content"=>"您退出排队，返还{$data["flk_money"]}"
				));
			}
			$this->goAll("退出成功");
		}
		
		public function onDaxin(){
			$id=get_post("id","i");
			$data=M("mod_flk_queue")->selectRow(array("where"=>"id=".$id));
			if($data["status"]!=0 || $data["isnew"] || $data["isback"]){
				$this->goAll("暂时无法打新",1);
			}
			if(time()-$data["dateline"]<3600*24*30){
				$this->goAll("订单超过一个月才可以打新",1);
			}
			$ct=M("mod_flk_queue")->selectOne(array(
				"where"=>" shopid=".$data["shopid"]." AND dateline<".$data["dateline"],
				"fields"=>"count(*) as ct"
			));
			if($ct<30 && 1==2){
				$this->goAll("您排在前30名，无需打新",1);
			}
			if($data["isdaxin"]){
				$this->goAll("已经打新了",1);
			}
			if($data["ischeck"] && $data["isnew"]==0 && $data["isback"]==0){
				MM("flk","flk_queue")->update(array(
					"status"=>1,
					"isnew"=>1,
					"isfinish"=>1
					
				),"id=".$id);
				MM("flk","flk_daxin")->add(array(
					"userid"=>$data["userid"],
					"money"=>$data["total_money"]
					 
				));
			}
			$this->goAll("打新成功");
		}
		
	}

?>