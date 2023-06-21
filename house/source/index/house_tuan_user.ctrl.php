<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_tuan_userControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$tid=get("tid","i");
			$where=" tid=".$tid;
			$url="/module.php?m=house_tuan_user&tid=".$tid;
			$limit=1200;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where,
				"fields"=>"tid,userid"
			);
			$rscount=true;
			$list=M("mod_house_tuan_user")->select($option,$rscount);
			if($list){
				foreach($list as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				
				foreach($list as $k=>$v){
					if(!isset($us[$v["userid"]])){
						unset($list[$k]);
						continue;
					}
					$v=$us[$v["userid"]];
					$list[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("house_tuan_user/index.html");
		}
		
		public function onAdmin(){
			$tid=get("tid","i");
			$tuan=M("mod_house_tuan")->selectRow("id=".$tid);
			$where=" tid=".$tid;
			$url="/module.php?m=house_tuan_user&a=admin&tid=".$tid;
			$limit=1200;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where,
				"fields"=>"*"
			);
			$rscount=true;
			$list=M("mod_house_tuan_user")->select($option,$rscount);
			if($list){
				foreach($list as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				
				foreach($list as $k=>$v){
					$v["user_head"]=$us[$v["userid"]]["user_head"];
					$list[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"tuan"=>$tuan
				)
			);
			$this->smarty->display("house_tuan_user/admin.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$data=M("mod_house_tuan_user")->postData();
			if(empty($data["tid"])){
				$this->goAll("数据出错",1);
			}
			$tuan=M("mod_house_tuan")->selectRow("id=".$data["tid"]);
			if($tuan["status"]!=1){
				$this->goAll("看房团还已下线",1);
			}
			$total=M("mod_house_tuan_user")->selectOne(array(
				"where"=>"id=".$data["tid"],
				"fields"=>"count(*)"
			));
			if($total>$tuan["max_num"]){
				$this->goAll("已经超过最大多人数",1);
			}
			if(empty($data["truename"])){
				$this->goAll("请填写名字",1);
			}
			if(empty($data["telephone"]) && !is_tel($data["telephone"])){
				$this->goAll("请正确填写手机号码",1);
			}
			$row=M("mod_house_tuan_user")->selectRow("userid=".$userid." AND tid=".$data["tid"]);
			if($row){
				$this->goAll("你已经报名了",1);
			}
			
			//邀请推广
			$invite_hsuserid=get_post("invite_hsuserid","i");
			if(!$invite_hsuserid && isset($_SESSION["ss_invite_hsuserid"])){
				$invite_hsuserid=intval($_SESSION["ss_invite_hsuserid"]);
			}elseif(isset($_COOKIE["ck_invite_hsuserid"])){
				$invite_hsuserid=intval($_COOKIE["ck_invite_hsuserid"]);
			}
			$data["userid"]=$userid;
			if($invite_hsuserid!=$userid){
				$data["invite_userid"]=$invite_hsuserid;
			}
			
			M("mod_house_tuan_user")->insert($data);
			M("mod_house_tuan")->changenum("join_num",1,"id=".$data["tid"]);
			$this->goAll("报名成功");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/module.php?m=house_tuan_user&a=my";
			$limit=1200;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where,
				"fields"=>"tid,userid,status,israty"
			);
			$rscount=true;
			$list=M("mod_house_tuan_user")->select($option,$rscount);
			if($list){
				$tids=array();
				foreach($list as $v){
					$tids[]=$v["tid"];
				}
				$tts=MM("house","house_tuan")->getListByIds($tids,"id,title,imgurl,stime,description"); 
				
				foreach($list as $k=>$v){
	 
					$p=$tts[$v["tid"]];
					$p["israty"]=$v["israty"];
					$p["status"]=$v["status"];
					$list[$k]=$p;
				}
			} 
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("house_tuan_user/my.html");
		}
		
		public function oncheckin(){
			$id=get("id","i");
			$u=M("mod_house_tuan_user")->selectRow("id=".$id);
			$t=M("mod_house_tuan")->selectRow("id=".$u["tid"]);
			$userid=M("login")->userid;
			if($t["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($u["status"]!=0){
				$this->goAll("已处理过了",1);
			}
			M("mod_house_tuan_user")->update(array(
				"status"=>1
			),"id=".$id);
			$this->goAll("签到成功");
		}
		
		public function onSendHongbao(){
			$id=get("id","i");
			$u=M("mod_house_tuan_user")->selectRow("id=".$id);
			$t=M("mod_house_tuan")->selectRow("id=".$u["tid"]);
			$userid=M("login")->userid;
			if($t["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($u["status"]!=1){
				$this->goAll("用户未签到",1);
			}
			if($u["ishongbao"]){
				$this->goAll("红包已发送过了",1);
			}
			
			$account=M("user")->get($userid,"userid,money");
			if($account["money"]<($t["hongbao"]+$t["invite_hongbao"])){
				$this->goAll("账户余额不足",1);
			}
			M("user")->begin();
			M("user")->addMoney(array(
				"money"=>-($t["hongbao"]+$t["invite_hongbao"]),
				"userid"=>$userid,
				"content"=>"送给看房客户奖励[money]元"
			));
			M("mod_house_tuan_user")->update(array(
				"ishongbao"=>1
			),"id=".$id);
			if($t["hongbao"]>0 || $t["invite_hongbao"]>0){
				require_once ROOT_PATH."api/wxpay/lib/WxPay.Config.php";
				$wx=M("weixin")->selectRow(array("where"=>$where,"order"=>"id DESC"));
				WxPayConfig::init($wx);
				require_once ROOT_PATH."api/wxpay/lib/WxPayHongbao.php";
				$hb=new WxPayHongbao();
			}
			if($t["hongbao"]>0){
				
				 
				$open=M("openlogin")->selectRow("userid=".$u["userid"]." AND xfrom='weixin'");
				$res=$hb->send(array(
					"re_openid"=>$open["openid"],
					"total_amount"=>$t["hongbao"]*100,
					"total_num"=>1,
					"send_name"=>"福鼎生活网",
					"wishing"=>"福鼎看房团，感谢您的参与，祝您早点买到合适的房字"
				));
				if($res["return_code"]!='SUCCESS'){
					M("user")->rollback();
					$this->goAll("红包发送失败",1);
				}
			}
			if($t["inivte_hongbao"]>0 && $u["invite_userid"]){
				
				 
				$open=M("openlogin")->selectRow("userid=".$u["invite_userid"]." AND xfrom='weixin'");
				$res=$hb->send(array(
					"re_openid"=>$open["openid"],
					"total_amount"=>$t["invite_hongbao"]*100,
					"total_num"=>1,
					"send_name"=>"福鼎生活网",
					"wishing"=>"福鼎看房团，感谢您的参与，祝您早点买到合适的房字"
				));
				if($res["return_code"]!='SUCCESS'){
					M("user")->rollback();
					$this->goAll("红包发送失败",1);
				}
			}
			M("user")->commit();
			$this->goAll("红包发送成功");
		}
		
	}

?>