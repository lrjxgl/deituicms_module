<?php
class elsearch_articleModel extends model{
	public $table="article";
	public function import($type='all'){
		$tablename="article";
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
		$sql="select a.id as objectid,a.title,a.description,b.content
			from ".table("article")." as a
			left join ".table("article_data")." as b
			on a.id=b.id
			where $where 
			order by a.id DESC
			limit 10000
		 ";
		$res=M("article")->getAll($sql);
		
		if(!empty($res)){
			foreach($res as $data){
				$data["tablename"]="article";
				$data["content"]=$data["title"]."[@title@]".$data["content"];
				M("mod_elsearch_topic")->insert($data);
			}
			
		}
		return count($res); 
	}
}