<?php 
use yii\jui\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
?>
<div class="form">
<style>
.field-expo-rbegin, .field-expo-rend {width:150px;}
	#expo-rbegin, #expo-rend{width:100px;}
	.kv-date-picker, .kv-date-remove{display:none;}

.close,.close_2{
    cursor: pointer;
    position: absolute;
    right: 15px;
    text-align: center;
    top:15px;
}
.modal_1,.modal_2{
    background-color: white;
    display: none;
/*    height: 300px;  */
    left:45%;
    margin-left: -200px;
    margin-top:-150px;
    position: fixed;
    top:40%;
    width: 400px; 
    z-index: 1000;
}
.overlay_1,.overlay_2{
    background-color: black;
    bottom:0;
    display: none;
    left:0;
    margin: 0;
    opacity: 0.65;
    position: fixed;
    top:0;
    right: 0;
    z-index: 999;
}

</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

<?php $form = ActiveForm::begin([
	'enableAjaxValidation'=>false]); 

?>
<style>th,td {text-align:center; border:1px solid black;}</style>

	<table width="100%">
			<tr><th colspan=3 style="border-right:1px solid black;"></th>
				<th width="30%" style="border-right:1px solid black;">Класс</th><th width="15%">СНИЛС<br> (только цифры)</th></tr>
			<tr >
					<td></td>
					<td >Дата начала МСП<?php //echo $model->efio["fam"]." ".$model->efio["im"]." ".$model->efio["ot"]." (".Site::rusdat($model->efio["rod"]).")";?>
					</td>
					<td style="border-right:1px solid black;">Выбыл</td>
					<td style="border-right:1px solid black;">
						<?php echo ((isset($model->sp_klass[trim((int)$model->klass)]))?$model->sp_klass[trim((int)$model->klass)]:print_r($model->sp_klass)); ?>
						<?php echo ((isset($model->sp_liter[$model->liter]) and ($model->sp_liter[$model->liter]))?$model->sp_liter[$model->liter]:''); ?>
					</td>

					<td><?php echo $pmodel->snils;?></td>
			</tr><tr>
					<td style="height: 80px;">
					<?php echo Html::submitButton('Изменить',array('name'=>'change_kl')).'==> ';
					
					
					
				$rbegin=substr($model->rbegin, 8, 2).'.'.substr($model->rbegin, 5, 2).'.'.substr($model->rbegin, 0, 4);
				if($model->rbegin==0) $model->rbegin=date("d.m.Y"); else $model->rbegin=$rbegin;
				$rend=substr($model->rend, 8, 2).'.'.substr($model->rend, 5, 2).'.'.substr($model->rend, 0, 4);
					//if($model->rbegin==0) $model->rbegin="00.00.0000"; else 
					//$model->rbegin=$rbegin;

					
					if($model->rend==0) $model->rend=date("d.m.Y"); else 
					$model->rend=$rend;
						echo '</td><td style="height: 80px;">'.
				$form->field($model, 'rbegin')->widget(DatePicker::className(),[
  				  //'name' => 'rbegin',
					'dateFormat' => 'php:d.m.Y',
				//	'options' => ['style' => 'width: 150px;font-size:12px;'],
    				'language' => 'ru',
					'options'=>['autocomplete'=>'off','placeholder'=>'дд.мм.гггг'],
   					 'clientOptions' => [
      				  	'format' => 'dd.mm.yyyy',
       				 	'autoclose'=>true,
       				 	'weekStart'=>1, //неделя начинается с понедельника,
					//	'startDate' =>',
    					]
				])->label(false);
						echo '</td><td style="border-right:1px solid black;height: 80px;">'.
						$form->field($model,'need_deleted')->checkBox().'<div id="Erend" style="display:none;">';
						echo $form->field($model, 'rend')->widget(DatePicker::className(),[
						//	'name' => 'rend',
						'dateFormat' => 'php:d.m.Y',
					//		'options' => ['style' => 'width: 150px;font-size:12px;'],
							'language' => 'ru', 'options'=>['autocomplete'=>'off','placeholder'=>'дд.мм.гггг'],
								
								'clientOptions' => [
									'format' => 'dd.mm.yyyy',
									'autoclose'=>true,
									'weekStart'=>1, //неделя начинается с понедельника,
								//	'startDate' => '01.09.'.(date('m')>6?date('Y'):(date('Y')-1)),
								]
						])->label(false);

					echo '</div></td>';

			?>
				</td><td style="font-size:0.8em;border-right:1px solid black;display: flex;height: 80px;">
					<?php echo $form->field($model,'klass')->dropDownList($model->sp_klass,[ 'style'=>'font-size:12px;']).
					$form->field($model,'liter')->dropDownList($model->sp_liter,[ 'style'=>'font-size:12px;']);?>
				</td><td>

				<?php echo $form->Field($pmodel,'snils')->textInput(['size'=>1,'maxlength'=>20,'autocomplete'=>'off'])->label(false); ?>

			    </td>

			</tr>
		</table>

<?php 

if($ans!='') 
    print_r($ans);?>
<?php ActiveForm::end(); ?>

<?php 
$script = <<< JS
$(document).ready(function() {
  $('#expo-need_deleted').change(function() {
    if (this.checked) {
      $('#Erend').css('display', 'block');
    } else {
      $('#Erend').css('display', 'none');
    }
  }).change();
});
JS;
$this->registerJs($script, yii\web\View::POS_END); 

?>

</div><!-- form -->
<script>
$(document).ready(function () {

   // $('#show_1').on('click',
   //         function () {
                $('.overlay_1,.modal_1').show();
   //         }
 //   );

    $('.overlay_1,.close').on('click',function(){
        $('.overlay_1,.modal_1').hide();
		window.location.replace('update?id='+<?php echo $model->id?>+'&ret=1');
    });
    $('.overlay_1,.forw').on('click',function(){
        $('.overlay_1,.modal_1').hide();
    });

});
</script>