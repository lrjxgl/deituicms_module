<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_bookControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			
			
			$type=get("type","h");
			$url="/moduleadmin.php?m=gread_book&type=".$type;
			switch($type){
				case "new":
					$where=" status=0 ";
					break;
				default:
					$where=" status in(0,1,2)";
					break;
			}
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%'";
			}
			$catid=get("catid","i");
			if($catid){
				$where.=" AND catid=".$catid;
				$url.="&catid=".$catid;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gread_book")->select($option,$rscount);
			$catlist=MM("gread","gread_book_category")->getList(0);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$v["catid_name"]=$catlist[$v["catid"]]["title"];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$catList=M("mod_gread_book_category")->select(array(
				"where"=>"status=1 AND pid=0",
				"order"=>"orderindex ASC"
			));
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"catList"=>$catList
				)
			);
			$this->smarty->display("gread_book/index.html");
		}
		
		public function onAdd(){
			$bookid=get_post("bookid","i");
			if($bookid){
				$data=M("mod_gread_book")->selectRow(array("where"=>"bookid={$bookid}"));			
			}
			$rss=M("mod_gread_book_category")->select($option,$rscount);
			$catlist=$child=array();
			if($rss){
				foreach($rss as $rs){
					if($rs["pid"]==0){
						$catlist[]=$rs;
					}else{
						$child[$rs["pid"]][]=$rs;
					}
				}
				foreach($catlist as $k=>$v){
					$v["child"]=$child[$v["catid"]];
					$catlist[$k]=$v;
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist
			));
			$this->smarty->display("gread_book/add.html");
		}
		
		public function onSave(){
			$bookid=get_post("bookid","i");
			$data=M("mod_gread_book")->postData();
			if($bookid){
				M("mod_gread_book")->update($data,"bookid='$bookid'");
			}else{
				M("mod_gread_book")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$bookid=get_post('bookid',"i");
			$row=M("mod_gread_book")->selectRow("bookid=".$bookid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_gread_book")->update(array("status"=>$status),"bookid=$bookid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onRecommend(){
			$bookid=get_post('bookid',"i");
			$row=M("mod_gread_book")->selectRow("bookid=".$bookid);
			$isrecommend=1;
			if($row["recommend"]==1){
				$isrecommend=2;
			}
			M("mod_gread_book")->update(array("isrecommend"=>$isrecommend),"bookid=$bookid");
			$this->goall("状态修改成功",0,$isrecommend);
		}
		
		public function onDelete(){
			$bookid=get_post('bookid',"i");
			M("mod_gread_book")->update(array("bstatus"=>11),"bookid=$bookid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>