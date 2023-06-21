<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cy2c_planControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=cy2c_plan";
			$type=get("type",'h');
			$url.="&type=".$type;
			switch($type){
				case "success":
					$where.=" AND status=1 ";
					break;
				case "cancel":
					$where.=" AND status=2 ";
					break;
				default:
					$where.=" AND status=0 ";
					break;
			}
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cy2c_plan")->select($option,$rscount);
			if($data){
				foreach($data as $v){
				
					$placeids[]=$v["placeid"];
				}
				
				$places=MM("cy2c","cy2c_place")->getListByIds($placeids);
				foreach($data as $k=>$v){
					$v["placeid_title"]=$places[$v["placeid"]]["title"];
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
			$this->smarty->display("cy2c_plan/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_cy2c_plan")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("cy2c_plan/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_cy2c_plan")->postData();
			 
			$data["createtime"]=date("Y-m-d H:i:s");
			if($id){
				M("mod_cy2c_plan")->update($data,"id='$id'");
			}else{
				M("mod_cy2c_plan")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onConfirm(){
			$id=get_post('id',"i");
			$row=M("mod_cy2c_plan")->selectRow("id=".$id);
			 
			if($row["status"]!=0){
				$this->goAll("已经处理过了",1);
			}
			M("mod_cy2c_plan")->update(array("status"=>1),"id=$id");
			$this->goall("确认成功");
		}
		
		public function onCancel(){
			$id=get_post('id',"i");
			$row=M("mod_cy2c_plan")->selectRow("id=".$id);
	 
			 if($row["status"]!=0){
				 $this->goAll("已经处理过了",1);
			 }
			M("mod_cy2c_plan")->update(array("status"=>2),"id=$id");
			$this->goall("取消成功");
		}
		
		public function onChoice(){
			$num=get("num","i");
			$list=M("mod_cy2c_place")->select(array(
				"where"=>" status=1 AND min_num<=".$num." AND max_num>=".$num." "
			));
			$planList=M("mod_cy2c_plan")->select(array(
				"where"=>" status=0 ",
				"fields"=>"placeid,plantime",
				"order"=>"plantime ASC"
			));
			$plans=array();
			if($planList){
				foreach($planList as $v){
					$plans[$v["placeid"]][]=$v;
				}				
			}
			if($list){
				foreach($list as $k=>$v){
					$v["plan"]=$plans[$v["placeid"]];
					$list[$k]=$v;
				}
			}
			$this->goAll("success",0,array(
				"list"=>$list
			));
		}
		
		
	}

?>