<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Site;
use kartik\date\DatePicker;

?>
<style>
.tab {width: 90%;
    font-size: 15px;}
.tab th,.tab td {
    text-align:center;

    padding: 5px;
    border: 3px solid #000;
}
.empt {    background-color: #ecc;}
#expo-rend_dop-kvdate {width:15%}
#expo-id_kmsasgo_dop {width:30%}

</style>

		
    <?php $form = ActiveForm::begin(); ?>
    <?php 
		$ap=Site::fromperiod($model->id_period);
		$period1=$model->id_period-($ap['mes']-1);
        
         
        $sp_kat_dop=$model->list_kat_dop($model->id_kmsasgo);                   						
       /* echo $form->field($model,'need_change')->checkBox(['onchange'=>'
                var idd=document.getElementById("change_rend"); 
                if(idd.style.display=="none"){  idd.style.display = "block"; } 
                else{ idd.style.display = "none";}	
        ']).'
        <div id="change_rend" style="display:none;">';
                        echo $form->field($model,'id_kmsasgo_dop')->
                        dropDownList($sp_kat_dop,array('size'=>1,'maxlength'=>20, 'style'=>"font-size:0.8em;"))->label(false);
                        echo $form->field($model, 'rend_dop')->widget(DatePicker::className(),[
                            'name' => 'rend_dop',
                    //		'options' => ['style' => 'width: 150px;font-size:12px;'],
                            'language' => 'ru',
                                'value'=> date("d.m.y",(integer) $model->rend),
                                'options'=>['style'=>'width:30%'],
                                'pluginOptions' => [
                                    'format' => 'dd.mm.yyyy',
                                    'autoclose'=>true,
                                    'weekStart'=>1, //неделя начинается с понедельника,
                                   
                                ]
                        ])->label(false);
                             echo Html::submitButton('Разделить',array('name'=>'Separation'));
                            echo '<br><Br></div>';
    */
		echo '<table class="tab">
        <tr><th style="font-size:0.7em;width:15%">По месяцам</th>';

			for($i=1;$i<13;$i++) {
				if (isset($sp_his[$period1-1+$i])) {
					$cou=0;
					foreach($sp_his[$period1-1+$i] as $kl=>$ar) {	
							$cou++;
					}
				} else $cou=1;
		
				echo '<td '.(($cou>1)?(' colspan="'.$cou.'"'):'').'>'.Site::ntocmonth($i).'</td>';

			}
		echo '</tr>';
		echo '<tr><th >Категория</th>';
        
        for($i=$period1;$i<$period1+12;$i++) {
            $v=''; 
            if (isset($sp_his[$i])) {
                foreach($sp_his[$i] as $kl=>$ktar) { 
                    $v.=(($v=='')?'':'</td>
                            <td style="background-color:#cdf;">').($upd['role']==1?'
                                <a href="update?id='.$ktar['id'].'">'.$model->getkratkmsasgo($kl)." ".$kl.'</a>':$model->getkratkmsasgo($kl)." ".$kl);
                };
            }
            echo '<td style="background-color:#cdf;">'.$v.'</td>';
                        
        }

    echo '</tr>';
    echo '<tr><th style="font-size:0.7em;">Тариф</th>';
        for($i=$period1;$i<$period1+12;$i++) {
            $v='';
            if (isset($sp_his[$i])) {
                foreach($sp_his[$i] as $kl=>$ktar) {
                    $v.=(($v=='')?'':'</td><td>').$ktar['tarif'];
                }
            }
            echo '<td'.(($v!='')?'':' class="empt"').'>'.$v.'</td>';
        }

    echo '</tr>';
    echo '<tr><th style="font-size:0.7em;">Дней</th>';
        for($i=$period1;$i<$period1+12;$i++) {
            $v='';
            if (isset($sp_his[$i])) {
                foreach($sp_his[$i] as $kl=>$tar) {

                    if(true) 
                        $v.=(($v=='')?'':'</td><td>').$tar['fact'];
                    elseif (($tar['in_period']==0)) {

                        $model->afact[$i][$tar['id']]=$tar['fact'];

                        if ($regimiskl==0) {
                            $v.=(($v=='')?'':'</td><td>').
                               /* Html::a(
                                   */ (($model->afact[$i][$tar['id']])?
                                        $model->afact[$i][$tar['id']]:'0');//, '#', array('onclick'=>"$(\"#datatabel_".$tar['id_period']."_".$kl."\").dialog(\"open\"); return false;"));
                        } else {
                            $v.=(($v=='')?'':'</td><td>').$form->textField($model,'afact['.$i.']['.$tar['id'].']',array('size'=>7,'maxlength'=>7,'style'=>"color:#e65;border:1;margin:0;"));
                        }

                    } 
                 
                }
            }	
            echo '<td'.(($v!='')?'':' class="empt"').'>'.$v.'</td>';

        }

    echo '</tr>';
    echo '<tr><th style="font-size:0.7em;">На сумму <br/>(ПАСГО)</th>';
        for($i=$period1;$i<$period1+12;$i++) {
            $v='';
            if (isset($sp_his[$i])) {
                foreach($sp_his[$i] as $kl=>$tar) {
                    $v.=(($v=='')?'':'</td><td>').$tar['nach'];
                }
            }
            echo '<td'.(($v!='')?'':' class="empt"').'>'.$v.'</td>';
        }

    echo '</tr>';
        ?>
</table>

    <?php ActiveForm::end(); ?>


