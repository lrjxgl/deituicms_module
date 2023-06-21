<?php
class ftpControl extends skymvc{
	public $dir="attach/ftp/";
	public function __construct(){
		parent::__construct();
		session_write_close();
	}
	public function onDefault(){
		M("login")->checkLogin();
		if(!file_exists($this->dir)){
			umkdir($this->dir,0666);
		} 
		$this->smarty->display("index/index.html");
	}
	public function onList(){
		$ftp=$this->newFtp();
		$dir=get("dir","h");
		if($dir==""){
			$dir="./";
		} 
		$list=[];
		$files = ftp_mlsd($ftp->conn_id, $dir);
		if(!empty($files)){
			foreach($files as $f){
				if($f["type"]=="dir"){
					$list[]=$f;
				}	
			}
			foreach($files as $f){
				if($f["type"]!="dir"){
					$list[]=$f;
				}	
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	
	public function onReadfile(){
		$ftp=$this->newFtp();
		$remote_file=get("file","h");
		$local_file=$this->dir."/temp_".md5($file).time();
		$handle = fopen($local_file, 'w');
		$bool=ftp_fget($ftp->conn_id, $handle, $remote_file, FTP_ASCII, 0);
		$fileContent=file_get_contents($local_file);
		unlink($local_file);
		if(!$bool){
			$this->goAll("读取文件失败",1);
		}
		$this->smarty->goAssign(array(
			"fileContent"=>$fileContent
		));
	}
	public function onSavefile(){
		$ftp=$this->newFtp();
		$remote_file=get_post("file","h");
		$local_file=$this->dir."/temp_".md5($file).time();
		$fileContent=stripslashes($_POST["fileContent"]);
		file_put_contents($local_file,$fileContent);
		 
		$handle = fopen($local_file, 'r');
		$bool=ftp_fput($ftp->conn_id, $remote_file, $handle, FTP_ASCII, 0);
		unlink($local_file);
		if($bool){
			$this->goAll("保存成功",0);
		}else{
			$this->goAll("保存失败",1);
		} 
		 
	}
	
	public function onupfile(){
		$ftp=$this->newFtp();
		$dir=post("dir","h");
		$file=$_FILES["upimg"];
		$local_file=$this->dir."/temp_".md5($file["name"]).time();
		move_uploaded_file($file["tmp_name"],$local_file);
		$remote_file=$dir."/".$file["name"];
		$bool=ftp_put($ftp->conn_id, $remote_file, $local_file, FTP_ASCII);
		unlink($local_file);
		if($bool){
			$this->goAll("上传成功");
		}else{
			$this->goAll("上传失败",1);
		}
	}
	
	public function onRenameFile(){
		$ftp=$this->newFtp();
		$old_file=post("file","h");
		$new_file=post("rename_title","h");
		$bool=ftp_rename($ftp->conn_id, $old_file, $new_file); 
		if($bool){
			$this->goAll("保存成功",0);
		}else{
			$this->goAll("保存失败",1);
		} 
	}
	
	public function onCreateDir(){
		$ftp=$this->newFtp();
		$dir=post("dir","h"); 
		$bool=ftp_mkdir($ftp->conn_id, $dir); 
		if($bool){
			$this->goAll("保存成功",0);
		}else{
			$this->goAll("保存失败",1);
		} 
	}
	
	public function onCreateFile(){
		$ftp=$this->newFtp();
		$remote_file=get_post("file","h");
		$local_file=$this->dir."/temp_".md5($file).time();
		$fileContent=stripslashes($_POST["fileContent"]);
		file_put_contents($local_file,$fileContent);
		 
		$handle = fopen($local_file, 'r');
		$bool=ftp_fput($ftp->conn_id, $remote_file, $handle, FTP_ASCII, 0);
		unlink($local_file);
		if($bool){
			$this->goAll("保存成功",0);
		}else{
			$this->goAll("保存失败",1);
		} 
	}
	public function onDelFile(){
		$ftp=$this->newFtp();
		$file=post("file","h");
		 
		$bool=ftp_delete($ftp->conn_id,$file); 
		if($bool){
			$this->goAll("删除成功",0);
		}else{
			$this->goAll("删除失败",1);
		} 
	}
	public function onRmdir(){
		$ftp=$this->newFtp();
		$file=post("file","h");
		 
		$bool=ftp_rmdir($ftp->conn_id,$file); 
		if($bool){
			$this->goAll("删除成功",0);
		}else{
			$this->goAll("删除失败",1);
		} 
	}
	public function newFtp(){
		/*$host="127.0.0.1";
		$port=21;
		$user="demo";
		$pwd="demo";
		*/
	   $userid=M("login")->userid;
		$ftpid=get_post("ftpid","i");
		$f=M("mod_ftp_host")->selectRow("ftpid=".$ftpid);
		if($f["userid"]!=$userid){
			$this->goAll("出错了",1);
		}
		$con=str2arr($f["ftpdata"]);
		 
		$ftp=new ftp($con["host"],$con["port"],$con["user"],$con["pass"]);
		return $ftp;
	}
	
	
}