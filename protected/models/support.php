<?php

class Support extends CActiveRecord{
	public function tableName(){
		return "wj_support";
	}

	public static function add($pid,$cid,$uid,$type){		
		$sql = "insert into `wj_support` (`postId`,`commentId`,`userId`,`type`) values ($pid,$cid,$uid,$type)";
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;		
	}

	public static function cancel($pid,$cid,$uid,$type){
		$sql = "delete from `wj_support` where `postId` = $pid and `commentId` = $cid and `userId` = $uid and `type` = $type";
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
}

?>