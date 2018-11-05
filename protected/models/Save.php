<?php

class Save extends CActiveRecord{
	public function tableName(){
		return "wj_save";
	}

	public static function addSave($pid,$uid){
		$sql = "insert into `wj_save` (`postId`,`userId`) values ($pid,$uid)";
		$sql2 = "update `wj_article` set `save` = `save`+1 where `id` = $pid and `delete` = 0";
		$app = Yii::app()->db;
		$transaction = $app->beginTransaction();
		try{
			$result = $app->createCommand($sql)->execute();
			$app->createCommand($sql2)->execute();
			$transaction->commit();
			return $result;
		}
		catch(Exception $e){
			$transaction->rollBack();
			throw new Exception($e->getMessage(), 1);			
		}
	}

	public static function cancelSave($pid,$uid){
		$sql = "delete from `wj_save` where `postId`=$pid and `userId`=$uid";
		$sql2 = "update `wj_article` set `save` = `save`-1 where `id` = $pid and `delete` = 0";
		$app = Yii::app()->db;
		$transaction = $app->beginTransaction();
		try{
			$result = $app->createCommand($sql)->execute();
			$app->createCommand($sql2)->execute();
			$transaction->commit();
			return $result;
		}
		catch(Exception $e){
			$transaction->rollBack();
			throw new Exception($e->getMessage(), 1);			
		}
	}
}

?>