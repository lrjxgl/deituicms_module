<?php
class yxq_paperModel extends model{
	public $table="mod_yxq_paper";
	public function xzList(){
		return [
			"白羊座","金牛座","双子座","巨蟹座","狮子座","处女座","天秤座","天蝎座","射手座","摩羯座","水瓶座","双鱼座"
		];
	}
}