<?php
class house_stoleControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	} 
	public function onInit(){
		include_once(ROOT_PATH."api/ossapi/ossapi.php");
	}
	public function upload_oss($files){
		if(!UPLOAD_OSS) return false;
		if(empty($files)) return false;
		$arr=array("",".100x100.jpg",".small.jpg",".middle.jpg");
		foreach($arr as $a){		
			if(file_exists(ROOT_PATH.$files.$a)){
				$to=str_replace("//","/",$files.$a);
				$from=ROOT_PATH.$files.$a;
				$response = oss_upload_file(array("bucket"=>OSS_BUCKET,"to"=>$to,"from"=>$from));
				if(defined("UPLOAD_DEL") && UPLOAD_DEL){
					@unlink($from);
				}
			}
		}
	} 
	
	public function onDefault(){
		$huxing="三房二厅二卫二阳台";
		preg_match("/((一|二|三|四|五|六)(房|室))/iUs",$huxing,$a); 
		print_r($a);
		exit;
	}
	
	public function onCollect(){
		set_time_limit(0);
		$this->loadClass("stole",false,false);
		$page=20;
		for($i=$page;$i--;$i>=0){
			
			$url="http://www.fdfc.cn/cs/index.php?userid=&infotype=&dq=&fwtype=&hx=&price01=&price02=&pricetype=&fabuday=&addr=&PageNo=".$i;		
			$st=new stole();
			$st->getContent($url);
			$st->cutHtml("<div class='conbox'>");
			//获取链接
			$urls=$st->preg_all("<h2><a href=({url=[^<]*}) target=_blank class=al13>({title=.*})</a>");
		
			//http://www.fdfc.cn/cs/chushou_54902.html
			foreach($urls['url'] as $k=>$u){
				$u1="http://www.fdfc.cn/cs/".$u;
				$row=M("mod_house_stole")->selectRow("url='".$u1."'");
			 
				if(empty($row)){
					M("mod_house_stole")->insert(array(
						"url"=>$u1,
						"title"=>$urls['title'][$k]
					));
				}
			}
				 
		}
		echo "采集完成";
	}
	
	public function onStole(){
		$row=M("mod_house_stole")->selectRow(array(
			"where"=>" isstole=0",
			"order"=>"id ASC"
		));
		if(empty($row)){
			exit("采集完成");
		}
		M("mod_house_stole")->update(array(
			"isstole"=>1
		),"id=".$row['id']);
		$this->loadClass("stole",false,false);
		$st=new stole();
		$st->getContent($row['url']);
	  	
		/**
		 * 二手房数据结构
		 * description、imgurl、address、huxing、weizhi、mianji、danjia、telephone、content
		 * 
		 * **/
		$html=$st->content;
		if(empty($html)){
			exit("采集错误");
		}
		$telephone=$st->preg_one("<p class=tel>(.*) ");
		$st->cutHtml("<div class='fbanner'>",$html);
		$img=$st->preg_one("<img src='(.*)' alt=''>");
		//图片本地化
		if($img ){
			$imgurl="attach/house/ershou/".time().md5($img).".jpg";
			umkdir("attach/house/ershou/");
			 
			@file_put_contents($imgurl,file_get_contents($img));
			$imsize=@getimagesize($imgurl);
			if($imsize[0]<5||$imsize[1]<5){
				unlink($imgurl);
			}else{		
				//缩略图
				$this->loadClass("image",false,false);
				$im=new image();
				$im->makethumb($imgurl.".100x100.jpg",$imgurl,"100","100",1);
				$im->makethumb($imgurl.".small.jpg",$imgurl,"240");
				$im->makethumb($imgurl.".middle.jpg",$imgurl,"440");
				$this->upload_oss($imgurl);	
			}

		}	
		$st->cuthtml("<div class='conjg'>",$html);
		$price=$st->preg_one("<span class=\"jg\">(.*)</span>");
		$mianji=$st->preg_one("<li>房屋面积：(.*)平方米");
		$huxing=$st->preg_one("<li>房屋户型：(.*)</li>");
		$weizhi="福鼎市";
		$address="福鼎市";
		$address=$st->preg_one('<li>详细地址：<a .*class="al13">(.*)</a>');

		$huxing=strip_tags($huxing);
		$danjia=intval(intval($price)*10000/intval($mianji));	
		$content=$st->cutHtml("<div class='xxmscon'>",$html);
		//内容图片本地化
		$content=$this->remote_img("attach/house/ershou/",$content);
		
		$hxlist=M("options")->id_title(array(
				"where"=>" tablename='house_huxing'  "
		));
		preg_match("/((一|二|三|四|五|六)(房|室))/iUs",$huxing,$a); 
		if(isset($a[2])){
		 
			$huxing=0; 
			foreach($hxlist as $k=>$v){
				if(preg_match("/".$a[2]."/",$v)){
					$huxing=$k;
					break;
				}
			}
		}
		//所在区域
		$sclist=M("site_city")->id_title();
		if($sclist){
			$preg="";
			foreach($sclist as $s){
				if(preg_match("/福鼎/",$s)){
					continue;
				}
				$preg.=str_replace(array("镇","市区"),"",$s)."|";
			}
			$preg.="福鼎";
			preg_match("/(".$preg.")/",$address,$b);
			if(isset($b[0])){
				foreach($sclist as $k=>$s){
					if(preg_match("/".$b[0]."/",$s)){
						$weizhi=$k;
						break;
					}
				}
			}
		}
		M("mod_house_ershou")->insert(array(
			"title"=>$row['title'],
			"description"=>$row['title'],
			"isbuy"=>1,
			"imgurl"=>$imgurl,
			"price"=>$price,
			"danjia"=>$danjia,
			"mianji"=>$mianji,
			"huxing"=>$huxing,
			"weizhi"=>$weizhi,
			"address"=>$address,
			"telephone"=>$telephone,
			"content"=>sql(nRemoveXSS($content))
		));
		echo "<script>window.location.reload();</script>";
		echo "标题：".$row['title'];	
		echo "图片：".$imgurl;
		echo "价格".$price;
		echo "面积".$mianji;
		echo "单价：".$danjia;
		echo "户型".$huxing;
		echo "电话：".$telephone;
		echo "地址：".$address;
		echo "内容 $content";
		 
		
	}
	
	public function remote_img($dir="",$content=''){
		$content=$content?$content:$this->content;
		preg_match_all("/<img.*src=[\'\"]+(.*)[\'\"][^>]*>/iUs",$content,$arr);
		$pics=$arr[1];
		
		if(empty($pics)) return $content;
		$dir=$dir?$dir:"attach/content/".date("Y/m/");
		umkdir($dir);
		foreach($pics as $k=>$pic)
		{
			$file=$dir.md5(time().$pic).".jpg";
			file_put_contents($file,curl_get_contents($pic));
			if(UPLOAD_OSS){
				$content=str_replace($pic,images_site($file),$content);
				$this->upload_oss($file);
			}else{
				$content=str_replace($pic,"/".$file,$content);
			}
		}
		return $content;
	}
	
}
?>