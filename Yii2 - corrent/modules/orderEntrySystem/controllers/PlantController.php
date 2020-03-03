<?php


namespace app\modules\orderEntrySystem\controllers;


use app\modules\orderEntrySystem\models\Plant;
use app\modules\orderEntrySystem\models\PlantSearch;
use Yii;

class PlantController extends BaseController
{
    public $modelClass = Plant::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['delete'], $actions['create']);

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        return (new PlantSearch())->search(Yii::$app->request->get());
    }
}
