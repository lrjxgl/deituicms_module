<?php
class searchControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
		$this->loadModuleModel("search",array("mod_search"));
	}
	
	public function onDefault(){
		$keyword=get_post('keyword','h');
		
		if($keyword){
			$limit=10;
			$start=min(1000,get('per_page','i'));
			$st=microtime(true);
			
			//分词
			$this->loadClass("keywords");			
			//$keyword=$this->keywords->zhfc($keyword,true);
			
			$res=$this->sphinx($keyword);
			//$res=$this->opensearch($keyword);
		 
			 $stime=microtime(true)-$st;
			$ids=$res['ids']; 
			if(!empty($ids)){
				$data=M("mod_search_topic")->select(array(
					"where"=>" id in("._implode($ids).") "
				));
			}
			$rscount=$res['rscount'];
			$url="/module.php?m=search&keyword=".urlencode($keyword);
			$pagelist=$this->pagelist($rscount,$limit,$url);
		}
		
		$this->smarty->goassign(array(
			"data"=>$data,
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"searchtime"=>$stime
		)); 
		$this->smarty->display("search/index.html"); 
	}
	
	private function sphinx($keyword,$limit=10){
		$start=min(1000,get('per_page','i'));
		$this->loadClass("sphinxClient");
			 
			$this->sphinxClient->SetServer("localhost",9312);
			$start=get('per_page','i');
			$this->sphinxClient->SetMatchMode(SPH_MATCH_ANY);
			$this->sphinxClient->SetMatchMode(SPH_MATCH_EXTENDED);
			$this->sphinxClient->setSelect("*");
			$this->sphinxClient->SetLimits($start,$limit); 
			
			$res=$this->sphinxClient->Query($keyword,"search") ;
			 
			$ids=array();
			if(!empty($res['matches'])){
				foreach($res['matches'] as $id=>$v){
					$ids[]=$id;
				}
				return array(
					"ids"=>$ids,
					"rscount"=>$res['total']
				);
			}
			
			 
	} 
	
	private function opensearch($keyword,$limit=10){
		require ROOT_PATH."api/alisearch/init.php";
		$start=min(1000,get('per_page','i'));
		// 实例化一个搜索类 search_obj
		$search_obj = new CloudsearchSearch($client);
		// 指定一个应用用于搜索
		$app_name="skySearch";
		$search_obj->addIndex($app_name);
		$search_obj->setQueryString("default:'".$keyword."'");
		// 指定返回的搜索结果的格式为json
		$search_obj->setFormat("json");
		$search_obj->setStartHit($start);
		$search_obj->setHits($limit);
		// 执行搜索，获取搜索结果
		$json = $search_obj->search();
		// 将json类型字符串解码
		$result = json_decode($json,true);
		 
		if($result['status']=='OK'){
			$data=$result['result']['items'];
			if($data){
				foreach($data as $k=>$v){
					$ids[]=intval($v['id']);
					 
				}
				return array(
						"ids"=>$ids,
						"rscount"=>$result['result']['total']
				);
			}
		}
		
	}
	
}

?>