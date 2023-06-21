<?php
class kuaidiControl extends skymvc{
	public $shop_app;
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
	 
	}
	
	public function onDefault(){
		 
		$this->smarty->display("kuaidi/index.html");
	}
	public function onTest(){
		$ops=array(
			"postid"=> 118654313470,
			"id"=>  1,
			"valicode" => 0,
			"temp"=>  0.5851430501389143,
			"type"=> " huitongkuaidi",
			"phone"=>""  ,
			"token"=>"" ,
			"platform"=>"MWWW"
		);
		$a=curl_post("http://m.kuaidi100.com/query",$ops);
		echo $a;
	}
	public function onShow(){
		$danhao=get_post('danhao','h');
		$gs=array(
				"shunfeng"=>"顺丰",
				"shentong"=> "申通",
				"huitongkuaidi"=> "汇通",
				"huiqiangkuaidi"=> "汇强",
				"tiantian"=> "天天",
				"zhaijisong"=> "宅急送",
				"quanfengkuaidi"=> "全峰",
				"longbanwuliu"=> "龙邦",
				"guotongkuaidi"=> "国通",
				"kuaijiesudi"=> "快捷",
				"debangwuliu"=>"德邦",
				"zhongtong"=> "中通",
				"yunda"=>"韵达",
				"yuantong"=>"圆通",
				
			);
		//$danhao="560146521533";
		 
		if($danhao){
			$ops=array(
				"id"=>1,
				"postid"=>" 640011936184",
				"type"=>"zhongtong",
				"platform"=>"MWWW"
			);
			$a=curl_post("http://m.kuaidi100.com/query",$ops);
			echo $a; 
			$a1=json_decode($a,true);
			if($a1){
				foreach($a1 as $v){
					 
					$content=curl_get_contents("http://m.kuaidi100.com/query?type=".$v['comCode']."&postid=".$danhao."&id=1&valicode=&temp=".time());
					if($content){
						$step=json_decode($content,true);
						
						if(isset($step['nu'])){
							if(isset($gs[$step['com']])){
								$step['com']=$gs[$step['com']]; 
							}
							if(!M("mod_kuaidi")->selectRow("kdhao='".$danhao."'")){
								M("mod_kuaidi")->insert(array(
									"kdhao"=>$danhao,
									"dateline"=>time(),
									"userid"=>M("login")->userid
								));
							}
							break;
						}
				
					}
				}
			}
		}
	 
		 
	 
		$this->smarty->assign(array(
			"danhao"=>$danhao,
			"wuliu"=>$step,
			 
			
		));
		$this->smarty->display("kuaidi/show.html");
	}
	
}

?>