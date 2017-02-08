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
    public function actions()
    {
        return [
            'upload'=>[
                'class' => 'common\widgets\file_upload\UploadAction',     //这里扩展地址别写错
                'config' => [
                    //上传图片配置
                    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                ]
            ],
            'ueditor'=>[
                'class' => 'common\widgets\ueditor\UeditorAction',
                'config'=>[
                    //上传图片配置
                    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                ]
            ]
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        $model = new PostForm();
        $model->setScenario(PostForm::SCENARIOS_CREATE);
        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            if (!$model->create()){
                Yii::$app->session->setFlash('warning', $model->_lastError);
            }else{
                return $this->redirect(['post/view'], ['id'=>$model->id]);
            }
        }
        $cat = CatsModel::getAllCats();
        return $this->render('create',['model'=>$model,'cat'=>$cat]);
    }
}
