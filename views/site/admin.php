<?php 

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use app\models\Site;
use app\models\LoginForm;
use app\models\User;

?>
<?php $form = ActiveForm::begin(); ?>


<a href='create'><h3>Добавить пользователя</h3></a>
<?php
        
        $searchModel=new LoginForm;
        $dataProvider = $searchModel->search(Yii::$app->request->get(),$upd);

        $prows=Yii::$app->db->createCommand("
        select id,name
        from _school")->queryAll();
        foreach($prows as $r)
             $org[$r['id']]=$r['name'];

        $sp_role=['0'=>'Пользователь','1'=>'Администратор'];
      echo GridView::widget([
         'dataProvider' => $dataProvider,
         'filterModel' => $searchModel,
         'columns' => [
             ['class' => 'yii\grid\SerialColumn'],
            'username',
            [
                'attribute' => 'role',
                'format' => 'raw',
                
                'value' => function ($data) {
                    $sp_role=['0'=>'Пользователь','1'=>'Администратор'];
                    $upd=Yii::$app->user->identity->getParams('data_sbor',$data->id);
                    return $sp_role[$upd['role']];
                },'filter' =>$sp_role,
            ],
            'login',
            /* [
                 'attribute' => 'fio',
                 'format' => 'raw',
                 'value' => function ($data) {
                     return $data->efio["fam"]." ".$data->efio["im"]." ".$data->efio["ot"]." (".Site::rusdat($data->efio["rod"]).")";
                 },
             ], */
             [
                 'attribute' => 'id_org',
                 'format' => 'raw',
                 'value' => function ($data) {
                     $prows=Yii::$app->db->createCommand("
                     select name
                     from _school
                     where id=".$data->id_org)->queryOne();
                     return $prows['name'];
                 },
                 'filter' =>$org
             ],
           /*  [
                 'attribute' => 'id_kmsasgo',
                 'format' => 'raw',
                 'value' => function ($data) {
                     return $data->kmsasgo["kat"];
                 },
             ],
 
             //'snils',
             //'id_fio',
             //'actu_dok',
             //'actu_lgdok',
             //'data_resh',
             [
                 'attribute' => 'prim',
                 'format' => 'raw',
                 'value' => function ($data) {
                     $sp_klass=['-5'=>'Ясельная группа',
                     '-4'=>'Младшая группа',
                     '-3'=>'Средняя группа',
                     '-2'=>'Старшая группа',
                     '-1'=>'Подготовительная гр.',
                     '0'=>''];
                     return (((int)$data->prim>0)?$data->prim:$sp_klass[$data->prim]);
                 },
             ],
           */
            
            // ['class' => 'yii\grid\ActionColumn'],
             ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}','headerOptions' => ['style' => 'width:8%'],
             'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'update') {
                    $url ='lk?id='.$model->id;
                    return $url;
                }
                if ($action === 'delete') {
                    $url ='delete?id='.$model->id;
                    return $url;
                }
              }
            ]
            ],
        ]);  ?>
   <?php ActiveForm::end(); ?>
