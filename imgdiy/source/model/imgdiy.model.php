<?php
class imgdiyModel extends model{
	public $table="mod_imgdiy";
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	public $fonts=array(
		
		"DidotBold"=>"module/imgdiy/fonts/Didot Bold_0.ttf",
		"DidotItalic"=>"module/imgdiy/fonts/Didot Italic_0.ttf",
		"DidotHTFB06BoldItal"=>"module/imgdiy/fonts/Didot-HTF-B06-Bold-Ital_0.otf",
		"DidotHTFB06Bold"=>"module/imgdiy/fonts/Didot-HTF-B06-Bold_0.otf",
		"DidotHTFB11BoldItal"=>"module/imgdiy/fonts/Didot-HTF-B11-Bold-Ital_0.otf",
		"DidotHTFB11Bold"=>"module/imgdiy/fonts/Didot-HTF-B11-Bold_0.otf",
		"DidotHTFB16BoldItal"=>"module/imgdiy/fonts/Didot-HTF-B16-Bold-Ital_0.otf",
		"DidotHTFB16Bold"=>"module/imgdiy/fonts/Didot-HTF-B16-Bold_0.otf",
		"DidotHTFB24BoldItal"=>"module/imgdiy/fonts/Didot-HTF-B24-Bold-Ital_0.otf",
		"DidotHTFB24Bold"=>"module/imgdiy/fonts/Didot-HTF-B24-Bold_0.otf",
		"DidotHTFB42BoldItal"=>"module/imgdiy/fonts/Didot-HTF-B42-Bold-Ital_0.otf",
		"DidotHTFB42Bold"=>"module/imgdiy/fonts/Didot-HTF-B42-Bold_0.otf",
		"DidotHTFB64BoldItal"=>"module/imgdiy/fonts/Didot-HTF-B64-Bold-Ital_0.otf",
		"DidotHTFB64Bold"=>"module/imgdiy/fonts/Didot-HTF-B64-Bold_0.otf",
		"DidotHTFB96BoldItal"=>"module/imgdiy/fonts/Didot-HTF-B96-Bold-Ital_0.otf",
		"DidotHTFB96Bold"=>"module/imgdiy/fonts/Didot-HTF-B96-Bold_0.otf",
		"DidotHTFL06LightItal"=>"module/imgdiy/fonts/Didot-HTF-L06-Light-Ital_0.otf",
		"DidotHTFL06Light"=>"module/imgdiy/fonts/Didot-HTF-L06-Light_0.otf",
		"DidotHTFL11LightItal"=>"module/imgdiy/fonts/Didot-HTF-L11-Light-Ital_0.otf",
		"DidotHTFL11Light"=>"module/imgdiy/fonts/Didot-HTF-L11-Light_0.otf",
		"DidotHTFL16LightItal"=>"module/imgdiy/fonts/Didot-HTF-L16-Light-Ital_0.otf",
		"DidotHTFL16Light"=>"module/imgdiy/fonts/Didot-HTF-L16-Light_0.otf",
		"DidotHTFL24LightItal"=>"module/imgdiy/fonts/Didot-HTF-L24-Light-Ital_0.otf",
		"DidotHTFL24Light"=>"module/imgdiy/fonts/Didot-HTF-L24-Light_0.otf",
		"DidotHTFL42LightItal"=>"module/imgdiy/fonts/Didot-HTF-L42-Light-Ital_0.otf",
		"DidotHTFL42Light"=>"module/imgdiy/fonts/Didot-HTF-L42-Light_0.otf",
		"DidotHTFL64LightItal"=>"module/imgdiy/fonts/Didot-HTF-L64-Light-Ital_0.otf",
		"DidotHTFL64Light"=>"module/imgdiy/fonts/Didot-HTF-L64-Light_0.otf",
		"DidotHTFL96LightItal"=>"module/imgdiy/fonts/Didot-HTF-L96-Light-Ital_0.otf",
		"DidotHTFL96Light"=>"module/imgdiy/fonts/Didot-HTF-L96-Light_0.otf",
		"DidotHTFM06MediumItal"=>"module/imgdiy/fonts/Didot-HTF-M06-Medium-Ital_0.otf",
		"DidotHTFM11MediumItal"=>"module/imgdiy/fonts/Didot-HTF-M11-Medium-Ital_0.otf",
		"DidotHTFM11Medium"=>"module/imgdiy/fonts/Didot-HTF-M11-Medium_0.otf",
		"DidotHTFM16MediumItal"=>"module/imgdiy/fonts/Didot-HTF-M16-Medium-Ital_0.otf",
		"DidotHTFM16Medium"=>"module/imgdiy/fonts/Didot-HTF-M16-Medium_0.otf",
		"DidotHTFM24MediumItal"=>"module/imgdiy/fonts/Didot-HTF-M24-Medium-Ital_0.otf",
		"DidotHTFM24Medium"=>"module/imgdiy/fonts/Didot-HTF-M24-Medium_0.otf",
		"DidotHTFM42MediumItal"=>"module/imgdiy/fonts/Didot-HTF-M42-Medium-Ital_0.otf",
		"DidotHTFM42Medium"=>"module/imgdiy/fonts/Didot-HTF-M42-Medium_0.otf",
		"DidotHTFM64MediumItal"=>"module/imgdiy/fonts/Didot-HTF-M64-Medium-Ital_0.otf",
		"DidotHTFM64Medium"=>"module/imgdiy/fonts/Didot-HTF-M64-Medium_0.otf",
		"DidotHTFM96MediumItal"=>"module/imgdiy/fonts/Didot-HTF-M96-Medium-Ital_0.otf",
		"DidotHTFM96Medium"=>"module/imgdiy/fonts/Didot-HTF-M96-Medium_0.otf",
		"DidotLTStdBold"=>"module/imgdiy/fonts/DidotLTStd-Bold_0.otf",
		"DidotLTStdHeadline"=>"module/imgdiy/fonts/DidotLTStd-Headline_0.otf",
		"DidotLTStdItalic"=>"module/imgdiy/fonts/DidotLTStd-Italic_0.otf",
		"DidotLTStdOrnaments"=>"module/imgdiy/fonts/DidotLTStd-Ornaments_0.otf",
		"DidotLTStdRoman"=>"module/imgdiy/fonts/DidotLTStd-Roman_0.otf",
		"Didot"=>"module/imgdiy/fonts/Didot_0.ttf",
		"PingFangBold"=>"module/imgdiy/fonts/PingFang Bold_0.ttf",
		"PingFangExtraLight"=>"module/imgdiy/fonts/PingFang ExtraLight_0.ttf",
		"PingFangHeavy"=>"module/imgdiy/fonts/PingFang Heavy_0.ttf",
		"PingFangLight"=>"module/imgdiy/fonts/PingFang Light_0.ttf",
		"PingFangMedium"=>"module/imgdiy/fonts/PingFang Medium_0.ttf",
		"PingFangRegular"=>"module/imgdiy/fonts/PingFang Regular_0.ttf",
		"PingFangHKSemibold"=>"module/imgdiy/fonts/PingFangHK-Semibold_0.otf",
		"STHeitiLight"=>"module/imgdiy/fonts/STHeiti-Light_1.ttc",
		"SourceHanSansCNBoldBold"=>"module/imgdiy/fonts/Source Han Sans CN Bold Bold_0.otf",
		"SourceHanSansCNHeavy"=>"module/imgdiy/fonts/SourceHanSansCN-Heavy_0.otf",
		"SourceHanSansCNMedium"=>"module/imgdiy/fonts/SourceHanSansCN-Medium_0.otf",
		"SourceHanSansCNNormal"=>"module/imgdiy/fonts/SourceHanSansCN-Normal_0.otf",
		"SourceHanSansCNRegular"=>"module/imgdiy/fonts/SourceHanSansCN-Regular_0.otf",
		"SourceHanSerifSCBold"=>"module/imgdiy/fonts/SourceHanSerifSC-Bold_0.otf",
		"SourceHanSerifSCExtraLight"=>"module/imgdiy/fonts/SourceHanSerifSC-ExtraLight.otf",
		"SourceHanSerifSCHeavy"=>"module/imgdiy/fonts/SourceHanSerifSC-Heavy.otf",
		"SourceHanSerifSCLight"=>"module/imgdiy/fonts/SourceHanSerifSC-Light.otf",
		"TpldKhangXiDictTrial"=>"module/imgdiy/fonts/TpldKhangXiDictTrial_0.otf",
		"didothtfm06medium"=>"module/imgdiy/fonts/didot-htf-m06-medium_0.ttf",
		"mingliu"=>"module/imgdiy/fonts/mingliu.ttc",
		//"mingliub"=>"module/imgdiy/fonts/mingliub.ttc",
		"msyh"=>"module/imgdiy/fonts/msyh.ttf",
		"simhei"=>"module/imgdiy/fonts/simhei.ttf",
		"simsun"=>"module/imgdiy/fonts/simsun.ttc",
		"庞门正道标题体2.0增强版"=>"module/imgdiy/fonts/庞门正道标题体2.0增强版_0.ttf",
		"康熙字典体完整版"=>"module/imgdiy/fonts/康熙字典体完整版_1.otf",
		 
		"田氏保钓体简"=>"module/imgdiy/fonts/田氏保钓体简_0.ttf",
		"禹卫书法行书简体"=>"module/imgdiy/fonts/禹卫书法行书简体.ttf",
		"苹方黑体中粗繁"=>"module/imgdiy/fonts/苹方黑体-中粗-繁.ttf",
		"苹果黑体"=>"module/imgdiy/fonts/苹果黑体_0.ttf",

	);
	public function imgck($id){
		$rss=M("mod_imgdiy_item")->select(array(
			"where"=>" status=2 AND imgid=".$id,
			"order"=>" orderindex ASC"
		));
		$w=640;
		$h=1136;
		$img = new Imagick();
		$img->newImage($w, $h, new ImagickPixel('transparent'));
		$draw = new ImagickDraw();
		$draw->setFillColor('#777bb4');
		/* Set ellipse dimensions */
		$draw->ellipse($width / 2, $height / 2, $width / 2, $height / 2, 0, 360);
		/* Draw ellipse onto the canvas */
		$img->drawImage($draw);
		$img->annotateImage($draw, 0, -10, 0, 'php');
		$img->setImageFormat('png');
		header('Content-Type: image/png');
		echo $img;
	}
	public function ch2arr($str)
	{
		$length = mb_strlen($str, 'utf-8');
		$array = [];
		for ($i=0; $i<$length; $i++)  
			$array[] = mb_substr($str, $i, 1, 'utf-8');    
		return $array;
	}
	public function img($id,$post=array(),$test=1){
		 
		$imgdiy=$this->selectRow("id=".$id);
		$bgimg=$imgdiy['bgimg'];
		if(!empty($post)){
			$rss=$post['itemList'];
			$bgimg=$post['bgimg'];
		 
		}else{
			$rss=M("mod_imgdiy_item")->select(array(
				"where"=>" status=2 AND imgid=".$id,
				"order"=>" orderindex ASC"
			));
		}
		
		closeDb();
		$w=$imgdiy['width'];
		$h=$imgdiy['height'];
		$im=imagecreatetruecolor($w,$h);
		//处理背景
		$pic=imagecreatefromstring(file_get_contents(images_site($bgimg)));
		imagecopyresampled($im, $pic, 0, 0, 0, 0, $w, $h, $w, $h);
		$font="module/imgdiy/fonts/msyh.ttf";
		$white = imagecolorallocate($im, 255, 255, 255);
		//$grey = imagecolorallocate($im, 0, 0, 0);
		//imagefill($im,0,0,$white);
		//背景图
		 
		foreach($rss as $rs){
			 
			if($rs['type']=='text'){
				
				$rgb=hex2rgb($rs['color']);				 
				$color= imagecolorallocate($im, $rgb['r'], $rgb['g'], $rgb['b']);
				if($rs['font']){
					$font=iconv("utf-8","gb2312",$rs['font']);
				} 
				
				if(!file_exists($font)){
					exit($font);
				}
				$word=$rs['word'];
				if($rs['direction']){
					$wd=$this->ch2arr($word);
					$word="";
					foreach($wd as $wdk){
						$word.=$wdk."\n";
					}
				}
				//$textBox=imagettfbbox($rs['size'],$rs['angle'],$font, $word);
				//$word="测\n试\n文\n档";
				if($rs['direction']){
					
				}else{
				//	$rs['y']+=$rs['y']/2;
				}
				imagettftext($im, $rs['size'], $rs['angle'], $rs['x'], $rs['y'], $color, $font, $word);
				
			}elseif($rs['type']=='rectangle'){
				$rgb=hex2rgb($rs['color']);				 
				$color= imagecolorallocate($im, $rgb['r'], $rgb['g'], $rgb['b']);
				imagerectangle($im, $rs['x'], $rs['y'], $rs['w']+$rs['x'],$rs['h']+$rs['y'], $color);
			}else{
				$pic=imagecreatefromstring(file_get_contents(images_site($rs['imgurl'])));
				$pw=imagesx($pic);
				$ph=imagesy($pic);
				$nh=$w*$ph/$pw;
				imagecopyresampled($im, $pic, $rs['x'], $rs['y'], 0, 0, $rs['w'], $rs['h'], $pw, $ph);
				imagedestroy($pic);
			}
			
		
		}
		if($test==1){
			$nw=640;
			$nh=$h*$nw/$w;
			$im=imagescale($im,$nw,$nh);
			header("Content-type:image/jpeg;");
			imagejpeg($im,NULL,50);
			imagedestroy($im);
		}elseif($test==3){
			$dir="attach/imgdiy/";
			umkdir($dir);
			$file=$dir.$id.".jpg";
			$nw=640;
			$nh=$h*$nw/$w;
			$im=imagescale($im,$nw,$nh);
			imagejpeg($im,$file,50); 
			imagedestroy($im);
		}else{
			header("Content-type:image/png;");
			imagepng($im);
			
			imagedestroy($im);
		}
		
		
	}
	
	public function Dselect($option=array(),&$rscount=true){
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v['imgurl']);
				$data[$k]=$v;
			}
			return $data;
		}
	} 
	
}
?>