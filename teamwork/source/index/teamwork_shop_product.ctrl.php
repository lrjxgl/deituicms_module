<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class teamwork_shop_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			$where=" status in(0,1,2)  ";
			$url="/index.php?m=teamwork_shop_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_teamwork_shop_product")->select($option,$rscount);
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
				)
			);
			$this->smarty->display("teamwork_shop_product/index.html");
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" status in(0,1,2) AND userid=".$userid;
			$url="/index.php?m=teamwork_shop_product&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_teamwork_shop_product")->select($option,$rscount);
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
			$this->smarty->display("teamwork_shop_product/my.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$userid=M("login")->userid;
			M("mod_teamwork_shop_product")->checkAccess($id,$userid);
			if($id){
				$data=M("mod_teamwork_shop_product")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"product"=>$data
			));
			$this->smarty->display("teamwork_shop_product/show.html");
		}
		
		public function onHome(){
			$id=get_post("id","i");	
			$userid=M("login")->userid;		
			M("mod_teamwork_shop_product")->checkAccess($id,$userid);
			$product=M("mod_teamwork_shop_product")->selectRow(array("where"=>"id={$id}"));	
			$where=" productid=".$id." AND status<4";
			$order=" id DESC";
			if(isset($_GET['status'])){
				$where.=" AND status=".max(0,get("status","i"));
				if($_GET['status']==3){
					$order=" finishtime DESC";
				}
			}
			$rscount=true;			 
			$itemList=M("mod_teamwork_shop_product_item")->Dselect(array(
				"where"=>$where,
				"order"=>$order,
				"limit"=>100
			),$rscount);
			 
			$isadmin=0;
			if($userid==$product['userid']){
				$isadmin=1;
			}  
			$this->smarty->goassign(array(
				"product"=>$product,
				"itemList"=>$itemList,
				"isadmin"=>$isadmin,
				"rscount"=>$rscount
			));
			$this->smarty->display("teamwork_shop_product/home.html");
		}
		
		
		public function onTeam(){
			 
			$id=get_post("id","i");			 
			$product=M("mod_teamwork_shop_product")->selectRow(array("where"=>"id={$id}"));	
			$userid=M("login")->userid;	
			M("mod_teamwork_shop_product")->checkAccess($id,$userid);
			$sql="select u.nickname,u.user_head,u.userid,m.roleid,m.id 
				from ".table('mod_teamwork_shop_product_user')." as m 
				left join ".table('user')." as u 
				on m.userid=u.userid 
				where m.productid={$id}	
			";
			$userList=M("mod_teamwork_shop_product")->getAll($sql);
			 
			$roleList=M("mod_teamwork_shop_product_user")->roleList();
			if($userList){
				foreach($userList as $k=>$v){
					$v['role_name']=$roleList[$v['roleid']];
					$userList[$k]=$v;
				}
			}
			$isadmin=0;
			if($userid==$product['userid']){
				$isadmin=1;
			} 
			$this->smarty->goassign(array(
				"product"=>$product,
				"userList"=>$userList,
				"isadmin"=>$isadmin
			));
			$this->smarty->display("teamwork_shop_product/team.html");
		}
		
		public function onLog(){
			$id=get_post("id","i");	
			$userid=M("login")->userid;
			M("mod_teamwork_shop_product")->checkAccess($id,$userid);
			$product=M("mod_teamwork_shop_product")->selectRow(array("where"=>"id={$id}"));	
			$where=	" productid=".$id;
			$userid=get("userid","i");
			if($userid){
				$where.=" AND userid=".$userid;
			}
			$logList=M("mod_teamwork_shop_product_item_log")->Dselect(array(
				"where"=>$where,
				"order"=>"id DESC",
				"limit"=>48
			));
			$sql="select u.nickname,u.user_head,u.userid,m.roleid 
				from ".table('mod_teamwork_shop_product_user')." as m 
				left join ".table('user')." as u 
				on m.userid=u.userid 
				where m.productid={$id}	
			";
			$userList=M("mod_teamwork_shop_product")->getAll($sql);
			 
			$roleList=M("mod_teamwork_shop_product_user")->roleList();
			if($userList){
				foreach($userList as $k=>$v){
					$v['role_name']=$roleList[$v['roleid']];
					$userList[$k]=$v;
				}
				
			}
			if($logList){
				foreach($logList as $v){
					$itemids[]=$v['itemid'];
				}
				 
				$items=M("mod_teamwork_shop_product_item")->getListByIds($itemids);
				 
				foreach($logList as $k=>$v){
					$v['item']=$items[$v['itemid']];
					$logList[$k]=$v;
				}
			} 
			$this->smarty->goassign(array(
				"userList"=>$userList,
				"product"=>$product,
				"logList"=>$logList
			));
			$this->smarty->display("teamwork_shop_product/log.html");
		} 
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_teamwork_shop_product")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("teamwork_shop_product/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_teamwork_shop_product")->postData();
			$userid=M("login")->userid;
			if(empty($data['title'])){
				$this->goAll("请填写主题",1);
			}
			if($id){
				$row=M("mod_teamwork_shop_product")->selectRow("id=".$id);
				if($row['userid']!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_teamwork_shop_product")->update($data,"id='$id'");
			}else{
				$data['userid']=$userid;
				$data['createtime']=date("Y-m-d H:i:s");
				$productid=M("mod_teamwork_shop_product")->insert($data);
				//插入成员
				M("mod_teamwork_shop_product_user")->insert(array(
					"productid"=>$productid,
					"userid"=>$userid,
					"roleid"=>2,
					"createtime"=>date("Y-m-d H:i:s")
				));
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			$userid=M("login")->userid;
			$row=M("mod_teamwork_shop_product")->selectRow("id=".$id);
			if($row['userid']!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_teamwork_shop_product")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0);
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