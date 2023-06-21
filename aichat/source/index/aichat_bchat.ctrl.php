<?php
class aichat_bchatControl extends skymvc{
	public function onDefault(){
		$id=get("id","i");
		$this->smarty->goAssign(array(
			"id"=>$id
		));
		$this->smarty->display("aichat_bchat/index.html");
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$rscount=true;
		$start=get("per_page","i");
		$limit=24;
		$list=M("mod_aichat_bchat")->select(array(
			"where"=>" userid=".$userid." AND status in(0,1,2)",
			"order"=>" id DESC",
			"fields"=>"id,prompt",
			"limit"=>$limit,
			"start"=>$start
		),$rscount);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->goAll("success",0,array(
			"list"=>$list,
			"per_page"=>$per_page
		));
	}
	public function onDelete(){
		$id=get_post("id","i");
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$row=M("mod_aichat_bchat")->selectRow("id=".$id);
		if($row["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		M("mod_aichat_bchat")->update(array("status"=>4),"id=".$id);
		$this->goAll("success");
	}
	public function onGetMsg(){
		M("login")->checkLogin();
		$id=get_post("id","i");
		$row=M("mod_aichat_bchat")->selectRow(" id=".$id." AND create_status=1");
		if(empty($row)){
			$this->goAll("error",1);
		}else{
			$res=str2arr($row["history"]);
			$msgList=[];
			 
			if(!empty($res)){
				foreach($res as $rs){
					$msgList[]=array(
						"role"=>"ask",
						"content"=>$rs[0]
					);
					$msgList[]=array(
						"role"=>"answer",
						"content"=>$rs[1]
					);
				}
			}
			$this->goAll("success",0,array(
				"msgList"=>$msgList,
				"stream_status"=>$row["stream_status"]
			));
		}
	}
	public function onSend(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		//检测vip
		$aiuser=MM("aichat","aichat_user")->get($userid);
		if($aiuser["vip_etime"]<time()){
			//$this->goAll("您的Vip已经到期了",11);
		}
		$prompt=trim(post("prompt","x"));
		if(empty($prompt)){
			$this->goAll("提示词不能为空",1);
		}
		$id=get_post("id","i");
		$history=[];
		if($id){
			$row=M("mod_aichat_bchat")->selectRow(" id=".$id);
			$history=str2arr($row["history"]);
			if(!$history){
				$history=[];
			}
			M("mod_aichat_bchat")->update(array(
				 
				"create_status"=>0,
				"stream_status"=>0,
				"createtime"=>date("Y-m-d H:i:s")
			),"id=".$id);
		}else{
			
			$id=M("mod_aichat_bchat")->insert(array(
				"prompt"=>cutstr($prompt,30),
				"userid"=>$userid,
				 
				"createtime"=>date("Y-m-d H:i:s")
			));
		}
		if(substr($prompt,0,7)=="/画画"){
			require ROOT_PATH."/module/aichat/xffanyi.php";
			$xfConfig=M("open_xunfei")->get();
			$fy = new xffanyi($xfConfig);
			$prompt=trim(str_replace("/画画 ","",$prompt));
			$fs=$fy->xfyun($prompt);
			if($fs["error"]==0){
				$prompt_en=$fs["content"];
			}else{
				$prompt_en=$prompt;
			}
			//插入到队列
			$finishdata=array(
				"tablename"=>"mod_create_bchat_img",
				"id"=>$id,
				"action"=>"create_bchat_img"
			);
			$queuekey="aichat_create_img";
			$queue=new queue($queuekey);
			$queue->lpush(array(
				"action"=>"create_bchat_img",
				"prompt"=>$prompt,
				"prompt_en"=>$prompt_en,
				"history"=>$history,
				"finishdata"=>arr2str($finishdata)
			));
		}elseif(post("img")!=='' && post('task')=='img_caption'){
			$img=post("img");
			$queuekey="aichat_img_caption";
			$finishdata=array(
				
				"id"=>$id,
				"action"=>"img_caption"
			);
			$queue=new queue($queuekey);
			
			$queue->lpush(array(
				"action"=>"img_caption",
				"prompt"=>$prompt,
				"history"=>$history,
				"image"=>$img,
				"finishdata"=>arr2str($finishdata)
			));
		}else{
			if(substr($prompt,0,7)=="/search"){
				
				$sprompt=str_replace("/search","",$prompt);
				$list=MM("aichat","aichat_mapp")->search($sprompt);
				$history=[]; 
				$history[]=array("请你根据我接下来提供给你的资料，后面回答我的问题。","好的");
				if(!empty($list)){
					foreach($list as $item){
						$history[]=array($item,"好的");
					}
					$history[]=array("根据以上资料回答我的问题","好的");
				}
			}
			//插入到队列
			$finishdata=array(
				"tablename"=>"mod_create_bchat",
				"id"=>$id,
				"action"=>"create_bchat"
			);
			$img=post("img");
			if($img!=''){
				$img.=".small.jpg";
			}
			$queuekey="aichat_create_text";
			$queue=new queue($queuekey);
			$queue->lpush(array(
				"action"=>"create_bchat",
				"prompt"=>$prompt,
				"history"=>$history,
				"image"=>$img,
				"finishdata"=>arr2str($finishdata)
			));
		}
		
		$this->goAll("正在创作中..",0,array(
			"id"=>$id,
			"history"=>$list
		));
	}
	
	public function ondelitem(){
		$id=get_post("id","i");
		$index=get_post("index","i");
		$row=M("mod_aichat_bchat")->selectRow(" id=".$id);
		$history=str2arr($row["history"]);
		if(!$history){
			$history=[];
		}
		$len=count($history);
		$history2=[];
		$delIndex=($index-1)/2;
		for($i=0;$i<$len;$i++){
			if($i==$delIndex){
				continue;
			}
			$history2[]=$history[$i];
		}
		M("mod_aichat_bchat")->update(array(
			 
			"history"=>arr2str($history2)
		),"id=".$id);
		$this->goAll("删除成功",0,$history2);
	}
	
}