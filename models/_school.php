<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "_school".
 *
 * @property int $id
 * @property string $fullname
 * @property string $name
 */
class _school extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '_school';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullname', 'name'], 'required'],
            [['fullname'], 'string', 'max' => 250],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fullname' => 'Fullname',
            'name' => 'Name',
        ];
    }
}
