<?php
use com\nlf\calendar\Foto;
use com\nlf\calendar\LunarYear;
use com\nlf\calendar\util\HolidayUtil;
use com\nlf\calendar\Lunar;
use com\nlf\calendar\util\LunarUtil;
use com\nlf\calendar\Solar;
class huangliControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("huangli/index.html");
	}
	
	public function onApi(){
		require ROOT_PATH."module/huangli/Lunar.php";
		$date=get("date");
		$darr=explode("-",$date);
		$word=get("word","h");
		$lunar = Lunar::fromYmd($darr[0], $darr[1], $darr[2]);
		 
		$d = Lunar::fromDate(new DateTime($date));
		//$d = Lunar::fromDate(new DateTime());
		// 宜
		$ji = $d->getDayYi();
		/*
		foreach ($l as $s) {
		  echo $s . "\n";
		}
		*/
		// 忌
		$xiong = $d->getDayJi();
		/*
		foreach ($l as $s) {
		  echo $s . "\n";
		}
		*/
	   //八字
	   $bazi = $d->getEightChar();
	   
	   echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"jiri"=>array(
					"ji"=>$ji,
					"xiong"=>$xiong
				),
				"bazi"=>$bazi->tostring()
			)
	   ));
	}
}