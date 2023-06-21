<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class shopmap_hongbao_sendlogControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" isdelete=0 ";
			$url="/moduleadmin.php?m=shopmap_hongbao_sendlog&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shopmap_hongbao_sendlog")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
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
				)
			);
			$this->smarty->display("shopmap_hongbao_sendlog/index.html");
		}
		
		public function onSend(){
			$id=get('id','i');
			$row=M("mod_shopmap_hongbao_sendlog")->selectRow("id=".$id);
			if($row['isdelete']){
				$this->goAll("该红包已重发了",1);			
			}
			M("mod_shopmap_hongbao_sendlog")->update(array(
				"isdelete"=>1
			),"id=".$id);
			$user=M("user")->selectRow("userid=".$row['userid']);
			$wx=M("weixin")->selectRow("status=1");
			include "api/wxpay/lib/WxAppPay.Config.php"; 
			WxPayConfig::init($wx);
			require ROOT_PATH."api/wxpay/lib/WxPayHongbao.php";
			$hb=new WxPayHongbao();
			$openlogin=M("openlogin")->selectRow("xfrom='weixin' AND userid=".$row["userid"]);
			$res=$hb->send(array(
				"re_openid"=>$openlogin['openid'],
				"total_amount"=>$row['money']*100,
				"total_num"=>1,
				"send_name"=>"福鼎互联网+活动",
				"wishing"=>"感谢您参加福鼎“互联网+”商家上网活动，祝您生活愉快！"
			));
			M("mod_shopmap_hongbao_sendlog")->insert(array(
				"userid"=>$row['userid'],
				"money"=>$row['money'],
				"dateline"=>time(),
				"status"=>$res['result_code'],
				"msg"=>$res['err_code_des'],
				"content"=>base64_encode(json_encode($res))
			));
			$this->goAll("重发成功");
		}
		
	}

?>