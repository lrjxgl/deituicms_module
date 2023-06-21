<?php
class ershou_product_commentControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onList(){
		$productid=get_post("productid","i");
		$data=M("mod_ershou_product")->selectRow(array("where"=>"productid=".$productid));
		if(empty($data)){
			$this->goAll("数据出错",1);
		}
		 
		 
		/****获取评论******/
		$start=get('per_page','i');
		$limit=24;
		$rscount=true;
		$cms=MM("ershou","ershou_product_comment")->select(array(
			"where"=>"pid=0 AND objectid=".$productid,
			"order"=>"id ASC",
			"limit"=>$limit,
			"start"=>$start
		),$rscount);
		if($cms){
			foreach($cms as $v){
				$uids[]=$v['userid'];
				$ids[]=$v['id'];
			}
			$cmschild=MM("ershou","ershou_product_comment")->select(array(
				"where"=>"pid in("._implode($ids).") ",
				"order"=>"id ASC",
			));
			if($cmschild){
				foreach($cmschild as $v){
					$uids[]=$v['userid'];
				}
			}
			$us=M("user")->getUserByIds($uids);
			$childs=array();
			if($cmschild){
			
				foreach($cmschild as $v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=images_site($us[$v['userid']]['user_head']);
					$v['imgurl']=images_site($v['imgurl']);
					$v["timeago"]=timeago(strtotime($v["createtime"]));
					$childs[$v['pid']][]=$v;
				}
			}
			foreach($cms as $k=>$v){
				$v['nickname']=$us[$v['userid']]['nickname'];
				$v['user_head']=images_site($us[$v['userid']]['user_head']);
				$v['imgurl']=images_site($v['imgurl']);
				$v['child']=isset($childs[$v['id']])?$childs[$v['id']]:array();
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$cms[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goassign(array(
			"data"=>$data,
			"rscount"=>$rscount,
			"per_page"=>$per_page, 
			"list"=>$cms,
			"ssuser"=>M("login")->getUser()
		));
	}
	public function onSave(){
		M("login")->checklogin();
		$userid=M("login")->userid;
		//M("blacklist")->check($userid);
		
		$data=M("mod_ershou_product_comment")->postData();
		
		$data['objectid']=post('objectid','i');	
		$product=M("mod_ershou_product")->selectRow("productid=".$data["objectid"]);
		$data['userid']=$userid;
		$data['createtime']=date("Y-m-d H:i:s");
		//处理imgsdata
		$data['imgsdata']=post('imgsdata','h');
		if(!empty($data["imgsdata"])){
			$ims=explode(",",$data["imgsdata"]);
			foreach($ims as $im){
				if($im!="undefined" && $im!=""){
					$imgsdata[]=$im;
				}
			}
			if(!empty($imgsdata)){
				$data["imgurl"]=$imgsdata[0];
				$data["imgsdata"]=implode(",",$imgsdata);
			}
			
		} 
		$id=M("mod_ershou_product_comment")->insert($data);
		M("mod_ershou_product")->changenum("comment_num",1,"productid=".$data['objectid']);
		//通知作者
		if($product['userid']!=$userid){
			M("notice")->add(array(
				"userid"=>$product['userid'],
				"content"=>"【评论】".$data['content']
			));
		}
		$num=$product['comment_num']+1;
		$this->goAll("评论成功",0,array("id"=>$id,"num"=>$num));
	}
	
	 
	
}