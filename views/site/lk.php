<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Site;

?>
<?php $form = ActiveForm::begin(); ?>
<h2 align=center>Личный кабинет пользователя</h2>
<br><br>
<style>
td{padding: 5px;color:black; font-size:20px}
.butn {padding:10px; color:black;font-weight: bold;}
.butn:hover{color:white;cursor:pointer;background:#55b8ff;}
input {padding:5px;}
.rtd {width:35%; text-align:right;}
</style>
<table style="width:100%">
<tr>


<?php if($my_upd['role']==1) echo '<td class="rtd">Имя:</td><td> '.$form->Field($model,'username')->textInput(array('style'=>'width:50%'))->label(false).'</td></tr>';
    else echo  '<td class="rtd">Имя:</td><td> '.$model->username.'</td></tr>';
$uch=(new \yii\db\Query())
    ->select('_school.name')
    ->from('_school')
    ->where('kod='.$model->school)
    ->One();

$sp_uch=$model->sp_org();

if($my_upd['role']==0) 
    echo '<tr><td  class="rtd">Огранизация: </td><td>'.$uch['name'].'</td></tr>';
else
    echo '<tr><td  class="rtd">Огранизация: </td><td>'.$form->field($model,'id_org')->dropDownList($sp_uch,array('style'=>'width:50%'))->label(false).'</td></tr>';

echo '<tr><td class="rtd">Пароль:</td><td>'.$form->field($model,'password')->passwordInput(array('style'=>'width:50%'))->label(false).'</td></tr>';


if($_SESSION['cur_period']==0) 
    $_SESSION['cur_period']=(6+(date("Y")-2010)*12+((date("d")>10)?date("m"):(date("m")-1)));

$per=$_SESSION['cur_period'];
$model->change_mod=$per;
for($i=$per+4;$i>($per-4);$i--)
    $mon[$i]=Site::fromperiod($i,1);

if($my_upd['role']==1) {
    echo '<tr><td  class="rtd">Текущий период:</td><td>'.$form->field($model,'change_mod')->dropDownList($mon,array('style'=>'width:50%'))->label(false).'</td></tr>';
    $model->role=$upd['role'];
    echo '<tr><td  class="rtd">Роль в системе:</td><td>'.$form->field($model,'role')->dropDownList(['0'=>'Пользователь','1'=>'Администратор'],array('style'=>'width:50%'))->label(false).'</td></tr>';
};
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