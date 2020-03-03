<?php

namespace app\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

/**
* This is the model class for table "transactions".
*
* @property string $DATE
* @property integer $ED1_ID
* @property string $ED1_TYPE
* @property string $ED1_DOCUMENT_NO
* @property string $ED1_FILENAME
* @property integer $ED1_STATUS
* @property integer $CU1_ID
* @property integer $VD1_ID
* @property string $ED1_MODIFIED_ON
* @property integer $ED1_MODIFIED_BY
* @property string $ED1_CREATED_ON
* @property integer $ED1_CREATED_BY
* @property integer $ED1_IN_OUT
* @property integer $ED1_RESEND
* @property integer $ED1_ACKNOWLEDGED
* @property integer $ED1_TEST_MODE
* @property integer $ED1_CONFIRMED
* @property string $ED1_CONFIRMATION_DATE
* @property integer $ED1_SHIPPED
* @property string $ED1_SHIPMENT_DATE
* @property integer $CO1_ID
* @property integer $IH1_ID
* @property integer $ED1_UPLOAD_TIMESTAMP
*/
class Transaction extends \kak\clickhouse\ActiveRecord
{
    /**
    * Get table name
    * @return string
    */

    public $ED1_MODIFIED_ON_FROM;
    public $ED1_MODIFIED_ON_TO;


    const SCENARIO_REPORT = 'Report';

    const STATUS_NOT_SENT   = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_SENT       = 2;
    const STATUS_FAILED     = 3;
    const STATUS_RESENDING  = 10;

    const SHOW_DEFAULT = 'X';

    public static function tableName()
    {
        return 'dist_' . (YII_ENV_PROD ? '' : 'test_') . 'transactions';
    }

    public static function primaryKey()
    {
        return ['ED1_ID', 'CO1_ID'];
    }

    /**
    * @return \kak\clickhouse\Connection the ClickHouse connection used by this AR class.
    */
    public static function getDb()
    {
        return Yii::$app->get('clickhouse');
    }


    /**
    * @inheritdoc
    * @return Array
    */
    public function rules()
    {
        return [
            [['ED1_MODIFIED_ON_FROM', 'ED1_MODIFIED_ON_TO'], 'date', 'on' => [self::SCENARIO_REPORT]],
            [['DATE', 'ED1_MODIFIED_ON', 'ED1_CREATED_ON', 'ED1_CONFIRMATION_DATE', 'ED1_SHIPMENT_DATE'], 'safe'],
            [['ED1_ID', 'ED1_STATUS', 'CU1_ID', 'VD1_ID', 'ED1_MODIFIED_BY', 'ED1_CREATED_BY', 'ED1_IN_OUT', 'ED1_RESEND', 'ED1_ACKNOWLEDGED', 'ED1_TEST_MODE', 'ED1_CONFIRMED', 'ED1_SHIPPED', 'CO1_ID', 'ED1_UPLOAD_TIMESTAMP'], 'integer'],
            [['ED1_TYPE', 'ED1_DOCUMENT_NO', 'ED1_FILENAME'], 'string']
        ];
    }

    /**
    * @inheritdoc
    * @return Array
    */
    public function attributeLabels()
    {
        return [
            'DATE' => 'Date',
            'ED1_ID' => 'Ed1  ID',
            'ED1_TYPE' => 'Ed1  Type',
            'ED1_DOCUMENT_NO' => 'Ed1  Document  No',
            'ED1_FILENAME' => 'Ed1  Filename',
            'ED1_STATUS' => 'Ed1  Status',
            'CU1_ID' => 'Cu1  ID',
            'VD1_ID' => 'Vd1  ID',
            'ED1_MODIFIED_ON' => 'Ed1  Modified  On',
            'ED1_MODIFIED_BY' => 'Ed1  Modified  By',
            'ED1_CREATED_ON' => 'Ed1  Created  On',
            'ED1_CREATED_BY' => 'Ed1  Created  By',
            'ED1_IN_OUT' => 'Ed1  In  Out',
            'ED1_RESEND' => 'Ed1  Resend',
            'ED1_ACKNOWLEDGED' => 'Ed1  Acknowledged',
            'ED1_TEST_MODE' => 'Ed1  Test  Mode',
            'ED1_CONFIRMED' => 'Ed1  Confirmed',
            'ED1_CONFIRMATION_DATE' => 'Ed1  Confirmation  Date',
            'ED1_SHIPPED' => 'Ed1  Shipped',
            'ED1_SHIPMENT_DATE' => 'Ed1  Shipment  Date',
            'CO1_ID' => 'Co1  ID',
            'ED1_UPLOAD_TIMESTAMP' => 'Ed1  Upload  Timestamp',
        ];
    }

