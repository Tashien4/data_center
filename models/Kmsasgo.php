<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kmsasgo".
 *
 * @property int $id
 * @property int $id_mspa
 * @property int $mspkod
 * @property string $mspkat
 * @property string $krat
 * @property string $kat
 * @property string $mspuro
 * @property string $mspisto
 * @property string $mspform
 * @property string $msprubr
 * @property string $kbk
 * @property string $prim
 * @property int $autor
 * @property string $needscriteria
 * @property int $needmama 1-dopfio и id_fio меняются при выгрузке, 2 - состав семьи
 * @property int $id_komp
 * @property string $data_start
 * @property string $data_zaya
 * @property string $data_oko
 * @property int $nocensored
 * @property float $tarif
 * @property int $okei
 * @property float $proz778
 * @property float $koef_rp коэффициент родительской платы
 * @property int $is_invalid 1- инвалид (ОВЗ),   2 - инв.на дому
 */
class Kmsasgo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kmsasgo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_mspa', 'mspkat', 'krat', 'kat', 'mspisto', 'mspform', 'kbk', 'prim', 'needscriteria'], 'required'],
            [['id_mspa', 'mspkod', 'autor', 'needmama', 'id_komp', 'nocensored', 'okei', 'is_invalid'], 'integer'],
            [['data_start', 'data_zaya', 'data_oko'], 'safe'],
            [['tarif', 'proz778', 'koef_rp'], 'number'],
            [['mspkat', 'msprubr'], 'string', 'max' => 8],
            [['krat'], 'string', 'max' => 5],
            [['kat', 'prim'], 'string', 'max' => 1000],
            [['mspuro', 'mspform'], 'string', 'max' => 2],
            [['mspisto'], 'string', 'max' => 4],
            [['kbk'], 'string', 'max' => 20],
            [['needscriteria'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_mspa' => 'Id Mspa',
            'mspkod' => 'Mspkod',
            'mspkat' => 'Mspkat',
            'krat' => 'Krat',
            'kat' => 'Kat',
            'mspuro' => 'Mspuro',
            'mspisto' => 'Mspisto',
            'mspform' => 'Mspform',
            'msprubr' => 'Msprubr',
            'kbk' => 'Kbk',
            'prim' => 'Prim',
            'autor' => 'Autor',
            'needscriteria' => 'Needscriteria',
            'needmama' => 'Needmama',
            'id_komp' => 'Id Komp',
            'data_start' => 'Data Start',
            'data_zaya' => 'Data Zaya',
            'data_oko' => 'Data Oko',
            'nocensored' => 'Nocensored',
            'tarif' => 'Tarif',
            'okei' => 'Okei',
            'proz778' => 'Proz778',
            'koef_rp' => 'Koef Rp',
            'is_invalid' => 'Is Invalid',
        ];
    }
}
