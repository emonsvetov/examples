<?php

namespace app\controllers;

use app\components\AuthController;
use app\models\Group;
use app\services\Group as GroupService;
use yii\filters\AccessControl;
use Yii;

class GroupController extends AuthController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'export'], //only be applied to
            'rules' => [[
                'actions' => ['index', 'export'],
                'allow'   => true,
                'roles'   => ['DASHBOARD_READ']
            ]]
        ];

        return $behaviors;
    }


    public function actionIndex()
    {
        $request = Yii::$app->getRequest();

        $group = new Group();
        $dataProvider = $group->search( $request->getQueryParams() );
        $dataProvider->query->asArray();

        return [
            'data'  => $dataProvider->getModels(),
            'count' => $dataProvider->getTotalCount()
        ];
    }

    public function actionView($id)
    {
        $group = Group::findOne(['GR1_ID' => $id]);
        return [
            'data' => $group
        ];
    }

    public function actionCreate()
    {
        $data = Yii::$app->request->post();

        if( isset($data['GR1_ID'])){
            unset($data['GR1_ID']);
        }

        $group = new Group();

        if( $group->load($data, '') && $group->save( true ) ){
            return [
                'success' => true,
                'data'    => $group
            ];
        }else{
            return [
                'success' => false,
                'errors'  => $group->getErrorSummary(true)
            ];
        }
    }

    public function actionUpdate()
    {
        $data = Yii::$app->request->post();

        $group = Group::findOne(['GR1_ID' => $data['GR1_ID']]);

        if( $group->load($data, '') && $group->save( true ) ){
            return [
                'success' => true,
                'data'    => $group
            ];
        }else{
            return [
                'success' => false,
                'errors'  => $group->getErrorSummary(true)
            ];
        }
    }

    public function actionDelete()
    {
        $data = Yii::$app->request->getQueryParams();

	    $groupService = new GroupService();
	    $result = $groupService->deleteGroup($data);

	    return [
		    'success' => !$result,
		    'usersCount' => $result ? count($result) : 0
	    ];
    }

	public function actionExport()
	{
		$request = Yii::$app->getRequest();
		$params = $request->getQueryParams();
		$service = new GroupService();

		switch ($params['type']) {
			case 'xls':
			default:
				return $service->generateXls($params);
		}
	}

}
