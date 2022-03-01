<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fio".
 *
 * @property int $id
 * @property string $fam
 * @property string $im
 * @property string $ot
 * @property string $rod
 * @property string $snils
 * @property int $id_bls
 */
class Fio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          //  [['fam', 'im', 'ot', 'rod', 'snils', 'id_bls'], 'required'],
            [['rod'], 'safe'],
            [['pol'], 'integer'],
            [['fam', 'im', 'ot'], 'string', 'max' => 100],
            [['snils'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fam' => 'Фамилия',
            'im' => 'Имя',
            'ot' => 'Отчество',
            'rod' => 'Дата рождения',
            'snils' => 'СНИЛС',
            'pol' => 'Пол',

        ];
    }
    public function last_pasp_id($id){
        $sql='select max(id) as id from doks where id_fio='.$id.' and doktype <910';
        $connection=Yii::$app->db->createCommand($sql)->queryAll();
        if(isset($connection['id'])) $id=$connection['id']; else $id=0;
        return $id;
    }
    public function last_lg_id($id){
        $sql='select max(id) as id from doks where id_fio='.$id.' and doktype >905';
        if(isset($connection['id'])) $id=$connection['id']; else $id=0;
        return $id;
    }
}
