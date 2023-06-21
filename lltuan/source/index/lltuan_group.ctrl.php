<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class lltuan_groupControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=lltuan_group&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("lltuan","lltuan_group")->Dselect($option,$rscount);
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
			$this->smarty->display("lltuan_group/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=lltuan_group&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("lltuan","lltuan_group")->Dselect($option,$rscount);
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
			$this->smarty->display("lltuan_group/index.html");
		}
		
		public function onShow(){
			$gid=get_post("gid","i");
			$data=M("mod_lltuan_group")->selectRow(array("where"=>"gid=".$gid));
			$data["imgurl"]=images_site($data["imgurl"]);
			$product=M("mod_lltuan_product")->selectRow("productid=".$data["productid"]);
			$setList=json_decode($product["setdata"],true);
			$ewm="/module.php?m=lltuan_group&a=sharepic&gid=".$gid;
			 
			$this->smarty->goassign(array(
				"data"=>$data,
				"setList"=>$setList,
				"ewm"=>$ewm
			));
			$this->smarty->display("lltuan_group/show.html");
		}
		public function onAdd(){
			$gid=get_post("gid","i");
			if($gid){
				$data=M("mod_lltuan_group")->selectRow(array("where"=>"gid=".$gid));
				
			}
			$placeList=MM("lltuan","lltuan_place")->select(array(
				"where"=>" status=1 "
			));
			$proList=MM("lltuan","lltuan_product")->select(array(
				"where"=>" status=1 "
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"placeList"=>$placeList,
				"proList"=>$proList
			));
			$this->smarty->display("lltuan_group/add.html");
		}
		
		public function onSave(){
			$gid=get_post("gid","i");
			$data=M("mod_lltuan_group")->postData();
			if($gid){
				M("mod_lltuan_group")->update($data,"gid=".$gid);
			}else{
				M("mod_lltuan_group")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$gid=get_post('gid',"i");
			$row=M("mod_lltuan_group")->selectRow("gid=".$gid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_lltuan_group")->update(array("status"=>$status),"gid=".$gid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onSharePic(){
			session_write_close();
			$gid=get("gid","i");
			$group=M("mod_lltuan_group")->selectRow("gid=".$gid);
			$sharePic="attach/lltuan_group/".$gid.".jpg";
			umkdir("attach/lltuan_group");
			if(file_exists($sharePic)){
				header("Location:".$sharePic);
				exit;
			}
			$im=imagecreatetruecolor(750,1120);
			$bg = imagecolorallocate($im,250,250,250);
			imagefill($im, 0, 0, $bg);
			$white= imagecolorallocate($im,255,255,255);
			//产品主图
			$proimg=images_site($group["imgurl"]);
			$title=$group["title"];
			$pim=imagecreatefromString(file_get_contents($proimg));
			
			$w=750;
			$h=750;		
			$e=getimagesize($proimg);
			$h=$w*$e[1]/$e[0];
			$h=min(750,$h);
			 
			imagecopyresampled($im, $pim, 0, 0, 0, 0, $w, $h, $e[0], $e[1]);
			$marginTop=$h+20; 
			//写入产品标题
			// Add some shadow to the text
			$font=ROOT_PATH."/static/msyh.ttf";
			$black = imagecolorallocate($im, 32, 32, 32);
			$red = imagecolorallocate($im, 193, 39, 37);
			//$marginTop+=40;
			 
			//处理标题换行
			$ntt=""; 
			$len=mb_strlen( $title); 
		
			for($i=0;$i<$len;$i++){
				$ntt.=mb_substr($title,$i,1);
				if($i==18){
					$ntt.="\n";
				}
			}
			$title=$ntt;
			$marginTop+=42;
			imagettftext($im, 22, 0, 156, $marginTop, $black, $font, $title);
			$marginTop+=32;
			//加二维码
			$url=HTTP_HOST."/module.php?m=lltuan_group&a=show&gid=".$gid;
			$ewm=HTTP_HOST."/index.php?m=qrcode&content=".urlencode($url);
			$eIm=imagecreatefromString(file_get_contents($ewm));
			$w=260;
			$h=260;		
			$e=getimagesize($ewm);
			$h=$w*$e[1]/$e[0];
			$h=min(260,$h);
			imagecopyresampled($im, $eIm, 230, $marginTop, 0, 0, $w, $h, $e[0], $e[1]);
			$marginTop+=300;
			//拼的越多越优惠
			$title="拼的越多   越优惠";
			imagettftext($im, 18, 0, 256, $marginTop, $black, $font, $title);
			$marginTop+=60;
			$title="长按图片识别二维码 参加拼团";
			imagettftext($im, 18, 0, 186, $marginTop, $black, $font, $title);
			$marginTop+=30;
			//裁剪图像
			$im2=imagecrop($im,array(
				"x"=>0,
				"y"=>0,
				"width"=>750,
				"height"=>$marginTop+100
			)); 
			//输出图片
			header("Content-type:image/png");
			imagejpeg($im2,$sharePic,100);
			imagejpeg($im2);
			
			//header("Location: ".$sharePic);
		}
		
	}

?>