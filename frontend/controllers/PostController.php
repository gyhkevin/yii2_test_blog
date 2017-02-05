<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2017/2/3
 * Time: 14:18
 */
namespace frontend\controllers;

use common\models\CatsModel;
use frontend\models\PostForm;
use Yii;
use frontend\controllers\base\BaseController;

class PostController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        $model = new PostForm();
        $cat = CatsModel::getAllCats();
        return $this->render('create',['model'=>$model,'cat'=>$cat]);
    }
}