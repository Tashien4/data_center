<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Users;
use app\models\User;
use app\models\Expo;
use app\models\_school;
use app\models\_uparams;
use yii\web\Session;

class SiteController extends Controller
{
 
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update','index'],
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
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    //--------------------------------------------------------
    //-----------------------------------------------------------//------------------------------------------------
    public function actionDelete($id)
    {
        _uparams::find()->where(['id_user'=>$id])->one()->delete();

        User::findOne($id)->delete();

        return $this->redirect(['admin']);
    }
//------------------------------------------------
     //-----------------------------------------------------------
	public function actionAdmin()
	{    
        $this->layout='menu_site';
		$model=LoginForm::find()->where(['id'=>Yii::$app->user->id])->one();
                $upd=$upd=Yii::$app->user->identity->getParams(Yii::$app->user->id);
                $model->school=$upd['school'];
		$id=Yii::$app->user->id;
      /*  $month=(new \yii\db\Query())
        ->select('period')
        ->from('_change_mon')
        ->where('coper='.$model->school)
        ->One();
        $_SESSION['cur_period']=$month['period'];
		    $model->change_mod=$_SESSION['cur_period'];
		if(isset($_POST['User']))
		{   print_r($_POST['User']);
		       $pass_old=$model->password;
			    $model->attributes=$_POST['User'];
		        $sql='update users set username="'.$_POST['User']['username'].'" where id='.$id;
					$command=Yii::$app->db->createCommand($sql)->execute();

		        if($model->password!=$pass_old){
                    $sql='update users set password="'.sha1(md5($model->login).md5($model->password)).'" where id='.$id;
					$command=Yii::$app->db->createCommand($sql)->execute();
                };
                if($upd['role']==1){
                        $sqls='update _change_mon set period="'.$_POST['User']['change_mod'].'" where coper='.$model->school;
					$command=Yii::$app->db->createCommand($sqls)->execute();
                 };

			
                return $this->redirect(['site/lk']);
            }
*/
		return $this->render('admin',array('model'=>$model,'upd'=>$upd));
	}
//-----------------------------------------------------------
	public function actionLk()
	{
        $this->layout='menu_site';
        if(isset($_GET['id'])) $id=$_GET['id'];
        else $id=Yii::$app->user->id;
        $my_id=Yii::$app->user->id;
if($id==0) 
    return $this->redirect(['create']);

		$model=User::find()->where(['id'=>$id])->One();
     $my_upd=Yii::$app->user->identity->getParams($my_id);  
    $upd=Yii::$app->user->identity->getParams($id);

    
//print_r($my_upd);
                $model->school=$upd['school'];


        $month=(new \yii\db\Query())
        ->select('period')
        ->from('_change_mon')
        ->where('coper='.$model->school)
        ->One();

        $_SESSION['cur_period']=$month['period'];
		    $model->change_mod=$_SESSION['cur_period'];
            

		if(isset($_POST['User']))
		{   
		       $pass_old=$model->password;
			    $model->attributes=$_POST['User'];
		        if(isset($_POST['User']['username'])) {$sql='update users set username="'.$_POST['User']['username'].'" where id='.$id;
					$command=Yii::$app->db->createCommand($sql)->execute();};

		        if($model->password!=$pass_old){
                    $sql='update users set password="'.sha1(md5($model->login).md5($model->password)).'" where id='.$id;
					$command=Yii::$app->db->createCommand($sql)->execute();
                };
                if(isset($_POST['User']['role'])) {$upd['role']=$_POST['User']['role'];
                $sqls='update users set role="'.$upd['role'].'" where id='.$id;
                $command=Yii::$app->db->createCommand($sqls)->execute();
                };
                if($my_upd['role']==1){
                    
                        if(isset($_POST['User']['change_mod'])) {$sqls='update _change_mon set period="'.$_POST['User']['change_mod'].'" where coper='.$model->school;
                        $command=Yii::$app->db->createCommand($sqls)->execute();};
                        
                        $s1=Yii::$app->db->createCommand('update users set id_org="'.$_POST['User']['id_org'].'" where id='.$id)->execute();
                        $upd['org']=$_POST['User']['id_org'];
                        $tarz=Yii::$app->user->identity->setParams($id,$upd);
					
                 };			
              //  return $this->redirect(['site/lk?id='.$id]);
            } else echo 'noo';

		return $this->render('lk',array('model'=>$model,'upd'=>$upd,'my_upd'=>$my_upd));
	}
//-----------------------------------------------------------
public function actionCreate()
{
    $this->layout='menu_site';
    $model=new User;
    if(isset($_POST['User']))
    { 
        $model->attributes=$_POST['User'];
        $model->login=$_POST['User']['login'];
        $model->id_org=$_POST['User']['id_org'];
        $model->password=$_POST['User']['password'];
        $model->role=$_POST['User']['role'];
        $model->password=sha1(md5($model->login).md5($model->password));
        print_r($model->attributes);
        /*
        $model->login=$_POST['User']['login']; 
        $model->password=$_POST['User']['password']; 
        $model->password=sha1(md5($model->login).md5($model->password));
*/
        $sql='insert into users (id_org,username,login,password) 
                    values('.$model->id_org.',"'.$model->username.'","'.$model->login.'","'.$model->password.'");';
        $command=Yii::$app->db->createCommand($sql)->execute();
        
        
       // if($model->validate()) {
       // $model->save();
 
        $sqls='select kod from _school where id='.$model->id_org;
        $school=Yii::$app->db->createCommand($sqls)->queryOne();
        
        $upd=['role'=>$model->role,'school'=>$school['kod'],'org'=>$model->id_org,'iscensored'=>0];
        $model=User::find()->where(['username'=>$model->username,'login'=>$model->login])->one();
        print_R($model->id);
        Yii::$app->user->identity->setParams($model->id,$upd);
        return $this->redirect(['admin']);
   // }
    //    else print_r($model->geterrors());


     }
     return $this->render('create',array('model'=>$model));

}
    //------------------------------------------------------
    public function actionLogin()
    {
      
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        };


        $model = new User();

        if(isset($_POST['User'])) {
       
            if(stristr($_POST['User']['username'], '-') === FALSE)
                $model= new User;
                
            else {
            $ar=explode('-',$_POST['User']['username']);
            $org=_school::find()->where(['name'=>trim($ar[0])])->one();
            $name=trim($ar[1]);
        
            $model=User::find()->where(['username'=>$name,'id_org'=>$org->id])->one();
  
            };
            print_r($model->attributes);
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
               $session = Yii::$app->session;
                if ($session->isActive) {
                    $_SESSION['cur_god'] = date('Y');
                    $_SESSION['cur_mes'] = date('m');
                    $_SESSION['cur_period']=(6+($_SESSION['cur_god']-2010)*12+$_SESSION['cur_mes']);
                };
               
                 return $this->redirect(['/expo/list']);
            }    else print_r($model->geterrors());

        }
            return $this->render('login', [
                'model' => $model
            ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        /*if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }*/
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
