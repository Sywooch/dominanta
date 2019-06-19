<?php

/* @var $this \yii\web\View */
/* @var $content string */

use Yii;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\assets\SiteAsset;

SiteAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?= Html::csrfMetaTags() ?>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

  <?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
