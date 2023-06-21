<?php
class vote_joinControl extends skyMvc{
	
	public $shop_app;
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
	 
		 
		 
	}
	
	public function onDefault(){
		$vid=get_post("vid",'i');
		$vote=M("mod_vote")->selectRow("id=".$vid);
		$this->smarty->template_dir.="/".$vote['tpl'];
		$this->smarty->goAssign(array(
			"vote"=>$vote,
			"skins"=>$this->smarty->template_dir,
		));
		$this->smarty->display("vote_item/index.html");
	}
	
	public function onList(){
		$_GET["ajax"]=1;
		$vid=get_post("vid",'i');
		 
		$bianhao=get('bianhao','i');
		$where=" vid=".$vid." AND status=1 ";
		if($bianhao){
			$where.=" AND bianhao=".$bianhao;
		}
		$type=get("type");
		switch($type){
			 
			case "hot":
				$where.=" AND isrecommend=1 ";
				$order=" vote_num DESC";
				break;
			 
			default:
				$order="id DESC";
				break;
		}
		$start=get_post('per_page','i');
		$limit=24;
		$option=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit,
		);
		$rscount=true;
		$list=M("mod_vote_join")->select($option,$rscount);
		if($list){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				unset($v["telephone"]);
				unset($v["address"]);
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
	
	public function onShow(){
		$id=get_post("id","i");
		$vid=get_post("vid","i");
		$userid=M("login")->userid;
		 
		$vote=M("mod_vote")->selectRow("id=".$vid);
		$join=M("mod_vote_join")->selectRow("id=".$id);
		unset($join["telephone"]);
		unset($join["address"]);
		$author=M("user")->getUser($join["userid"]);
		if(empty($vote) || empty($join)){
			$this->goAll("参数出错",1);	
		}
		$join["imgurl"]=images_site($join["imgurl"]);
		$join["url"]=images_site($join["url"]);
		$imgs=array();
		if(!empty($join["imgsdata"])){
			$ims=explode(",",$join["imgsdata"]);
			
			foreach($ims as $im){
				if(!empty($im)){
					$imgs[]=array(
						"imgurl"=>htmlspecialchars($im),
						"trueimgurl"=>images_site(htmlspecialchars($im))
					);
				}
			}
			 
		}
		$ut=M("mod_vote_join")->select(array(
			"where"=>" vid=".$vid." AND status=1",
			"order"=>" vote_num DESC",
			"fields"=>"userid",
			"limit"=>1000
		));
		$join['paiming']="暂无";
		if($ut){ 
			foreach($ut as $k=>$u){
				if($u['userid']==$join['userid']){
					$join['paiming']=$k+1;
					break;
				}
			}
		}
		$this->smarty->template_dir.="/".$vote['tpl'];
		$this->smarty->goassign(array(
			"vote"=>$vote,
			"join"=>$join,
			"imgsdata"=>$imgs,
			"author"=>$author,
			"skins"=>$this->smarty->template_dir,
			"seo"=>array(
				"title"=>"我正在参加".$vote['title']."快来帮我投一票吧"
			)		
		));
		
		$this->smarty->display("vote_item/show.html");
	}
	
	
	public function onVote(){
		M("login")->checkLogin();
		$id=get_post('id','i');
		$vid=get_post('vid','i');
		$userid=M("login")->userid;
		$vote=M("mod_vote")->selectRow("id=".$vid);
		$join=M("mod_vote_join")->selectRow("id=".$id);
		$g1=M("mod_vote_go")->selectRow(array("where"=>"joinid=".$id." AND userid=".$userid,"order"=>"id DESC"));
		$time=date("Y-m-d H:i:s");
		//判断当前选手投票
		if(!empty($g1)){
			/***
			*投票类型 gotype
			* 1 一次性投票
			* 2 每天 一定数量
			*/
			if($vote['gotype']==1){
				$this->goAll("你已经投过票了",1);
			}else{
				if(substr($g1['time'],0,10)==date("Y-m-d")){
					$this->goAll("你今天已经投过票了",1);
				}
			}						
		}
		//判断整个活动
		if($v['gotype']==1){
			$g2=M("mod_vote_go")->selectOne(array("where"=>"vid=".$vid." AND userid=".$userid,"fields"=>" count(*) as ct "));
			if($g2>$vote['gonum']){
				$this->goAll("你投票次数已用完了",1);
			}
		}else{
			$day=strtotime(date("Y-m-d"));
			$g3=M("mod_vote_go")->selectOne(array("where"=>"vid=".$vid." AND userid=".$userid." AND time>'".$day."'","fields"=>" count(*) as ct "));
			if($g3>=$vote['gonum']){
				$this->goAll("你今天投票次数已用完了",1);
			}
		}
		
		M("mod_vote_go")->insert(array(
				"userid"=>$userid,
				"vid"=>$vid,
				"joinid"=>$id,			 
				"time"=>$time
		));
		
		M("mod_vote_join")->update(array(
			"vote_num"=>$join['vote_num']+1
		),"id=".$id);
		M("mod_vote")->update(array(
				"vote_num"=>$vote['vote_num']+1,
			 
			),"id=".$vid);
		$this->goAll("感谢您的投票",0);
	}
	
	public function onjoin(){
		M("login")->checkLogin();
		$id=get_post("id","i");
		$userid=M("login")->userid;
		$vote=M("mod_vote")->selectRow("id=".$id);
		if(empty($vote) || $vote["status"]!=1){
			$this->goAll("该投票活动已经结束",1);	
		}
		$vote["imgurl"]=images_site($vote["imgurl"]); 
		$row=M("mod_vote_join")->selectRow("userid=".$userid." AND status in(0,1) AND vid=".$id);
		if($row){
			if($row["imgurl"]){
				$row["trueimgurl"]=images_site($row["imgurl"]);
			}
			if($row['status']==1){
				$this->goAll("您的资料已经通过审核，请联系客服修改",1);
				
			}
			$row["url_www"]=images_site($row["url"]);
			$imgs=array();
			if(!empty($row["imgsdata"])){
				$ims=explode(",",$row["imgsdata"]);
				
				foreach($ims as $im){
					if(!empty($im)){
						$imgs[]=array(
							"imgurl"=>htmlspecialchars($im),
							"trueimgurl"=>images_site(htmlspecialchars($im))
						);
					}
				}
				 
			}
		}
		$this->smarty->template_dir.="/".$vote['tpl'];
		$this->smarty->goassign(array(
			"vote"=>$vote,
			"data"=>$row,
			"imgsdata"=>$imgs,
			"skins"=>$this->smarty->template_dir,
			"seo"=>array(
				"title"=>$vote['title']
			)
		));
		
		$this->smarty->display("vote/join.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$id=get_post("id","i");
		$userid=M("login")->userid;
		$vote=M("mod_vote")->selectRow("id=".$id);
		if(empty($vote)){
			$this->goAll("参数出错",1);	
		}
		$row=M("mod_vote_join")->selectRow("userid=".$userid." AND vid=".$id);
	 
		$data['vid']=$id;
		$data['dateline']=time();
		$data['userid']=$userid;
		$data['title']=post('title','h');
		$data['nickname']=post('nickname','h');
		$data['telephone']=post('telephone','h');
		$data['address']=post('address','h');
		if(!is_tel($data['telephone'])){
			$this->goAll("请正确填写手机号码",1);
		}
		if(empty($data['nickname'])){
			$this->goAll("请正确填写联系人",1);
		}
		if(empty($data['address'])){
			$this->goAll("请正确填写收货地址",1);
		}
		if(empty($data['title'])){
			$this->goAll("请正确填写主题",1);
		}
		
		$data['imgurl']=post('imgurl','h');
		$data['url']=post('url','h');
		$data['content']=post('content','h');
		$data['description']=post('description','h');
		$data["imgsdata"]=post("imgsdata","h");
		if(!empty($data["imgsdata"])){
			$ims=explode(",",$data["imgsdata"]);
			$imgs=array();
			foreach($ims as $im){
				if(!empty($im)){
					$imgs[]=htmlspecialchars($im);
				}
			}
			$data["imgsdata"]=implode(",",$imgs);
			if(!empty($imgs)){
				$data["imgurl"]=$imgs[0];
			}
			
		} 
		$data['status']=0;
		
		if($row){
			 
			if($row['status']==1){
				$this->goAll("您的资料已经通过审核，请联系客服修改",1);
			}else{
				M("mod_vote_join")->update($data,"id=".$row['id']);
			}
			$i=$row['id'];
		}else{
			$data["vote_num"]=0;
			$ct=M("mod_vote_join")->selectOne(array(
				"where"=>" vid=".$id,
				"fields"=>"count(*) "
			));
			$bianhao=1000+$ct;
			$data["bianhao"]=$bianhao;
			$i=M("mod_vote_join")->insert($data);
			M("mod_vote")->update(array(
				"join_num"=>$vote['join_num']+1		
			),"id=".$id);
		}
		$url="/module.php?m=vote_join&a=show&vid=".$id."&id=".$i;
		$this->goAll("感谢您的参与，请等待审核",0,0,$url);
	}
	
}

?>