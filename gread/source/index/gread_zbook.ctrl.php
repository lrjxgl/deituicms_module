<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_zbookControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			
			$seo=M("seo")->get("gread_zbook","default");
			$content='
				<div class="mgb-5">针对买书费用贵，我们推出一折买书以租代购服务</div>
				<div class="mgb-5">
					1、对于代购书籍，收取标价一折费用。
				</div>
				<div class="mgb-5">2、按书籍标价占用保证金，最低时长一个月。还书则可解除占用</div>
				<div class="mgb-5">3、单用户代购书籍总价少于500元</div>
				
				<div class="text-center cl-red">新书代购，一折买书，代购更省钱</div> 
			';
			$data=array(
				"content"=>$content
			);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"seo"=>$seo 
				)
			);
			$this->smarty->display("gread_zbook/index.html");
		}
		
		
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=gread_zbook&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gread_zbook")->select($option,$rscount);
			$statusList=array(
				0=>"未接单",
				1=>"处理中",
				3=>"已完成",
				4=>"已取消"
			);
			if($data){
				foreach($data as $k=>$v){
					$v["status_name"]=$statusList[$v["status"]];
					$imgs=[];
					if(!empty($v["imgsdata"])){
						$imgs=explode(",",$v["imgsdata"]);
						foreach($imgs as $ik=>$im){
							$im=images_site($im);
							$imgs[$ik]=$im;
						}
						 
					}
					$v["imgList"]=$imgs;
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
			$this->smarty->display("gread_zbook/my.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			
			$this->smarty->goassign(array(
				"a"=>1
			));
			$this->smarty->display("gread_zbook/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_gread_zbook")->postData();
			$data["userid"]=$userid;
			$shopid=MM("gread","gread")->getShopId();
			$data["shopid"]=$shopid;
			$data["createtime"]=date("Y-m-d H:i:s");
			M("mod_gread_zbook")->insert($data);
			$this->goall("下单成功");
		}
		
		 
		public function onDelete(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post('id',"i");
			$row=M("mod_gread_zbook")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]!=0){
				$this->goAll("已接单不能取消",1);
			}
			M("mod_gread_zbook")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>