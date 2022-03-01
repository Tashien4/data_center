<?php

namespace app\models;
use DOMDocument;

use Yii;
class Site extends \yii\db\ActiveRecord
{
//----------------------------------------------------------
//	public function russnils($dat) { 

//          return ($dat)?(substr($dat,0,3)."-".substr($dat,3,3)."-".substr($dat,6,3)." ".substr($dat,9,2)):"";
//        }



	public static function rusdat($dat,$withtime=0) { 
	$time='';
		if ($withtime>0) {
			$time=substr($dat,11,8);
			if ($time) $time=' ('.$time.')';
			else $time='';
		}
		
          return ($dat>0)?(substr($dat,8,2).".".substr($dat,5,2).".".substr($dat,0,4).$time):"";

        }
//-----------------------------------------------------
//-----------------------------------
public static function get_filesize($dir,$file) {
    if (file_exists($dir)) {
    	if(!file_exists($dir.'/'.$file)) $ret="Файл  не найден";
	else {
		$filesize = filesize($dir.'/'.$file);

		if($filesize > 1024){
			$filesize = ($filesize/1024);
			if($filesize > 1024){
				$filesize = ($filesize/1024);
				if($filesize > 1024) {
					$filesize = ($filesize/1024);
					$filesize = round($filesize, 1);
					$ret=$filesize." ГБ";       
				} else {
					$filesize = round($filesize, 1);
					$ret=$filesize." MБ";   
				}       
			} else {
    				$filesize = round($filesize, 1);
				$ret=$filesize." Кб";   
			}  
		} else {
			$filesize = round($filesize, 1);
			$ret=$filesize." байт";   
		}
	}
    } else $ret='---';
    return $ret;
}
//---------------------------------------Применение:

//-------------------------------------------------
public static function CreaDom($encoding='UTF-8') {
	$dom= new domdocument();
	$dom->preserveWhiteSpace=false; //подавлять незначащие пробелы
	$dom->substituteEntities=true;      //произвести подстановки
	$dom->formatOutput=true; 
	$dom->encoding=$encoding; //'UTF-8'; 
//	$root=$dom->createElement($root);
	return $dom;
}
//-------------------------------------------------
   public static function SaveDom($dom,$filename="reestr",$outdir='') {
	if(isset($dom)) {
//		$this->dom->save('templates/'.$filename.'.xml');
//		print $_SESSION['save_path'].'/'.$filename.'.xml'.'<br/>';
		$dom->formatOutput=true;
    		$dom->save($outdir.'/'.$filename); //.'.xml');
//  		@$dom->save($filename.'.xml');
//  		$dom->save($filename); //.'.xml');
	}
	return $dom;
}
//---------------------------------------------------------
   public static function clearDir($dir,$mask='') {
//print $dir.'<br/>';
    if (file_exists($dir)) {
//print $mask.'<br/>';
        foreach (glob($dir.'/'.$mask) as $file) {
//print $file.'<br/>';
            unlink($file);
        }
    }
}
//-----------------------------------------------------------
//---------------------------------------
   public function ansdecode($s) {
	return (iconv('UTF-8','Windows-1251',$s));

}
//---------------------------------------

/*	public function rusdat($dat) { 

          return ($dat!="0000-00-00")?(substr($dat,8,2).".".substr($dat,5,2).".".substr($dat,0,4)." ".substr($dat,10)):"---";

        }
*/
	public static function ntocmonth($m) { 

		switch($m) {
		    case  "1" : $ret="Январь";break;
		    case  "2" : $ret="Февраль";break;
		    case  "3" : $ret="Март";break;
		    case  "4" : $ret="Апрель";break;
		    case  "5" : $ret="Май";break;
		    case  "6" : $ret="Июнь";break;
		    case  "7" : $ret="Июль";break;
		    case  "8" : $ret="Август";break;
		    case  "9" : $ret="Сентябрь";    break;
		    case  "10" : $ret="Октябрь";break;
		    case  "11" : $ret="Ноябрь";break;
		    case  "12" : $ret="Декабрь";break;
		    default:$ret='';
		}
		return $ret;
	}
	public function ntocmonthrod($m) { 

		switch($m) {
		    case  "1" : $ret="января";break;
		    case  "2" : $ret="февраля";break;
		    case  "3" : $ret="марта";break;
		    case  "4" : $ret="апреля";break;
		    case  "5" : $ret="мая";break;
		    case  "6" : $ret="июня";break;
		    case  "7" : $ret="июля";break;
		    case  "8" : $ret="августа";break;
		    case  "9" : $ret="сентября";    break;
		    case  "10" : $ret="октября";break;
		    case  "11" : $ret="ноября";break;
		    case  "12" : $ret="декабря";break;
		    default:$ret='';
		}
		return $ret;
	}
//------------------------------------------
	public static  function rusdnned($m) { 

		switch($m) {
		    case  "1" : $ret="Пн";break;
		    case  "2" : $ret="Вт";break;
		    case  "3" : $ret="Ср";break;
		    case  "4" : $ret="Чт";break;
		    case  "5" : $ret="Пт";break;
		    case  "6" : $ret="Сб";break;
		    case  "7" : $ret="Вс";break;
		    default:$ret='';
		}
		return $ret;
	}
//***************************************
//***************************************
function summa_pro($num) {

	$snum='|'.sprintf('% 15.2f',$num);
	$pro=' ';
	$s=' ';
	$j=4;

	if ($num==0) { $ret=' Ноль pублей ноль копеек'; }
	else {
		$pos= strpos($snum,'.');
		$cel= '  '+($pos==0)?$snum:substr($snum,0,$pos);
		while ((substr($snum,1,3))==='   ') {
			$j--;
			$snum='|'.substr($snum,4);
		}

		for ($i=3*$j; $i>=3;  $i=$i-3) {
			$pro='|'.substr($cel,-$i,3);
			if (substr($pro,1,3)!=0) {
//				$s.=($pro.'+'.$i.'++');
            			$s.=(Site::str3($pro,($i==6)?0:1));
            			switch ($i) {
               				case 12:  $s.=(' '.Site::sklon('миллиаpд',1,$pro)); break;
					case 9:   $s.=(' '.Site::sklon('миллион',1,$pro)); break;
					case 6:   $s.=(' '.Site::sklon('тысяч',0,$pro));
            			} 
         		}
		}
      		$tt= substr($pro,1);
		$i= (int) ($tt%10);

//print '6==='.$tt.'=='.$i.'='.($tt%100).'='.$s.'<br>';
		if     (($j==0) && ($i==0)) { $s.=' ноль pублей'; }
		elseif ($i==0) { $s.=' pублей'; }
      		elseif ($i==1) { $s.=((($tt%100)>10)&&(($tt%100)<20))?' pублей':' pубль';}
      		elseif ($i<5) { $s.=(((($tt%100)>10)&&(($tt%100)<20))?' pублей':' pубля'); }
      		else { $s.=' pублей' ;}
//$ret=('7====='.$s.'<br>');
		$pro=substr($snum,-2,2);

      		if ($pro=='00') {$s=$s.' ноль'; }
      		else { $s.=(' '.$pro); }

      		$i= (int) ($pro%10);
      		if     ($i==0) { 
			$s.=' копеек'; 
//		} elseif ($i==2) { 
//			$s.=' копейки'; 
		} elseif ($i==1) { 
			$s.=(((($pro%100)>10)&&(($pro%100)<20))?' копеек':' копейкa'); 
		} elseif ($i<5)  { 
			$s.=(((($pro%100)>10)&&(($pro%100)<20))?' копеек':' копейки'); 
		} else { 
			$s.=' копеек' ;
		}
	}
	$ret=trim($s);

 	return($ret);
// 	return(upper(left($s,1))+substr($s,2));
//  	return ucfirst($ret);
}

//***************************************
function str3($str,$rod) {
     $ot=''; $prom=''; $sot='';

  //--------обработка сотен-----------------
  if (strlen(''.$str)>3) {
     $prom= '|'.substr($str,1,1);
     switch ($prom) {
        case '|0': break;
        case '| ': break;
        case '|1': $ot=$ot.' сто'; break;
        case '|2': $ot=$ot.' двести'; break;
        default :  $sot=((0+substr($prom,1,1)) < 5)?'ста':'cот'; 
                  $ot.=Site::name_number($prom,$rod).$sot;
     }
     $str= '|'.substr($str,2);
  }

  	$prom= '|'.substr($str,1,1);
	$str = '|'.substr($str,2,1);
	if (($prom=='|0')|| ($prom=='| ')) { 
		$ot.=Site::name_number($str,$rod); // только единицы
	}  elseif ($prom=='|1') {
	        switch ($str) {
			case '|0': $ot.=' десять'; break;
			case '|2': $ot.=(Site::name_number($str,0).'надцать'); break;
			case '|3': $ot.=(Site::name_number($str,0).'надцать'); break;
//			default  : $ot.=(substr(Site::name_number($str,1),0,strlen(Site::name_number($str,1))-1).'надцать');
			default  : $ot.=(substr(Site::name_number($str,1),0,strlen(Site::name_number($str,1))).'надцать');
		}
	} else { 
		$tt=substr($prom,1);
		$ot=$ot.(($tt==4)?' соpок': 
                        (($tt==9)?' девяносто':
                        (($tt<4) ? Site::name_number($prom,1).'дцать':
                        (($tt<9) ? Site::name_number($prom,0).'десят':''))));
                        $ot.=Site::name_number($str,$rod);
  	}
return $ot;
}
//*****************************************
function name_number($number,$rod) {

/* возвpащает словом название цифpы number
 * в женском pоде, если rod=0
 * в мужском pоде в пpотивном случае
 */
 switch ($number) { 
   case '|0': $ret=''; break;
   case '|1': $ret=($rod==0)?' одна':' один';break;
   case '|2': $ret=($rod==0)?' две':' два';break;
   case '|3': $ret=' тpи'; break;
   case '|4': $ret=' четыpе'; break;
   case '|5': $ret=' пять'; break;
   case '|6': $ret=' шесть'; break;
   case '|7': $ret=' семь'; break;
   case '|8': $ret=' восемь'; break;
   case '|9': $ret=' девять'; break;
   default: $ret=''; break;
  }
return $ret;
}
//********************************************
function sklon($s,$rod,$num) {
	$ret='';
	$tt=substr($num,1);
	$n = (int) $tt%10;
	if ((($tt%100)>9)&&(($tt%100)<=20)) { 
		$ret.=($s.(($rod==1)?'ов':'')); 
	} elseif ($n==1) { 
		$ret.=($s.(($rod==1)?'':'а')); 
	} elseif ((($n<5)&&($n>0))) {
		$ret.=($s.(($rod==1)?'а':'и'));
	} else { 
		$ret.=($s.(($rod==1)?'ов':'')); 
	}

  return $ret;
}
//-------------------------------------------------------
	public function beforemonth() { 
		   if ($_SESSION['cur_mes'] == 1) {
		      $_SESSION['cur_mes'] = 12;
		      $_SESSION['cur_god'] = $_SESSION['cur_god'] - 1;
		   }
		   else $_SESSION['cur_mes'] = $_SESSION['cur_mes'] - 1;
		   
		   Yii::app()->params['cur_god']=$_SESSION['cur_god'];
		   Yii::app()->params['cur_mes']=$_SESSION['cur_mes'];
		return $_SESSION['cur_mes'];
	}

