<?php
    /**
    *Author 雷日锦 362606856@qq.com
    *控制器自动生成
    */
    if (!defined("ROOT_PATH")) {
        exit("die Access ");
    }
    class taokeControl extends skymvc
    {
        public function __construct()
        {
            parent::__construct();
        }
        
        public function onInit()
        {
        }
        
        public function taoke_tags($tags)
        {
            $arr=explode("，", $tags);
         
            foreach ($arr as $v) {
                $data[]=array(
                    "name"=>trim($v),
                    "value"=>trim($v)
                );
            }
            return $data;
        }
        public function onDefault()
        {
            
            //require_once(ROOT_PATH."module/taoke/tags.php");
            $config=M("mod_taoke_config")->selectRow();
            $taoke_tags=$this->taoke_tags($config['tags']);
            
			$etime=date("Y-m-d",time()-3600*48);
			$fromplatform=get("fromplatform","h");
			switch($config["opmode"]){
				case 1:
					if($fromplatform=='mp-weixin'){
						
						$recList=MM("taoke","taoke_group")->getProductByKey("pdd_recommend"," AND status=1 ");
						$newList=MM("taoke","taoke_group")->getProductByKey("pdd_new"," AND status=1 ");
					}else{
						$recList=MM("taoke","taoke_group")->getProductByKey("recommend"," AND status=1 ");
						$newList=MM("taoke","taoke_group")->getProductByKey("new"," AND status=1 ");
						 
					}
					
					break;
				case 0:
					$k="taobao";
					$yj_bl=500;
					$yj_money=5;
					if($fromplatform=='mp-weixin'){
						$k='pdd';
						$yj_money=5;
					}
					$recList=MM("taoke","taoke_searchcache")->Dselect(array(
						"where"=>" k='".$k."' AND isrecommend=1 ",
						"order"=>" love_num DESC,sold_num DESC",
						"limit"=>12
					));
					$newList=MM("taoke","taoke_searchcache")->Dselect(array(
						"where"=>" k='".$k."' AND ishot=1  ",
						"order"=>" love_num DESC,sold_num DESC",
						"limit"=>48
					));
					break;
			}
			
            //
            $fromapp=get("fromapp");
            switch ($fromapp) {
                case "uniapp":
                    $flashList=M("ad")->listByNo("uniapp-taoke-index");
                    $adList=M("ad")->listByNo("uniapp-taoke-ad");
					if($fromplatform=='mp-weixin'){
						$navList=M("ad")->listByNo("uniapp-taoke-nav-mpweixin");
					}else{
						$navList=M("ad")->listByNo("uniapp-taoke-nav");
					}
                    break;
                default:
                    $flashList=M("ad")->listByNo("wap-taoke-index");
                    $adList=M("ad")->listByNo("wap-taoke-ad");
                    $navList=M("ad")->listByNo("wap-taoke-nav");
                    break;
            }
            $seo=M("seo")->get("taoke","default");
			$site=M("site")->get();
            $this->smarty->goassign(
                array(
					"seo"=>$seo,
                    "recList"=>$recList,
					"newList"=>$newList,
                    "taoke_tags"=>$taoke_tags,
                    "site"=>$site,
                    "flashList"=>$flashList,
                    "adList"=>$adList,
                    "navList"=>$navList,
					"opmode"=>$config["opmode"]
                )
            );
            $this->smarty->display("taoke/index.html");
        }
        
        public function onList()
        {
            $where=" status=1 ";
            $url="/module.php?m=taoke&a=list";
            $catid=get('catid', 'i');
            $tagname=get_post('tagname', 'h');
            $cat=M("mod_taoke_category")->selectRow("catid=".$catid);
            if ($catid) {
                !$tagname && $where.=" AND catid=".$catid;
                $url.="&catid=".$catid;
            }
            
            if ($tagname) {
                if ($cat['tags_need']) {
                    $where.=" AND title like '%".$cat['tags_need']."%' ";
                }
                //拆分 男 女
                if (preg_match("/(男|女)/", $tagname, $a)) {
                    $s1=$a[1];
                    $s2=str_replace($a[1], "", $tagname);
                    $where.=" AND (title like '%".$s1."%' AND title like '%".$s2."%') ";
                } else {
                    $where.=" AND title like '%".$tagname."%' ";
                }
                
                $url.="&tagname=".urlencode($tagname);
            }
            $idstr=get("ids");
            if (!empty($idstr)) {
                $idss=explode(",", $idstr);
                $ids=array();
                foreach ($idss as $v) {
                    if (intval($v)>0) {
                        $ids[]=intval($v);
                    }
                }
                $where.=" AND id in("._implode($ids).") ";
            }
			//排序
			$order=" id DESC";
			$orderby=get("orderby","h");
			if($orderby){
				switch($orderby){
					case "sold_num":
						$order=" sold_num DESC ";
						break;
					case "priceAsc":
						$order=" price ASC ";
						break;
					case "maxBack":
						$order=" yj_bl DESC,yj_money DESC ";
						break;
				}
			}
            $limit=20;
            $start=get("per_page", "i");
            $option=array(
                "start"=>$start,
                "limit"=>$limit,
                "order"=>$order,
                "where"=>$where
            );
            $rscount=true;
            $data=M("mod_taoke")->select($option, $rscount);
            if ($data) {
                foreach ($data as $k=>$v) {
                    $v['imgurl']=images_site($v['imgurl']);
                    $data[$k]=$v;
                }
            }
            $catList=MM("taoke", "taoke_category")->children($catid); 
            $pagelist=$this->pagelist($rscount, $limit, $url);
            $per_page=$start+$limit;
            $per_page=$per_page>$rscount?0:$per_page;
                
            $taoke_tags=array();
            if (!empty($cat['tags'])) {
                $taoke_tags=$this->taoke_tags($cat['tags']);
            }
             
            $this->smarty->goassign(
                array(
                     
                    "taoke_tags"=>$taoke_tags,
                    "cat"=>$cat,
                    "data"=>$data,
                    "catList"=>$catList,
                    "pagelist"=>$pagelist,
                    "rscount"=>$rscount,
                    "url"=>$url,
                    "per_page"=>$per_page,
                )
            );
             
            $this->smarty->display("taoke/list.html");
        }
        
        
        public function onSearch()
        {
            $where=" status=1 ";
			$limit=20;
			$start=get("per_page", "i");
            $url="/module.php?m=taoke&a=search";
            $word=get('word', 'h');
            if ($word) {
                $where.=" AND title like '%".$word."%' ";
                $url.="&word=".urlencode($word);
            }
			//排序
			$order=" id DESC";
			$orderby=get("orderby","h");
			if($orderby){
				switch($orderby){
					case "sold_num":
						$order=" sold_num DESC ";
						break;
					case "priceAsc":
						$order=" price ASC ";
						break;
					case "maxBack":
						$order=" yj_bl DESC,yj_money DESC ";
						break;
				}
			} 
            
            $option=array(
                "start"=>$start,
                "limit"=>$limit,
                "order"=>$order,
                "where"=>$where
            );
            $rscount=true;
            $data=M("mod_taoke")->select($option, $rscount);
            if ($data) {
                foreach ($data as $k=>$v) {
                    $v['imgurl']=images_site($v['imgurl']);
                    $data[$k]=$v;
                }
            }
        
            $pagelist=$this->pagelist($rscount, $limit, $url);
            $per_page=$start+$limit;
            $per_page=$per_page>$rscount?0:$per_page;
            
            $this->smarty->goassign(
                array(
                    "word"=>$word,
                    
                    "list"=>$data,
                    "pagelist"=>$pagelist,
                    "rscount"=>$rscount,
                    "url"=>$url,
                    "per_page"=>$per_page,
                )
            );
             
            $this->smarty->display("taoke/search.html");
        }
        
        public function on99()
        {
            $where=" status=1 AND price=9.9";
            $url="/module.php?m=taoke&a=99";
            $catid=get('catid', 'i');
            $tagname=get_post('tagname', 'h');
            $cat=M("mod_taoke_category")->selectRow("catid=".$catid);
            if ($catid) {
                !$tagname && $where.=" AND catid=".$catid;
                $url.="&catid=".$catid;
            }
            
            if ($tagname) {
                if ($cat['tags_need']) {
                    $where.=" AND title like '%".$cat['tags_need']."%' ";
                }
                //拆分 男 女
                if (preg_match("/(男|女)/", $tagname, $a)) {
                    $s1=$a[1];
                    $s2=str_replace($a[1], "", $tagname);
                    $where.=" AND (title like '%".$s1."%' AND title like '%".$s2."%') ";
                } else {
                    $where.=" AND title like '%".$tagname."%' ";
                }
                
                $url.="&tagname=".urlencode($tagname);
            }
            $limit=20;
            $start=get("per_page", "i");
            $option=array(
                "start"=>$start,
                "limit"=>$limit,
                "order"=>" id DESC",
                "where"=>$where
            );
            $rscount=true;
            $data=M("mod_taoke")->select($option, $rscount);
            if ($data) {
                foreach ($data as $k=>$v) {
                    $v['imgurl']=images_site($v['imgurl']);
                    $data[$k]=$v;
                }
            }
            $catList=MM("taoke", "taoke_category")->children($catid);
            $pagelist=$this->pagelist($rscount, $limit, $url);
            $per_page=$start+$limit;
            $per_page=$per_page>$rscount?0:$per_page;
                
            $taoke_tags=array();
            if (!empty($cat['tags'])) {
                $taoke_tags=$this->taoke_tags($cat['tags']);
            }
             
            $this->smarty->goassign(
                array(
                     
                    "taoke_tags"=>$taoke_tags,
                    "cat"=>$cat,
                    "data"=>$data,
                    "catList"=>$catList,
                    "pagelist"=>$pagelist,
                    "rscount"=>$rscount,
                    "url"=>$url,
                    "per_page"=>$per_page,
                )
            );
             
            $this->smarty->display("taoke/99.html");
        }
        
		 
		
        public function onHot()
        {
            $where=" status=1  ";
            $url="/module.php?m=taoke&a=hot";
            $catid=get('catid', 'i');
            $tagname=get_post('tagname', 'h');
            $cat=M("mod_taoke_category")->selectRow("catid=".$catid);
            if ($catid) {
                !$tagname && $where.=" AND catid=".$catid;
                $url.="&catid=".$catid;
            }
            
            if ($tagname) {
                if ($cat['tags_need']) {
                    $where.=" AND title like '%".$cat['tags_need']."%' ";
                }
                //拆分 男 女
                if (preg_match("/(男|女)/", $tagname, $a)) {
                    $s1=$a[1];
                    $s2=str_replace($a[1], "", $tagname);
                    $where.=" AND (title like '%".$s1."%' AND title like '%".$s2."%') ";
                } else {
                    $where.=" AND title like '%".$tagname."%' ";
                }
                
                $url.="&tagname=".urlencode($tagname);
            }
            $limit=20;
            $start=get("per_page", "i");
            $option=array(
                "start"=>$start,
                "limit"=>$limit,
                "order"=>" sold_num DESC,id DESC",
                "where"=>$where
            );
            $rscount=true;
            $data=M("mod_taoke")->select($option, $rscount);
            if ($data) {
                foreach ($data as $k=>$v) {
                    $v['imgurl']=images_site($v['imgurl']);
                    $data[$k]=$v;
                }
            }
            $catList=MM("taoke", "taoke_category")->children($catid);
            $pagelist=$this->pagelist($rscount, $limit, $url);
            $per_page=$start+$limit;
            $per_page=$per_page>$rscount?0:$per_page;
                
            $taoke_tags=array();
            if (!empty($cat['tags'])) {
                $taoke_tags=$this->taoke_tags($cat['tags']);
            }
             
            $this->smarty->goassign(
                array(
                     
                    "taoke_tags"=>$taoke_tags,
                    "cat"=>$cat,
                    "data"=>$data,
                    "catList"=>$catList,
                    "pagelist"=>$pagelist,
                    "rscount"=>$rscount,
                    "url"=>$url,
                    "per_page"=>$per_page,
                )
            );
             
            $this->smarty->display("taoke/hot.html");
        }
        public function onShow()
        {
            $id=get_post("id", "i");
            $userid=M("login")->userid;
            $data=M("mod_taoke")->selectRow(array("where"=>"id=".$id));
            if (!$data || $data['status']!=1) {
                $this->goAll("数据出错", 1);
            }
			if(empty($data["content"])){
				
			}
            $data['imgurl']=images_site($data['imgurl']);
            $reclist=M("mod_taoke")->select(array(
                "start"=>0,
                "limit"=>100,
                "where"=>" status=1 AND catid=".$data['catid'],
                "order"=>"id ASC",
                "fields"=>"id,title,imgurl,price,juan_money,sold_num"
            ));
            shuffle($reclist);
            $reclist=array_slice($reclist, 0, 24);
            if ($reclist) {
                foreach ($reclist as $k=>$v) {
                    $v['imgurl']=images_site($v['imgurl']);
                     
                    $reclist[$k]=$v;
                }
            }
            $isfav=0;
            if ($userid) {
                $row=M("fav")->selectRow("tablename='mod_taoke' AND objectid=".$id);
                if ($row) {
                    $isfav=1;
                }
            }
            
            $this->smarty->goassign(array(
                "data"=>$data,
                "reclist"=>$reclist,
                "isfav"=>$isfav
            ));
            
            $this->smarty->display("taoke/show.html");
        }
		
		public function onUpdateContent(){
			$id=get_post("id", "i");
			include ROOT_PATH."/module/taoke/sdk/TopSdk.php";
			$config=M("mod_taoke_config")->selectRow();
			$c = new TopClient;
			$c->appkey = $config['appkey'];
			$c->secretKey = $config['secretKey'];
			$req = new TbkItemInfoGetRequest;
			$req->setNumIids("43561457746");
			$req->setPlatform("1");
			//$req->setIp("11.22.33.43");
			$resp = $c->execute($req);
			print_r($resp);
		}
		
    }
