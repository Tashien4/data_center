<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
class User extends ActiveRecord implements IdentityInterface
{
    public $user = false;
    private $_model;
    public $iscensored=0;
    public $school;
    public $change_mod;


    public static function tableName()
    {
        return 'users';
    }
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['username', 'password','login'], 'string', 'max' => 50],
            [['id_org','role'],'integer'],
            ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return [
            
            'username' => 'ФИО пользователя',
            'password' => 'Пароль',
            'login'=>'Логин',
            'id_org'=>'Организация',
            'role'=>'Роль',
            'iscensored'=>'Данные проверены и готовы к выгрузке'
        ];
    }
     //---------------------------------------------------

public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if(!$this->getUser())
            {
           $this->addError($attribute, 'Неверный логин или пароль');
            } 
        }
    }
 //--------------------------------------------------   
 //---------------------------------------------------
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
//-------------------------------------------------
 public static function findIdentity($id)
 {
     return static::findOne($id);
 }
 
 public function getId()
 {
     return $this->id;
 }
 
 public static function 
 findIdentityByAccessToken($token, $type = null)
 {
   
 }
 
 public function getAuthKey()
 {
    
 }

 public function validateAuthKey($authKey)
 {
   
 }

 public function login()
    {
        if ($this->validate()) {
        return Yii::$app->user->login($this->getUser());
        }
    }
//--------------------------------------------------
    public function getUser()
    {
      if ($this->user === false) { //если пользователь существует
        //определяем пользователя по логину и шифрованному хешу
            $this->user = User::findOne(['login'=>$this->login,
                'password'=>sha1(md5($this->login).md5($this->password))]);
      }       
     return $this->user;
   }
 //-------------------------------------------------------  
 public function getCenser(){
    $id_us=Yii::$app->user->id;
    $user = $this->loadUser($id_us);
    $censer=0;
    if ($user) {	
        $upar=_uparams::find()->where(['id_user'=>$id_us])->one();//ищем пользователя

                $text=unserialize($upar->name);   //смотрим параметры
                $school_1=$text['school'];        //определяем из какой школы
      
                $iscensered = (new \yii\db\Query())    
                    ->select('iscensered')
                    ->from('_school')
                    ->where('kod='.$school_1.' and type=1')
                    ->One();

//return (array('-7'=>count($upar),'*'=>((isset($upar))?'+':'-'),'valid'=>$upar->geterrors()));
   if (isset($upar)) {
            return $iscensered['iscensered'];//(unserialize($upar->name));
        } else return (['-2'=>'Не найдена строка: id_user=>'.$id_us]);
//		return intval($user->usersadmin) ;
    } else {
        return (['-1'=>'Не найден пользователь: id_user=>'.$id_us]);
    }

  }
//----------------------------------------------------------
protected function loadUser($id=null)
{
    if(($this->_model===null) or ($id!==null and $this->id!=$id))
    {
        if($id!==null)
            $this->_model=User::findOne($id);;
    }
    return $this->_model;
}
//-------------------------------------------------------------
   function getParams($id=0){
       if($id==0)
    $id_us=Yii::$app->user->id;
    else $id_us=$id;
    $user = $this->loadUser($id_us);
$upar=array();
    if ($user) {	
        $upar['school']=($user['cur_org']>0?$user['cur_org']:$user['id_org']);
        $upar['role']=$user['role'];
        $upar['org']=$user['id_org'];
        $upar['cur_sc']=$user['cur_org'];
         //if (isset($upar)) {
             return $upar;
        // } else return (['-2'=>'Не найдена строка: id_user=>'.$id_us]);

     } else {
         return (['-1'=>'Не найден пользователь: id_user=>'.$id_us]);
     }
   }
 //---------------------------------------------------------------
       
 function setParams($id=0,$param){

    if($id==0)
    $id_us=Yii::$app->user->id;
    else $id_us=$id;
    $user = $this->loadUser($id_us);
    if ($user) {	
        $upar=_uparams::find()->where(['id_user'=>$id_us])->one();//ищем пользователя
 //return (array('-6'=>count($upar),'*'=>((isset($upar)))?'+':'-'));
         if (!(isset($upar)) or !($upar->id>0)) {
             $upar=New _uparams;
             $upar->id_user=$id_us;
             
 //return (array('-6'=>count($upar),'*'=>((isset($upar)))?'+':'-'));
         }
         
         if (isset($upar)) {
             $upar->name=serialize($param);
 //$upar->validate();
 //return (array('-7'=>count($upar),'*'=>((isset($upar))?'+':'-'),'valid'=>$upar->geterrors()));
             if ($upar->validate())
                 $upar->save();
             else return ($upar->geterrors());
         }
             return (['-2'=>'Не найдена строка: id_user=>'.$id_us]);
         
 //		return intval($user->usersadmin) ;
     } else {
         return (['-1'=>'Не найден пользователь: id_user=>'.$id_us]);
     }
   }   

   //---------------------------------------------
   //---------------------------------------------------------------
       
 function setCenserParams($id=0,$param){

    if($id==0)
    $id_us=Yii::$app->user->id;
    else $id_us=$id;
    $user = $this->loadUser($id_us);
    if ($user) {	
/*
        $upar=Use::find()->where(['id_user'=>$id_us])->one();//ищем пользователя
        $upd=unserialize($upar->name);*/

        $school_id = (new \yii\db\Query())    //находим всех из этой школы
        ->select('id')
        ->from('_school')
        ->where('kod='.$user->cur_org)
        ->One();
//foreach($school_id as $s){
        $up=_school::find()->where(['id'=>$school_id['id']])->one();
        $up->iscensered=$param;

//}; */
if ($up->validate())
                 $up->save();
             else return ($up->geterrors());
};

   }   

   //---------------------------------------------
   function getSchool(){
    $id_us=Yii::$app->user->id;
    $user = $this->loadUser($id_us);
    $aparam=$this->getParams();	

    $school=((isset($aparam['school']))?$aparam['school']:0);   
     if ($user) {	
        return (intval($school)>0)?($school):99;
    } else {
        return 0;
    }
  }
    //---------------------------------------------
    public function isAdmin(){
        $id_us=Yii::$app->user->id;
        $user = $this->loadUser($id_us);
        $aparam=$this->getParams();	
    
        $role=((isset($aparam['role']))?$aparam['role']:0);   
         if ($user) {	
            return $role;
        } else {
            return 0;
        }
      }
      //--------------------------------------------
      public function sp_org(){
$ret=array();
        $sql='select id,name
        from _school';
        $prows=Yii::$app->db->createCommand($sql)->queryAll();
        foreach($prows as $row)
            $ret[$row['id']]=$row['name'];
        return $ret;
      }
      //----------------------------------------------------------

//------------------------------------------
public function search($params,$upd)
{
    $query = User::find();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);
    $per=$_SESSION['cur_period'];  
  print_r( $this->validate());

    if (!($this->load($params) && $this->validate())) {
        return $dataProvider;
    }

    // adjust the query by adding the filters
    $query->andFilterWhere(['username' => $this->username])
    ->andFilterWhere(['like', 'login', $this->login])
          ->andFilterWhere(['like', 'id_org', $this->id_org]);
          
     //     ->andFilterWhere(['like', 'type_name.name', $this->type_name]);

    return $dataProvider;
}
//------------------------------------
}