<?php
	class group_titleModel extends model{
		public $table="mod_group_title";
		public function __construct(){
			parent::__construct();
		}
		public function getListByIds($ids){
			$rss=$this->select(array(
				"where"=>"id in ("._implode($ids).")"
			));
			if($rss){
				foreach($rss as $rs){
					$data[$rs['id']]=$rs;
				}
				return $data;
			}
		}
		public  function select($option=array(),&$rscount=false,$cache = 0, $expire = 60){
			$userid=M("login")->userid;
			$fuids=M("follow")->selectCols(array(
				"fields"=>"t_userid",
				"where"=>"userid=".$userid
			));
			 
			$data=parent::select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids); 
				foreach($data as $k=>$v){
					$v["user"]=$us[$v["userid"]];
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=images_site($us[$v['userid']]['user_head']);
					$v['timeago']=timeago($v['dateline']);
					if($v['userid']==$userid){
						$v['isme']=1;
					}else{
						$v['isme']=0;
					}
					$v['isfollow']=0;
					if($fuids && in_array($v['userid'],$fuids)){
						$v['isfollow']=1;
					}
					$v["imgList"]=[];
					if(!empty($v['imgsdata'])){
						$imgList=explode(",",$v['imgsdata']);
						foreach($imgList as $kk=>$vv){
							$v["imgList"][]=images_site($vv);
						}
					}
					$v["imgurl"]=images_site($v["imgurl"]);
					$data[$k]=$v; 
				}
			}
			return $data;
		}
	}
?>