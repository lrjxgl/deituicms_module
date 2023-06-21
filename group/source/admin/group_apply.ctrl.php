<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class group_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="";
			$url="/module.php?m=group_apply&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_group_apply")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as &$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("group_apply/index.html");
		}
		
		public function onPass(){
			$gid=get_post('gid','i');
			$apply=M("mod_group_apply")->selectRow("gid=".$gid);
			unset($apply['gid']);
			$apply['status']=1;
			$apply['user_num']=1;
			M("mod_group_apply")->delete("gid=".$gid);
			$gid=M("mod_group")->insert($apply);
			
			//生成管理员
			M("mod_group_user")->insert(array(
				"userid"=>$apply['userid'],
				"gid"=>$gid,
				"isfound"=>1,
				"isadmin"=>1,
				"status"=>1,
				"dateline"=>time()
			));
			//发送通知
			M("notice")->add(array(
				"userid"=>$apply['userid'],
				"content"=>"您申请创建圈子《".$apply['gname']."》，审核通过了，快去看看吧！"
			));
			$this->goAll("审核成功");
		}
		
		public function onForbid(){
			$gid=get_post('gid','i');
			$apply=M("mod_group_apply")->selectRow("gid=".$gid);
			M("mod_group_apply")->delete("gid=".$gid);
			//发送通知
			M("notice")->add(array(
				"userid"=>$apply['userid'],
				"content"=>"您申请创建圈子《".$apply['gname']."》，没通过审核了"
			));
			$this->goAll("审核成功");
		}
		
	}

?>