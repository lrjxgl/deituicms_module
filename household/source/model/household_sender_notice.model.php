<?php
class household_sender_noticeModel extends model{
	public $table="mod_household_sender_notice";
	
	public function add($option){
		if(is_array($option['content'])){
			$content=$option['content']['content'];
			$con=$option['content'];
		}else{
			$content=$option['content'];
		}
		if(isset($option['title'])){
			$title=$option['title'];
		}else{
			$title="通知";
		}
		$senderid=$option['senderid'];
		$linkurl=$option['linkurl'];
		//发送通知
		$msg=array(
			"dateline"=>time(),
			"status"=>0,
			"senderid"=>$senderid,
			"content"=>sql($content),
			"linkurl"=>arr2str($linkurl),
			"title"=>$title
		);
		$this->insert($msg);
		 		
	}
}