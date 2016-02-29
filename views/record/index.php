<?php
use yii\grid\GridView;
use yii\grid\ActionColumn;
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
        'ttl',

        [
        	'class' => ActionColumn::className(),
        	// 'template' => '{delete}',
        	'buttons' => [
        		'delete' => function($url, $model, $key) use ($domainName) {
        			$url .= '&domain=' . $domainName;
        			return "<a href='$url' class='btn btn-red'>Delete</a>";
        		}
        	]
        ]
    ],
]);
?>