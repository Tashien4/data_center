<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Site;

?>
<?php $form = ActiveForm::begin([
        'id' => 'lk-form']); ?>
<h2 align=center>Добавить пользователя</h2>
<style>
td{padding: 5px;color:black; font-size:20px}
.butn {padding:10px; color:black;font-weight: bold;}
.butn:hover{color:white;cursor:pointer;background:#55b8ff;}
input {padding:5px;}
.rtd {width:25%; text-align:right;}
</style>
<table style="width:100%">
<tr>


<?php echo '<td class="rtd">Имя:</td><td> '.$form->Field($model,'username')->textInput(array('style'=>'width:50%'))->label(false).'</td></tr>';
echo '<td class="rtd">Логин:</td><td> '.$form->Field($model,'login')->textInput(array('style'=>'width:50%'))->label(false).'</td></tr>';


$sp_uch=$model->sp_org();


    echo '<tr><td  class="rtd">Огранизация: </td><td>'.$form->field($model,'id_org')->dropDownList($sp_uch)->label(false).'</td></tr>';

echo '<tr><td class="rtd">Пароль:</td><td>'.$form->field($model,'password')->passwordInput(array('style'=>'width:50%'))->label(false).'</td></tr>';


if($_SESSION['cur_period']==0) 
    $_SESSION['cur_period']=(6+(date("Y")-2010)*12+((date("d")>10)?date("m"):(date("m")-1)));

$per=$_SESSION['cur_period'];
$model->change_mod=$per;
for($i=$per+4;$i>($per-4);$i--)
    $mon[$i]=Site::fromperiod($i,1);


    echo '<tr><td  class="rtd">Текущий период:</td><td>'.$form->field($model,'change_mod')->dropDownList($mon)->label(false).'</td></tr>';
    echo '<tr><td  class="rtd">Роль в системе:</td><td>'.$form->field($model,'role')->dropDownList(['0'=>'Пользователь','1'=>'Администратор'])->label(false).'</td></tr>';

?>
</table>
<br><br>
<div style="display: flex;align-content: flex-end;justify-content: center;">
	<?php echo Html::submitButton('Сохранить',array('class'=>'butn','onclick'=>'prov()')); ?></div>
   
   <?php ActiveForm::end(); ?>
<script>
    function prov() {
        alert('Данные сохранены');
    }
    </script>