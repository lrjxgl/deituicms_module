<?php
class diypage_userpageControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onSave(){
		$id=1;
		$content=post("content","x");
		$data=json_decode($_POST["content"],true);
		M("mod_diypage_userpage")->update(array(
			"page_data"=>$content
		),"up_id=".$id);
		echo json_encode([
			"error"=>0,
			"message"=>"success"
		]);
	}
	
}
?>