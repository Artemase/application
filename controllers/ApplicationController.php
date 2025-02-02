<?php

namespace app\controllers;

use app\models\Application;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * ApplicationController implements the CRUD actions for Application model.
 */
class ApplicationController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['create', 'show', 'answer'],
                    'rules' => [
                        [
                            'actions' => ['create'],
                            'allow' => true,
                        ],
                        [
                            'actions' => ['show', 'answer'],
                            'allow' => true,
                            'roles' => ['@']
                        ]
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'create' => ['POST'],
                        'index' => ['GET'],
                        'answer' => ['PUT']
                    ],
                ],
            ]
        );
    }

    /**
     * Показывает список всех заявок от всех пользователей.
     *
     * @status - enum('active',')
     * @dateOrder - enum('DESC', 'ASC')
     * 
     * @return string
     */
    public function actionShow($status = null, $dateOrder = null)
    {
        
        $dataProvider = Application::find();
        if($status){
            $dataProvider->andWhere(['status' => $status]);
        }

        if($dateOrder){
            $dataProvider->orderBy(['created_at'=> (($dateOrder === 'ASC') ? SORT_ASC : SORT_DESC)]);
        }
        foreach($dataProvider->all() as $application){
            $result[] = $application;
        }
        return Json::encode($result);
    }

    /**
     * Создает новую заявку от польователя.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($name, $email, $message)
    {
        $model = new Application();

        $model->name = $name;
        $model->email = $email;
        $model->message = $message;

        $model->status = 'Active';
        $model->save();
    }

    /**
     * Updates an existing Application model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAnswer($id, $answer)
    {
        \Yii::$app->controller->enableCsrfValidation = false;
        $model = Application::find()->andWhere(['id' => $id])->one();

        $model->comment = $answer;
        $model->status = 'Resolved';

        $model->save();
        
        \Yii::$app->mailer->compose()
        ->setFrom('antipoff.artyom@gmail.com')
        ->setTo($model->email)
        ->setSubject('Ответ на вашу заявку')
        ->setHtmlBody($answer)
        ->send();
    }

}
