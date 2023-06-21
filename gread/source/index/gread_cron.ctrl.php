<?php
class gread_cronControl extends skymvc{
	
	public function onDefault(){
		session_write_close();
		while(1){
			$time=time();
			$card=M("mod_gread_user_card")->selectRow(array(
				"where"=>" isfinish=0 AND status=0 AND endtime<".$time." "
			));
			if(empty($card)){
				exit("完成");
			}
			M("mod_gread_user_card")->begin();
			M("mod_gread_user_card")->update(array(
				"isfinish"=>1,
				"status"=>3
			),"id=".$card["id"]);
			$gm=MM("gread","gread_shop_money")->get($card["shopid"]);
			MM("gread","gread_shop_money")->addMoney(array(
				"income"=>$card["shop_money"],
				"balance"=>$card["shop_money"],
				"shopid"=>$card["shopid"],
				"content"=>"借书卡[".$card["id"]."]结算收入".$card["shop_money"]."元"
			));
			M("mod_gread_user_card")->commit();
			echo "计划执行成功";
		}
		
	}
	
}