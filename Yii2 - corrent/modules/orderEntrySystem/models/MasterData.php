<?php

namespace app\modules\orderEntrySystem\models;

use Yii;

/**
 * This is the model class for table "md1_master_data".
 *
 * @property int $CORP_ADDRESS_ID
 * @property int $CO1_ID
 * @property string $SHIP_TO_ID
 * @property string $SHIP_TO_NAME
 * @property string $CUSTOMER_PART_NO
 * @property string $CONTRACT_BIN_ID
 * @property string $ITEM_ID
 * @property string $ITEM_DESC
 * @property string $EXTENDED_DESC
 * @property string $ITEM_STATUS
 * @property string $FAMILY
 * @property string $COMMODITY
 * @property string $START_DATE
 * @property double $EAU
 * @property double $REORDER_QTY
 * @property double $MIN_QTY
 * @property double $MAX_QTY
 * @property double $CAPACITY
 * @property double $MD1_ON_HAND_QTY
 * @property int $MD1_P21_ON_HAND_QTY
 * @property double $MD1_PRICE
 * @property string $MD1_UM
 * @property double $MD1_UM_SIZE
 * @property string $MD1_SHOW_DEFAULT
 * @property int $FLAG
 * @property string $LINE
 * @property string $LINE_FEED
 * @property string $LINE_STATION
 * @property int $PREFERRED_LOCATION_ID
 * @property int $CUSTOMER_ID
 * @property string $CUSTOMER_NAME
 * @property string $ITEM_CATEGORY
 * @property string $CATALOG_NO
 * @property string $MD1_CREATED_BY
 * @property string $MD1_CREATED_ON
 * @property string $MD1_MODIFIED_BY
 * @property string $MD1_MODIFIED_ON
 * @property int $EclipseID
 * @property double $MD1_MIN_FREQUENCY
 * @property double $MD1_MAX_FREQUENCY
 * @property string $MD1_CUSTOMER_ESTIMATED_LIABILITY
 */
class MasterData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'md1_master_data';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
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
            [['CORP_ADDRESS_ID', 'CO1_ID', 'SHIP_TO_ID', 'CONTRACT_BIN_ID', 'ITEM_STATUS', 'FAMILY', 'COMMODITY', 'MD1_ON_HAND_QTY'], 'required'],
            [['CORP_ADDRESS_ID', 'CO1_ID', 'MD1_P21_ON_HAND_QTY', 'FLAG', 'PREFERRED_LOCATION_ID', 'CUSTOMER_ID', 'EclipseID'], 'integer'],
            [['START_DATE', 'MD1_CREATED_ON', 'MD1_MODIFIED_ON'], 'safe'],
            [['EAU', 'REORDER_QTY', 'MIN_QTY', 'MAX_QTY', 'CAPACITY', 'MD1_ON_HAND_QTY', 'MD1_PRICE', 'MD1_UM_SIZE', 'MD1_MIN_FREQUENCY', 'MD1_MAX_FREQUENCY'], 'number'],
            [['SHIP_TO_ID', 'ITEM_STATUS'], 'string', 'max' => 50],
            [['SHIP_TO_NAME', 'ITEM_DESC', 'ITEM_CATEGORY', 'CATALOG_NO'], 'string', 'max' => 100],
            [['CUSTOMER_PART_NO', 'ITEM_ID'], 'string', 'max' => 40],
            [['CONTRACT_BIN_ID', 'EXTENDED_DESC', 'LINE', 'LINE_FEED', 'LINE_STATION', 'CUSTOMER_NAME'], 'string', 'max' => 255],
            [['FAMILY', 'COMMODITY'], 'string', 'max' => 4],
            [['MD1_UM'], 'string', 'max' => 20],
            [['MD1_SHOW_DEFAULT'], 'string', 'max' => 1],
            [['MD1_CREATED_BY', 'MD1_MODIFIED_BY'], 'string', 'max' => 10],
            [['MD1_CUSTOMER_ESTIMATED_LIABILITY'], 'string', 'max' => 300],
            [['CORP_ADDRESS_ID', 'CO1_ID', 'SHIP_TO_ID', 'CONTRACT_BIN_ID'], 'unique', 'targetAttribute' => ['CORP_ADDRESS_ID', 'CO1_ID', 'SHIP_TO_ID', 'CONTRACT_BIN_ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CORP_ADDRESS_ID' => 'Corp  Address  ID',
            'CO1_ID' => 'Co1  ID',
            'SHIP_TO_ID' => 'Ship  To  ID',
            'SHIP_TO_NAME' => 'Ship  To  Name',
            'CUSTOMER_PART_NO' => 'Customer  Part  No',
            'CONTRACT_BIN_ID' => 'Contract  Bin  ID',
            'ITEM_ID' => 'Item  ID',
            'ITEM_DESC' => 'Item  Desc',
            'EXTENDED_DESC' => 'Extended  Desc',
            'ITEM_STATUS' => 'Item  Status',
            'FAMILY' => 'Family',
            'COMMODITY' => 'Commodity',
            'START_DATE' => 'Start  Date',
            'EAU' => 'Eau',
            'REORDER_QTY' => 'Reorder  Qty',
            'MIN_QTY' => 'Min  Qty',
            'MAX_QTY' => 'Max  Qty',
            'CAPACITY' => 'Capacity',
            'MD1_ON_HAND_QTY' => 'Md1  On  Hand  Qty',
            'MD1_P21_ON_HAND_QTY' => 'Md1  P21  On  Hand  Qty',
            'MD1_PRICE' => 'Md1  Price',
            'MD1_UM' => 'Md1  Um',
            'MD1_UM_SIZE' => 'Md1  Um  Size',
            'MD1_SHOW_DEFAULT' => 'Md1  Show  Default',
            'FLAG' => 'Flag',
            'LINE' => 'Line',
            'LINE_FEED' => 'Line  Feed',
            'LINE_STATION' => 'Line  Station',
            'PREFERRED_LOCATION_ID' => 'Preferred  Location  ID',
            'CUSTOMER_ID' => 'Customer  ID',
            'CUSTOMER_NAME' => 'Customer  Name',
            'ITEM_CATEGORY' => 'Item  Category',
            'CATALOG_NO' => 'Catalog  No',
            'MD1_CREATED_BY' => 'Md1  Created  By',
            'MD1_CREATED_ON' => 'Md1  Created  On',
            'MD1_MODIFIED_BY' => 'Md1  Modified  By',
            'MD1_MODIFIED_ON' => 'Md1  Modified  On',
            'EclipseID' => 'Eclipse ID',
            'MD1_MIN_FREQUENCY' => 'Md1  Min  Frequency',
            'MD1_MAX_FREQUENCY' => 'Md1  Max  Frequency',
            'MD1_CUSTOMER_ESTIMATED_LIABILITY' => 'Md1  Customer  Estimated  Liability',
        ];
    }
}
