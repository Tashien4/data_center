<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\jui\DatePicker;
?>
<style>
 #w0{display: flex;
    flex-direction: column;
    align-items: center;} 
    .kv-date-picker, .kv-date-remove{display:none;} 
 .ff{padding: 20px;
    border: 5px outset #ddd8d8;
text-align:center;}
 </style>
<?php $fio=$_GET['fio'];
    $ffio=explode(' ',$fio);
    $fam=(isset($ffio[0])?$ffio[0]:'');
    $im=(isset($ffio[1])?$ffio[1]:'');
    $ot=(isset($ffio[2])?$ffio[2]:'');
?>
    <?php $form = ActiveForm::begin(); ?>  
     <div class="ff">
    <h2 align=center>ВНИМАТЕЛЬНО ПРОВЕРЬТЕ ВВЕДЕНЫЕ ДАННЫЕ</h2>
    <table width="100%" style="margin: 10px;">
			
             <tr><Td colspan=2 align=left><?php echo $form->field($pmodel,'fam')->textInput(['value'=>$fam]);?></td></tr>
             <tr><Td colspan=2 align=left><?php echo $form->field($pmodel,'im')->textInput(['value'=>$im]);?></td></tr>
             <tr><Td colspan=2 align=left><?php echo $form->field($pmodel,'ot')->textInput(['value'=>$ot]);?></td></tr>
             <tr><Td align=left><?php echo $form->field($pmodel,'pol')->dropdownList(['0'=>'Мужской','1'=>'Женский'],['style'=>'width: 50%;']);?></td>
             <Td width=30% align=left><?php echo $form->field($pmodel,'rod')->widget(DatePicker::className(),[
    				'language' => 'ru',
                    'options'=>['autocomplete'=>'off','placeholder'=>'дд.мм.гггг'],'dateFormat' => 'php:d.m.Y',
  					//  'clientOptions'=>['style'=>'width:30%'],
   					 'clientOptions' => [
      				  	'format' => 'dd.mm.yyyy',
       				 	'autoclose'=>true,

       				 	'weekStart'=>1, //неделя начинается с понедельника,
    					]
				]);?></td></tr>

                        </table>

        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
 </div>
<?php ActiveForm::end(); ?> 
           

