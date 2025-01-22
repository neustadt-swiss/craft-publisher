<?php

namespace neustadt\publisher\migrations;

use craft\db\Migration;

/**
 * Class Install
 *
 * @package neustadt\publisher\migrations
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $hasEntryPublishTable = $this->db->tableExists('{{%entrypublishes}}');

        // Create tables
        if (!$hasEntryPublishTable) {
            $this->createTable(
                '{{%entrypublishes}}',
                [
                    'id'             => $this->integer()->notNull(),
                    'sourceId'       => $this->integer()->notNull(),
                    'sourceSiteId'   => $this->integer(),
                    'publishDraftId' => $this->integer(),
                    'publishAt'      => $this->dateTime()->notNull(),
                    'expire'         => $this->boolean()->defaultValue(false),
                    'dateCreated'    => $this->dateTime()->notNull(),
                    'dateUpdated'    => $this->dateTime()->notNull(),
                    'uid'            => $this->uid(),
                    'PRIMARY KEY([[id]])',
                ]
            );
        }
        // Add foreign keys
        if (!$hasEntryPublishTable) {
            $this->addForeignKey(null, '{{%entrypublishes}}', ['id'], '{{%elements}}', ['id'], 'CASCADE', null);
            $this->addForeignKey(null, '{{%entrypublishes}}', ['sourceId'], '{{%elements}}', ['id'], 'CASCADE', null);
            $this->addForeignKey(
                null,
                '{{%entrypublishes}}',
                ['publishDraftId'],
                '{{%drafts}}',
                ['id'],
                'CASCADE',
                'CASCADE'
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTableIfExists('{{%entrypublishes}}');

        return true;
    }
}