    private function _prepareIntFilter($fieldName)
    {
        $value = $this->$fieldName;
        if($this->$fieldName && is_array($this->$fieldName)){
            $value = array_map(function($item){
                return (int)$item;
            }, $this->$fieldName);
        }

        return (is_null($value) || is_array($value)) ? $value : (int)$value;
    }

    private function _addAccessRestrictions( \kak\clickhouse\ActiveQuery $query )
    {
        $profile = Yii::$app->user->getIdentity()->userProfile;

        if($profile->UP1_RESTRICT_COMPANIES){

            $conditions = [];
            $companies  = [];

            $customers = Customer::find()->select(['CU1_ID', 'CO1_ID', 'rectype'])->asArray()->all();
            foreach( $customers as $customer ){
                if($customer['rectype'] == 1){
                    $conditions[] = '(CO1_ID = ' . (int)$customer['CO1_ID'] . ' AND CU1_ID = ' . (int)$customer['CU1_ID'] . ')';
                }else if(!in_array($customer['CO1_ID'], $companies)){
                    $companies[] = $customer['CO1_ID'];
                }
            }

            $vendors = Vendor::find()->select(['VD1_ID', 'CO1_ID', 'rectype'])->asArray()->all();
            foreach( $vendors as $vendor ){
                if($vendor['rectype'] == 1){
                    $conditions[] = '(CO1_ID = ' . (int)$vendor['CO1_ID'] . ' AND VD1_ID = ' . (int)$vendor['VD1_ID'] . ')';
                }
            }


            foreach( $companies as $company){
                $conditions[] = 'CO1_ID = ' . (int)$company;
            }

            if(!empty($conditions)){
                $query->andWhere(join(" OR ", $conditions));
            }else{
                $query->andWhere('CO1_ID = -1');
            }
        }
    }

