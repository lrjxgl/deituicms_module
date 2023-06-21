<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class xiangqin_peopleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$userid=M("login")->userid;
			$where=" status =1 ";
			$my=MM("xiangqin","xiangqin_people")->selectRow("userid=".$userid);
			if($my){
				$gender=$my["gender"]==1?2:1;
				$where.=" AND gender=".$gender;
			}
			
			$url="/module.php?m=xiangqin_people&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" updatetime DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=MM("xiangqin","xiangqin_people")->Dselect($option,$rscount);
			$ageList=[
				1=>"25岁以下",
				2=>"25-30岁",
				3=>"30-35岁",
				4=>"35-40岁",
				5=>"40岁以上"
			];
			$moneyList=array(
				1=>"5w万以下",
				2=>"5万-10万元",
				3=>"10万-15万元",
				4=>"15万-20万元",
				5=>"20万以上"
			);
			$addrList=[
				1=>["id"=>1,"title"=>"同城"],
				2=>["id"=>2,"title"=>"同地区"],
				3=>["id"=>3,"title"=>"同省"],
				4=>["id"=>4,"title"=>"外省"],
			];
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
					"ageList"=>$ageList,
					"moneyList"=>$moneyList,
					"addrList"=>$addrList
				)
			);
			$this->smarty->display("xiangqin_people/index.html");
		}
		
		public function onList(){
			$userid=M("login")->userid;
			$where=" status =1 ";
			$my=MM("xiangqin","xiangqin_people")->selectRow("userid=".$userid);
			if($my){
				$gender=$my["gender"]==1?2:1;
				$where.=" AND gender=".$gender;
			}
			$url="/module.php?m=xiangqin_people&a=list";
			$ageChoice=get("age_choice","h");
			if(!empty($ageChoice)){
				$arr=explode("-",$ageChoice);
				if(!isset($arr[1])){
					$min=intval($arr[0]);
					$min=date("Y-m-d",strtotime(" -".$min." year "));
					if($min<40){
						$where.=" AND birthday<".$min;
					}else{
						$where.=" AND birthday>=".$min;
					}
				}else{
					$min=intval($arr[0]);
					$min=date("Y-m-d",strtotime(" -".$min." year "));
					$max=intval($arr[1]);
					$min=date("Y-m-d",strtotime(" -".$max." year "));
					$where.=" AND ( birthday>=".$min." AND birthday<".$max.")";
				}
			}
			$moneyChoice=get("money_choice","h");
			if(!empty($moneyChoice)){
				$arr=explode("-",$moneyChoice);
				if(!isset($arr[1])){
					$min=intval($arr[0]);
					if($min<20){
						$where.=" AND income<".$min;
					}else{
						$where.=" AND income>=".$min;
					}
				}else{
					$min=intval($arr[0]);
					$max=intval($arr[1]);
					$where.=" AND ( income>=".$min." AND income<".$max.")";
				}
			}
			$addrid=get("addrid",'i');
			if($addrid && $my){
				if($addrid==1){
					$where.=" AND town_id=".$my["town_id"];
				}elseif($addrid==2){
					$where.=" AND city_id=".$my["city_id"];
				}elseif($addrid==3){
					$where.=" AND province_id=".$my["province_id"];
				}elseif($addrid==4){
					$where.=" AND province_id<>".$my["province_id"];
				}
			}
			
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" updatetime DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=MM("xiangqin","xiangqin_people")->Dselect($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("xiangqin_people/index.html");
		}
		
		public function onShow(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$my=M("mod_xiangqin_join")->selectRow(array("where"=>"userid=".$userid));
			if(empty($my)){
				$this->goAll("您还未加入相亲队伍，无权查看",1);
			}
			
			$id=get_post("id","i");
			if($id){
				$where="id=".$id;
			}else{
				$uid=get("userid","i");
				$where=" userid=".$uid;
			}
			$data=M("mod_xiangqin_people")->selectRow($where);
			//
			if($data["status"]!=1){
				$this->goAll("相亲信息已下架",1);
			}
			//判断关系
			$friend=M("mod_xiangqin_friend")->selectRow("userid=".$data["userid"]." AND touserid=".$userid);
			if($friend){
				$isFriend=1;
			}else{
				$isFriend=0;
			}
			$data["age"]=date("Y")-substr($data["birthday"],0,4)+1;
			$data["gender_title"]=$data["gender"]==1?'男':'女';
			$data["imgurl"]=images_site($data["imgurl"]);
			//统计
			$key="xiangqin_people_stat_".$data["userid"];
			if(!$stat=cache()->get($key)){
				$zh_num_receive=M("mod_xiangqin_zhaohu")->getCount("touserid=".$data["userid"]);
				$zh_num_receive_pass=M("mod_xiangqin_zhaohu")->getCount("touserid=".$data["userid"]." AND status=1 ");
				$zh_num_send=M("mod_xiangqin_zhaohu")->getCount("userid=".$data["userid"]);
				$zh_num_send_pass=M("mod_xiangqin_zhaohu")->getCount("userid=".$data["userid"]." AND status=1 ");
				//表白
				$bb_num_receive=M("mod_xiangqin_biaobai")->getCount("touserid=".$data["userid"]);
				$bb_num_receive_pass=M("mod_xiangqin_biaobai")->getCount("touserid=".$data["userid"]." AND status=1 ");
				$bb_num_send=M("mod_xiangqin_biaobai")->getCount("userid=".$data["userid"]);
				$bb_num_send_pass=M("mod_xiangqin_biaobai")->getCount("userid=".$data["userid"]." AND status=1 ");
				$stat=[
					"zh_num_receive"=>$zh_num_receive,
					"zh_num_receive_pass"=>$zh_num_receive_pass,
					"zh_num_send"=>$zh_num_send,
					"zh_num_send_pass"=>$zh_num_send_pass,
					"bb_num_receive"=>$bb_num_receive,
					"bb_num_receive_pass"=>$bb_num_receive_pass,
					"bb_num_send"=>$bb_num_send,
					"bb_num_send_pass"=>$bb_num_send_pass,
				];
				cache()->set($key,$stat,300);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"isFriend"=>$isFriend,
				"stat"=>$stat
			));
			$this->smarty->display("xiangqin_people/show.html");
		}
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$data=M("mod_xiangqin_people_new")->selectRow(array(
				"where"=>"userid=".$userid,
				"order"=>"id DESC"
			));
		 
			if(!empty($data)){
				$data["trueimgurl"]=images_site($data["imgurl"]);
				$dids=[$data['province_id'],$data["city_id"],$data["town_id"]];
				$dss=M("district")->dist_list(array(
					"where"=>" id in("._implode($dids).")"
				));
				$data["pct_address"]=$dss[$dids[0]].$dss[$dids[1]].$dss[$dids[2]];
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("xiangqin_people/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			 
			$data=M("mod_xiangqin_people_new")->postData();
			if(empty($data["truename"])){
				$this->goAll("请填写名字",1);
			}
			if(empty($data["telephone"])){
				$this->goAll("请填写电话",1);
			}
			if(empty($data["town_id"])){
				$this->goAll("请填写所在地",1);
			}
			if(empty($data["imgurl"])){
				$this->goAll("请上传照片",1);
			}
			$row=M("mod_xiangqin_people_new")->selectRow(array(
				"where"=>"userid=".$userid." AND status in(0,2) ",
				"order"=>"id DeSC"
			));
			$data["status"]=0;
			$data["updatetime"]=date("Y-m-d H:i:s");
			$dids=[$data['province_id'],$data["city_id"],$data["town_id"]];
			$dss=M("district")->dist_list(array(
				"where"=>" id in("._implode($dids).")"
			));
			$data["address"]=$dss[$dids[0]].$dss[$dids[1]].$dss[$dids[2]];
			if($row){
				
				M("mod_xiangqin_people_new")->update($data,"userid=".$userid);
			}else{
				$data["userid"]=$userid;
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_xiangqin_people_new")->insert($data);
				
			}
			$this->goall("保存成功");
		}
		
		 
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_xiangqin_people")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>