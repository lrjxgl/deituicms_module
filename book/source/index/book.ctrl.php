<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class bookControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=2 AND isprivate=0 ";
			$url="/module.php?m=book";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("book","book")->Dselect($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$data[$k]=$v;
				}
			}
			$reclist=MM("book","book")->Dselect(array(
				"where"=>" isrecommend=1 AND status=2 AND isprivate=0",
				"limit"=>24
			));
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
					"reclist"=>$reclist
				)
			);
			$this->smarty->display("book/index.html");
		}
		
		public function onShow(){
			$bookid=get_post("bookid","i");
			$data=M("mod_book")->selectRow(array("where"=>"bookid={$bookid}"));
			if(empty($data)){
				$this->goAll("数据出错");
			}
			
			$userid=M("login")->userid;
			$isbuy=1;
			if($data['ispay']){
				$isbuy=0;
				$order=M("mod_book_order")->selectRow("bookid=".$bookid." AND isdelete=0 AND userid=".$userid);
				if($order){
					$isbuy=1;
				}
			}
			//是否收藏
			$isfav=0;
			$fav=M("fav")->selectRow("tablename='mod_book' AND userid=".$userid." AND objectid=".$bookid);
			if($fav){
				$isfav=1;
			}
			if($data['isprivate'] && !$isbuy){
				$this->goAll("该书不公开",1);
			}
			$reclist=MM("book","book")->Dselect(array(
				"where"=>" status=2 AND  isrecommend=1 AND isprivate=0 ",
				"limit"=>24
			)); 
			$seo=array(
				"title"=>$data["title"],
				"description"=>$data["description"]
			);
			$this->smarty->assign(
				array("seo"=>$seo)
			);
			$this->smarty->goassign(array(
				"data"=>$data,
				"isbuy"=>$isbuy,
				"reclist"=>$reclist,
				"isfav"=>$isfav
			));
			$this->smarty->display("book/show.html");
		}
		
		 
		public function onView(){
			$bookid=get_post("bookid","i");
			if($bookid){
				$data=M("mod_book")->selectRow(array("where"=>"bookid={$bookid}"));				
			}
			
			$userid=M("login")->userid;
			if($data['ispay'] || $data['isprivate']){
				$order=M("mod_book_order")->selectRow("bookid=".$bookid." AND isdelete=0 AND userid=".$userid);
				if(empty($order)){
					$this->goAll("请先购买图书",1);
				}
				
			}
			if($data['isprivate'] && !$order){
				$this->goAll("该书不公开",1);
			} 
			$artlist=MM("book","book_article")->getFamily($bookid);
			 
			$this->smarty->goassign(array(
				"book"=>$data,
				"artlist"=>$artlist,
				 
			));
			$this->smarty->display("book/view.html");
			
		}
		public function onViewContent(){
			$bookid=get_post("bookid","i");
			if($bookid){
				$data=M("mod_book")->selectRow(array("where"=>"bookid={$bookid}"));				
			}
			 
			 
			$this->smarty->goassign(array(
				"book"=>$data,
				 
				 
			));
			$this->smarty->display("book/view_content.html");
		}
		
		public function onMy(){
			M("login")->checkLogin(true);
			$userid=M("login")->userid;
			$where="  userid=".$userid." AND in(0,1,2) ";
			$url="/module.php?m=book&a=my";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("book","book")->Dselect($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
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
			$this->smarty->display("book/my.html");
		}
		
	}

?>