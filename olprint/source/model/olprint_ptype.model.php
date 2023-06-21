<?php
class olprint_ptypeModel extends model{
	public $table="mod_olprint_ptype";
	public function __construct(){
		parent::__construct();
	}
	public function pTypeList(){
		return array(
			1=>array(
				"ptype"=>1,
				"title"=>"A4黑白",
				"start_money"=>1,
				"page_money"=>0.5
			),
			2=>array(
				"ptype"=>2,
				"title"=>"A4彩色",
				"start_money"=>4,
				"page_money"=>2
			),
			3=>array(
				"ptype"=>3,
				"title"=>"A4彩色双面",
				"start_money"=>6,
				"page_money"=>4
			),
			4=>array(
				"ptype"=>4,
				"title"=>"一寸证件照,一版9张",
				"start_money"=>10,
				"page_money"=>5,
				 
			),
			5=>array(
				"ptype"=>5,
				"title"=>"两寸证件照,一版4张",
				"start_money"=>10,
				"page_money"=>5
				 
			),
			6=>array(
				"ptype"=>6,
				"title"=>"照片",
				"start_money"=>5,
				"page_money"=>5
				 
			),
			99=>array(
				"ptype"=>99,
				"title"=>"共享资料",
				"start_money"=>99,
				"page_money"=>99
			)
		);
	}
	
}