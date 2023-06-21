<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class teamwork_shop_product_itemControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onInit(){
			M("login")->checkLogin();
		}
		
		public function onDefault(){
			 
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/index.php?m=teamwork_shop_product_item&a=my";
			if(isset($_GET['status'])){
				$where.=" AND status=".max(0,get("status","i"));
			}else{
				$where.=" AND status<4"; 
			}	
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_teamwork_shop_product_item")->Dselect($option,$rscount);
			$statusList=M("mod_teamwork_shop_product_item")->statusList(); 
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
					"statusList"=>$statusList,
				)
			);
			$this->smarty->display("teamwork_shop_product_item/my.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			
			$data=M("mod_teamwork_shop_product_item")->selectRow(array("where"=>"id={$id}"));			
			if(empty($data)){
				$this->goAll("数据出错",1);
			}
			$userid=M("login")->userid;
			M("mod_teamwork_shop_product")->checkAccess($data['productid'],$userid);
			$user=M("user")->selectRow(array(
				"where"=>"userid=".$data['userid'],
				"fields"=>"userid,nickname,user_head"
			));
			$data['timeago']=timeago(strtotime($data['createtime']));
			$statusList=M("mod_teamwork_shop_product_item")->statusList();
			$data['status_name']=$statusList[$data['status']];
			//反馈
			$commentList=M("mod_teamwork_shop_product_item_comment")->select(array(
				"where"=>" itemid=".$id,
				"order"=>"id DESC",
				"limit"=>2
			));
			if($commentList){
				foreach($commentList as $k=>$v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($commentList as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['timeago']=timeago(strtotime($v['createtime']));
					$commentList[$k]=$v;
				}
			}
			//进度
			$logList=M("mod_teamwork_shop_product_item_log")->select(array(
				"where"=>" itemid=".$id,
				"order"=>"id DESC",
				"limit"=>4
			));
			if($logList){
				foreach($logList as $k=>$v){
					$v['timeago']=timeago(strtotime($v['createtime']));
					$logList[$k]=$v;
				}
			}
			//测试
			//进度
			$testList=M("mod_teamwork_shop_product_item_test")->select(array(
				"where"=>" itemid=".$id,
				"order"=>"id DESC",
				"limit"=>4
			));
			 
			if($testList){
				foreach($testList as $v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($testList as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['timeago']=timeago(strtotime($v['createtime']));
					$testList[$k]=$v;
				}
				if(count($testList)>=3){
					$data['canFinish']=1;
				}
			}
			$isauthor=0;
			if($userid==$data['userid']){
				$isauthor=1;
			}
			$isadmin=0;
			$product=M("mod_teamwork_shop_product")->selectRow("id=".$data['productid']);
			if($userid==$product['userid']){
				$isadmin=1;
			} 
			 
			$this->smarty->goassign(array(
				"data"=>$data,
				"commentList"=>$commentList,
				"logList"=>$logList,
				"testList"=>$testList,
				"user"=>$user,
				"isadmin"=>$isadmin,
				"isauthor"=>$isauthor
			));
			$this->smarty->display("teamwork_shop_product_item/show.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			$productid=get("productid","id");
			if(empty($productid)){
				$this->goAll("请选择项目",1);
			}
			$userid=M("login")->userid;
			M("mod_teamwork_shop_product")->checkAccess($productid,$userid);
			$product=M("mod_teamwork_shop_product")->selectRow(array(
				"where"=>"id=".$productid,
				"fields"=>"id,title"
			));	
			 
			if($id){
				$data=M("mod_teamwork_shop_product_item")->selectRow(array("where"=>"id={$id}"));				
			}
			$typelist=M("mod_teamwork_shop_product_item")->typelist();
			$this->smarty->goassign(array(
				"data"=>$data,
				"product"=>$product,
				"typelist"=>$typelist				 
			));
			$this->smarty->display("teamwork_shop_product_item/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$endtime=post("endtime","h");
			$data=M("mod_teamwork_shop_product_item")->postData();
			if(empty($data['productid'])){
				$this->goAll("请选择项目",1);
			}
			if(empty($data['title'])){
				$this->goAll("请填写主题",1);
			}
			$userid=M("login")->userid;
			M("mod_teamwork_shop_product")->checkAccess($data['productid'],$userid);
			//$endDay=post("endDay",'i');
			//$data['endtime']=date("Y-m-d H:i:s",time()+$endDay*3600*24);
			$data['endtime']=$endtime;
			if($id){
				$row=M("mod_teamwork_shop_product_item")->selectRow("id=".$id);
				if($row['userid']!=$userid){
					//$this->goAll("暂无权限",1);
				}
				M("mod_teamwork_shop_product_item")->update($data,"id='$id'");
			}else{
				$data['userid']=$userid;
				$data['createtime']=date("Y-m-d H:i:s");
				$itemid=M("mod_teamwork_shop_product_item")->insert($data);
				//通知用户
				$product=M("mod_teamwork_shop_product")->selectRow("id=".$data['productid']);
				M("notice")->add(array(
					"userid"=>$userid,
					"content"=>"您的项目[{$product['title']}]有了新需求[{$data['title']}]",
					"linkurl"=>array(
						"path"=>"/index.php",
						"m"=>"teamwork_shop_product_item","a"=>"show","param"=>"id=".$itemid)
				));
			}
			$this->goall("保存成功");
		}
		
		public function onTest(){
			$id=get_post('id',"i");
			$userid=M("login")->userid;			
			$row=M("mod_teamwork_shop_product_item")->selectRow("id=".$id);
			$ut=M("mod_teamwork_shop_product_user")->selectRow("id=".$row['productid']." AND userid=".$userid);
			if($row['userid']!=$userid && !$ut ){
				$this->goAll("暂无权限",1);
			}
			if($row['status']!=1){
				$this->goAll("请先进行测试",1);
			}
			M("mod_teamwork_shop_product_item")->update(array("status"=>2),"id=$id");
			$this->goall("提交测试成功，继续加油",0);
		}
		
		public function onFinish(){
			$id=get_post('id',"i");
			$userid=M("login")->userid;			
			$row=M("mod_teamwork_shop_product_item")->selectRow("id=".$id);
			$ut=M("mod_teamwork_shop_product_user")->selectRow("id=".$row['productid']." AND userid=".$userid);
			if($row['userid']!=$userid && !$ut ){
				$this->goAll("暂无权限",1);
			}
			if($row['status']!=2){
				$this->goAll("请先进行测试",1);
			}
			M("mod_teamwork_shop_product_item")->update(array("status"=>3,"finishtime"=>date("Y-m-d H:i:s")),"id=$id");
			$this->goall("需求完成，继续加油",0);
		}
		
		public function onCancel(){
			$id=get_post('id',"i");
			$userid=M("login")->userid;
			$row=M("mod_teamwork_shop_product_item")->selectRow("id=".$id);
			if($row['userid']!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_teamwork_shop_product_item")->update(array("status"=>11),"id=$id");
			$this->goall("取消成功",0);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$userid=M("login")->userid;
			$row=M("mod_teamwork_shop_product_item")->selectRow("id=".$id);
			if($row['userid']!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_teamwork_shop_product_item")->update(array("status"=>99),"id=$id");
			$this->goall("删除成功",0);
		}
		
		
	}

?>