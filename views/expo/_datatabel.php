<?php 
use yii\jui\Dialog;
use yii\widgets\ActiveForm;
use app\models\Site;
use yii\helpers\Html;
?>
<?php
Dialog::begin(array(
                    'id' => 'datatabel_'.$model->id_period.'_'.$model->id_kmsasgo,
                    'clientOptions' => [
                        'title' => $prefix,
                        'autoOpen' => false,
                        'modal' => true,
                        'resizable'=> false
					],
                ));
?>
		<div class="dt_div">

		<?php $form = ActiveForm::begin(); ?>

			<?php 	$weekbegin=strftime('%w', mktime(0, 0, 0, $mes, 1, $god));
				$weekbegin=($weekbegin ==0)? 7: $weekbegin;

				$lastday=$aparam['alldays'];

				if ($model->id>0) 
					echo $form->field($model,'id')->hiddenInput()->label(false, ['style'=>'display:none']); //,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"));
				echo $form->field($model,'id_fio')->hiddenInput()->label(false, ['style'=>'display:none']); //,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"));
				echo $form->field($model,'id_period')->hiddenInput()->label(false, ['style'=>'display:none']); //,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"));
				echo $form->field($model,'id_kmsasgo')->hiddenInput()->label(false, ['style'=>'display:none']); //,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"));
				echo $form->field($model,'coper')->hiddenInput()->label(false, ['style'=>'display:none']); //,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"));
				echo $form->field($model,'klass')->hiddenInput()->label(false, ['style'=>'display:none']); //,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"));

				$ret='<table class="dt_table">';
				$ret.='<tr>';
					for($i=1;$i<8;$ret.=('<th class="dt_th" style="width=30px;">'.Site::rusdnned($i++).'</th>'));
				$ret.='</tr><tr>';

				for($i=(2-$weekbegin);$i<38;$i++) {

					if(($i<=0) or ($i>$lastday)) $ret.='<td class="dt_td_no"></td>';

					elseif(isset($amask[$i]) and $amask[$i]==0) $ret.='<td style="font-size:10px;" class="dt_td_prazd">'.$i.'</td>';

					elseif(((($i+($weekbegin-1))%7==0))) $ret.='<td class="dt_td_prazd">'.((isset($aparam['prazd']))?$aparam['prazd']:'').'</td>';
					else $ret.='<td>'.$form->field($model,'d'.$i)->checkBox(['class'=>"dt_check"]).'</td>';	


					if ((($i+($weekbegin-1))%7)==0) {
						$ret.='</tr>';
						if ($i>$lastday) break;
						else $ret.='<tr id="tr'.$i.'">';
		            		} 
				}
				$ret.='</table>';

?>
				<?php echo $ret;?>


			</table>
			<div class="row buttons">
				<?php echo Html::submitButton('Сохранить',array('name'=>'dt_but')); ?>
				<?php echo Html::submitButton('-6-',array('name'=>'dt_but6','class'=>'dt_but,dtb6')); ?>
				<?php echo Html::submitButton('-5-',array('name'=>'dt_but5','class'=>'dt_but,dtb5')); ?>
				<?php echo Html::submitButton(' х ',array('name'=>'dt_but0','class'=>'dt_but,dtb0')); ?>
			</div>

		<?php ActiveForm::end();  ?>
		</div>

	<?php Dialog::end();
?>
