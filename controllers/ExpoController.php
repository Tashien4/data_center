<?php

namespace app\controllers;

use Yii;
use app\models\Expo;
use app\models\Fio;
use app\models\Histarif;
use app\models\Doks;
use app\models\Sout;
use app\models\Site;
use app\models\Lgdoks;
use app\models\Datatabel;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use yii\web\Response;
use DateTime;

/**
 * ExpoController implements the CRUD actions for Expo model.
 */
class ExpoController extends Controller
{
    public $layout='menu_plus';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['list'],
                'rules' => [
                    // deny all POST requests
                    [   
                        'allow' => false,
                        'verbs' => ['POST']
                    ],
                    // allow authenticated users
                    [
                       
                       'allow' => true,
                        'roles' => ['@'],
                    ],
                    
                ],
            ],
          
        ];
    }

    /**
     * Lists all Expo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Expo::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
//-------------------------------------
//-------------------------------------------------------------
	public function actionError() {
		
		
	    if($error=Yii::$app->errorHandler->exception)
	    {

	    if(Yii::$app->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }//$this->render('error', $error);
	}
//-------------------------------------------------------------
public function actionImport(){

}
//-----------------------------------------------------------
public function actionSout() {

    if(isset($_GET['id']) and ($_GET['id'])>0) 	
        $per=$_GET['id'];
    else 	
        $per=$_SESSION['cur_period'];

        $upd=Yii::$app->user->identity->getParams(Yii::$app->user->id);
    $coper=$upd['school'];


        $model=new Sout;
        $scool=$model->sout($per);
if(isset($_POST['save'])) {

    $model->date=date("Y-m-d");
    foreach($scool['links'] as $links){
        $model=new Sout;
        $model->date=date("Y-m-d");
        $model->name=$links['fname'];
        $model->save();
    };
    $sql='update expo set in_period=id_period where in_period=1';
    Yii::$app->db->createCommand($sql)->execute(); 
    $this->redirect('list');
}
       return $this->render('sout',array(
            'model'=>$model,'per'=>$per,'scool'=>$scool
			
    ));

//		if (($coper==1) or ($coper==7) or ($coper==1130)) { print_r(Datatabel::model()->atarif(361253,15)); }
}
//-----------------------------------------------------------//------
//-----------------------------------------------------------
public function actionPril4() {

    if(isset($_GET['id']) and ($_GET['id'])>0) 	
        $per=$_GET['id'];
    else 	
        $per=$_SESSION['cur_period'];

        $upd=Yii::$app->user->identity->getParams(Yii::$app->user->id);
    $coper=$upd['school'];

    if ($upd['role']==1) 
                $only=0;
    else $only=1;

        $model=Expo::statist4($per,$coper,$only);
if (count($ad=Expo::has_double())>0) 
    print_r($ad);

       return $this->render('pril4',array(
            'model'=>$model,
//			    'model5'=>$model5,
            'coper'=>$coper,'per'=>$per,
    ));

//		if (($coper==1) or ($coper==7) or ($coper==1130)) { print_r(Datatabel::model()->atarif(361253,15)); }
}
//-----------------------------------------------------------//-----------------------------------------------------------
public function actionPrilall() {

    if(isset($_GET['id']) and ($_GET['id'])>0) 	
        $per=$_GET['id'];
    else 	
        $per=$_SESSION['cur_period'];

        $upd=Yii::$app->user->identity->getParams(Yii::$app->user->id);
    $coper=$upd['school'];

    if ($upd['role']==1) 
                $only=0;
    else $only=1;

        $model=Expo::statist4($per,$coper,$only);
if (count($ad=Expo::has_double())>0) 
    print_r($ad);

       return $this->render('prilall',array(
            'model'=>$model,
//			    'model5'=>$model5,
            'coper'=>$coper,'per'=>$per,
    ));

//		if (($coper==1) or ($coper==7) or ($coper==1130)) { print_r(Datatabel::model()->atarif(361253,15)); }
}
//-----------------------------------------------------------
public function actionSeparate()
{
    
    $id=$_GET['id'];
    $model=Expo::find()->where(['id'=>$id])->one();
    if(isset($_POST['Expo'])) {
        $idd=$model->separate_me($id,$_POST['Expo']['rend_dop'],$_POST['Expo']['id_kmsasgo_dop']);
         return $this->redirect(['/expo/update?id='.$idd]);
    };
    return $this->render('separate',array(
        'model'=>$model));
}
//-----------------------------------------------------------
    public function actionCreate()
    {
        $this->layout="main";
        $model = new Expo();
        $upd=Yii::$app->user->identity->getParams(Yii::$app->user->id);

        $per=$_SESSION['cur_period'];
        $model->id_period=$per;
		$model->coper=$upd['school'];
        $sp_msp=$model->sp_kod();
		$sp_kat=$model->sp_katbykod();

        if ($this->request->isPost) {
            $model->attributes=$_POST['Expo'];
            $klass=($_POST['Expo']['klass']);
				$liter=($_POST['Expo']['liter']);
                $model->prim=$klass.' '.($liter!=''?$liter:'');
            $id_fio=((isset($_POST['Expo']['fio']))?($model->findbyautoresult($_POST['Expo']['fio'])):null); //находим идентификатор
            if (isset($id_fio)) {
                $prov_on_exist=Expo::find()->where(['id_fio'=>$id_fio,'id_period'=>$per])->one(); //проверяем есть ли такой человек в базе в этом периоде
                if(isset($prov_on_exist->id))
                    return $this->redirect(['/expo/update?id='.$prov_on_exist->id]);
                else {    
                    $model->id_fio=$id_fio;
                    $fiomodel=Fio::find($model->id_fio)->one(); 
                    
                       // $model->snils=$fiomodel->snils;
                        $model->actu_pasp=$fiomodel->last_pasp_id($model->id_fio);
                        $model->actu_lgdok=$fiomodel->last_lg_id($model->id_fio);
                        if ($model->actu_lgdok>0) 
                            $dmodel=Doks::find($model->actu_lgdok)->one();
                }
                   
            }
            else return $this->redirect(['/expo/create_people?id_kmsasgo='.$model->id_kmsasgo.'&prim='.$model->prim.'&fio='.$_POST['Expo']['fio']]);
        
        
        };
            if ($model->load($this->request->post()) && $model->save()) 
                return $this->redirect(['update', 'id' => $model->id]);
            /* else 
                print_r($model->geterrors());*/
            

        return $this->render('create', [
            'model' => $model,'sp_msp'=>$sp_msp,'sp_kat'=>$sp_kat
        ]);
    }
