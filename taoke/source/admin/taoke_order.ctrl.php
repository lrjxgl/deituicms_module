<?php
class taoke_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$where=" status in(0,1,2) ";
		$type=get("type","h");
		$url="/moduleadmin.php?m=taoke_order&type=".$type;
		switch($type){
			case "yes":
				$where.=" AND isbd=1 ";
				break;
			case "no":
				$where.=" AND isbd=0 ";
				break;
		}
		
		$limit=24;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_taoke_order")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($data as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
	 
		$this->smarty->goassign(
			array(
				"data"=>$data,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"per_page"=>$per_page,
				"typelist"=>$typelist,
				"groupList"=>$groupList
			)
		);
		$this->smarty->display("taoke_order/index.html");
	}
	
	public function onImport(){
		
		$this->smarty->display("taoke_order/import.html");
	}
	
	public function onImportSave(){
		require_once ROOT_PATH. 'PHPExcel/Classes/PHPExcel.php';
		require_once ROOT_PATH. 'PHPExcel/Classes/PHPExcel/IOFactory.php'; 
		$file=$_FILES['xsl']['tmp_name'];
		$excel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
		$excelData = array();
		$tbfields=array(
			2=>"createtime",
			5=>"productid",
			7=>"title",
			6=>"imgurl",
			13=>"orderno",
			15=>"orderstatus",
			17=>"money",
			29=>"income",
			
		); 
		for ($row = 2; $row <= $highestRow; $row++) {
			$rdata=array();
			for ($col = 0; $col < $highestColumnIndex; $col++) { 
				$rdata[]=(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue() ;
				 
			} 
			$rd=array();
			foreach($rdata as $k=>$v){
				if($tbfields[$k]=='tk_url_small') continue;
				if(isset($tbfields[$k])){
					if($k==6){
						$v="https:".$v;
					}
					$rd[$tbfields[$k]]=$v;
					
				}
			}
		 
			$_POST=$rd;
			
			$od=M("mod_taoke_order")->selectRow("orderno='".sql($rd["orderno"])."'");
			if($od){
				M("mod_taoke_order")->update(array(
					"orderstatus"=>$rd["orderstatus"]
				),"orderno='".sql($rd["orderno"])."'");
			}else{
				$indata=M("mod_taoke_order")->postData();
				M("mod_taoke_order")->insert($indata);
			}
			
		}
		$this->goAll("订单导入成功");
	}
	
	public function onUnion(){
		$list=M("mod_taoke_baodan")->select(array(
			"where"=>" status=0  "
		));
		if($list){
			foreach($list as $k=>$v){
				$row=M("mod_taoke_order")->selectRow("orderno='".$v["orderno"]."' ");
				if($row){
					M("mod_taoke_order")->update(array(
						"userid"=>$v["userid"],
						"isbd"=>1
					),"orderid=".$row["orderid"]);
					M("mod_taoke_baodan")->update(array(
						"status"=>1
					),"id=".$v["id"]);
				}
				
			}
		}
		echo "报单关联成功";
		 
	}
	
	public function onfanli(){
		$where="  isbd=1 AND orderstatus='已结算' ";
		$type=get("type","h");
		$url="/moduleadmin.php?m=taoke_order&a=fanli&type=".$type;
		switch($type){
			case "yes":
				$where.=" AND isback=1 ";
				break;
			case "no":
				$where.=" AND isback=0 ";
				break;
		}
		$limit=24;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_taoke_order")->select($option,$rscount);
			 
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
			 
		$this->smarty->goassign(
			array(
				"data"=>$data,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"per_page"=>$per_page,
				"typelist"=>$typelist,
				"groupList"=>$groupList
			)
		);
		$this->smarty->display("taoke_order/fanli.html");
	}
	
	public function onDofan(){
		$orderid=get("orderid","h");
		$order=M("mod_taoke_order")->selectRow("orderid=".$orderid);
		if($order["isback"]){
			$this->goAll("该订单已经返利了",1);
		}
		if($order["orderstatus"]!="已结算"){
			$this->goAll("该订单还未结算",1);
		}
		M("mod_taoke_order")->update(array(
			"isback"=>1
		),"orderid=".$orderid);
		$config=M("mod_taoke_config")->selectRow("1");
		$flsets=explode(",",$config["flsets"]);
		$steps=count($flsets);
		//处理返利
		$userid=$order["userid"];
		for($i=0;$i<$steps;$i++){
			$money=$order["income"]*$flsets[$i]/100;
			MM("taoke","taoke_user_money")->addMoney(array(
				"userid"=>$userid,
				"income"=>$money,
				"balance"=>$money,
				"content"=>"淘客订单返利{$money}元"
			));
			$u=M("user")->selectRow(array(
				"where"=>"userid=".$userid,
				"fields"=>"userid,invite_userid"
			));
			if($u["invite_userid"]){
				$userid=$u["invite_userid"];
			}else{
				break;
			}
		}
		
		$this->goAll("返利操作成功");
	}
	
	public function ondelFan(){
		$orderid=get("orderid","h");
		$order=M("mod_taoke_order")->selectRow("orderid=".$orderid);
		if($order["isback"]){
			$this->goAll("该订单已经返利了",1);
		}
		M("mod_taoke_order")->update(array(
			"isback"=>11
		),"orderid=".$orderid);
		$this->goAll("取消返利成功");
	}
}
?>