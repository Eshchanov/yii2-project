<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "zayavka".
 *
 * @property string $zid
 * @property integer $user_id
 * @property string $organ_name
 * @property string $sxema
 * @property string $nomer_z
 * @property string $data
 * @property string $srok
 * @property string $otvetstvenniy
 * @property string $tel
 * @property string $prim
 * @property string $status
 * @property integer $inn
 * @property string $full_name_head
 * @property double $summa
 * @property string $regnomer_cgsn
 * @property string $nomer_gigieni
 * @property string $data_gigieni
 * @property string $strana_izgotovitel
 * @property string $kod_tn_ved
 * @property string $manufacture_doc_name
 * @property string $manufacture_doc_number
 * @property string $goods_name
 * @property integer $ats
 * @property integer $is_import
 * @property string $import_value
 * @property integer $is_production
 * @property integer $is_sample_labeling
 * @property integer $is_bill_of_lading
 * @property string $transport_doc_number
 * @property string $transport_doc_date
 * @property string $transport_doc_file
 * @property string $adress
 * @property string $ail_name
 * @property integer $oc_new
 * @property integer $status_z
 * @property integer $count_certificate
 * @property string $code
 * @property string $contract
 * @property integer $app_online_id
 * @property string $organ_type
 * @property string $good_type
 * @property string $production_type_one
 * @property string $production_type_two
 * @property string $action
 * @property string $action_date
 */
class Zayavka extends ActiveRecord
{
    public $uploadFilePath = '/files/upload/';
    // public $p_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zayavka';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'oc_new', 'organ_name', 'sxema', 'nomer_z', 'data', 'srok', 'otvetstvenniy', 'tel', 'status', 'inn', 'full_name_head', 'regnomer_cgsn',  'strana_izgotovitel',  'manufacture_doc_name',  'ats', 'is_import', 'is_production', 'is_sample_labeling', 'is_bill_of_lading', 'adress', 'ail_name', 'count_certificate', 'code', 'organ_type', 'good_type'], 'required'],
            [['user_id', 'inn', 'ats', 'is_import', 'is_production', 'is_sample_labeling', 'is_bill_of_lading', 'oc_new', 'status_z', 'count_certificate', 'app_online_id'], 'integer'],
            [['data', 'srok', 'data_gigieni', 'transport_doc_date', 'action_date'], 'safe'],
            [['prim', 'goods_name'], 'string'],
            [['summa'], 'number'],
            [['organ_name', 'import_value', 'ail_name', 'code', 'contract', 'organ_type', 'good_type', 'production_type_one', 'production_type_two', 'action'], 'string', 'max' => 255],
            [['adress'], 'string', 'max' => 500],
            [['sxema', 'nomer_z'], 'string', 'max' => 50],
            [['otvetstvenniy'], 'string', 'max' => 30],
            [['tel'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 25],
            [['full_name_head', 'manufacture_doc_name', 'manufacture_doc_number', 'transport_doc_file'], 'string', 'max' => 250],
            [['regnomer_cgsn', 'nomer_gigieni', 'strana_izgotovitel', 'kod_tn_ved'], 'string', 'max' => 55],
            [['transport_doc_number'], 'string', 'max' => 5000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'zid' => Yii::t('app', 'Zid'),
            'user_id' => Yii::t('app', 'User ID'),
            'organ_name' => Yii::t('app', 'Organ Name'),
            'sxema' => Yii::t('app', 'Sxema'),
            'nomer_z' => Yii::t('app', 'Nomer Z'),
            'data' => Yii::t('app', 'Data'),
            'srok' => Yii::t('app', 'Srok'),
            'otvetstvenniy' => Yii::t('app', 'Otvetstvenniy'),
            'tel' => Yii::t('app', 'Tel'),
            'prim' => Yii::t('app', 'Prim'),
            'status' => Yii::t('app', 'Status'),
            'inn' => Yii::t('app', 'Inn'),
            'full_name_head' => Yii::t('app', 'Full Name Head'),
            'summa' => Yii::t('app', 'Summa'),
            'regnomer_cgsn' => Yii::t('app', 'Regnomer Cgsn'),
            'nomer_gigieni' => Yii::t('app', 'Nomer Gigieni'),
            'data_gigieni' => Yii::t('app', 'Data Gigieni'),
            'strana_izgotovitel' => Yii::t('app', 'Strana Izgotovitel'),
            'kod_tn_ved' => Yii::t('app', 'Kod Tn Ved'),
            'manufacture_doc_name' => Yii::t('app', 'Manufacture Doc Name'),
            'manufacture_doc_number' => Yii::t('app', 'Manufacture Doc Number'),
            'goods_name' => Yii::t('app', 'Goods name'),
            'ats' => Yii::t('app', 'Ats'),
            'is_import' => Yii::t('app', 'Is Import'),
            'import_value' => Yii::t('app', 'Import Value'),
            'is_production' => Yii::t('app', 'Is Production'),
            'is_sample_labeling' => Yii::t('app', 'Is Sample Labeling'),
            'is_bill_of_lading' => Yii::t('app', 'Is Bill Of Lading'),
            'transport_doc_number' => Yii::t('app', 'Transport Doc Number'),
            'transport_doc_date' => Yii::t('app', 'Transport Doc Date'),
            'transport_doc_file' => Yii::t('app', 'Transport Doc File'),
            'adress' => Yii::t('app', 'Adress'),
            'ail_name' => Yii::t('app', 'Ail Name'),
            'oc_new' => Yii::t('app', 'Oc New'),
            'status_z' => Yii::t('app', 'Status Z'),
            'count_certificate' => Yii::t('app', 'Count Certificate'),
            'code' => Yii::t('app', 'Code'),
            'contract' => Yii::t('app', 'Contract'),
            'app_online_id' => Yii::t('app', 'App Online ID'),
            'organ_type' => Yii::t('app', 'Тип организации'),
            'good_type' => Yii::t('app', 'Тип продукта'),
            'production_type_one' => Yii::t('app', 'Первый тип продукта'),
            'production_type_two' => Yii::t('app', 'Второй тип продукта'),
            'action' => Yii::t('app', 'Действие'),
            'action_date' => Yii::t('app', 'Дата действия'),
        ];
    }

