<?php
class indexControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$catid=1; 
		$data=M("mod_imgdiy")->select(array(
			"where"=>" status=1 AND catid=".$catid,
			"limit"=>12
		));
		
		if($data){
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v['imgurl']);
				$data[$k]=$v;
			}
		}
		$catlist=MM("imgdiy","imgdiy_category")->select(array(
			"where"=>" status=1 ",
			"order"=>" orderindex ASC"
		));
		if($catlist){
			foreach($catlist as $k=>$v){
				if($catid==$v['catid']){
					$v['isActive']=1;
				}else{
					$v['isActive']=0;
				}
				$catlist[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"catlist"=>$catlist
		));
		$this->smarty->display("imgdiy/index.html"); 
	}
	public function onList(){
		$catid=get("catid",'i');
		$data=M("mod_imgdiy")->select(array(
			"where"=>" status=1 AND catid=".$catid
		));
		 
		if($data){
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v['imgurl']);
				
				$data[$k]=$v;
			}
		}
		$catlist=MM("imgdiy","imgdiy_category")->select(array(
			"where"=>" status=1 ",
			"order"=>" orderindex ASC"
		));
		if($catlist){
			foreach($catlist as $k=>$v){
				if($catid==$v['catid']){
					$v['isActive']=1;
				}else{
					$v['isActive']=0;
				}
				$catlist[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"catlist"=>$catlist
		));
		$this->smarty->display("imgdiy/list.html");
	}
	public function onDesign(){
		
		$imgid=get("imgid",'i');
		$data=M("mod_imgdiy")->selectRow("id=".$imgid);
		$data['imgurl']=images_site($data['imgurl']);
		$data['bgimg']=images_site($data['bgimg']);
		$data['phpimgurl']=HTTP_HOST.$data['phpimgurl'];
		$bgList=M("mod_imgdiy_bgimg")->select(array(
			"where"=>" catid=".$data['catid']." AND width=".$data['width']." AND height=".$data['height']." AND status=1 "
		));
		if($bgList){
			foreach($bgList as $k=>$v){
				$v['trueimgurl']=images_site($v['imgurl']);
				$bgList[$k]=$v;
			}
		}
		$artItems=M("mod_imgdiy_item")->select(array(
			"where"=>" imgid=".$imgid." AND status=2 AND type='text'",
			"order"=>" orderindex ASC"
		));
		$picItems=M("mod_imgdiy_item")->select(array(
			"where"=>" imgid=".$imgid." AND status=2 AND type='img'",
			"order"=>" orderindex ASC"
		));
		$itemList=M("mod_imgdiy_item")->select(array(
			"where"=>" imgid=".$imgid." AND status=2 ",
			"order"=>" orderindex ASC"
		));
		
		$this->smarty->goAssign(array(
			"artItems"=>$artItems,
			"picItems"=>$picItems,
			"itemList"=>$itemList,
			"bgList"=>$bgList,
			"data"=>$data,
			 
			 
		));
		$this->smarty->display("imgdiy/design.html");
	}
	
	
	
	public function onTest(){
		$imgid=3;
		$rss=M("mod_imgdiy_item")->select(array(
			"where"=>" imgid=".$imgid." AND status=2",
			"order"=>" orderindex ASC"
		));
		$this->smarty->goAssign(array(
			"imgid"=>$imgid,
			"itemList"=>$rss
		));
		$this->smarty->display("imgdiy/test.html");
	}
	public function onImg(){
		$id=get("id","i");
		MM("imgdiy","imgdiy")->img($id);		
	}
	
	public function onImgPost(){
	 
		$itemList= stripslashes_deep($_POST['itemList']);
		$arr=json_decode($itemList,true);
		$md5=md5($_POST['itemList'].$_POST['bgimg']);
		$key="imgdiyPost".$md5.time();
		$data=array(
			"itemList"=>$arr,
			"bgimg"=>$_POST['bgimg']
		);
		cache()->set($key,$data,3600);
		$redata=array(
			"key"=>$key
		);
		$this->goAll("success",0,$redata);
		//MM("imgdiy","imgdiy")->img($id,$data);
	}
	
	public function onMd5(){
		$key=get("key",'h');
		$data=cache()->get($key);
		$id=get("id",'i');
	 
		MM("imgdiy","imgdiy")->img($id,$data);
	}
	public function onDown(){
		$key=get("key",'h');
		$data=cache()->get($key);
		$id=get("id",'i');
		
		MM("imgdiy","imgdiy")->img($id,$data,0);
	}
	
}