<?php
/**
 * 
 */
class Article extends CActiveRecord
{
	
	public function tableName()
	{
		return "wj_article";
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public static function create($userId,$title,$context,$resc,$from){
		$app = Yii::app()->db;
		$sql = "insert into `wj_article` (`userId`,`title`,`context`,`resource`,`from`) values ($userId,'$title','$context',$resc,$from)";
		$transaction = $app->beginTransaction();
		try{
			$result = $app->createCommand($sql)->execute();
			Article::increReport($from);
			$transaction->commit();
			return $result;
		}
		catch(Exception $e){
			$transaction->rollBack();
			throw new Exception($e->getMessage(), 1);
			
		}
		
	}

	public static function getArticle($aid){
		$sql = "select t2.*,GROUP_CONCAT(sa.userId) as totalSave from (select t.*,GROUP_CONCAT(s.userId) as totalS from (select a.*,b.name from `wj_article` as a left join `wj_user` as b on a.userId = b.id where a.delete = 0 and a.id = $aid) t left join `wj_support` s on t.id = s.postId group by t.id) t2 left join `wj_save` sa on t2.id = sa.postId";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		return $result;
	}

	public static function increReport($id){
		$sql = "update `wj_article` set report  = report+1 where id = $id";
		Yii::app()->db->createCommand($sql)->execute();
	}

	public static function getlist($start,$size,$condition){
		$condition = json_decode($condition);
		$str = '';
		if($condition){
			foreach($condition as $c){
				foreach($c as $k => $v){
					$str .= " a.`".$k."` like '%".$v."%' and ";
				}
			}
			$str = substr($str, 0,-4);
		}
		$sql = "select t.*,GROUP_CONCAT(s.userId) as totalS from (select a.*,b.name from `wj_article` as a left join `wj_user` as b on a.userId = b.id where";
		$sql .= ($str ? $str.' and ':'')." a.delete = 0 order by a.`id` desc limit $start,$size) t left join `wj_support` s on t.id = s.postId group by t.id order by t.id desc";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		return $result;
	}

	public static function getMyPosts($uid,$page){
		$start = ($page-1)*10;
		$sql_post = "select `id`,`title`,`date` from `wj_article` where `userId` = $uid and `resource` = 0 and `delete` = 0 order by `id` desc limit $start,10";
		$sql_total = "select count(`id`) as total from `wj_article` where `userId` = $uid and `resource` = 0 and `delete` = 0";
		$result = Yii::app()->db->createCommand($sql_post)->queryAll();
		$total = Yii::app()->db->createCommand($sql_total)->queryAll();
		$result[] = $total;
		return $result;
	}
	public static function getMyReports($uid,$page){
		$start = ($page-1)*10;
		$sql_report = "select `id`,`title`,`date` from `wj_article` where `userId` = $uid and `resource` = 1 and `delete` = 0 order by `id` desc limit $start,10";
		$sql_total = "select count(`id`) as total from `wj_article` where `userId` = $uid and `resource` = 1 and `delete` = 0";
		$result = Yii::app()->db->createCommand($sql_report)->queryAll();
		$total = Yii::app()->db->createCommand($sql_total)->queryAll();
		$result[] = $total;
		return $result;
	}
	public static function getMySaves($uid,$page){
		$start = ($page-1)*10;
		$sql_save = "select a.`postId`,b.`title`,a.`saveTime` from `wj_save` a left join `wj_article` b on a.`postId` = b.`id` where a.`userId` = $uid order by a.`sid` desc limit $start,10";
		$sql_total = "select count(`postId`) as total from `wj_save` where `userId` = $uid";
		$result = Yii::app()->db->createCommand($sql_save)->queryAll();
		$total = Yii::app()->db->createCommand($sql_total)->queryAll();
		$result[] = $total;
		return $result;
	}
	public static function getMySupports($uid,$page){
		$start = ($page-1)*10;
		$sql_support = "select a.`postId`,b.`title`,a.`createTime` from `wj_support` a left join `wj_article` b on a.`postId` = b.`id` where a.`userId` = $uid order by a.`id` desc limit $start,10";
		$sql_total = "select count(`postId`) as total from `wj_support` where`userId` = $uid";
		$result = Yii::app()->db->createCommand($sql_support)->queryAll();
		$total = Yii::app()->db->createCommand($sql_total)->queryAll();
		$result[] = $total;
		return $result;	
	}

	public static function increRead($id){
		$sql = "update `wj_article` set `read` = `read`+1 where id = $id";
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}

	public static function increSupport($id,$num){
		$sql = "update `wj_article` set `support` = `support`+$num where id = $id";
		$result = Yii::app()->db->createCommand($sql)->execute();
	}

	public static function deleteArticle($id){
		$sql = "update `wj_article` set `delete` = 1 where `id` = $id";
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}

	public static function increComment($id,$num){
		$sql = "update `wj_article` set comment = comment+$num where `id` = $id";
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}

}


?>