<?php

namespace app\modules\orderEntrySystem\models;

use app\components\ArrayHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "pl1_plant".
 *
 * @property string $PL1_ID
 * @property string $PL1_NAME
 * @property int $CU1_ID
 * @property string $PL1_SHOW_DEFAULT
 * @property int $FLAG
 *
 * @property Customer $customer
 */
class Plant extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pl1_plant';
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
            [['PL1_ID', 'CU1_ID'], 'required'],
            [['CU1_ID', 'FLAG'], 'integer'],
            [['PL1_ID'], 'string', 'max' => 10],
            [['PL1_NAME'], 'string', 'max' => 100],
            [['PL1_SHOW_DEFAULT'], 'string', 'max' => 1],
            [['PL1_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PL1_ID' => 'Pl1  ID',
            'PL1_NAME' => 'Pl1  Name',
            'CU1_ID' => 'Cu1  ID',
            'PL1_SHOW_DEFAULT' => 'Pl1  Show  Default',
            'FLAG' => 'Flag',
        ];
    }

    public function fields()
    {
        return [
            'PL1_ID',
            'PL1_NAME',
            'CU1_ID',
            'PL1_SHOW_DEFAULT',
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
