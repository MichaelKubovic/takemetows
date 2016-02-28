<?php
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'WS DNS records';

?>
<p class="hello">Hello Websupport! Below are all DNS records for <b><?php echo Yii::$app->params['ws']['domain'] ?></b> <a href="<?php echo Url::to(['record/create', 'domain' => $domainName]) ?>" class="btn btn-green btn-right">Create record</a></p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    // 'filterModel' => $searchModel,
    'columns' => [
        'type',
        'name',
        'content',
        'ttl'
    ],
]);
?>