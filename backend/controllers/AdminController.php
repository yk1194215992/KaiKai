<?php
/*
 * @Author: yangkai
 * @Description: ……
 * @Date: 2019-03-08 09:51:32
 */


namespace backend\controllers;

use backend\models\AdminForm;
use backend\common\components\Definition;
use Yii;
use yii\filters\AccessControl;

/**
 * 后台用户控制器
 */
class AdminController extends BaseController
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
                        'actions' => ['index', 'update', 'list', 'file', 'password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 列表
     *
     * @return string
     */
    public function actionList()
    {
        $searchModel = new AdminForm(['scenario' => Definition::SCENARIOS_SEARCH]);
        $searchModel->load(Yii::$app->request->get());
        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(),
        ]);
    }


    /**
     * 添加
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new AdminForm(['scenario' => Definition::SCENARIOS_SAVE]);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', '保存成功');
                return $this->redirect('list');
            }else{
                Yii::$app->getSession()->setFlash('error', '保存失败');
            }
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }


    /**
     * 修改
     *
     * @param int $id
     *
     * @return string
     */
    public function actionUpdate($id)
    {
        if ($id) {
            $model = new AdminForm(['scenario' => Definition::SCENARIOS_UPDATE]);
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->update($id)) {
                    Yii::$app->getSession()->setFlash('success', '保存成功');
                } else {
                    Yii::$app->getSession()->setFlash('error', '保存失败');
                }
            }
            $admin = AdminForm::user(['id' => $id]);
            $model->setAttributes($admin->attributes, false);
            return $this->render('update', [
                'model' => $model,
            ]);
        }

        return $this->redirect('list');
    }


    //封面修改
    /*修改封面图*/
    public function actionFile($id)
    {
        $model = new AdminForm(['scenario' => Definition::SCENARIOS_FILE]);
        if (Yii::$app->request->isPost) {
            $data = $model->file($id);
            if ($data) {
                \Yii::$app->getSession()->setFlash('success', '保存成功');
            } else {
                \Yii::$app->getSession()->setFlash('error', '保存失败');
            }
        }
        $admin = AdminForm::user($id);
        $model->setAttributes($admin->attributes, false);
        return $this->render('file', [
            'model' => $model,
        ]);
    }


    //修改
    public function actionPassword($id)
    {
        $model = new AdminForm(['scenario' => Definition::SCENARIOS_PASSWORD]);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->password($id)) {
                Yii::$app->getSession()->setFlash('success', '保存成功');
            } else {
                Yii::$app->getSession()->setFlash('error', '保存失败');
            }
        }
        $admin = AdminForm::user($id);
        $model->setAttributes($admin->attributes, false);
        return $this->render('password', [
            'model' => $model,
        ]);
    }


}
