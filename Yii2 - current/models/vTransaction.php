<?php

namespace app\models;

use Yii;

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
class vTransaction extends Transaction
{
    /**
    * Get table name
    * @return string
    */
    public static function tableName()
    {
        return 'v_dist_' . (YII_ENV_PROD ? '' : 'test_') . 'transactions';
    }

    public static function optimize()
    {
        $connection = Yii::$app->get('clickhouse');
        $command = $connection->createCommand('OPTIMIZE TABLE ' . self::tableName());
        $command->execute(true);
    }
}