    public static function sxemaList()
    {
        $result = Sxema::find()->where(['status' => 1])->all();

        $organID = Yii::$app->user->identity->organ_id;

        $allow = SxemeAllow::find()->where(['organ_id' => $organID])->all();

        $result = ArrayHelper::map($result, 'sxema', 'sxema');

        $allow = ArrayHelper::map($allow, 'sxema', 'sxema');

        if (!empty($allow))
        {
            foreach ($allow as $key => $value)
            {
                $result[$value] = $value;
            }
        }

        return $result;
    }

    // public function sxemaList()
    // {
    //     return [
    //         1 => 1,
    //         2 => 2,
    //         3 => 3,
    //         4 => 4,
    //         5 => 5,
    //         6 => 6,
    //         7 => 7,
    //         // '7A' => '7A',
    //         8 => 8,
    //         9 => 9,
    //         // 'confession' => Yii::t('app', 'Confession')
    //     ];
    // }

    public static function sxemaListAll()
    {
        return [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            '7A' => '7A',
            8 => 8,
            9 => 9,
            'confession' => Yii::t('app', 'Confession')
        ];
    }
    
    public function beforeSave($insert) {
        
        if(parent::beforeSave($insert)){
            if($this->data){
                $this->data = date('Y-m-d', strtotime($this->data));
                // $this->data = parse_date($this->data, 'd.m.Y', 'Y-m-d');
                // $this->data = date('Y-m-d');
            }
            if ($this->data_gigieni) {
                $this->data_gigieni = date('Y-m-d', strtotime($this->data_gigieni));
                // $this->data_gigieni = parse_date($this->data_gigieni, 'd.m.Y', 'Y-m-d');
            }
            if ($this->transport_doc_date) {
                $this->transport_doc_date = date('Y-m-d', strtotime($this->transport_doc_date));
                // $this->transport_doc_date = parse_date($this->transport_doc_date, 'd.m.Y', 'Y-m-d');
            }
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        if($this->data){
            $this->data = date('d.m.Y', strtotime($this->data));
        }
        if ($this->data_gigieni) {
            $this->data_gigieni = date('d.m.Y', strtotime($this->data_gigieni));
            // $this->data_gigieni = parse_date($this->data_gigieni, 'Y-m-d', 'd.m.Y');
        }
        if ($this->transport_doc_date) {
            $this->transport_doc_date = date('d.m.Y', strtotime($this->transport_doc_date));
            // $this->transport_doc_date = parse_date($this->transport_doc_date, 'Y-m-d', 'd.m.Y');
        }

        parent::afterFind();
    }

    public function getUser()
    {
        return $this->hasOne(UsersUser::className(), ['id' => 'user_id']);
    }

    public function getOrgan()
    {
        return $this->hasOne(UsersOrgans::className(), ['id' => 'user_id']);
    }

    public function getActTest()
    {
        return $this->hasOne(ActTest::className(), ['zayavka_id' => 'zid']);
    }

    public function getDefective()
    {
        return $this->hasOne(Defective::className(), ['zayavka_id' => 'zid']);
    }

    public function getActSurvey()
    {
        return $this->hasOne(ActSurvey::className(), ['zayavka_id' => 'zid']);
    }

    public function getDecisionExtraditoin()
    {
        return $this->hasOne(DecisionExtraditoin::className(), ['zayavka_id' => 'zid']);
    }

    public function getActSelection()
    {
        return $this->hasOne(ActSelection::className(), ['zayavka_id' => 'zid']);
    }

    public function getActIdentification()
    {
        return $this->hasOne(ActIdentification::className(), ['zayavka_id' => 'zid']);
    }

    public function getConfession()
    {
        return $this->hasOne(Confession::className(), ['zayavka_id' => 'zid']);
    }

    public function getDecision()
    {
        return $this->hasOne(Decision::className(), ['zayavka_id' => 'zid']);
    }

    public function getContractData()
    {
        return $this->hasOne(ContractData::className(), ['zayavka_id' => 'zid']);
    }

    public function getDeclaration()
    {
        return $this->hasOne(Declaration::className(), ['zayavka_id' => 'zid']);
    }

    public function getFormCertification()
    {
        return $this->hasOne(FormCertification::className(), ['zayavka_id' => 'zid']);
    }

    public function getFormCertifications()
    {
        return $this->hasMany(FormCertification::className(), ['zayavka_id' => 'zid']);
    }

    public function getZayavkaCertificate()
    {
        return $this->hasOne(ZayavkaCertificate::className(), ['zayavka_id' => 'zid']);
    }

    public function getProducts()
    {
        return $this->hasMany(ProductName::className(), ['zayavka_id' => 'zid']);
    }

    public function getProduct()
    {
        return $this->hasOne(ProductName::className(), ['zayavka_id' => 'zid']);
    }

    public function getAppProducts()
    {
        return $this->hasMany(AppProducts::className(), ['app_id' => 'app_online_id']);
    }

    public function getAppProductsCement()
    {
        return $this->hasMany(AppProducts::className(), ['app_id' => 'app_online_id'])->where(['sm_yn' => 1]);
    }

    public function getAppOnlineGtk()
    {
        return $this->hasOne(AppOnline::className(), ['id' => 'app_online_id']);
    }

    public function setLastNomer()
    {
        $now_year = date('Y');
        $now_year = $now_year.'-01-01';
        $nomer_z = 1;
        $last_nomer_z = Zayavka::find()
                            ->where(['user_id' => $this->user_id, 'oc_new' => 1])
                            ->andWhere(['>=', 'data', $now_year])
                            ->orderBy('(1 * nomer_z) DESC')
                            ->one();
        if ($last_nomer_z) {
            $last_nomer_z = $last_nomer_z->nomer_z;
            $nomer_z += 1 * $last_nomer_z;
        }
        $this->nomer_z = $nomer_z;
    }

    public function displayName()
    {
        // vd($this);
        return Yii::t('app', 'Nomer Z').': '.$this->nomer_z .', '.Yii::t('app', 'Organ Name').': '.$this->organ_name;
    }

    public function setRandomString($length = 10)
    {
        $characters = 'abcdfgijkmnpqrstuvxy3456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $this->code = $randomString;
    }
}