	public function nextmonth() { 
		   if ($_SESSION['cur_mes'] == 12) {
		      $_SESSION['cur_mes'] = 1;
		      $_SESSION['cur_god'] = $_SESSION['cur_god'] + 1;
		   }
		   else $_SESSION['cur_mes'] = $_SESSION['cur_mes'] + 1;

		   Yii::app()->params['cur_god']=$_SESSION['cur_god'];
		   Yii::app()->params['cur_mes']=$_SESSION['cur_mes'];
   
		return $_SESSION['cur_mes'];

        }	
//--------------------------------------------------------------
        public function listotdel() {   

		$command=Yii::app()->db->createCommand();
		$command->select('kod,fullname');
		$command->from('otdels');
//		$command->where(array('and','tab=:tab','type_e=:ot' ), array(':tab'=>$_tab,':ot'=>"ОТ")); 
		$command->order('pp,fullname');

	$rows = $command->queryAll();
	$listotdel=array(); 
	foreach($rows as $row) {
		$listotdel[$row['kod']]=$row['fullname'];
	}
	$listotdel[999]='--Все отделы--';
	return ($listotdel);
}

//--------------------------------------------------------------
        public function listmesaz() {   

	for ($i=0,$listmesaz=array();$i++,$listmesaz[$i]=Site::ntocmonth($i); $i<13);
	$listmesaz[0]='--Не выбран';
	$listmesaz[999]='--Все месяцы--';
	return ($listmesaz);
}
//----------------------------------------------------------------
	public function poln_let($regn) {
			$sql=sprintf("
			SELECT round((datediff(
				now(),
				reestr.rod)+1)/365.25-0.499) as let
			FROM reestr 
			WHERE (reestr.regnom='%s')
			;",
			$regn);
			$command=Yii::app()->db->createCommand($sql); 
			$row = $command->queryOne();
			$let=$row['let'];
	return $let;
}
/*
select 	fio.fio,from_days(mday.mdays-30) as let,
	mday.mdays
from   (select  stag.tab,
		sum(datediff(if(isnull(stag.end_e),now(),stag.end_e),stag.begin_e)) as mdays
	from stag
	where stag.muns=1
	group by tab) 
		as mday left join fio on fio.tab=mday.tab
where mday.mdays>0
order by let
*/

//-----------------------------------------------------------
	public function protokol($obj,$key_id,$newvalue,$bef=array()) {
		foreach($newvalue as $key=>$value) {
			$oldvalue=(!isset($bef[$key]))?'':$bef[$key];
			if (($key!='lastedit') and ($oldvalue!=$value)) {
				$log=new Log_edit;
//print $obj.'  = = > '.$key.'  = = > '.$oldvalue.'  = = > '.$value.'<br/>';
//				$this->create_time=$this->update_time=time();

				$log->obj=$obj;
				$log->field=$key;
				$log->key_id=$key_id;
				$log->oldvalue=$oldvalue;
				$log->newvalue=$value;
				$log->coper=Yii::app()->user->id;
				if(!$log->save()) ;
			} 
		}
	
	}

//-----------------------------------------------------
	public function rusvrem($dat,$withtime=0) { 
	$time='';
		if ($withtime>0) {
			$time=substr($dat,11,8);
			if ($time) $time=' ('.$time.')';
			else $time='';
		}
		
          return ($dat>0)?(substr($dat,11,8)):"";

        }
//-------------------------------------------------------
public static function russnils($dat) {
	 
	$sim=array ("-"," ");
	$dates=str_replace($sim, "", $dat);
          return ($dates)?(substr($dates,0,3)."-".substr($dates,3,3)."-".substr($dates,6,3)." ".substr($dates,9,2)):"";
        }

//-------------------------------------------------------

public function statis_sv() {

$sql='SELECT da.id,da.name_for_smen_choice
,if(pp2-ss2=0,"",pp2-ss2) as sv2
,if(pp2>0,round(ss2/pp2*100,3),"") as pr2
,if(pp3-ss3=0,"",pp3-ss3) as sv3
,if(pp3>0,round(ss3/pp3*100,3),"") as pr3
,if(pp4-ss4=0,"",pp4-ss4) as sv4
,if(pp4>0,round(ss4/pp4*100,3),"") as pr4
,if(pp5-ss5=0,"",pp5-ss5) as sv5
,if(pp5>0,round(ss5/pp5*100,3),"") as pr5
,if(pp6-ss6=0,"",pp6-ss6) as sv6
,if(pp6>0,round(ss6/pp6*100,3),"") as pr6
,if(pp7-ss7=0,"",pp7-ss7) as sv7
,if(pp7>0,round(ss7/pp7*100,3),"") as pr7

FROM (
	select lager	, if(sum(p1)>0,sum(p1),"") as pp1, if(sum(p1)>0,sum(s1),"") as ss1
			, if(sum(p2)>0,0+sum(p2),"") as pp2, if(sum(p2)>0,0+sum(s2),"") as ss2
			, if(sum(p3)>0,0+sum(p3),"") as pp3, if(sum(p3)>0,0+sum(s3),"") as ss3
			, if(sum(p4)>0,0+sum(p4),"") as pp4, if(sum(p4)>0,0+sum(s4),"") as ss4
			, if(sum(p5)>0,0+sum(p5),"") as pp5, if(sum(p5)>0,0+sum(s5),"") as ss5
			, if(sum(p6)>0,0+sum(p6),"") as pp6, if(sum(p6)>0,0+sum(s6),"") as ss6
			, if(sum(p7)>0,0+sum(p7),"") as pp7, if(sum(p7)>0,0+sum(s7),"") as ss7
	from (

		select sm.lager	
			,if(!isnull(ts.smena) and ts.smena=1,ts.cou,0) as s1
			,if(sm.smena=1,sm.plan,0) as p1
			,if(!isnull(ts.smena) and ts.smena=2,ts.cou,0) as s2
			,if(sm.smena=2,sm.plan,0) as p2
			,if(!isnull(ts.smena) and ts.smena=3,ts.cou,0) as s3
			,if(sm.smena=3,sm.plan,0) as p3
			,if(!isnull(ts.smena) and ts.smena=4,ts.cou,0) as s4
			,if(sm.smena=4,sm.plan,0) as p4
			,if(!isnull(ts.smena) and ts.smena=5,ts.cou,0) as s5
			,if(sm.smena=5,sm.plan,0) as p5
			,if(!isnull(ts.smena) and ts.smena=6,ts.cou,0) as s6
			,if(sm.smena=6,sm.plan,0) as p6
			,if(!isnull(ts.smena) and ts.smena=7,ts.cou,0) as s7
			,if(sm.smena=7,sm.plan,0) as p7
		from (
			select zz.lager,zz.smena,count(*) as cou
			from dokasgo.zakaz zz
			INNER JOIN dokasgo.reestr rr ON rr.regnom=zz.regnom
			where zz.status>=0 AND rr.okoosn=""
			group by zz.lager,zz.smena
		) ts 
		right join dokasgo.sp_smen sm on sm.lager=ts.lager and sm.smena=ts.smena
		where sm.plan>0	
	) tot
	group by lager

) poop 
INNER JOIN dokasgo.dislall da ON da.id=poop.lager
ORDER by da.id;
';
		$command=Yii::app()->db->createCommand($sql);
		$ret = $command->queryAll();
	return ($ret);

}
//-------------------------------------------------
        public function lastdate() {   
		$ret='';

	$sql='SELECT max(lastdate) as ld FROM zakaz;';
	$command=Yii::app()->db->createCommand($sql);
	$row = $command->queryOne();
	if ($row['ld']) $ret=Site::rusdat($row['ld']);
	return ($ret);
}
//-------------------------------------------------
        public function rustel($tel='') {   

		$ret='';
	$atel=preg_split("/[\s,]+/", trim($tel)); //explode($tel);
//print strlen($atel[0]);
	if ((count($atel)<1) or strlen($atel[0])<5) $ret=trim($tel);
	else {
		foreach($atel as $ct) {
			if(strlen($ret)!=0) $ret.=', ';

			if(strlen($ct)<6) $ret.=substr($ct,0,1).'-'.substr($ct,1,2).'-'.substr($ct,3);
			elseif(strlen($ct)<11) $ret.=substr($ct,0,3).'-'.substr($ct,3,3).'-'.substr($ct,6);
			else $ret.=substr($ct,0,1).'-'.substr($ct,1,3).'-'.substr($ct,4,3).'-'.substr($ct,7);
		}
	}
	return ($ret);
}
//-----------------------------------------------------------
	public static function toperiod($god=0,$mes=0) {
		$god=(($god==0)?$_SESSION['cur_god']:$god);			
		$mes=(($mes==0)?$_SESSION['cur_mes']:$mes);			
		return (6+($god-2010)*12+$mes);
}
//-----------------------------------------------------------
	public function datetoperiod($pdate="0000-00-00"){ 
		$ret=0;
		if ($pdate!="0000-00-00") {
			$god=substr($pdate,0,4);
			$mes=substr($pdate,5,2);
			$ret=(6+($god-2010)*12+$mes);
			if (!isset($_SESSION['cur_god'])) {
				$_SESSION['cur_god']=$god;
				$_SESSION['cur_mes']=$mes;			
			}
		}
		return $ret;
}
//-----------------------------------------------------------
	public static function fromperiod($period,$char=0) {
		$mes=($period-6)%12;			
		$god=($period-6-$mes)/12+2010;	
		if ($mes==0) { 
			$mes=12; 
			$god--; 
		}

		if ($char==0)	$ret=array('god'=>$god,'mes'=>$mes);
		else {if ($char==3) {$ret=$god.' год.';}
			else $ret=Site::ntocmonth($mes).' '.$god.' г.';}
	
	return $ret;
}
//-----------------------------------------------------------
	public function egisso($mspkod,$mspkat,$mspuro,$mspisto,$mspform,$msprubr) { 
		$ret='';
		if ($mspkod>0) {
			$ret.=(substr(10000+$mspkod,1));
			$ret.=$mspkat; //substr($dat,6,2)):"";
			$ret.=(substr(100+$mspuro,1));
			$ret.=(substr(10000+$mspisto,1));
			$ret.=(substr(100+$mspform,1));		
//			$ret.=$msprubr;
			$ret.=(substr($msprubr,0,2).substr($msprubr,3,2).substr($msprubr,6,2));
//		if (strlen($dat)>10) 
//	          	$ret.=(' '.substr($dat,11));
		}		
		return ($ret);

        }
//-----------------------------------------------------------
	public function rusegissobyid($id) {
		$model=Kmsasgo::model()->findByPk($id);
//		$mspkod,$mspkat,$mspuro,$mspisto,$mspform,$msprubr) { 
		$ret='';
		if ($model->mspkod>0) {
			$ret.=(substr(10000+$model->mspkod,1));
			$ret.=$model->mspkat; //substr($dat,6,2)):"";
			$ret.=(substr(100+$model->mspuro,1));
			$ret.=(substr(10000+$model->mspisto,1));
			$ret.=(substr(100+$model->mspform,1));		
//			$ret.=$msprubr;
			$ret.=(substr($model->msprubr,0,2).substr($model->msprubr,3,2).substr($model->msprubr,6,2));
//		if (strlen($dat)>10) 
//	          	$ret.=(' '.substr($dat,11));
		}		
		return ($ret);

        }
//----------------------------------------------------------
//----------------------------------------------------------
	public function rusdatvrem($dat) { 
		$ret=($dat>0)?('<b>'.substr($dat,8,2).".".substr($dat,5,2).".".substr($dat,0,4).'</b>'):"";
		if (strlen($dat)>10) 
	          	$ret.=(' <small>'.substr($dat,11).'</small>');
		
		return ($ret);

        }
//----------------------------------------------------------
	public static function torusdat($dat) { 

//          return ($dat>0)?(substr($dat,8,2).".".substr($dat,5,2).".".substr($dat,0,4)):"";
          return ($dat>0)?(substr($dat,8,2)."".substr($dat,5,2)."".substr($dat,0,4)):"";

        }
//----------------------------------------------------------
	public static function fromrusdat($dat) { 

//          return ($dat>0)?(substr($dat,6,4)."-".substr($dat,3,2)."-".substr($dat,0,2)):"";
          return ($dat>0)?(substr($dat,4,4)."-".substr($dat,2,2)."-".substr($dat,0,2)):"";

        }
//----------------------------------------------------------

