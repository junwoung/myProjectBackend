<?php
header('Access-Control-Allow-Origin:http://localhost:8080');
class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	// public function accessRules()
	// {
	// 	return array(
	// 		array('allow',  // allow all users to perform 'index' and 'view' actions
	// 			'actions'=>array('index','view'),
	// 			'users'=>array('*'),
	// 		),
	// 		array('allow', // allow authenticated user to perform 'create' and 'update' actions
	// 			'actions'=>array('create','update'),
	// 			'users'=>array('@'),
	// 		),
	// 		array('allow', // allow admin user to perform 'admin' and 'delete' actions
	// 			'actions'=>array('admin','delete'),
	// 			'users'=>array('admin'),
	// 		),
	// 		array('deny',  // deny all users
	// 			'users'=>array('*'),
	// 		),
	// 	);
	// }


	/**
	 * 注册
	 */
	public function actionCreate(){

		$pwd = md5(trim($_POST['password']));
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$gender = $_POST['gender'];
		$birth = $_POST['birth'];
		$return = array(
			'code' => 0,
			'msg' => '插入成功'
		);		
		try{
			$sql = "insert into `wj_user` (`name`,`password`,`email`,`phone`,`gender`,`birth`) values ( '$name','$pwd','$email','$phone',$gender,'$birth')";
			$result = Yii::app()->db->createCommand($sql)->execute();
			if($result){
				$query = "select * from `wj_user` where `name` = '$name'";
				$msg = Yii::app()->db->createCommand($query)->queryAll();
				$return['msg'] = json_encode($msg);
			}
		}
		catch(Exception $e){
			$return['code'] = 1;
			$return['msg'] = '插入失败'.$e;
		}
		echo json_encode($return);
	}

	/**
	 * 登录
	 */
	public function actionLogin(){
		// print_r($_SERVER['REMOTE_ADDR']);
		$name = $_GET['name'];
		$password = $_GET['password'];
		$return = array(
			'code' => 0,
			'msg' => '登录成功',
		);
		$sql = "select * from `wj_user` where `name` = '$name'";
		$res = Yii::app()->db->createCommand($sql)->queryAll();
		if(count($res)){
			$pwd = $res[0]['password'];
			if($pwd !== md5(trim($password))){
				$return['code'] = 1;
				$return['msg'] = '密码错误';
			}
			else{
				Yii::app()->session->add('user',$res[0]);
				$sid = Yii::app()->session->getSessionID();
				$res[0]['sessionId'] = $sid;
				$return['msg'] = json_encode($res[0]);
				// $_SESSION[''] = $res[0];
			}
		}
		else{
			$return['code'] = 2;
			$return['msg'] = '账号不存在';
		}
		// print_r(Yii::app()->session->get('user'));
		print_r(json_encode($return));
	}

	public function actionList(){
		$page = $_GET['page'];
		$size = $_GET['size'];
		$start = ($page - 1) * $size;
		$sql = "select sql_calc_found_rows * from `wj_user` where `avaliable` > 0 limit $start,$size";
		$sql_total = "select id from `wj_user` where `avaliable` > 0";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		$total = Yii::app()->db->createCommand($sql_total)->execute();
		$return = array(
			'total' => $total,
			'data' => $result
		);
		echo json_encode($return);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id)->attributes;
		unset($model['password']);
		echo json_encode($model);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	// public function actionCreate()
	// {
	// 	$model=new User;

	// 	// Uncomment the following line if AJAX validation is needed
	// 	// $this->performAjaxValidation($model);

	// 	if(isset($_POST['User']))
	// 	{
	// 		$model->attributes=$_POST['User'];
	// 		if($model->save())
	// 			$this->redirect(array('view','id'=>$model->id));
	// 	}

	// 	$this->render('create',array(
	// 		'model'=>$model,
	// 	));
	// }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
