<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2017/2/3
 * Time: 14:18
 */
namespace frontend\controllers\base;

use yii\web\Controller;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
        {
            return false;
        }
        return true;
    }
}