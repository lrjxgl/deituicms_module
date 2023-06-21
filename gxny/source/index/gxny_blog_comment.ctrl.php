<?php
class gxny_blog_commentControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		M("blacklist")->check($userid);
		M("blacklist_post")->check($userid);
		$data=M("mod_gxny_blog_comment")->postData();
		if(empty($data["content"])){
			$this->goAll("内容不能为空",1);
		}
		$data['ip']=ip();
		$data['ip_city']=ipcity($data['ip'],1);
				 
		if(empty($data['ip_city'])){
			$data['ip_city']="本地";
		}
		$data['createtime']=date("Y-m-d H:i:s");
		$row=M("mod_gxny_blog")->selectRow(array(
			"where"=>" id=".$data['objectid'],			 
		));
		$data["userid"]=$userid;
		M("mod_gxny_blog_comment")->insert($data);
		M("mod_gxny_blog")->changenum("comment_num",1,"id=".$data["objectid"]);
		if($row["topicid"]){
			M("mod_gxny_topic")->changenum("comment_num",1,"id=".$row["topicid"]);
		}
		M("notice")->add(array(
			"content"=>"有人评论了你：".$data['content'],
			"userid"=>intval($row['userid']),
			"template_id"=>"comment",
			"linkurl"=>array(
				"path"=>"/module.php",
				"m"=>"gxny_blog",
				"a"=>"show",
				"param"=>"&id=".$data['objectid']
			)
		));
		$this->goAll("success");
	}
	
	public function onMy(){
		
		$this->smarty->display("gxny_blog_comment/my.html");
	}
	
}