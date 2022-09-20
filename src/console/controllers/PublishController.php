<?php

namespace goldinteractive\publisher\console\controllers;

use goldinteractive\publisher\Publisher;
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
