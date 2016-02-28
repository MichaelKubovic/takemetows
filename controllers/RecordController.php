<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Domain;
use app\models\Record;
use yii\data\ArrayDataProvider;

class RecordController extends Controller
{
    public function actionIndex()
    {
        $domainName = \Yii::$app->params['ws']['domain'];
        $domain = Domain::findOne($domainName);

        $recordsData =  new ArrayDataProvider(['allModels' => $domain->getRecords()]);

        return $this->render('index', [
            'dataProvider' => $recordsData,
            'domainName'   => $domain->name
        ]);
    }

    public function actionCreate()
    {
        $domainName = Yii::$app->request->get('domain');
        if (empty($domainName)) {
            throw new \Exception('Missing domain name.');
        }

        $domain = Domain::findOne($domainName);

        $record = new Record();
        $record->setParentResource(['domain' => $domain]);

        $errors = [];
        if ($record->load(Yii::$app->request->post()) && $record->validate()) {
            $result = $record->create();

            if ($result) {
                /**
                 * @todo  set success session
                 */
                $this->redirect('index');
            }
        }

        return $this->render('create', [
            'record' => $record,
            'domain' => $domain
        ]);
    }
}