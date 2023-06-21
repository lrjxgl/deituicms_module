<?php
class search_domainControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$domains=M("mod_search_domain")->select();
		$this->smarty->assign(array(
			"data"=>$domains
		));
		$this->smarty->display("search_domain/index.html");
	}
	
	public function onAdd(){
		$id=get_post('id','i');
		if($id){
			$data=M("mod_search_domain")->selectRow("id=".$id);
		}
		$this->smarty->assign(array(
			"data"=>$data
		));
		$this->smarty->display("search_domain/add.html");
	}
	
	public function onSave(){
		$id=get_post('id','i');
		$data=M("mod_search_domain")->postData();
		$data['basedomain']=getBaseDomain($data['domain']);
		if($id){
			M("mod_search_domain")->update($data,"id=".$id);
		}else{
			M("mod_search_domain")->insert($data);
		}
		$this->goAll("保存成功");	
	}
	
	public function onDelete(){
		$id=get_post('id','i');
		M("mod_search_domain")->delete("id=".$id);
		$this->goAll("保存成功");
	}
	
}

?>