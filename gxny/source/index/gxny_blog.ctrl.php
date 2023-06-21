<?php
class gxny_blogControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("gxny_blog/index.html");
	}
	
	public function onList(){
		
		$shopid=MM("gxny","gxny_shop")->inShopid();
		 
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=6;
		$type=get("type","h");
		$order=" id DESC";
		switch($type){
			case "all":
			case "new":
				$where=" status=1 ";
				break;
			case "hot":
				$where="status=1 AND createtime>'".date("Y-m-d H:i:s",strtotime("-10 day"))."' ";
				$order="comment_num DESC";
				break;
			case "recommend":
				$where=" status=1 AND isrecommend=1 ";
				break;
		 
			default:
				$where="status=1   ";
				
				break;
		}
		$where .=" AND shopid=".$shopid;
		$productid=get("productid","i");
		if($productid){
			$where.=" AND productid=".$productid;
		}
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("gxny","gxny_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$topicList=M("mod_gxny_topic")->select(array(
			"where"=>" isindex=1 AND status=1 "
		));
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"topicList"=>$topicList
		));
	}
	
	public function onShow(){
		$id=get('id','i');
		$userid=M("login")->userid;
		$data=MM("gxny","gxny_blog")->selectRow("id=".$id." AND status in(0,1)");
		if(!$data ) $this->goAll("数据出错",1);
		//浏览记录
		if($userid){
			$view=M("mod_gxny_blog_view")->selectRow("userid=".$userid." AND objectid=".$id);
			if(!$view){
				M("mod_gxny_blog_view")->insert(array(
					"userid"=>$userid,
					"objectid"=>$id,
					"createtime"=>date("Y-m-d H:i:s")
				));
				MM("gxny","gxny_blog")->update(array(
					"view_num"=>$data["view_num"]+1
				),"id=".$id);
			}
		}
		$data["parsecontent"]=MM("gxny","gxny_blog")->parseContent($data["content"]);
		
		$data["timeago"]=timeago(strtotime($data["createtime"]));
		$author=M("user")->getUser($data['userid'],"userid,nickname,user_head,follow_num,followed_num");
		if(empty($author)){
			$author["userid"]=0;
		}
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
		//视频
		$data["mp4url"]=images_site($data["mp4url"]);
		//是否点赞
		$islove=0;
		$love=M("love")->selectRow("tablename='mod_gxny_blog' AND userid=".$userid." AND objectid=".$id);
		if($love){
			$islove=1;
		}
		//是否收藏
		$isfav=0;
		if($userid){
			$fav=M("fav")->selectRow("tablename='mod_gxny_blog' AND userid=".$userid." AND objectid=".$id);
			if($fav){
				$isfav=1;
			}
		}
		//菜园基地
		$shop=[];
		$product=[];
		if($data["shopid"]){
			$shop=MM("gxny","gxny_shop")->selectRow("shopid=".$data["shopid"]);
		}
		if($data["productid"]){
			$product=MM("gxny","gxny_shop_product")->selectRow("id=".$data["productid"]);
		} 
		$this->smarty->goAssign(array(
			"isadmin"=>$isadmin,
			"islove"=>$islove,
			"isfav"=>$isfav,
			"data"=>$data,
			"comment_objectid"=>$id,
			"comment_tablename"=>"mod_gxny_blog",
			"comment_f_userid"=>$data['userid'],
			"imgslist"=>$imgslist,
			"author"=>$author,
			"userid"=>$userid,
			"shop"=>$shop,
			"product"=>$product
		));
		$this->smarty->display("gxny_blog/show.html");
	}
	
	public function onFollow(){
		$start=get("per_page","i");
		$limit=6;
		$type=get("type","h");
		$order=" id DESC";
		$userid=M("login")->userid;
		$rscount=true;
		$blogids=M("mod_gxny_blog_feeds")->selectCols(array(
			"where"=>" userid=".$userid,
			"fields"=>"blogid",
			"order"=>" dateline DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		if(!$blogids){
			$blogids=[0]; 
		} 
		$ops=array(
			"where"=>" status=1 AND  id in("._implode($blogids).") ",
			"order"=>"  id DESC"
		);
		
		$list=MM("gxny","gxny_blog")->Dselect($ops);
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
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=24;
		$where=" status in(0,1,2) AND userid=".$userid;
		$ops=array(
			"where"=>$where,
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("gxny","gxny_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
		));
		$this->smarty->display("gxny_blog/my.html");
	}
	
	public function onAdd(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
	 
		$uAdmin=M("mod_gxny_useradmin")->selectRow("userid=".$userid);
		 
		if(!empty($uAdmin)){
			$proList=M("mod_gxny_shop_product")->select(array(
				"where"=>" status=1 AND isused=1 AND shopid=".$uAdmin["shopid"]
			)); 
			 
		}else{
			$proList=M("mod_gxny_shop_product")->select(array(
				"where"=>" status=1 AND isused=1 AND userid=".$userid
			)); 
		}
		
		$productid=get("productid","i");
		$this->smarty->goassign(array(
			"a"=>1,
			"proList"=>$proList,
			"productid"=>$productid
		));
		$this->smarty->display("gxny_blog/add.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		 
		$data=M("mod_gxny_blog")->postData();
		$data["content"]=$content=post("content","h");
		//商品信息
		$pro=[];
		if($data["productid"]>0){
			$pro=M("mod_gxny_shop_product")->selectRow("id=".$data["productid"]);
			$data["shopid"]=$pro["shopid"];
			$data["orderid"]=$pro["orderid"];
		}
		
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
		$addr=ipCity(ip());
		if(!empty($addr["city"])){
			$city=$addr["city"];
		}else{
			$city="";
		}
		$data["city"]=sql($city);
		$data["status"]=1;
		$data["userid"]=$userid;
		$data["createtime"]=date("Y-m-d H:i:s");
		$blogid=M("mod_gxny_blog")->insert($data);
		//#订阅
		if(!empty($pro) && $pro["userid"]>0){
			M("mod_gxny_blog_feeds")->insert(array(
				"userid"=>$pro["userid"],
				"blogid"=>$blogid,
		 
				"dateline"=>time(),
			));
		}
		
		
		$this->goAll("发布成功");
	}
	
	public function onDelete(){
		 
		$userid=M("login")->userid;
		$id=get("id","i");
		$row=M("mod_gxny_blog")->selectRow("id=".$id);
		if($row["userid"]!=$userid ){
			$this->goAll("暂无权限",1);
		}
		M("mod_gxny_blog")->update(array("status"=>11),"id=".$id);
		//删除所有关注的
		M("mod_gxny_feeds")->delete("blogid=".$id);
		M("mod_gxny_topic_index")->delete("blogid=".$id);
		$this->goAll("删除成功");
	}
	
	public function onRecommend(){
		$id=get_post('id',"i");
		$row=MM("gxny","gxny_blog")->selectRow("id=".$id);
		$userid=M("login")->userid;
		$admin=M("mod_gxny_admin")->selectRow("userid=".$userid." AND status=1 ");
		if(!$admin){
			$this->goAll("暂无权限",1);
		}else{
			MM("gxny","gxny_blog")->update(array(
				"isrecommend"=>1
			),"id=".$id);
			$this->goAll("推荐成功");
		}
	}
	
	
}