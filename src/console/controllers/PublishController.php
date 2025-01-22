<?php

namespace neustadt\publisher\console\controllers;

use neustadt\publisher\Publisher;
use yii\console\Controller;

class PublishController extends Controller
{
    /**
     * Publishes the due entries.
     *
     * @throws \Throwable
     */
    public function actionIndex()
    {
        Publisher::getInstance()->entries->publishDueEntries();

        return;
    }
}
