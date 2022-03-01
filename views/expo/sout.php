<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Site;
?>
<style>
    .tabs {width: 700px;
    border: 5px outset #e3e1e1;
    padding: 0 0px 20px 0px;
font-size:18px;
margin:20px;}
    </style>
<?php $all=$model->statist($per); ?>
<?php $form = ActiveForm::begin();  ?>
<div style="display: flex;
    justify-content: center;">
<table class="tabs">
    <tr><td> <h5 align=center><?php echo Site::fromperiod($per,1);?></h5>
Всего:<?php echo $all['al'];?> <br>
Выгружено:<?php echo $all['ot'];?><br>
Осталось:<?php echo $all['stay'];?><br>
     </td></tr>

<tr><td>
<?php   

$pp=0;
	$akodbrak=array(
		'1'=>'Нет СНИЛС',
		'2'=>'Без начислений',
		'3'=>'Нет ФИО',
		'4'=>'Ошибка даты рождения',
		'5'=>'Ошибка основного документа',
		'6'=>'Ошибка даты основного документа',
		'7'=>'Ошибка льготного документа ',
		'8'=>'Нет основного документа',
		'9'=>'Нет ФИО СОПРОВОЖДАЮЩЕГО',
		'10'=>'Ошибка даты рождения СОПРОВОЖДАЮЩЕГО',
		'11'=>'Нет СНИЛС СОПРОВОЖДАЮЩЕГО',
		'12'=>'Ошибка вида основного документа СОПРОВОЖДАЮЩЕГО',
		'13'=>'Нет льготного документа',
		'14'=>'Не указана льготная категория',
		'99'=>'Прочее',
	);



	if (isset($scool['brak'])) {
		foreach($scool['brak'] as $kodbrak=>$items) {

			$_vs='';$_vskol=0;
			echo '<br/><b>'.((isset($akodbrak[$kodbrak]))?$akodbrak[$kodbrak]:'Прочее').'</b><ol>';
			foreach($items as $afio) {

				echo '<li><a href="/expo/update?id='.$afio['id'].'">'.$afio['fio'].'-'.$model->find_coper($afio['coper']).'</a></li>';
			}
			echo '</ol>'; 
		}
			
	} 
   echo '</td></tr><tr><td>';
	foreach($scool['links'] as $links){

			echo '<br/><a href="'.$links['link'].'" download> Скачать файл <b>'.$links['fname'].' ('.$links['fsize'].')</b>. Содержит '.$links['mszcount'].
			' '.($links['mszcount']%10==0?'фактов':
				(($links['mszcount']%10==1 and $links['mszcount']!=11)?'факт':
				(($links['mszcount']%10<5 and ($links['mszcount']<10 or $links['mszcount']>21))?'факта':
				'фактов'))).' МСП </a>';
    };?>
</td></tr><tr><td align=center>
			<br>
		<?php echo Html::submitButton('Заполнить журнал выгрузки, если вариант окончательный',['name'=>'save']); ?>
			<br><br>
</td></tr>
</table>
</div>
<?php ActiveForm::end(); ?>