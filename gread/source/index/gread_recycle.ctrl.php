<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_recycleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$seo=M("seo")->get("gread_recycle","default");
			$content='
				<div class="mgb-5">旧书回收按标价1折进行回收</div>
				<div class="mgb-5">书籍类型要适合中小学儿童阅读</div>
				<div class="mgb-5">回收的图书主要用在共享图书项目上</div>
				<div class="mgb-5">大家可以送到书店或者我们上门回收</div>
				<div>如果大家有闲置的图书，欢迎回收给我们</div>
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
			$this->smarty->display("gread_recycle/index.html");
		}
		
		
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=gread_recycle&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gread_recycle")->select($option,$rscount);
			$statusList=array(
				0=>"未接单",
				1=>"处理中",
				3=>"已完成",
				4=>"已取消"
			);
			if($data){
				foreach($data as $k=>$v){
					$v["status_name"]=$statusList[$v["status"]];
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
			$this->smarty->display("gread_recycle/my.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			
			$this->smarty->goassign(array(
				"a"=>1
			));
			$this->smarty->display("gread_recycle/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_gread_recycle")->postData();
			$data["userid"]=$userid;
			$shopid=MM("gread","gread")->getShopId();
			$data["shopid"]=$shopid;
			$data["createtime"]=date("Y-m-d H:i:s");
			M("mod_gread_recycle")->insert($data);
			$this->goall("保存成功");
		}
		
		 
		public function onDelete(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post('id',"i");
			$row=M("mod_gread_recycle")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]!=0){
				$this->goAll("已接单不能取消",1);
			}
			M("mod_gread_recycle")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>