<?php

namespace app\models;


use Yii;
use yii\data\ActiveDataProvider;

class Expo extends \yii\db\ActiveRecord
{

    public $no_lgot=0;
	public $no_dok=0;
	public $no_snils=0;
    public $no_kms=0;
    public $need_change=0;
    public $id_kmsasgo_dop;
    public $kmsasgo_name;
    public $rend_dop;
    public $klass;
    public $liter;
    public $_msp;
    public $fio;    public $snils;
    public $pech_tabel;
    public $only_lgot;
    public $egisso_role;
    public $fionamedop;
    public $snilsdop;
    public $sp_liter=array(
		'  '=>'',
		'А'=>'А',
		'Б'=>'Б',
		'В'=>'В',
		'Г'=>'Г',
		'Д'=>'Д',
		'Е'=>'E',
		'З'=>'З',
		'И'=>'И',
		'К'=>'К',
		'П'=>'П',
		'С'=>'С',

	);
    public $sp_klass=array(
		'-5'=>'Ясельная группа',
		'-4'=>'Младшая группа',
		'-3'=>'Средняя группа',
		'-2'=>'Старшая группа',
		'-1'=>'Подготовительная гр.',
		'0'=>'',
		'1'=>'1 класс',
		'2'=>'2 класс',
		'3'=>'3 класс',
		'4'=>'4 класс',
		'5'=>'5 класс',
		'6'=>'6 класс',
		'7'=>'7 класс',
		'8'=>'8 класс',
		'9'=>'9 класс',
		'10'=>'10 класс',
		'11'=>'11 класс',
	);
    public $cssnosnils='border:4px;border-color:red;border-style:solid;red;';
	public $cssnopasp='background-color:#fce;';
	public $cssnolgt='background-color:#fce;';
	public $afact=array();
    
