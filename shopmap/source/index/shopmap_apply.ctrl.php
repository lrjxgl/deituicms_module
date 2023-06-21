<?php
class shopmap_applyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		$userid=M("login")->userid;
		if(!$userid){
			if(INWEIXIN){
				$backurl="/module.php?m=shopmap_apply";
				header("Location: /index.php?m=open_weixin&a=geturl&backurl=".urlencode($backurl));
				exit;
			}else{
				M("login")->checkLogin();
			}
		}
	}
	public function onDefault(){
		$userid=M("login")->userid;
	 
		$spUser=M("mod_shopmap_user")->selectRow("userid=".$userid);
		//新店申请
		$shopList=M("mod_shopmap_apply")->select(array(
			"order"=>"id DESC",
			"fields"=>"id,userid,title,address,description,imgurl,hbmoney",
			"limit"=>10,
			"where"=>" status=1 "
		));
		if($shopList){
			foreach($shopList as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($shopList as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=images_site($us[$v["userid"]]["user_head"]);
				$shopList[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"spUser"=>$spUser,
			"shopList"=>$shopList
		));
		$this->smarty->display("shopmap_apply/index.html");
	}
	
	public function onPaihang(){
		$userid=M("login")->userid;
		$userList=M("mod_shopmap_user")->select(array(
			"order"=>"money DESC",
			"limit"=>100
		));
		$paihang="100+";
		if($userList){
			foreach($userList as $k=>$v){
				$uids[]=$v["userid"];
				if($v["userid"]==$userid){
					$paihang= $k+1;
				}
			}
			$us=M("user")->getUserByIds($uids);
			foreach($userList as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=images_site($us[$v["userid"]]["user_head"]);
				$userList[$k]=$v;
			}
		}
		
		$user=M("user")->getUser($userid);
		$spUser=M("mod_shopmap_user")->selectRow("userid=".$userid);
		$this->smarty->goAssign(array(
			"userList"=>$userList,
			"user"=>$user,
			"spUser"=>$spUser,
			"paihang"=>$paihang
		));
		$this->smarty->display("shopmap_apply/paihang.html");
	}
	
	public function onNear(){
		$gps=gps_get();
		$lat=$gps['lat'];
		$lng=$gps['lng'];
		$meter=0.00001*1.1;//1米以内
		$meter=isset($_GET['mi'])?$meter*intval($_GET['mi']):$meter*50000;
		$miarr=array();
	 
		   
		if($lat>0)
		{				
			$ilng=$lng+$meter;
			$mlng=$lng-$meter;
			$ilat=$lat+$meter;
			$mlat=$lat-$meter;
			$pagesize=20;
			$start=get('per_page','i');
			 
								
			$option=array(
				"where"=>"(lng<'$ilng' AND  lng>'$mlng' AND  lat>'$mlat' AND  lat<'$ilat')  AND status in(0,1)"
			);
								
			$arr=M('mod_shopmap_apply')->select($option);	
			if($arr){ 
				foreach($arr as $k=>$v){
					 $distance[$k]=$v['distance']=distanceByLnglat($lng,$lat,$v['lng'],$v['lat']);
					$shopids[$k]=$v['shopid'];
					$v["imgurl"]=images_site($v["imgurl"]);
					 $arr[$k]=$v;
										
				}
				  
				array_multisort ( $distance ,  SORT_ASC ,  $shopids ,  SORT_ASC ,  $arr );

			}
			
		}
		$this->smarty->goAssign(array(
				"list"=>$arr,
				"rscount"=>$rscount,
				"pagelist"=>$pagelist
		));
		$this->smarty->display("shopmap_apply/near.html");
	}
	
	public function onMy(){
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		$spUser=M("mod_shopmap_user")->selectRow("userid=".$userid);
		//新店申请
		$shopList=M("mod_shopmap_apply")->select(array(
			"order"=>"id DESC",
			"fields"=>"id,title,address,description,imgurl",
			"limit"=>100,
			"where"=>" userid=".$userid
		));
		if($shopList){
			foreach($shopList as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$shopList[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"spUser"=>$spUser,
			"user"=>$user,
			"shopList"=>$shopList
		));
		$this->smarty->display("shopmap_apply/my.html");
	}
	
	public function onAdd(){
		$this->smarty->display("shopmap_apply/add.html");
	}
	
	public function onSave(){
		$userid=M("login")->userid;
		$data=M("mod_shopmap_apply")->postData();
		if($data["lat"]==0){
			$this->goAll("位置信息有误",1);
		}
		if(empty($data["title"])){
			$this->goAll("商家名称不能为空",1);
		}
		if(empty($data["address"])){
			$this->goAll("商家地址不能为空",1);
		}
		if(empty($data["imgurl"])){
			$this->goAll("商家门面照不能为空",1);
		}
		if(empty($data["description"])){
			$this->goAll("商家简介不能为空",1);
		}
		
		$data["userid"]=$userid;
		$data["createtime"]=date("Y-m-d H:i:s");
		M("mod_shopmap_apply")->insert($data);
		$spUser=M("mod_shopmap_user")->selectRow("userid=".$userid);
		if($spUser){
			M("mod_shopmap_user")->update(array(
				"apply_num"=>$spUser["apply_num"]+1
			),"id=".$spUser["id"]);
		}else{
			M("mod_shopmap_user")->insert(array(
				"createtime"=>date("Y-m-d H:i:s"),
				"userid"=>$userid,
				"apply_num"=>1
			));
		}
		$this->goAll("登记成功，请等待审核");
	}
	
}
?>