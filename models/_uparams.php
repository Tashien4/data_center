<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "_uparams".
 *
 * @property int $id
 * @property int $id_user
 * @property string $name
 * @property string $type
 */
class _uparams extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '_uparams';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'name'], 'required'],
            [['id_user'], 'integer'],
            [['name'], 'string', 'max' => 1000],
           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'name' => 'Name',

        ];
    }
}
