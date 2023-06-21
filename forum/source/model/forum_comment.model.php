<?php
class forum_commentModel extends model{
	public $table="mod_forum_comment";
	public function __construct(){
		parent::__construct();
	}
	public function del($data){
		$this->update(array(
			"status"=>11
		),"id=".$data["id"]);
		MM("forum","forum_group")->changenum("comment_num",-1,"gid=".$data["gid"]);
	}
	
}