<?php

namespace neustadt\publisher\controllers;

use Craft;
use craft\elements\Entry;
use craft\errors\ElementNotFoundException;
use craft\helpers\DateTimeHelper;
use craft\web\Controller;
use neustadt\publisher\elements\EntryPublish;
use neustadt\publisher\Publisher;
use yii\web\NotFoundHttpException;

/**
 * Class EntriesController
 *
 * @package neustadt\publisher\controllers
 */
class EntriesController extends Controller
{
    /**
     * Saves an EntryPublish.
     *
     * @throws ElementNotFoundException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionSave()
    {
        $this->requirePostRequest();

        $draftId = Craft::$app->request->post('publisher_draftId');
        $publishAt = Craft::$app->request->post('publisher_publishAt');
        $siteId = Craft::$app->request->post('publisher_sourceSiteId');

        $draft = Entry::find()
            ->draftId($draftId)
            ->siteId($siteId)
            ->status(null)
            ->one();

        if ($draft === null) {
            throw new \Exception('Invalid entry draft ID: '.$draftId);
        }

        $entry = Craft::$app->entries->getEntryById($draft->getCanonicalId(), $siteId);

        if ($entry === null) {
            throw new ElementNotFoundException("No element exists with the ID '{$draft->getCanonicalId()}'");
        }

        if ($draft->enabled) {
            $this->requirePermission('saveEntries:'.$entry->section->uid);
        }

        if ($publishAt !== null) {
            $publishAt = DateTimeHelper::toDateTime($publishAt, true);
        }

        $model = new EntryPublish();
        $model->sourceId = $entry->id;
        $model->publishDraftId = $draft->draftId;
        $model->publishAt = $publishAt;
        $model->sourceSiteId = $siteId;

        if (!Publisher::getInstance()->entries->saveEntryPublish($model)) {
            Craft::$app->getUrlManager()->setRouteParams(
                [
                    'publisherEntry' => $model,
                ]
            );
        }
    }


    /**
     * Deletes the EntryPublish.
     *
     * @return bool
     * @throws \Throwable
     */
    public function actionDelete()
    {
        $entriesService = Publisher::getInstance()->entries;
        $publishEntryId = Craft::$app->request->getQueryParam('sourceId');

        if ($publishEntryId === null) {
            throw new NotFoundHttpException('EntryPublish not found');
        }

        $entryPublish = $entriesService->getEntryPublishById($publishEntryId);

        if ($entryPublish !== null) {
            $entry = $entryPublish->getEntry();

            $entriesService->deleteEntryPublish($publishEntryId);
            $this->redirect($entry->getCpEditUrl());

            return true;
        }

        throw new NotFoundHttpException('EntryPublish not found');
    }
}