	public function skladr_streets_autocomplete($parq) {

//		if (isset($_GET['q'])) {
//$parq=$_GET['q'];
//$parq='ул.'; //$_GET['q'];
//$parlimit=$_GET['limit'];
//print_r($_GET);       
//$parnpunkt=(isset($_GET['np']))?$_GET['np']:1; //$_SESSION['cur_npunkt'];
//print $parnpunkt;
	$sql=sprintf("
		select id,seekname as name
		from smev.sstreets
		where seekname like '%s'
		order by id_npunkt,seekname;
	     ",('%'.$parq.'%')
	);
	$command=Yii::app()->db->createCommand($sql); 
	$prows = $command->queryAll();
    
	$resStr = "";
	foreach($prows as $prow) {
		$resStr .= $prow['name']."\n";
	}
        return $resStr;
//		}
	}

//---------------------------------------
  public static function get_egisso() {
        $row=array('0'=>' ');
	$sql=sprintf("
		select kod as id,name
		from _school
		where type>0
		order by id;
	        "
	);
	$command=Yii::$app->db->createCommand($sql);
	$prows = $command->queryAll();
    
        foreach($prows as $prow) {
       	         $row[$prow['id']]=$prow['name'];
	}
	return ($row);
}
//-------------------------------------------------

public function change_egisso($komu,$nachto=0) {
	$rowCount=0;
//	if ($nachto>0) {
		$connection=Yii::app()->db; 
		$sql=sprintf('	
			UPDATE users
			SET scool_only=%s
			WHERE id=%s
			'
        	       , $nachto, $komu
		);
//print $sql;
		$updatecommand=Yii::app()->db->createCommand($sql);
		$updatecommand=$connection->createCommand($sql);
		$rowCount=$updatecommand->execute(); 
//	}
	return $rowCount;
}

//-----------------------------------------------------------
	public function ugl_stamp($umodel) {
	if (isset($umodel) and ($umodel->id>0)) {
//		$umodel=Dislall::model()->findByPk($coper);
		$ret='<div id="ugl_stamp" style="width:270px;text-align:center;">';
		$ret.='<img src="/images/image002.jpg" width="35" height="50"><br>';
		$ret.='<p style="font-size:1.0em;text-align:center;">Муниципальное образование<br/>Серовский городской округ</p>';
		$ret.='<p style="font-size:1.0em;text-align:center;"><b><span style="font-size:1.1em;text-align:center;">'.mb_strtoupper($umodel->name,'UTF-8').'</span><br/>';
		$ret.=(($umodel->krat)?('('.$umodel->krat.')'):'').'</b></p>';
		$ret.='<p style="font-size:1.0em;text-align:center;line-height:90%;">адрес:  '.$umodel->adres.'</p>';
		$ret.='<p style="font-size:1.0em;text-align:center;">Телефон:  '.$umodel->tel.'<br/>e-mail: '.Site::strtoupperm($umodel->email).(($umodel->okpo or $umodel->ogrn)?('<br>ОКПО '.$umodel->okpo.', ОГРН '.$umodel->ogrn):'').'<br>ИНН '.$umodel->inn.', КПП '.$umodel->kpp.'<br/>
№___ от "'.date("d").'" '.Site::ntocmonthrod(date("m")).' '.date("Y").' г.';//<br/>на №___ от "___"__________ '.date("Y").' г.</p>';
		$ret.='</div>';
	} else {
		$ret='<div id="ugl_stamp"></div>';
	}
	return $ret;
	}
//-----------------------------------------------------------
	public function podpis($kod=0,$pdf=0) { // $kod=9 для РФЛ1 подписи не надо
		if ($kod==9 or $pdf>0) {
			$ret='<table width=100%>';
			$ret.='<tr><th style="font-size: 0.8em;">Должностное лицо</th><td> _________________________________________________________ </td><td>__________________</td></tr>';
			$ret.='<tr><td></td><td style="font-size: 0.6em;text-align:center;text-valign:top;">ФИО, подпись должностного лица</td><td style="font-size: 0.6em;text-align:center;text-valign:top;">Дата</td></tr>';
			$ret.='</table>';
		} else
//			$ret='<h4>'.((Yii::app()->user->name!='Тренихина Мария Михайловна')?'Специалист':'Начальник').' отдела учета населения  _________________ '.Yii::app()->user->name.'</h4>';
			$ret='<h4>'.(Yii::app()->user->dolg).'  _________________ '.Yii::app()->user->name.'</h4>';
	return $ret;
	}
//-----------------------------------------------------------
//-----------------------------------------------------------
//-----------------------------------------------------------

