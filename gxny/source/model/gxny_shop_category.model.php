<?php
class gxny_shop_categoryModel extends model{
	public $table="mod_gxny_shop_category";
	public function idList($ops){
		$res=$this->select($ops);
		$list=[];
		if(!empty($res)){
			foreach($res as $rs){
				$list[$rs["catid"]]=$rs;
			}
		}
		return $list;
	}
}