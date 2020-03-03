<?php

namespace app\modules\orderEntrySystem\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "cu1_customer".
 *
 * @property int $CU1_ID
 * @property string $CU1_SHORT_CODE
 * @property string $CU1_NAME
 * @property int $CU1_TYPE
 * @property int $CU1_ALLOW_DUPLICATE_BARCODES
 * @property int $CU1_SPLIT_UP_ORDERS
 * @property int $CU1_TXT_APPROVED
 * @property int $CU1_ALLOW_INVENTORY_MANAGEMENT
 * @property int $CU1_IMPORT_EXTERNAL_XML_USAGE
 * @property string $CU1_SHOW_DEFAULT
 * @property string $CU1_NEW_CONTRACT_NUMBER
 * @property int $CU1_CONVERT_REORDER_QTY_INTO_EACH
 * @property int $CU1_UPTO_ORDER_MODE
 * @property int $CU1_SHOW_ON_HAND_QTY_FROM_P21
 * @property int $CU1_MULTIPLY_ORDER_QUANTITY
 * @property double $MD1_MIN_FREQUENCY
 * @property double $MD1_MAX_FREQUENCY
 * @property int $CU1_SHOW_CUSTOMER_ESTIMATED_LIABILITY
 */
class Customer extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cu1_customer';
    }

    /**
     * @return Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('dbWssi');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CU1_NAME'], 'required'],
            [['CU1_TYPE', 'CU1_ALLOW_DUPLICATE_BARCODES', 'CU1_SPLIT_UP_ORDERS', 'CU1_TXT_APPROVED', 'CU1_ALLOW_INVENTORY_MANAGEMENT', 'CU1_IMPORT_EXTERNAL_XML_USAGE', 'CU1_CONVERT_REORDER_QTY_INTO_EACH', 'CU1_UPTO_ORDER_MODE', 'CU1_SHOW_ON_HAND_QTY_FROM_P21', 'CU1_MULTIPLY_ORDER_QUANTITY', 'CU1_SHOW_CUSTOMER_ESTIMATED_LIABILITY'], 'integer'],
            [['MD1_MIN_FREQUENCY', 'MD1_MAX_FREQUENCY'], 'number'],
            [['CU1_SHORT_CODE'], 'string', 'max' => 10],
            [['CU1_NAME'], 'string', 'max' => 100],
            [['CU1_SHOW_DEFAULT'], 'string', 'max' => 1],
            [['CU1_NEW_CONTRACT_NUMBER'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CU1_ID' => 'Cu1  ID',
            'CU1_SHORT_CODE' => 'Cu1  Short  Code',
            'CU1_NAME' => 'Cu1  Name',
            'CU1_TYPE' => 'Cu1  Type',
            'CU1_ALLOW_DUPLICATE_BARCODES' => 'Cu1  Allow  Duplicate  Barcodes',
            'CU1_SPLIT_UP_ORDERS' => 'Cu1  Split  Up  Orders',
            'CU1_TXT_APPROVED' => 'Cu1  Txt  Approved',
            'CU1_ALLOW_INVENTORY_MANAGEMENT' => 'Cu1  Allow  Inventory  Management',
            'CU1_IMPORT_EXTERNAL_XML_USAGE' => 'Cu1  Import  External  Xml  Usage',
            'CU1_SHOW_DEFAULT' => 'Cu1  Show  Default',
            'CU1_NEW_CONTRACT_NUMBER' => 'Cu1  New  Contract  Number',
            'CU1_CONVERT_REORDER_QTY_INTO_EACH' => 'Cu1  Convert  Reorder  Qty  Into  Each',
            'CU1_UPTO_ORDER_MODE' => 'Cu1  Upto  Order  Mode',
            'CU1_SHOW_ON_HAND_QTY_FROM_P21' => 'Cu1  Show  On  Hand  Qty  From  P21',
            'CU1_MULTIPLY_ORDER_QUANTITY' => 'Cu1  Multiply  Order  Quantity',
            'MD1_MIN_FREQUENCY' => 'Md1  Min  Frequency',
            'MD1_MAX_FREQUENCY' => 'Md1  Max  Frequency',
            'CU1_SHOW_CUSTOMER_ESTIMATED_LIABILITY' => 'Cu1  Show  Customer  Estimated  Liability',
        ];
    }

    public function search($params)
    {
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => new Sort([
                'defaultOrder' => 'CO1_ID DESC',
            ]),
        ]);

        $this->load($params, '');

        return $dataProvider;
    }
}
