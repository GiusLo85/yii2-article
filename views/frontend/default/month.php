<?php
use yii\widgets\LinkPager;
?>
<?= date("Y-m-d", $firstDay);?>

<?php foreach($materials as $material):?>

<?= $material->title?>
</br>
<?php endforeach;?>

<?php
echo LinkPager::widget([
    'pagination' => $pages,
    'registerLinkTags' => true
]);
?>
