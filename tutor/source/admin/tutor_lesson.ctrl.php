<?php
    /**
    *Author 雷日锦 362606856@qq.com
    *控制器自动生成
    */
    if (!defined("ROOT_PATH")) {
        exit("die Access ");
    }
    class tutor_lessonControl extends skymvc
    {
        public function __construct()
        {
            parent::__construct();
        }
        
        public function onDefault()
        {
            $type=get("type","h");
            switch($type){
            	case "new":
            		$where=" status=0 ";
            		$type_name="待审帖子";
            		break;
            	case "pass":
            		$where=" status=2 ";
            		$type_name="上架帖子";
            		break;
            	case "forbid":
            		$where=" status=2 ";
            		$type_name="下架帖子";
            		break;
            	default:
            		$where=" status in(0,1,2) " ;
            		$type_name="全部帖子";
            		break;
            }
            
            
            $url="/moduleadmin.php?m=tutor_lesson&type=".$type;
			$title=get('title','h');
			if($title){
				$where.=" AND title like '%".$title."%'";
				$url.="&title=".urlencode($title);
			}
			$isrecommend=get("isrecommend","i");
			if($isrecommend){
				$where.=" AND isrecommend=1 ";
			}
            $limit=20;
            $start=get("per_page", "i");
            $option=array(
                "start"=>$start,
                "limit"=>$limit,
                "order"=>" lessonid DESC",
                "where"=>$where
            );
            $rscount=true;
            $data=M("mod_tutor_lesson")->select($option, $rscount);
			if($data){
				$catids=[];
				foreach($data as  $v){
					$catids[]=$v["catid"];
				}
				$cats=MM("tutor","tutor_category")->getListByIds($catids);
			
				foreach($data as $k=>$v){
					$v["cat"]=$cats[$v["catid"]];
					$data[$k]=$v;
				}
			}
            $per_page=$start+$limit;
            $per_page=$per_page>$rscount?0:$per_page;
            $pagelist=$this->pagelist($rscount, $limit, $url);
            $this->smarty->goassign(
                array(
                    "list"=>$data,
                    "per_page"=>$per_page,
                    "pagelist"=>$pagelist,
                    "rscount"=>$rscount,
                    "url"=>$url,
					"type"=>$type,
					"type_name"=>$type_name
                )
            );
            $this->smarty->display("tutor_lesson/index.html");
        }
        
        public function onAdd()
        {
            $lessonid=get_post("lessonid", "i");
            if ($lessonid) {
                $data=M("mod_tutor_lesson")->selectRow(array("where"=>"lessonid=".$lessonid));
            }
            $catlist=MM("tutor", "tutor_category")->children(0);
            $this->smarty->goassign(array(
                "data"=>$data,
                "catlist"=>$catlist
            ));
            $this->smarty->display("tutor_lesson/add.html");
        }
        
        public function onSave()
        {
            $lessonid=get_post("lessonid", "i");
            $data=M("mod_tutor_lesson")->postData();
            if ($lessonid) {
                M("mod_tutor_lesson")->update($data, "lessonid='$lessonid'");
            } else {
				$data["dateline"]=time();
                M("mod_tutor_lesson")->insert($data);
            }
            $this->goall("保存成功");
        }
        
        public function onStatus()
        {
            $lessonid=get_post('lessonid', "i");
			$row=M("mod_tutor_lesson")->selectRow("lessonid=".$lessonid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
             
            M("mod_tutor_lesson")->update(array("status"=>$status), "lessonid=".$lessonid);
            $this->goall("状态修改成功",0,$status);
        }
        
		public function onRecommend()
		{
		    $lessonid=get_post('lessonid', "i");
			$row=M("mod_tutor_lesson")->selectRow("lessonid=".$lessonid);
			if($row["isrecommend"]==1){
				$status=0;
			}else{
				$status=1;
			}
		     
		    M("mod_tutor_lesson")->update(array("isrecommend"=>$status), "lessonid=".$lessonid);
		    $this->goall("状态修改成功",0,$status);
		}
		
        public function onDelete()
        {
            $lessonid=get_post('lessonid', "i");
            M("mod_tutor_lesson")->update(array("status"=>11), "lessonid=$lessonid");
            $this->goAll("删除成功");
        }
    }
