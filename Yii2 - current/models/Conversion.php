<?php

namespace app\models;

use app\models\scopes\ConversionQuery;
use Yii;

/**
 * This is the model class for table "um2_conversion".
 *
 * @property int $UM2_ID
 * @property int $CO1_ID
 * @property int $MD1_ID
 * @property int $UM1_FROM_ID
 * @property int $UM1_TO_ID
 * @property double $UM2_FACTOR
 * @property int $UM2_DELETE_FLAG
 * @property int $UM2_CREATED_BY
 * @property string $UM2_CREATED_ON
 * @property int $UM2_MODIFIED_BY
 * @property string $UM2_MODIFIED_ON
 */
class Conversion extends ActiveRecord
{
    protected $_tablePrefix = 'UM2';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'um2_conversion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CO1_ID', 'MD1_ID', 'UM1_FROM_ID', 'UM1_TO_ID', 'UM2_DELETE_FLAG', 'UM2_CREATED_BY', 'UM2_MODIFIED_BY'], 'integer'],
            [['MD1_ID'], 'required'],
            [['UM2_FACTOR'], 'number'],
            [['UM2_CREATED_ON', 'UM2_MODIFIED_ON'], 'safe'],
            [['MD1_ID', 'UM1_TO_ID', 'UM1_FROM_ID'], 'unique', 'targetAttribute' => ['MD1_ID', 'UM1_TO_ID', 'UM1_FROM_ID']],
            [['MD1_ID', 'UM1_FROM_ID', 'UM1_TO_ID'], 'unique', 'targetAttribute' => ['MD1_ID', 'UM1_FROM_ID', 'UM1_TO_ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'UM2_ID' => 'Um2  ID',
            'CO1_ID' => 'Co1  ID',
            'MD1_ID' => 'Md1  ID',
            'UM1_FROM_ID' => 'Um1  From  ID',
            'UM1_TO_ID' => 'Um1  To  ID',
            'UM2_FACTOR' => 'Um2  Factor',
            'UM2_DELETE_FLAG' => 'Um2  Delete  Flag',
            'UM2_CREATED_BY' => 'Um2  Created  By',
            'UM2_CREATED_ON' => 'Um2  Created  On',
            'UM2_MODIFIED_BY' => 'Um2  Modified  By',
            'UM2_MODIFIED_ON' => 'Um2  Modified  On',
        ];
    }

    /**
     * {@inheritdoc}
     * @return Um2ConversionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConversionQuery(get_called_class());
    }
}
