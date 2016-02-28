<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Record;

?>
<p class="hello">Create record <a href="<?php echo Url::to(['record/index']) ?>" class="btn btn-right">back to list</a></p>

<?php

$form = ActiveForm::begin([
    'id' => 'record-form',
    // 'options' => ['class' => 'form-horizontal'],
]) ?>

	<?= $form->errorSummary($record) ?>

    <?= $form->field($record, 'type')->dropdownList(
	    Record::types(),
	    ['prompt' => 'Select Type']
	); ?>

    <?= $form->field($record, 'name') ?>
    <?= $form->field($record, 'content') ?>
    <?= $form->field($record, 'ttl') ?>

    <?= Html::submitButton('Create record', ['class' => 'btn btn-save']) ?>
<?php ActiveForm::end() ?>