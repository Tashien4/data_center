<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\jui\AutoComplete;
use app\models\Users;
use yii\web\JsExpression;

?>

<style>

    .ui-menu-item-wrapper{font-size: 0.8em;}
    .col-lg-3 {
        width:200px;
        max-width: 100%;
        text-align: right;
    }
    #users-username {width:300px;}

    #login-form {width: 700px;
    border: 5px outset #e3e1e1;
    padding: 0 0px 20px 0px;
    text-align: center;
margin:20px;}
.site-login{display: flex;
    justify-content: center;}
</style>
<div class="site-login">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "<tr><td class=\"col-lg-3\">{label}\n</td><td>{input}</div></td></tr>
            <tr><td colspan=2>\n<div class=\"col-lg-8\">{error}</div></td></tr>",
            //'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-3 col-form-label'],
        ],
    ]); ?>
<?= $form->errorSummary($model)?>
<h1>Авторизация</h1>

 <br>
<h5>Введите имя и пароль для определения Ваших полномочий:</h5>
<table>
    <tr>
        
		<?php  	


echo	$form->field($model, 'username')->widget(
                        AutoComplete::className(), [            
                            'clientOptions' => [
                                'source' =>$model->find_user(),
                                'autoFill'=>true,
                                'minLength'=>'2'],
                                'options' =>['style' => 'font-size: 0.8e;width:300px;',],
                                 ]);
    

?></tr>
<tr>

		<?php echo $form->field($model, 'password')->passwordInput() ?>
</tr><tr><td></td><td align=center><br>
		<?php echo Html::submitButton('Войти', ['style'=>'padding:5px;width: 300px;']); ?>
                                </td></tr>
  </table>
<br>


    <?php ActiveForm::end(); ?>

 