    public static function tableName()
    {
        return 'expo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['id_period', 'in_period', 'id_kmsasgo', 'prim', 'snils', 'id_fio', 'actu_dok', 'actu_lgdok', 'data_resh', 'rbegin', 'rend', 'fact', 'tarif', 'nach', 'vyd', 'coper', 'id_fiodop', 'need_deleted'], 'required'],
            [['id_period', 'in_period', 'id_kmsasgo', 
             'id_fio', 'actu_pasp', 'actu_lgdok', 'fact', 'coper', 'need_deleted',
            ], 'integer'],
            [['data_resh', 'rbegin', 'rend'], 'safe'],
            [['snils','tarif', 'nach'], 'number'],
            [['prim','fio','kmsasgo_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            '_msp'=>'Мера социальной поддержки',
            'id_period' => 'Id Period',
            'in_period' => 'In Period',
            'id_kmsasgo' => 'Категория',
            'prim' => 'Класс',
            'klass' => 'Класс',
            'liter' => 'Литера',
            'snils' => 'СНИЛС',
            'id_fio' => 'ФИО',
            'actu_dok' => 'Actu Dok',
            'actu_lgdok' => 'Actu Lgdok',
            'data_resh' => 'Data Resh',
            'rbegin' => 'Rbegin',
            'rend' => 'Rend',
            'fact' => 'Посещения',
            'tarif' => 'Тариф',
            'nach' => 'Сумма',
            'kmsasgo_name'=>'Категория',
            'fio'=>'Получатель',
            'coper' => 'Coper',
            'need_deleted' => 'Выбыл',
            'no_snils'=>'Нет СНИЛС',
            'no_kms'=>'Нет категории',
            'no_dok'=>' Без св-ва о рождении (паспорта)',
            'no_lgot'=>' Без документов о льготе',
            'need_change'=>'Надо разделить?'
            
        ];
    }
     //---------------------------------------------------
     //---------------------------------------------------------------------
 public static function statist4($per=0,$coper,$only) {
    $ret=array();
          //only=1 -только школа
    if($per>136) {
        $rus_per=Site::fromperiod($per,0);
        $alldays=cal_days_in_month(CAL_GREGORIAN, $rus_per['mes'], $rus_per['god']);
           $scool=array();
            $sql=sprintf('SELECT kod,name as scool FROM _school WHERE type=1;'); //,$sd,$per,$coper);
               $command=Yii::$app->db->createCommand($sql);
               $rows = $command->queryAll();
   //print $sql;    
               foreach($rows as $row) {
                   $ret[-$row['kod']]['krat']=$row['scool'];
               }
   
   //		$kms=array();
            $sql=sprintf('SELECT coper,id_kmsasgo,kmsasgo.kat 
                       FROM expo 
                       INNER JOIN kmsasgo ON kmsasgo.id=expo.id_kmsasgo 
                       WHERE expo.id_period=%s AND kmsasgo.autor=30 and kmsasgo.id_mspa=156 
                       GROUP BY coper,id_kmsasgo;',$per);
               $command=Yii::$app->db->createCommand($sql);
               $rows = $command->queryAll();
   //print $sql;    
               foreach($rows as $row) {
                   $ret[-$row['coper']][-$row['id_kmsasgo']]=$row['kat'];
               }
   
   //	$sd='sum(d1) as d1';	for ($i=2;$i<=31;$i++) 	$sd.=',sum(d'.$i.') as d'.$i;	
   
           $sql=sprintf('	
                   SELECT kla, tpasgo, tdist, dt.*,expo.fact,expo.nach,expo.id as eid
                   FROM  `datatabel` dt
                   INNER JOIN expo ON expo.id_kmsasgo = dt.id_kmsasgo
                           AND expo.id_period = dt.id_period
                           AND expo.id_fio = dt.id_fio
                           AND expo.coper = dt.coper
                           AND expo.prim = dt.klass
   
                   LEFT JOIN ( 
                       SELECT %s as id_period,kla,id_kmsasgo,
                             if (tt.inv=1, 
                                   if(tt.kla<5,hp.t2,hp.t4),
                               if(tt.kla<5,
                                   if(tt.inv=2,hp.t11,hp.t1),
                                   if(tt.inv=2,hp.t12,hp.t3))
                             ) as tpasgo,
                             if (tt.inv=1, 
                             if(tt.kla<5,distant.t2,distant.t4),
                             if(tt.kla<5,
                                 if(tt.inv=2,distant.t11,distant.t1),
                                 if(tt.inv=2,distant.t12,distant.t3))
                           ) as tdist
                                     FROM histarif hp
                             INNER JOIN (SELECT (0+expo.prim) as kla,id_kmsasgo,is_invalid as inv FROM expo INNER JOIN kmsasgo ON kmsasgo.id=expo.id_kmsasgo WHERE expo.id_period=%s AND kmsasgo.autor=30 and kmsasgo.id_mspa=156 GROUP BY (0+expo.prim),id_kmsasgo ) tt ON 1
                             left join (select max(data) as ddat,histarif.id from histarif where dist=1 group by dist) as dm on 1
                                left join (select max(data) as ddat,histarif.id from histarif where dist=0 group by dist) as pm on 1
                             left JOIN histarif distant ON dm.id=distant.id
                             WHERE   pm.id=hp.id) tarifset ON tarifset.id_period=dt.id_period AND tarifset.id_kmsasgo=dt.id_kmsasgo AND tarifset.kla=(0+dt.klass)
                   WHERE dt.id_period=%s AND dt.klass>0 %s
                   ORDER BY dt.coper,dt.klass
               ;',$per,$per,$per,(($only==1)?' and dt.coper='.$coper:''));

       $command=Yii::$app->db->createCommand($sql);
       $rows = $command->queryAll();
 //print $sql;    	
       foreach($rows as $row) {
           $kla=($row['kla']<5)?1:5;
           $kolp=0; $kold=0; $sump=0; $sumd=0;
   
           if (!(isset($ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]))) 
               $ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]=array('kold'=>0, 'sumd'=>0, 'kolp'=>0, 'sump'=>0, 'tdist'=>$row['tdist'], 'days'=>0, 'nach'=>0, 'cou'=>0);
   
   
           for ($i=1;$i<=31;$i++) {
               if(($s=$row['d'.$i])>0) {
                   if ($s==$row['tdist']) {
                       $kold++; $sumd+=$s;
                   } else {
                       $kolp++; $sump+=$s;
                   }
               }
           }
           $ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]['cou']++;
           $ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]['kolp']+=$kolp;
           $ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]['kold']+=$kold;
           $ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]['sump']+=$sump;
           $ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]['sumd']+=$sumd;
           $ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]['nach']+=$row['nach'];
   
           $ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]['days']+=$row['fact'];
		  
       }
   //if (Yii::app()->user->id==1) {		print $ret[$row['coper']][$kla][$row['id_kmsasgo']][$row['tpasgo']]['nach'].' |'.$row['nach'].'<br/>';    	}	
   
   
      }
       return ($ret);
   }
   

   //---------------------------------------------------------------------
   public static function find_all_tarif() {
    $sql='select * from histarif where "'.date("Y-m-d").'">histarif.data and if(end=0,1,"'.date("Y-m-d").'"<=histarif.end)';
    //print_r($sql);
    $command=Yii::$app->db->createCommand($sql);
	$rows = $command->queryAll();
    return $rows;
   }

     //-------------------------------------------------
  public static function has_double($liveonly=0,$id_period=0,$coper=0) {   

	$ret='';
	$sql=sprintf("

		SELECT expo.id_fio,concat(' (',fio.rod,') ',fio.fam,' ',fio.im,' ',fio.ot) as fio, fio.snils, group_concat(expo.coper) as coper
		FROM expo 
		INNER JOIN fio ON fio.id=expo.id_fio
		inner join kmsasgo on kmsasgo.id=expo.id_kmsasgo
        inner join msp_asgo on kmsasgo.id_mspa=msp_asgo.id
		WHERE id_period =%s
			AND (msp_asgo.mspkod=758 or msp_asgo.mspkod=771)
			AND expo.need_deleted=0
			%s %s
		GROUP BY id_fio
		HAVING count(*)>1

        ",(($id_period==0)?$_SESSION['cur_period']:$id_period)
        ,(($liveonly==0)?'':' AND expo.fact >0')
        ,(($coper==0)?'':(' AND coper='.$coper))
	);
//print $sql;
	$command=Yii::$app->db->createCommand($sql);
	$rows = $command->queryAll();
        $ret=array();
	foreach($rows as $row) {
		$ret[]='Школы '.$row['coper'].' с одним и тем же учеником: '.$row['fio'].'-'.$row['snils'].' ('.$row['id_fio'].')<br>';
	}  if(count($ret)>1) $ret[].=' <b>Поставьте дату окончания, если был перевод в другую школу</b>';
	return ($ret);
}