	public function podpisruk($kod=0) { // $kod=9 для РФЛ1 подписи не надо
	$ret='';
/*
		if ($kod==9) {
			$ret='<table width=100%>';
			$ret.='<tr><th>Должностное лицо</th><td> _________________________________________________________ </td><td>__________________</td></tr>';
			$ret.='<tr><td></td><td style="font-size: 0.6em;text-align:center;text-valign:top;">ФИО, подпись должностного лица органа регистрационного учёта</td><td style="font-size: 0.6em;text-align:center;text-valign:top;">Дата</td></tr>';
			$ret.='</table>';
		} else
//			$ret='<h4>'.((Yii::app()->user->name!='Тренихина Мария Михайловна')?'Специалист':'Начальник').' отдела учета населения  _________________ '.Yii::app()->user->name.'</h4>';
			$ret='<h4>'.(Yii::app()->user->dolg).'  _________________ '.Yii::app()->user->name.'</h4>';
*/
//			$ret='<h4>Начальник отдела учета населения  _________________ Тренихина Марина Михайловна </h4>';
			$ret='<h4>Начальник отдела учета населения  _________________ '.Yii::app()->params['nach'].'</h4>';

	return $ret;
	}
//-----------------------------------------------------------
	public function podpis2($kod=0,$pdf=0) { // $kod=9 для РФЛ1 подписи не надо
		$ret='<table width=100%>';
		if ($kod==9 or $pdf>0) {
			$ret.='<tr><th style="font-size: 0.8em;">Должностное лицо</th><td> _________________________________________________________ </td><td>__________________</td></tr>';
			$ret.='<tr><td></td><td style="font-size: 0.6em;text-align:center;text-valign:top;">ФИО, подпись должностного лица</td><td style="font-size: 0.6em;text-align:center;text-valign:top;">Дата</td></tr>';
//			$ret.='</table>';
		} else {
//			$ret='<h4>'.((Yii::app()->user->name!='Тренихина Мария Михайловна')?'Специалист':'Начальник').' отдела учета населения  _________________ '.Yii::app()->user->name.'</h4>';
//			$ret='<h4>'.(Yii::app()->user->dolg).'  _________________ '.Yii::app()->user->name.'</h4>';
			$ret.='<tr><td> _____________________________________ </td><td>'.Yii::app()->user->name.'</td></tr>';
			$ret.='<tr><td style="font-size: 0.6em;text-align:center;text-valign:top;">'.(Yii::app()->user->dolg).'</td><td></td></tr>';
			$ret.='<tr><td> _____________________________________ </td><td>'.Yii::app()->params['nach'].'</td></tr>';
			$ret.='<tr><td style="font-size: 0.6em;text-align:center;text-valign:top;">Начальник отдела учета населения</td><td></td></tr>';
		}
//		$ret.='<tr><td style="font-size: 0.6em;text-align:center;text-valign:top;">'.(Yii::app()->user->dolg).'</td><td></td></tr>';
		$ret.='</table>';

	return $ret;
	}
//----------------------------------------------------------
//-----------------------------------------------------------
public function strtoupperm($st) {
  $st = strtr($st, 
    "абвгдеёжзийклмнопрстуфхцчшщьыъэюяabcdefghijklmnopqrstuvwxyz№",
    "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ№"
  );
  return $st;
}
//---------------------------------------
public function criterii() {
        $criteria = new CDbCriteria;
        $criteria->compare('snils','>9000000' );
        
	     return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
}
//-----------------------------------------------------------------
 public function podnam_mod() {
 $ret=array();
$per=$_SESSION['cur_period'];
$coper=Yii::app()->user->id;
	if (($coper==1) or ($coper==7)) $coper=Yii::app()->user->scool();


		//	        $per=Site::fromperiod($_SESSION['cur_period']);
$sd='sum(d1) as d1';	
 for ($i=1;$i<=31;$i++) 
	$sd.=',sum(d'.$i.') as d'.$i;	

 			$sql=sprintf('SELECT expo.id_kmsasgo, 
						kmsasgo.is_invalid, 	
						if(expo.prim<5,1,5) as klas,
						 expo.tarif,
						 expo.vyd/expo.fact as prtarif,
						 %s

					FROM  `datatabel` dt
					INNER JOIN smev.expo ON expo.id_fio = dt.id_fio
							AND expo.id_period = dt.id_period
							AND expo.id_kmsasgo = dt.id_kmsasgo

					inner JOIN smev.kmsasgo ON kmsasgo.id=expo.id_kmsasgo
			 	 WHERE  dt.id_period =%s
					AND expo.coper =%s
					AND expo.autor =30
					AND expo.fact>0
					AND expo.prim>0
				group by kmsasgo.is_invalid, if(expo.prim <5,1,5)
			; 
			',$sd,$per,$coper);
			$command=Yii::app()->db->createCommand($sql);
			$rows = $command->queryAll();
//print $sql;    
	foreach($rows as $row) {
			$ret[]=$row;
	}
		  
	return ($ret);
}

/*
SELECT ht.data, expo.id, expo.coper, expo.prim, expo.tarif, IF( kmsasgo.is_invalid, IF( expo.prim <5, ht.t2, ht.t4 ) , IF( expo.prim <5, ht.t1, ht.t3 ) ) AS rtarif
FROM smev.expo
INNER JOIN smev.kmsasgo ON kmsasgo.id = expo.id_kmsasgo
INNER JOIN dok.histarif ht ON ht.coper = expo.coper
INNER JOIN (

SELECT coper, MAX( data ) AS mdata
FROM dok.histarif
GROUP BY coper
)md ON md.coper = expo.coper
AND md.mdata = ht.data
WHERE expo.autor =30
AND expo.id_period =111
AND expo.fact >0
AND expo.prim >0
AND expo.tarif != IF( kmsasgo.is_invalid, IF( expo.prim <5, ht.t2, ht.t4 ) , IF( expo.prim <5, ht.t1, ht.t3 ) ) 



#SELECT ht.data, expo.id, expo.coper, expo.prim, expo.tarif, IF( kmsasgo.is_invalid, IF( expo.prim <5, ht.t2, ht.t4 ) , IF( expo.prim <5, ht.t1, ht.t3 ) ) AS rtarif
#FROM smev.expo
UPDATE expo
INNER JOIN smev.kmsasgo ON kmsasgo.id = expo.id_kmsasgo
INNER JOIN dok.histarif ht ON ht.coper = expo.coper
INNER JOIN (
	SELECT coper, MAX( data ) AS mdata
	FROM dok.histarif
	GROUP BY coper
)md ON md.coper = expo.coper
AND md.mdata = ht.data
SET expo.tarif=IF( kmsasgo.is_invalid, IF( expo.prim <5, ht.t2, ht.t4 ) , IF( expo.prim <5, ht.t1, ht.t3 ) ),expo.nach=IF( kmsasgo.is_invalid, IF( expo.prim <5, ht.t2, ht.t4 ) , IF( expo.prim <5, ht.t1, ht.t3 ) )*expo.fact
WHERE expo.autor =30
AND expo.coper=%s
AND expo.id_period =111
AND expo.fact >0
AND expo.prim >0
AND expo.tarif != IF( kmsasgo.is_invalid, IF( expo.prim <5, ht.t2, ht.t4 ) , IF( expo.prim <5, ht.t1, ht.t3 ) ) 

SELECT ht.pbegin,ht.pend, expo.id, expo.coper, expo.prim, expo.tarif, IF( kmsasgo.is_invalid, IF( expo.prim <5, ht.t2, ht.t4 ) , IF( expo.prim <5, ht.t1, ht.t3 ) ) AS rtarif
FROM smev.expo
#UPDATE expo
INNER JOIN smev.kmsasgo ON kmsasgo.id = expo.id_kmsasgo
INNER JOIN dok.histarifpasgo ht ON (expo.id_period between ht.pbegin AND ht.pend)
#SET expo.vyd=IF( kmsasgo.is_invalid, IF( expo.prim <5, ht.t2, ht.t4 ) , IF( expo.prim <5, ht.t1, ht.t3 ) )*expo.fact
WHERE expo.autor =30
AND kmsasgo.id_mspa=45
AND expo.coper=%s
AND expo.id_period =111
AND expo.fact >0
AND expo.prim >0
AND expo.tarif != IF( kmsasgo.is_invalid, IF( expo.prim <5, ht.t2, ht.t4 ) , IF( expo.prim <5, ht.t1, ht.t3 ) ) 



SELECT expo.id, expo.id_fio, expo.coper, expo.prim, d1 + d2 + d3 + d4 + d5 + d6 + d7 + d8 + d9 + d10 + d11 + d12 + d13 + d14 + d15 + d16 + d17 + d18 + d19 + d20 + d21 + d22 + d23 + d24 + d25 + d26 + d27 AS dd, expo.fact
FROM  `datatabel` dt
INNER JOIN smev.expo ON expo.id_fio = dt.id_fio
AND expo.id_period = dt.id_period
AND expo.id_kmsasgo = dt.id_kmsasgo
AND expo.coper = dt.coper
WHERE dt.id_period =112
AND (
d1 + d2 + d3 + d4 + d5 + d6 + d7 + d8 + d9 + d10 + d11 + d12 + d13 + d14 + d15 + d16 + d17 + d18 + d19 + d20 + d21 + d22 + d23 + d24 + d25 + d26 + d27 != expo.fact
)
ORDER BY  `dt`.`coper` , expo.prim ASC 
*/
//-------------------------------------------------------

}