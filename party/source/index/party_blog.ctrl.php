<?php
class party_blogControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$this->smarty->display("party_blog/index.html");
	}
	
	public function onList(){
		$start=get("per_page","i");
		$limit=24;
		$type=get("type","h");
		$order=" id DESC";
		switch($type){
			case "new":
				$where=" status in(0,1) ";
				break;
			case "hot":
				//AND createtime>'".date("Y-m-d H:i:s",strtotime("-10 day"))."'
				$where=" status=1   ";
				$order="comment_num DESC";
				break;
			default:
				$where=" status=1  AND isrecommend=1 ";
				break;
		}
		$partyid=get("partyid","i");
		if($partyid){
			$where.=" AND partyid=".$partyid;
		}
		
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("party","party_blog")->Dselect($ops,$rscount);
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
		$data=MM("party","party_blog")->selectRow("id=".$id." AND status in(0,1) ");
		if(!$data) $this->goAll("数据出错",1);
		$data["mp4url"]=images_site($data["mp4url"]);
		//浏览记录
		if($userid){
			$view=M("mod_party_blog_view")->selectRow("userid=".$userid." AND partyid=".$id);
			if(!$view){
				M("mod_party_blog_view")->insert(array(
					"userid"=>$userid,
					"blogid"=>$id,
					"partyid"=>$blog["partyid"],
					"createtime"=>date("Y-m-d H:i:s")
				));
				MM("party","party_blog")->update(array(
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
		$love=M("love")->selectRow("tablename='mod_party_blog' AND userid=".$userid." AND objectid=".$id);
		if($love){
			$islove=1;
		}
		//是否收藏
		$isfav=0;
		if($userid){
			$fav=M("fav")->selectRow("tablename='mod_party_blog' AND userid=".$userid." AND objectid=".$id);
			if($fav){
				$isfav=1;
			}
		}
		//
		$party=M("mod_party")->selectRow(array(
			"where"=>"id=".$data["partyid"],
		));
		$this->smarty->goAssign(array(
			"islove"=>$islove,
			"isfav"=>$isfav,
			"data"=>$data,
			"comment_objectid"=>$id,
			"comment_tablename"=>"mod_party_blog",
			"comment_f_userid"=>$data['userid'],
			"imgslist"=>$imgslist,
			"author"=>$author,
			"userid"=>$userid,
			"party"=>$party
		));
		$this->smarty->display("party_blog/show.html");
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
		$list=MM("party","party_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
		));
		$this->smarty->display("party_blog/my.html");
	}
	
	public function onAdd(){
		M("login")->checkLogin();
		$partyid=get("partyid","i");
		$this->smarty->goAssign(array(
			"partyid"=>$partyid
		));
		$this->smarty->display("party_blog/add.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$data["content"]=post("content","h");
		$imgsdata=post("imgsdata","h");
		if($imgsdata){
			$ims=explode(",",$imgsdata);
			foreach($ims as $im){
				if(substr($im,0,6)=="attach"){
					$imgs[]=$im;
				}
			}
			if(!empty($imgs)){
				$data["imgurl"]=$imgs[0];
				$data["imgsdata"]=implode(",",$imgs);
			}	
		}
		$mp4url=post("mp4url","h");
		if($mp4url!='' && substr($mp4url,0,5)=="video"){
			$data["mp4url"]=post("mp4url","h");
		}
		
		$data["partyid"]=post("partyid","i");
		$data["userid"]=$userid;
		$data["createtime"]=date("Y-m-d H:i:s");
		M("mod_party_blog")->insert($data);
		$this->goAll("发布成功",0,$mp4url);
	}
	
	public function onDelete(){
		 
		$userid=M("login")->userid;
		$id=get("id","i");
		$row=M("mod_party_blog")->selectRow("id=".$id);
		if($row["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		M("mod_party_blog")->update(array("status"=>11),"id=".$id);
		$this->goAll("删除成功");
	}
	
}