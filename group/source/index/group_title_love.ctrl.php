<?php
	class group_title_loveControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			
		}
		
		public function onZan(){
			M("login")->checklogin();
			$newsid=get('newsid','i');
			$userid=M("login")->userid;
			$news=M("mod_group_title")->selectRow("id=".$newsid);
			if(!$news){
				$this->goAll("数据出错",1);
			}
			$row=M("mod_group_title_love")->selectRow("newsid=".$newsid." AND userid=".$userid);
			if($row){
				M("mod_group_title_love")->delete("id=".$row['id']);
				M("mod_group_title")->changenum("love_num",-1,"id=".$newsid);
				$num=max(0,$news['love_num']-1);
				$this->goAll("success",0,array(
					"action"=>"delete",
					"news_zan_id"=>$row['id'],
					"num"=>$num
				));
			}else{
				$news_zan_id=M("mod_group_title_love")->insert(array(
					"newsid"=>$newsid,
					"userid"=>$userid
				));
				M("mod_group_title")->changenum("love_num",1,"id=".$newsid);
				$user=M("login")->getUser();
				$num=$news['love_num']+1;
				$news_zan=array(
					"id"=>$news_zan_id,
					"newsid"=>$newsid,
					"userid"=>$userid,
					"nickname"=>$user['nickname'],
					"user_head"=>images_site($user['user_head'])
				);
				$this->goAll("success",0,array(
					"action"=>"add",
					"news_zan"=>$news_zan,
					"num"=>$num
				));
			}
		}
		
	}
?>