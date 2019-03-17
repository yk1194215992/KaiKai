<?php

namespace backend\controllers;

use backend\common\components\Definition;
use backend\models\MenuForm;
use yii\db\Query;
use yii\filters\AccessControl;
use Yii;

class MenuController extends BaseController
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
                        'actions' => ['index', 'del', 'update', 'create', 'sort'],
                        'allow' => true,
                        'roles' => ['@'],
                    ], 
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new MenuForm(['scenario' => Definition::SCENARIOS_SEARCH]);
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new MenuForm(['scenario' => Definition::SCENARIOS_SAVE]);
        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                \Yii::$app->getSession()->setFlash('success', '保存成功');
                return $this->redirect('index');
            } else {
                \Yii::$app->getSession()->setFlash('error', '保存失败');
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = new MenuForm(['scenario' => Definition::SCENARIOS_UPDATE]);
        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->update($id)) {
                \Yii::$app->getSession()->setFlash('success', '保存成功');
                return $this->redirect('index');
            } else {
                \Yii::$app->getSession()->setFlash('error', '保存失败');
            }
        }
        $model->setAttributes(MenuForm::findId($id, true));
        return $this->render('update', [
            'model' => $model,
        ]);
    }


    //删除
    public function actionDel($id)
    {
        if (!$id) {
            return $this->render('index');
        }
        $model = MenuForm::del($id);
        if ($model) {
            \Yii::$app->getSession()->setFlash('success', '删除成功');
        } else {
            \Yii::$app->getSession()->setFlash('error', '存在子集不能删除');
        }
        return $this->redirect('index');
    }

    /**
     * 排序
     *
     * @param $id
     * @param $sort
     *
     * @return string
     */
    public function actionSort($id, $sort)
    {
        $params = [];
        if ($id && $sort) {
            if (MenuForm::returnSort($id, $sort)) {
                $params['code'] = static::AJAX_SUCCESS;
            }
            return $this->redirect('index');
        }
        return $this->renderJson($params);
    }

}