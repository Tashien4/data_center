<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

$form = ActiveForm::begin();?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
#lgdoks-doks_dat-kvdate,#lgdoks-end-kvdate  {width:50%}
</style>
	<?php	$sp_kat=$emodel->sp_kod_all($emodel->_msp);
	echo $form->field($emodel,'_msp')->dropDownList($sp_msp,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"));
	echo $form->field($emodel,'id_kmsasgo')->dropDownList($sp_kat,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"));?>

	<?php 
	if (isset($lgmodel->id) and ($lgmodel->id>0)) {//echo $lgmodel->doks_ser; //$form->errorSummary($lgmodel); ?>

  	   <table style="width:100%">
		<tr>
              		<th colspan="2" style='background-color:#e1e1e1;'>Вид предоставленного документа</th>
              		<th width="40%" style='background-color:#e1e1e1;'>
					<?php echo $form->field($lgmodel,'doktype')->dropDownList($lgmodel->sp_lgdoktype,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"))->label(false);?>
					
			</th>
           	</tr><tr> 
           	</tr><tr style="font-size:0.8em;width:100%"> 
              		<th style='background-color:#f1f1f1;'><?php if ($emodel->id_kmsasgo>145 && $emodel->id_kmsasgo<149)
					{echo 'Серия и номер';} else {echo 'Номер';} ?></th>
              		<th style='background-color:#f1f1f1;'><?php echo 'Дата выдачи и окончание действия'; ?></th>
              		<th style='background-color:#f1f1f1;'>
			
			</th>
           	</tr><tr> 
              		<td>
			     <table style="width:100%"><tr><td>
				<?php if ($emodel->id_kmsasgo>145 && $emodel->id_kmsasgo<149)
					{echo $form->Field($lgmodel,'doks_ser')->textinput(array('size'=>'10')); 
					// $form->error($lgmodel,'doks_ser');
					 } else {echo '&#8195';}?>
              			</td><td>
				<?php echo '&#8195'.$form->Field($lgmodel,'doks_nom')->textinput(array('size'=>'8')).'&#8195'; ?>
				
              			</td></tr></table>
              		</td>
			<td>
			<?php 	
$doks_dat=date("d.m.Y",(strtotime($lgmodel->doks_dat)));
if($lgmodel->doks_dat==0) $lgmodel->doks_dat=date("d.m.Y"); else $lgmodel->doks_dat=$doks_dat;

$end=date("d.m.Y",(strtotime($lgmodel->end)));
if($lgmodel->end==0) $lgmodel->end=date("d.m.Y"); else $lgmodel->end=$end;

			echo $form->field($lgmodel, 'doks_dat')->widget(DatePicker::className(),[
				'name' => 'doks_dat',
		//		'options' => ['style' => 'width: 150px;font-size:12px;'],
				'language' => 'ru','dateFormat' => 'php:d.m.Y',
				'options'=>['autocomplete'=>'off','placeholder'=>'дд.мм.гггг'],
					'value'=> date("d.m.y",(integer) $lgmodel->doks_dat),
					'clientOptions' => [
						'format' => 'dd.mm.yyyy',
						'autoclose'=>true,
						'weekStart'=>1, //неделя начинается с понедельника,
						
					]
			])->label(false);

			echo $form->field($lgmodel, 'end')->widget(DatePicker::className(),[
				'name' => 'end','dateFormat' => 'php:d.m.Y',
				'options'=>['autocomplete'=>'off','placeholder'=>'дд.мм.гггг'],
		//		'options' => ['style' => 'width: 150px;font-size:12px;'],
				'language' => 'ru',
					'value'=> date("d.m.y",(integer) $lgmodel->end),
					'clientOptions' => [
						'format' => 'dd.mm.yyyy',
						'autoclose'=>true,
						'weekStart'=>1, //неделя начинается с понедельника,
						
					]
			])->label(false);
?>
 
	             	</td>

        	      	<td>
				<?php /*if ($lgmodel->id_kem==0) { //Yii::app()->user->isAdmin() or Yii::app()->user->isHomeAdder()) {
						//echo '<strong>Код и наименование выдавшей организации</strong>'; 			
					 	echo '<table><tr><td>'.$form->textField($lgmodel,'doks_kem_kod',array('size'=>'7','style'=>'width:2cm;')).'</td><td>'.$form->textField($lgmodel,'doks_kem',array('size'=>'250','maxlength'=>'250')).$form->error($lgmodel,'doks_kem').'</td></tr></table>';
						//echo $form->error($lgmodel,'doks_kem');
					} else */
						echo '<span style="width:2cm;">'.((isset($lgmodel->doks_kem))?$lgmodel->doks_kem:'').'</span><span style="font-size:0.8em;"></span><br/>';
					

				?>
			</td>
        	</tr>
	   </table> 

	<?php }  ?>
     <br><Br>
		<?php echo $form->field($emodel,'actu_lgdok')->dropDownList($sp830,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"))->label(false);?>

		<?php 
		if ($emodel->actu_lgdok ==0) {
			if ($emodel->id_kmsasgo==74) echo Html::submitButton('Приказ опеки',array('name'=>'803'));
						
			elseif ($emodel->id_kmsasgo==147 or $emodel->id_kmsasgo==159 or $emodel->id_kmsasgo==144 ) echo Html::submitButton('Справка об инвалидности',array('name'=>'84'));
			elseif ($emodel->id_kmsasgo==148) echo Html::submitButton('Протокол ПМПК',array('name'=>'804')); 
            elseif ($emodel->id_kmsasgo==127) echo Html::submitButton('Удостоверение многодетности',array('name'=>'830'));
			        $need_btn=1;
		};

		if ($emodel->id_kmsasgo==0) {echo Html::submitButton('Приказ опеки',array('name'=>'803')).'	&#8195;'.
	                                        Html::submitButton('Справка об инвалидности',array('name'=>'84')).'	&#8195;'.
											Html::submitButton('Удостоверение многодетности',array('name'=>'830')).'	&#8195;'.
                                                Html::submitButton('Протокол ПМПК',array('name'=>'804')).'<br><br>';

		 };
		/* if(($emodel->id_kmsasgo==127 or $emodel->id_kmsasgo==158) and $lgmodel->doktype!=830)
		 echo 'Укажите родителя: '.$form->field($emodel, 'fionamedop')->widget(
			AutoComplete::className(), [            
				'clientOptions' => [
					'source' =>$emodel->fio_auto_dop(),
					'autoFill'=>true,
					'minLength'=>'2'],
					'options' =>['style'=>'width:500px']
					 ])->label('');*/

 ?> 
	<br><br>	<div style="display: flex;
    width: 100%;
    flex-wrap: nowrap;
    justify-content: space-between;">
	<div>	<?php echo Html::submitButton('Записать',array('name'=>'change_lg','class'=>'butn')); ?></div>
	<div><?php echo Html::submitButton(('Сохранить как новый'),array('name'=>'new_lg','class'=>'butn')); ?></div>
						</div>
		<?php //echo Html::submitButton('Записать',array('name'=>'change_lg')); ?>


    <?php ActiveForm::end(); ?>

<script type="text/javascript">
$('#expo-_msp').on('change', function () {
    $.ajax('kmsauto?id_msp=' + this.value, {
        type: "POST",
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                $('#expo-id_kmsasgo').empty();
                $.each(data.prows, function (key, val) {
                    $('#expo-id_kmsasgo').append('<option value="'+val.id+'">'+val.name+'</option>');
                });
            }
        }
    });
});
</script>