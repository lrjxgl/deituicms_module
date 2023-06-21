<?php
use Com\Pdd\Pop\Sdk\PopHttpClient;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkRpPromUrlGenerateRequest;
class taoke_pdd_configControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$data=M("mod_taoke_pdd_config")->selectRow();
		$this->smarty->assign(array(
			"data"=>$data
		));
		$this->smarty->display("taoke_pdd_config/index.html");
	}
	
	public function onSave(){
		$data=M("mod_taoke_pdd_config")->postData();
		$row=M("mod_taoke_pdd_config")->selectRow();
		if($row){
			M("mod_taoke_pdd_config")->update($data,"id=".$row['id']);
		}else{
			M("mod_taoke_pdd_config")->insert($data);
		}
		$this->goAll("保存成功");
	}
	
	public function onBeian(){
		require_once ROOT_PATH."module/taoke/ddksdk/vendor/autoload.php";
		$config=M("mod_taoke_pdd_config")->selectRow();
		$client = new PopHttpClient($config["appkey"], $config["secretKey"]);
		$request = new PddDdkRpPromUrlGenerateRequest();
		
		$request->setChannelType(10);
		$request->setCustomParameters($config["custom_parameters"]);
		$request->setPIdList(array($config["pid"]));
		try{
			$response = $client->syncInvoke($request);
		} catch(Com\Pdd\Pop\Sdk\PopHttpException $e){
			$this->goAll("异常返回",1);
		}
		$content = $response->getContent();
		if(isset($content['error_response'])){
			$this->goAll("异常返回",1);
		}
		header("Location:".$content["rp_promotion_url_generate_response"]["url_list"][0]["url"]);
		exit;
		
	}
	
}
?>