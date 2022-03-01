<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$js0='  
	function bingo(da,tab) {

		var it=document.getElementById("itogo");
		var ii=document.getElementById("tdit_" + da +""); 
		var pi=document.getElementById("_ta_" + tab + "");
		var itar=document.getElementById("_tar_" + tab + "");
		var bef=Number(pi.value);

		var el=document.getElementById("fa_" + tab + "_" +da ); 
		var tar=document.getElementById("tar_" + tab + "_"+da);
		if (el.value==1) {
			el.value=0; 
			pi.value=Number(bef-1);

			it.innerHTML=(Number(it.innerHTML)-1); 
			ii.innerHTML=(Number(ii.innerHTML)-1);
		} else { 
			el.value=1; 
			pi.value=Number(bef+1);

			it.innerHTML=(Number(it.innerHTML)+1);
			ii.innerHTML=(Number(ii.innerHTML)+1);
		} 

		return 0;
	}
';

?>
<div>
<style>
	tr:hover { /*#ul li a:hover, #mainmenu ul li.active a*/
	color: #ecd;
	background-color:#4E4F4A;

}
tr.odd:hover { /*#ul li a:hover, #mainmenu ul li.active a*/
	color: #fff;
	background-color:#4E4F4A;

}
tr.odd {
	background-color:#ADFF2F;
}
td.t_vyh {
	background-color:#DDD;
}

td.kl_fio_first {
	color:red;
}


td.t_zd {

	text-align:center;

}
td.t_yes {
	border: 1px solid #DDD;
	border-radius: 1px;
}
td.t_no {
	background-color:#FFA500;
}
</style>

<?php
$znak=0;
$pos=0;
$prop=0;   
$ait=array();

echo '<script type="text/javascript">'.$js0.'</script>';

$form = ActiveForm::begin();  ?>

<h4 align=center><b>Табель</br><?php echo $class."  класса за ".$zag; ?></b></h4>
<b style="background:#FFA500;border:1px solid black;">&ensp;&ensp;</b>-Отсутвие<br><br>
<b style="background:#white;border:1px solid black;">&ensp;&ensp;</b> <b style="background:#adff2f;border:1px solid black;">&ensp;&ensp;</b>-Посещение<br><br>
<b style="background:#DDD;border:1px solid black;">&ensp;&ensp;</b>-Выходной<br><br>
<table align=center width="100%" border=1px>
	<tr>
		<td bgcolor= #DCDCDC  width="20%" rowspan="2" align=center><b>ФИО</td>
		<td style="text-align:center;background-color:#DDA0DD" colspan="<?php echo $aparam['alldays'];?>"><b>Дни месяца</td>
		<td rowspan="2" style="background-color:#48D1CC;"><b>Посещений</b></td>
	</tr>
	<tr>
			<?php 
			for  ($i=1; $i <=$aparam['alldays']; $i++) {
				echo "<td class='t_zd' id='z[".$i."]' bgcolor=#FFFF00> ".$i." </td>";
				$ait[$i]=0; $ait[-1]=0;$ait[-2]=0;
			}?>
	</tr>
        <tr>
