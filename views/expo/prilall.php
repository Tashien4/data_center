<?php
use yii\helpers\Html;
use app\models\Site;
use yii\jui\Accordion;
use yii\widgets\ActiveForm;

$apanel=array();
foreach($model as $id=>$row) {
    if ($id<1000)

		if ($id>=0) 


		$apanel[]=[
			'header'=>$model[-$id]['krat'].'  ('.Site::fromperiod($per,1).')',
			'content'=>
				$this->render('pril4_137', array(
					'model'=>$row,
				        'scool'=>$model[-$id]['krat'],
				        'sp_kms'=>$model[-$id],
				        'noitog'=>'1',
				        'id_scool'=>$id,
				        'coper'=>$coper
					)
				)
		];};
	//	print_r($apanel);

if($id<0) echo '<h3 align=center>Данные еще не заполнены</h3>';

echo Accordion::widget([
		'items' =>$apanel,
		   
			'options' => ['tag' => 'div'],
			'itemOptions' => ['tag' => 'div'],
			'headerOptions' => ['tag' => 'h3'],
			'clientOptions' => ['collapsible' => true],
		]);

?>
