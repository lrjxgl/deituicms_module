<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class teamwork_shop_product_userControl extends skymvc{
		
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
			$sql="select p.title,p.id,p.createtime from ".table('mod_teamwork_shop_product_user')." as u 
				left join ".table('mod_teamwork_shop_product')." as p
				on u.productid=p.id
				where p.status in(0,1,2) AND u.userid={$userid}
			";
			$data=M("mod_teamwork_shop_product")->getAll($sql);
			if($data){
				foreach($data as $k=>$v){
					 $v['timeago']=timeago(strtotime($v['createtime']));
					$data[$k]=$v;
				}
			}
			 
			$this->smarty->goassign(
				array(
					"list"=>$data,
					 
					"per_page"=>0,
				)
			);
			$this->smarty->display("teamwork_shop_product_user/my.html");
		} 
		
		public function onAdd(){
			$productid=get("productid",'i');
			$roleList=M("mod_teamwork_shop_product_user")->roleList();
			$id=get("id",'i');
			if($id){
				$sql="select u.nickname,u.user_head,u.userid,m.roleid,m.id 
					from ".table('mod_teamwork_shop_product_user')." as m 
					left join ".table('user')." as u 
					on m.userid=u.userid 
					where m.id={$id}	
				";
				$puser=M("mod_teamwork_shop_product_user")->getRow($sql);
			}
			$this->smarty->goAssign(array(
				"productid"=>$productid,
				"roleList"=>$roleList,
				"puser"=>$puser
			));
			$this->smarty->display("teamwork_shop_product_user/add.html");
		}
		 
		public function onSave(){
			$id=get_post("id","i");
			
			$data=M("mod_teamwork_shop_product_user")->postData();
			if(empty($data['productid'])){
				$this->goAll("请选择项目",1);
			}
			$nickname=post("nickname",'h');
			$user=M("user")->selectRow("nickname='".$nickname."'");
			/*
			$telephone=post("telephone","h");
			$user=M("user")->selectRow("telephone='".$telephone."'");
			*/
			if(empty($user)){
				$this->goAll("查无此人，请确认昵称",1);
			}
			$row=M("mod_teamwork_shop_product_user")->selectRow("productid={$data['productid']} AND userid={$user['userid']}");
			if($id){
				if($row['id']!=$id){
					$this->goAll("数据出错",1);
					
				}
				M("mod_teamwork_shop_product_user")->update($data,"id=".$id);
			}else{
				if($row){
					$this->goAll("该用户已经加入了",1);
				}
				$data['userid']=$user['userid'];
				$data['createtime']=date("Y-m-d H:i:s");
				M("mod_teamwork_shop_product_user")->insert($data);
			}
			
			 
			$this->goall("保存成功");
		}
		public function onDelete(){
			$id=get_post('id',"i");
			$productid=get_post("productid","i");
			$userid=M("login")->userid;
			$product=M("mod_teamwork_shop_product")->selectRow("id=".$productid);
			if(empty($product)){
				$this->goAll("项目出错",1);
			}
			
			$row=M("mod_teamwork_shop_product_user")->selectRow("id=".$id);
			if($product['userid']==$row['userid']){
				$this->goAll("创始人不能删除",1);
			}
			M("mod_teamwork_shop_product_user")->delete("id=".$row['id']);
			$this->goall("删除成功",0);
		}
		
		
	}

?>