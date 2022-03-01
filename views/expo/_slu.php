<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\Site;
$form = ActiveForm::begin();?>

		<table width="100%">
			<tr><th colspan=2>ФИО</th><th width="20%">СНИЛС</th></tr>
			<tr style="color:#6c6c6c;">
					<td colspan=3>
					<?php echo "Заполнять данные получателя, если лицо, являющееся основанием предоставления МСЗ не совпадает с получателем.";$emodel->dopfio["snils"];?></td>
			</tr><tr>
					<td style="font-size:0.8em;"><?php echo Html::submitButton('Изменить',array('name'=>'knopkafiodop')).'==> </td><td>';
					echo '<br>'.$emodel->dopfio["fam"]." ".$emodel->dopfio["im"]." ".$emodel->dopfio["ot"]." (".Site::rusdat($emodel->dopfio["rod"]).")<br><br>";
echo $form->field($emodel, 'fionamedop')->widget(
		AutoComplete::className(), [            
			'clientOptions' => [
				'source' =>$emodel->fio_auto_dop(),
				'autoFill'=>true,
				'minLength'=>'2'],
				'options' =>['style'=>'width:500px']
				 ])->label('');


			?>
				</td><td>

					<?php 
					echo '<br><br><br>'.$form->Field($emodel,'snilsdop')->
					textInput(array('style'=>"width:200px;border:1px solid black;font-size:0.8em;"))->label(false); ?>
					<?php //echo $form->error($emodel,'snilsdop'); ?>
			    </td>

			</tr>
		</table>
<?php ActiveForm::end(); ?>


