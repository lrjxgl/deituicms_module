<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class hongbaoControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onInit(){
			$_SESSION['sshongbaoget']=1;
		}
		
		
		public function onLoginx(){
			$_SESSION['sshongbaoget']=1;
			$this->onDefault();
		}
		public function onLogin3232(){
			$_SESSION['sshongbaoget']=1;
			$this->onDefault();
		}
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=hongbao";
			$type=get("type","h");
			switch($type){
				case "all":
					$where="status in(1,2) ";
					break;
				case "finish":
					$where="status=1 AND endtime<".time()." ";
					break;
				default:
					$where=" status=1 AND endtime>".time()."  ";
					$type="doing";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_hongbao")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					if($v['endtime']<time()){
						$v['isfinish']=1;
					}
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
					"type"=>$type
				)
			);
			$this->smarty->display("hongbao/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_hongbao")->selectRow(array("where"=>"id={$id}"));
				$data['imgurl']=images_site($data['imgurl']);
			}
			$userid=M("login")->userid;
			$myItem=M("mod_hongbao_item")->selectRow("hbid=".$id." AND userid=".$userid);
			$canget=1;
			if($myItem){
				$canget=0;
			}
			$itemlist=M("mod_hongbao_item")->select(array(
				"where"=>"hbid=".$id." AND userid>0",
				"order"=>"money desc"
			));
			if($itemlist){
				foreach($itemlist as $v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($itemlist as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=$us[$v['userid']]['user_head'];
					$itemlist[$k]=$v;
					
				}
			}
			 
			if($data['endtime']<time()){
				$data['isfinish']=1;
			}
			$this->smarty->assign(array(
				"seo"=>array(
					"title"=>$data["title"]
				)
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"canget"=>$canget,
				"itemlist"=>$itemlist,
				"myItem"=>$myItem
			));
			$this->smarty->display("hongbao/".$data['tpl']);
		}
		
		public function onZhaoshang(){
			$word="zhaoshang";
			$data=M("mod_hongbao_html")->selectRow("word='".$word."'");
			$this->smarty->goAssign(array(
				"data"=>$data
			));
			$this->smarty->display("hongbao/zhaoshang.html");
		}
		
		public function onGet(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post('id','i');
			$hb=M("mod_hongbao")->selectRow(array("where"=>"id=".$id));
			if($hb["status"]!=1){
				$this->goAll("该活动已下线",1);
			}
			if($hb['endtime']<time()){
				$this->goAll("该红包答题活动已经结束了",1);
			}
			$answer=get_post('answer','x');
			if($hb['isask'] && $hb['answer']!=$answer){
				$this->goAll("答案出错了".$answer,1);
			}
			$user_guest=get_post("user_guest","x");
			$item=M("mod_hongbao_item")->selectRow("hbid=".$id." AND userid=".$userid);
			if($item){
				$this->goAll("你已经领取过了",2);
			}
			if($hb['isfinish']){
				$this->goAll("红包已经领取完了",1);
			}
			$itemlist=M("mod_hongbao_item")->select(array(
				"where"=>"hbid=".$id." AND userid=0 ",
				 
			));
			if(empty($itemlist)){
				$this->goAll("红包已经领取完了",1);
			}
			$ct=count($itemlist);
			if($ct==0){
				M("mod_hongbao_item")->update(array(
					"userid"=>$userid,
					"user_guest"=>$user_guest,
					"dateline"=>time()
				),"id=".$itemlist[0]['id']);
				M("mod_hongbao")->update(array(
					"isfinish"=>1
				),"id=".$id);
				$myitem=$itemlist[0];
			}else{
				$key=rand(0,$ct);
				 
				M("mod_hongbao_item")->update(array(
					"userid"=>$userid,
					"user_guest"=>$user_guest,
					"dateline"=>time()
					
				),"id=".$itemlist[$key]['id']);
				$myitem=$itemlist[$key];
			}
			//操作红包
			$hbuser=MM("hongbao","hongbao_user")->get($userid);
			MM("hongbao","hongbao_user")->addmoney(array(
				"userid"=>$userid,
				"typeid"=>2,
				"money"=>$myitem['money'],
				"dateline"=>time(),
				"content"=>"你参与活动获得了".$myitem['money'].",原来".$hbuser['money'].",现在".($hbuser['money']+$myitem['money'])."。"
			));
			$hbuser=MM("hongbao","hongbao_user")->get($userid);
			$user=M("user")->selectRow("userid=".$userid);
			if($hbuser['money']>1 && $user['xfrom']=='weixin'  ){
				//发送红包
				$wx=M("weixin")->selectRow(" status=1 ");
				include "api/wxpay/lib/WxAppPay.Config.php"; 
				WxPayConfig::init($wx);
				require ROOT_PATH."api/wxpay/lib/WxPayHongbao.php";
				$hb=new WxPayHongbao();
				MM("hongbao","hongbao_user")->addmoney(array(
					"userid"=>$userid,
					"typeid"=>1,
					"money"=>-$hbuser['money'],
					"dateline"=>time(),
					"content"=>"官网给你发了".$hbuser['money']."元红包，现在红包账户0元。"
				));
				$openlogin=M("openlogin")->selectRow("xfrom='weixin' AND userid=".$row["userid"]);
				$res=$hb->send(array(
					"re_openid"=>$openlogin['openid'],
					"total_amount"=>$hbuser['money']*100,
					"total_num"=>1,
					"send_name"=>"答题红包活动",
					"wishing"=>"感谢您参加答题抢活动，祝您生活愉快！"
				));
				M("mod_hongbao_sendlog")->insert(array(
					"userid"=>$userid,
					"money"=>$hbuser['money'],
					"dateline"=>time(),
					"status"=>$res['result_code'],
					"msg"=>$res['err_code_des'],
					"content"=>base64_encode(json_encode($res))
				));
			}
			$this->goAll("红包获取成功");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$user=M("user")->getUser($userid);
			$hbuser=MM("hongbao","hongbao_user")->get($userid);
			$user['money']=$hbuser['money'];
			$sql="select i.*,h.title from 
				".table('mod_hongbao_item')." as i 
				left join ".table('mod_hongbao')." as h 
				on  i.hbid=h.id 
				where userid=".$userid."
				order by i.id DESC 
				limit 48 
			";
			$itemlist=M("mod_hongbao")->getAll($sql);
			$this->smarty->goAssign(array(
				"itemlist"=>$itemlist,
				"user"=>$user
			));
			$this->smarty->display("hongbao/my.html");
		}
		
	}

?>