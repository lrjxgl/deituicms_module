<?php
class printerControl extends skymvc{
	//打印机提供的公司
	public $pcom="feier";
	public $htags="CB";
	public $qrtags="QR";
	public $pconfig;
	public function __construct(){
		parent::__construct();
		$this->pconfig=M("mod_printer_config")->selectRow("1"); 
	}
	
	public function onInit(){
		$key="mdsq".MODULENAME;
		if(!$data=cache()->setType("file")->get($key)){
			$domain=getBaseDomain($_SERVER['HTTP_HOST']);
			$json=file_get_contents("http://www.deitui.com/index.php?m=yunmodule&a=domain&domain=$domain&appdir=".MODULENAME);
			$data=json_decode($json,true);
			if(empty($data)) return true;
			if(!$data['error']){
				cache()->setType("file")->set($key,$data,3600);
			}
		}
		if($data['error']){				
			exit("当前域名未授权，不可使用");
		}
	}
	
	public function onDefault(){
		
		echo "打印机服务";
		
	}
	
	public function shead($str){
		if(empty($str)) return false;
		$arr=explode("\n",$str);
		$sdata="";
		foreach($arr as $v){
			$sdata.="<".$this->htags.">".$v."</".$this->htags."><BR>";
		}
		
		return $sdata;
	}
	
	public function sfoot($str){
		if(empty($str)) return false;
		$arr=explode("\n",$str);
		$sdata="";
		foreach($arr as $v){
			$sdata.=$v."<BR>";
		}
		return $sdata;
	}
	
	public function sewm($str){
		if(!empty($str)){
			$sdata="<".$this->qrtags.">".$str."</".$this->qrtags.">";
			return $sdata;
		}
	}
	
	public function set_printer($pcom){
		if($pcom!=1){
			
		}
	}
	/****打印计划****
	1.打印新订单
	2.重置isvalid==4 时间超过5分钟的订单
	3.删除isvalid=11 时间超过一天的订单
	*/
	
	public function onPlan(){
		set_time_limit(0);
		$this->Change();
		$this->delete();
		for($i=0;$i<100;$i++){
			usleep(10);
			$this->onprinter();
		}
	}
	
	public function Change(){
		MM("printer","mod_printer_plan")->update(array(
			"isvalid"=>1
		)," isvalid=4 AND last_time<".(time()-6));
	}
	
	public function delete(){
		$time=time()-3600*24;
		MM("printer","mod_printer_plan")->delete(" times > 5 or ( isvalid=11 AND last_time<".$time.")" );
	}
	
	public function setStr($str,$len=13){
		$str=cutstr($str,$len,"");
		$slen=strlen(iconv("utf-8","gbk",$str));
		if($slen<$len){
			$str.=str_pad("",$len-$slen);
		}
		return $str;
	}
	
	public function onPrinter(){
		/*
		isvalid 0无效的 1是有效 4正在打印  11删除
		***/
		
		$data=M("mod_printer_plan")->selectRow(array(
			"where"=>" isvalid=1 ",
			"order"=>"last_time ASC"
		));
		if(empty($data)){
			echo "完成"; 
			exit;	
		}
		MM("printer","mod_printer_plan")->update(array("isvalid"=>4,"last_time"=>time(),"times"=>$data['times']+1),"id=".$data['id']);
		$tpl=M("mod_printer_tpl")->selectRow(" tablename='".$data['tablename']."' AND shopid='".$data['shopid']."' ");
		$printer=M("mod_printer")->selectRow(" tablename='".$data['tablename']."' AND shopid='".$data['shopid']."'  AND bstatus=2 ");
		$printer['printer_num']=max(1,$tpl['num']);
		$this->set_printer($printer['pcom']);
		$head=$this->shead($tpl['shead']);
		$foot=$this->sfoot($tpl['sfoot']);
		$ewm=$this->sewm($tpl['sqr']);
		$content=str2arr($data['content']);
	 
		$p="";
		$p.=$head;
	 	$orderInfo .= '--------------------------------<BR>';
		$p.=$this->setStr("名称",13).$this->setStr("单价",10).$this->setStr("数量",5).$this->setStr("金额",8)."<BR>";
		$p.=str_pad("",32,"-")."<BR>";
		foreach($content['products'] as $pro){
			$p.= $this->setStr($pro['title'],13).$this->setStr($pro['price'],10).$this->setStr($pro['amount'],5).$this->setStr($pro['money'],8)."<BR>";
		}
		$p .= '--------------------------------<BR>';
		$p .= '合计：'.$content['order']['money'].'元  '.$content['order']['pay_type'].'<BR>';
		$p .= '送货地点：'.$content['addr']['address'].'<BR>';
		$p .= '联系电话：'.$content['addr']['telephone'].'  <BR>';
		$p .='联系人：'.$content['addr']['nickname'].' <BR>';
		$p .= '下单时间：'.date("Y-m-d H:i:",$content['order']['dateline']).'<BR>';
		$p.="订单编号：".$content['order']['orderno'];
		$p.="备注：".$content['order']['comment'];
		$p.=$foot;
		$p .= $ewm;//把二维码字符串用标签套上即可自动生成二
		 
		echo $p;
		$re=$this->goPrinter($printer,$p);
		 
		//打印成功
		if($re['status']==1){
			M("mod_printer_plan")->update(array("bstatus"=>11,"isvalid"=>11),"id=".$data['id']);
			$log['bstatus']=2;
		}else{
			$log['bstatus']=1;
		}
		
		$_POST=$data;
		$log=M("mod_printer_log")->postData();
		$log['dateline']=time();
		
		unset($log['id']);
		M("mod_printer_log")->insert($log);
		 
	}
	
	public function goPrinter($printer,$data){
	 
		require_once ROOT_PATH."module/printer/api/feier/HttpClient.class.php";
		define('USER', $this->pconfig["feier_user"]);	//*必填*：飞鹅云后台注册账号
		define('UKEY', $this->pconfig["feier_ukey"]);	//*必填*: 飞鹅云注册账号后生成的UKEY
		define('SN', $printer['printer_sn']);	    //*必填*：打印机编号，必须要在管理后台里添加打印机或调用API接口添加之后，才能调用API
		
		
		//以下参数不需要修改
		define('IP','api.feieyun.cn');			//接口IP或域名
		define('PORT',80);						//接口IP端口
		define('PATH','/Api/Open/');		//接口路径
		define('STIME', time());			    //公共参数，请求时间
		define('SIG', sha1(USER.UKEY.STIME));   //公共参数，请求公钥
 
		 
		$content = array(			
			'user'=>USER,
			'stime'=>STIME,
			'sig'=>SIG,
			'apiname'=>'Open_printMsg',

			'sn'=>$printer['printer_sn'],
			'content'=>$data,
		    'times'=>$printer['printer_num']//打印次数
		);
		 
		$client = new HttpClient(IP,PORT);
		if(!$client->post(PATH,$content)){
			return array(
					"status"=>11
				);
		}
		else{
			$json=$client->getContent();
			
			$arr=json_decode($json,true);
			//print_r($arr);
			if($arr['msg']=='ok' && $arr['data']==true){
				return array(
					"status"=>1
				);
			}else{
				return array(
					"status"=>11
				);
			}
		}
			
		 
	}
}

?>