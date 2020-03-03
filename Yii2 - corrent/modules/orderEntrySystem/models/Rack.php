<?php

namespace app\modules\orderEntrySystem\models;

use app\components\ArrayHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "ra1_rack".
 *
 * @property string $RA1_ID
 * @property int $CU1_ID
 * @property string $PL1_ID
 * @property string $SHIP_TO_ID
 * @property string $SHIP_TO_NAME
 * @property string $RA1_SHOW_DEFAULT
 * @property int $FLAG
 *
 * @property Customer $customer
 */
class Rack extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ra1_rack';
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
            [['RA1_ID', 'CU1_ID', 'PL1_ID'], 'required'],
            [['CU1_ID', 'FLAG'], 'integer'],
            [['RA1_ID', 'SHIP_TO_ID'], 'string', 'max' => 50],
            [['PL1_ID'], 'string', 'max' => 10],
            [['SHIP_TO_NAME'], 'string', 'max' => 100],
            [['RA1_SHOW_DEFAULT'], 'string', 'max' => 1],
            [['RA1_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'RA1_ID' => 'Ra1  ID',
            'CU1_ID' => 'Cu1  ID',
            'PL1_ID' => 'Pl1  ID',
            'SHIP_TO_ID' => 'Ship  To  ID',
            'SHIP_TO_NAME' => 'Ship  To  Name',
            'RA1_SHOW_DEFAULT' => 'Ra1  Show  Default',
            'FLAG' => 'Flag',
        ];
    }

    public function fields()
    {
        return [
            'RA1_ID',
            'CU1_ID',
            'PL1_ID',
            'SHIP_TO_ID',
            'SHIP_TO_NAME',
            'RA1_SHOW_DEFAULT',
            'FLAG',
            'customerName' => function () {
                return ArrayHelper::getValue($this, 'customer.CU1_NAME', '-');
            },
        ];
    }


    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['CU1_ID' => 'CU1_ID']);
    }
}
