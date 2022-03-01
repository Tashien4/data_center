<?php use app\models\Site;
use app\models\_uparams;
?>

<h3 style="text-align:center;"><?php echo Site::fromperiod($period,1)?></h3>


<table border=1 width=100% bgcolor=#F0FFF0>
	<tr align=center bgcolor= #dbd8d8>
		<td rowspan=2><b> Наименование учереждения</td>
		<td colspan=3><b> Без документов</td>
		<td colspan=2><b> Кассовый<br/>расход</td>
	</tr><tr align=center bgcolor= #f2f2f2>
		<td ><b> Основные</td>
		<td ><b> Льготные</td>
		<td ><b> СНИЛС </td>
		<td ><b> Школа</td>
		<td ><b> Дет. сад </td>
	</tr>
          
<?php
$cor="#fdfdfd";
$cor2="#FFFF00";
$cor3="#FFD700";
$cor4="#FFFACD";
$cor5="#9ACD32";
$c1="_";
$c2="_";
$p1="_ ";
$p2="_";

$censer=array();
$school_2 = (new \yii\db\Query())   
	->select('id,iscensered')
	->from('_school')
	->All();
	foreach($school_2 as $sc) {                   
                    
	$censer[$sc['id']]=$sc['iscensered'];
}; 
 foreach($model as $id=>$row) {                                              

	
 	if ($row['vse1']=="-") {$cor="#FF00FF";
				$cor2="#FF00FF";
				$cor3="#FF00FF";
				$cor4="#FF00FF";
				$cor5="#FF00FF";}
				else {$cor="#7FFF00";
				      $cor2="#fdfdfd";
				      $cor3="#eaebf5";
				      $cor4="#fffce4";
				      $cor5="#d4ff00";};
        if ($row['pasp2']=="-") {$c1="&nbsp;";} else {$c1=$row['pasp2'];};
        if ($row['lgot2']=="-") {$c2="&nbsp;";} else {$c2=$row['lgot2'];};
      	echo '	<tr align=center>
			<td style="width:30%;color:'.(($censer[$row['id']]>0)?('black;background-color:'.$cor.''):'red;background-color:#ffe6e6').';"><b>'.$row['scool'].'</b> ('.$row['vse1'].')</td>
			<td style="color:red;background-color:'.$cor2.'">'.(($row['pasp']=='-')?'':$row['pasp']).'</td>											
			<td style="color:red;background-color:'.$cor3.'">'.(($row['lgot']=='-')?'':$row['lgot']).(($c2==0)?'':' ('.$c2.')').'</td>
			<td style="color:red;background-color:'.$cor4.'">'.(($row['snils1']=='-')?'':$row['snils1']).'</td>	
	        <td style="color:red;background-color:'.$cor2.'">'.(($row['sump']=='-')?'-':$row['sump']).'</td>
			<td style="color:red;background-color:'.$cor2.'">'.(($row['sumds']=='-')?'-':$row['sumds']).'</td>
		
		</tr>';
}
?>
</table> 