//-----------------------------------------------
public function actionCreate_people() {

    $fio=$_GET['fio'];
    $pmodel=new Fio;
    $model= new Expo; 
    $upd=Yii::$app->user->identity->getParams(Yii::$app->user->id);
    $model->coper=$upd['school'];
        $model->id_kmsasgo=$_GET['id_kmsasgo'];
        $model->prim=$_GET['prim'];
        $model->id_period=$_SESSION['cur_period'];
        if((int)$model->prim>0)
            $model->data_resh=((date("m")>5 and date('m')<10)?date("Y").'-09-01':date("Y").'-'.date('m').'-01');
        else $model->data_resh=date('Y-m-d');
        $model->rbegin=$model->data_resh;
    if(isset($_POST['Fio'])) {
        $pmodel->attributes=$_POST['Fio'];
        $pmodel->rod=date("Y-m-d",strtotime($pmodel->rod));
        print_R($pmodel->attributes);
        if($pmodel->validate())
            $pmodel->save();
        else print $pmodel->geterrors();
       $model->id_fio=$pmodel->id;
        if($model->validate()) {
            $model->save();
            return $this->redirect(['/expo/update?id='.$model->id]);
        } else print $model->geterrors();
            
    }
    return $this->render('create_people', [
        'model' => $model,'pmodel' => $pmodel
    ]);

}
//-----------------------------------------------------------//-----------------------------------------------------------
	public function actionStatist() {

		if(isset($_GET['id']) and ($_GET['id'])>0) 	
			$id=$_GET['id'];
		else 	
			$id=$_SESSION['cur_period'];
			
		$model=Expo::statist($id);

   return     $this->render('statist',array(
            'model'=>$model,'period'=>$id
    ));
}
//-------------------------------------------------------        
public function actionRepl_by_coper() {


    $upd=Yii::$app->user->identity->getParams(Yii::$app->user->id);
$coper=$upd['school'];
if ($upd['role']==1) { 
$coper=999; //Yii::app()->user->school;

if(isset($_GET['id_period'])) 	{
    $per=$_GET['id_period'];	
} else 
    $per=$_SESSION['cur_period']+1;
$model=Datatabel::repl_by_coper($per,$coper);

}
return $this->redirect(['expo/list']);
}
//-----------------------------------------------------------

    //-------------------------------------------
    public function actionUpdate($id)
    {
        $this->layout='menu_plusupdate';
        $model = Expo::find()->where(['id'=>$_GET['id']])->one();
        $omodel = Expo::find()->where(['id'=>$_GET['id']])->one();
        $ans='';
        $model->_msp=$model::mspbykat($model->id_kmsasgo);
       // $fmodel=Fio::find()->where(['id'=>$model->id_fio])->one();
        $upd=Yii::$app->user->identity->getParams(Yii::$app->user->id);
        $coper=$model->coper;
        $sp_msp=$model->sp_kod();
		$sp_kat=$model->sp_katbykod();
        $pmodel=Fio::find()->where(['id'=>$model->id_fio])->one();

        
               //записываем основной и льготный документ отдельно, чтобы не исправить их случайно при изменении 
               $old_dok=$model->actu_pasp;
               $old_lgdok=$model->actu_lgdok;

               $klass_o=$model->prim;
               $klass=explode(' ',$model->prim);
                if($klass[0]=='') $klass[0]=0;
                     $model->klass=$klass[0];
                $model->liter=(isset($klass[1])?$klass[1]:'');

         if(isset($_POST['Expo']))
           $model->attributes=$_POST['Expo'];

    if(isset($_POST['Separate']))
        $model->separate_me($id,$_POST['Expo']['rend_dop'],$_POST['Expo']['id_kmsasgo_dop']);

//-------------------------------------------
if(isset($_POST['change_kl'])){

    if (isset($_POST['Expo']['klass']) and $_POST['Expo']['klass']!=0) 
        $model->prim=$_POST['Expo']['klass']." ".$_POST['Expo']['liter']; 
    else $model->prim=$klass_o; 

  if($model->rbegin==0 and $omodel->rbegin!=0)$model->rbegin=$omodel->rbegin; 
        else $model->rbegin=date("Y-m-d",strtotime($_POST['Expo']['rbegin']));
  if($model->rend==0 and $omodel->rend!=0)$model->rend=$omodel->rend;
    else $model->rend=date("Y-m-d",strtotime($_POST['Expo']['rend']));
        
  if(isset($_POST['Fio']['snils']) and $_POST['Fio']['snils']!=$pmodel->snils)
  {$pmodel->snils=$_POST['Fio']['snils'];
       // if(stristr($model->snils, '-') === true or stristr($model->snils, ' ') === true) {
            $pmodel->snils=str_replace('-','', $pmodel->snils); //убираем "-" из СНИЛС
            $pmodel->snils=str_replace(' ','', $pmodel->snils); //убираем лишние пробелы
       // };
       print_r($pmodel->snils);
        $ans=$model->prov_to_snils($pmodel->snils,$model->id_fio); //проверка и сохранение снилса
      //  
       }
       else $this->redirect('update?id='.$model->id.'&ret=1');
}
//---------------------------------------
if($model->actu_pasp>0)
        $pasp_model=Doks::find()->where(['id'=>$model->actu_pasp])->one();
else {
			$pasp_model=new Doks;
             $vozr=date("y",abs(strtotime(date("Y-m-d"))-strtotime($pmodel->rod)))-70;
                        if ($vozr<14) {
				$pasp_model->doktype=901;
                                $pasp_model->id_fio=$model->id_fio;
				$pasp_model->doks_kem='Отделом ЗАГС города Серова';
				$pasp_model->id_kem=909;
			}
			else {
                              	$pasp_model->doktype=21;
                                $pasp_model->id_fio=$model->id_fio;
			};
                
		};
        $pasp_model->dkem=$pasp_model->hoiskem($pasp_model->doks_kem_kod." ".$pasp_model->doks_kem);
        if(isset($_POST['new_pasp'])) {
            $pasp_model=new Doks;
            $vozr=date("y",abs(strtotime(date("Y-m-d"))-strtotime($pmodel->rod)))-70;
                       if ($vozr<14) {
               $pasp_model->doktype=901;
                               $pasp_model->id_fio=$model->id_fio;
               $pasp_model->doks_kem='Отделом ЗАГС города Серова';
               $pasp_model->id_kem=909;}
               else {
                $pasp_model->doktype=21;
                $pasp_model->id_fio=$model->id_fio;
            };
            $pasp_model->attributes=$_POST['Doks'];
            $pasp_model->doks_dat=date("Y-m-d",strtotime($_POST['Doks']['doks_dat']));
            $pasp_model->dkem=$pasp_model->hoiskem($pasp_model->doks_kem_kod." ".$pasp_model->doks_kem);
               $pasp_model->save();
                        
                        $sql='update expo 
                            set actu_pasp='.$pasp_model->id.'
                            where id='.$model->id;
                        $ee=Yii::$app->db->createCommand($sql)->execute(); 
                        $model->actu_pasp=$pasp_model->id;
            $this->redirect('update?id='.$model->id.'&ret=2');
        }

        if(isset($_POST['pasp'])) {
            //замена на существующий в паспортной
          //  if(isset($_POST['Expo']['actu_pasp'])) {

                if(isset($_POST['Expo']['actu_pasp']) and !(isset($_POST['new_pasp'])) and $_POST['Expo']['actu_pasp']!=$old_dok and $_POST['Expo']['actu_pasp']!=0)
                                  $model->actu_pasp=$_POST['Expo']['actu_pasp'];
               
                    //изменение текущего, если он не заполнен, или создание нового
                             elseif(!(isset($_POST['new_pasp']))) {
                    
                                 $pasp_model->attributes=$_POST['Doks'];
                                 $pasp_model->doks_dat=date("Y-m-d",strtotime($_POST['Doks']['doks_dat']));
                                 $pasp_model->dkem=$pasp_model->hoiskem($pasp_model->doks_kem_kod." ".$pasp_model->doks_kem);
                        if (!$pasp_model->validate()) {
                        print_r($pasp_model->geterrors());
                    } else { 
                        $pasp_model->save();
                        //обновим сразу во всех фактах,если было пустое
                        $sql='update expo 
                            set actu_pasp=if(actu_pasp=0,'.$pasp_model->id.',actu_pasp) 
                            where id_fio='.$model->id_fio;
                        $ee=Yii::$app->db->createCommand($sql)->execute(); 
                        $this->redirect('update?id='.$model->id.'&ret=2');
                    };
    
    
                              }; 
                    };
//----------------------------------------------------------------

if($model->actu_lgdok>0) 
        $lgmodel=Lgdoks::find()->where(['id'=>$model->actu_lgdok])->one();
else $lgmodel=new Lgdoks;

if(isset($_POST['change_lg'])) {

    
    //замена на существующий 

        if(isset($_POST['Expo']['actu_lgdok']) and $_POST['Expo']['actu_lgdok']!=$old_lgdok )
                          $model->actu_lgdok=$_POST['Expo']['actu_lgdok'];
//      
            //изменение текущего, если он не заполнен
                     elseif(isset($_POST['Lgdoks'])) {

                         $lgmodel->doks_ser=(isset($_POST['Lgdoks']['doks_ser'])?$_POST['Lgdoks']['doks_ser']:'');
                         $lgmodel->doks_nom=$_POST['Lgdoks']['doks_nom'];
                         $lgmodel->doks_dat=date("Y-m-d",strtotime($_POST['Lgdoks']['doks_dat']));
                         $lgmodel->end=date("Y-m-d",strtotime($_POST['Lgdoks']['end']));
                           // $lgmodel->dkem=$pasp_model->hoiskem($pasp_model->doks_kem_kod." ".$pasp_model->doks_kem);
                            if (!$lgmodel->validate()) {
                print_r($lgmodel->geterrors());
              
            } else { 
                $lgmodel->save();
                $model->actu_lgdok=$lgmodel->id;
                $sql='update expo 
                            set actu_lgdok=if(actu_lgdok=0,'.$lgmodel->id.',actu_lgdok) 
                            where id_fio='.$model->id_fio.' and id_kmsasgo='.$model->id_kmsasgo;
                        $ee=Yii::$app->db->createCommand($sql)->execute(); 
                       
            };


                      }; $this->redirect('update?id='.$model->id.'&ret=3');
            };
            if(isset($_POST['new_lg'])) {
                $lgmodel=new Doks;
                
                $lgmodel->attributes=$_POST['Lgdoks'];
                $lgmodel->id_fio=$model->id_fio;
                $lgmodel->doks_dat=date("Y-m-d",strtotime($_POST['Lgdoks']['doks_dat']));
                if($lgmodel->doktype!=913) {
                    $lgmodel->id_kem=912;
                    if($lgmodel->doktype==910) $lgmodel->lgtype=830;
                    elseif($lgmodel->doktype==912) $lgmodel->lgtype=803;
                    else $lgmodel->lgtype=84;
                    $lgmodel->doks_kem='Управление социальной политики в г.Серове';
                    $lgmodel->doks_kem_kod='УСП г.Серова';
                } else {
                    $lgmodel->id_kem=933;
                    $lgmodel->doks_kem='Территориальная психолого-медико-педагогическая комиссия';
                    $lgmodel->doks_kem_kod='ТПМПК';
                    $lgmodel->lgtype=804;
                };
                   if($lgmodel->validate())$lgmodel->save();
                   else prin_r($lgmodel->geterrors());
                            
                            $sql='update expo 
                                set actu_lgdok='.$lgmodel->id.'
                                where id='.$model->id;
                            $ee=Yii::$app->db->createCommand($sql)->execute(); 
                            $model->actu_lgdok=$lgmodel->id;
                $this->redirect('update?id='.$model->id.'&ret=3');
            }
            $new_lgot=0;
        if(isset($_POST['804'])) {	
			$new_lgot=$model->new_lgot($model->id_fio,804,$model->id_kmsasgo);
                        $this->redirect('update?id='.$model->id.'&ret=3');
		} elseif(isset($_POST['803'])) {
			$new_lgot=$model->new_lgot($model->id_fio,803,$model->id_kmsasgo);
                        $this->redirect('update?id='.$model->id.'&ret=3');	
		} elseif(isset($_POST['84'])) {
			$new_lgot=$model->new_lgot($model->id_fio,84,$model->id_kmsasgo);
                        $this->redirect('update?id='.$model->id.'&ret=3');	
		} elseif(isset($_POST['830'])) {
			$new_lgot=$model->new_lgot($model->id_fio,830,$model->id_kmsasgo);	
                        $this->redirect('update?id='.$model->id.'&ret=3');
		};
      /*  if(isset($_POST['Expo']['fionamedop'])) {
            $parent=$model->findbyautoresult($_POST['Expo']['fionamedop']);
            if(isset($parent)){
                $new_lgot=$model->new_lgotf($model->id_fio,$parent,830,$model->id_kmsasgo);	
                $model->actu_lgdok=$new_lgot;
               // print_r( $new_lgot);
                $this->redirect('update?id='.$model->id.'&ret=3');
            }
        }*/
if($new_lgot!=0) $model->actu_lgdok=$new_lgot;
        /*
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
*/
if(isset($_POST['Expo'])){
        if($model->validate()) 
            $model->save();

            else {print_r($model->geterrors());
				$this->render('error',array('code'=>'','message'=>''));}
};   
        return $this->render('update', [
            'ans'=>$ans,
            'model' => $model,
            'lgmodel' => $lgmodel,
            'sp_msp'=>$sp_msp,
            'sp_kat'=>$sp_kat,
            'sp_his'=>$model->sp_hispasgo($model->id),
            'coper'=>$coper,
            'pasp_model'=>$pasp_model,
            'lgmodel'=>$lgmodel,
            'pmodel'=>$pmodel,
            'upd'=>$upd,
            'sp830'=>$model->dok830($model->id,$model->actu_lgdok)

        ]);
    }
