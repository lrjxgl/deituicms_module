<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class sjsj_newsControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1   ";
			$url="module.php?m=sjsj_news&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" newsid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("sjsj","sjsj_news")->Dselect($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("sjsj_news/index.html");
		}
		
		public function onList(){
			$where=" status=1   ";
			$url="module.php?m=sjsj_news&a=list";
			$type=get("type","h");
			$order=" newsid DESC";
			switch($type){
				case "new":
					$where.="  AND  isbuy=0  AND issuccess=0 ";
					$order=" newsid DESC ";
					break;
				case "success":
					$where .=" AND issuccess=1 ";
					$order=" updatetime DESC ";
					break;
				case "recommend":
					$where.="  AND  isbuy=0 ";
					$where.=" AND isrecommend=1 ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			$rscount=true;
			$data=MM("sjsj","sjsj_news")->Dselect($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("sjsj_news/index.html");
		}
		
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" status in(0,1)  AND userid=".$userid;
			$url="/module.php?m=sjsj_news&a=my";
			$type=get("type","h");
			$order=" newsid DESC";
			switch($type){
				case "new":
					$where.="  AND  isbuy=0  AND issuccess=0 ";
					$order=" newsid DESC ";
					break;
				case "success":
					$where .=" AND issuccess=1 ";
					$order=" updatetime DESC ";
					break;
				case "recommend":
					$where.="  AND  isbuy=0 ";
					$where.=" AND isrecommend=1 ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			$rscount=true;
			$data=MM("sjsj","sjsj_news")->Dselect($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("sjsj_news/my.html");
		}
		
		public function onMyBuy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" status in(0,1)  AND buyer=".$userid;
			$url="/module.php?m=sjsj_news&a=mybuy";
			$type=get("type","h");
			$order=" newsid DESC";
			switch($type){
				case "new":
					$where.="  AND  isbuy=0  AND issuccess=0 ";
					$order=" newsid DESC ";
					break;
				case "success":
					$where .=" AND issuccess=1 ";
					$order=" updatetime DESC ";
					break;
				case "recommend":
					$where.="  AND  isbuy=0 ";
					$where.=" AND isrecommend=1 ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			$rscount=true;
			$data=MM("sjsj","sjsj_news")->Dselect($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("sjsj_news/mybuy.html");
		}
		
		public function onShow(){
			$newsid=get_post("newsid","i");
			$data=M("mod_sjsj_news")->selectRow(array("where"=>"newsid=".$newsid." AND status in(0,1) "));
			if(empty($data)){
				$this->goAll("当前信息已下架",1);
			}
			$suser=MM("sjsj","sjsj_user")->get($data["userid"]); 
			$this->smarty->goassign(array(
				"data"=>$data,
				"suser"=>$suser
			));
			$this->smarty->display("sjsj_news/show.html");
		}
		public function onAdd(){
			$newsid=get_post("newsid","i");
			if($newsid){
				$data=M("mod_sjsj_news")->selectRow(array("where"=>"newsid=".$newsid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("sjsj_news/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$newsid=get_post("newsid","i");
			$data=M("mod_sjsj_news")->postData();
			$data["content"]=nl2br($data["content"]);
			checkEmpty($data["title"],"标题不能为空");
			checkEmpty($data["content"],"内容不能为空");
			$userid=M("login")->userid;
			$data["userid"]=$userid;
			$data["updatetime"]=date("Y-m-d H:i:s");
			$su=MM("sjsj","sjsj_user")->get($userid);
			if($newsid){
				M("mod_sjsj_news")->update($data,"newsid=".$newsid);
			}else{
				$data["createtime"]=date("Y-m-d H:i:s");
				MM("sjsj","sjsj_user")->update(array(
					"post_num"=>$su["post_num"]+1
				),"userid=".$userid);
				M("mod_sjsj_news")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onDelete(){
			$newsid=get_post('newsid',"i");
			M("mod_sjsj_news")->update(array("status"=>11),"newsid=".$newsid);
			$this->goAll("删除成功");
			 
		}
		//买断
		public function onBuy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$newsid=get("newsid","i");
			$scf=MM("sjsj","sjsj_config")->get();
			$money=$scf["sold_money"];
			$user=M("user")->getUser($userid,"userid,money");
			if($user["money"]<$money){
				$this->goAll("余额不足，请先充值",1,$user);
			}
			$news=M("mod_sjsj_news")->selectRow("newsid=".$newsid);
			if($news["isbuy"]){
				$this->goAll("已经被人买走了",1);
			}
			if($news["userid"]==$userid){
				$this->goAll("不能买自己的",1);
			}
			M("user")->begin();
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$money,
				"content"=>"购买商机消费了".$money."元"
			));
			M("mod_sjsj_news")->update(array(
				"isbuy"=>1,
				"buyer"=>$userid
			),"newsid=".$newsid);
			//更改作者统计
			$su=MM("sjsj","sjsj_user")->get($news["userid"]);
			MM("sjsj","sjsj_user")->update(array(
				"post_sold_num"=>$su["post_sold_num"]+1
			),"userid=".$news["userid"]);
			//获取收益
			$money=$scf["sold_money"]*(100-$scf["pt_per"])*0.01;
			MM("sjsj","sjsj_user")->addMoney(array(
				"userid"=>$news["userid"],
				"money"=>$money,
				"content"=>"用户卖断商机【".$news["title"]."】获得了".$money."元"
			));
			//消息通知
			M("notice")->add(array(
				"userid"=>$news["userid"],
				"content"=>"用户卖断商机【".$news["title"]."】获得了".$money."元",
				"linkurl"=>array(
					"path"=>"/module.php",
					"m"=>"sjsj_news",
					"a"=>"show",
					"param"=>"newsid=".$news["newsid"]
				)
			));
			M("user")->commit();
			$this->goAll("success");
		}
		
		public function onFinish(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$newsid=get_post("newsid","i");
			$news=M("mod_sjsj_news")->selectRow("newsid=".$newsid);
			if($news["buyer"]!=$userid){
				$this->goAll("暂无权限",1,$news);
			}
			if($news["issuccess"]){
				$this->goAll("已经打赏了",1);
			}
			$money=post("money","i");
			$content=post("content","h");
			if($money==0){
				$this->goAll("请填写打赏金额",1);
			}
			checkEmpty($content,"请写打赏内容");
			$user=M("user")->selectRow("userid=".$userid);
			if($user["money"]<$money){
				$this->goAll("余额不足",1);
			}
			$su=MM("sjsj","sjsj_user")->get($news["userid"]);
			M("user")->begin();
			M("mod_sjsj_news")->update(array(
				"issuccess"=>1,
				"sj_money"=>$money,
				"sj_content"=>$content
			),"newsid=".$newsid);
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$money,
				"content"=>"打赏[".$news["title"]."]发布者".$money." 元"
			));
			$income=(100-$scf["pt_per"])*$money*0.01;
			MM("sjsj","sjsj_user")->addMoney(array(
				"userid"=>$news["userid"],
				"money"=>$income,
				"content"=>"[".$news["title"]."]收到打赏".$money." 元"
			));
			//更改作者统计
			
			MM("sjsj","sjsj_user")->update(array(
				"post_success_num"=>$su["post_success_num"]+1
			),"userid=".$news["userid"]);
			M("user")->commit();
			$this->goAll("打赏成功");
		}
		
	}

?>