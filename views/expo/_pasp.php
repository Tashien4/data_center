
<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

$form = ActiveForm::begin(); ?>
	<?php /*if($emodel->actu_pasp==0)
			$vid=1;
	      elseif($model->doks_ser=='')
			$vid=2;
	      elseif($model->doks_nom==0)
			$vid=3;
	      elseif($model->doks_dat==0)
			$vid=4;
	      elseif($model->doks_kem=='')
			$vid=5;
	      else $vid=0;
$vozr=date("y",abs(strtotime(date("Y-m-d"))-strtotime($rod)))-70;
	*/?>
<style>
.old {width:50%;background:white;font-weight: bold;}
.butn {padding:10px;cursor:pointer;    background: linear-gradient(to top, #bfc5c7, transparent);}
.butn:hover{background: linear-gradient(to top, #c2f2fd, transparent);}
select {padding:5px;}
</style>	

<?php 



  	echo' <table style="width: 100%;">
		<tr>
              		<th colspan="2" style="background-color:#e1e1e1;">Вид предоставленного документа</th>
              		<th width="40%" style="background-color:#e1e1e1;">
			'.$form->field($model,'doktype')->dropDownList($model->sp_pasptype())->label(false)
			  //     	else echo $model->sp_doktype[$model->doktype];
			//	echo $form->error($model,'doktype')
			.'</th>
           	</tr><tr> 
           	</tr><tr style="font-size:0.8em;"> 
              		<th style="background-color:#f1f1f1;">Серия и номер</th>
              		<th style="background-color:#f1f1f1;">Дата выдачи</th>
              		<th style="background-color:#f1f1f1;">';
			
				 echo 'Код и наименование выдавшей организации
			</th>
           	</tr><tr> 
              		<td >
			     <table style="border-spacing: 7px 11px;width: 100%;border-collapse: separate;"><tr>
				<td>'.$form->field($model,'doks_ser')->textInput(['size'=>'10']); 
              		echo '</td><td>'.$form->field($model,'doks_nom')->textInput(['size'=>'8']);
				echo '
              			</td></tr></table>
              		</td>
			<td >';
			$doks_dat=date("d.m.Y",(strtotime($model->doks_dat)));
			if($model->doks_dat==0) $model->doks_dat=date("d.m.Y"); else $model->doks_dat=$doks_dat;
			echo $form->field($model, 'doks_dat')->widget(DatePicker::className(),[
				'name' => 'doks_dat','dateFormat' => 'php:d.m.Y',
		//		'options' => ['style' => 'width: 150px;font-size:12px;'],
				'language' => 'ru', 'options'=>['autocomplete'=>'off','placeholder'=>'дд.мм.гггг'],
					'value'=> date("d.m.y",(integer) $model->doks_dat),
					'clientOptions' => [
						'format' => 'dd.mm.yyyy',
						'autoclose'=>true,
						'todayHighlight' => true,
						'weekStart'=>1, //неделя начинается с понедельника,
					//	'startDate' => (date('m')>6?date('Y'):(date('Y')-1)).'-09-01',
					]
			])->label(false);


		echo '
	             	</td>
        	      	<td style="background-color:#fff">';
 
		
//echo $url;                            
			if ($model->id_kem==0) { 		
					 	/*echo '<table width="100%">
							<tr><td>'.$form->Field($model,'doks_kem_kod')->textinput(['size'=>'7','style'=>'width:2cm;']).
							'</td><td>'.$form->Field($model,'doks_kem')->textinput(['size'=>'250','maxlength'=>'250','style'=>'width:9cm;']).
							'</td></tr></table>';*/

					} else {
						echo '<span style="width:2cm;">'.((isset($model->dkem['kod']))?$model->dkem['kod']:'').'</span><span style="font-size:0.8em;">'.((isset($model->dkem['name']))?$model->dkem['name']:'').'</span><br/>';
					};
					echo	$form->field($model, 'doks_kem')->textInput()->label('');
		
	echo '		</td>
        	</tr>
	   </table>    ';
	   ?>


        <?php $sp21=$emodel->find_pasp($model->id_fio);
if(count($sp21)>1)
	echo '<br>
	Список документов, удостоверяющих личность:<br><br>'.$form->field($emodel,'actu_pasp')->dropDownList($sp21,['size'=>1,'maxlength'=>20])->label(false);?>

	<br><br><br><div style="display: flex;
    width: 100%;
    flex-wrap: nowrap;
    justify-content: space-between;">
	<div>	<?php echo Html::submitButton(($model->isNewRecord?'Создать новую запись':'Записать'),array('name'=>'pasp','class'=>'butn')); ?></div>
	<div><?php echo Html::submitButton(('Сохранить как новый'),array('name'=>'new_pasp','class'=>'butn')); ?></div>
						</div>




    <?php ActiveForm::end(); ?>
