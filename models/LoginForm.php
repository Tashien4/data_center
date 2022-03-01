<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class LoginForm extends ActiveRecord
{

    public $user = false;
    private $_model;
    public $iscensored=0;
    public $school;
    public $change_mod;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password','login','role'], 'string', 'max' => 50],
            [['id_org','role'],'integer'],
            // username and password are both required
           // [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
          //  ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
           // ['password', 'validatePassword'],
        ];
    } 
    public static function tableName()
    {
        return 'users';
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
    //------------------------------------------
    public function search($params,$upd)
    {
        $query = LoginForm::find();
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $per=$_SESSION['cur_period'];  

    
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
    
        // adjust the query by adding the filters
        $query->andFilterWhere(['role' => $this->role])
        ->andFilterWhere(['like', 'username', $this->username])
        ->andFilterWhere(['like', 'login', $this->login])
              ->andFilterWhere(['like', 'id_org', $this->id_org]);
              
         //     ->andFilterWhere(['like', 'type_name.name', $this->type_name]);
    
        return $dataProvider;
    }
    //------------------------------------
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
