<?php
class sblog_peopleControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$this->smarty->display("sblog_people/index.html");
	}
	public function onList(){
		$userid=M("login")->userid;
		$order=" followed_num DESC,userid DESC";
		$start=get("per_page","i");
		$limit=12;
		$rscount=true;
		$type=get("type","h");
		switch($type){
			case "recommend":
				
				$group=M("user_group")->selectRow("title='推荐用户' ");
				if(!$group){
					$where="status=1 AND followed_num>10 AND follow_num>3 ";
				}else{
					$uids=M("user_group_people")->selectCols(array(
						"where"=>" gid=".$group["gid"],
						"fields"=>"userid"
					));
					if($uids){
						$where.=" status=1  AND userid in("._implode($uids).")";
					}else{
						$where="status=1 AND followed_num>10 AND follow_num>3 ";
					}
				}
				break;
			case "vip":
				$group=M("user_group")->selectRow("title='Vip用户' ");
				if(!$group){
					$where="status=1 AND followed_num>10 AND follow_num>3 ";
				}else{
					$uids=M("user_group_people")->selectCols(array(
						"where"=>" gid=".$group["gid"],
						"fields"=>"userid"
					));
					if($uids){
						$where.=" status=1  AND userid in("._implode($uids).")";
					}else{
						$where="status=1 AND followed_num>3 AND follow_num>3 ";
					}
				}
				break;
			case "new":
				$group=M("user_group")->selectRow("title='新用户' ");
				if(!$group){
					$where="status=1 AND   follow_num>1 ";
				}else{
					$uids=M("user_group_people")->selectCols(array(
						"where"=>" gid=".$group["gid"],
						"fields"=>"userid"
					));
					if($uids){
						$where.=" status=1  AND userid in("._implode($uids).")";
					}else{
						$where="status=1 AND  follow_num>1 ";
					}
				}
				$order="userid DESC";
				break;
			default:
				$where="status in(0,1)  ";
			 
				break;
		}
		$keyword=get('keyword',"h");
		if($keyword){
			$where.=" AND nickname like '%".$keyword."%' ";
		}
		$list=M("user")->Dselect(array(
			"where"=>$where,
			"limit"=>$limit,
			"start"=>$start,
			"fields"=>"userid,nickname,follow_num,followed_num,user_head,description",
			"order"=>$order
		),$rscount);
		if($list){
			$uids=array();
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$fuids=M("follow")->selectCols(array(
				"fields"=>"t_userid",
				"where"=>" userid=".$userid." AND t_userid in("._implode($uids).")"
			));
			foreach($list as $k=>$v){
				if($fuids && in_array($v['userid'],$fuids)){
					$isfollow=1;
				}else{
					$isfollow=0;
				}
				$v["isfollow"]=$isfollow;
				$list[$k]=$v;
			}
		} 
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page
		));
		 
	}
}