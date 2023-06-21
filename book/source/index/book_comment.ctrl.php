<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class book_commentControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			
			$bookid=get('bookid','i');
			$articleid=get('articleid','i');
			$where=" articleid=".$articleid;
			$url="/module.php?m=book_comment&articleid=".$articleid;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_book_comment")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];
					
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v['user_head']=$us[$v['userid']]['user_head'];
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['timeago']=timeago(strtotime($v['createtime']));
					$data[$k]=$v;
				}
			}
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
			$this->smarty->display("book_comment/index.html");
		}
		
		
		public function onMy(){
			
			$bookid=get('bookid','i');
			$articleid=get('articleid','i');
			$where=" articleid=".$articleid;
			$url="/module.php?m=book_comment&articleid=".$articleid;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_book_comment")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];
					
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v['user_head']=$us[$v['userid']]['user_head'];
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['timeago']=timeago(strtotime($v['createtime']));
					$data[$k]=$v;
				}
			}
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
			$this->smarty->display("book_comment/my.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_book_comment")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("book_comment/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");

			$data=M("mod_book_comment")->postData();
			$data['ip']=ip();
			$data['ip_addr']=ipcity($data['ip'],1);
			$data['createtime']=date("Y-m-d H:i:s");
			if($id){
				$row=M("mod_book_comment")->selectRow("id=".$id);
				if($row['userid']!=$userid){
					$this->goAll("保存失败",1);
				}
				M("mod_book_comment")->update($data,"id='$id'");
			}else{
				$data['userid']=$userid;
				M("mod_book_comment")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_book_comment")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_book_comment")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>