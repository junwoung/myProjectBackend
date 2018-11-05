<?php
header('Access-Control-Allow-Origin:*');
class SaveController extends Controller{
	public function actionAddSave(){
		$pid = $_GET['articleId'];
		$uid = $_GET['userId'];
		$ret = Save::addSave($pid,$uid);
		if($ret){
			$return = array(
				'code' => 0,
				'msg' => '已收藏'
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '收藏失败'
			);
		}
		echo json_encode($return);
	}

	public function actionCancelSave(){
		$pid = $_GET['articleId'];
		$uid = $_GET['userId'];
		$ret = Save::cancelSave($pid,$uid);
		if($ret){
			$return = array(
				'code' => 0,
				'msg' => '已收藏'
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '收藏失败'
			);
		}
		echo json_encode($return);
	}
}


?>