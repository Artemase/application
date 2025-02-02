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
     * status - enum('active',')
     * dateOrder - enum('DESC', 'ASC')
     * 
     * @return json
     */
    public function actionShow($status = null, $dateOrder = null)
    {
        header("Access-Control-Allow-Origin: http://www.application.com");
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
     * 
     * name - string (Имя пользователя)
     * email - string (e-mail пользователя)
     * message - string (Сообщение к заявке пользователя)
     * 
     * @return void
     */
    public function actionCreate($name, $email, $message)
    {
        header("Access-Control-Allow-Origin: http://www.application.com");
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
        
        header("Access-Control-Allow-Origin: http://www.application.com);
        \Yii::$app->controller->enableCsrfValidation = false;
        $model = Application::find()->andWhere(['id' => $id])->one();

        $model->comment = $answer;
        $model->status = 'Resolved';

        $model->save();
        
        \Yii::$app->mailer->compose()
        ->setFrom('antipoff.artyom@gmail.com')
        ->setTo($model->email)
        ->setSubject('Ответ на вашу заявку')
        ->setTextBody($answer)
        ->send();
    }

}
