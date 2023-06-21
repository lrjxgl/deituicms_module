<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cfdControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) ";
			$type=get("type","h");
			$url="/module.php?m=cfd&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			
			switch($type){
				case "new":
					$where=" status=0 ";
					$typename="待审核";
					break;
				case "forbid":
					$where=" status=2 ";
					$typename="已禁止";
					break;
				case "pass":
					$where=" status=1 ";
					$typename="已通过";
					break;
				default:
					$typename="全部";
					break;
			}
			
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" cfdid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cfd")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
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
					"type"=>$type,
					"typename"=>$typename
				)
			);
			$this->smarty->display("cfd/index.html");
		}
		
		public function onAdd(){
			$cfdid=get_post("cfdid","i");
			if($cfdid){
				$data=M("mod_cfd")->selectRow(array("where"=>"cfdid={$cfdid}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("cfd/add.html");
		}
		
		public function onSave(){
			
			$cfdid=get_post("cfdid","i");

			$data=M("mod_cfd")->postData();
			$data['endtime']=date("Y-m-d H:i:s",$data['endtime']);
			if($cfdid){
				M("mod_cfd")->update($data,"cfdid='$cfdid'");
			}else{
				M("mod_cfd")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$cfdid=get_post('cfdid',"i");
			$row=M("mod_cfd")->selectRow("cfdid=".$cfdid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_cfd")->update(array(
				"status"=>$status
			),"cfdid=".$cfdid);
			$this->goall("保存成功",0,$status);
		}
		
		public function onDelete(){
			$cfdid=get_post('cfdid',"i");
			M("mod_cfd")->update(array("status"=>11),"cfdid=$cfdid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>