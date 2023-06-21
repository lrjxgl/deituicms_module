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
		
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		
		public function onDefault(){
			$where=" status=2 ";
			$url="/moduleadmin.php?m=ttcj&a=default";
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
		
		public function onAdd(){
			$cjid=get_post("cjid","i");
			if($cjid){
				$data=M("mod_ttcj")->selectRow(array("where"=>"cjid={$cjid}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("ttcj/add.html");
		}
		
		public function onSave(){
			
			$cjid=get_post("cjid","i");
			$endtime=post("endtime",'h');
			$data=M("mod_ttcj")->postData();
			$data['endtime']=$endtime;
			if($cjid){
				M("mod_ttcj")->update($data,"cjid='$cjid'");
			}else{
				M("mod_ttcj")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$cjid=get_post('cjid',"i");
			$status=get_post("status","i");
			M("mod_ttcj")->update(array("status"=>$status),"cjid=$cjid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$cjid=get_post('cjid',"i");
			M("mod_ttcj")->update(array("status"=>11),"cjid=$cjid");
			$this->goAll("删除成功");
			 
		}
		
		public function onOpen(){
			$cjid=get_post('cjid',"i");
			$row=M("mod_ttcj")->selectRow(array(
				"where"=>" cjid=".$cjid." AND status=2 AND isopen=0 AND endtime<'".date("Y-m-d H:i:s")."' "
			));
			if(empty($row)){
				$this->goAll("无开奖计划",1);
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
				$this->goAll("无人获奖",1);
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
			$this->goAll("开奖成功");
		}
		
	}

?>