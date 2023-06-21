<?php
class aichat_img2imgControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("aichat_img2img/index.html");
	}
	
	public function onsave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$oimg=post("oimg","h");
		if(empty($oimg)){
			$this->goAll("图片不能为空",1);
		}
		$prompt=trim(post("prompt","x"));
		if(empty($prompt)){
			$this->goAll("提示词不能为空",1);
		}
		$negative_prompt=trim(post("negative_prompt"));
		//查看未完成的
		$ct=M("mod_aichat_img2img_task")->selectOne(array(
			"where"=>" userid=".$userid." AND create_status=0 ",
			"fields"=>" count(*)"
		));
		if($ct>10 && $userid!=4){
			$this->goAll("你已经有5张图片正在处理,请稍后再添加..",1);
		}
		//处理金币
		$cfg=MM("aichat","aichat_config")->get();
		$token=$cfg["img_pay_token"];
		$aiuser=MM("aichat","aichat_user")->get($userid);
		if($aiuser["token"]<$token && $aiuser["vip_etime"]<time()){
			$this->goAll("可消费Token数量不足",1);
		}
		//查看prompt是否已经有了
		$pro=M("mod_aichat_img2img_prompt")->selectRow("prompt='".$prompt."'");
		if(!empty($pro)){
			$prompt_en=$pro["prompt_en"];
			$negative_prompt_en=$pro["negative_prompt_en"];
			M("mod_aichat_img_prompt")->update(array(
				"create_num"=>$pro["create_num"]+1
			),"promptid=".$pro["promptid"]);
			$promptid=$pro["promptid"];
		}else{
			//翻译成英文
			require ROOT_PATH."/module/aichat/xffanyi.php";
			$xfConfig=M("open_xunfei")->get();
			$fy = new xffanyi($xfConfig);
			$fs=$fy->xfyun($prompt);
			if($fs["error"]==0){
				$prompt_en=$fs["content"];
			}else{
				$prompt_en=$prompt;
			}
			$fs2=$fy->xfyun($negative_prompt);
			if($fs2["error"]==0){
				$negative_prompt_en=$fs2["content"];
			}else{
				$negative_prompt_en=$negative_prompt;
			}
			$promptid=M("mod_aichat_img2img_prompt")->insert(array(
				"prompt"=>$prompt,
				"prompt_en"=>$prompt_en,
				"negative_prompt"=>$negative_prompt,
				"negative_prompt_en"=>$negative_prompt_en,
				"userid"=>$userid,
				"create_num"=>1,
				"createtime"=>date("Y-m-d H:i:s")
			));
		}
		
		M("user")->begin();
		MM("aichat","aichat_user")->addToken(array(
			"userid"=>$userid,
			"num"=>-$token,
			"content"=>"生成图片消耗".$token."个token"
		));
		$id=M("mod_aichat_img2img_task")->insert(array(
			"prompt"=>$prompt,
			"prompt_en"=>$prompt_en,
			"negative_prompt"=>$negative_prompt,
			"negative_prompt_en"=>$negative_prompt_en,
			"oimg"=>$oimg,
			"userid"=>$userid,
			"promptid"=>$promptid,
			"createtime"=>date("Y-m-d H:i:s")
		));
		$data=M("mod_aichat_img2img_task")->selectRow("id=".$id);
		//插入到队列
		$finishdata=array(
			"tablename"=>"mod_aichat_img2img",
			"id"=>$id,
			"action"=>"create_img2img"
		);
		$queuekey="aichat_edit_img";
		$queue=new queue($queuekey);
		$queue->lpush(array(
			"action"=>"create_img2img",
			"oimg"=>images_site($oimg),
			"prompt"=>$prompt,
			"prompt_en"=>$prompt_en,
			"prompt_en"=>$prompt_en,
			"negative_prompt"=>$negative_prompt,
			"negative_prompt_en"=>$negative_prompt_en,
			"finishdata"=>arr2str($finishdata)
		));
		M("user")->commit();
		$this->goAll("正在创作图片中..",0,$data);
	}
	
	public function onGet(){
		$id=get_post("id","i");
		$data=M("mod_aichat_img2img_task")->selectRow("id=".$id);
		$data["imgurl"]=images_site($data["imgurl"]);
		$this->goAll("success",0,$data);
	}
}