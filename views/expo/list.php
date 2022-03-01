<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use app\models\Site;
use app\models\Expo;

?>

<style>
.form-group {margin:0px;}
    </style>

 <?php $form = ActiveForm::begin(['id'=>'user-form','method' => 'get','action' => 'list','validateOnSubmit' => false]); ?>

 <?php 
//$path='expo/list?'.http_build_query(array_merge($_GET, array_intersect_key($_GET, $_GET)));

 if(isset($_GET['Expo']['no_snils']))
    $no_snils=$_GET['Expo']['no_snils'];
else $no_snils=0;
if(isset($_GET['Expo']['no_dok']))
    $no_dok=$_GET['Expo']['no_dok'];
else $no_dok=0;
if(isset($_GET['Expo']['no_lgot']))
    $no_lgot=$_GET['Expo']['no_lgot'];
else $no_lgot=0;
if(isset($_GET['Expo']['no_kms']))
    $no_kms=$_GET['Expo']['no_kms'];
else $no_kms=0;

 echo '<table width="100%" style="font-size:0.8em;">
			    <tr>	
                        <td width="55%" style="font-size:1.4em;" colspan=2>Реестр мер социальной поддержки за <b>'.Site::fromperiod($per,1).'</b>
                        </td></tr><tr>
				        <td style="font-size:14px;width: 70%;">'.
                           ((count($sp_role)>1)?
                            'Изменить оператора'.
                                ($form->field($model,'egisso_role')->
                                    dropDownList($sp_role,
                                        ['size'=>1,
                                        'style'=>'font-size:12px;width: 25%;margin: 0px;',

                                        'onchange'=>'submit();'])->label(false, ['style'=>'display:none']))
                                :'').
                            'Табель класса '.$form->field($model,'pech_tabel')->dropDownList($sp_klass,
						        array('size'=>1,'style'=>'font-size:14px;width: 25%;','onchange'=>'submit();'))->label(false, ['style'=>'display:none']).'</td>
                            <td style="font-size: 14px;">'.$form->field($model,'no_snils')->checkbox(['onchange'=>'submit();',($no_snils>0?['checked'=>'true']:[])]).
                                $form->field($model,'no_dok')->checkBox(['onchange'=>'submit();',($no_dok>0?['checked'=>'true']:[])]).
                                $form->field($model,'no_lgot')->checkBox(['onchange'=>'submit();',($no_lgot>0?['checked'=>'true']:[])]).
                                $form->field($model,'no_kms')->checkBox(['onchange'=>'submit();',($no_lgot>0?['checked'=>'true']:[])]).'</td>

			    </tr>
		        
              <td style="font-size:1.2em;border:4px;"><div style="width: 60%;'.
				(($umodel->iscensored>0)?'':'background-color:#e89;').'">'
				.$form->field($umodel,'iscensored')->checkBox(
					($umodel->iscensored>0?
						array('onchange'=>'submit();'):
						array('onchange'=>'submit();','style'=>'width:15px;background-color:#e89;'))).
			'</div></td>'
				/*<td>Категории:'.$form->field($model,'only_lgot')->
                dropDownList($sp_lgot,array('size'=>1,'style'=>'font-size:12px;width:200px;','maxlength'=>20,'onchange'=>'submit();'))->label(false, ['style'=>'display:none']).'</td>
			*/.'</tr>
		</table>'; ?>
<?php ActiveForm::end(); ?>

<div class="expo-index">

  
    <?php
        
       $searchModel=new Expo;
       $dataProvider = $searchModel->search(Yii::$app->request->get(),$upd);

     echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'fio',
                'format' => 'raw',
                'value' => function ($data) {
                   return (($data->actu_pasp==0)?'<span style="padding:3px;background-color:#ffb1b1;">'.$data->efio["fam"].' '.$data->efio["im"].' '.$data->efio["ot"].' ('.Site::rusdat($data->efio["rod"]).')</span>':
                            (($data->actu_pasp!=0 and ($data->paspdoks['doks_nom']==0 or $data->paspdoks['doks_dat']==0)))?'<span style="padding:3px;background-color:#ffb1b1;">'.$data->efio["fam"].' '.$data->efio["im"].' '.$data->efio["ot"].' ('.Site::rusdat($data->efio["rod"]).')</span>':
                                $data->efio["fam"]." ".$data->efio["im"]." ".$data->efio["ot"]." (".Site::rusdat($data->efio["rod"]).")");
       
                },
            ], 
            [
                'attribute' => 'snils',
                'format' => 'raw',
                'value' => function ($data) {
                    $prows=Yii::$app->db->createCommand("
                    select concat(substring(_mspkod.name,1,75),'...') as name
                    from kmsasgo t
                    inner join msp_asgo on msp_asgo.id=t.id_mspa
                    inner join  _mspkod ON _mspkod.id=msp_asgo.mspkod
                    where t.id=".$data->id_kmsasgo)->queryOne();
                    return '<span '.(($data->efio['snils']<9000000)?'style="padding:3px;background-color:#ffb1b1;">СНИЛС отсутсвует':'>'.Site::russnils($data->efio['snils'])).'</span><br>
                    <span style="font-size:8px;">'.$prows['name'].'</span>';
                },
            ],
            [
                'attribute' => 'id_kmsasgo',
                'format' => 'raw',
                'value' => function ($data) {
                    if($data->id_kmsasgo!=0) {
                        $prows=Yii::$app->db->createCommand("
                        select t.id
                        from kmsasgo t        
                        inner join msp_asgo on msp_asgo.id=t.id_mspa          
                        where needscriteria='' and (msp_asgo.mspkod=758 or msp_asgo.mspkod=771)")->queryAll();
                        foreach($prows as $r)
                            $ar[]=$r['id'];
                    return (($data->actu_lgdok==0 and in_array($data->id_kmsasgo, $ar))?'<span style="padding:3px;background-color:#ffb1b1;">'.$data->kmsasgo["kat"].'</span>':
                            ($data->actu_lgdok!=0 and ($data->lgdoks['doks_nom']==0 or $data->lgdoks['doks_dat']==0))?'<span>'.$data->kmsasgo["kat"].'<br><span style="padding:3px;background-color:#ffb1b1;">(Ошибка в льготном документе)</span></span>':$data->kmsasgo["kat"]);
                   }    
                   else return  '<span style="padding:3px;background-color:#ffb1b1;">Не выбрана льготная категория</span>';  
                },
                'filter'=>$sp_lgot
            ],

            //'snils',
            //'id_fio',
            //'actu_dok',
            //'actu_lgdok',
            //'data_resh',
            [
                'attribute' => 'prim',
                'format' => 'raw',
                'value' => function ($data) {
                    $sp_klas=['-5'=>'Ясельная группа',
                    '-4'=>'Младшая группа',
                    '-3'=>'Средняя группа',
                    '-2'=>'Старшая группа',
                    '-1'=>'Подготовительная гр.',
                    '0'=>''];
                    return (((int)$data->prim>0)?$data->prim:$sp_klas[(int)$data->prim]);
                },
            ],
            'fact',

            //'tarif',
            //'nach',
            'nach',
            //'coper',
            //'id_fiodop',
            //'need_deleted',

           // ['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}','headerOptions' => ['style' => 'width:8%'],
            
        ],
        ],
    ]); ?>


</div>
