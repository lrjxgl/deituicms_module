<?php
class kuaidiModel extends model{
	public $table="mod_kuaidi";
	public function __construct(){
		parent::__construct();
	}
	/**
	 * 申通查询
	 */
	public function shentong(){
		
	}
	/**
	 * 圆通快递
	 */
	public function yuantong(){
		$url="http://www.yto.net.cn/api/trace/waybill";
		$ops=array(
			"waybillNo"=>"100613574827"
		);
		$res=curl_post($url,$ops);
		echo $res;
	}
	/**
	 * 中通查询
	 */
	public function zhongtong(){
		$url="https://hdgateway.zto.com/WayBill_GetDetail";
		$ops=array(
			"billCode"=>"640011936184"
		);
		$res=curl_post($url,$ops);
		print_r($res);
	}
	/**
	 * 韵达快递
	 */
	public  function yunda(){
		
	}
}
?>