<?php           $pp=1;
		$ai=array();
		$find_dt=1;

		$js="var aid=[";
		foreach ($model as $tab) {

                       $pos=0;
			$prop=0;
			$edaden=0;

			$find_dt=$find_dt*$tab['id'];
			$w0=strftime('%w', mktime(0, 0, 0, $aparam['aper']['mes'], 1, $aparam['aper']['god']))-1;
			echo "<tr".(($pp%2==0)?" class='odd'":"")."><td class='kl_fio".(($tab['id']==0)?'_first':'')."'>".($pp++).". ".$tab['fio'];
			echo "</td>";

			$js.=$tab['eid_fio'].",";

			for  ($j = 1; $j <=$aparam['alldays']; $j++) { 
				$weekbegin=(((($w0+$j)%7)==0)? 7: ($w0+$j)%7);
				$days="d".$j;


				if (	(isset($aparam['avyh'][$li5][$j]) and $aparam['avyh'][$li5][$j]>0)
				     or (isset($aparam['aprazd'][$j]) and $aparam['aprazd'][$j]>0) 

				) {

					$prop=$prop-1;

					echo "<td bgcolor=#FFF8DC class='t_vyh' onclick=\"alert('Выходной');\" ></td>";
				} else {



					$tadays=((($tab[$days]>0) or isset($tab['noser']))?1:0);	
					echo "<td class='".(($tadays==1)?'t_yes':'t_no')."' onclick=\"bingo(".$j.",".$tab['eid_fio']."); this.className = (this.className == 't_yes' ? 't_no' : 't_yes');\" >";
					echo Html::hiddenInput('fa['.$tab['eid_fio'].'][d'.$j.']' , $tadays, array('id' => 'fa_'.$tab['eid_fio'].'_'.$j));
					echo "</td>";
					$pos+=$tadays;
					$ait[$j]+=$tadays;

				}

                                     
			}
			$ait[-1]+=$prop;
			$ait[-2]+=$pos;
			echo "<td id='ta_".$tab['eid_fio']."' style='text-align:right;background-color:#AFEEEE;'>"; 
						echo Html::textInput('Expo['.$tab['eid_fio'].'][fact]' , $pos, array('id' => '_ta_'.$tab['eid_fio'],'readonly'=>'readonly','style'=>'width:25px;'));
echo Html::hiddenInput('did['.$tab['eid_fio'].']' , $tab['id']);
			echo "</td>";
			echo "</tr>";
		} 
		$js=(substr($js,0,strlen($js)-1)."];");
	?>

	</tr>
	<tr><td bgcolor=#87CEFA>Итого</td>
		<?php for  ($i = 1; $i <=$aparam['alldays']; $i++) {
			if ((isset($avyh[$i]) and $avyh[$i]>0) or (isset($aprazdn[$i]) and $aprazdn[$i]>0)) {
			    echo "<td  class='t_vyh'> ".(($ait[$i]>0)?$ait[$i]:'')." </td>";
			} else {
			    echo "<td bgcolor=#00FFFF id='tdit_".$i."'> ".(($ait[$i]>0)?$ait[$i]:'')." </td>";
			}
		}?>
		<!--td style="text-align:right;background-color:#F0FFF0"><?php echo $ait[-1]; ?></td-->
		<td id='itogo' style="text-align:right;background-color:#E0FFFF"><?php echo $ait[-2]; ?></td>
	</tr>
</table> 

<hr size=8, color=#6495ED>
		<?php if(!$find_dt) echo '<b style="color:red">Данные не сохранены</b>'; ?>
		<?php echo Html::submitButton('Сохранить',array('name'=>'button_tabel','style'=>(($find_dt)?'color:black':'color:red;font-weight:bolder'))); //$model->isNewRecord ? 'Создать' : 'Сохранить',array('name'=>'button_tabel'))); ?>
		<?php //echo Html::submitButton('Печать',array('name'=>'button_print')); //$model->isNewRecord ? 'Создать' : 'Сохранить',array('name'=>'button_tabel'))); ?>

		<?php ActiveForm::end(); ?>

<script>
			$(document).ready(function(){
				$(".t_zd").click(function(event) {
	var cou="<?php echo $aparam['alldays']?>";
        var dda=event.target.id;
	var it=0;
	var da=dda.toString();

	da=da.substr(2,da.length-3);
	var ito=$("#itogo");
 	jQuery.each(aid, function() {
		var hid=$("#fa_" + this + "_"+da);
		var ta=$("#_ta_" + this);
		var r=hid.val();
		if (r>0) {
			hid.val("0");
			hid.closest("td").removeClass("t_yes").addClass("t_no").end();

			ta.val(Number(ta.val())-1);

			ito.html(Number(ito.html())-1);
			r=0;
		} else {
			hid.val("1");

			hid.closest("td").removeClass("t_no").addClass("t_yes").end();

			ta.val(Number(ta.val())+1);

			ito.html(Number(ito.html())+1);
			r=1;

		}
		it += Number(r);

     	});

	$("#tdit_"+da).text(it.toString());


 });
});
				</script>


</div>