<?php
/**
 * 角色列表
 *
 * @author yanghan
 * @version $Id: RoleController.php 1 2017-10-26 10:02:33Z zhangshuai $
 */

namespace backend\controllers;

use backend\models\AdminForm;
use backend\models\RoleForm;
use Yii;
use yii\web\Response;
use yii\filters\AccessControl;
use backend\common\components\Definition;

/**
 * Class RoleController
 *
 * @package backend\application\controllers
 */
class RoleController extends BaseController
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
                        'actions' => ['index', 'delete', 'update', 'list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 角色添加
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new RoleForm(['scenario' => Definition::SCENARIOS_SAVE]);
        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            if ($model->save()) {
                return $this->redirect('list');
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
        
    }
    
    /**
     * 删除角色
     *
     * @param $name
     *
     * @return Response
     */
    public function actionDelete($name)
    {
        if ($name) {
            if (AdminForm::count($name) > 0) {
                Yii::$app->getSession()->setFlash('error', "无法删除，{$name} 正在使用！~");
            } else {
                $auth = Yii::$app->getAuthManager();
                $item = $auth->createPermission($name);
                $auth->remove($item);
            }
        }
        return $this->redirect('list');
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
        $model = new RoleForm(['scenario' => Definition::SCENARIOS_UPDATE]);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->update($name)) {
                \Yii::$app->getSession()->setFlash('success', '保存成功');
            }else{
                \Yii::$app->getSession()->setFlash('error', '保存失败');
            }
        }
        $item = \Yii::$app->getAuthManager()->getRole($name);
        $model->name        = $item->name;
        $model->description = $item->description;
        $model->rules       = \Yii::$app->getAuthManager()->getPermissionsByRole($name);
        return $this->render('update', [
            'model'     => $model,
        ]);
    }

    /**
     * 角色列表
     *
     * @return string
     */
    public function actionList()
    {
        $searchModel = new RoleForm(['scenario' => Definition::SCENARIOS_UPDATE]);
        $searchModel->load(Yii::$app->request->get());
        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(),
        ]);
    }

}