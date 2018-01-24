<?php
namespace frontend\controllers;

use common\models\Log;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return array
     */
    public function actionLog()
    {

        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [];

        if (Yii::$app->request->getIsPost()) {
            $page = Yii::$app->request->get('page', 0);

            $limit = 10;
            $offset = $page * $limit;

            $response = Log::getStatisticsLog($limit, $offset);
        }

        return $response;
    }

}
