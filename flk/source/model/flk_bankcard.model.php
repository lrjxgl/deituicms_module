<?php
class flk_bankcardModel extends model{
	public $table="mod_flk_bankcard";
	public function __construct(){
		parent::__construct();
	}
	public function bankList(){
		return array(
			"nyyh"=>"中国农业银行",
			"jsyh"=>"中国建设银行",
			"zsyh"=>"中国招商银行",
			"yzyh"=>"中国邮政储蓄",
			"alipay"=>"支付宝",
			"wxpay"=>"微信支付"
		);
	}
	
}