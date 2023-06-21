<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class ttcjControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=2 ";
			$url="/module.php?m=ttcj&a=default";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" cjid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_ttcj")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
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
			$this->smarty->display("ttcj/index.html");
		}
		
		public function onList(){
			$where=" status=2 ";
			$url="/module.php?m=ttcj&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" cjid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_ttcj")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
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
			$this->smarty->display("ttcj/list.html");
		}
		
		public function onShow(){
			$cjid=get_post("cjid","i");
			$data=M("mod_ttcj")->selectRow(array("where"=>"cjid={$cjid}"));
			if(empty($data)){
				$this->goAll("数据出错了",0,0,"/index.php");
			}
			$isDoing=1;
			if($ttcj['status']!=2 || strtotime($ttcj['endtime'])<time()){
				$isDoing=0;
			} 
			$data['imgurl']=images_site($data['imgurl']);
			$itemlist=M("mod_ttcj_item")->select(array(
				"where"=>" cjid=".$cjid." AND status=2",
				"order"=>" money ASC"
			));
			$win_money=0;
			if($itemlist){
				foreach($itemlist as $v){
					
					if($v['join_num']>$data['join_num']){
						break;
					}
					$win_money=$v['money'];
				}
			}
			if($data['isopen']){
				$order=M("mod_ttcj_order")->selectRow("cjid=".$cjid);
				$order && $item=M("mod_ttcj_item")->selectRow("id=".$order['itemid']);
				$win_user=M("user")->getUser($data['win_userid']);
			}
			$seo=array(
				"title"=>$data['title'],
				"description"=>$data['description']
			); 
			 
			$this->smarty->goassign(array(
				"data"=>$data,
				"itemList"=>$itemlist,
				"win_money"=>$win_money,
				"win_user"=>$win_user,
				"win_item"=>$item,
				"seo"=>$seo,
				"isDoing"=>$isDoing
				
			));
			$this->smarty->display("ttcj/show.html");
		}
		
		public function onUser(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$user=M("login")->getUser();
			 
			$this->smarty->goAssign(array(
				"user"=>$user
			));
			$this->smarty->display("ttcj/user.html");
		}
		
		public function onAddr(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$addr=M("user_lastaddr")->get($userid);
			$this->smarty->goAssign(array(
				"addr"=>$addr
			));
			
		}
		
		public function onCron(){
			$row=M("mod_ttcj")->selectRow(array(
				"where"=>" status=2 AND isopen=0 AND endtime<'".date("Y-m-d H:i:s")."' "
			));
			if(empty($row)){
				exit("无开奖计划");
			}
			//获取奖品
			$item=M("mod_ttcj_item")->selectRow(array(
				"where"=>" cjid=".$row['cjid']." AND join_num<=".$row['join_num'],
				"order"=>" join_num DESC"
			));
			if(empty($item)){
				M("mod_ttcj")->update(array(
					"isopen"=>1
				),"cjid=".$row['cjid']); 
				exit("无人获奖");
			}
			//开奖
			$win=M("mod_ttcj_join")->selectRow(array(
				"where"=>" cjid=".$row['cjid'],
				"limit"=>1,
				"order"=>" rand() "
			));
			M("mod_ttcj")->update(array(
				"isopen"=>1,
				"win_userid"=>$win['userid']
			),"cjid=".$row['cjid']);
			$_POST=$win;
			$indata=M("mod_ttcj_order")->postData();
			$indata['itemid']=$item['id'];
			$indata['status']=0;
			$indata['createtime']=date("Y-m-d H:i:s");
			$indata['item_title']=$item['title'];
			$indata['item_money']=$item['money'];
			M("mod_ttcj_order")->insert($indata);
			echo "success"; 
		}
		
	}

?>