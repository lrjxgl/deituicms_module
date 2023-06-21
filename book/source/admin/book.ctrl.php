<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class bookControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onaMenu(){
			
			$this->smarty->display("menu.html");
		}
		
		public function onDefault(){
			$where=" status in(0,1,2,3,4) ";
			$url="/moduleadmin.php?m=book";
			 
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_book")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("book/index.html");
		}
		
		public function onList(){
			$where="";
			$url="/module.php?m=book&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_book")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("book/list.html");
		}
		
		public function onAdd(){
			$bookid=get_post("bookid","i");
			if($bookid){
				$data=M("mod_book")->selectRow(array("where"=>"bookid={$bookid}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("book/add.html");
		}
		
		public function onWrite(){
			$bookid=get_post("bookid","i");
			if($bookid){
				$data=M("mod_book")->selectRow(array("where"=>"bookid={$bookid}"));				
			}
			$artlist=MM("book","book_article")->getFamily($bookid);
			$this->smarty->goassign(array(
				"book"=>$data,
				"artlist"=>$artlist
			));
			$this->smarty->display("book/write.html");
		}
		
		public function onMenu(){
			$bookid=get_post("bookid","i");
			if($bookid){
				$data=M("mod_book")->selectRow(array("where"=>"bookid={$bookid}"));				
			}
			$artlist=MM("book","book_article")->getFamily($bookid);
			$html="";
			if($artlist){
				
				foreach($artlist as $a){
					$html.="*"."[".$a['id']."]".$a['title']."\r\n";
					if($a['child']){
						foreach($a['child'] as $b){
							$html.="**"."[".$b['id']."]".$b['title']."\r\n";
							if($b['child']){
								foreach($b['child'] as $c){
									$html.="****"."[".$c['id']."]".$c['title']."\r\n";
								}
							}
						}
					}
				}
			}
			$this->smarty->goassign(array(
				"book"=>$data,
				"artlist"=>$artlist,
				"artMenu"=>$html
			));
			$this->smarty->display("book/menu.html");
		}
		
		public function onMenuSave(){
			$bookid=get_post('bookid','i');
			$content=post("content");
			$arr=explode("\n",$content);
			if($arr){
				foreach($arr as $k=>$v){
					if(empty($v)){
						unset($arr[$k]);
					}
				}
				$step=1;
				$fms_key_1=-1;
				$fms_key_2=-1;
				$fms_key_3=-1;
				$oldids=array();
				$colids=M("mod_book_article")->selectCols(array(
					"where"=>"bookid=".$bookid." AND status in(0,1,2) " ,
					"order"=>"id asc"
				));
				
				foreach($arr as $v){
					//解析每行
					$dots=substr($v,0,strpos($v,"["));
					if(!preg_match("/\*+/",$dots,$dota)){
						$this->goALl("*格式出错",1);
					}
					$dot=$dota[0];
					$ids=substr($v,0,strpos($v,"]"));
					if(!preg_match("/\d+/",$ids,$ida)){
						$this->goALl("id出错",1);
					}
					$title=substr($v,strpos($v,"]")+1);
					$id=$ida[0];
					if($id!=0){
						$oldids[]=$id;
					}
					
					$row=array(
						"dot"=>$dot,
						"id"=>$id,
						"title"=>$title
					);
					 
					if(strlen($dot)==4){
						
						$fms_key_3++;
						$fms[$fms_key_1]['child'][$fms_key_2]['child'][$fms_key_3]=$row; 
					}elseif(strlen($dot)==2){
						$fms_key_3=-1;
				 		$fms_key_2++;
				 		$fms[$fms_key_1]['child'][$fms_key_2]=$row; 
				 	}else{
				 		$fms_key_1++;
				 		$fms_key_2=-1;
						$fms_key_3=-1;
				 		$fms[$fms_key_1]=$row; 
				 	}
					
				}
				sort($oldids);
				$addIds=array_diff($oldids,$colids);
				$delIds=array_diff($colids,$oldids);
				 
				if($oldids!=$colids){
					$this->goAll("原有文章出错，请刷新",1);
				}
				if(!empty($delIds)){
					foreach($delIds as $articleid){
						M("mod_book_article")->update(array(
							"status"=>11
						),"id=".$articleid);
					}
				}
				
				//处理新增文章
				$aindex=$bindex=$cindex=0;
				$updata=array();
				foreach($fms as $ak=>$a){
					if($a['id']==0){
						$a['id']=M("mod_book_article")->insert(array(
							"pid"=>0,
							"bookid"=>$bookid,
							"title"=>$a['title'],
							"createtime"=>date("Y-m-d H:i:s")
						));
						M("mod_book_article_data")->insert(array(
							"id"=>$a['id']
						));
										
					}
					$a['orderindex']=$aindex;
					$aindex++;
					$bindex=0;
					$a['pid']=0;
					unset($a['dot']);
					$indata=array(
						"id"=>$a['id'],
						"pid"=>$a['pid'],
						"title"=>$a['title'],
						"orderindex"=>$a['orderindex']
					);
					//M("mod_book_article")->update($indata,"id=".$a['id']);
					//批量更新
					$updata[]=$indata;
					$fms[$ak]=$a;	
					if(isset($a['child'])){
						foreach($a['child'] as $bk=>$b){
							if($b['id']==0){
								$b['id']=M("mod_book_article")->insert(array(
									"pid"=>$a['id'],
									"bookid"=>$bookid,
									"title"=>$b['title'],
									"createtime"=>date("Y-m-d H:i:s")
								));
								M("mod_book_article_data")->insert(array(
									"id"=>$b['id']
								));
							}
							$b['pid']=$a['id'];
							$b['orderindex']=$bindex;
							$bindex++;
							$cindex=0;
							unset($b['dot']);
							$indata=array(
								"id"=>$b['id'],
								"pid"=>$b['pid'],
							 
								"title"=>$b['title'],
								"orderindex"=>$b['orderindex']
							);
							//M("mod_book_article")->update($indata,"id=".$b['id']);
							//批量更新
							$updata[]=$indata;
							$fms[$ak]['child'][$bk]=$b;
							if(isset($b['child'])){
								foreach($b['child'] as $ck=>$c){
									if($c['id']==0){
										$c['id']=M("mod_book_article")->insert(array(
											"pid"=>$b['id'],
											"title"=>$c['title'],
											"bookid"=>$bookid,
											"createtime"=>date("Y-m-d H:i:s")
										));
										M("mod_book_article_data")->insert(array(
											"id"=>$c['id']
										));
										
									}
									$c['orderindex']=$cindex;
									$cindex++;
									$c['pid']=$b['id'];
									unset($c['dot']);
									$indata=array(
										"id"=>$c['id'],
										"pid"=>$c['pid'],
										 
										"title"=>$c['title'],
										"orderindex"=>$c['orderindex']
									);
									
									//M("mod_book_article")->update($indata,"id=".$c['id']);
									//批量更新
									$updata[]=$indata;
									$fms[$ak]['child'][$bk]['child'][$ck]=$c;
								}
							}
							
						}
					}
					
				}
				M("mod_book_article")->updateMore($updata);
				
				  
			}
			
			$this->goAll("处理成功");
		}
		
		public function onSave(){
			
			$bookid=get_post("bookid","i");

			$data=M("mod_book")->postData();
			if($bookid){
				M("mod_book")->update($data,"bookid='$bookid'");
			}else{
				M("mod_book")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$bookid=get_post('bookid',"i");
			$status=get_post("status","i");
			M("mod_book")->update(array("status"=>$status),"bookid=$bookid");
			$this->goall("状态修改成功");
		}
		
		public function onDoRecommend(){
			$bookid=get_post('bookid',"i");
			$isrecommend=get_post("isrecommend","i");
			M("mod_book")->update(array("isrecommend"=>$isrecommend),"bookid=$bookid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$bookid=get_post('bookid',"i");
			M("mod_book")->update(array("status"=>11),"bookid=$bookid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>