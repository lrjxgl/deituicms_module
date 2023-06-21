<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_live_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/moduleadmin.php?m=zbtao_live_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_live_product")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_live_product/index.html");
		}
		
		
		public function onTag(){
			$tagid=get("tagid","i");
			$tag=M("mod_zbtao_tag")->selectRow("tagid=".$tagid." AND status=1 ");
			if(empty($tag)){
				$this->goAll("数据出错",1);
			}
			$start=get("per_page","i");
			$limit=12;
			$sql=" select p.productid,p.liveid,p.price,p.market_price,p.title,p.imgurl  
				from ".table('mod_zbtao_live_product_tag')." as t 
				left join ".table('mod_zbtao_live_product')." as p 
				on t.productid=p.productid 
				where t.tagid=".$tagid." AND p.status=1 
				limit $start,$limit 
			";
			$sql2=" select count(*) as ct
				from ".table('mod_zbtao_live_product_tag')." as t 
				left join ".table('mod_zbtao_live_product')." as p 
				on t.productid=p.productid 
				where t.tagid=".$tagid." AND p.status=1 
				 
			"; 
			$list=M("mod_zbtao_tag")->getAll($sql);
			if(!empty($list)){
				foreach($list as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$list[$k]=$v;
				}
			}
			$rscount=M("mod_zbtao_tag")->getOne($sql2);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			 
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
			 
					"rscount"=>$rscount,
					"tag"=>$tag
				)
			);
			$this->smarty->display("zbtao_live_product/tag.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/moduleadmin.php?m=zbtao_live_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_live_product")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_live_product/index.html");
		}
		
		public function onShow(){
			$productid=get_post("productid","i");
			$data=M("mod_zbtao_live_product")->selectRow(array("where"=>"productid=".$productid));
			//用户关注
			$data["isFollow"]=0;
			$userid=M("login")->userid;
			 
			if($userid){
				$rs=M("mod_zbtao_live_product_follow")->selectRow("userid=".$userid." AND productid=".$productid);
				if($rs){
					$data["isFollow"]=1;
				}else{
					$data["isFollow"]=0;
				}
				 
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zbtao_live_product/show.html");
		}
		public function onAdd(){
			$liveid=get("liveid","i");
			$live=M("mod_zbtao_live")->selectRow(array(
				"where"=>" liveid=".$liveid,
				"fields"=>"liveid,title"
			));
			$productid=get_post("productid","i");
			if($productid){
				$data=M("mod_zbtao_live_product")->selectRow(array("where"=>"productid=".$productid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"live"=>$live
			));
			$this->smarty->display("zbtao_live_product/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$pp=MM("zbtao","zbtao_pp")->getByUserid($userid);
			if(empty($pp)){
				$this->goAll("暂无权限",1);
			}
		 
			$liveid=post("liveid","i");
			$productid=get_post("productid","i");
			$data=M("mod_zbtao_live_product")->postData();
			$data["ppid"]=$pp["ppid"];
			if($productid){
				M("mod_zbtao_live_product")->update($data,"productid='$productid'");
			}else{
				M("mod_zbtao_live_product")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		 
		public function onDelete(){
			$productid=get_post('productid',"i");
			M("mod_zbtao_live_product")->update(array("status"=>11),"productid=$productid");
			$this->goAll("删除成功");
			 
		}
		
		public function onFollowToggle(){
			M("login")->checkLogin();
			$productid=get_post("productid","i");
			$userid=M("login")->userid;
			$rs=M("mod_zbtao_live_product_follow")->selectRow("userid=".$userid." AND productid=".$productid);
			$isFollow=0;
			if($rs){
				M("mod_zbtao_live_product_follow")->delete("userid=".$userid." AND productid=".$productid);
				M("mod_zbtao_live_product")->changenum("followed_num",-1,"productid=".$productid);
			}else{
				M("mod_zbtao_live_product_follow")->insert(array(
					"userid"=>$userid,
					"productid"=>$productid
				));
				$isFollow=1;
				M("mod_zbtao_live_product")->changenum("followed_num",1,"productid=".$productid);
			}
			$this->goAll("success",0,array(
				"isFollow"=>$isFollow
			));
		}
		
		public function onMyFollow(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$ops=array(
				"where"=>" userid=".$userid,
				"fields"=>"productid"
			);
			$productids=M("mod_zbtao_live_product_follow")->selectCols($ops);
			$list=[];
			if(!empty($productids)){
				$list=MM("zbtao","zbtao_live_product")->Dselect(array(
					"where"=>" productid in("._implode($productids).") AND status=1 "
				));
			}
			
			$this->smarty->goAssign(array(
				"list"=>$list
			));
			$this->smarty->display("zbtao_live_product/myfollow.html");
		}
		
		
		public function onAdmin(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$pp=MM("zbtao","zbtao_pp")->getByUserid($userid);
			if(empty($pp)){
				$this->goAll("暂无权限",1);
			}
			$ppid=$pp["ppid"];
			$liveid=get("liveid","i");
			$live=M("mod_zbtao_live")->selectRow(array(
				"where"=>"liveid=".$liveid,
				"fields"=>"liveid,title"
			));
			$where="liveid=".$liveid." AND status in(0,1) ";
			$url="/moduleadmin.php?m=zbtao_live_product&a=admin";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_live_product")->Dselect($option,$rscount);
			 
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
					"live"=>$live
				)
			);
			$this->smarty->display("zbtao_live_product/admin.html");
		}
		
		public function onStatus(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$pp=MM("zbtao","zbtao_pp")->getByUserid($userid);
			if(empty($pp)){
				$this->goAll("暂无权限",1);
			}
			$ppid=$pp["ppid"];
			$productid=get("productid","i");
			$pro=MM("zbtao","zbtao_live_product")->selectRow("productid=".$productid);
			if($pro["status"]==1){
				$status=2;
				$status_name="已下架";
			}else{
				$status=1;
				$status_name="已上架";
			}
			MM("zbtao","zbtao_live_product")->update(array(
				"status"=>$status
			),"productid=".$productid);
			$this->goAll("success",0,array(
				"status"=>$status,
				"status_name"=>$status_name
			));
		}
		
	}

?>