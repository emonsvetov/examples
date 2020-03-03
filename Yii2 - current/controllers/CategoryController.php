<?php
/**
 * Created by PhpStorm.
 * User: e.monsvetov
 * Date: 09/11/2018
 * Time: 11:43
 */

namespace app\controllers;

use app\components\AuthController;
use app\models\Category;
use app\services\MasterCategory as MasterCategoryService;
use app\services\Category as CategoryService;
use yii\filters\AccessControl;
use Yii;

class CategoryController extends AuthController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'view', 'create', 'update', 'delete', 'delete-multiple', 'export'], //only be applied to
            'rules' => [[
                'actions' => ['index', 'view', 'export'],
                'allow'   => true,
                'roles'   => ['CATEGORIES_READ']
            ],[
                'actions' => ['create'],
                'allow'   => true,
                'roles'   => ['CATEGORIES_ADD']
            ],[
                'actions' => ['update'],
                'allow'   => true,
                'roles'   => ['CATEGORIES_EDIT']
            ],[
                'actions' => ['delete', 'delete-multiple'],
                'allow'   => true,
                'roles'   => ['CATEGORIES_DELETE']
            ]]
        ];

        return $behaviors;
    }


    public function actionIndex()
    {
        $request = Yii::$app->getRequest();

        $category = new Category();
        $dataProvider = $category->search( $request->getQueryParams() );
        $dataProvider->query->asArray();

        return [
            'data'  => $dataProvider->getModels(),
            'count' => $dataProvider->getTotalCount()
        ];
    }

    public function actionView($id)
    {
        $category = new Category();
        $query = $category->getSearchQuery(['CA1_ID' => $id]);
        $query->asArray();
        $data = $query->one();

        return [
            'data' => $data
        ];
    }

    public function actionCreate()
    {
        $data = Yii::$app->request->post();

        if( isset($data['CA1_ID'])){
            unset($data['CA1_ID']);
        }

        $category = new Category();

        if( $category->load($data, '') && $category->save( true ) ){
            return [
                'success' => true,
                'data'    => $category
            ];
        }else{
            return [
                'success' => false,
                'errors'  => $category->getErrorSummary(true)
            ];
        }
    }

    public function actionUpdate()
    {
        $data = Yii::$app->request->post();

        $category = Category::findOne(['CA1_ID' => $data['CA1_ID']]);

        if( $category->load($data, '') && $category->save( true ) ){
            return [
                'success' => true,
                'data'    => $category
            ];
        }else{
            return [
                'success' => false,
                'errors'  => $category->getErrorSummary(true)
            ];
        }
    }

    public function actionDelete()
    {
        $data = Yii::$app->request->getQueryParams();

        $category = Category::findOne(['CA1_ID' => $data['id']]);
        $category->CA1_DELETE_FLAG = 1;
        $category->save(false);

        return [
            'success' => true
        ];
    }

	public function actionDeleteMultiple()
	{
		$CA1_IDs = Yii::$app->getRequest()->post('CA1_IDs');

		foreach ( $CA1_IDs as $CA1_ID ) {
			$categoryModel = Category::findOne(['CA1_ID' => $CA1_ID]);
			$categoryModel->CA1_DELETE_FLAG = 1;
			$categoryModel->save(false);
		}

		return [
			'success' => true
		];
	}

	public function actionExport()
	{
		$request = Yii::$app->getRequest();
		$params = $request->getQueryParams();
		$service = new CategoryService();

		switch ($params['type']) {
			case 'xls':
			default:
				return $service->generateXls($params);
		}
	}
}