//----------------------------------------------------------------
	public function actionKl_tabel() {
        $emodel=new Expo;
        $this->layout='menu_site';
     
        $upd=Yii::$app->user->identity->getParams(Yii::$app->user->id);
        $coper=$upd['school'];
        $etarif=array();
          
    $id_period=$_SESSION['cur_period'];

 $id=((isset($_GET['id']))?$_GET['id']:''); // нужен для redirect

$aklass=$emodel->getklass($coper); 

$klass=trim($aklass[$id]);

if ((int)$klass<1) 
    $apluskoef=Datatabel::cou_his($id_period);

if(isset($_POST['fa'])) {

//$buds=((Yii::app()->user->id==1 or Yii::app()->user->id==7) and isset($_GET['bugs']))?true:false; 
$time_start = microtime(true); 
$time0 = microtime(true);

    foreach($_POST['fa'] as $id_fio_kms=>$ar) {
        $tt=explode('_',$id_fio_kms);
        
            $id_fio=substr($id_fio_kms,0,7);
            $id_kmsasgo=substr($id_fio_kms,7);
            $days='';

//if ($buds) { $time_end = microtime(true); print '<br/>::'.$id_fio.'  '.$id_kmsasgo.' - '.($time_end-$time_start).'<br>'; $time_start=$time_end; }

              print_r($_POST['did'][$id_fio_kms]);
        if (true) { //(0+$klass>0) {  // школы-0 (теперь и садики-1 по этой же схеме)
            $atar=((int)$klass>0)?Datatabel::sp_bykla(0,$coper,((int)$klass),$id_period):
            Datatabel::sp_bykla(1,$coper,(((int)$klass<-4)?-5:-1),$id_period);
            if (isset($_POST['did'][$id_fio_kms]) and ($_POST['did'][$id_fio_kms]>0)) 
                    $dmodel=Datatabel::find()->where(['id'=>$_POST['did'][$id_fio_kms]])->one();
            else { $dmodel=new Datatabel; 	
                    $dmodel->id_fio=$id_fio;	
                    $dmodel->id_kmsasgo=$id_kmsasgo;	
                    $dmodel->klass=$klass;	
                    $dmodel->coper=$coper;	
                    $dmodel->id_period=$id_period;
            }
            for ($i=1;$i<32;$i++) {
                $kl=((int)$klass>0?(int)$klass:($klass<-4?-5:-1));
                $ar['d'.$i]=((isset($ar['d'.$i]) and $ar['d'.$i]>0)?
                        $atar[$id_kmsasgo][$kl][$i]:0);
            } 
            $ar['plus']=(((int)$klass>0)?0:($atar[$id_kmsasgo]['plus'][0]/((isset($apluskoef[$id_fio]))?$apluskoef[$id_fio]:1)));
            print_r($ar);
            $dmodel->attributes=$ar;

              if (!$dmodel->validate()) print_r($dmodel->geterrors());
            else $dmodel->save();

        };          
    
    };     Datatabel::set_rascet($id_period,$coper,$klass);
    Expo::deletefortabel($id_period,$coper,$klass); 
    $this->redirect('list'); 
};//print_r($sqls);
                //  if(isset($_POST['button_print'])) {$this->redirect('expo/tabelecheck/'.$id);};
 

//		$dop=('expo.coper='.$coper.' AND expo.autor='.Expo::model()->expo_autor);
$afrombase=Datatabel::tabelecheck($coper,$klass,$id_period);
//if (Yii::app()->user->id==1) print_r($afrombase);
// это массив [id_fio.id_kmsasgo] = fio,cпиcок полей из datatabel
//		$find_datatab=Datatabel::model()->find_dt($id_period,$coper,$klass);*/
return $this->render('kl_tabel',array(
       // 'dmodel'=>Datatabel::model(),
       'model'=>$afrombase,
        'aparam'=>Datatabel::kl_param($_SESSION['cur_period'],$coper),
//			    'aper'=>Site::fromperiod($_SESSION['cur_period']),
        'zag'=>Site::fromperiod($_SESSION['cur_period'],1),
       'class'=>$klass,
        'coper'=>$coper,
//			    'find_dt'=>$find_datatab,
        'id'=>$id,
        'li5'=>(($klass<5)?5:6),

//			    'li5'=>(($klass<(($coper==27)?2:5))?5:6),


));
}
//------------------------------------------------
    public function actionDelete($id)
    {
        $model=$this->findModel($id);

    //удаляем данные из таблицы, где хранятся табеля посещения
        $dmodel=Datatabel::find()->where([
            'id_fio'=>$model->id_fio,           //уникальный идентификатор получателя МСП
            'id_period'=>$model->id_period,     //текущий отченый период
            'klass'=>$model->prim,              //класс или группа, в которой обучается получатель МСП
            'id_kmsasgo'=>$model->id_kmsasgo,    //льготная категория получтеля МСП
            'coper'=>$model->coper              //код образовательного учреждения
        ])->one();
        if(isset($dmodel->id))$dmodel->delete();

        //удаляем данные из таблицы expo, где хранятся данные о факте предоставления
        $model->delete();
        //возвращаем пользователя на страницу с реестром получателей МСП
        return $this->redirect(['list']);
    }
    //------------------------------------------------
