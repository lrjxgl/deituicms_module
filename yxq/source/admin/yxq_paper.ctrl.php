<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class yxq_paperControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h");
			switch($type){
				case "new":
					$where=" status=0 ";
					$type_name="待审核";
					break;
				default:
					$where=" status in(0,1,2)";
					$type_name="全部";
					break;
			}
			
			$url="/moduleadmin.php?m=yxq_paper&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=M("mod_yxq_paper")->select($option,$rscount);
			if(!empty($list)){
				foreach($list as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($list as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$imgList=[];
					$e=explode(",",$v["imgsdata"]);
					foreach($e as $img){
						$imgList[]=images_site($img);
					}
					$v["imgList"]=$imgList;
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
					"type_name"=>$type_name
				)
			);
			$this->smarty->display("yxq_paper/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_yxq_paper")->selectRow("id=".$id);
			$status=2;
			if($row["status"]!=1){
				$status=1;
			}
			M("mod_yxq_paper")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_yxq_paper")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>