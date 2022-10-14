<?php

namespace goldinteractive\publisher;

use craft\base\Element;
use craft\base\Plugin;
use Craft;
use craft\elements\Entry;
use craft\events\DefineHtmlEvent;
use craft\events\ElementEvent;
use craft\services\Elements;
use craft\web\twig\variables\CraftVariable;
use goldinteractive\publisher\services\Entries;
use yii\base\Event;

/**
 * @author    Gold Interactive
 * @package   Publisher
 * @since     0.1.0
 *
 * @property \goldinteractive\publisher\services\Entries $entries
 *
 */
class Publisher extends Plugin
{
    /**
     * @inheritdoc
     */
    public string $schemaVersion = '2.0.6';

    public function init()
    {
        parent::init();

        $this->setComponents(
            [
                'entries' => Entries::class,
            ]
        );

        if (Craft::$app instanceof \craft\console\Application) {
            $this->controllerNamespace = 'goldinteractive\publisher\console\controllers';
        }

        if (Craft::$app->getRequest()->getIsCpRequest()) {
            Event::on(
                CraftVariable::class,
                CraftVariable::EVENT_INIT,
                function (Event $event) {
                    $variable = $event->sender;
                    $variable->set('publisherEntries', Entries::class);
                }
            );

            Event::on(
                Elements::class,
                Elements::EVENT_AFTER_SAVE_ELEMENT,
                function (ElementEvent $event) {
                    if ($event->element instanceof Entry) {
                        if ($event->element->isCanonical) {
                            return self::getInstance()->entries->onSaveEntry($event->element);
                        }
                    }
                }
            );

            Event::on(
                Element::class,
                Element::EVENT_DEFINE_SIDEBAR_HTML,
                function (DefineHtmlEvent $event) {
                    $element = $event->sender;

                    if ((get_class($element) == Entry::class)) {
                        $isNew = $element->id === null;

                        if ($isNew) {
                            return;
                        }

                        if ($element->getIsDraft()) {
                            $element = $element->getCanonical();
                        }

                        $event->html .= Craft::$app->view->renderTemplate(
                            'gold-publisher/_cp/entriesEditRightPane',
                            [
                                'permissionSuffix' => ':' . $element->getSection()->uid,
                                'entry'            => $element,
                            ]
                        );
                    }
                }
            );
        }
    }
}
