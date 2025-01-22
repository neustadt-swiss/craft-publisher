<?php

namespace neustadt\publisher\controllers;

use craft\web\Controller;
use neustadt\publisher\Publisher;

/**
 * Class ApiController
 *
 * @package neustadt\publisher\controllers
 */
class ApiController extends Controller
{
    /**
     * @inheritdoc
     */
    protected array|int|bool $allowAnonymous = ['publish'];

    /**
     * Publishes or expires all due entries.
     *
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionPublish()
    {
        $result = Publisher::getInstance()->entries->publishDueEntries();

        return $this->asJson($result);
    }
}
