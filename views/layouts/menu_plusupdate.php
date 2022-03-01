<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use app\models\Site;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100" style="font-size: 12px;background: radial-gradient( #ffffff,#ebebeb);">
<?php $this->beginBody() ?>

<header>
    <?php 
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'renderInnerContainer'=>false,
        'options' => [
            'id'=>'w1',
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Личный кабинет', 'url' => ['/site/lk']],
            ['label' => 'Реестр', 'url' => ['/expo/list']],
             Yii::$app->user->isGuest ? (
                ['label' => 'Вход', 'url' => ['/site/login']]
            ) : ( ['label' => 'Выход (' . Yii::$app->user->identity->username . ')', 'url' => ['/site/logout']]),
            Yii::$app->user->isGuest ?
                ['label' => '', 'url' => ['/site/index']]:
                 Yii::$app->user->identity->isAdmin() ? 
                ['label' => 'Администрирование', 'url' => ['/site/admin']]:
                ['label' => '', 'url' => ['/site/index']],
               /* '<li class="nav-item">'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                . Html::submitButton(
                    
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'*/
            
        ],
    ]);
    NavBar::end();
    ?>
</header>
<style>
.container{max-width: 95%;padding-right: 0px;padding-left: 5%;}
.row {justify-content: flex-end;}
.col-sm-3 {text-align:center;flex: 0 0 15%;padding-left: 5px;font-size: 12px;}
.col-sm-9 {flex: 0 0 100%;max-width: 85%;padding-right: 5px;}
.col-sm-3 li:hover {cursor: pointer;background:#f7f7f7;}
.col-sm-3 a{padding:10px;color:black;text-decoration: none;}
.navbar {min-height: 50px !important;}
.nav > li > a:hover, .nav > li > a:focus {
    text-decoration: none;
    background-color: transparent;
}
#w1-collapse {font-size: 15px;}
#w1 {border-radius:0px;}
.container {padding-top: 1px;    margin-top: 1px;}
</style>
    <div class="container" style="padding-top: 80px;width:100%">
        <div class="row">
            <div class="col-sm-9">
            <?= $content ?>
            </div>
            <?php 
                        $upd=Yii::$app->user->identity->getParams('data_sbor',Yii::$app->user->id);?>
            <div class="col-sm-3">
                <ul class="list-group">
                <!--li class="list-group-item" onclick="document.location.href='separate?id=<?php //echo $_GET['id'];?>'">Разделить</li-->
                    <li class="list-group-item" onclick="document.location.href='statist'">Статистика</li>
                    <li class="list-group-item" onclick="document.location.href='create'">Дополнить список</li>
                    <!--li class="list-group-item" onclick="document.location.href='all_people'">Свод по категориям</li-->
                    <li class="list-group-item" onclick="document.location.href='<?php if($upd['role']==1) echo 'prilall';else echo 'pril4';?>'">СВОД Приложение 4</li>
       
                    <?php
                        if($upd['role']==1)
                        echo '</ul><br><ul class="list-group">
                            <li class="list-group-item" onclick="document.location.href=\'repl_by_coper?id_period='.($_SESSION['cur_period']+1).'\'">Перевести на '.Site::fromperiod($_SESSION['cur_period']+1,1).'</li>
                            <li class="list-group-item" onclick="document.location.href=\'sout\'">Выгрузка xml</li>
                            <li class="list-group-item" onclick="document.location.href=\'tarif\'">Тарифы</li>
                        ';
                    ?>
                </ul>
            </div>
            </div>
    </div>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
    <p class="float-left">&copy; Администрация Серовского городского округа <?= date('Y') ?></p>
        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
