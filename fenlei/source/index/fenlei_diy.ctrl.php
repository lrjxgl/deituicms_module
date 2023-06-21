<?php
	class fenlei_diyControl extends skymvc{
		public function onDefault(){
			$dir=get("dir","h");
			if(preg_match("/\W/",$dir)){
				exit( "路径非法");
			}
			$tpl=get("tpl","h");
			if(preg_match("/\W/",$tpl)){
				exit( "路径非法");
			}
			$this->smarty->display($dir."/".$tpl.".html");
		}
	}
?>