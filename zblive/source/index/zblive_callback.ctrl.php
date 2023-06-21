<?php
class zblive_callbackControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
	 
		$id=get("id","h");
		$id=str_replace("zblive","",$id);
		$id=intval($id);
		$action=get("action",'h');
		switch($action){
			case "publish":
				M("mod_zblive")->update(array(
					"zbstatus"=>1
				),"id=".$id);
				break;
			case "publish_done":
				M("mod_zblive")->update(array(
					"zbstatus"=>2,
					"offtime"=>time(),
					"endtime"=>time()
				),"id=".$id);
				break;
		}
	}
	
	public function onCallBackRecord(){
		skyLogg("zbliveRecord.txt",serialize($_REQUEST));
		$id=get("stream","h");
		$id=str_replace("zblive","",$id);
		$id=intval($id);
		$uri=get("uri","h");
		if($uri){
			M("mod_zblive")->update(array(
				"isback"=>1,
				"mp4url"=>$uri
			),"id=".$id);
		}
		echo "success"; 
	}
	
}
?>