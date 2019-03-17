<?php
namespace backend\controllers;


use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

/**
 * 基类
 */
class BaseController extends Controller
{
    const AJAX_SUCCESS = 0;
    const AJAX_ERROR   = 1;

    protected $isHeadquarters = false;


    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            $controller     = Yii::$app->controller;
            $controllerName = $controller->id;
            $url            = sprintf("/%s/%s", $controllerName,  $controller->action->id);
            if (!Yii::$app->user->can($url)) {
                throw new HttpException(400, '没有权限访问，请联系管理员！~~');
            }
        }

        if (parent::beforeAction($action)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Displays Json Data
     *
     * @param array $params
     * @param bool  $filter
     *
     * @return string
     */
    protected function renderJson($params = [], $filter = true)
    {
        $response = [];
        if ($filter === true) {
            if (isset($params['code']) && $params['code'] === static::AJAX_SUCCESS) {
                $response['code'] = static::AJAX_SUCCESS;
                $response['msg']  = isset($params['msg']) ? $params['msg'] : '操作成功！~';
                $response['data'] = isset($params['data'])? $params['data'] : [];
            } else {
                $response['code'] = static::AJAX_ERROR;
                $response['msg']  = isset($params['msg']) ? $params['msg'] : '超时，稍后重试！~';
                $response['data'] = [];
            }
        } else {
            $response = $params;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $response;
    }

}