//-------------------------------------------------
//------------------------------------------
public function getEfio()
{
    return $this->hasOne(Fio::className(), ['id' => 'id_fio']);
}
//------------------------------------------

//------------------------------------------
public function getKmsasgo()
{
    return $this->hasOne(Kmsasgo::className(), ['id' => 'id_kmsasgo']);
}
//------------------------------------------
//------------------------------------------
public function getKmsasgo_name()
{
    return $this->hasOne(Kmsasgo::className(), ['id' => 'id_kmsasgo']);
}
//------------------------------------------
//------------------------------------------
public function getPaspdoks()
{
    return $this->hasOne(Doks::className(), ['id' => 'actu_pasp']);
}
//------------------------------------------
//------------------------------------------
public function getLgdoks()
{
    return $this->hasOne(Doks::className(), ['id' => 'actu_lgdok']);
}
//------------------------------------------
 public function find_user() {
    $resStr='';
$rows = Yii::$app->db->createCommand('
            select concat(oo.name,"-",users.username) as value,concat(oo.name,"-",users.username) as label
            from users
            left join _school oo on oo.id=users.id_org 
            where 1 
')->queryAll();

return $rows;
}
//-------------------------------------------------//-----------------------------------------------
public static function statist($per=0) {
	$ret=array();

	if ($per>0) {
		$aper=Site::fromperiod($per);
		$connection=Yii::$app->db; 
		$sql=sprintf('	
			SELECT _school.name as scool, _school.kod ,  _school.id ,
				if(isnull(vse.col),"-",vse.col) AS vse1, 
				if(isnull(pas.col2),"-",pas.col2) AS pasp,
				if(isnull(bp.kol4),"-",bp.kol4) AS pasp2,
				if(isnull(lg.col3),"-",lg.col3) AS lgot,
				if(isnull(nulev.kol3),"-",nulev.kol3) AS lgot2,
				if(isnull(nosnils.col4),"-",nosnils.col4) AS snils1,
				if((sum.sum=0),"-",sum.sum) AS sum,
				if((sum.sump=0),"-",sum.sump) AS sump,
				if((sum.sumds=0),"-",sum.sumds) AS sumds
			FROM _school
			LEFT JOIN (
		
				SELECT coper, COUNT( expo.id ) AS col
				FROM expo
				WHERE expo.id_period =%s 
				GROUP BY coper
			) AS vse ON vse.coper = _school.kod
			LEFT JOIN (

				SELECT expo.coper, COUNT( expo.id ) AS col2
				FROM expo
				left JOIN doks ON doks.id = expo.actu_pasp
				WHERE expo.id_period =%s
					AND (expo.actu_pasp =0 or doks.doks_dat =0 or doks.doks_kem="" )
					
				GROUP BY expo.coper
			) AS pas ON pas.coper =_school.kod 
			LEFT JOIN ( SELECT expo.coper, COUNT( expo.id ) AS col3
				FROM expo
				LEFT JOIN doks ON doks.id = expo.actu_lgdok
				WHERE expo.id_period =%s
					AND (expo.actu_lgdok =0 OR 
					doks.doks_dat = 0)
					AND expo.id_kmsasgo !=149
					AND expo.id_kmsasgo !=155
					AND expo.id_kmsasgo !=128
				GROUP BY expo.coper
			) AS lg ON lg.coper = _school.kod
			LEFT JOIN (	
				SELECT expo.coper, COUNT( expo.id ) AS kol3
				FROM expo
				LEFT JOIN doks ON doks.id = expo.actu_lgdok
				WHERE expo.id_period =%s
					AND expo.id_kmsasgo !=149
					AND expo.id_kmsasgo !=155
					AND expo.id_kmsasgo !=128
					AND expo.actu_lgdok =0
				GROUP BY expo.coper
			) AS nulev ON nulev.coper = _school.kod
			LEFT JOIN (	
				SELECT expo.coper, COUNT( expo.id ) AS kol4
				FROM expo
				LEFT JOIN doks ON doks.id = expo.actu_pasp
				WHERE expo.id_period =%s
					AND expo.actu_pasp =0
				GROUP BY expo.coper
			) AS bp ON bp.coper = _school.kod
			LEFT JOIN (

				SELECT expo.coper, COUNT( expo.id ) AS col4
				FROM expo
                inner join fio on fio.id=expo.id_fio
				WHERE expo.id_period =%s
					AND fio.snils <9000000
					GROUP BY coper
			) AS nosnils ON nosnils.coper = _school.kod
			Left join (
				SELECT coper,sum(nach) as sum,sum(vyd) as sump, sum(ds) as sumds
				FROM (
					SELECT expo.coper,if(prim<=0,0,expo.nach) as vyd,if(prim<=0,0,expo.nach) as nach,if(prim<=0,expo.nach,0) as ds
					from expo
					where expo.id_period=%s 
				) ss
				group by coper	
			) as sum on sum.coper=_school.kod
			
			WHERE _school.id>1000  
			GROUP BY _school.kod
			',
        	       $per, $per, $per, $per, $per, $per, $per, $aper['god'], $aper['god']
		);

		$command=Yii::$app->db->createCommand($sql);
		$prows = $command->queryAll();
	        foreach($prows as $prow) {
                	$ret[$prow['kod']]=$prow;
        	}
	}
	return $ret;
}
//------------------------------------
//---------------------------------------
public function sp_kod($cond='') {   
	$prows=Yii::$app->db->createCommand("
		select distinct _mspkod.id, concat(substring(_mspkod.name,1,75),'...') as name
		from kmsasgo t
        inner join msp_asgo on msp_asgo.id=t.id_mspa
		inner join  _mspkod ON _mspkod.id=msp_asgo.mspkod
		where _mspkod.id in (758,771)
		order by _mspkod.name;")->queryAll();
//print $sql;
	$row[0]=" -----";    
        foreach($prows as $prow) {
                 $row[$prow['id']]="".$prow['id']."- ".$prow['name'];
        }
	return ($row);
}
//---------------------------------------
//---------------------------------------
public function CalcCtrlSum($n, $m) {
   
    $a = $n . $m;
    //разбиваем строку $a на массив $b;
    $b = str_split($a);
    $c = '';
    //проверяем элементы массива, если цифра, добавляем ее в переменную $c;
    foreach($b as $value) {
        if (ctype_digit($value)) {
            $c .= $value;
          }
      }
    //проверяем количество цифр в СНИЛС, если все правильно, считаем контрольную сумму;
print strlen($c);
    if (strlen($c) == 11) {
        $snils = substr("$c", 0, 9);
        $ctrlinput = substr("$c", 9, 2);
        //задаем тип переменной $snils как integer чтобы убрать ведущие нули если таковые 
        //имеются иначе сумма будет посчитана неправильно;
        settype($snils, "integer");
        $ctrlsum = 0;
        $ki = 1;
        for ($i = 9; $i > 0; $i--) {
            $ni = $snils % 10;
            $snils = (int)($snils / 10);
            $ctrlsum = $ctrlsum + $ni * $ki;
            $ki++;
          }
        $ctrlsum = $ctrlsum % 101;
        //если полученная сумма больше 99, возвращаем ошибку;
        if ($ctrlsum >= 100) {
            $result['err']['code'] = 2;
            $result['err']['desc'] = "Сумма (" . $ctrlsum . ") больше 99";
            return $result;
            $ctrlsum = 0;
          }
        //если рассчетная сумма и веденная пользователем совпадают, возвращаем массив содержащий СНИЛС,
        //рассчетную и введенную пользователем контрольную сумму и код ошибки равный 0;
        if ((int)$ctrlsum == (int)$ctrlinput) {
             //проверяем есть ли такой СНИЛС у другого 
            $prows=Yii::$app->db->createCommand("
            select id from fio where snils=".$n)->queryOne();
 
            if(isset($prows['id']) and $prows['id']>0) { 
                $result['err']['code'] = 5;
                $result['err']['desc'] = "Данный СНИЛС привязан к другому получателю";
                return $result;
            } else {

            $result['snils'] = (int)substr("$c", 0, 9);
            $result['ctrlsum'] = (int)$ctrlsum;
            $result['ctrlinput'] = (int)$ctrlinput;
            $result['err']['code'] = 0;
            $result['err']['desc'] = "Ошибок не обнаружено";
            return $result;}
          }
        //если рассчетная сумма и введенная в форме не совпали, возвращаем ошибку;
        else {
            $result['err']['code'] = 3;
            $result['err']['desc'] = "Контрольные суммы не совпадают, проверьте правильность ввода данных.<br>";
            $result['err']['desc'] .= "Рассчетная сумма: " . (int)$ctrlsum . ", введенная: " . (int)$ctrlinput;
            return $result;
          }
      }
    //если после "чистки" полученных данных получилось число неравное 11 возвращаем ошибку;
    else {
        $result['err']['code'] = 1;
        $result['err']['desc'] = "Номер СНИЛС должен состоять из 9 цифр, либо не указана контрольная сумма";
        return $result;
      };
      
     
  }
//---------------------------------------
//-------------------------------------------------
public function prov_to_snils($snils,$id_fio) {

	$retsnils=$this->CalcCtrlSum($snils,"");
    
	if($retsnils['err']['code']>0)
    $ans='<div class="overlay_1"></div><div class="modal_1"><div ><h2>&#8195;Ошибка</h2></div>
               <span class="close">X</span><br>
               <div style="padding:10px 20px;">'.$retsnils['err']['desc'].'
        </div>
       </div>';
   
else {
   
    
$sql='update fio 
   set snils="'.$snils.'" 
   where snils<9000000 and id='.$id_fio;
		   $updatecommand=Yii::$app->db->createCommand($sql);
$row=$updatecommand->execute();

$ans='';
//запросы разедлила специально, для более быстрой обработки
/*$sql='update fio 
   set snils="'.$snils.'" 
   where snils<9000000 and id='.$id_fio;
		   $updatecommand=Yii::$app->db->createCommand($sql);
$row=$updatecommand->execute();*/
	};
    print_r($ans);
    return($ans);
//    return($row);
}


//-----------------------------------------------
//---------------------------------------
public static function list_kat_dop($kms=0) {   
	$prows=Yii::$app->db->createCommand("
		select distinct t.id, concat(substring(t.kat,1,75),'...') as name
		from kmsasgo t
        inner join msp_asgo on msp_asgo.id=t.id_mspa 
        inner join (select kmsasgo.id,msp_asgo.mspkod 
                        from kmsasgo 
                        inner join msp_asgo on msp_asgo.id=kmsasgo.id_mspa 
                        where kmsasgo.id=".$kms.") as jj on jj.mspkod=msp_asgo.mspkod
		")->queryAll();
//print $sql;
	$row[0]=" -----";    
        foreach($prows as $prow) {
                 $row[$prow['id']]="".$prow['id']."- ".$prow['name'];
        }
	return ($row);
}//---------------------------------------
//---------------------------------------
public function sp_kod_all($msp=0) {   
	$prows=Yii::$app->db->createCommand("
		select distinct t.id, concat(substring(t.kat,1,75),'...') as name
		from kmsasgo t
        inner join msp_asgo on msp_asgo.id=t.id_mspa 
		inner join  _mspkod ON _mspkod.id=msp_asgo.mspkod
		where ".($msp=0?"_mspkod.id in (758,771)":"_mspkod.id=".$msp)."
		order by _mspkod.name;")->queryAll();
//print $sql;
	$row[0]=" -----";    
        foreach($prows as $prow) {
                 $row[$prow['id']]="".$prow['id']."- ".$prow['name'];
        }
	return ($row);
}//---------------------------------------
  public function sp_katbykod($kat=0) {   

	$row=array();
	$prows=Yii::$app->db->createCommand("
		select kk.id, kk.kat as name
		from kmsasgo kk
		inner join kmsasgo cur on cur.id_mspa=kk.id_mspa
		where (0+cur.id=".$kat.")
		order by kk.id;
        ")->queryAll();
    
        foreach($prows as $prow) {
                 $row[$prow['id']]="".$prow['id']."- ".$prow['name'];

        }

	return ($row);
}
//----------------------------------------------------------
public function search($params,$upd)
    {
        $query = Expo::find()->joinWith(['efio'])->joinWith(['kmsasgo'])->joinWith(['kmsasgo_name'])->joinWith(['paspdoks'])->joinWith(['lgdoks']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // 'sort'=> ['defaultOrder' => [['efio']['fam'] => SORT_ASC]]
        ]);
      //  print_R($query);
        $per=$_SESSION['cur_period'];  
        if($upd['role']==1) $dop=($upd['cur_sc']==0?'':'and expo.coper='.$upd['school']);
        else $dop='and expo.coper='.$upd['school'];
        $dataProvider->query->where('expo.id_period='.$per.' '.$dop);
        
        //если выбрана отметка "Нет СНИЛС", то выбираются все получатели без СНИЛСа
        if(isset($params['Expo']['no_snils']) and $params['Expo']['no_snils']>0) 
                $dataProvider->query->andwhere('fio.snils<90000000');

      //если выбрана отметка "Без св-ва о рождении (паспорта)", то выбираются все получатели без выбранного основного документа
        if(isset($params['Expo']['no_dok']) and $params['Expo']['no_dok']>0) 
                $dataProvider->query->andwhere('expo.actu_pasp=0');

        if(isset($params['Expo']['no_lgot']) and $params['Expo']['no_lgot']>0) 
                $dataProvider->query->andwhere('expo.actu_lgdok=0 and expo.id_kmsasgo not in (149,155,128)');
        
        if(isset($params['Expo']['no_kms']) and $params['Expo']['no_kms']>0) 
                $dataProvider->query->andwhere('expo.id_kmsasgo=0');

       

        $dataProvider->sort->attributes['fio'] = [
            'asc' => ['fio.fam' => SORT_ASC],
            'desc' => ['fio.fam' => SORT_DESC],
        ]; 
         $dataProvider->sort->attributes['snils'] = [
            'asc' => ['fio.snils' => SORT_ASC],
            'desc' => ['fio.snils' => SORT_DESC],
        ];
       /* $dataProvider->sort->attributes['kmsasgo_name'] = [
            'asc' => ['kmsasgo.kat' => SORT_ASC],
            'desc' => ['kmsasgo.kat' => SORT_DESC],
        ];*/
     /*   $dataProvider->sort->defaultOrder=['efio.fam' => SORT_ASC];*/
        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'fio.fam', $this->fio])
            //  ->andFilterWhere(['like', 'kmsasgo.kat', $this->kmsasgo_name])
              ->andFilterWhere(['like', 'fio.snils', $this->snils])
              ->andFilterWhere(['like', 'expo.id_kmsasgo', $this->id_kmsasgo])
              ->andFilterWhere(['like', 'expo.fact', $this->fact])
              ->andFilterWhere(['like', 'expo.nach', $this->nach])
              ->andFilterWhere(['like', 'expo.prim', $this->prim]);
         //     ->andFilterWhere(['like', 'type_name.name', $this->type_name]);

        return $dataProvider;
    }
//------------------------------------
//-------------------------------------------------
   public static function deletefortabel($id_period,$coper,$klass) {
    	        //$date=date("Y-m-d",strtotime($data)); //echo $date;  /*
            	$sql=sprintf('select dt.id
			from datatabel dt
			left join expo on expo.id_fio=dt.id_fio
					 and expo.id_kmsasgo=dt.id_kmsasgo
					 and expo.prim=dt.klass and
					expo.coper=dt.coper
			where expo.id_fio is null and dt.id_period=%s and dt.coper=%s and dt.klass="%s" ',
			$id_period,$coper,$klass);
        	$command=Yii::$app->db->createCommand($sql);
		$prows = $command->queryAll();
		$sqls='';
		foreach($prows as $prow) {
                	$sqls.="delete from datatabel where id=".$prow['id'].";";
		}

		if($sqls!='') {
               	$connection=Yii::$app->db; 
					$updatecommand=$connection->createCommand($sqls);
					$ret=$updatecommand->execute(); }   
				

}
//-------------------------------------------------
public function getklass($school=0,$vybrat_this_klass_name=0) {
	$ret='';

	if ($school>0) {

		$sql=sprintf('	
			SELECT expo.prim,count(*) as cou
			FROM expo
			WHERE expo.coper=%s AND id_period=%s
			GROUP BY prim
			ORDER BY (0+prim),prim
			',
        	       $school
			,$_SESSION['cur_period']
		);

		$command=Yii::$app->db->createCommand($sql);
		$rows = $command->queryAll();

	    $apr=array('0'=>'');

		foreach($rows as $row) {
			$apr[]=$row['prim']; //.'  ('.$row['cou'].')';
		}
		if ($vybrat_this_klass_name>0) 
			$ret=$apr[$vybrat_this_klass_name];
		else
			$ret=$apr;

	} else $ret=array('0'=>'');
	return ($ret);
}
//---------------------------------------
//------------------------------------
public function family($id) {
    $ret='<select name="family" id="family">';
        
         $sql=sprintf('SELECT fio.id, CONCAT( fam, " ", im, " ", ot, " (", rod, ")" ) AS fio
            FROM fio
            INNER JOIN (

            SELECT id, id_bls
            FROM fio
            WHERE id =%s
            AND id_bls !=0
            ) AS j ON j.id_bls = fio.id_bls
            WHERE fio.rod < "2000-01-01"',$id
        ); 
    $command=Yii::$app->db->createCommand($sql);
$prows = $command->queryAll();
    foreach($prows as $prow) {
    $ret.='<option value="'.$prow['id'].'">'.$prow['fio'].'</option>';

};  
       $ret.='</select>';
//print $sql;
      return $ret; 



}
//-------------------------------------------------
//-------------------------------------------------
public function sp_lgot($per=0,$scool=0) {
	$ret=array();
//	$ret['0']='Отсутствует';
//print $per;
	if ($per>0) {
		$sql=sprintf('	
			SELECT DISTINCT	expo.id_kmsasgo,
				if(isnull(kmsasgo.kat),"-",kmsasgo.kat) as name
			FROM expo
			LEFT JOIN kmsasgo ON kmsasgo.id=expo.id_kmsasgo
			WHERE expo.id_period=%s AND expo.coper=%s
			ORDER BY id_kmsasgo
			',
        	       $per, $scool
		);

		$command=Yii::$app->db->createCommand($sql);
		$prows = $command->queryAll();
	        foreach($prows as $prow) {
                	$ret[$prow['id_kmsasgo']]=$prow['name'];
        	};
         //   $ret['0']='Отсутствует';
	}
	return $ret;
}
//-------------------------------------------------
//------------------------------------
public function dok830($expoid=0,$current=0) {
    $ret=array('0'=>'---');
//if ($expoid>0) {
    $sql=sprintf("
        select doks.*, concat(freb.fam,' ',freb.im,' ',freb.ot,' ',date_format(freb.rod,'%s')) as name
        from expo
        inner join fio freb ON freb.id=expo.id_fio

        inner join doks ON doks.id_fio=freb.id
        where expo.id=%s AND (doks.lgtype=830 or (doks.id_fio=expo.id_fio and doks.doktype not in (21,901,12)))
         ;",'%d.%m.%Y',$expoid
    );
    $command=Yii::$app->db->createCommand($sql); 
    $rows = $command->queryAll();
    foreach($rows as $row) {
        $ret[$row['id']]=$row['doks_nom'].' от '.Site::rusdat($row['doks_dat']).' '.$row['name'];
    }
//Print_r($sql);
//   }
/*
if ($current>0) {
    $sql=sprintf("
        select doks.*, concat(ff.fam,' ',ff.im,' ',ff.ot,' ',date_format(ff.rod,'%s')) as name
        from doks
        inner join fio ff ON ff.id=doks.id_fio
        where doks.id=%s
         ;",'%d.%m.%Y',$current
    );
    $command=Yii::$app->db->createCommand($sql); 
    $rows = $command->queryAll();
    foreach($rows as $row) {
        $ret[$row['id']]=$row['doks_nom'].' от '.Site::rusdat($row['doks_dat']).' '.$row['name'];
    }

    }**/
return ($ret);
}
//---------------------------------------
//-------------------------------------------------
     //---------------------------------------------------
     public function fio_auto() {
//создаем запрос к таблице с информацией о получателях с выбором ФИО и даты рождения.
//выбираем только тех, получателей, кто младше 19 лет
    $rows = Yii::$app->db->createCommand("
    select concat(ff.fam,' ',ff.im,' ',ff.ot,' ',DATE_FORMAT(ff.rod,'%d.%m.%Y')) as value,
            concat(ff.fam,' ',ff.im,' ',ff.ot,' ',DATE_FORMAT(ff.rod,'%d.%m.%Y')) as label
	from fio ff
	where (datediff(now(),ff.rod)<19*366)
    order by concat(ff.fam,' ',ff.im,' ',ff.ot);
    ")->queryAll();
    //полученный результат передаем в виджет
    return $rows;
    }
    //-------------------------------------------------
     //---------------------------------------------------
     public function fio_auto_dop() {
        $resStr='';
    $rows = Yii::$app->db->createCommand("
    select concat(ff.fam,' ',ff.im,' ',ff.ot,' ',DATE_FORMAT(ff.rod,'%d.%m.%Y')) as value,concat(ff.fam,' ',ff.im,' ',ff.ot,' ',DATE_FORMAT(ff.rod,'%d.%m.%Y')) as label
				from fio ff
				where (datediff(now(),ff.rod)>17*366)
				order by concat(ff.fam,' ',ff.im,' ',ff.ot);
    ")->queryAll();

    /*foreach($rows as $prow) {
        $resStr[$prow['id']] = ($prow['name'].' '.Site::rusdat($prow["rod"])."\n");
    }*/
    
    return $rows;
    }
    //-------------------------------------------------
//-------------------------------------------------
public function sp_hispasgo($id,$scool=0) {
	$ret=array();
	if ($id>0) {
		$sql="SELECT expo.id,expo.prim,expo.id_period,expo.in_period,expo.id_kmsasgo,expo.tarif,sum(expo.fact) as fact,sum(expo.nach) as nach
		      from expo
		      inner join expo ee on ee.id_fio=expo.id_fio and ee.coper=expo.coper and ee.id=".$id."  
		      where 1
                      GROUP BY expo.id_period,expo.id_kmsasgo
		";


		$command=Yii::$app->db->createCommand($sql);
		$prows = $command->queryAll();
	        foreach($prows as $prow) {
                	$ret[$prow['id_period']][$prow['id_kmsasgo']]=$prow;
        	}
	}
	return $ret;
}
//-------------------------------------------------
//-------------------------------------------------
public function getkratkmsasgo($id_kmsasgo=0) {   

	$ret='';
	$sql="
		select id,krat as name
		from kmsasgo
		where ".(($id_kmsasgo==0)?"1":("id=".$id_kmsasgo))."
		order by id;
        ";
	//print_R($sql);
	$command=Yii::$app->db->createCommand($sql);
	$prows = $command->queryOne();
//	$row[0]=" ---не выбран ---";    

	if (isset($prows['name'])) 
    $ret=$prows['name'];
	return ($ret);
}
//-------------------------------------------------
//------------------------------------
public static function mspbykat($kat=0) {   
	$ret=0;
//	if ($tab>0) {
		$sql=sprintf("
			select k.id_mspa,msp_asgo.mspkod
			from kmsasgo as k
            inner join msp_asgo on msp_asgo.id=k.id_mspa 
			where k.id=%s
			limit 1;
	        ",$kat);
//echo $sql;
	$command=Yii::$app->db->createCommand($sql);
	$prow = $command->queryOne();
        if (isset($prow['id_mspa'])) $ret=$prow['mspkod'];

	return ($ret);
}
//------------------------------------
//-------------------------------------------------

  public function new_lgot($id_fio,$lg,$kms) {   
	if($lg=='804') $sql=sprintf('
		INSERT INTO doks (id_fio,doktype,lgtype,id_kem,doks_kem,doks_kem_kod) 
				VALUES (%s,913,804,933,"Территориальная психолого-медико-педагогическая комиссия","ГПМПК");',
	    $id_fio); 
        elseif($lg=='830') $sql=sprintf('
		INSERT INTO doks (id_fio,doktype,lgtype,id_kem,doks_kem,doks_kem_kod) 
				VALUES (%s,910,830,912,"Управление социальной политики в г.Серове","УСП г.Серова");',
	    $id_fio); 
        elseif($lg=='84') $sql=sprintf('
		INSERT INTO doks (id_fio,doktype,lgtype,id_kem,doks_kem,doks_kem_kod) 
				VALUES (%s,911,84,912,"Управление социальной политики в г.Серове","УСП г.Серова");',
	    $id_fio); 
        else $sql=sprintf('
		INSERT INTO doks (id_fio,doktype,lgtype,id_kem,doks_kem,doks_kem_kod) 
				VALUES (%s,912,803,912,"Управление социальной политики в г.Серове","УСП г.Серова");',
	    $id_fio); 
	$updatecommand=Yii::$app->db->createCommand($sql);
	$rowCount=$updatecommand->execute();

        $sqls=sprintf('select id from doks where lgtype in (804,803,84,830) and id_fio =%s order by id  DESC limit 1',$id_fio);
         	$command=Yii::$app->db->createCommand($sqls);
		$rows = $command->queryOne();
		$idd=$rows['id'];

         $sq=sprintf('update expo 
			set actu_lgdok=if(actu_lgdok=0,'.$idd.',actu_lgdok) 
			where id_fio='.$id_fio.' and id_kmsasgo='.$kms);
          
            $updatecommand=Yii::$app->db->createCommand($sq)->execute();
return $idd;
 
}
//-------------------------------------------------
//---------------------------------------
public function separate_me($id,$data_ch,$kms) {
    $ret='';
    // подключаемся к базе данных
    $connection=Yii::$app->db; 
    //переводим полученную дату в формат для базы данных
    $data_c=substr($data_ch, 4, 4).'-'.substr($data_ch, 2, 2).'-'.substr($data_ch, 0, 2);
    //формируем дату, меньше даты изменения на 1 день для указания даты окончания действия прошлого факта МСП
    $data_b=substr($data_ch, 4, 4).'-'.substr($data_ch, 2, 2).'-'.((int)substr($data_ch, 0, 2)-1);

    //всавляем запись о факте МСП с новой категорией
    $sql='insert into expo( id_period, snils, id_kmsasgo,prim, id_fio, actu_pasp,  data_resh,rbegin,coper)
  SELECT id_period, snils, '.$kms.',prim, id_fio, actu_pasp, data_resh,"'.$data_c.'",coper
  FROM expo
  WHERE id='.$id;
    $updatecommand=$connection->createCommand($sql);
$rowCount=$updatecommand->execute(); 
//запоминаем идентификатор последней введенной записи
$ret = Yii::$app->db->getLastInsertID();

//указываем дату окончания действия факта МСП с прошлой категорией
$sql='update expo set rend="'.$data_b.'",need_deleted=1 where id='.$id;
    $updatecommand=$connection->createCommand($sql);
$rowCount=$updatecommand->execute(); 
return $ret;
}
//----------------------------------------

//-------------------------------------------------
  public function new_lgotf($id_fio,$id_fiorod,$lg) {   
	if($lg=='830') $sql=sprintf('
		INSERT INTO doks (id_fio,doktype,lgtype,id_kem,doks_kem,doks_kem_kod,coper) 
				VALUES (%s,910,830,912,"Управление социальной политики в г.Серове","УСП г.Серова",-1);',
	    $id_fiorod);
	$updatecommand=Yii::$app->db->createCommand($sql);
	$rowCount=$updatecommand->execute();

        $sqls=sprintf('select id from doks where lgtype =830 and id_fio=%s order by id  DESC',$id_fiorod);
         	$command=Yii::$app->db->createCommand($sqls);
		$rows = $command->queryOne();
		$idd=$rows['id'];

         $sqll=sprintf('update expo 
			set actu_lgdok=if(actu_lgdok=0,%s,actu_lgdok) 
			where id_fio=%s and id_kmsasgo =%s ',$idd,$id_fio,$lg);
	$updatecommand=Yii::$app->db->createCommand($sqll);
    print_r($sqll);
	$rowCount=$updatecommand->execute();
    return $idd;

 
}
//-------------------------------------------------

//-------------------------------------------------
public function get_full_kmsasgo($id_kmsasgo=0) {   

	$ret='';
	$sql="
		select id,kat as name
		from kmsasgo
		where ".(($id_kmsasgo==0)?"1":("id=".$id_kmsasgo))."
		order by id;
        ";
	
	$command=Yii::$app->db->createCommand($sql);
	$prows = $command->queryOne();
//	$row[0]=" ---не выбран ---";    

	if (isset($prows['name'])) 
    $ret=$prows['name'];
	return ($ret);
}
//-------------------------------------------------
//------------------------------------
public function findbyautoresult($famplusrod) {
    $ret=null;
if ($famplusrod) {
    $rows = Yii::$app->db->createCommand("
        select ff.id as id
        from fio ff 
        where concat(ff.fam,' ',ff.im,' ',ff.ot,' ',date_format(ff.rod,'%d.%m.%Y')) like '%".$famplusrod."%'
        limit 1
         ;"
    )->queryOne();
    $ret=$rows['id'];

    }
return ($ret);
}
//---------------------------------------
//---------------------------------------
public function find_pasp($id){
	$ret=array('0'=>'');
        $sql=sprintf("
			select doks.*,_doktypes.name,if(doks.end>0,'Не действителен','Действителен') as status
			from  fio freb 
			inner join doks ON doks.id_fio=freb.id
			inner join _doktypes on doktype=_doktypes.id
			where freb.id=%s AND doks.doktype in (21,901,12)
		     ;",$id
		);
		$command=Yii::$app->db->createCommand($sql); 
		$rows = $command->queryAll();
		foreach($rows as $row) {
			$ret[$row['id']]=$row['name'].': '.$row['doks_ser'].' №'.$row['doks_nom'].' от '.Site::rusdat($row['doks_dat']).' ('.$row['status'].')';
		}

	return ($ret);	


}
//-------------------------------------------------
}
