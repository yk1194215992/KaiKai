<?php
/**
 * Definition.php
 *
 * @author apple
 * @version $Id: Definition.php 109 2017-02-15 08:57:03Z zhangshuai $
 */

namespace backend\common\components;

/**
 * Definition
 */
class Definition
{
    /**
     * @var 场景
     */
    const SCENARIOS_PASSWORD = 'password';
    const SCENARIOS_SEARCH = 'search';
    const SCENARIOS_SAVE   = 'save';
    const SCENARIOS_DELETE = 'delete';
    const SCENARIOS_UPDATE = 'update';
    const SCENARIOS_DETAIL = 'detail';
    const SCENARIOS_FILE = 'file';
    const SCENARIOS_SHELVES = 'shelves';
    //登录使用
    const SCENARIOS_LOGIN = 'login';





    /**
     * 状态
     */
    const STATUS_ZERO = 0;
    const STATUS_ONE  = 1;

    /**
     * 获取默认下拉
     */
    public static function getStatusSelect()
    {
        return [
            static::STATUS_ZERO => '否',
            static::STATUS_ONE  => '是',
        ];
    }

}
