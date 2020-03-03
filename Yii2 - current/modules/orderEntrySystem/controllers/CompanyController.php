<?php


namespace app\modules\orderEntrySystem\controllers;


use app\models\Company;
use Yii;

class CompanyController extends BaseController
{
    public $modelClass = 'app\models\Company';

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['delete'], $actions['create']);

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        return (new Company())->search(Yii::$app->request->get());
    }
}
