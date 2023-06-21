<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class book_articleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			 
		}
	 
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_book_article")->selectRow(array("where"=>"id={$id}"));
			if(empty($data)) $this->goAll("文章出错",1);
			$data['content']=M("mod_book_article_data")->selectOne(array(				 
				"fields"=>"content",
				"where"=>"id=".$id
			));
			$book=M("mod_book")->selectRow("bookid=".intval($data['bookid']));
			$book['imgurl']=images_site($book['imgurl']);
			$userid=M("login")->userid;
			$bookid=intval($data['bookid']);
			if($book['ispay'] || $book['ispay']){
				$order=M("mod_book_order")->selectRow("bookid=".$bookid." AND isdelete=0  AND userid=".$userid);
				if(empty($order)){
					$this->goAll("请先购买",1);
				}				
			}
			if($data['isprivate'] && !$order){
				$this->goAll("该书不公开",1);
			}
			  //是否点赞
			  $islove=0;
			  $love=M("love")->selectRow("tablename='mod_book_article' AND userid=".$userid." AND objectid=".$id);
			  if($love){
				$islove=1;
			  }
			  //是否收藏
			  $isfav=0;
			  $fav=M("fav")->selectRow("tablename='mod_book_article' AND userid=".$userid." AND objectid=".$id);
			  if($fav){
				$isfav=1;
			  }
			$this->smarty->goassign(array(
				"data"=>$data,
				"book"=>$book,
				"isfav"=>$isfav,
				"islove"=>$islove
			));
			$this->smarty->display("book_article/show.html");
		}
		
		public function onSearch(){
			$bookid=get_post("bookid","i");
			$where="bookid=".$bookid;
			$word=get('word','h');
			if($word){
				$where.=" AND title like '%".$word."%' ";
			}
		 
			$data=M("mod_book_article")->select(array(
				"where"=>$where
			));	
			if(!$data){
				$this->goAll("搜索不到内容",1);
			}
		 
			$this->smarty->goassign(array(
				"data"=>$data,
		 
			));
			//$this->smarty->display("book_article/show.html");
		}
		public function onView(){
			$id=get_post("id","i");
			$userid=M("login")->userid;
			$data=M("mod_book_article")->selectRow(array("where"=>"id={$id}"));
			if(empty($data)) $this->goAll("文章出错",1);
			$data['content']=M("mod_book_article_data")->selectOne(array(				 
				"fields"=>"content",
				"where"=>"id=".$id
			));
			if(get('fromapp')=='wxapp'){
				$data['content']=$this->wxappHtml($data['content']);
			}
			$data['mp3url']=images_site($data['mp3url']);
			$data['mp4url']=images_site($data['mp4url']);
			$book=M("mod_book")->selectRow("bookid=".intval($data['bookid']));
			 
			$bookid=intval($data['bookid']);
			if($book['ispay'] || $book['isprivate']){
				$order=M("mod_book_order")->selectRow("bookid=".$bookid." AND isdelete=0  AND userid=".$userid);
				if(empty($order)){
					$this->goAll("请先购买",1);
				}
				
			}
			if($data['isprivate'] && !$order){
				$this->goAll("该书不公开",1);
			}
			 //是否点赞
			 $islove=0;
			 $love=M("love")->selectRow("tablename='mod_book_article' AND userid=".$userid." AND objectid=".$id);
			 if($love){
			 	$islove=1;
			 }
			 //是否收藏
			 $isfav=0;
			 $fav=M("fav")->selectRow("tablename='mod_book_article' AND userid=".$userid." AND objectid=".$id);
			 if($fav){
			 	$isfav=1;
			 }
			$this->smarty->goassign(array(
				"data"=>$data,
				"book"=>$book,
				"isfav"=>$isfav,
				"islove"=>$islove
			));
			$this->smarty->display("book_article/view.html");
		}
		
		public function wxappHtml($html){
			//替换pre标签
			 
			$html='<div class="rich-text">'.$html.'</div> '; 
			$html=preg_replace_callback(
				"/<pre.*>(.*)<\/pre>/iUs",
				 function ($matches) {
				 	 
				 	$str=nl2br($matches[1]);
				 	$str=str_replace("&#39;","'",$str);
		            return '<div class="pre">'.$str.'</div>';
		        },
		        $html
			);
			 
			return $html;
		}
		
		public function onAddClick(){
			$id=get("id","i");
			M("mod_book_article")->changenum("view_num",1,"id=".$id);
			echo "success";
		}
		
	}

?>