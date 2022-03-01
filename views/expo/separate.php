<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Site;
use yii\jui\DatePicker;

?>
<style>
    td {border:1px solid black;padding: 10px;}
    .kv-date-picker, .kv-date-remove{display:none;}
    #expo-rend_dop-kvdate{width: 30%;}
</style>
<h4><a href='update?id=<?php echo $model->id;?>'>Назад</a><br></h4>
 <?php $form = ActiveForm::begin(); ?>
 <?php    echo '<h2 align=center>'.$model->efio["fam"]." ".$model->efio["im"]." ".$model->efio["ot"].'</h2>';?>
 <div  style="    display: flex;align-items: center;font-size: 18px;    flex-direction: column;">
 <div style="    display: flex;align-items: center;font-size: 18px;flex-direction: column;border: 5px outset #dfdfdf;
    padding: 15px;">
 <table>
     <tr>
<?php 
echo '<td>Текущая категория</td><td><b>'.$model->get_full_kmsasgo($model->id_kmsasgo).'</b></td></tr>';

$sp_kat_dop=$model->list_kat_dop($model->id_kmsasgo);
echo '<tr><td>Новая категория</td><td>';
                        echo $form->field($model,'id_kmsasgo_dop')->
                        dropDownList($sp_kat_dop,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"))->label(false).'</td></tr>';
                        echo '<tr><td>Дата изменения</td><td>'.$form->field($model, 'rend_dop')->widget(DatePicker::className(),[
                            'name' => 'rend_dop','dateFormat' => 'php:d.m.Y',
                    //		'options' => ['style' => 'width: 150px;font-size:12px;'],
                            'language' => 'ru',
                                
                                'options'=>['style'=>'width:30%','autocomplete'=>'off','placeholder'=>'дд.мм.гггг'],
                                'clientOptions' => [
                                    'format' => 'dd.mm.yyyy',
                                    'autoclose'=>true,
                                    'weekStart'=>1, //неделя начинается с понедельника,
                                   
                                ]
                        ])->label(false).'</td></tr></table><br>';
                             echo Html::submitButton('Разделить',array('name'=>'Separation'));
                            
                            ?>
                            
    <?php ActiveForm::end(); ?>
                                </div></div>