<?php

class Follow extends CActiveRecord{

	public function tableName(){
		return 'wj_follow';
	}

	public static function addRelation($follower,$follow){
		$sql = "insert into `wj_follow` (`follower`,`follow`) values ($follower,$follow)";
		$sql2 = "update `wj_user` set `follower` = `follower`+1 where `id` = $follow";
		$sql3 = "update `wj_user` set `following` = `following`+1 where `id` = $follower";
		$app = Yii::app()->db;
		$transaction = $app->beginTransaction();
		try{
			$result = $app->createCommand($sql)->execute();
			$app->createCommand($sql2)->execute();
			$app->createCommand($sql3)->execute();
			$transaction->commit();
			return $result;
		}
		catch(Exception $e){
			$transaction->rollBack();
			throw new Exception($e->getMessage(), 1);			
		}
	}

	public static function cancelRelation($follower,$follow){
		$sql = "delete from `wj_follow` where `follower` = $follower and `follow` = $follow";
		$sql2 = "update `wj_user` set `follower` = `follower`-1 where `id` = $follow";
		$sql3 = "update `wj_user` set `following` = `following`-1 where `id` = $follower";
		$app = Yii::app()->db;
		$transaction = $app->beginTransaction();
		try{
			$result = $app->createCommand($sql)->execute();
			$app->createCommand($sql2)->execute();
			$app->createCommand($sql3)->execute();
			$transaction->commit();
			return $result;
		}
		catch(Exception $e){
			$transaction->rollBack();
			throw new Exception($e->getMessage(), 1);			
		}
	}

	public static function justRelation($follower,$follow){
		$sql = "select `followId` from `wj_follow` where `follower` = $follower and `follow` = $follow";
		$sql2 = "select `followId` from `wj_follow` where `follower` = $follow and `follow` = $follower";
		$app = Yii::app()->db;
		$result1 = $app->createCommand($sql)->execute();
		$result2 = $app->createCommand($sql2)->execute();
		$result = array(
			'following' => $result1,
			'follow' => $result2
		);
		return $result;
	}

	public static function getFollower($uid,$page){
		$start = ($page-1)*10;
		$sql = "select a.`follower`,a.`followTime`,b.`name` from `wj_follow` a left join `wj_user` b on a.follower = b.id where a.`follow` = $uid order by a.`followId` desc limit $start,10";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		return $result;
	}

	public static function getFollowing($uid,$page){
		$start = ($page-1)*10;
		$sql = "select a.`follow`,a.`followTime`,b.`name` from `wj_follow` a left join `wj_user` b on a.follow = b.id where a.`follower` = $uid order by a.`followId` desc limit $start,10";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		return $result;
	}
}

?>