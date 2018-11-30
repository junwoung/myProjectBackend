<?php

header("Access-Control-Allow-Origin:*");

class UploadController extends Controller{
	public function actionUploadImg(){
		$code = 0;
		$msg = 'success';
		$data = null;
		if($_FILES && $_FILES['file']){
			$file = $_FILES['file'];
			$name = $file['name'];
			$name = explode('.', $name)[1];
			$size = $file['size'];
			$error = $file['error'];
			$type = $file['type'];
			$tmp_name = $file['tmp_name'];
			if($error){
				$code = 3;
				$msg = '文件上传发生错误';
			}
			else{
				$fname = md5(time().$file['name']).'.'.$name;
				$date = date('Y-m-d');
				$dirname = 'upload/header/'.$date;
				$this->makeDir($dirname);		
				move_uploaded_file($tmp_name,$dirname.'/'.$fname);
				$data = [
					'short_uri' => $dirname.'/'.$fname,
					'long_uri' => 'http://'.$_SERVER['HTTP_HOST'].'/yii/testdrive/'.$dirname.'/'.$fname
				];
			}
		}
		else{
			$code = 2;
			$msg = '文件为空';
		}
		$return = [
			'ret' => [
				'code' => $code,
				'msg' => $msg
			],
			'data' => $data
		];
		echo json_encode($return);
		
	}

	public function makeDir($path = null){
		if(!$path)return;
		if(file_exists($path))return;
		$path = explode('/', $path);
		$count = count($path);
		$dirname = $path[0];
		foreach($path as $p){
			if($p == $path[0]){
				if(!file_exists($dirname)){
					mkdir($dirname,0777);
				}
			}
			else{
				$dirname .= '/'.$p;
				if(!file_exists($dirname)){
					mkdir($dirname,0777);
				}
			}
		}
	}
}