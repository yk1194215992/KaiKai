<?php
/**
 * ItemController
 *
 * @version $Id: ItemController.php 1 2017-10-26 10:02:33Z zhangshuai $
 */

namespace backend\controllers;

use backend\models\ItemForm;
use backend\common\components\Definition;
use Yii;
use yii\filters\AccessControl;

/**
 * Item Controller
 */
class ItemController extends BaseController
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
                        'actions' => ['index', 'update', 'list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 模块添加
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new ItemForm(['scenario' => Definition::SCENARIOS_SAVE]);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', '保存成功');
                return $this->redirect('list');
            }else{
                \Yii::$app->getSession()->setFlash('error', '保存失败');
            }
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Displays House Update From
     *
     * @param string $name
     *
     * @return string
     */
    public function actionUpdate($name)
    {
        if (!$name) {
            return $this->redirect('list');
        }
        $model = new ItemForm(['scenario' => Definition::SCENARIOS_UPDATE]);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->update($name)) {
                \Yii::$app->getSession()->setFlash('success', '修改成功');
            }else{
                \Yii::$app->getSession()->setFlash('error', '修改失败');
            }
        }
        $model->setAttributes($model->findModel($name), false);
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 模块列表
     *
     * @return string
     */
    public function actionList()
    {
        $searchModel = new ItemForm(['scenario' => Definition::SCENARIOS_SEARCH]);
        $searchModel->load(Yii::$app->request->post());
        return $this->render('list', [
            'searchModel'  => $searchModel,
            'dataProvider' => $searchModel->search(),
        ]);
    }

}
