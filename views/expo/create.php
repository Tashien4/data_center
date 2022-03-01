<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
?>

<div class="expo-create">
<style>
th,td {white-space:nowrap;}
td div{display:inline-block}
#w0 {    padding: 60px 0px 0px 60px;
    border: 5px outset #e3e1e1;}
</style>
<br><BR>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <?php $form = ActiveForm::begin(); ?>
    <table width="100%">
			<tr>
                <th>ФИО и дата рождения</th>
                <th>Класс</th>
            </tr>
            <tr>
				<td style="font-size:0.8em;width: 50%;">
                <?php echo $form->field($model, 'fio')->widget(
                        AutoComplete::className(), [            
                            'clientOptions' => [
                                'source' =>$model->fio_auto(),
                                'autoFill'=>true,
                                'minLength'=>'2'],
                                'options' =>['style'=>'width:500px']
                                 ])->label('');

			    ?>
				</td>
                <td>
					<?php echo $form->field($model, 'klass')->dropDownList($model->sp_klass,
                                    ['value'=>0,'size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"])->label('').
                             '&nbsp;&nbsp;'.$form->field($model, 'liter')->dropDownList($model->sp_liter,
                                        ['size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"])->label(''); ?>
				</td>			
            </tr>
            <tr>
                <th>Мера социальной поддержки</th>
                <th>Категория получателя</th>
            </tr>
            <tr>
                <td>
                    <?php echo $form->field($model,'_msp')->dropDownList($sp_msp,
                        ['size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"])->label('');?>           
                </td>
                <td>
                    <?php echo $form->field($model,'id_kmsasgo')->dropDownList($sp_kat,['size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"])->label('');?>               
                </td>
            </tr>


		</table>

<div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?> 
<script type="text/javascript">
    //если изменяется поле с МСП
$('#expo-_msp').on('change', function () {
    //получаем список льготный категорий для этой МСП из метода kmsauto
    $.ajax('kmsauto?id_msp=' + this.value, {
        type: "POST",
        dataType: 'json',
        success: function (data) {
            //когда ответ получен
            if (data.success) {
                //очищаем значение раскрываеющего списка с льготными категориями
                $('#expo-id_kmsasgo').empty();
                $.each(data.prows, function (key, val) {
                    //создаем новый список из полученных категорий
                    $('#expo-id_kmsasgo').append('<option value="'+val.id+'">'+val.name+'</option>');
                });
            }
        }
    });
});
</script>
    

</div>

