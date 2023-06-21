<?php
	/*Author:雷日锦*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pdd_couponControl extends skymvc{
		public $shopid;
		public $shop;
		public function __construct(){
			parent::__construct(); 
		}
		
		public function onDefault(){
			$where=" shopid=".SHOPID;
			$url="/moduleshop.php?m=pdd_coupon&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pdd_coupon")->select($option,$rscount);
		 	if($data){
		 		foreach($data as $k=>$v){
		 			$v['imgurl']=images_site($v['imgurl']);
		 			$v['end_day']=date("Y-m-d",strtotime($v['end_time']));
		 			$data[$k]=$v;
		 		}
		 	}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
	 		$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goAssign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page
				)
			);
			$this->smarty->display("pdd_coupon/index.html");
		}
		
 
		
		 
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_pdd_coupon")->selectRow(array("where"=>"id={$id}"));
				if($data['shopid']!=SHOPID ){
					$this->goAll("暂无权限");
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("pdd_coupon/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");
			$data=M("mod_pdd_coupon")->postData();
			$data['shopid']=SHOPID;

			if($id){
				$row=M("mod_pdd_coupon")->selectRow(array("where"=>"id={$id}"));
				if($row['shopid']!=SHOPID ){
					$this->goAll("暂无权限");
				}
				M("mod_pdd_coupon")->update($data,"id='$id'");
			}else{
				 
				$data["dateline"]=time();
				M("mod_pdd_coupon")->insert($data);
			}
			
			$this->goAll("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_pdd_coupon")->update(array("status"=>$status),"id=$id");
			exit(json_encode(array("error"=>0,"message"=>"状态修改成功")));
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_pdd_coupon")->update(array("status"=>99),"id={$id}");
			exit(json_encode(array("error"=>0,"message"=>"删除成功")));
		}
		
		public function onSend(){
			$id=get_post('id','i');
			$userid=get_post('userid','i');
			M("mod_pdd_coupon_user")->insert(array(
				"coupon_id"=>$id,
				"userid"=>$userid,
				"dateline"=>time()
			));
			$this->goall("赠送成功！");
		}
		
		public function onUser(){
			$start=get('per_page','i');
			$limit=20;
			$where=" 1=1 ";
			$url="/moduleshop.php?m=pdd_coupon&a=user";
			$option=array(
				"where"=>$where,
				"start"=>$start,
				"limit"=>$limit
			);
			$rscount=true;
			$data=M("mod_pdd_coupon_user")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$uids[]=$v['userid'];
					$cids[]=$v['coupon_id'];
				}
				$uids && $us=M("user")->getUserByIds($uids);
				$cids && $cos=M("mod_pdd_coupon")->getByIds($cids);
				foreach($data as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=$us[$v['userid']]['user_head'];
					$v['title']=$cos[$v['coupon_id']]['title'];
					$v['end_time']=$cos[$v['coupon_id']]['end_time'];
					$v['money']=$cos[$v['coupon_id']]['money'];
					$v['type_id']=$cos[$v['coupon_id']]['type_id'];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->assign(array(
				"data"=>$data,
				"rscount"=>$rscount,
				"pagelist"=>$pagelist
			));
			$this->smarty->display("coupon/user.html");
		}
		
		public function onuserDelete(){
			$id=get_post('id',"i");
			M("mod_pdd_coupon_user")->delete("id={$id}");
			exit(json_encode(array("error"=>0,"message"=>"删除成功")));
		}
		
	}

?>