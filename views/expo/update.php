<?php

use yii\helpers\Html;
use app\models\Site;
use app\models\Datatabel;
use yii\jui\Accordion;
use yii\widgets\ActiveForm;

?>
<div class="expo-update">

<?php
 
if(isset($_GET['ret']))
    $tabs=(int)$_GET['ret'];
else $tabs=0;
$kms=$model->get_full_kmsasgo($model->id_kmsasgo);
echo Accordion::widget([
    'items' =>[
        [//вкладка с ФИО получателя и сводом о полученных суммах в разных отчетных периодах
            'header'=>($model->efio["fam"]." ".$model->efio["im"]." ".$model->efio["ot"]." (".Site::rusdat($model->efio["rod"]).")"),
        'content'=>$this->render('_form', ['model'=>$model,'regimiskl'=>0,'sp_his'=>$sp_his,'upd'=>$upd])
        ],
        [//вкладка с указанием класса, СНИЛС и датами предоставления МСП
            'header'=>'Класс (группа) <strong>'.$model->prim.'</strong>, <span style=\"'.(($model->efio['snils']<9000000)?$model->cssnosnils:'').'\">СНИЛС: '.Site::russnils($model->efio['snils']).'</span>',
         'content'=>$this->render('_klass', ['ans'=>$ans,'model'=>$model,'pmodel'=>$pmodel,'regimiskl'=>0])
        ],
        ['header'=>($pasp_model->sp_doktype($pasp_model->doktype)." ".$pasp_model->doks_ser." ".$pasp_model->doks_nom),
          'content'=>$this->render('_pasp', ['model'=>$pasp_model,'regimiskl'=>0,'emodel'=>$model])
        ],
        ['header'=>'<strong>'.(($model->id_kmsasgo>0)?$kms:'--нет категории--').' </strong>'.((isset($lgmodel->id) and ($lgmodel->id>0) and isset($lgmodel->sp_lgdoktype[$lgmodel->doktype]))?$lgmodel->sp_lgdoktype[$lgmodel->doktype]:''),
         'content'=>$this->render('_lgt', ['lgmodel'=>$lgmodel,'sp_msp'=>$sp_msp,'sp_kat'=>$sp_kat,'sp830'=>$sp830,'emodel'=>$model,'kms'=>$kms])
        ]  
       
    ],
    'options' => ['tag' => 'div'],
    'itemOptions' => ['tag' => 'div'],
    'headerOptions' => ['tag' => 'h3'],
    'clientOptions' => ['collapsible' => true,'active' =>$tabs],
]);
$ap=Site::fromperiod($model->id_period);
$period1=$model->id_period-($ap['mes']-1);

$li5=(($model->prim<5)?5:6);
for($i=1;$i<=12;$i++) {
    $per=($ap['god']-2010)*12+6+$i;

    if (isset($sp_his[$per])) { 
        foreach($sp_his[$per] as $kms=>$tar) {

            $dttmodel=Datatabel::find(['id_fio'=>$model->id_fio,
                                        'id_period'=>$per,
                                        'id_kmsasgo'=>$kms,
                                        'coper'=>$coper,
                                        'klass'=>$tar['prim']])->all(); //)$model->id_kmsasgo));
            if (!isset($dttmodel->id)) {
                $dttmodel=New Datatabel;
                $dttmodel->id_fio=$model->id_fio;
                $dttmodel->id_period=$per;
                $dttmodel->coper=$coper;
                $dttmodel->id_kmsasgo=$kms;
                $dttmodel->klass=$tar['prim'];
            };

          /*  echo $this->render('_datatabel', 	array(
              //  'caltable'=>$dtmodel->gettabel($model->id_fio,$ap['god'],$i,$kms, $kl),
                'model'=>$dttmodel,
                'mes'=>$i,
                'god'=>$ap['god'],
                'prefix'=>Site::ntocmonth($i),
             //   'backurl'=>Yii::app()->createUrl($this->route).'/'.$model->id,

                'aparam'=>$dttmodel->kl_param($per,$coper),
                'li5'=>$li5,
                'amask'=>((isset($_SESSION['mask'][$per][$coper]))?$_SESSION['mask'][$per][$coper][$li5]:array())
                    )
            ,true);*/
            			

        }

    }

}
?>

</div>
