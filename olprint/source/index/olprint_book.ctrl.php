<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class olprint_bookControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=olprint_book&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_olprint_book")->select($option,$rscount);
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
			$this->smarty->display("olprint_book/index.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=olprint_book&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_olprint_book")->select($option,$rscount);
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
			$this->smarty->display("olprint_book/index.html");
		}
		public function onSearch(){
			$where=" status=1 AND siteid=".SITEID;
			$keyword=get("keyword","h");
			$where.=" AND title like '%".$keyword."%' ";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_olprint_book")->select($option,$rscount);
			 
			 
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			 
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"keyword"=>$keyword, 
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("olprint_book/search.html");
		}
		public function onShow(){
			$bookid=get_post("bookid","i");
			$userid=M("login")->userid;
			$data=M("mod_olprint_book")->selectRow(array("where"=>"bookid=".$bookid));
			if(empty($data)){
				$this->goAll("数据出错",1);
			}
			$list=M("mod_olprint_book")->select(array(
				"where"=>" status=1 AND isrecommend=1 ",
				"limit"=>12
			));
			//是否收藏
			$isfav=0;
			$fav=M("fav")->selectRow("tablename='mod_olprint_book' AND userid=".$userid." AND objectid=".$bookid);
			if($fav){
				$isfav=1;
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"list"=>$list,
				"isfav"=>$isfav
			));
			$this->smarty->display("olprint_book/show.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" status<4 AND userid=".$userid;
			 
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_olprint_book")->select($option,$rscount);
			 
			 
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
			$this->smarty->display("olprint_book/my.html");
		}
		public function onAdd(){
			M("login")->checkLogin();
			
			$bookid=get_post("bookid","i");
			$userid=M("login")->userid;
			if($bookid){
				$data=M("mod_olprint_book")->selectRow(array("where"=>"bookid=".$bookid));
				 
				if($data["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
			}
			$catlist=MM("olprint","olprint_category")->children(0);
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist
			));
			$this->smarty->display("olprint_book/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$bookid=get_post("bookid","i");
			$userid=M("login")->userid;
			$un=array(
				"status","isindex"
			);
			$data=M("mod_olprint_book")->postData($un);
			if($data["page_num"]<1){
				$this->goAll("页数需大于1",1);
			}
			if($data["money"]<1){
				$this->goAll("金额过小",1);
			}
			$data["status"]=0;
			if($bookid){
				$row=M("mod_olprint_book")->selectRow("bookid=".$bookid);
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_olprint_book")->update($data,"bookid='$bookid'");
			}else{
				
				$data["userid"]=$userid;
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_olprint_book")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		 
		
		public function onDelete(){
			M("login")->checkLogin();
			$bookid=get_post('bookid',"i");
			$userid=M("login")->userid;
			$status=11;
			$row=M("mod_olprint_book")->selectRow("bookid=".$bookid);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_olprint_book")->update(array("status"=>$status),"bookid=$bookid");
			$this->goall("删除成功");
		}
		public function onMyfav(){
			$this->smarty->display("olprint_book/myfav.html");
		}
		
	}

?>