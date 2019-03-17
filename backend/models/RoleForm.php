<?php

namespace backend\models;

use backend\common\components\Definition;
use Yii;
use backend\common\models\Item;
use yii\base\Exception;
use yii\data\ActiveDataProvider;

class RoleForm extends BaseForm
{

    public $name;
    public $description;
    public $rules;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'rules'], 'required', 'on' => [Definition::SCENARIOS_SAVE, Definition::SCENARIOS_UPDATE], 'message' => '{attribute}不能为空',],
            ['name', 'rulesName', 'on' => [Definition::SCENARIOS_SAVE],],
        ];
    }

    public function rulesName($attribute)
    {
        $exists = Item::ruleName($this->$attribute);
        if ($exists) {
            $this->addError($attribute, '属性已存在，请重新输入！~');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '角色名称',
            'description' => '角色简介',
            'rules' => '角色权限',
        ];
    }


    /**
     * 角色列表
     *
     * @return ActiveDataProvider
     */
    public function search()
    {
        if (Yii::$app->user->identity->id != 1) {
            $query = Item::roleSearch();
        } else {
            $query = Item::role_search();
        }
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 8],
        ]);
    }

    /**
     * 保存
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $auth = \Yii::$app->authManager;
            $role = $auth->createRole($this->name);

            $role->description = $this->description;
            $auth->add($role);
            $rules = $this->rules;
            $parent = isset($rules['parent']) ? $rules['parent'] : [];
            $child = isset($rules['child']) ? $rules['child'] : [];
            foreach ($parent as $rule) {
                $item = $auth->createPermission($rule);

                $auth->addChild($role, $item);
            }
            foreach ($child as $index => $value) {
                if (!array_key_exists($index, $parent)) {
                    foreach ($value as $rule) {
                        $item = $auth->createPermission($rule);

                        $auth->addChild($role, $item);
                    }
                }
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            \Yii::error($e, static::className());

            $transaction->rollBack();
        }
    }

    public function update($name)
    {
        if (!$this->validate()) {
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $auth = \Yii::$app->authManager;
            $role = $auth->createRole($this->name);

            $role->description = $this->description;
            $auth->update($name, $role);
            $auth->removeChildren($role);

            $rules = $this->rules;
            $parent = isset($rules['parent']) ? $rules['parent'] : [];
            $child = isset($rules['child']) ? $rules['child'] : [];
            foreach ($parent as $rule) {
                $item = $auth->createPermission($rule);

                $auth->addChild($role, $item);
            }
            foreach ($child as $index => $value) {
                if (!array_key_exists($index, $parent)) {
                    foreach ($value as $rule) {
                        $item = $auth->createPermission($rule);

                        $auth->addChild($role, $item);
                    }
                }
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            \Yii::error($e, static::className());

            $transaction->rollBack();
        }
    }

}