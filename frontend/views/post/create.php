<?php

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label'=>'文章', 'url'=>['post/index']];
$this->params['breadcrumbs'][] = $this->title;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-lg-9">
        <div class="panel-title box-title">
            <span>创建文章</span>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin() ?>
            <?=$form->field($model,'title')->textInput(['maxlength'=>true])?>
            <?=$form->field($model,'cat_id')->dropDownList($cat)?>
            <?=$form->field($model,'label_img')->widget('common\widgets\file_upload\FileUpload',[
                'config'=>[
                    //图片上传的一些配置，不写调用默认配置
                    'domain_url' => 'http://www.yii-china.com',
                ]
            ])?>
            <?= $form->field($model, 'content')->widget('common\widgets\ueditor\Ueditor',[
                'options'=>[
//                    'initialFrameWidth' => 850,
                ]
            ]) ?>
            <?=$form->field($model,'tags')->widget('common\widgets\tags\TagWidget')?>
            <div class="form-group">
                <?=Html::submitButton("发布",['class'=>'btn btn-success'])?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel-title box-title">
            注意事项
        </div>
        <div class="panel-body">
            <p>1.djkoskfp</p>
        </div>
    </div>
</div>
