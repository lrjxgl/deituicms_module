<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class teamwork_shop_product_item_logControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/index.php?m=teamwork_shop_product_item_log&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_teamwork_shop_product_item_log")->Dselect($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['timeago']=timeago(strtotime($v['createtime']));
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("teamwork_shop_product_item_log/index.html");
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/index.php?m=teamwork_shop_product_item_log&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_teamwork_shop_product_item_log")->Dselect($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['timeago']=timeago(strtotime($v['createtime']));
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("teamwork_shop_product_item_log/my.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			
			$data=M("mod_teamwork_shop_product_item_log")->selectRow(array("where"=>"id={$id}"));
			if(empty($data)){
				$this->goAll("数据出错",1);
			}
			$userid=M("login")->userid;
			M("mod_teamwork_shop_product")->checkAccess($data['productid'],$userid);	
			$data['timeago']=timeago(strtotime($data['createtime']));
			$user=M("user")->selectRow(array(
				"where"=>"userid=".$data['userid'],
				"fields"=>"userid,nickname,user_head"
			));
			$item=M("mod_teamwork_shop_product_item")->selectRow("id=".$data['itemid']);
			$this->smarty->goassign(array(
				"data"=>$data,
				"user"=>$user,
				"item"=>$item
			));
			$this->smarty->display("teamwork_shop_product_item_log/show.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			$itemid=get("itemid",'i');
			$productid=get("productid","id");
			if(empty($productid)){
				$this->goAll("请选择项目",1);
			}
			$userid=M("login")->userid;
			M("mod_teamwork_shop_product")->checkAccess($productid,$userid);	
			$itemid=get("itemid","i");
			$product=M("mod_teamwork_shop_product")->selectRow(array(
				"where"=>"id=".$productid,
				"fields"=>"id,title"
			));	
			$item=M("mod_teamwork_shop_product_item")->selectRow(array(
				"where"=>"id=".$itemid,
				"fields"=>"id,title"
			));
			if($id){
				$data=M("mod_teamwork_shop_product_item_log")->selectRow(array("where"=>"id={$id}"));
				
			}
			 
			$this->smarty->goassign(array(
				"data"=>$data,
				"product"=>$product,
				"itemid"=>$itemid,
				"item"=>$item
			));
			$this->smarty->display("teamwork_shop_product_item_log/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$productid=get_post("productid","id");
			$data=M("mod_teamwork_shop_product_item_log")->postData();
			
			if(empty($productid)){
				$this->goAll("请选择项目",1);
			}
			if(empty($data['title'])){
				$this->goAll("请填写主题",1);
			}
			 
			$userid=M("login")->userid;
			M("mod_teamwork_shop_product")->checkAccess($productid,$userid);	
			if($id){
				$row=M("mod_teamwork_shop_product_item_log")->selectRow("id=".$id);
				if($row['userid']!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_teamwork_shop_product_item_log")->update($data,"id='$id'");
			}else{
				$data['userid']=$userid;
				$data['createtime']=date("Y-m-d H:i:s");
				M("mod_teamwork_shop_product_item_log")->insert($data);
				 
				if(isset($data['itemid'])){
					$item=M("mod_teamwork_shop_product_item")->selectRow("id=".$data['itemid']);
					//通知用户
					M("notice")->add(array(
						"userid"=>$userid,
						"content"=>"您的需求[{$item['title']}]有了新进展，",
						"linkurl"=>array(
							"path"=>"/index.php",
							"m"=>"teamwork_shop_product_item","a"=>"show","param"=>"id=".$item['id'])
					));
					
				}
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			$userid=M("login")->userid;
			$row=M("mod_teamwork_shop_product_item_log")->selectRow("id=".$id);
			if($row['userid']!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_teamwork_shop_product_item_log")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$userid=M("login")->userid;
			$row=M("mod_teamwork_shop_product_item_log")->selectRow("id=".$id);
			if($row['userid']!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_teamwork_shop_product_item_log")->update(array("status"=>99),"id=$id");
			$this->goall("删除成功",0);
		}
		
		
	}

?>