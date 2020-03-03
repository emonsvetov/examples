<?php

namespace app\controllers;

use \Yii;
use app\components\AuthController;
use yii\filters\AccessControl;
use app\services\PurchaseOrder as PurchaseOrderService;
use app\services\Location as LocationService;
use app\models\PurchaseOrder;
use app\models\Company;
use app\models\Location;
use app\models\MasterData;
use app\services\Number as NumberService;
use app\components\PdfTrait;
use app\pdfs\Pdf;

class PurchaseOrderController extends AuthController
{
	use PdfTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'view', 'create', 'delete', 'delete-multiple', 'preload', 'init-order', 'export', 'print-pdf', 'materials-budget-report', 'dashboard-charts'], //only be applied to
            'rules' => [[
                'actions' => ['index', 'view', 'preload', 'init-order', 'export', 'print-pdf'],
                'allow'   => true,
                'roles'   => ['PURCHASE_ORDERS_READ']
            ],[
                'actions' => ['create'],
                'allow' => true,
                'roles' => ['PURCHASE_ORDERS_EDIT', 'PURCHASE_ORDERS_ADD']
            ],[
                'actions' => ['delete', 'delete-multiple'],
                'allow'   => true,
                'roles'   => ['PURCHASE_ORDERS_DELETE']
            ],
            [
	            'actions' => ['materials-budget-report'],
	            'allow'   => true,
	            'roles'   => ['MATERIALS_BUDGET_READ']
            ],[
	            'actions' => ['dashboard-charts'],
	            'allow'   => true,
	            'roles'   => ['DASHBOARD_READ']
            ]]
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $model = new PurchaseOrder();
        $dataProvider = $model->getAll($request->getQueryParams());
        $dataProvider->query->asArray();

        return [
            'data' => [
                'currentVocabulary' => $dataProvider->getModels(),
                'count' => $dataProvider->getTotalCount()
            ]
        ];
    }

    public function actionView()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');

        $model = new PurchaseOrder();
        $dataProvider = $model->getPurchaseOrder($id);
        $dataProvider->query->asArray();

        return [
            'data' =>  $dataProvider->getModels()
        ];
    }

    public function actionCreate()
    {
        $data = Yii::$app->request->post();

        $orderHeaderService = new PurchaseOrderService();

        $errors = [];

        $result = $orderHeaderService->create( $data['orders'] );
        if($result['errors']){
            $errors += $result['errors'];
        }

        return [
            'data' => [
                'success'=> !$errors,
                'data'   => $data,
                'errors' => $errors
            ]
        ];
    }

    public function actionDelete()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');

        $purchaseOrderService = new PurchaseOrderService();
        $errors = $purchaseOrderService->delete( $id );

        return [
            'data' => [
                'success'=> !$errors,
                'errors' => $errors
            ]
        ];
    }

	public function actionDeleteMultiple()
	{
		$PO1_IDs = Yii::$app->getRequest()->post('PO1_IDs');

		$purchaseOrderService = new PurchaseOrderService();
		foreach ( $PO1_IDs as $PO1_ID ) {
			$purchaseOrderService->delete( $PO1_ID );
		}

		return [
			'success' => true
		];
	}

    public function actionPreload()
    {
        $request = Yii::$app->getRequest();

        $user = new \app\models\User();
        $users = $user->search( $request->getQueryParams() );
        $users->query->asArray();

        $vendor = new \app\models\Vendor();
        $vendors = $vendor->getAllVendors( $request->getQueryParams() );
        $vendors->query->asArray();

        $company = new \app\models\Company();
        $companies = $company->search( $request->getQueryParams() );
        $companies->query->asArray();

        $location = new \app\models\Location();
        $locations = $location->search( $request->getQueryParams() );
        $locations->query->asArray();

        return [
            'users'     => $users->getModels(),
            'vendors'   => $vendors->getModels(),
            'companies' => $companies->getModels(),
            'shops'   => $locations->getModels(),
        ];
    }

    public function actionInitOrder()
    {
        $filters = Yii::$app->request->getQueryParams();

        $user = Yii::$app->user->getIdentity();

        $company = Company::findOne(['CO1_ID' => $user->CO1_ID]);
        $fromLocation    = Location::findOne(['LO1_ID' => $user->LO1_ID]);

        $masterData = new MasterData();
        $parts = $masterData->getQuickAddParts($filters);

        $number = new NumberService();
        $purchaseOrderNumber = $number->getPurchaseOrderNumber();

        return [
            'company'         => $company,
            'fromLocation'    => $fromLocation,
            'parts'           => $parts,
            'PO1_NUMBER'      => $purchaseOrderNumber->PO1_NUMBER
        ];
    }

    public function actionExport()
    {
        $request = Yii::$app->getRequest();
        $params = $request->getQueryParams();
        $service = new PurchaseOrderService();

        switch ($params['type']) {
            case 'xls':
            default:

		        switch ($params['subtype']) {
			        case 'with-lines':
				        return $service->generateXlsWithLines($params);
			        default:
			        	return $service->generateXls($params);
		        }
        }
    }

    public function actionReceive()
    {
        $data = Yii::$app->getRequest()->post();

        $PO1_ID = $data['PO1_ID'];
        $orderLines = $data['orderLines'];

        $purchaseOrderService = new PurchaseOrderService();
        $result = $purchaseOrderService->receive($PO1_ID, $orderLines);

        return [
            'data' => $result
        ];
    }

    public function actionUpload()
    {
        $data = Yii::$app->getRequest()->post();

        $PO1_IDs = $data['PO1_IDs'];
        $errors = [];
        foreach( $PO1_IDs as $PO1_ID ){
            $purchaseOrderService = new PurchaseOrderService();
            $result = $purchaseOrderService->upload($PO1_ID);
            if(!$result['success']){
                $errors += $result['errors'];
            }
        }

        return [
            'data' => [
                'success' => !$errors,
                'errors'  => $errors
            ]
        ];
    }

	public function actionPrintPdf()
	{
		$request = Yii::$app->getRequest();
		$params = $request->getQueryParams();

		$pdfService = new Pdf();
		$pdf = $pdfService->printPurchaseOrders($params);

		$filePrefix = str_replace('.pdf', '', $params['fileName']);
		$currDate = Date('Ymd_His');
		$filename = $filePrefix . '_' . $currDate . '.pdf';

		$this->sendPdfFile($pdf, $filename);
	}

	public function actionMaterialsBudgetReport()
	{
		$request = Yii::$app->getRequest();
		$params = $request->getQueryParams();

		$service = new PurchaseOrderService();
		$result = $service->getMaterialsBudgetReport($params);

		return [
			'status' =>  '200',
			'statusText' => 'success',
			'series' => isset($result['data']) ? $result['data']: [],
			'drilldown' => isset($result['drilldown']) ? $result['drilldown']: [],
			'title' => isset($result['title']) ? $result['title'] : ''
		];
	}

	public function actionDashboardCharts()
	{
		$service = new PurchaseOrderService();
		$result = $service->getTotalOfPurchaseOrders();

		return [
			'status' => '200',
			'statusText' => 'success',
			'series' => isset($result['data']) ? $result['data']: [],
			'drilldown' => isset($result['drilldown']) ? $result['drilldown']: [],
			'title' => isset($result['title']) ? $result['title'] : '',
		];
	}

}