    public function search($params)
    {
        $defaultOrder = [];
        if(!empty($params['sort'])){
            foreach( $params['sort'] as $sort){
                list($prop, $dir) = explode(',', $sort);
                $defaultOrder[$prop] = $dir == 'asc' ? SORT_ASC : SORT_DESC;
            }
        }else{
            $defaultOrder['ED1_MODIFIED_ON'] = SORT_DESC;
        }

        $query = self::find()->select(['*', 'ED1_DOCUMENT_NO' => 'toString(ED1_DOCUMENT_NO)' ]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => $params['perPage']
            ],
             'sort' => [
                'defaultOrder' => $defaultOrder
            ]
        ]);

        $this->load($params, '');

        $query->andFilterWhere([
            'ED1_STATUS'      => $this->_prepareIntFilter('ED1_STATUS'),
            'ED1_IN_OUT'      => $this->_prepareIntFilter('ED1_IN_OUT'),
            'ED1_TEST_MODE'   => $this->_prepareIntFilter('ED1_TEST_MODE')
        ]);
        $query->andFilterWhere(['like', 'toString(ED1_DOCUMENT_NO)',  $this->ED1_DOCUMENT_NO]);

        $this->ED1_MODIFIED_ON_FROM = $this->ED1_MODIFIED_ON_FROM == 'null' ? null : $this->ED1_MODIFIED_ON_FROM;
        $this->ED1_MODIFIED_ON_TO   = $this->ED1_MODIFIED_ON_TO   == 'null' ? null : $this->ED1_MODIFIED_ON_TO;

        $query->andFilterWhere(['>=', 'ED1_MODIFIED_ON', $this->ED1_MODIFIED_ON_FROM ]);
        $query->andFilterWhere(['<=', 'ED1_MODIFIED_ON', $this->ED1_MODIFIED_ON_TO ]);

        if($this->ED1_TYPE){
            $query->andWhere(['OR', ['toString(ED1_TYPE)' => $this->ED1_TYPE]]);
        }

        $venCompanies = [];
        $conditions   = [];
        if( !empty($params['VD1_ID']) &&  is_array( $params['VD1_ID'] )){
            foreach( $params['VD1_ID'] as $vendor ){
                $conditions[] = '(CO1_ID = ' . (int)$vendor['CO1_ID'] . ' AND VD1_ID = ' . (int)$vendor['VD1_ID'] . ')';
                $venCompanies[] = $vendor['CO1_ID'];
            }
        }
        $custCompanies = [];
        if( !empty($params['CU1_ID']) &&  is_array( $params['CU1_ID'] )){
            foreach( $params['CU1_ID'] as $customer ){
                $conditions[] = '(CO1_ID = ' . (int)$customer['CO1_ID'] . ' AND CU1_ID = ' . (int)$customer['CU1_ID'] . ')';
                $custCompanies[] = $customer['CO1_ID'];
            }
        }
        if(!empty($params['CO1_ID'])){
            $companies = array_diff($params['CO1_ID'], $venCompanies, $custCompanies);
            if(!empty($companies)){
                $conditions[] = '( CO1_ID IN('.join(', ', array_map(function($company){return (int)$company; }, $companies)).'))';
            }
        }
        $query->andWhere(join(" OR ", $conditions));

        $this->_addAccessRestrictions( $query );

        return $dataProvider;
    }

    public function getDrilldown($params, $aggrigateBy=0, $groupBy = '')
    {
        $fields = [];
        $groupByFields = [];
        $orderBy = [];

        if($aggrigateBy > 0){

            if($aggrigateBy == 1){
                $time = 'toStartOfDay(ED1_MODIFIED_ON)';
            }elseif( $aggrigateBy == 2){
                $time = 'toMonday(ED1_MODIFIED_ON)';
            }elseif($aggrigateBy == 3){
                $time = 'toStartOfMonth(ED1_MODIFIED_ON)';
            }elseif($aggrigateBy == 4){
                $time = 'toStartOfYear(ED1_MODIFIED_ON)';
            }
            $fields['time']  = $time;
            $groupByFields[] = $time;
            $orderBy[$time]  = SORT_ASC;
        }

        $fields['total'] = 'count(*)';
        if($groupBy){
            if($groupBy == 'ED1_TYPE'){
                $fields['ED1_TYPE'] = 'toString(ED1_TYPE)';
            }else{
                 $fields[] = $groupBy;
            }
            $groupByFields[] = $groupBy;
        }

        $query = self::find()->select($fields);
        $this->load($params, '');
        $query->andFilterWhere([
            'CO1_ID'     => $this->_prepareIntFilter('CO1_ID'),
            'CU1_ID'     => $this->_prepareIntFilter('CU1_ID'),
            'VD1_ID'     => $this->_prepareIntFilter('VD1_ID'),
            'ED1_IN_OUT' => $this->_prepareIntFilter('ED1_IN_OUT')
        ]);

        if($this->ED1_TYPE){
            $query->andWhere(['OR', ['toString(ED1_TYPE)' => $this->ED1_TYPE]]);
        }

        $this->ED1_MODIFIED_ON_FROM = $this->ED1_MODIFIED_ON_FROM == 'null' ? null : $this->ED1_MODIFIED_ON_FROM;
        $this->ED1_MODIFIED_ON_TO   = $this->ED1_MODIFIED_ON_TO   == 'null' ? null : $this->ED1_MODIFIED_ON_TO;

        $query->andFilterWhere(['>=', 'ED1_MODIFIED_ON', $this->ED1_MODIFIED_ON_FROM ]);
        $query->andFilterWhere(['<=', 'ED1_MODIFIED_ON', $this->ED1_MODIFIED_ON_TO ]);

        $query->groupBy($groupByFields);
        $query->orderBy($orderBy);

        $this->_addAccessRestrictions( $query );

        $rawSql = $query->createCommand()->getRawSql();

        return $query->all();
    }

    public function getInOutBoundReport($params)
    {
        $query = self::find()->select(['ED1_IN_OUT', 'ED1_TYPE' => 'toString(ED1_TYPE)', 'total' => 'count(*)']);
        $this->load($params, '');
        $query->andFilterWhere([
            'CO1_ID' => $this->_prepareIntFilter('CO1_ID')
        ]);

        if($this->ED1_TYPE){
            $query->andWhere(['OR', ['toString(ED1_TYPE)' => $this->ED1_TYPE]]);
        }

        $this->ED1_MODIFIED_ON_FROM = $this->ED1_MODIFIED_ON_FROM == 'null' ? null : $this->ED1_MODIFIED_ON_FROM;
        $this->ED1_MODIFIED_ON_TO   = $this->ED1_MODIFIED_ON_TO   == 'null' ? null : $this->ED1_MODIFIED_ON_TO;

        $query->andFilterWhere(['>=', 'ED1_MODIFIED_ON', $this->ED1_MODIFIED_ON_FROM ]);
        $query->andFilterWhere(['<=', 'ED1_MODIFIED_ON', $this->ED1_MODIFIED_ON_TO ]);

        $query->groupBy(['ED1_IN_OUT', 'toString(ED1_TYPE)']);
        $query->orderBy(['ED1_IN_OUT' => SORT_ASC]);

        $this->_addAccessRestrictions( $query );

        return $query->all();
    }

    public function getTradingPartnersReport($params)
    {
        $query = self::find()->select(['CO1_ID', 'VD1_ID', 'CU1_ID', 'total' => 'count(*)']);
        $this->load($params, '');
        $query->andFilterWhere([
            'CO1_ID' => $this->_prepareIntFilter('CO1_ID')
        ]);


        $this->ED1_MODIFIED_ON_FROM = $this->ED1_MODIFIED_ON_FROM == 'null' ? null : $this->ED1_MODIFIED_ON_FROM;
        $this->ED1_MODIFIED_ON_TO   = $this->ED1_MODIFIED_ON_TO   == 'null' ? null : $this->ED1_MODIFIED_ON_TO;

        $query->andFilterWhere(['>=', 'ED1_MODIFIED_ON', $this->ED1_MODIFIED_ON_FROM ]);
        $query->andFilterWhere(['<=', 'ED1_MODIFIED_ON', $this->ED1_MODIFIED_ON_TO ]);

        $query->groupBy(['CO1_ID', 'VD1_ID', 'CU1_ID']);
        $query->orderBy(['CO1_ID' => SORT_ASC]);

        $this->_addAccessRestrictions( $query );

        return $query->all();
    }

    public function getEdiTypes()
    {
        $query = self::find()->select(['ED1_TYPE' => 'toString(ED1_TYPE)'])
                    ->groupBy(['ED1_TYPE']);

        return $query->all();
    }

    public function getResending( $CO1_ID, $maxUploadTime = false )
    {
        $query = self::find()->select(['ED1_ID', 'ED1_UPLOAD_TIMESTAMP'])
                    ->where(['CO1_ID' => $CO1_ID, 'ED1_STATUS' => self::STATUS_RESENDING]);

        if($maxUploadTime){
            $query = self::find()->select(['ED1_ID', 'MAX_ED1_UPLOAD_TIMESTAMP' => 'max(ED1_UPLOAD_TIMESTAMP)'])
                    ->where(['CO1_ID' => $CO1_ID])
                    ->andWhere(['IN', 'ED1_ID', $query->select(['ED1_ID'])])
                    ->groupBy(['ED1_ID']);
        }

        return $query->all();
    }
}
