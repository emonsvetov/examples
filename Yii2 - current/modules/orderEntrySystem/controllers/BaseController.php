<?php


namespace app\modules\orderEntrySystem\controllers;


use yii\rest\ActiveController;

class BaseController extends ActiveController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'data',
    ];
}
