<?php
class household_cronControl extends skymvc{
	
	public $rankList;
	public function __construct(){
		session_write_close();
	}
	public function onDefault(){
		
	}
	/**
	 * 自动完成订单
	 */
	public function onFinishOrder(){
		$config=M("mod_household_config")->selectRow("1");
		if(!$config["auto_finish"]){
			echo "无任务";
			return false;
		}
		$stime=date("Y-m-d H:i:s",time()-$config["auto_finish"]*3600);
		$list=MM("household","household_order")->select(array(
			"where"=>" status=2 AND ispay=1 AND updatetime<='".$stime."' ",
			"limit"=>30
		));
		 
		
		if(!empty($list)){
			foreach($list as $order){
				MM("household","household_order")->begin();
				MM("household","household_order")->finish($order,"admin");				
				MM("household","household_order")->commit();
			}
		}
		echo "执行完毕";
	}
	/**
	 * 更新技工等级
	 * 评价体系 客户评分、 接单数、上期 派单率 、接单数 
	 * 每周更新一次
	 */
	public function onRank(){
		$file="attach/cron_household_cron.rank.lock";
		if(file_exists($file)){
			return false;
		}
		$this->rankList=M("mod_household_rank")->select(array(
			"where"=>" status=1 "
		));
		$ctime=date("Y-m-d 00:00:00");
		$last_time=date("Y-m-d H:i:s")-6*3600*24;
		
		file_put_contents($file,date("Y-m-d H:i:s"));
		$list=M("mod_household_sender_rank")->select(array(
			"order"=>" grade DESC",
			"where"=>"last_time<'".$last_time."' "
		));
		if(empty($list)){
			return false;
		}
		foreach($list as $urank){
			$this->setRank($urank,$ctime);
		}
		unlink($file);
	}
	
	public function setRank($urank){
		$grade=8.2;
		$rankid=0;
		$etime=date("Y-m-d H:i:s",strtotime("-1 days"));
		$stime=date("Y-m-d H:i:s",strtotime("-7 days"));
		$grade=M("mod_household_order_raty")->selectOne(array(
			"where"=>" senderid=".$urank." AND createtime >'".$stime."' AND createtime<'".$etime."'  ",
			"fields"=>"AVG(raty_quality) as ct"
		));
		if($grade==0){
			$grade=urank["grade"]-1;
		}
		foreach($this->rankList as $rk){
			if($rk["min_grade"]<=$grade && $rk["max_grade"]>$grade){
				$rank=$rk;
				$rankid=$rank["rankid"];
				break;
			}
		}
		M("mod_household_sender_rank")->update(array(
			"last_time"=>date("Y-m-d H:i:s"),
			"grade"=>$grade,
			"rankid"=>$rankid,
		),"senderid=".$urank["senderid"]);
		M("mod_household_sender_rank_log")->insert(
			array(
				"createtime"=>date("Y-m-d H:i:s"),
				"grade"=>$grade,
				"senderid"=>$senderid,
				"rankid"=>$rankid
			)
		);
	}
	
}