<?php


namespace app\modules\orderEntrySystem\controllers;


use app\modules\orderEntrySystem\models\Customer;

class CustomerController extends BaseController
{
    public $modelClass = Customer::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['delete'], $actions['create']);

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        return (new Customer())->search(\Yii::$app->request->get());
    }
}
