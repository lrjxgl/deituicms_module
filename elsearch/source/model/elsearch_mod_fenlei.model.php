<?php
class elsearch_mod_fenleiModel extends model{
	public $table="mod_fenlei";
	public function import($type='all'){
		$tablename="mod_fenlei";
		$row=M("mod_elsearch_table")->selectRow("tablename='".$tablename."' ");
		
		if(empty($row)){
			return 0;
		}
		M("mod_elsearch_table")->update(array(
			"updatetime"=>date("Y-m-d H:i:s")
		),"id=".$row["id"]);
		$where="a.status=1  ";
		if($type=='new'){
			$where.=" AND updatetime >'".$row["updatetime"]."' ";
		}else{
			M("mod_elsearch_topic")->delete("tablename='".$tablename."' ");
		}
		 
		$sql="select a.id as objectid,a.title,a.description,a.content
			from ".table("mod_fenlei")." as a
			 
			where $where 
			order by a.id DESC
			limit 10000
		 ";
		$res=M("mod_fenlei")->getAll($sql);
		
		if(!empty($res)){
			foreach($res as $data){
				$data["tablename"]=$tablename;
				$data["content"]=$data["title"]."[@title@]".$data["content"];
				M("mod_elsearch_topic")->insert($data);
			}
			
		}
		return count($res); 
	}
}