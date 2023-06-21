<?php
class aichat_queueControl extends skymvc{
	
	public function onDefault(){
		
	}
	 
	public function onGet(){
		$apptoken=get_post("apptoken","h");
		$cfg=MM("aichat","aichat_config")->get();
		if($cfg["apptoken"]!=$apptoken){
			echo json_encode(array(
				"error"=>1,
				"message"=>"token error",
				"queuekey"=>$queuekey
			));
			exit;
		}
		$queuekey=get("queuekey","h");
		$queuekey=$queuekey==''?'aichat_create_img':$queuekey;
		$queue=new queue($queuekey);
		$task=$queue->rpop();
		if(empty($task)){
			echo json_encode(array(
				"error"=>1,
				"message"=>"empty task",
				"queuekey"=>$queuekey
			));
			exit; 
		}else{
			echo json_encode(array(
				"error"=>0,
				"message"=>"get task",
				"data"=>$task
			));
			exit; 
		}
	}
	public function upload_oss($files,$delFile=true){
		
		if(!UPLOAD_OSS) return false;
		if(empty($files)) return false;
		include_once(ROOT_PATH."api/ossapi/ossapi.php"); 
		$arr=array("",".100x100.jpg",".small.jpg",".middle.jpg");
		foreach($arr as $a){		
			if(file_exists(ROOT_PATH.$files.$a)){
				$to=str_replace("//","/",$files.$a);
				$from=ROOT_PATH.$files.$a;
				 
				$response = oss_upload_file(array("bucket"=>OSS_BUCKET,"to"=>$to,"from"=>$from));
				 
				if(defined("UPLOAD_DEL") && UPLOAD_DEL && $delFile){
					@unlink($from);
				}
			}
		}
	}
	public function onFinish(){
		session_write_close();
		$apptoken=get_post("apptoken","h");
		$cfg=MM("aichat","aichat_config")->get();
		if($cfg["apptoken"]!=$apptoken){
			echo json_encode(array(
				"error"=>1,
				"message"=>"token error",
				"queuekey"=>$queuekey
			));
			exit;
		}
		$_GET["ajax"]=1;
		/*$f=file_get_contents("temp/aichat_test.txt");
		$_POST=json_decode($f,true);
		*/ 
		$task=str2arr(post("finishdata"));
		 
		//file_put_contents("temp/aichat_test.txt",json_encode($_REQUEST));
		
		switch($task["action"]){
			case "create_img":
				$image64=post("image64");
				$dir="atatch/aichat/".date("Y/m/d");
				umkdir($dir);
				$imgurl=$dir."/".(M("maxid")->get()).".png";
				file_put_contents($imgurl,base64_decode($image64));
				M("attach")->add(array(
					"url"=>$imgurl
				));
				//缩略图
				$img=new image();
				$img->makethumb($imgurl.".100x100.jpg",$imgurl,100,100,1);
				$img->makethumb($imgurl.".small.jpg",$imgurl,480);
				$this->upload_oss($imgurl);
				//
				
				
				M("mod_aichat_img_task")->update(array(
					"imgurl"=>$imgurl,
					"create_status"=>1
				),"id=".$task["id"]);
				//插如
				$data=M("mod_aichat_img_task")->selectRow("id=".$task["id"]);
				unset($data["id"]);
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_aichat_img")->insert($data);
				break;
			case "create_img2img":
				$image64=post("image64");
				$dir="atatch/aichat/".date("Y/m/d");
				umkdir($dir);
				$imgurl=$dir."/".(M("maxid")->get()).".png";
				file_put_contents($imgurl,base64_decode($image64));
				M("attach")->add(array(
					"url"=>$imgurl
				));
				//缩略图
				$img=new image();
				$img->makethumb($imgurl.".100x100.jpg",$imgurl,100,100,1);
				$img->makethumb($imgurl.".small.jpg",$imgurl,480);
				$this->upload_oss($imgurl);
				//
				
				
				M("mod_aichat_img2img_task")->update(array(
					"imgurl"=>$imgurl,
					"create_status"=>1
				),"id=".$task["id"]);
				//插如
				$data=M("mod_aichat_img2img_task")->selectRow("id=".$task["id"]);
				unset($data["id"]);
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_aichat_img2img")->insert($data);
				break;
			case "create_imgscale":
				$image64=post("image64");
				$dir="atatch/aichat/".date("Y/m/d");
				umkdir($dir);
				$imgurl=$dir."/".(M("maxid")->get()).".png";
				file_put_contents($imgurl,base64_decode($image64));
				M("attach")->add(array(
					"url"=>$imgurl
				));
				//缩略图
				$img=new image();
				$img->makethumb($imgurl.".100x100.jpg",$imgurl,100,100,1);
				$img->makethumb($imgurl.".small.jpg",$imgurl,480);
				$this->upload_oss($imgurl);
				//
				
				
				M("mod_aichat_imgscale_task")->update(array(
					"imgurl"=>$imgurl,
					"create_status"=>1
				),"id=".$task["id"]);
				//插如
				$data=M("mod_aichat_imgscale_task")->selectRow("id=".$task["id"]);
				unset($data["id"]);
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_aichat_imgscale")->insert($data);
				break;
			case "create_text":
				$content=post("content","x");
				$title=post("title","h");
				$title=str_replace("&amp;","",$title);
				$title=str_replace("&","",$title);
				$title=str_replace("quot;","",$title);
				M("mod_aichat_text_task")->update(array(
					"content"=>nl2br($content),
					"title"=>$title,
					"create_status"=>1
				),"id=".$task["id"]);
				//插如
				$data=M("mod_aichat_text_task")->selectRow("id=".$task["id"]);
				unset($data["id"]);
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_aichat_text")->insert($data);
				break;
			case "create_bchat":
				$answer= stripslashes($_POST["answer"]);
				$prompt=stripslashes($_POST["prompt"]);  
				$type=get("type","h");
				$row=M("mod_aichat_bchat")->selectRow("id=".$task["id"]);
				$history=[];
				$res=str2arr($row["history"]);
				if(!empty($res)){
					$history=$res;
				}
				//stream模式
				file_put_contents("temp/aichat_queue_".$task["id"].".txt",$answer."\r\n");
				$stream_status=1;
				if($type=='stream'){
					$stream_status=0;
				}
				if($type=='stream' && $row["create_status"]==1){
					$max=count($history)-1;
					$history[$max]=array($prompt,$answer);
				}elseif($type=='streamfinish'){
					$stream_status=1;
				}else{					
					$history[]=array($prompt,$answer);				
				}
				M("mod_aichat_bchat")->update(array(
					"history"=>arr2str($history),
					"create_status"=>1,
					"stream_status"=>$stream_status
				),"id=".$task["id"]);
				break;
			case "create_bchat_img":
				 
				$prompt=stripslashes($_POST["prompt"]);
				$image64=post("image64");
				$dir="atatch/aichat/".date("Y/m/d");
				umkdir($dir);
				$imgurl=$dir."/".(M("maxid")->get()).".png";
				file_put_contents($imgurl,base64_decode($image64));
				M("attach")->add(array(
					"url"=>$imgurl
				));
				//缩略图
				$img=new image();
				$img->makethumb($imgurl.".100x100.jpg",$imgurl,100,100,1);
				$img->makethumb($imgurl.".small.jpg",$imgurl,480);
				$this->upload_oss($imgurl);
				//处理内容
				$row=M("mod_aichat_bchat")->selectRow("id=".$task["id"]);
				$history=[];
				$res=str2arr($row["history"]);
				if(!empty($res)){
					$history=$res;
				}
				$answer="![".$prompt."](".images_site($imgurl).")"; 
				$history[]=array("/画画 ".$prompt,$answer);	
				 M("mod_aichat_bchat")->update(array(
				 	"history"=>arr2str($history),
				 	"create_status"=>1,
				 	"stream_status"=>1
				 ),"id=".$task["id"]);
				break;
				
			case "create_book":
				//生成书籍
				$prompt=stripslashes($_POST["prompt"]);
				$content=post("content","x");
				$title=post("title","h");
				$title=str_replace("&amp;","",$title);
				$title=str_replace("&","",$title);
				$title=str_replace("quot;","",$title);
				$bookid=$task["bookid"];
				//file_put_contents("attach/book".$bookid.".txt",$content,FILE_APPEND);
				M("mod_aichat_book")->update(array(
					"title"=>$title,
					"content"=>nl2br($content),
					"answer"=>$content,
					"create_status"=>1
				),"bookid=".$bookid);
				/*
				$history=[
					[$prompt,$content]
				];
				$num=$task["num"];
				
				//生成书籍文章
				$queuekey="aichat_create_text";
				$queue=new queue($queuekey);
				for($i=1;$i<=$num;$i++){
					
					$prompt2="给我第".$i."篇的内容";
					$finishdata=array(
						"bookid"=>$bookid,
						"action"=>"create_book_article"
					);
					$queue->lpush(array(
						"action"=>"create_book_article",
						"prompt"=>$prompt2,
						"history"=>$history,
						"finishdata"=>arr2str($finishdata)
					));
				}
				*/
				break;
			case "create_book_article":
				//生成文章
				$content=post("content","x");
				$title=post("title","h");
				$title=str_replace("&amp;","",$title);
				$title=str_replace("&","",$title);
				$title=str_replace("quot;","",$title);
				$bookid=$task["bookid"];
				//file_put_contents("attach/book".$bookid.".txt",$content,FILE_APPEND);
				$book=M("mod_aichat_book")->selectRow("bookid=".$bookid);
				$id=intval($task["id"]);
				if($id){
					$row=M("mod_aichat_book_article")->selectRow("id=".$id);
					$history=str2arr($row["history"]);
					$history[]=[$prompt,$content];
					$history=arr2str($history);
					M("mod_aichat_book_article")->update(array(
						"bookid"=>$bookid,
						"userid"=>$book["userid"],
						"title"=>$title,
						"content"=>nl2br($content),
						"prompt"=>post('prompt'),
						"history"=>$history
					),"id=".$id);
				}else{
					$history=[
						[$book["prompt"],$book['answer']]
					];
					$history[]=[$prompt,$content];
					$history=arr2str($history);
					M("mod_aichat_book_article")->insert(array(
						"bookid"=>$bookid,
						"userid"=>$book["userid"],
						"title"=>$title,
						"content"=>nl2br($content),
						"prompt"=>post('prompt'),
						"history"=>$history
					));
				}
				
				break;
		}
		$this->goAll("finish",0);
	}
	
}