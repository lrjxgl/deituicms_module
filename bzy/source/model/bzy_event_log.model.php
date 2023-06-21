<?php
class bzy_event_logModel extends model{
	public $table="mod_bzy_event_log";
	public function add($data){
		return $this->insert($data);
	}
}