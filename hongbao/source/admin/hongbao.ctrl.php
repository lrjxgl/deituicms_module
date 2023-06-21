<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class hongbaoControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$where=" 1 ";
			$url="/moduleadmin.php?m=hongbao&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_hongbao")->select($option,$rscount);
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
			$this->smarty->display("hongbao/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_hongbao")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("hongbao/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");

			$data=M("mod_hongbao")->postData();
			
			if($id){
				M("mod_hongbao")->update($data,"id='$id'");
			}else{
				M("mod_hongbao")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onCopy(){
			$id=get('id','i');
			$data=M("mod_hongbao")->selectRow(array("where"=>"id={$id}"));
			$uns=array("id","iscreate","status");
			foreach($uns as $key){
				unset($data[$key]);
			}
			$data['title'].="第二期";
			$data['endtime']=time()+3600*24;
			M("mod_hongbao")->insert($data);
			$this->goAll("复制成功");	
		}
		
		public function onCreate(){
			$id=get_post("id",'i');
			$hb=M("mod_hongbao")->selectRow("id=".$id);
			if($hb['iscreate']){
				$this->goAll("你已经创建过红包了",1);
			}
			$money=$hb['total_money']-$hb['max_money'];
			$total_num=$hb['total_num']-1;
			M("mod_hongbao_item")->insert(array(
				"hbid"=>$id,
				"money"=>$hb['max_money']
			));
			$num=1;
			for($i=1;$i<$total_num;$i++){
				
				if($i/$total_num>0.8){
					$mn=$avg=$money/($total_num-$i+1);
				}elseif($i/$total_num>0.6){
					$avg=$money/($total_num-$i+1);
					$mn=$avg*1.2;
					$num++;
				}elseif($i/$total_num>0.4){
					$avg=$money/($total_num-$i);
					$mn=$avg*1.5;
					$num++;
				}elseif($i/$total_num>0.2){
					$avg=$money/($total_num-$i);
					$mn=$avg*2;
					$num++;
				}else{
					$avg=$money/($total_num-$i);
					$mn=$avg*2.5;
					$num++;
				}
				
				$money=$money-$mn; 
				M("mod_hongbao_item")->insert(array(
					"hbid"=>$id,
					"money"=>$mn
				));
			}
			$ctmoney=M("mod_hongbao_item")->selectOne(array(
				"fields"=>"sum(money) as mm",
				"where"=>"hbid=".$id
			));
			$money=$hb['total_money']-$ctmoney;
			M("mod_hongbao_item")->insert(array(
				"hbid"=>$id,
				"money"=>$money
			));
			M("mod_hongbao")->update(array("iscreate"=>1),"id=".$id);
			$this->goAll("生成成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_hongbao")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_hongbao")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>