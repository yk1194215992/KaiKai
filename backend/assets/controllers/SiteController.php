<?php
namespace backend\controllers;

use backend\models\AdminForm;
use backend\common\components\Definition;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow'   => true,
                    ],
                    [
                        'actions' => ['logout', 'error', 'index','document'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {

        $model = new AdminForm(['scenario' => Definition::SCENARIOS_LOGIN]);
        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            }
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * 退出
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goBack();
    }

    /**
     * 首页
     * @return string
     */
    public function actionIndex(){
        return $this->render('index');
    }

    /**
     * Displays Error
     */
    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }

        $name = "(#$code)";
        $message = $exception->getMessage();
        if (Yii::$app->request->isAjax) {
            return $this->renderJson(['msg' => $message]);
        } else {
            return $this->render('error', [
                'code'    => $code,
                'name'    => $name,
                'message' => $message,
            ]);
        }
    }


    public function actionDocument(){
    	return $this->render('document');
    }

}
