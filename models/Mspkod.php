<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "_mspkod".
 *
 * @property int $id
 * @property int $id_kodz
 * @property string $name
 * @property int $hasmun
 */
class Mspkod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '_mspkod';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id', 'id_kodz', 'hasmun'], 'integer'],
            [['name'], 'string', 'max' => 1000],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kodz' => 'Id Kodz',
            'name' => 'Name',
            'hasmun' => 'Hasmun',
        ];
    }
}
