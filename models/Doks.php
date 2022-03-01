<?php

namespace app\models;

use Yii;


class Doks extends \yii\db\ActiveRecord
{
    public $dkem;
    public $_data_out;
	public $_data_in;
	public $sp_doktype=array(''=>'','901'=>'Свидетельство о рождении','21'=>'Паспорт РФ');
    public $sp_lgdoktype=array(
		'  '=>'',
		'910'=>'Удостоверение',
		'911'=>'Справка',
		'912'=>'Приказ органа опеки УСП',
		'913'=>'Протокол ПМПК',
	);

    public static function tableName()
    {
        return 'doks';
    }


    public function rules()
    {
        return [
          //  [['id_fio', 'doktype', 'doks_ser', 'doks_nom', 'doks_dat', 'doks_kem_kod', 'doks_kem'], 'required'],
            [['id_fio', 'doktype'], 'integer'],
         
            [['doks_ser', 'doks_nom'], 'string', 'max' => 50],
            [['doks_kem_kod'], 'string', 'max' => 50],
            [['doks_kem'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_fio' => 'Id Fio',
            'doktype' => 'Id Type',
            'doks_ser' => 'Серия',
            'doks_nom' => 'Номер',
            'data_vyd' => 'Data Vyd',
            'doks_kem_kod' => 'Doks Kem Kod',
            'doks_kem' => 'Doks Kem',
        ];
    }
    //----------------------------------------
    //---------------------------------------

   public function sp_doktype($idtype=0) {   

	$sql=sprintf("
			select id,name
			from _doktypes
			where %s
			order by id
			;
		     ",($idtype>=0)?('id='.$idtype):'1'
	     );
	$command=Yii::$app->db->createCommand($sql); 
	if($idtype>=0) {
		$row = $command->queryOne();
		return ($row['name']);
	} else {
		$rows = $command->queryAll();
		$ret=array();
		foreach($rows as $row)
			$ret[$row['id']]=$row['name'];	
		return ($ret);
	}
  }
  //---------------------------------------

   public function sp_pasptype() {   

	$sql=sprintf("
			select id,name
			from _doktypes
			where id in (901,21)
			order by id
			;
		     ");
	$command=Yii::$app->db->createCommand($sql); 
	
		$rows = $command->queryAll();
		$ret=array();
		foreach($rows as $row)
			$ret[$row['id']]=$row['name'];	
		return ($ret);

  }
  //----------------------------------------------
  //-----------------------------------------------------------
public static function hoiskem($_kem='') {
	$ret=array();
	$connection=Yii::$app->db; 
        $sql=sprintf("
		SELECT id,kod,name
		FROM _doks_kem_kod
		WHERE concat(kod,' ',name) like '%s'
		LIMIT 1
		;",'%'.trim($_kem).'%'
	);
//print $sql;
	$command=$connection->createCommand($sql);
	$row = $command->queryOne();
	if ($row['id']) {
               	$ret['id']=$row['id'];
               	$ret['kod']=$row['kod'];
               	$ret['name']=$row['name'];
       	}
//print_r($ret);
	return $ret;
}
//-----------------------------------------------------------------
//--------------------------------
public function actionKemrogd() {
    $resStr='';

if (true)
        $sql=sprintf("
            select ff.id,ff.kod,concat(ff.kod,' ',ff.name) as value,concat(ff.kod,' ',ff.name) as label
            from pasp._doks_kem_kod ff
            where ((id=0) or doktype=901) 
            order by kod;
             "
        );
else
        $sql=sprintf("
            select ff.doks_kem_kod,concat(ff.doks_kem_kod,' ',ff.doks_kem) as value,concat(ff.doks_kem_kod,' ',ff.doks_kem) as label
            from doks ff
            where 1
            GROUP BY doks_kem_kod,doks_kem
            order by doks_kem;
             "
        );

        $command=Yii::app()->db->createCommand($sql); 
        $prows = $command->queryAll();
   
    
           return $prows;
//	echo json_encode($aret);
}
//--------------------------------
public function actionKempasp() {
    $resStr='';
//		$skv=iconv('UTF-8','WINDOWS-1251','Кв.');

    if (isset($_GET['q'])) {
        $parq=$_GET['q'];
        if(is_numeric($parq) and strlen($parq)>3) $parq=substr($parq,0,3).'-'.substr($parq,3);
if (true)
        $sql=sprintf("
            select ff.id,ff.kod,concat(ff.kod,' ',ff.name) as name
            from pasp._doks_kem_kod ff
            where ((id=0) or doktype=21 or doktype=12 or doktype=19 or doktype=20) and (concat(ff.kod,' ',ff.name) like '%s')
            order by kod;
             ",'%'.$parq.'%'
        );
else
        $sql=sprintf("
            select ff.doks_kem_kod,concat(ff.doks_kem_kod,' ',ff.doks_kem) as name
            from doks ff
            where (concat(ff.doks_kem_kod,' ',ff.doks_kem) like '%s')
            GROUP BY doks_kem_kod,doks_kem
            order by doks_kem;
             ",'%'.$parq.'%'
        );

        $command=Yii::app()->db->createCommand($sql); 
        $prows = $command->queryAll();
        foreach($prows as $prow) {
            $resStr .= ($prow['name']."\n");
        }
    }
           echo $resStr;
//	echo json_encode($aret);
}
//--------------------------------
public function actionKeminopasp() {
    $resStr='';

        $sql=sprintf("
            select ff.id,ff.kod,concat(ff.kod,' ',ff.name,' [',ff.country,']') as name
            from _doks_kem_kod ff
            where ((id=0) or doktype=13)
            order by kod;
             "
        );

        $command=Yii::app()->db->createCommand($sql); 
        $prows = $command->queryAll();


           return $prows;
//	echo json_encode($aret);
}

//-------------------------------------------------------------------------
}
