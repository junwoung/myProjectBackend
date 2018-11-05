<?php
header('Access-Control-Allow-Origin:*');

class CommentController extends Controller{

	public function actionCreate(){
		$userId = $_POST['userId'];
		$postId = $_POST['postId'];
		$comment = $_POST['comment'];
		$commentType = $_POST['commentType'];
		$ret = Comment::createComment($userId,$postId,$comment,$commentType);
		if($ret){
			$return = array(
				'code' => 0,
				'msg' => '评论成功'
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '评论失败'
			);
		}
		echo json_encode($return);
	}

	public function actionCreateReply(){
		$userId = $_POST['userId'];
		$postId = $_POST['postId'];
		$comment = $_POST['comment'];
		$commentType = $_POST['commentType'];
		$postCommentId = $_POST['postCommentId'];
		$ret = Comment::createReply($userId,$postId,$comment,$commentType,$postCommentId);
		if($ret){
			$return = array(
				'code' => 0,
				'msg' => '评论成功'
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '评论失败'
			);
		}
		echo json_encode($return);
	}

	public function actionGetcomment(){
		$postId = $_GET['postId'];
		$page = $_GET['page'];
		$size = $_GET['size'];
		$start = ($page - 1)*$size;
		$ret = Comment::getComment($postId,$start,$size);
		if($ret){
			$return = array(
				'code' => 0,
				'msg' => $ret
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '暂无评论'
			);
		}
		echo json_encode($return);
	}

	public function actionGetreply(){
		$commentId = $_GET['commentId'];
		$ret = Comment::getReply($commentId);
		if($ret){
			$return = array(
				'code' => 0,
				'msg' => $ret
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '暂无评论'
			);
		}
		echo json_encode($return);
	}

	public function actionDeleteReply(){
		$cid = $_GET['commentId'];
		$uid = $_GET['userId'];
		$detail = Comment::getOneReply($cid);
		if(isset($detail[0])){
			if($uid == $detail[0]['userId']){
				$ret = Comment::deleteReply($cid,$detail[0]['postId'],$detail[0]['postCommentId']);
				if($ret){
					$return = array(
						'code' => 0,
						'msg' => $ret
					);
				}
				else{
					$return = array(
						'code' => 2,
						'msg' => '删除失败'
					);
				}
			}
			else{
				$return = array(
					'code' => 1,
					'msg' => '你没有权限删除该评论'
				);
			}
		}
		else{
			$return = array(
				'code' => 3,
				'msg' => '未找到相关评论'
			);
		}
		echo json_encode($return);
	}
}

?>