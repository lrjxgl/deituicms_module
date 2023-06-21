<?php
class elsearch_askModel extends model{
	public $table="mod_ask";
	public function import($type='all'){
		$tablename="mod_ask";
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
		$sql="select a.askid as objectid,a.title,a.description,b.content
			from ".table("mod_ask")." as a
			left join ".table("mod_ask_data")." as b
			on a.askid=b.askid
			where $where 
			order by a.askid DESC
			limit 10000
		 ";
		$res=M("mod_forum")->getAll($sql);
		
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