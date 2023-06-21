<?php
class book_orderModel extends model{
	public $table="mod_book_order";
	public function __construct(&$base=null){
		parent::__construct($base);
		$this->base=$base;
	}
	
	public function Dselect($option,&$rscount=false){
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$bookids[]=$v['bookid'];
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			$books=MM("book","book")->getListByIds($bookids);
		 
			foreach($data as $k=>$v){
				$book=$books[$v['bookid']];
				$v['title']=$book['title'];
				$v['description']=$book['description'];
				$v['imgurl']=images_site($book['imgurl']);
				$v['nickname']=$us[$v['userid']]['nickname'];
				 
				$data[$k]=$v;
			}
			return $data;
		}
		
	}
	
	public function add($ops){
		$bookid=$ops["bookid"];
		$userid=$ops["userid"];
		$data=M("mod_book")->selectRow("bookid=".$bookid);
		M("mod_book_order")->insert(array(
			"bookid"=>$bookid,
			"userid"=>$userid,
			"createtime"=>date("Y-m-d H:i:s"),	
			"money"=>$data["money"]
		));
		M("mod_book")->changenum("buy_num",1,"bookid='.$bookid.'");
	 
		//获取分销收益
		M("invite_account")->add(array(
			"cuserid"=>$userid,
			"money"=>$data["money"],
			"content"=>"好友购买了图书《".$data["title"]."》,奖励[money]元"
		));
	}
}

?>