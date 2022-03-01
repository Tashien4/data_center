<div class="post">
	<div class="title">
		<h3><?php //print (isset($title))?($title):''; ?></h3>
		<?php //if (isset($params)) print_r($params); ?>
		<?php //foreach($param as $key=>$val) echo $key.' =>'.$val.'<br/>'; ?>
	</div>
</div>
<h3 color=red>Внимание ошибка!!</h3><h2 style="color:red;">
<?php foreach($error as $er)
echo $er[0];?></h2><br><BR>
<h2><a href="<?php echo Yii::$app->request->urlReferrer;?>" > Назад</a></h2>