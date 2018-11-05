<?php
header("Access-Control-Allow-Origin:*");

class FollowController extends Controller{
	public function actionAddRelation(){
		$follower = $_GET['follower'];
		$follow = $_GET['follow'];
		if(!$follower || !$follow){
			$return = array(
				'code' => 1,
				'msg' => '参数错误，缺少必传'
			);
		}
		else if($follower == $follow){
			$return = array(
				'code' => 3,
				'msg' => '不可以关注自己'
			);
		}
		else{
			$res = Follow::addRelation($follower,$follow);
			if($res){
				$return = array(
					'code' => 0,
					'msg' => '已关注'
				);
			}
			else{
				$return = array(
					'code' => 2,
					'msg' => '关注失败'
				);
			}
		}
		echo json_encode($return);
	}

	public function actionCancelRelation(){
		$follower = $_GET['follower'];
		$follow = $_GET['follow'];
		if(!$follower || !$follow){
			$return = array(
				'code' => 1,
				'msg' => '参数错误，缺少必传'
			);
		}
		else{
			$res = Follow::cancelRelation($follower,$follow);
			if($res){
				$return = array(
					'code' => 0,
					'msg' => '已取消关注'
				);
			}
			else{
				$return = array(
					'code' => 2,
					'msg' => '取消关注失败'
				);
			}
		}
		echo json_encode($return);
	}

	public function actionJustRelation(){
		$follower = $_GET['follower'];
		$follow = $_GET['follow'];
		if(!$follower || !$follow){
			$return = array(
				'code' => 1,
				'msg' => '参数错误，缺少必传'
			);
		}
		else{
			$res = Follow::justRelation($follower,$follow);
			if($res){
				$return = array(
					'code' => 0,
					'msg' => $res
				);
			}
			else{
				$return = array(
					'code' => 2,
					'msg' => '查询用户关系失败'
				);
			}
		}
		echo json_encode($return);
	}

	public function actionGetFollower(){
		$uid = $_GET['uid'];
		$page = $_GET['page'];
		$res = Follow::getFollower($uid,$page);
		$return = array(
			'code' => 0,
			'msg' => $res
		);
		echo json_encode($return);
	}

	public function actionGetFollowing(){
		$uid = $_GET['uid'];
		$page = $_GET['page'];
		$res = Follow::getFollowing($uid,$page);
		$return = array(
			'code' => 0,
			'msg' => $res
		);
		echo json_encode($return);
	}
}

?>