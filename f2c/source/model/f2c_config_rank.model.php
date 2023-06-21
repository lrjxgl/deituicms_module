<?php
class f2c_config_rankModel extends model{
	public $table="mod_f2c_config_rank";
	public function __construct(){
		parent::__construct();
	}
	/**
	 * 获取上级折扣设置
	 */
	public function getParentDiscount($uids){
		 
		if($uids){
			$us=MM("f2c","f2c_user")->select(array(
				"where"=>"userid in("._implode($uids).") ",
				"fields"=>"userid,grade",
				"order"=>"grade ASC"
			));
			 
			$ranklist=$this->selectCols(array(
				"fields"=>"id",
				"order"=>"discount ASC"
			));
			foreach($us as $k=>$v){
				$fv=$this->selectRow(array(
					"fields"=>" id,discount ",
					"where"=>" min_grade<=".$v['grade']." AND max_grade>".$v['grade'] 
				));
				$v['fanlv']=$fv['discount'];
				$fanlv_key=0;
				foreach($ranklist as $rv){
					if($rv==$fv['id']){
						
						break;
					}
					$fanlv_key++;
				}
				$v['fanlv_key']=$fanlv_key;
				$us[$k]=$v;
			}
			return $us;
		}
	}
	
	/**
	 * $us 用户
	 * $p 产品
	 *获取级差返利
	 */
	public function jcfanli($us,$p,$fctype=0){
		//按产品设置
		if($fctype==1){
			$fanli=explode(",",$p['fanli']);
			$last=0;
			foreach($us as $k=>$v){
				$fv=$fanli[$v['fanlv_key']];
				$data[$v['userid']]=max(0,$fv-$last);
				$last=$fv;
			}
			return $data;
		}else{//按用户
			$last=0;
			foreach($us as $k=>$v){
				$data[$v['userid']]=max(0,$v['fanlv']-$last);
				$last=$v['fanlv'];
			}
			return $data;
		}
	}
	
}