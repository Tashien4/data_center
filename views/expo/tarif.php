<?php use app\models\Expo;
use yii\jui\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin();  ?>
<h3 style="text-align:center;">Тарифы</h3>


<table border=1 width=100% bgcolor=#F0FFF0 style="text-align:center;">
	<tr align=center bgcolor= #dbd8d8>
	<td rowspan=2 style="width:7%;background: #cee2e1;">Дата начала</td>
	<td rowspan=2 style="width:7%;background: #cee2e1;">Дата окончания</td>
	<td rowspan=2 style="width:7%;background: #cee2e1;">Дистант</td>
	<td rowspan=2 style="width:7%;background: #cee2e1;">Для классов:<br><span style="font-size:10px;">(только для дистанта)</span></td>
	<td colspan=2 style="background: #d6ecff;">ОВЗ и инвалиды</td>
	<td colspan=2 style="background: #d6ecff;">Остальные</td>
    <td colspan=2 style="background: #fffdf5;">Ясли</td>
	<td colspan=2 style="background: #fffdf5;">Старшие группы</td>
    <td rowspan=2 style="width:5%;background: #fffdf5;">Доп. расходы</td>

	</tr><tr align=center bgcolor= #f2f2f2>
	<td style="background: #d6ecff;">1-4 </td>
		<td style="background: #d6ecff;">5-11</td>
		<td style="background: #d6ecff;">1-4</td>
		<td style="background: #d6ecff;">5-11</td>
  		<td style="background: #fffdf5;">10ч</td>
		<td style="background: #fffdf5;">12ч</td>
		<td style="background: #fffdf5;">10ч</td>
		<td style="background: #fffdf5;">12ч</td>
	
	</tr>
          

<?php

$school = Expo::find_all_tarif();
		$d=0;
	foreach($school as $sch) { 
		echo '<tr>
		<td>    <label for="bdata">'.$sch['data'].'</label></td><td>
		<label for="edata">'.$sch['end'].'</label>
		<td><input type="checkbox" name="old_distant" '.($sch['dist']>0?'checked':'').'/></td>
		<td>'.$sch['for_class'].'</td>';
		 $i=2; 
for($s=1;$s<10;$s++) {	
		   echo '	<td>    <label for="t'.$i.'">'.$sch['t'.$i].'</label>
		
		</td>';

if ($s<4) {if($s%2==0 )	$i=$i-3;else $i=$i+2;} 
elseif ($s==4) $i=$i+3;
	elseif ($s==8 or $s==9) $i=$i+1;
else { if ($s%2==0) $i=$i-1;else $i=$i+2;}; 
		
};  
echo'</tr>';$d=$d+1;
	    };       
		     
		echo '<tr><td>'.$form->field($model,'data')->widget(DatePicker::className(),[
			//'name' => 'rbegin',
			'dateFormat' => 'php:Y-m-d',
		//	'options' => ['style' => 'width: 150px;font-size:12px;'],
			'language' => 'ru',
			'options'=>['autocomplete'=>'off','placeholder'=>'ГГГГ-ММ-ДД'],
				'clientOptions' => [
					'format' => 'yyyy.mm.dd',
					'autoclose'=>true,
					'weekStart'=>1, //неделя начинается с понедельника,
			//	'startDate' =>',
				]
		])->label(false).'</td>';
		echo '<td>'.$form->field($model,'end')->widget(DatePicker::className(),[
			//'name' => 'rbegin',
			'dateFormat' => 'php:Y-m-d',
		//	'options' => ['style' => 'width: 150px;font-size:12px;'],
			'language' => 'ru',
			'options'=>['autocomplete'=>'off','placeholder'=>'ГГГГ-ММ-ДД'],
				'clientOptions' => [
					'format' => 'yyyy.mm.dd',
					'autoclose'=>true,
					'weekStart'=>1, //неделя начинается с понедельника,
			//	'startDate' =>',
				]
		])->label(false).'</td>';
		echo '<td>'.$form->field($model,'dist')->checkbox()->label(false).'</td><td>'.
		$form->field($model,'for_class')->textinput()->label(false).'</td>';
		$i=2;
		for($s=1;$s<10;$s++) {	
			   echo '<td>'.$form->field($model,'t'.$i)->textinput()->label(false).'</td>';
			
		if ($s<4) {if($s%2==0 ) 
				$i=$i-3; 
				else $i=$i+2;
			   } 
		elseif ($s==4) $i=$i+3;
			elseif ($s==8 or $s==9) $i=$i+1;
		else { if ($s%2==0) $i=$i-1; 
					else $i=$i+2;}; 	
	
		};

?></tr></table> 
<br><BR>
<?php echo Html::submitButton('Сохранить',array('name'=>'save'));?>
<?php ActiveForm::end(); ?>
