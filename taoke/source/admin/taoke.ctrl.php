<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class taokeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$where=" status in (0,1,2,3,4) ";
			$url="/moduleadmin.php?m=taoke";
			$catid=get('catid','i');
			if($catid){
				if($catid=="-1"){
					$where.=" AND catid=0";
				}else{
					$cids=MM("taoke","taoke_category")->id_family($catid);
					$where.=" AND catid in("._implode($cids).") ";
				}
				
				$url.="&catid=".$catid;
			}
			$id=get("id","i");
			if($id){
				$where.=" AND id=".$id;
			}
			$word=get('word','h');
			if($word){
				$where.=" AND title like '%".$word."%' ";
				$url.="&word=".urlencode($word);
			}
			$xfrom=get("xfrom","h");
			if($xfrom){
				$where.=" AND xfrom='".$xfrom."' ";
			}
			$status=get("status","h");
			 if($status){
				 $url.="&status=".$status;
				 switch($status){
					case "online":
						$where.=" AND status=1 ";
						break;
					default:
					$where.=" AND status in(0,2) ";
						break;
				 }
			 }
			$isrecommend=get("isrecommend","h");
			 if($isrecommend){
				 $url.="&isrecommend=".$isrecommend;
				 switch($isrecommend){
					case "online":
						$where.=" AND isrecommend=1 ";
						break;
					default:
					$where.=" AND isrecommend=0 ";
						break;
				 }
			 } 
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_taoke")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$cids[]=$v["catid"];
				}
				$cats=MM("taoke","taoke_category")->getListByIds($cids);
				foreach($data as $k=>$v){
					if(isset($cats[$v["catid"]])){
						$v["cat_name"]=$cats[$v["catid"]]["title"];
					}else{
						$v["cat_name"]="";
					}
					$data[$k]=$v;
				}
			}
			$catList=MM("taoke","taoke_category")->children(0);
			 
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			//聚合标签
			$groupList=M("mod_taoke_group")->select(array(
				"where"=>" status=1"
			));	
			$xfromlist=MM("taoke","taoke")->xfromList();
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
					"catList"=>$catList,
					"groupList"=>$groupList,
					"xfromlist"=>$xfromlist
				)
			);
			$this->smarty->display("taoke/index.html");
		}
		
		public function onClear(){
			$t3=time()-3600*24*3;
			$t7=time()-3600*24*7;
			M("mod_taoke")->update(array(
				"status"=>11
			),"juan_end<'".date("Y-m-d")."' ");
			//M("mod_taoke")->delete("dateline<".$t7);
			$this->goAll("清空成功");	
		}
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_taoke")->selectRow(array("where"=>"id={$id}"));
				
			}
			$catList=MM("taoke","taoke_category")->children(0);
			$xfromList=MM("taoke","taoke")->xfromList();
			$this->smarty->goassign(array(
				"data"=>$data,
				"catList"=>$catList,
				"xfromList"=>$xfromList
			));
			$this->smarty->display("taoke/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");
			$tk_end=strtotime(post("tk_end"));
			$data=M("mod_taoke")->postData();
			$data["tk_end"]=$tk_end;
			if($id){
				M("mod_taoke")->update($data,"id='$id'");
			}else{
				$data['dateline']=time();
				M("mod_taoke")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		 
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_taoke")->selectRow("id=".$id);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_taoke")->update(array(
				"status"=>$status
			),"id=".$id);
			$this->goAll("success",0,$status);
		}
		 
		public function onrecommend(){
			$id=get_post('id',"i");
			$row=M("mod_taoke")->selectRow("id=".$id);
			$isrecommend=1;
			if($row["isrecommend"]==1){
				$isrecommend=0;
			}
			M("mod_taoke")->update(array(
				"isrecommend"=>$isrecommend
			),"id=".$id);
			$this->goAll("success",0,$isrecommend);
			$this->goall("推荐修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_taoke")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		/***
		 * 自动设置分类
		 * 
		 */
		public function onAutoCat(){
			$cats=M("mod_taoke_category")->select(array(
			 
				"limit"=>1000
			));
			foreach($cats as $cat){
				$ttlikes="";
				$taoke_tags=$this->taoke_tags($cat['tags']);
				if($taoke_tags){
					foreach($taoke_tags as $k=>$t){
						if($k>0){
							$ttlikes .=" or ";
						}
						$ttlikes.="   title like '%".$t['value']."%' ";
					}
				} else{
					$ttlikes=" title like '%".$cat['title']."%' ";
				}
				$ttneeds=" 1=1 AND ";
				if($cat['tags_need']){
					$ttneeds=" title like '%".$cat['tags_need']."%' AND ";
				}
				 
				M("mod_taoke")->update(array(
					"catid"=>$cat['catid']
				)," catid=0 AND status in(0,1) AND ($ttneeds ( $ttlikes)  )");
			}
			
			echo "分类处理完成";
		}
		
		function taoke_tags($tags){
			if(empty($tags)) return ;
			$arr=explode("，",$tags);
		 
			foreach($arr as $v){
				$data[]=array(
					"name"=>trim($v),
					"value"=>trim($v)
				);
			}
			return $data;
			
		}
		
		public function onCategory(){
			$ids=post('ids','i');
			$catid=post('catid','i');
			if(!$catid) $this->goall("请选择分类",1);
			if($ids){
				foreach($ids as $id){
					M("mod_taoke")->update(array("catid"=>$catid),"id=".$id);
				}
			}
			$this->goall("修改成功");
		}
		public function onGroup(){
			$ids=post('ids','i');
			$gid=post("gid","i");
			if(!$gid){
				$this->goAll("请选择归类",1);
			}
			if(empty($ids)){
				$this->goAll("请选择产品",1);
			}
			$hasids=M("mod_taoke_group_product")->selectCols(array(
				"where"=>" gid=".$gid." AND productid in("._implode($ids).") ",
				"fields"=>"productid"
			));
			$newids=$ids;
			if($hasids){
				$newids=array_diff($ids,$hasids);
			}
			if(!empty($newids)){
				foreach($newids as $productid){
					M("mod_taoke_group_product")->insert(array(
						"gid"=>$gid,
						"productid"=>$productid,
						"orderindex"=>11
					));
				}
			}
			$this->goAll("success");
		}
		
		
	}

?>