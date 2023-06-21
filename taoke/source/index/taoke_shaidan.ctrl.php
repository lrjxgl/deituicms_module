<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class taoke_shaidanControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=taoke_shaidan&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_taoke_shaidan")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=images_site($us[$v['userid']]['user_head']);
					$v['timeago']=timeago($v['dateline']);
					if($v['imgsdata']){
						$imgs=explode(",",$v['imgsdata']);
						$imgslist=array();
						foreach($imgs as $img){
							$imgslist[]=images_site($img);
						}
						$v['imgslist']=$imgslist;
					}
					$data[$k]=$v;
				}
			}
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
			$this->smarty->display("taoke_shaidan/index.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" status in(0,1,2) AND userid=".$userid;
			$url="/moduleadmin.php?m=taoke_shaidan&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_taoke_shaidan")->select($option,$rscount);
			if($data){
				 
				foreach($data as $k=>$v){
					 
					$v['timeago']=timeago($v['dateline']);
					if($v['imgsdata']){
						$imgs=explode(",",$v['imgsdata']);
						$imgslist=array();
						foreach($imgs as $img){
							$imgslist[]=images_site($img);
						}
						$v['imgslist']=$imgslist;
					}
					$data[$k]=$v;
				}
			}
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
			$this->smarty->display("taoke_shaidan/my.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_taoke_shaidan")->selectRow(array("where"=>"id=".$id));
			$author=M("user")->getUser($data['userid'],"userid,nickname,user_head,follow_num,followed_num");
			$author['user_head']=images_site($author['user_head']);
			$data["timeago"]=timeago(strtotime($data["createtime"]));
			//图集
			$imgslist=array();
			if($data['imgsdata']){
				$imgs=explode(",",$data['imgsdata']);
				foreach($imgs as $img){
					$imgslist[]=images_site($img);
				}
				  
			}else{
				$imgslist=M("mod_forum_img")->select(array(
					"where"=>"objectid=".$id
				));
				if($imgslist){
					foreach($imgslist as $k=>$v){
						 
						$imgslist[$k]=$v;
					}
				}
			}
			$order=M("mod_taoke_order")->selectRow("orderno='".$data["orderno"]."'");
			if($order){
				$taoke=M("mod_taoke_searchcache")->selectRow("objectid=".$order["productid"]);
				if(!$taoke){			 
					$taoke=M("mod_taoke")->selectRow("tb_numid=".$order["productid"]);
				}
			}
			
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgslist"=>$imgslist,
				"author"=>$author,
				"order"=>$order,
				"taoke"=>$taoke
			));
			$this->smarty->display("taoke_shaidan/show.html");
		}
		public function onAdd(){
			$userid=M("login")->userid;
			$orderno=get_post("orderno","h");
			$order=M("mod_taoke_order")->selectRow("orderno='".$orderno."'");
			if(empty($order)){
				$this->goAll("订单还未生效哦",1);
			}
			$row=M("mod_taoke_shaidan")->selectRow("orderno='".$orderno."'");
			if($row){
				$this->goAll("已经晒单咯",1);
			}
			if($order["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"order"=>$order
			));
			$this->smarty->display("taoke_shaidan/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$userid=M("login")->userid;
			$data=M("mod_taoke_shaidan")->postData();
			$orderno=post("orderno","h");
			$order=M("mod_taoke_order")->selectRow("orderno='".$orderno."'");
			if(empty($order)){
				$this->goAll("订单还未生效哦",1);
			}
			$row=M("mod_taoke_shaidan")->selectRow("orderno='".$orderno."'");
			if($row){
				$this->goAll("已经晒单咯",1);
			}
			if($order["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			//处理imgsdata
			if(!empty($data["imgsdata"])){
				$ims=explode(",",$data["imgsdata"]);
				foreach($ims as $im){
					if($im!="undefined" && $im!=""){
						$imgsdata[]=$im;
					}
				}
				if(!empty($imgsdata)){					 
					$data["imgsdata"]=implode(",",$imgsdata);
				}
				
			} 
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			M("mod_taoke_shaidan")->insert($data);
			M("mod_taoke_order")->update(array(
				"issd"=>1
			),"orderno='".$data['orderno']."'");
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_taoke_shaidan")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_taoke_shaidan")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>