public function actionTarif()	{ 
      $model=new Histarif;
//если нажали на кнопку "Сохранить"
      if(isset($_POST['save'])) {
         
//находим все строки в histarif без указания даты окончания действия тарифов
        $need_update=(new \yii\db\Query()) 
        ->select('id')
        ->from('histarif')
        ->where('end=0')
        ->All();

//меняем даты окончания на полученную -1 день
//$new_date=date("Y-m-d",strtotime('"'.$_POST['Histarif']['end'].'" -1 day'));
$new_date = new DateTime($_POST['Histarif']['end']);
$new_date->modify('-1 day');
$new_date=$new_date->format('Y-m-d');
       foreach($need_update as $nu) {
        $sql='update histarif set end="'.$new_date.'" where id='.$nu['id'];
        Yii::$app->db->createCommand($sql)->execute(); 
       }
       //запоминаем полученные данные из формы
       $model->attributes=$_POST['Histarif'];
//проверяем введенные данные. Если ошибок нет, то добавляем новую строку в таблицу histarif.
        if($model->validate()) {
            $model->save();
            $this->redirect(['tarif']);
        }
//в противном случае выводим сообщение об ошибке
        else print_r($model->geterrors());
    }
    return $this->render('tarif',['model'=>$model]);
 
}

//------------------------------------------------
public function actionList()	{

    $upd=Yii::$app->user->identity->getParams(Yii::$app->user->id);
   
    $censer=(new \yii\db\Query()) 
    ->select('iscensered as is')
    ->from('_school')
    ->where('kod='.$upd['school'])
    ->one();
print_r($censer);
    $model=new Expo;
    $per = (new \yii\db\Query()) 
    ->select('period')
    ->from('_change_mon')
    ->where('coper='.$upd['school'])
    ->one();

    if($per['period']!=0) 
        $_SESSION['cur_period']=$per['period'];
    else $_SESSION['cur_period']=(6+(date("Y")-2010)*12+((date("d")>5)?date("m"):(date("m")-1)));


$return_url='expo/list?'.http_build_query(array_merge($_GET, array_intersect_key($_GET, $_GET)));

    if(isset($upd['school']) or $upd['school']>0) 
        $coper=$upd['school']; 
    else $coper=99;
    $model->egisso_role=$coper;
    $per=$_SESSION['cur_period'];   
    $umodel=User::find()->where(['id'=>Yii::$app->user->id])->one();
    $umodel->iscensored=$censer['is'];
    $dop=['t.coper'=>('='.$coper)];  
//определяем сторку из таблицы Users,принадлежащую текущему пользователю
$usp=User::find()->where(['id'=>Yii::$app->user->id])->one();
//если был запрошен переход к другому учреждению 
    if (isset($_GET['Expo']['egisso_role']) and $upd['school']!=($_GET['Expo']['egisso_role'])) {
        $sql='update users set cur_org='.$_GET['Expo']['egisso_role'].' where id='.Yii::$app->user->id;
        Yii::$app->db->createCommand($sql)->execute(); 

        $this->redirect(array('list'));
    };
    if (isset($_GET['User']['iscensored'])) { 
        
        $oold=$censer['is'];
        $censer=($_GET['User']['iscensored']);
        if($coper==$upd['school'])
    $tarz=Yii::$app->user->identity->setCenserParams(Yii::$app->user->id,$censer);

         if($oold!=$censer)
                    $this->redirect(array('list'));
    };
    if (isset($_GET['Expo']['pech_tabel']) and ($_GET['Expo']['pech_tabel']>0))  {
        $this->redirect(array('/expo/kl_tabel?id='.$_GET['Expo']['pech_tabel']));
    };


            $model->no_snils=((isset($_GET['Expo']['no_snils']) and ($_GET['Expo']['no_snils']>0))?1:0); //определяем необходима ли выборка по отсутвию СНИЛС
            $model->no_dok=((isset($_GET['Expo']['no_dok']) and ($_GET['Expo']['no_dok']>0))?1:0); // $model->onlyosh=1;
            $model->no_lgot=((isset($_GET['Expo']['no_lgot']) and ($_GET['Expo']['no_lgot']>0))?1:0); // $model->onlyosh=1;
            $model->no_kms=((isset($_GET['Expo']['no_kms']) and ($_GET['Expo']['no_kms']>0))?1:0); // $model->onlyosh=1;
 
            


    return $this->render('list', [
        'upd'=>$upd,
        'per'=>$per,
        'model'=>$model,
        'umodel'=>$umodel,
        'sp_role'=>(($upd['role']>0)?Site::get_egisso():array()),
        'sp_klass'=>$model->getklass($coper),
        'sp_lgot'=>$model->sp_lgot($_SESSION['cur_period'],$coper)
    ]);

}                

//-------------------------------------------------------------------------

    protected function findModel($id)
    {
        if (($model = Expo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
//--------------------------------------
//-----------------------------------------------------------
public function actionKmsauto() {
	$aret[]=array('id'=>0,'name'=>'-');
	$aut=30;
    if ($_GET['id_msp']) {
		$parq=$_GET['id_msp'];
        
    Yii::$app->response->format = Response::FORMAT_JSON;
    if (Yii::$app->request->isAjax) {
        $sql=sprintf("
			select t.id, concat(substring(t.kat,1,100),'...') as name
			from kmsasgo t
            inner join msp_asgo on msp_asgo.id=t.id_mspa
			inner join _mspkod ON _mspkod.id=msp_asgo.mspkod
			where t.autor=30 AND msp_asgo.mspkod=%s
			order by t.kat;
        	",     $parq
		);
        $command=Yii::$app->db->createCommand($sql); 
		$prows = $command->queryAll();
        return ['success' => true, 'prows' => $prows];
    }

  //  return ['oh no' => 'you are not allowed :('];
}

	}
//-----------------------------------------------------------
    
}
