<?php
class forumModel extends model{
	public $table="mod_forum";
	public function __construct(){
		parent::__construct();
	}
	
	public function id_list($option){
		$data=$this->select($option);
		if($data){
			foreach($data as $k=>$v){
				$t[$v['id']]=$v;
			}
			return $t;
		}
		return false;
	}
	public function getListByIds($ids,$online=0){
		$ids=array_unique($ids);
		$where=" id in("._implode($ids).")  ";
		if($online){
			$where.=" AND status=1 ";
		}
		$res=$this->Dselect(array(
			"where"=>$where
		));
		if($res){
			foreach($res as $rs){
				$list[$rs["id"]]=$rs;
			}
			return $list;
		}
	}
	public function Dselect($option=array(),&$rscount=false){
		if(!isset($option["fields"])){
			$option["fields"]="id,title,catid,gid,dateline,userid,imgsdata,status,imgurl,money,description,love_num,view_num,comment_num";
		}
		$data=$this->select($option,$rscount);
		$catlist=M("mod_forum_category")->select(array(
			"where"=>"  status=2 ",
			"order"=>" orderindex ASC",
		)); 
		$cats=[];
		if($catlist){
			foreach($catlist as $v){
				$cats[$v['catid']]=$v;
			}
		}
		if($data){
			foreach($data as $v){
				$uids[]=$v['userid'];
				$gids[]=$v['gid'];
			}
			$gs=MM("forum","forum_group")->getListByIds($gids);
			$us=M("user")->getUserByIds($uids); 
			$us=M("user_rank")->rankUsers($us);
			 
			foreach($data as $k=>$v){
				$v['cname']=isset($cats[$v['catid']])?$cats[$v['catid']]:"";
				$v['imgurl']=images_site($v['imgurl']);
				$v['nickname']=$us[$v['userid']]['nickname'];
				$v['user_head']=images_site($us[$v['userid']]['user_head']);
				$v['timeago']=timeago($v['dateline']);
				$v["user"]=$us[$v["userid"]];
				$v['group_title']=$gs[$v['gid']]['title'];
				if($v['imgsdata']){
					$imgs=explode(",",$v['imgsdata']);
					$imgslist=array();
					foreach($imgs as $img){
						$imgslist[]=images_site($img);
					}
					$v['imgslist']=$imgslist;
				}
				$data[$k]=$v;
			}
		}
		return $data;
	}
	
	public function recommendList($ops=array(),$limit=6){
		$where="  status=2 AND is_recommend=1 ";
		if(isset($ops['gid'])){
			$where.=" AND gid=".intval($ops['gid']);
		}
		if(isset($ops['catid'])){
			$where.="AND catid=".intval($ops['catid']);
		}
		$option=array(
			"where"=>$where,
			"limit"=>$limit,
			"order"=>"id DESC"
		);
		return $this->Dselect($option);
	}
	
	public function del($data){
		$this->update(array(
			"status"=>11
		),"id=".$data["id"]);
		//删除评论
		$rscount=MM("forum","forum_comment")->selectOne(array(
			"where"=>" objectid=".$data["id"]." AND status in(0,1)",
			"fields"=>"count(*) as ct"
		));
		MM("forum","forum_comment")->update(array(
			"status"=>11
		),"objectid=".$data["id"]);
		$group=MM("forum","forum_group")->selectRow("gid=".$data["gid"]);
		if($group){
			 
			MM("forum","forum_group")->update(array(
				"topic_num"=>$group["topic_num"]-1,
				"comment_num"=>max(0,$group["comment_num"]-$rscount)
			),"gid=".$group["gid"]);
		}
		
		 
	}
	
}

?>