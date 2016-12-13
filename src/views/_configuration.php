<?php
/**
 * @var \yii\widgets\ActiveForm $form
 * @var \yii\db\ActiveRecord $model
 * @var \yii\web\View $this
 */
?>

<div class="box-body">
    <?= $form->field($model, 'commentsPerPage')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'allowAnswer')->widget(\kartik\switchinput\SwitchInput::class) ?>
    <?= $form->field($model, 'allowForGuest')->widget(\kartik\switchinput\SwitchInput::class) ?>
</div>
