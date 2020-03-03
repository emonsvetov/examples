<?php

namespace app\modules\orderEntrySystem\models;

use yii\data\ActiveDataProvider;
use yii\data\Sort;

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
class MasterDataSearch extends MasterData
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [array_keys($this->attributes), 'safe'],
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

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (isset($this->MD1_SHOW_DEFAULT)) {
            $query->andWhere([
                'MD1_SHOW_DEFAULT' => $this->MD1_SHOW_DEFAULT,
            ]);
        }

        $query->andFilterWhere([
            'CO1_ID' => $this->CO1_ID,
        ]);

        foreach ([
                     'SHIP_TO_NAME',
                     'CUSTOMER_PART_NO',
                     'ITEM_DESC',
                     'EXTENDED_DESC',
                     'ITEM_STATUS',
                     'FAMILY',
                     'COMMODITY',
                     'START_DATE',
                     'EAU',
                     'MD1_UM',
                     'FLAG',
                     'LINE',
                     'LINE_FEED',
                     'LINE_STATION',
                     'CUSTOMER_NAME',
                     'ITEM_CATEGORY',
                     'CATALOG_NO',
                     'MD1_CREATED_BY',
                     'MD1_CREATED_ON',
                     'MD1_MODIFIED_BY',
                     'MD1_MODIFIED_ON',
                     'MD1_CUSTOMER_ESTIMATED_LIABILITY',
                 ] as $attribute) {
            $query->andFilterCompare($attribute, $this->$attribute, 'like');
        }

        foreach ([
                     'CORP_ADDRESS_ID',
                     'SHIP_TO_ID',
                     'CONTRACT_BIN_ID',
                     'ITEM_ID',
                     'REORDER_QTY',
                     'MIN_QTY',
                     'MAX_QTY',
                     'CAPACITY',
                     'MD1_ON_HAND_QTY',
                     'MD1_P21_ON_HAND_QTY',
                     'MD1_PRICE',
                     'MD1_UM_SIZE',
                     'FLAG',
                     'LINE',
                     'LINE_FEED',
                     'LINE_STATION',
                     'PREFERRED_LOCATION_ID',
                     'CUSTOMER_ID',
                     'EclipseID',
                     'MD1_MIN_FREQUENCY',
                     'MD1_MAX_FREQUENCY',
                     'MD1_CUSTOMER_ESTIMATED_LIABILITY',
                 ] as $attribute) {
            $query->andFilterCompare($attribute, $this->$attribute);
        }

        return $dataProvider;
    }
}
