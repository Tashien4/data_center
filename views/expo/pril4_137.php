<!--h2 align=center>Суммы начислений</h2-->

<?php   $zag='';$ok='';
        if (isset($id)) {
	        for($z=0;$z<6;$z++) {
			$pers=$_SESSION['cur_period']-5+$z; 
        		$zag=(($pers==$id)?'<b style="font-size:20px;">':'');
		 	$ok=(($pers==$id)?'</b>':'');
			echo '<a href="expo/pril4/'.(($pers<137)?('/'.$pers):('_137/'.$pers)).'">'.$zag.   Site::fromperiod($_SESSION['cur_period']-5+$z,1).$ok.'</a>&#8195&#8195';
		};
	};?>

<table border=1 width=80% bgcolor=#F0FFF0 align=center style="    font-size: 20px;">
<tr align=center bgcolor= #FFEBCD>
	<!--th rowspan="2" style="width:20px;">№ п/п</th-->
	<th rowspan="2" style="width:40%;"><font size=2 color=red <b> Наименование показателя</th>
	<th rowspan="2"><font size=2 color=red><b> Кол-во </th></font>
	<!--th rowspan="2"><font size=2 color=red><b> Посещений </th></font-->
	<th colspan="3" style="width:120px;"><font size=2 color=red><b>Столовая</th></font>
	<th colspan="3" style="width:120px;"><font size=2 color=red><b>Дистант</th></font>
	<th rowspan="2"><font size=2 color=red><b>ВСЕГО</th></font>
</tr>
<tr align=center bgcolor= #FFEBCD>
	<th style="width:30px;"><font size=2 color=red><b> Тариф </th></font>
	<th style="width:40px;"><font size=2 color=red><b> кол </th></font>
	<th style="width:50px;"><font size=2 color=red><b> Начислено </th></font>
	<th style="width:30px;"><font size=2 color=red><b> Тариф </th></font>
	<th style="width:40px;"><font size=2 color=red><b> кол </th></font>
	<th style="width:50px;"><font size=2 color=red><b> Начислено </th></font>
</tr>
          
<?php

$i=0;
$pr=' '; //&nbsp;

$zag=array('0'=>'--- НЕ УСТАНОВЛЕН КЛАСС !!!! ----','1'=>'Численность учащихся 1-4 классов - всего, человек*','5'=>'Численность учащихся 5-11 классов, нуждающихся в социальной поддержке - всего, человек*',);
 foreach($model as $klas=>$ar) {                                              
	$s=''; 
	$skol=0; $skold=0; $skolp=0; $sumd=0; $sump=0; $sit=0; 
	
 	foreach($ar as $id_kmsasgo=>$akms) {                                              
		if ($id_kmsasgo>0) {
		        $it=0;
	 		foreach($akms as $tarif=>$akol) {                                              
//if (Yii::app()->user->id==1) print_r($akol); //$ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]['nach'].' |'.$row['nach'].'<br/>';    	
//print '<br/>'.$tarif.'|';//.print_r($akol);

				$s.='<tr align=center '.((($sp_kms[-$id_kmsasgo]=='---не установлена категория---') or ($klas==0) or ($klas>4 and $id_kmsasgo==149))?'style="color:red;font-style:bold;"':'').'>';
//					<!--td align=center>'.(++$i).'</td-->
				$s.='<td style="text-align:left;">'.$sp_kms[-$id_kmsasgo].'</td>';
//echo '<td align=center colspan="5">'.print_r($akol).'</td></tr>';


				$s.='<td align=center>'.(($id_scool==$coper)?'<a href="expo/list?coper='.$id_scool.'&id_kmsasgo='.$id_kmsasgo.'&lklass='.$klas.'">'.$akol['cou'].'</a>':$akol['cou']).'</td>
				<!--td align=right><nobr>'.number_format(((isset($akol['days']))?(0+$akol['days']):0),0,',',$pr).'</nobr></td-->
				<td align=right style="font-size:0.7em;"><nobr>'.(($akol['sump']>0)?number_format((float)$tarif,2,',',$pr):'').'</nobr></td>
				<td align=right style="font-size:0.7em;"><nobr><b>'.(($akol['sump']>0)?number_format($akol['kolp'],0,',',$pr):'').'</b></nobr></td>
				<td align=right><nobr>'.(($akol['sump']>0)?number_format($akol['sump'],2,',',$pr):'').'</nobr></td>
				<td align=right style="font-size:0.7em;"><nobr>'.(($akol['sumd']>0)?number_format($akol['tdist'],2,',',$pr):'').'</nobr></td>
				<td align=right style="font-size:0.7em;"><nobr><b>'.(($akol['sumd']>0)?number_format($akol['kold'],0,',',$pr):'').'</b></nobr></td>
				<td align=right><nobr>'.(($akol['sumd']>0)?number_format($akol['sumd'],2,',',$pr):'').'</nobr></td>';

//				<td'.((isset($akol['nach']) and ($akol['nach']!=$akol['vyd']))?(' align=right><b>'.number_format((0+$akol['nach']),2,',',$pr)):'><b>-').'</td>
				$s.='<td align=right '.((abs($akol['nach']-$akol['sump']-$akol['sumd'])>0.01)?'style="color:red;"':'').'><b><nobr>'.number_format($akol['nach'],2,',',$pr).((abs($akol['nach']-$akol['sump']-$akol['sumd'])>0.01)?(' |'.($akol['nach']-$akol['sump']-$akol['sumd'])):'').'</nobr></b></td>
				</tr>';
				$it+=$akol['cou']; 
				$skol+=$akol['cou']; $skold+=$akol['kold'];  $skolp+=$akol['kolp']; $sumd+=$akol['sumd'];  $sump+=$akol['sump']; $sit+=$akol['nach'];

			}
			if (!isset($noitog) or $noitog==0)
			echo '<tr><td style="color:blue;" colspan="7"> ИТОГО по '.$sp_kms[-$id_kmsasgo].':<b>'.$it.'</b></td></tr>';
		}
	}	
	echo '<td style="text-align:left;" colspan="9"></td></tr>';
	echo '<tr align=center '.(($klas==0)?'style="color:red;font-style:bold;border-top:black solid 4px;"':'').'>';
//		<!--td align=center>'.(++$i).'</td-->
	echo '<td style="text-align:left;" style="background-color:#aaa;"><b>'.$zag[$klas].'</b></td>
		<td align=center><b>'.(($skol==0)?'':number_format($skol,0,',',$pr)).'</b></td>
		<td></td>
		<td align=center><nobr><b>'.(($skolp==0)?'':number_format($skolp,0,',',$pr)).'</b></nobr></td>
		<td align=center><nobr><b>'.(($sump==0)?'':number_format($sump,2,',',$pr)).'</b></nobr></td>
		<td></td>
		<td align=center><nobr><b>'.(($skold==0)?'':number_format($skold,0,',',$pr)).'</b></nobr></td>
		<td align=center><nobr><b>'.(($sumd==0)?'':number_format($sumd,2,',',$pr)).'</b></nobr></td>
		<td align=center><nobr><b>'.(($sit==0)?'':number_format($sit,2,',',$pr)).'</b></nobr></td>
	</tr>';
	echo '<tr align=center '.(($klas==0)?'style="color:red;font-style:bold;"':'').'>';
//		<td align=center>'.(++$i).'</td>
	echo '<td style="text-align:left;" colspan="9">в том числе:</td>
	</tr>';

	echo $s;

}
?>
</table> 
