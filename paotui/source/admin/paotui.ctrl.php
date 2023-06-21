<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class paotuiControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function status_list(){
			return array(
				0=>"未确认",
				1=>"已确认",
				2=>"配送中",
				3=>"已完成",
				8=>"已取消"
			);
		}
		public function onDefault(){
			
			
			$status_list=MM("paotui","paotui")->status_list();
			$status=get("status","i");
			switch($status){
				case 0:
				case 1:
				case 2:
				case 3:
					$where="  status=".$status;
					break;
				default:
					$where=" status<11 ";
					break;
			}
			$typelist=MM("paotui","paotui")->typelist();
			 
			$url="/moduleadmin.php?m=paotui&a=default";
			$limit=20;
			$start=get("per_page","i");
			$typeid=get("typeid","i"); 
			if($typeid){
				$where.="AND typeid=".$typeid;
			}
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" createtime DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_paotui")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					if($v["status"]==0 && $v["ispay"]==0){
						$v["status_name"]="待支付";
					}else{
						$v['status_name']=$status_list[$v['status']];
					}
					
					$v['ispay_name']=$v['ispay']==2?"已支付":"未支付";
					$v["typeid_name"]=$typelist[$v["typeid"]]["title"];
					$v["fromaddr"]=json_decode($v["fromaddr"]);
					$v["toaddr"]=json_decode($v["toaddr"]);
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"typelist"=>$typelist,
					"typeid"=>$typeid,
					"rscount"=>$rscount
				)
			);
			$this->smarty->display("paotui/index.html");
		}
		 
		
		public function onFinish(){
			$id=get_post('id',"i");
			$status=3;
			$row=M("mod_paotui")->selectRow("id=".$id);
			if($row['status']>=3){
				$this->goAll("状态修改失败",1);
			}
			M("mod_paotui")->update(array("status"=>$status),"id=$id");
			M("mod_paotui_log")->insert(array(
				"dateline"=>time(),
				"pid"=>$id,
				"content"=>"跑腿任务已完成"
			));
			$this->goall("状态修改成功",0);
		}
		
		public function onCancel(){
			$id=get_post('id',"i");
			$status=8;
			$row=M("mod_paotui")->selectRow("id=".$id);
			if($row['status']>=3){
				$this->goAll("取消失败",1);
			}
			M("mod_paotui")->update(array("status"=>$status),"id=$id");
			M("mod_paotui_log")->insert(array(
				"dateline"=>time(),
				"pid"=>$id,
				"content"=>"跑腿任务已取消"
			));
			//退还支付金额
			if($row['ispay']==2){
				M("user")->addMoney(array(
					"userid"=>$row['userid'],
					"money"=>$row['money'],
					"content"=>"跑腿任务取消了，返还[money]元。"
				));
			}
			$this->goall("状态修改成功",0);
		}
		
		
	}

?>