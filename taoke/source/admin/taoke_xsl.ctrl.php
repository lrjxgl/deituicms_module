<?php
class taoke_xslControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	public function onInit(){
		session_write_close();
	}
	function echoflush($str){
		ob_start();
		echo "<div class='logs'>".$str."</div>";
		
		ob_flush();
		flush();
	}
	public function onPwd(){
		ob_implicit_flush(true); 
		set_time_limit(0);
		include ROOT_PATH."/module/taoke/sdk/TopSdk.php";
		$config=M("mod_taoke_config")->selectRow();
		$c = new TopClient;
		$c->appkey = $config['appkey'];
		$c->secretKey = $config['secretKey'];
		 $c->format="json";
		$req = new TbkTpwdCreateRequest;
		$req->setUserId($config['tkuserid']);
		
		$data=M("mod_taoke")->select(array(
			"where"=>" tk_pwd='' AND (xfrom='taobao' or xfrom='tmall') AND status=1 ",
			"limit"=>100,
			"order"=>"id DESC"
		));
		if(empty($data)){
			exit("生成完毕");
		}
		$st=microtime(true);
		foreach($data as $k=>$v){
			echo "处理".$v["id"]."<br/>"; 
			$req->setText($v['title']);
			$req->setUrl($v['tk_url']);
			$req->setLogo($v['imgurl']);
			$resp = $c->execute($req);
			$resp=json_decode(json_encode($resp),true);
			$tk_pwd=$resp['data']['model'];
			$req->setUrl($v['juan_url']);
			$req->setLogo($v['imgurl']);
			$resp = $c->execute($req);
			$resp=json_decode(json_encode($resp),true);
			$juan_pwd=$resp['data']['model'];
			if($juan_pwd){
				M("mod_taoke")->update(array(
					"tk_pwd"=>$tk_pwd,
					"juan_pwd"=>$juan_pwd,
					"status"=>1
				),"id=".$v['id']);
			}else{
				M("mod_taoke")->update(array(
					"status"=>3
				),"id=".$v['id']);
			}
			
			unset($data[$k]);
		}
		echo "一共耗时".(microtime(true)-$st)."秒，";
		echo "正在生成中...<script>window.location.reload()</script>"; 
	}
	public function onImportExcel(){
		$this->smarty->display("taoke_xsl/importexcel.html");
	}
	/**淘宝精品推荐**/
	public function onImTaobest(){
		set_time_limit(0);
		require_once ROOT_PATH. 'PHPExcel/Classes/PHPExcel.php';
		require_once ROOT_PATH. 'PHPExcel/Classes/PHPExcel/IOFactory.php'; 
		$file=ROOT_PATH."module/taoke/taobest.xls";
		$excel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
		$excelData = array(); 
		$tbfields=array(
			0=>"tb_numid",
			1=>"title",
			2=>"imgurl",
			3=>"tb_url",
			4=>"tb_cat",
			5=>"tk_url",
			6=>"price",
			7=>"sold_num",
			8=>"yj_bl",
			9=>"yj_money",
			10=>"seller_ww",
			11=>"seller_id",
			12=>"shop_name",
			13=>"shop_type",
			14=>"juan_id",
			15=>"juan_total",
			16=>"juan_num",
			17=>"juan_money",
			18=>"juan_start",
			19=>"juan_end",
			20=>"juan_url",
			21=>"juan_pwd",
			
		);
		 
		for ($row = 2; $row <= $highestRow; $row++) { 
			$rdata=array();
			for ($col = 0; $col < $highestColumnIndex; $col++) { 
				$rdata[]=(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue() ;
				 
			} 
			foreach($rdata as $k=>$v){
				$rd[$tbfields[$k]]=$v;
			}
			$res=C("upload","source/index/")->saveRemoteImg("https://".$rd["imgurl"]);
			if(!$res["error"]){
				$rd["imgurl"]=$res["imgurl"];
			}else{
				continue;
			}
			$rd['status']=1;
			$rd['dateline']=time();
			preg_match("/减(\d+)元/",$rd['juan_money'],$a);
			$rd['juan_money']=intval($a[1]);
			
			if($res=M("mod_taoke")->selectRow("tb_numid=".$rd['tb_numid'])){
				
				//M("mod_taoke")->update($rd,"tb_numid=".$rd['tb_numid']);
			}else{
				M("mod_taoke")->insert($rd);
			}
			unset($res);
			unset($rd);
			//$excelData[$row]=$rd;
			/*
			if($row>10){
				break;
			}
			*/
		}
		
		echo "导入完毕"; 
		
	}
	
	public function onImTaoSelf(){
		set_time_limit(0);
		require_once ROOT_PATH. 'PHPExcel/Classes/PHPExcel.php';
		require_once ROOT_PATH. 'PHPExcel/Classes/PHPExcel/IOFactory.php'; 
		//$file=ROOT_PATH."module/taoke/taoself.xls";
		$file=$_FILES['xsl']['tmp_name'];
		$excel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
		$excelData = array(); 
		$tbfields=array(
			0=>"tb_numid",
			1=>"title",
			2=>"imgurl",
			3=>"tb_url",
			4=>"shop_name",		 
			5=>"price",
			6=>"sold_num",
			7=>"yj_bl",
			8=>"yj_money",
			9=>"seller_ww",
			10=>"tk_url_small",
			11=>"tk_url",
			12=>"tk_pwd",
			13=>"juan_total",
			14=>"juan_num",
			15=>"juan_money",
			16=>"juan_start",
			17=>"juan_end",
			18=>"juan_url",
			19=>"juan_pwd",
	
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
					$rd[$tbfields[$k]]=$v;
				}
			}
			 
			if(count($rd)<15) continue;
			$res=C("upload","source/index/")->saveRemoteImg("https://".$rd["imgurl"]);
			if(!$res["error"]){
				$rd["imgurl"]=$res["imgurl"];
			}else{
				continue;
			}	
			$rd['status']=1;
			$rd['dateline']=time();
			preg_match("/减(\d+)元/",$rd['juan_money'],$a);
			$rd['juan_money']=intval($a[1]);
			if($res=M("mod_taoke")->selectRow("tb_numid=".$rd['tb_numid'])){
				
				M("mod_taoke")->update($rd,"tb_numid=".$rd['tb_numid']);
			}else{
				M("mod_taoke")->insert($rd);
			}
			 
			//$excelData[$row]=$rd;
			/*
			if($row>10){
				break;
			}
			*/
		}
		
		$this->goAll("导入完毕");
		
	}
	
	
	public function onImJdSelf(){
		set_time_limit(0);
		require_once ROOT_PATH. 'PHPExcel/Classes/PHPExcel.php';
		require_once ROOT_PATH. 'PHPExcel/Classes/PHPExcel/IOFactory.php'; 
		//$file=ROOT_PATH."module/taoke/taoself.xls";
		$file=$_FILES['xsl']['tmp_name'];
		$excel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
		$excelData = array(); 
		$tbfields=array(
			 
			0=>"title",
			1=>"tb_url", 
			2=>"imgurl",
			3=>"sold_num",
			4=>"price",
			5=>"yj_bl",
			6=>"yj_money",
			7=>"tk_url",
			8=>"juan_url"
			 
	
		);
		 
		for ($row = 2; $row <= $highestRow; $row++) { 
			$rdata=array();
			for ($col = 0; $col < $highestColumnIndex; $col++) { 
				$rdata[]=(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue() ;
				 
			} 
			$rd=array();
			foreach($rdata as $k=>$v){
				 
				if(isset($tbfields[$k])){
					$rd[$tbfields[$k]]=$v;
				}
			}
			 
			$res=C("upload","source/index/")->saveRemoteImg("https://".$rd["imgurl"]); 
			if(!$res["error"]){
				$rd["imgurl"]=$res["imgurl"];
			}else{
				continue;
			}
			 	
			$rd['status']=1;
			$rd['dateline']=time();
			$rd["xfrom"]="jd"; 
			$burl=basename($rd["tb_url"]);
			$rd["tb_numid"]=substr($burl,0,strpos($burl,"."));
			$rd["tb_numid"]=floatval($rd["tb_numid"]);
			if($rd["tb_numid"]==0){
				continue;
			}
			if($res=M("mod_taoke")->selectRow("tb_numid=".$rd['tb_numid'])){
				
				M("mod_taoke")->update($rd,"tb_numid=".$rd['tb_numid']);
			}else{
				M("mod_taoke")->insert($rd);
			}
			 
			 
		}
		
		$this->goAll("导入完毕");
		
	}
	
}
?>