<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "oc_decision".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $zayavka_id
 * @property string $number
 * @property string $oc_date
 * @property string $name_document
 * @property string $name_ail
 * @property string $accreditation_lab_id
 * @property string $payment_method
 * @property string $decision_type
 * @property string $letter_number
 * @property string $letter_date
 * @property string $letter_file
 * @property integer $after_installation
 * @property string $product_ids
 * @property string $head_organ
 * @property string $executor
 * @property string $phone
 * @property integer $signature
 * @property integer $signature_decision
 */
class Decision extends \yii\db\ActiveRecord
{

    public $uploadFilePath = '/web/letter/';
    public $urlFilePath = '/letter/';
    public $letter_number_usual;
    public $letter_date_usual;
    public $letter_file_usual;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_decision';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'number', 'oc_date', 'name_document'], 'required'],
            [['oc_date', 'accreditation_lab_id', 'product_ids'], 'safe'],
            [['user_id', 'zayavka_id', 'after_installation', 'signature', 'signature_decision'], 'integer'],
            [['number', 'payment_method'], 'string', 'max' => 500],
            [['name_document', 'name_ail'], 'string', 'max' => 250],
            [['decision_type', 'letter_date', 'letter_date_usual', 'letter_file', 'letter_file_usual', 'head_organ', 'executor', 'phone'], 'string', 'max' => 255],
            [['letter_number', 'letter_number_usual'], 'string', 'max' => 35],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'zayavka_id' => Yii::t('app', 'Zayavka ID'),
            'number' => Yii::t('app', 'Номер'),
            'oc_date' => Yii::t('app', 'Дата'),
            'name_document' => Yii::t('app', 'Наименование документации (ГОСТ, O`zDst, TS) и др.'),
            'name_ail' => Yii::t('app', 'Наименования АИЛ'),
            'accreditation_lab_id' => Yii::t('app', 'Наименования АИЛ'),
            'payment_method' => Yii::t('app', 'Форма оплата'),
            'decision_type' => Yii::t('app', 'Тип решение'),
            'letter_number' => Yii::t('app', '№ письма'),
            'letter_number_usual' => Yii::t('app', '№ письма'),
            'letter_date' => Yii::t('app', 'Дата письма'),
            'letter_date_usual' => Yii::t('app', 'Дата письма'),
            'letter_file' => Yii::t('app', 'Приложите письмо'),
            'letter_file_usual' => Yii::t('app', 'Приложите письмо'),
            'after_installation' => Yii::t('app', 'Создать письмо после установки'),
            'product_ids' => Yii::t('app', 'Выберите продукты'),
            'head_organ' => Yii::t('app', 'Руководитель'),
            'executor' => Yii::t('app', 'Исполнитель'),
            'phone' => Yii::t('app', 'Телефон'),

        ];
    }

    public function beforeSave($insert) {
        
        if(parent::beforeSave($insert)){

            $this->oc_date = date('Y-m-d', strtotime($this->oc_date));
            return true;
        }else{
            return false;
        }

    }
    
    public function afterFind()
    {
        $this->oc_date = date('d.m.Y', strtotime($this->oc_date));
        parent::afterFind();
        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZayavka()
    {
        return $this->hasOne(Zayavka::className(), ['zid' => 'zayavka_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccreditationLab()
    {
        return $this->hasOne(AccreditationLab::className(), ['id' => 'accreditation_lab_id']);
    }

    public function setLetterNumber()
    {
        $organ = $this->zayavka->organ;
        $TIN = $organ->inn;
        $presentYear = date('Y');
        
        $countDecision = self::find()
            ->where(['like', 'oc_date', $presentYear])
            ->andWhere(['user_id' => $organ->id])
            ->andWhere(['signature' => 1])
             ->count();
        
        $countDecision = 1 * $countDecision;

        if ($countDecision)
        {
            $countDecision++;
            $countDecision = (string)$countDecision;
            $str = $countDecision;
            if (strlen($str) < 6)
            {
                for ($i=strlen($str); $i < 6; $i++)
                {
                    $countDecision = '0' . $countDecision;
                }
            }
            $countDecision = "01" . $presentYear . $TIN . $countDecision;
        }
        else
        {
            $countDecision = "01" . $presentYear . $TIN . "000001";
        }

        if (!$this->letter_number)
        {
            $this->letter_number = $countDecision;
        }
    }

    public function setDecisionNumber()
    {
        $organ = $this->zayavka->organ;
        $TIN = $organ->inn;
        $presentYear = date('Y');
        
        $countDecision = self::find()
            ->where(['like', 'oc_date', $presentYear])
            ->andWhere(['user_id' => $organ->id])
            // ->andWhere(['signature' => 1])
            ->count();
        
        $countDecision = 1 * $countDecision;

        if ($countDecision)
        {
            $countDecision++;
            $countDecision = (string)$countDecision;
            $str = $countDecision;
            if (strlen($str) < 6)
            {
                for ($i=strlen($str); $i < 6; $i++)
                {
                    $countDecision = '0' . $countDecision;
                }
            }
            $countDecision = "01" . $presentYear . $TIN . $countDecision;
        }
        else
        {
            $countDecision = "01" . $presentYear . $TIN . "000001";
        }

        if (!$this->number)
        {
            $this->number = $countDecision;
            return true;
        }
        else
        {
            $number = self::find()
                ->where(['number' => $countDecision])
                ->count();
            if ((1 * $number) > 1)
            {
                // $this->number = $countDecision . $this->user_id;
                $this->number = $countDecision . $this->id;
                return true;
            }
        }
        return false;
    }
}
