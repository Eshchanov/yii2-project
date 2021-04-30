<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zayavka_certificate".
 *
 * @property integer $id
 * @property integer $zayavka_id
 * @property string $user_id
 * @property string $date
 * @property string $organ_name
 * @property string $adress
 * @property string $full_name_head
 * @property string $goods_name
 * @property string $kod_tn_ved
 * @property string $requirement
 * @property string $sxema
 * @property string $intelligence
 * @property string $attached
 * @property string $declarant
 */
class ZayavkaCertificateForm extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'zayavka_certificate';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['zayavka_id', 'user_id', 'date', 'organ_name', 'adress', 'full_name_head', 'goods_name', 'kod_tn_ved', 'requirement', 'sxema', 'intelligence', 'declarant', 'number'], 'required'],
			[['zayavka_id', 'user_id'], 'integer'],
			[['date', 'goods_name'], 'safe'],
			[['intelligence', 'attached'], 'string'],
			[['organ_name', 'adress', 'full_name_head', 'requirement', 'sxema', 'declarant', 'number'], 'string', 'max' => 255],
			[['kod_tn_ved'], 'string', 'max' => 5000],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'zayavka_id' => Yii::t('app', 'Zayavka ID'),
			'user_id' => Yii::t('app', 'User ID'),
			'date' => Yii::t('app', 'Дата заявки (дд.мм.гггг)'),
			'organ_name' => Yii::t('app', 'Organ Name'),
			'adress' => Yii::t('app', 'Adress'),
			'full_name_head' => Yii::t('app', 'Full Name Head'),
			'goods_name' => Yii::t('app', 'Наименование продукции'),
			'kod_tn_ved' => Yii::t('app', 'Kod Tn Ved'),
			'requirement' => Yii::t('app', 'Наименование документации (ГОСТ, O`zDst, TS) и др.'),
			'sxema' => Yii::t('app', 'Sxema'),
			'intelligence' => Yii::t('app', 'Дополнительные сведения'),
			'attached' => Yii::t('app', 'Санитарно-эпидемиологическое заключения'),
			'declarant' => Yii::t('app', 'Заявитель'),
			'number' => Yii::t('app', 'Номер заявки'),
		];
	}

	public function getZayavka()
	{
		return $this->hasOne(Zayavka::className(), ['zid' => 'zayavka_id']);
	}

	public function beforeSave($insert) {

		if(parent::beforeSave($insert)){
			if($this->date){
				$this->date = date('Y-m-d', strtotime($this->date));
			}
			return true;
		}else{
			return false;
		}
	}

	public function afterFind()
	{
		if ($this->date) {
			$this->date = date('d.m.Y', strtotime($this->date));
		}
		parent::afterFind();
	}

	public function getSetNumber()
	{
		$user_id = Yii::$app->user->identity->organ_id;
		$number = self::find()->where(['user_id' => $user_id])->orderBy('(1 * number) DESC')->one();
		if ($number) {
			$number = $number->number + 1;
		}
		else {
			$number = 1;
		}
		return $number;
	}

	public function getSetSertNumber()
	{
		$zayavka = $this->zayavka;
		$number = $zayavka->nomer_z;
		$number = $number.'/1';
		return $number;
	}
}