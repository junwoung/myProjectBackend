<?php
header('Access-Control-Allow-Origin:*');

class SupportController extends Controller{
	public function actionAddSupport(){
		$pid = $_GET['postId'];
		$cid = isset($_GET['commentId']) ? $_GET['commentId']:0;
		$uid = $_GET['userId'];
		$type = $_GET['type'];
		$flag = $_GET['flag'];
		$app = Yii::app()->db;
		$transaction = $app->beginTransaction();
		try{
			if($flag == 'true'){
				$ret = Support::add($pid,$cid,$uid,$type);
				Article::increSupport($pid,1);
			}
			else {
				$ret = Support::cancel($pid,$cid,$uid,$type);
				Article::increSupport($pid,-1);
			}			
			$transaction->commit();
		}
		catch(Exception $e){
			$transaction->rollBack();
			throw new Exception($e->getMessage(), 1);		
		}
		
		if($ret){
			$return = array(
				'code' => 0,
				'msg' => 'success'
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => 'error'
			);
		}
		echo json_encode($return);
	}
}

?>