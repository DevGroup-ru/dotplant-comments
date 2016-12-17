<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model DotPlant\Comments\models\Comment */

$this->title = Yii::t('dotplant.comments', $model->isNewRecord ? 'Create' : 'Update');
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('dotplant.comments', 'Comments'), 'url' => ['index']],
    $this->title,
];
$statuses  = DotPlant\Comments\models\Comment::getStatusesListData();

?>
<?php $form = ActiveForm::begin(); ?>
<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-md-8 col-sx-12">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
                <?= $form->field($model, 'status')->dropDownList($statuses) ?>
            </div>
            <div class="col-md-4 col-xs-12">
                <?= $form->field($model, 'parent_id')->textInput(['disabled' => 'disabled']) ?>
                <?= $form->field($model, 'applicable_property_model_id')->textInput(['disabled' => 'disabled']) ?>
                <?= $form->field($model, 'model_id')->textInput(['disabled' => 'disabled']) ?>
                <?= $form->field($model, 'user_id')->textInput(['disabled' => 'disabled']) ?>
                <?= $form->field($model, 'created_at')->textInput(['disabled' => 'disabled']) ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton($this->title, ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
