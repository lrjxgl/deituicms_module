<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fxa_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onInit(){
			if(isset($_GET["icode"])){
				$_SESSION["ssIcode"]=get("icode","i");
			}
			/*
			if(!isset($_SESSION["ssuser"]) || empty($_SESSION["ssuser"])){
				
				if(INWEIXIN){
					$backurl=urlencode(HTTP_HOST.$_SERVER["REQUEST_URI"]);
					header("Location: /index.php?m=open_weixin&a=Geturl&backurl=".$backurl);
					exit;
				}else{
					header("Location: /index.php?m=login");
					exit;
				}
				
			}*/
		}
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fxa_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fxa_product")->select($option,$rscount);
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
			$this->smarty->display("fxa_product/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fxa_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fxa_product")->select($option,$rscount);
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
			$this->smarty->display("fxa_product/index.html");
		}
		
		public function onShow(){
			$isLogin=true;
			if(!isset($_SESSION["ssuser"]) || empty($_SESSION["ssuser"])){			
				if(INWEIXIN){
					$backurl=urlencode(HTTP_HOST.$_SERVER["REQUEST_URI"]);
					header("Location: /index.php?m=open_weixin&a=Geturl&backurl=".$backurl);
					exit;
				}
				$isLogin=false;
			}
			$userid=M("login")->userid;
			$id=get_post("id","i");
			setcookie("ck_fxa_id",$id,time()+3600*24*30,"/",DOMAIN);
			$data=M("mod_fxa_product")->selectRow(array("where"=>"id=".$id));
			if(empty($data)){
				$this->goAll("产品已下架",1);
			}
			$data["imgurl"]=images_site($data["imgurl"]);
			$seo=array(
				"title"=>$data["title"],
				"description"=>$data["description"]
			);
			$imgsdata=array();
			if($data["imgsdata"]){
				$ims=explode(",",$data["imgsdata"]);				
				foreach($ims as $im){
					$imgsdata[]=images_site($im);
				}			
			}
			$shareList=MM("fxa","fxa_ushare")->Dselect(array(
				"where"=>"productid=".$id,
				"order"=>"money DESC"
			));
			//检测是否可购买
			$canBuy=1;
			if(empty($data) || $data["status"]!=1 || $data["etime"]<time()){
				$canBuy=0;
			}
			//更多产品
			$list=MM("fxa","fxa_product")->Dselect(array(
				"where"=>" status=1  AND (id>".$id." or id<".$id.") ",
				"order"=>" id DESC",
				"limit"=>12
			));
			$shareImg="/module.php?m=fxa_product&a=shareimg&id=".$id;
			//
			$lastAddr=M("user_lastaddr")->selectRow("userid=".$userid);
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata,
				"shareList"=>$shareList,
				"canBuy"=>$canBuy,
				"list"=>$list,
				"shareImg"=>$shareImg,
				"isLogin"=>$isLogin,
				"lastAddr"=>$lastAddr,
				"seo"=>$seo
			));
			$tpl="fxa_product/cha/show.html";
			if(!empty($data["tpl"])){
				$tpl=$data["tpl"];
			}
			$this->smarty->display($tpl);
		}
		
		public function onShare(){
			$id=get_post("id","i");
			$data=M("mod_fxa_product")->selectRow(array("where"=>"id=".$id));
			if(empty($data)){
				$this->goAll("产品已下架",1);
			}
			 
			$shareImg="/module.php?m=fxa_product&a=shareimg&id=".$id;
			$this->smarty->assign(array(
				"shareImg"=>$shareImg,
				"data"=>$data
			));
			$this->smarty->display("fxa_product/share.html");
		}
		
		public function onShareImg(){
			$userid=M("login")->userid;
			$user=M("user")->getUser($userid);
			$id=get_post("id","i");
			$data=M("mod_fxa_product")->selectRow(array("where"=>"id=".$id));
			if(empty($data)){
				$this->goAll("产品已下架",1);
			}
			ob_start();
			$content=HTTP_HOST."/module.php?m=fxa_product&a=show&id=".$id."&icode=".$userid."&invite_userid=".$userid;
			$this->loadClass("qrcode",false,false);
			QRCODE::png($content,false,QR_ECLEVEL_L,6);
			$ewmdata=ob_get_contents();		
			ob_end_clean();
			$ewm=imagecreatefromString($ewmdata);
			$ewmx=imagesx($ewm);
			$ewmy=imagesy($ewm);
			header("Content-type:image/jpg");
			$im=imagecreatetruecolor(750,1331);
			//创建半透明背景
			$whitebg = imagecolorallocatealpha($im,250,250,250,0);
			//imagefill($im, 0, 0, $white);
			imagefilledrectangle($im,0,0,750,1331,$whitebg);
			
			
			$im2=imagecreatefromString(file_get_contents(images_site($data["imgurl"])));
			$im2X=imagesx($im2);
			$im2Y=imagesy($im2);
			 
			imagecopymerge($im,$im2,0,0,0,0,$im2X,$im2Y,100);
			$imageHeight=$im2Y+20;
			$imageHeight=min($imageHeight,1000);
			  
			//创建半透明背景
			$whitebgx = imagecolorallocatealpha($im,250,250,250,90);
			//imagefill($im, 0, 0, $white);
			imagefilledrectangle($im,25,$imageHeight,725,$imageHeight+210,$whitebgx);
			 
			//合并二维码
			$imageHeight+=20;
			imagecopyresampled($im,$ewm,35,$imageHeight,0,0,175,175,$ewmx,$ewmy);		
			
			//创建头像昵称
			$uhead=imagecreatefromString(file_get_contents(images_site($user["user_head"].".100x100.jpg")));
			$uheadx=imagesx($uhead);
			$uheady=imagesy($uhead);
			imagecopyresampled($im,$uhead,225,$imageHeight+20,0,0,50,50,$uheadx,$uheady);
			
			//文字
			$font=ROOT_PATH."/static/msyh.ttf";
			$black = imagecolorallocate($im, 32, 32, 32);
			imagettftext($im, 16, 0, 285, $imageHeight+50, $black, $font, "我是".$user["nickname"]);
			imagettftext($im, 16, 0, 225, $imageHeight+100, $black, $font, "我给您推荐一款优惠商品");
			imagettftext($im, 16, 0, 225, $imageHeight+140, $black, $font, "快点来领取吧");
			$imageHeight+=195;
			//裁剪图片
			if($imageHeight<1331){
				$im=imagecrop($im, ['x' => 0, 'y' => 0, 'width' => 750, 'height' => $imageHeight+30]);
			}
			imagejpeg($im);
		}
		
		public function onOrder(){
			$productid=get("productid","i");
			$list=MM("fxa","fxa_order")->Dselect(array(
				"where"=>"productid=".$productid." AND ispay=1"
			));
			$this->smarty->goAssign(array(
				"list"=>$list
			));
		}
	}

?>