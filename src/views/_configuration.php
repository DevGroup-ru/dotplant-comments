<?php
/**
 * @var \yii\widgets\ActiveForm $form
 * @var \yii\db\ActiveRecord $model
 * @var \yii\web\View $this
 */

use DotPlant\Emails\models\Template;

$templates = Template::find()
    ->select(['name', 'id'])
    ->where(['is_active' => 1])
    ->indexBy('id')
    ->column();
?>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('dotplant.comments', 'Common') ?></div>
            <div class="panel-body">
                <?= $form->field($model, 'commentsPerPage')->textInput(['type' => 'number']) ?>
                <?= $form->field($model, 'allowAnswer')->widget(\kartik\switchinput\SwitchInput::class) ?>
                <?= $form->field($model, 'allowForGuest')->widget(\kartik\switchinput\SwitchInput::class) ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('dotplant.comments', 'Notifications') ?></div>
            <div class="panel-body">
                <?= $form->field($model, 'email') ?>
                <?=
                $form
                    ->field($model, 'emailTemplateId')
                    ->dropDownList($templates, ['prompt' => Yii::t('dotplant.comments', 'Select an item')])
                ?>
            </div>
        </div>
    </div>
</div>
