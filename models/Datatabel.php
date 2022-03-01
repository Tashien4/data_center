<?php

namespace app\models;

use Yii;
use app\models\Site;

class Datatabel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'datatabel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          //  [['id_period', 'id_fio', 'coper', 'id_kmsasgo', 'd1', 'd2', 'd3', 'd4', 'd5', 'd6', 'd7', 'd8', 'd9', 'd10', 'd11', 'd12', 'd13', 'd14', 'd15', 'd16', 'd17', 'd18', 'd19', 'd20', 'd21', 'd22', 'd23', 'd24', 'd25', 'd26', 'd27', 'd28', 'd29', 'd30', 'd31'], 'required'],
            [['id_period', 'id_fio', 'coper'], 'integer'], 
             [['klass'], 'string', 'max' => 5],
            [['id_kmsasgo', 'plus','d1', 'd2', 'd3', 'd4', 'd5', 'd6', 'd7', 'd8', 'd9', 'd10', 'd11', 'd12', 'd13', 'd14', 'd15', 'd16', 'd17', 'd18', 'd19', 'd20', 'd21', 'd22', 'd23', 'd24', 'd25', 'd26', 'd27', 'd28', 'd29', 'd30', 'd31'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_period' => 'Id Period',
            'id_fio' => 'Id Fio',
            'coper' => 'Coper',
            'id_kmsasgo' => 'Id Kmsasgo',
            'd1' => '1',
            'd2' => '2',
            'd3' => '3',
            'd4' => '4',
            'd5' => '5',
            'd6' => '6',
            'd7' => '7',
            'd8' => '8',
            'd9' => '9',
            'd10' => '10',
            'd11' => '11',
            'd12' => '12',
            'd13' => '13',
            'd14' => '14',
            'd15' => '15',
            'd16' => '16',
            'd17' => '17',
            'd18' => '18',
            'd19' => '19',
            'd20' => '20',
            'd21' => '21',
            'd22' => '22',
            'd23' => '23',
            'd24' => '24',
            'd25' => '25',
            'd26' => '26',
            'd27' => '27',
            'd28' => '28',
            'd29' => '29',
            'd30' => '30',
            'd31' => '31',
        ];
    }

    //----------------------------------------------
  public static function kl_param($per=0,$coper=0) {   
	$ret=array();
	if ($per==0) $per=$_SESSION['cur_period'];
    
	$ret['aper']=Site::fromperiod($per);
	$ret['alldays']=cal_days_in_month(CAL_GREGORIAN, $ret['aper']['mes'], $ret['aper']['god']);
	$ret['aprazd']=Datatabel::aprazd($ret['aper']['god'],$ret['aper']['mes']);
	$ret['avyh']=Datatabel::avyh($ret['aper']['god'],$ret['aper']['mes']); //,$subbotavyhodnoy);

	$ret['norm'][5]=$ret['alldays']; 
	$ret['norm'][6]=$ret['alldays']; 
	$_SESSION['mask'][$per][$coper][5]=array();
	$_SESSION['mask'][$per][$coper][6]=array();

	$kolp=count($ret['aprazd']);
//	else {
		for  ($j = 1; $j <=$ret['alldays']; $j++) { 
			$_SESSION['mask'][$per][$coper][5][$j]=1;
			$_SESSION['mask'][$per][$coper][6][$j]=1;
			if (isset($ret['avyh'][5][$j]) and $ret['avyh'][5][$j]>0) {
				$ret['norm'][5]--;
				$_SESSION['mask'][$per][$coper][5][$j]=0;
			} elseif (isset($ret['aprazd'][$j]) and $ret['aprazd'][$j]>0) {
				if ($kolp<2) $ret['norm'][5]--;
				$_SESSION['mask'][$per][$coper][5][$j]=0;
			}
			if (isset($ret['avyh'][6][$j]) and $ret['avyh'][6][$j]>0) {
				$ret['norm'][6]--;
				$_SESSION['mask'][$per][$coper][6][$j]=0;
			} elseif (isset($ret['aprazd'][$j]) and $ret['aprazd'][$j]>0) {
				if ($kolp<2) $ret['norm'][6]--;
				$_SESSION['mask'][$per][$coper][6][$j]=0;
			}
		}

	$_SESSION['norm'][$per][$coper]=$ret['norm'];

	return $ret;

}
//---------------------------------------
//-------------------------------------------------
public static function cou_his($per=0) { // количество строк в садике в этом периоде. Для правильного назначения суммы, не зависящей от посещений
	$ret=array();
	if ($per>0) {
		$connection=Yii::$app->db; 
		$sql=sprintf('	
			SELECT id_fio,count(*) as cou
			FROM expo
			WHERE id_period=%s AND (0+prim<1)  
			GROUP BY id_fio
			HAVING count(*)>1
		',
       	       $per		);
//print $sql;
		$command=Yii::$app->db->createCommand($sql);
		$prows = $command->queryAll();
		foreach($prows as $prow)
               		$ret[$prow['id_fio']]=$prow['cou'];
	}
	return $ret;
}
//-------------------------------------------------
//----------------------------------------------
public static function aprazd($god,$mes=0) {   
    $ret=array();
$command=Yii::$app->db->createCommand("
    select day(data) as id,prazdn
    from vyh
    where month(data)=".$mes." and year(data)=".$god." 
        and prazdn>0
     order by data;
        ");
$prows = $command->queryAll();
       foreach($prows as $prow) {
        $ret[$prow['id']]=$prow['prazdn'];
       }

return $ret;
}
//------------------------------------
// из Expo::controller kl_tabel tabelecheck($dop,$klass)
public static function tabelecheck($scool,$klass,$per=0) {
	if ($per==0) $per=$_SESSION['cur_period'];
        $ret=array();

	if ($scool) {
		$s=(((int)$klass<1)?', dt.plus':''); 
            for($i=1;$i<32;$s.=(', if(isnull(dt.d'.$i.'),0,dt.d'.$i.') as d'.$i),$i++);
		$sql=sprintf("
			select expo.id as expoid,
				concat(ff.fam,' ',ff.im,' ',if(isnull(kmsasgo.krat) or kmsasgo.id=149,'',concat(' (',kmsasgo.krat,')'))) as fio, 
				concat(expo.id_fio,expo.id_kmsasgo) as eid_fio, 
				expo.prim, expo.id_kmsasgo as eid_kmsasgo, 
				expo.fact, if(isnull(dt.id),0,dt.id) as id
				%s

			from expo
			inner join fio ff ON ff.id=expo.id_fio

			left join datatabel dt ON dt.id_period=expo.id_period AND 
                                        dt.id_fio=expo.id_fio AND 
                                        dt.id_kmsasgo=expo.id_kmsasgo AND 
                                        dt.coper=expo.coper AND dt.klass=expo.prim
			left join kmsasgo ON kmsasgo.id=expo.id_kmsasgo
			where expo.id_period=%s AND expo.coper=%s AND expo.prim='%s'
			order by ff.fam,ff.im,ff.ot
		     ;",$s
			,  $per, $scool, /*(((int)$klass<1)?(((int)$klass<-4)?'-5':'-1'):'(0+expo.prim)')
			, $per
			,$scool,*/
			$klass
			
		);
//if(Yii::app()->user->id==1)
//print_r($sql);

//if (Yii::app()->user->id==1) print $sql;
		$command=Yii::$app->db->createCommand($sql); 
		$rows = $command->queryAll();
		foreach($rows as $row) {
//			$ret[]=$row;
			$ret[$row['eid_fio']]=$row;
		}

        }
	return ($ret);
}
//------------------------------------
public static function set_rascet($id_period,$coper,$klass) {
	$ss=''; $skol='';
	for ($i=1;$i<32;$i++) {
        $ss.=((($ss)?' + ':'').sprintf(' dt.d%s',$i,$i)); $skol.=((($skol)?' + ':'').sprintf(' if(dt.d%s>0,1,0) ',$i));
    };
    // в глобальном без учёта школы и кокретной буквы класса
//if($klass>0)
	$sql=sprintf('
		UPDATE expo
		INNER JOIN ( 	SELECT dt.id,dt.id_period,dt.coper,dt.klass,dt.id_fio,dt.id_kmsasgo,(%s) as nach, (%s) as kol, dt.plus  
				FROM datatabel dt
				WHERE dt.id_period=%s AND dt.coper=%s AND dt.klass="%s"
		) dd ON dd.id_kmsasgo=expo.id_kmsasgo and dd.id_period=expo.id_period AND dd.id_fio=expo.id_fio AND dd.coper=expo.coper and dd.klass=expo.prim
		SET expo.fact=dd.kol, expo.nach=(dd.nach+dd.plus), expo.tarif=if(dd.kol=0,0,(dd.nach+dd.plus)/dd.kol)
		WHERE expo.id_period=%s AND expo.coper=%s AND expo.prim="%s";
	',$ss,$skol
	,$id_period,$coper,$klass
	,$id_period,$coper,$klass
	);
/*/else {  $max=$_SESSION['norm'][$id_period][$coper][5];
$sql=sprintf('UPDATE expo
		INNER JOIN (SELECT ddd.id,
					ddd.id_period,
					ddd.coper,
					ddd.klass,
					ddd.id_fio,
					ddd.id_kmsasgo,
					(if (_school.regim=10,if (ddd.klass<-4,hp.t6,hp.t7),if (ddd.klass<-4,hp.t8,hp.t9))/'.$max.'*ddd.kol*kmsasgo.koef_rp+ddd.plus) as nach,
					ddd.kol
				FROM (
					SELECT dt.id, dt.id_period, dt.coper, dt.klass, dt.id_fio, dt.id_kmsasgo,(%s) as kol, dt.plus
                                        FROM datatabel dt
					WHERE dt.id_period =%s AND dt.coper =%s AND dt.klass = "%s"
				) ddd
				INNER JOIN _school on _school.kod=%s 
				INNER JOIN kmsasgo on kmsasgo.id=ddd.id_kmsasgo
				INNER JOIN histarifpasgo hp on (%s between pbegin and pend))
                        dd ON dd.id_kmsasgo=expo.id_kmsasgo and dd.id_period=expo.id_period AND dd.id_fio=expo.id_fio AND dd.coper=expo.coper and dd.klass=expo.prim
		SET expo.fact=dd.kol, expo.nach=dd.nach, expo.vyd=dd.nach, expo.tarif=if(dd.kol=0,0,dd.nach/dd.kol)
		WHERE expo.autor=30 AND expo.id_period=%s AND expo.coper=%s AND expo.prim="%s";

',$skol,$id_period,$coper,$klass
,$coper,$id_period,
$id_period,$coper,$klass);  };*/
//print $sql;
	$ret=Yii::$app->db->createCommand($sql)->execute(); 
return $ret;
}
//-------------------------------------------------

public static function repl_by_coper($per=0,$coper=0) { 
	$ret=0;
       if (($per>0) and ($coper>0)) {
//		$aper=Site::fromperiod($per);
		$sql=sprintf('
			INSERT IGNORE INTO expo (id_period,id_kmsasgo,prim,id_fio,actu_pasp,actu_lgdok,data_resh,rbegin,rend,fact,tarif,nach,coper)
			SELECT %s as id_period,id_kmsasgo,prim,id_fio,actu_pasp,actu_lgdok,data_resh,rbegin,rend,0 as fact,0 as tarif,0 as nach,coper
			from expo
			where expo.id_period=%s 
				and (need_deleted=0 or ((year(rend)-2010)*12+6+month(rend)>=%s) )
			;',
		        	$per,
				$per-1,
				$per
		);
	$connection=Yii::$app->db; 
//print $sql;
	$updatecommand=$connection->createCommand($sql);
	$ret=$updatecommand->execute();

			$sql=sprintf('
				update _change_mon 
				set period=0
				WHERE 1
			;');
			$ret=Yii::$app->db->createCommand($sql)->execute(); 


	 //Datatabel::makeklasstarif($per);
	 
      }
	return ($ret);
}
//---------------------------------------
//------------------------------------
public static function makeklasstarif($per=0) {
    $ret='';
$aper=Site::fromperiod($per);
$alldays=cal_days_in_month(CAL_GREGORIAN, $aper['mes'], $aper['god']);


$connection=Yii::$app->db; 

$anorm=Datatabel::kl_param($per,0);

$sql=sprintf('DELETE FROM tarifklass WHERE id_period=%s;',$per); 
$connection->createCommand($sql)->execute(); 

$sql=sprintf('	INSERT INTO `tarifklass` (id_period,klass,id_kmsasgo,d1)
                SELECT %s as id_period,klass,id_kmsasgo,
        if (tt.inv=1, 
            if(tt.klass<5,hp.t2,hp.t4),
            if(tt.klass<5,
                if(tt.inv=2,hp.t11,hp.t1),
                if(tt.inv=2,hp.t12,hp.t3))
        ) as d1
                FROM histarifpasgo hp
        INNER JOIN (SELECT (0+expo.prim) as klass,id_kmsasgo,is_invalid as inv 
                FROM expo 
                INNER JOIN kmsasgo ON kmsasgo.id=expo.id_kmsasgo 
                WHERE expo.id_period=%s AND kmsasgo.autor=30 and kmsasgo.id_mspa=156 
                GROUP BY (0+expo.prim),id_kmsasgo ) tt ON 1
        WHERE (%s between hp.pbegin and hp.pend) 
        ORDER BY klass,id_kmsasgo
        ',
               $per, $per, $per); 

$connection->createCommand($sql)->execute(); 

$command=$connection->createCommand(sprintf("
    select id,klass,id_kmsasgo,d1
    from `tarifklass`
    where id_period=%s;
        ", $per)
); 
$rows=$command->queryAll();

foreach($rows as $row) {
    $sql=''; 
    for ($i=1;$i<$alldays+1;$i++) {
    /*	if ($per==137) { // ноябрь 2020 
            if (isset($anorm['avyh'][6][$i]) or isset($akanikula[$i])) $tarif=0;
            elseif ($row['kla']<6)   $tarif=$row['d1'];
            elseif ($row['kla']==9)  $tarif=($i<=29)?$row['d2']:$row['d1'];
            elseif ($row['kla']<11)  $tarif=$row['d2'];
            elseif ($row['kla']==11) $tarif=(($i>=9) and ($i<=15))?$row['d2']:$row['d1'];
            else  $tarif=$row['d1'];
        } else { */// в декабре 1-5,9,11 - столовая, остальные дистант
            if (isset($anorm['avyh'][6][$i])) $tarif=0;
        /*	elseif ($row['kla']<6)   $tarif=$row['d1'];
            elseif ($row['kla']==9)  $tarif=$row['d1']; //($i<=29)?$row['d2']:$row['d1'];
            elseif ($row['kla']<11)  $tarif=$row['d2'];
            elseif ($row['kla']==11) $tarif=$row['d1']; //(($i>=9) and ($i<=15))?$row['d2']:$row['d1']; 
        */
            else  $tarif=$row['d1'];
    //	}
        $sql.=((($sql)?',':'').sprintf(' d%s=%s',$i,$tarif)); 
    }	
    $qq=$connection->createCommand(sprintf('UPDATE tarifklass SET %s WHERE id=%s;',$sql,$row['id']))->execute(); 
}

//---------------------------------------------------- Садики --------------------------------------------------
$sql=sprintf('DELETE FROM tarifddu WHERE id_period=%s;',$per); 
$connection->createCommand($sql)->execute(); 
$sql=sprintf('	INSERT INTO `tarifddu` (id_period,coper,klass,id_kmsasgo,d1,plus)
   SELECT %s as id_period,tt.coper,tt.klass,tt.id_kmsasgo,
                if (tt.regim=10,
                    if (tt.klass<-4,hp.t6,hp.t7),
                    if (tt.klass<-4,hp.t8,hp.t9)
                    )*tt.koef_rp/%s	 as d1,
                hp.t10*tt.koef_rp as plus

                FROM histarifpasgo hp
        INNER JOIN ( 
            SELECT coper,_school.regim,if((0+expo.prim)<-4,-5,-1) as klass,id_kmsasgo,koef_rp 
            FROM expo 
            INNER JOIN kmsasgo ON kmsasgo.id=expo.id_kmsasgo 
            INNER JOIN _school on _school.kod=expo.coper
            WHERE expo.id_period=%s AND expo.prim<1 AND kmsasgo.autor=30 and kmsasgo.koef_rp>0 GROUP BY coper,if((0+expo.prim)<-4,-5,-1),id_kmsasgo 
        ) tt ON 1
        WHERE (%s between hp.pbegin and hp.pend) 
        ORDER BY coper,klass,id_kmsasgo

        '
    ,$per,((false)?$anorm['norm'][5]:20),$per,$per
);
//print_r($anorm);
//print $sql;

$connection->createCommand($sql)->execute(); 

$command=$connection->createCommand(sprintf("
    select id,klass,id_kmsasgo,d1,plus
    from `tarifddu`
    where id_period=%s;
        ", $per)
); 
$rows=$command->queryAll();
//print_r($avyh);
foreach($rows as $row) {
    $sql='plus='.$row['plus']; 
    for ($i=1;$i<$alldays+1;$i++) {

       // $tarif=((isset($_SESSION['mask'][$per][0][5][$i]))?$_SESSION['mask'][$per][0][5][$i]:0)*$row['d1'];
        if (isset($anorm['avyh'][5][$i]))$tarif=0; else  $tarif=$row['d1'];
        
//print '<br/>'.((isset($_SESSION['mask'][$per][0][5][$i]))?$_SESSION['mask'][$per][0][5][$i]:'---').' | '.$row['d1'];
/*			if (isset($anorm['avyh'][6][$i]) or isset($akanikula[$i])) $tarif=0;
        elseif ($row['kla']<6)   $tarif=$row['d1'];
        elseif ($row['kla']==9)  $tarif=($i<=29)?$row['d2']:$row['d1'];
        elseif ($row['kla']<11)  $tarif=$row['d2'];
        elseif ($row['kla']==11) $tarif=(($i>=9) and ($i<=15))?$row['d2']:$row['d1'];
        else  $tarif=$row['d1'];
*/
        $sql.=sprintf(' ,d%s=%s',$i,$tarif); 
    }	
//print '<br/>'.sprintf('UPDATE tarifddu SET %s WHERE id=%s;',$sql,$row['id']);
    $qq=$connection->createCommand(sprintf('UPDATE tarifddu SET %s WHERE id=%s;',$sql,$row['id']))->execute(); 
}


//print $sql;


return $ret;
}
//------------------------------------
//------------------------------------
public static function sp_bykla($type,$school,$klass,$per=0) {
	if ($per==0) $per=$_SESSION['cur_period'];
    $rus_per=Site::fromperiod($per,0);
    $alldays=cal_days_in_month(CAL_GREGORIAN, $rus_per['mes'], $rus_per['god']);
    $ret=array();
    $anorm=Datatabel::kl_param($per,0);
                   
         
        if($type==0) {
            //выводим список всех классов их expo
            $all_kl=Yii::$app->db->createCommand('
            SELECT (0+expo.prim) as klass,id_kmsasgo,is_invalid as inv 
            FROM expo 
            INNER JOIN kmsasgo ON kmsasgo.id=expo.id_kmsasgo 
            WHERE expo.id_period='.$per.' AND kmsasgo.autor=30 and kmsasgo.id_mspa=156 
            GROUP BY (0+expo.prim),id_kmsasgo ')->queryAll();

            //выводим строки  дистанта и текущего тарифа
            $sql='select max(data) as ddat,histarif.* from histarif group by dist';
            $command=Yii::$app->db->createCommand($sql); 
            $rows = $command->queryAll();
            foreach($rows as $row)
                $tarif[$row['dist']]=$row['id'];

            foreach($all_kl as $all)
                {
              
                    for($i=1;$i<($alldays+1);$i++) {
                        $sql='SELECT if(for_class like "%'.$all['klass'].'%",1,0) as itis 
                        FROM `histarif` 
                        WHERE "'.$rus_per['god'].'-'.$rus_per['mes'].'-'.$i.'">=data and if(end!=0,"'.$rus_per['god'].'-'.$rus_per['mes'].'-'.$i.'"<=end,1) and dist=1';
                        $command=Yii::$app->db->createCommand($sql); 
                        $rows = $command->queryOne();
//echo $i.'-'.$all['klass'].'-'.$rows['itis'].'-'.((isset($rows['itis']) and $rows['itis']>0)?$tarif[1]:$tarif[0]).'<br>';
                            $sql='select tt.id_kmsasgo,tt.klass,
                                if(tt.inv=1,
                                    if(tt.klass<5,ht.t2,ht.t4),
                                    if(tt.klass<5,
                                        if(tt.inv=2,ht.t11,ht.t1),
                                        if(tt.inv=2,ht.t12,ht.t3))
                                ) as d
                                from histarif ht
                                inner join (SELECT (0+expo.prim) as klass,id_kmsasgo,is_invalid as inv 
                                FROM expo 
                                INNER JOIN kmsasgo ON kmsasgo.id=expo.id_kmsasgo 
                                WHERE expo.id_period='.$per.' AND kmsasgo.autor=30 and kmsasgo.id_mspa=156 
                                GROUP BY (0+expo.prim),id_kmsasgo ) tt ON 1
                                Where tt.klass='.$all['klass'].' and ht.id='.((isset($rows['itis']) and $rows['itis']>0)?$tarif[1]:$tarif[0]);
                                $command=Yii::$app->db->createCommand($sql); 
//print_r($sql);
                                $rows = $command->queryAll();
                                foreach($rows as $row) 
                                    $ret[$row['id_kmsasgo']][$all['klass']][$i]=$row['d'];

                    };
                }
           /*for($i=1;$i<($alldays+1);$i++) {
            $sql='select tt.id_kmsasgo,tt.klass,if(tt.inv=1,
            if(tt.klass<5,ht.t2,ht.t4),
            if(tt.klass<5,
                if(tt.inv=2,ht.t11,ht.t1),
                if(tt.inv=2,ht.t12,ht.t3))
            ) as d
            from histarif ht
             inner join (SELECT (0+expo.prim) as klass,id_kmsasgo,is_invalid as inv 
            FROM expo 
            INNER JOIN kmsasgo ON kmsasgo.id=expo.id_kmsasgo 
            WHERE expo.id_period='.$per.' AND kmsasgo.autor=30 and kmsasgo.id_mspa=156 
            GROUP BY (0+expo.prim),id_kmsasgo ) tt ON 1
            where "'.$rus_per['god'].'-'.$rus_per['mes'].'-'.$i.'" >= ht.data and (if(ht.end!=0,"'.$rus_per['god'].'-'.$rus_per['mes'].'-'.$i.'"<=ht.end,1))
            group by tt.id_kmsasgo,tt.klass';
            $command=Yii::$app->db->createCommand($sql); 
            print_r($sql);
            $rows = $command->queryAll();
            foreach($rows as $row) 
                $ret[$row['id_kmsasgo']][$row['klass']][$i]=$row['d'];
            
            }*/
//	print_r($ret);
    } else {
        $all_kl=Yii::$app->db->createCommand('
        SELECT coper,_school.regim,if((0+expo.prim)<-4,-5,-1) as klass,id_kmsasgo,koef_rp 
        FROM expo 
        INNER JOIN kmsasgo ON kmsasgo.id=expo.id_kmsasgo 
        INNER JOIN _school on _school.kod=expo.coper
        WHERE expo.id_period='.$per.' AND expo.prim<1 AND kmsasgo.autor=30 and kmsasgo.koef_rp>0 GROUP BY coper,if((0+expo.prim)<-4,-5,-1),id_kmsasgo 
        ')->queryAll();

//выводим строки  дистанта и текущего тарифа
        $sql='select max(data) as ddat,histarif.* from histarif group by dist';
        $command=Yii::$app->db->createCommand($sql); 
        $rows = $command->queryAll();
        foreach($rows as $row)
            $tarif[$row['dist']]=$row['id'];

    foreach($all_kl as $all)
        {
  
        for($i=1;$i<($alldays+1);$i++) {
            $sql='SELECT if(for_class like "%'.$all['klass'].'%",1,0) as itis 
            FROM `histarif` 
            WHERE "'.$rus_per['god'].'-'.$rus_per['mes'].'-'.$i.'">=data and if(end!=0,"'.$rus_per['god'].'-'.$rus_per['mes'].'-'.$i.'"<=end,1) and dist=1';
            $command=Yii::$app->db->createCommand($sql); 
            $rows = $command->queryOne();

            $sql='select tt.id_kmsasgo,tt.klass,if (tt.regim=10,
            if (tt.klass<-4,ht.t6,ht.t7),
            if (tt.klass<-4,ht.t8,ht.t9)
            )*tt.koef_rp/'.((false)?$anorm['norm'][5]:20).'	 as d,
            ht.t10*tt.koef_rp as plus
            from histarif ht
             inner join (SELECT coper,_school.regim,if((0+expo.prim)<-4,-5,-1) as klass,id_kmsasgo,koef_rp 
             FROM expo 
             INNER JOIN kmsasgo ON kmsasgo.id=expo.id_kmsasgo 
             INNER JOIN _school on _school.kod=expo.coper
             WHERE expo.id_period='.$per.' AND expo.prim<1 AND kmsasgo.autor=30 and kmsasgo.koef_rp>0 GROUP BY coper,if((0+expo.prim)<-4,-5,-1),id_kmsasgo 
            ) tt ON 1
            where tt.klass='.$all['klass'].' and ht.id='.((isset($rows['itis']) and $rows['itis']>0)?$tarif[1]:$tarif[0]);
            $command=Yii::$app->db->createCommand($sql); 
            
            $rows = $command->queryAll();
            foreach($rows as $row) {
                $ret[$row['id_kmsasgo']][$row['klass']][$i]=$row['d'];
                $ret[$row['id_kmsasgo']]['plus'][0]=$row['plus'];
            }
        }
        } 
    };

	return ($ret);
}
//------------------------------------
//----------------------------------------------
public static function avyh($god,$mes=0) {//,$subbotavyhodnoy=0) {   
    $ret=array();
$command=Yii::$app->db->createCommand("
    select weekday('".$god."-".$mes."-01') as id;
        ");
$prow = $command->queryOne();
       for($i=(7-$prow['id']);$i<33;$i+=7) {
            $ret[5][$i]=1;
//		if (($subbotavyhodnoy>0) and $i>1) {
    if ($i>1) {
                $ret[5][$i-1]=1;
    }
            $ret[6][$i]=1;
       }
$all=cal_days_in_month(CAL_GREGORIAN, $mes, $god);
for ($i=$all+1; $i<33;$i++) {
            if(isset($ret[5][$i])) unset($ret[5][$i]);
            if(isset($ret[6][$i])) unset($ret[6][$i]);
}
//print_r($ret);
return $ret;


}
//----------------------------------------------
}
