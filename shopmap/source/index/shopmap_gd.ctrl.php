<?php
class shopmap_gdControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
 
	
	public function onWinner(){
			$id=get("id",'i');
			$userid=M("login")->userid;
			$user=M("user")->selectRow("userid=".$userid);
			$spUser=M("mod_shopmap_user")->selectRow("userid=".$userid);
			//背景图
			$bgimg="static/gd2/jiangzhuang.png";		 
			$im=imagecreatefrompng($bgimg);
			//二维码
			$content=HTTP_HOST."/module.php?m=shopmap_apply";
			$ewmimg="attach/".md5($content).".png";
			$this->loadClass("qrcode",false,false);
			QRCODE::png($content,$ewmimg,QR_ECLEVEL_L,2);
			// Add some shadow to the text
			$font=ROOT_PATH."/static/msyh.ttf";
			$black = imagecolorallocate($im, 64, 64, 64);
			$red = imagecolorallocate($im, 255, 255, 255);
			$marginTop=230; 
			//处理换行
			
			$title=$user["nickname"]."同志:";
			imagettftext($im, 22, 0, 120, $marginTop, $black, $font, $title);
			$marginTop+=45;
			$title="荣获福鼎“互联网+”商户入网活动优秀奖，帮助".$spUser["pass_num"]."家商户入驻福鼎生活网";
			$t1=cutstr($title,38,"");
			$title=str_replace($t1,$t1."\n",$title); 
			imagettftext($im, 18, 0, 180, $marginTop, $black, $font, $title);
			//奖金
			$marginTop=384;
			$title="奖金".$spUser["money"]."元";
			imagettftext($im, 20, 0, 200, $marginTop, $black, $font, $title);
			//帮助
			$marginTop=424;
			$title="特此鼓励!";
			imagettftext($im, 20, 0, 200, $marginTop, $black, $font, $title);
			//单位
			$marginTop=394;
			$title="福鼎生活网";
			imagettftext($im, 16, 0, 400, $marginTop, $black, $font, $title);
			//日期
			$marginTop+=44;
			$title=date("Y年m月d日");
			imagettftext($im, 16, 0, 400, $marginTop, $black, $font, $title);
			//二维码
			$ewmim=imagecreatefrompng($ewmimg);
			$e=getimagesize($ewmimg);
			$marginTop=400; 
			$w=120;
			$h=120;		
			  
			imagecopyresampled($im, $ewmim, 620, $marginTop, 0, 0, $w, $h, $e[0], $e[1]);
	
			 
			
			//裁剪图像
			/*
			$im2=imagecrop($im,array(
				"x"=>0,
				"y"=>0,
				"width"=>750,
				"height"=>$marginTop+80
			)); 
			*/
			//输出图片
			header("Content-type:image/jpeg");
			imagejpeg($im,null,80);
		}
	
}