<?php
	class gread_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$shopid=MM("gread","gread")->getShopid();
			 
			if(!$shopid){
				$this->goAll("请选择书店",1,0,"/module.php?m=gread_shop&a=near");
			}
			$userid=M("login")->userid;
			$shop=MM("gread","gread_shop")->selectRow("shopid=".$shopid);
			$catlist=MM("gread","gread_book_category")->select(array(
				"where"=>" status=1 ",
				"order"=>"orderindex ASC"
			));
			 
			$sql="select a.*,b.* 
				from  ".table('mod_gread_shop_product')." as a 
				left join  ".table('mod_gread_book')." as b
				on a.bookid=b.bookid
				where a.shopid=".$shopid." AND a.status=1 
								
			";
			$catid=get("catid","i");
			if($catid){
				$sql.=" AND b.catid=".$catid;
			}else{
				$sql.=" AND a.isrecommend=1 ";
			}
			//获取购物车
			$carts=MM("gread","gread_cart")->getBooks($userid,$shopid);
			$booklist=M("mod_gread")->getAll($sql);
			if($booklist){
				foreach($booklist as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					if(isset($carts[$v['bookid']])){
						$v['incart']=1;
					}else{
						$v['incart']=0;
					}
					$booklist[$k]=$v;
				}
			}
			
			$this->smarty->goAssign(array(
				"shop"=>$shop,
				"catlist"=>$catlist,
				"booklist"=>$booklist
			));
			$this->smarty->display("gread_shop/index.html");
		}
		
		public function onNear(){
			$data=M("mod_gread_shop")->select(array(
				"where"=>" status=1 "
			));
			$this->smarty->goAssign(array(
				"list"=>$data
			));
			 
			$this->smarty->display("gread_shop/near.html");
		}
		
		public function onNearData(){
			$userid=M("login")->userid;
			$lat=get('lat','h');
			$lng=get('lng','h');
			$_SESSION['latlng']=array(
				"lat"=>$lat,
				"lng"=>$lng
			);
			
			$meter=0.00001*1.1;//1米以内
			$meter=$meter*50000000;
			$ilng=$lng+$meter;
			$mlng=$lng-$meter;
			$ilat=$lat+$meter;
			$mlat=$lat-$meter;
			$where="(lng<'$ilng' AND  lng>'$mlng' AND  lat>'$mlat' AND  lat<'$ilat')  ";
			$rscount=true;
			$arr=M("mod_gread_shop")->select(array(
				"where"=>" 1 "
			),$rscount);
			$start=get('per_page','i');
			$limit=24;
			$data=array();
			if($arr){ 
				foreach($arr as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$distance[$k]=$v['distance']=distanceByLnglat($lng,$lat,$v['lng'],$v['lat']);
					$arr[$k]=$v;					 					
				}				  
				array_multisort ( $distance ,  SORT_ASC ,  $arr );
				$rscount=count($arr);
				$end=$start+$limit;
				$end=$end>$rscount?$rscount:$end;
				
				for($i=$start;$i<$end;$i++){
					$data[]=$arr[$i];
					$uids[]=$arr[$i]['userid'];
				}
			}
			$this->smarty->goAssign(array(
				"list"=>$data
			));
		}
		
		public function onSetshop(){
			$shopid=get("shopid","i");
			setcookie("gread_shopid",$shopid,time()+3600*24*365);
			$data["gread_shopid"]=$shopid;
			//同步打印
			if(M("module")->isInstall("olprint")){
				$op=M("mod_olprint_openbind")->selectRow("tablename='gread_shop' AND objectid=".$shopid." AND status=1 ");
				if($op){
					setcookie("ck_olprint_shopid",$op["shopid"],time()+3600*24*365);
				}
				$data["olprint_shopid"]=$op["shopid"];
			}
			$this->goAll("success",0,$data);
		}
		
		public function ondefaultShop(){
			$data=M("mod_gread_shop")->select(array(
				"where"=>" 1 "
			));
			$this->smarty->goAssign(array(
				"data"=>$data
			));
			 
			$this->smarty->display("gread_shop/defaultshop.html");
		}
	}
?>