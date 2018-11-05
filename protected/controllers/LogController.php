<?php

/**
 * 
 */
class LogController extends Controller
{
	
	public function actionList(){
		$sql = "select * from `wj_log`";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		print_r(User::$user);
		print_r($_SESSION);
		print_r($result);
	}

	public function actionAddlog(){
		
	}
}

?>