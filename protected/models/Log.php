<?php
class Log extends CActiveRecord{
	const REGISTER = 0;
	const LOGIN = 1;
	const OTHER = 2;

	public function tableName(){
		return 'wj_log';
	}

	public function search(){
		$criteria = new CDbCriteria;

		$criteria->compare('id',$this->id);
		return new CActiveDataProvider($this,array(
			'criteria' => $criteria,
		));
	}

	public static function model($className = __CLASS__){
		return parent::model($className);
	}
}

?>