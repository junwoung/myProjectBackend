<?php

class Comment extends CActiveRecord{
	public function tableName(){
		return "wj_comment";
	}

	public static function model($className = __CLASS__){
		return parent::model($className);
	}

	public static function createComment($uid,$pid,$com,$type){
		$sql = "insert into `wj_comment` (`userId`,`postId`,`comment`,`commentType`) values ($uid,$pid,'$com',$type)";
		$sql2 = "update `wj_article` set comment = comment+1 where `id` = $pid";
		$transaction = Yii::app()->db->beginTransaction();
		try{
			$ret = Yii::app()->db->createCommand($sql)->execute();
			Yii::app()->db->createCommand($sql2)->execute();
			$transaction->commit();
			return $ret;
		}
		catch(Exception $e){
			$transaction->rollBack();
			throw new Exception("评论插入错误".$e->getMessage(), 1);			
		}
	}
	public static function createReply($uid,$pid,$com,$type,$postCommentId){
		$sql = "insert into `wj_comment` (`userId`,`postId`,`comment`,`commentType`,`postCommentId`) values ($uid,$pid,'$com',$type,$postCommentId)";
		$sql2 = "update `wj_comment` set commentNum = commentNum+1 where `commentId` = $postCommentId";
		$sql3 = "update `wj_article` set comment = comment+1 where `id` = $pid";
		$transaction = Yii::app()->db->beginTransaction();
		try{
			$ret = Yii::app()->db->createCommand($sql)->execute();
			Yii::app()->db->createCommand($sql2)->execute();
			Yii::app()->db->createCommand($sql3)->execute();
			$transaction->commit();
			return $ret;
		}
		catch(Exception $e){
			$transaction->rollBack();
			throw new Exception("评论插入错误".$e->getMessage(), 1);			
		}
	}



	public static function getComment($pid,$start,$size){
		$sql = "select a.*,b.name from `wj_comment` as a left join `wj_user` as b on a.userId = b.id where a.`postId` = $pid and a.`commentType` = 1 and a.`deleteSign` = 0 limit $start,$size";
		try{
			$ret = Yii::app()->db->createCommand($sql)->queryAll();
			return $ret;
		}
		catch(Exception $e){
			throw new Exception("获取评论错误".$e->getMessage(), 1);			
		}
	}

	public static function getReply($cid){
		$sql = "select a.*,b.name from `wj_comment` as a left join `wj_user` as b on a.userId = b.id where a.`postCommentId` = $cid and a.`commentType` = 2 and a.`deleteSign` = 0";
		try{
			$ret = Yii::app()->db->createCommand($sql)->queryAll();
			return $ret;
		}
		catch(Exception $e){
			throw new Exception("获取评论错误".$e->getMessage(), 1);			
		}
	}

	public static function getOneReply($cid){
		$sql = "select * from `wj_comment` where `commentId` = $cid and `deleteSign` = 0";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		return $result;
	}

	public static function deleteReply($cid,$pid,$pcid){
		$sql = "update `wj_comment` set `deleteSign` = 1 where `commentId` = $cid and `deleteSign` = 0";
		$sql2 = "update `wj_comment` set `deleteSign` = 1 where `postCommentId` = $cid and `deleteSign` = 0";
		$transaction = Yii::app()->db->beginTransaction();
		try{
			$result1 = Yii::app()->db->createCommand($sql)->execute();
			$result2 = Yii::app()->db->createCommand($sql2)->execute();
			$result = $result1 + $result2;
			$sql3 = "update `wj_article` set comment = comment-$result where `id` = $pid";
			$sql4 = "update `wj_comment` set commentNum = commentNum-$result where `commentId` = $pcid";
			Yii::app()->db->createCommand($sql3)->execute();
			Yii::app()->db->createCommand($sql4)->execute();
			$transaction->commit();
			return $result;
		}
		catch(Exception $e){
			$transaction->rollBack();
			throw new Exception("评论插入错误".$e->getMessage(), 1);
		}
	}
}

?>