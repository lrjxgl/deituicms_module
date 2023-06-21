<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class  recycleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$userid=M("login")->userid;
			$shopid=MM("recycle","recycle")->getShopid();
			if(!$shopid){
				if(get("ajax")){
					$this->goAll("请选择回收站",2);
				}else{
					CC("recycle","recycle_shop")->smarty->assign("skins","module/recycle/themes/index/");
					CC("recycle","recycle_shop")->smarty->template_dir="module/recycle/themes/index/";
					CC("recycle","recycle_shop")->onNear();
				}
				
			}
			$shop=false;
			if($shopid){
				$shop=MM("recycle","recycle_shop")->selectRow("shopid=".$shopid);
			}
			 
			$fromapp=get("fromapp");
			switch($fromapp){
				case "uniapp":
					$flashList=M("ad")->listByNo("uniapp-recycle-index");
					$adList=M("ad")->listByNo("uniapp-recycle-ad");
					$navList=M("ad")->listByNo("uniapp-recycle-nav"); 
					break;
				default:
					$flashList=M("ad")->listByNo("wap-recycle-index");
					$adList=M("ad")->listByNo("wap-recycle-ad");
					$navList=M("ad")->listByNo("wap-recycle-nav"); 
					break;
			}
			$seo=M("seo")->get("recycle","default");
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
			$data=M("dataapi")->getWord("物品回收说明");
			$recycle_price=M("mod_recycle_shop_price")->selectOne(array(
				"where"=>" status in(0,1) AND shopid=".$shopid,
				"fields"=>"content",
				"order"=>" id DESC"
			));
			 
			if(empty($recycle_price)){
				$recycle_price="";
			}
			$this->smarty->goassign(
				array(
					"flashList"=>$flashList,
					"adList"=>$adList,
					"navList"=>$navList,
					"shopid"=>$shopid,
					"shop"=>$shop,
					"data"=>$data,
					"seo"=>$seo,
					"recycle_price"=>nl2br($recycle_price),
					"userid"=>$userid
				)
			);
			$this->smarty->display("recycle/index.html");
		}
		
		public function onShow(){
			$id=get("id","i");
			$userid=M("login")->userid;
			$row=MM("recycle","recycle")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$statusList=MM("recycle","recycle")->statusList();
			$row["status_name"]=$statusList[$row["status"]];
			$logList=M("mod_recycle_log")->select(array(
				"where"=>"recycleid=".$id,
				"order"=>"id ASC"
			));
			$raty=[];
			if($row["israty"]){
				$raty=M("mod_recycle_raty")->selectRow("recycleid=".$id);
			}
			$this->smarty->goAssign(array(
				"data"=>$row,
				"logList"=>$logList,
				"raty"=>$raty
			));
			$this->smarty->display("recycle/show.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." ";
			$type=get("type","h");
			switch($type){
				case "new":
					$where.=" AND status=0 ";
					break;
				case "confirm":
					$where.=" AND status=1 ";
					break;
				case "send":
					$where.=" AND status=2 ";
					break;
				case "finish":
					$where.=" AND status=3 ";
					break;
				case "cancel":
					$where.=" AND status=4 ";
					break;
				case "unraty":
					$where.=" AND status=3 AND israty=0 ";
					break;	
				default:
					$where.=" AND status in(0,1,2,3,4) ";
					break;
			}
			$url="/module.php?m=recycle&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_recycle")->select($option,$rscount);
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
			$this->smarty->display("recycle/my.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			
			$this->smarty->goassign(array(
				"a"=>1
			));
			$this->smarty->display("recycle/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_recycle")->postData();
			if(empty($data["description"])){
				$this->goAll("请输入物品详情",1);
			}
			if(empty($data["address"]) || !is_tel($data["telephone"])){
				$this->goAll("请输入联系方式",1);
			}
			if(empty($data["freetime"])){
				$this->goAll("请输入空闲时间",1);
			}
			$data["userid"]=$userid;
			$shopid=MM("recycle","recycle")->getShopId();
			$data["shopid"]=$shopid;
			$data["createtime"]=date("Y-m-d H:i:s");
			M("mod_recycle")->insert($data);
			$this->goall("保存成功");
		}
		
		 
		public function onDelete(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post('id',"i");
			$row=M("mod_recycle")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]!=0){
				$this->goAll("已接单不能取消",1);
			}
			M("mod_recycle")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>