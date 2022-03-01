<?php

namespace app\models;

use Yii;


class Histarif extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'histarif';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          //  [['dist', 'data', 'end', 'for_class', 't1', 't2', 't3', 't4', 't5', 't6', 't7', 't8', 't9', 't10', 't11', 't12'], 'required'],
            [['dist'], 'integer'],
            [['data', 'end'], 'safe'],
            [['t1', 't2', 't3', 't4', 't5', 't6', 't7', 't8', 't9', 't10', 't11', 't12'], 'number'],
            [['for_class'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dist' => ' ',
            'data' => 'data',
            'end' => 'End',
            'for_class' => 'For Class',
            't1' => 'T1',
            't2' => 'T2',
            't3' => 'T3',
            't4' => 'T4',
            't5' => 'T5',
            't6' => 'T6',
            't7' => 'T7',
            't8' => 'T8',
            't9' => 'T9',
            't10' => 't10',
            't11' => 'T11',
            't12' => 'T12',
        ];
    }
}
