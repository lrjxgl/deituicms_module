<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class teamwork_shop_product_item_userControl extends skymvc{
		
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
			$sql="select p.title,p.id,p.productid,p.createtime,p.status,p.typeid from ".table('mod_teamwork_shop_product_item_user')." as u 
				left join ".table('mod_teamwork_shop_product_item')." as p
				on u.itemid=p.id
				where u.userid={$userid}
				
			";
		 
			if(isset($_GET['status'])){
				$sql.=" AND p.status=".max(0,get("status","i"));
			}else{
				$sql.=" AND p.status<4"; 
			}
			$sql.=" order by p.id DESC limit 48 ";	 
			$data=M("mod_teamwork_shop_product_item")->getAll($sql);
			$statusList=MM("teamwork","teamwork_shop_product_item")->statusList();
			$typeList==MM("teamwork","teamwork_shop_product_item")->typeList();
			if($data){
				foreach($data as $v){
					$proids[]=$v['productid'];
				}
				$pros==MM("teamwork","teamwork_shop_product")->getListByIds($proids);
				foreach($data as $k=>$v){
					$v['product']=$pros[$v['productid']];
					$v['type_name']=$typeList[$v['typeid']];
					 $v['timeago']=timeago(strtotime($v['createtime']));
					 $v['status_name']=$statusList[$v['status']];
					$data[$k]=$v;
				}
			}
			 
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"statusList"=>$statusList, 
					"per_page"=>0,
				)
			);
			$this->smarty->display("teamwork_shop_product_item_user/my.html");
		} 
		
		public function onAdd(){
			$itemid=get("itemid",'i');
			$item==MM("teamwork","teamwork_shop_product_item")->selectRow("id=".$itemid);
			if(empty($item)){
				$this->goAll("需求数据出错",1);
			}
			$sql="select u.nickname,u.user_head,u.userid,m.roleid 
				from ".table('mod_teamwork_shop_product_user')." as m 
				left join ".table('user')." as u 
				on m.userid=u.userid 
				where m.productid={$item['productid']}	
			";
		 
			$userList==MM("teamwork","teamwork_shop_product")->getAll($sql);
			 
			$roleList==MM("teamwork","teamwork_shop_product_user")->roleList();
			if($userList){
				foreach($userList as $k=>$v){
					$v['role_name']=$roleList[$v['roleid']];
					$userList[$k]=$v;
				}
			}
			
			$this->smarty->goAssign(array(
				"item"=>$item,
				"userList"=>$userList
			));
			
			$this->smarty->display("teamwork_shop_product_item_user/add.html");
		} 
		
		public function onSave(){
			$id=get_post("id","i");
			
			$data=M("mod_teamwork_shop_product_item_user")->postData();
			if(empty($data['itemid'])){
				$this->goAll("请选择项目",1);
			}
			$item=M("mod_teamwork_shop_product_item")->selectRow("id=".$data['itemid']);
			if(empty($item)){
				$this->goAll("需求数据出错",1);
			}
			$uids=post("uids","i");
			if(empty($uids)){
				$this->goAll("请选择用户",1);
			} 
			M("mod_teamwork_shop_product_item")->update(array(
				"status"=>1
			),"id=".$data['itemid']);
			foreach($uids as $userid){
				$row=M("mod_teamwork_shop_product_item_user")->selectRow("itemid={$data['itemid']} AND userid={$userid}");
				if($row){
					continue;
				}
				$data['userid']=$userid;
				$data['productid']=$item['productid']; 
				$data['createtime']=date("Y-m-d H:i:s");
				M("mod_teamwork_shop_product_item_user")->insert($data);
				//通知用户
				M("notice")->add(array(
					"userid"=>$userid,
					"content"=>"你有一项新需求要去处理，[{$item['title']}]",
					"linkurl"=>array(
						"path"=>"/index.php",
						"m"=>"teamwork_shop_product_item","a"=>"show","param"=>"id=".$item['id'])
				));
			}
			$this->goall("保存成功");
		}
		public function onDelete(){
			$id=get_post('id',"i");
			$userid=M("login")->userid;
			$row=M("mod_teamwork_shop_product")->selectRow("id=".$id);
			if($row['userid']!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_teamwork_shop_product")->update(array("status"=>99),"id=$id");
			$this->goall("删除成功",0);
		}
		
		
	}

?>