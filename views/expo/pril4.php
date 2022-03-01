<?php


        $sp_kms=array();
        $m=array();

	foreach($model as $id=>$row) {
		if ($id>=0) {
			foreach($model[-$id] as $pp=>$item) if(!isset($sp_kms[$pp])) $sp_kms[$pp]=$item;
			foreach($row as $klass=>$ar1) 
				foreach($ar1 as $id_kms=>$ar2) 
					foreach($ar2 as $tarif=>$ar3) 
						if(isset($m[$klass][$id_kms][$tarif])) {
							$m[$klass][$id_kms][$tarif]['kold']+=$ar3['kold'];
							$m[$klass][$id_kms][$tarif]['sumd']+=$ar3['sumd'];
							$m[$klass][$id_kms][$tarif]['kolp']+=$ar3['kolp'];
							$m[$klass][$id_kms][$tarif]['sump']+=$ar3['sump'];
							$m[$klass][$id_kms][$tarif]['tdist']+=$ar3['tdist'];
							$m[$klass][$id_kms][$tarif]['days']+=$ar3['days'];
							$m[$klass][$id_kms][$tarif]['nach']+=$ar3['nach'];
							$m[$klass][$id_kms][$tarif]['cou']+=$ar3['cou'];
						} else $m[$klass][$id_kms][$tarif]=array(
							'kold'=>$ar3['kold'],
							'sumd'=>$ar3['sumd'],
							'kolp'=>$ar3['kolp'],
							'sump'=>$ar3['sump'],
							'tdist'=>$ar3['tdist'],
							'days'=>$ar3['days'],
							'nach'=>$ar3['nach'],
							'cou'=>$ar3['cou']
							);			
//			if (count($m)<1) $m=$row;
//	        'coper'=>$coper,
//			print '<br/>------'.$id.'------<br/>';
//			print_r($m); 
		}
	}

	echo $this->render('pril4_137', array(
		'model'=>$m,
	        'scool'=>'Приложение 4 (свод)',//$model[-$id]['krat'],
	        'sp_kms'=>$sp_kms,
	        'noitog'=>'1',
	        'id_scool'=>0,
	        'coper'=>0
		)
	);

?>