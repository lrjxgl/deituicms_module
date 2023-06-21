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
            $where="shopid=".SHOPID." AND status in(0,1,2)";
            $url="/moduleadmin.php?m=tutor_lesson&a=default";
            $limit=20;
            $start=get("per_page", "i");
            $option=array(
                "start"=>$start,
                "limit"=>$limit,
                "order"=>" lessonid DESC",
                "where"=>$where
            );
            $rscount=true;
            $data=MM("tutor","tutor_lesson")->Dselect($option, $rscount);
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
                    "url"=>$url
                )
            );
            $this->smarty->display("tutor_lesson/index.html");
        }
        
        public function onAdd()
        {
            $lessonid=get_post("lessonid", "i");
            if ($lessonid) {
                $data=M("mod_tutor_lesson")->selectRow(array("where"=>"lessonid=".$lessonid));
				$data["trueimgurl"]=images_site($data["imgurl"]);
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
			$content=post("content","x"); 
            $data=M("mod_tutor_lesson")->postData();
			$data["status"]=0;
			$data["ispass"]=0;
			$data["content"]=$content;
			if($data["total_num"]>10){
				$this->goAll("库存不能大于10",1);
			}
            if ($lessonid) {
				$row=M("mod_tutor_lesson")->selectRow("lessonid=".$lessonid);
				if($row["shopid"]!=SHOPID){
					$this->goAll("暂无权限",1);
				}
                M("mod_tutor_lesson")->update($data, "lessonid='$lessonid'");
            } else {
				$data["shopid"]=SHOPID;
				$data["dateline"]=time();
                M("mod_tutor_lesson")->insert($data);
            }
            $this->goall("保存成功");
        }
        
        public function onStatus()
        {
            $lessonid=get_post('lessonid', "i");
			$row=M("mod_tutor_lesson")->selectRow("lessonid=".$lessonid);
			 
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
             
            M("mod_tutor_lesson")->update(array("status"=>$status), "lessonid=".$lessonid);
            $this->goall("状态修改成功",0,$status);
        }
        
        public function onDelete()
        {
            $lessonid=get_post('lessonid', "i");
			$row=M("mod_tutor_lesson")->selectRow("lessonid=".$lessonid);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
            M("mod_tutor_lesson")->update(array("status"=>11), "lessonid=$lessonid");
            $this->goAll("删除成功");
        }
    }
