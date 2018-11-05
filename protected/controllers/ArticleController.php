<?php
header('Access-Control-Allow-Origin:*');
header('Access-control-Allow-Headers','Content-Type,XFILENAME,XFILECATEGORY,XFILESIZE');
class ArticleController extends Controller{

	public function actionCreate(){
		$userId = $_POST['userId'];
		$title = $_POST['title'];
		$context = $_POST['context'];
		$resource = isset($_POST['resource']) ? $_POST['resource'] : 0;
		$from = isset($_POST['from']) ? $_POST['from'] : 0;
		$return = array(
			'code' => 0,
			'msg' => '添加成功'
		);
		$ret = Article::create($userId,$title,$context,$resource,$from);
		if($ret !== 1){
			$return['code'] = 1;
			$return['msg'] = '添加失败 '.$ret;
		}
		echo json_encode($return);
	}

	public function actionList(){
		$page = $_GET['number'];
		$size = $_GET['size'];
		$condition = isset($_GET['condition']) ? $_GET['condition'] : null;
		$start = ($page-1)*$size;
		$ret = Article::getlist($start,$size,$condition);
		if(isset($ret)){
			$return = array(
				'code' => 0,
				'msg' => $ret
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

	public function actionIncreRead(){
		$id = $_GET['id'];
		$ret = Article::increRead($id);
		echo $ret;
	}

	public function actionGetArticle(){
		$aid = $_GET['articleId'];
		$ret = Article::getArticle($aid);
		if(isset($ret)){
			$return = array(
				'code' => 0,
				'msg' => $ret,
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '查询错误'
			);
		}
		echo json_encode($return);
	}

	public function actionDeleteArticle(){
		$aid = $_GET['articleId'];
		$uid = $_GET['userId'];
		$detail = Article::getArticle($aid);
		if(isset($detail)){
			if($uid == $detail[0]['userId']){
				$ret = Article::deleteArticle($aid);
				if($ret){
					$return = array(
						'code' => 0,
						'msg' => '删除成功'
					);
				}
				else{
					$return = array(
						'code' => 3,
						'msg' => '删除失败'
					);
				}
			}
			else{
				$return = array(
					'code' => 2,
					'msg' => '你没有权限删除该贴'
				);
			}
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '未找到相关帖子'
			);
		}
		echo json_encode($return);
	}

	public function actionGetMyPosts(){
		$uid = $_GET['userId'];
		$page = $_GET['page'];
		$res = Article::getMyPosts($uid,$page);
		if(isset($res)){
			$total = array_pop($res);
			$return = array(
				'code' => 0,
				'msg' => $res,
				'total' => $total[0]['total']
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '查找我的发帖信息失败'
			);
		}
		echo json_encode($return);
	}
	public function actionGetMySaves(){
		$uid = $_GET['userId'];
		$page = $_GET['page'];
		$res = Article::getMySaves($uid,$page);
		if(isset($res)){
			$total = array_pop($res);
			$return = array(
				'code' => 0,
				'msg' => $res,
				'total' => $total[0]['total']
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '查找我的收藏信息失败'
			);
		}
		echo json_encode($return);
	}
	public function actionGetMySupports(){
		$uid = $_GET['userId'];
		$page = $_GET['page'];
		$res = Article::getMySupports($uid,$page);
		if(isset($res)){
			$total = array_pop($res);
			$return = array(
				'code' => 0,
				'msg' => $res,
				'total' => $total[0]['total']
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '查找我的点赞信息失败'
			);
		}
		echo json_encode($return);
	}
	public function actionGetMyReports(){
		$uid = $_GET['userId'];
		$page = $_GET['page'];
		$res = Article::getMyReports($uid,$page);
		if(isset($res)){
			$total = array_pop($res);
			$return = array(
				'code' => 0,
				'msg' => $res,
				'total' => $total[0]['total']
			);
		}
		else{
			$return = array(
				'code' => 1,
				'msg' => '查找我的转发信息失败'
			);
		}
		echo json_encode($return);
	}


}


?>