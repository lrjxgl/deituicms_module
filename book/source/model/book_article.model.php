<?php
class book_articleModel extends model{
	public $table="mod_book_article";
	
	public function Dselect($option,&$rscount=false){
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$bookids[]=$v['bookid'];
			 
			}
			 
			$books=MM("book","book")->getListByIds($bookids);
		 
			foreach($data as $k=>$v){
				$v['book_title']=$books[$v['bookid']]['title'];
				 
				 
				$data[$k]=$v;
			}
			return $data;
		}
		
	}
	
	public function getListByIds($ids,$fields="id,title,haschild,description"){
		$rss=$this->select(array(
			"where"=>" id in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($rss){
			$data=array();
			foreach($rss as $v){
				$data[$v['id']]=$v;
			}
			return $data;
		}
	}
	
	public function getFamily($bookid){
		$data=$this->select(array(
			"where"=>"bookid=".$bookid." AND status in(0,1,2)  ",
			"order"=>"orderindex ASC",
			"fields"=>"bookid,id,haschild,title,description,content,pid"
		));
		if($data){
			foreach($data as $k=>$v){
				$v["openFold"]=0;
				$data[$k]=$v;
			}
			foreach($data as $k=>$v){
				if($v['pid']==0){
					$a[$v['id']]=$v;
					unset($data[$k]);
				}			
			}
			foreach($data as $k=>$v){
				if(isset($a[$v['pid']])){
					$a[$v['pid']]['child'][$v['id']]=$v;
					unset($data[$k]);
				}
			}
			foreach($data as $k=>$v){
				foreach($a as $kk=>$vv){
					if(isset($vv['child'][$v['pid']])){
						$vv['child'][$v['pid']]['child'][]=$v;
						$a[$kk]=$vv;
					}
				}
			}
			$ak=0;
			foreach($a as $k=>$v){
				$bk=0; 
				$b2=array();				
				if($v['child']){
					
					foreach($v['child'] as $kk=>$vv){
						$c3=array();
						if($vv['child']){
							
							foreach($vv['child'] as $kkk=>$vvv){
								$c3[]=$vvv;								 
							}							 
						}
						$b2[$bk]=$vv;
						$b2[$bk]['child']=$c3;
						$bk++; 
					}					
				}
				$newdata[$ak]=$v;
				$newdata[$ak]['child']=$b2;				 
				$ak++;
			} 
			return $newdata;
			
		}
		
		
	}
}
?>