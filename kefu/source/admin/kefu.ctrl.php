<?php
	class kefuControl extends skymvc{
		public function onDefault(){
			$start=get("per_page","i");
			$limit=12;
			$where="  status in(0,1,2)";
			$order="orderindex ASC";
			$rscount=true;
			$url="/moduleadmin.php?m=kefu";
			$list=M("mod_kefu")->select(array(
				"where"=>$where,
				"order"=>$order,
				"start"=>$start,
				"limit"=>$limit
			),$rscount);
			if($list){
				foreach($list as $k=>$v){
					$v["user_head"]=images_site($v["user_head"]);
					$list[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goAssign(array(
				"list"=>$list,
				"pagelist"=>$pagelist
			));
			$this->smarty->display("kefu/index.html");	
		}
		public function onMenu(){
			
			$this->smarty->display("menu.html");
		}
	}
?>