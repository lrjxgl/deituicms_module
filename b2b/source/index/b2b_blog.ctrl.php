<?php
class b2b_blogControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$this->smarty->display("b2b_blog/index.html");
	}
	
	public function onList(){
		$start=get("per_page","i");
		$limit=24;
		$type=get("type","h");
		$order=" id DESC";
		switch($type){
			case "new":
				$where=" 1 ";
				break;
			case "hot":
				$where=" createtime>'".date("Y-m-d H:i:s",strtotime("-10 day"))."' ";
				$order="comment_num DESC";
				break;
			default:
				$where=" isrecommend=1 ";
				break;
		}
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("b2b","b2b_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
		));
	}
	
	public function onShow(){
		$id=get('id','i');
		$userid=M("login")->userid;
		$data=MM("b2b","b2b_blog")->selectRow("id=".$id);
		if(!$data) $this->goAll("数据出错",1);
		//浏览记录
		if($userid){
			$view=M("mod_b2b_blog_view")->selectRow("userid=".$userid." AND objectid=".$id);
			if(!$view){
				M("mod_b2b_blog_view")->insert(array(
					"userid"=>$userid,
					"objectid"=>$id,
					"createtime"=>date("Y-m-d H:i:s")
				));
				MM("b2b","b2b_blog")->update(array(
					"view_num"=>$data["view_num"]+1
				),"id=".$id);
			}
		}
		
		
		$data["timeago"]=timeago(strtotime($data["createtime"]));
		$author=M("user")->getUser($data['userid'],"userid,nickname,user_head,follow_num,followed_num");
		$author['user_head']=images_site($author['user_head']);
		//关注
		if($userid){
			$author["isFollow"]=0;
			$isFollow=M("follow")->selectRow(array("where"=>"t_userid=".$author["userid"]." AND userid=".$userid."   "));
			if($isFollow){
				$author["isFollow"]=1;
			}
		}
		//图集
		$imgslist=array();
		if($data['imgsdata']){
			$imgs=explode(",",$data['imgsdata']);
			foreach($imgs as $img){
				$imgslist[]=images_site($img);
			}			  
		}
		//是否点赞
		$islove=0;
		$love=M("love")->selectRow("tablename='mod_b2b_blog' AND userid=".$userid." AND objectid=".$id);
		if($love){
			$islove=1;
		}
		//是否收藏
		$isfav=0;
		if($userid){
			$fav=M("fav")->selectRow("tablename='mod_b2b_blog' AND userid=".$userid." AND objectid=".$id);
			if($fav){
				$isfav=1;
			}
		}
		$this->smarty->goAssign(array(
			"islove"=>$islove,
			"isfav"=>$isfav,
			"data"=>$data,
			"comment_objectid"=>$id,
			"comment_tablename"=>"mod_b2b_blog",
			"comment_f_userid"=>$data['userid'],
			"imgslist"=>$imgslist,
			"author"=>$author,
			"userid"=>$userid
		));
		$this->smarty->display("b2b_blog/show.html");
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=24;
		$where=" userid=".$userid;
		$ops=array(
			"where"=>$where,
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("b2b","b2b_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
		));
		$this->smarty->display("b2b_blog/my.html");
	}
	
	public function onAdd(){
		M("login")->checkLogin();
		$this->smarty->display("b2b_blog/add.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$data["content"]=post("content","h");
		$imgsdata=post("imgsdata","h");
		if($imgsdata){
			$ims=explode(",",$imgsdata);
			foreach($ims as $im){
				if($im!="undefined" && $im!=""){
					$imgs[]=$im;
				}
			}
			if(!empty($imgs)){
				$data["imgurl"]=$imgs[0];
				$data["imgsdata"]=implode(",",$imgs);
			}	
		}
		$data["userid"]=$userid;
		$data["createtime"]=date("Y-m-d H:i:s");
		M("mod_b2b_blog")->insert($data);
		$this->goAll("发布成功");
	}
	
	public function onDelete(){
		 
		$userid=M("login")->userid;
		$id=get("id","i");
		$row=M("mod_b2b_blog")->selectRow("id=".$id);
		if($row["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		M("mod_b2b_blog")->update(array("status"=>11),"id=".$id);
		$this->goAll("删除成功");
	}
	
}