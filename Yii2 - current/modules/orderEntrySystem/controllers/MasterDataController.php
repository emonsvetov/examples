<?php


namespace app\modules\orderEntrySystem\controllers;


use app\modules\orderEntrySystem\models\MasterData;
use app\modules\orderEntrySystem\models\MasterDataSearch;
use Yii;

class MasterDataController extends BaseController
{
    public $modelClass = 'app\modules\orderEntrySystem\models\MasterData';

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['delete'], $actions['create']);

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        return (new MasterDataSearch())->search(Yii::$app->request->get());
    }

    public function actionOne($CORP_ADDRESS_ID, $CO1_ID, $SHIP_TO_ID, $CONTRACT_BIN_ID)
    {
        return MasterData::findOne([
            'CORP_ADDRESS_ID' => $CORP_ADDRESS_ID,
            'CO1_ID' => $CO1_ID,
            'SHIP_TO_ID' => $SHIP_TO_ID,
            'CONTRACT_BIN_ID' => $CONTRACT_BIN_ID,
        ]);
    }
}
