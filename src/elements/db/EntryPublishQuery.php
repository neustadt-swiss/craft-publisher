<?php

namespace neustadt\publisher\elements\db;

use craft\elements\db\ElementQuery;
use craft\helpers\Db;

class EntryPublishQuery extends ElementQuery
{
    /**
     * @var int|array|null The entry ID(s) to query for.
     */
    public $sourceId;

    /**
     * @var int|array|null The entry site ID(s) to query for.
     */
    public $sourceSiteId;

    /**
     * @var int|array|null The draft ID(s) to query for.
     */
    public $publishDraftId;

    /**
     * @var \DateTime|null The DateTime to query for.
     */
    public $publishAt;

    /**
     * @var boolean|null
     */
    public $expire;

    /**
     * Filters the query results based on the element ID.
     *
     * @param int|array|null $value The element ID(s).
     * @return $this
     */
    public function sourceId(int $value)
    {
        $this->sourceId = $value;

        return $this;
    }

    /**
     * Filters the query results based on the element site ID.
     *
     * @param int|array|null $value The element site ID(s).
     * @return $this
     */
    public function sourceSiteId(int $value)
    {
        $this->sourceSiteId = $value;

        return $this;
    }

    /**
     * Filters the query results based on the DateTime.
     *
     * @param \DateTime|null $value The DateTime.
     * @return $this
     */
    public function publishAt(\DateTime $value)
    {
        $this->publishAt = $value;

        return $this;
    }

    /**
     * Filters the query results based on the expire value.
     *
     * @param bool|null $value The expire value.
     * @return $this
     */
    public function expire(bool $value)
    {
        $this->expire = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function beforePrepare(): bool
    {
        $this->joinElementTable('entrypublishes');

        $this->query->select(
            [
                'entrypublishes.*',
            ]
        );

        if ($this->sourceId !== null) {
            $this->subQuery->andWhere(Db::parseParam('entrypublishes.sourceId', $this->sourceId));
        }

        if ($this->sourceSiteId !== null) {
            $this->subQuery->andWhere(Db::parseParam('entrypublishes.sourceSiteId', $this->sourceSiteId));
        }

        if ($this->publishDraftId !== null) {
            $this->subQuery->andWhere(Db::parseParam('entrypublishes.publishDraftId', $this->publishDraftId));
        }

        if ($this->publishAt !== null) {
            $this->subQuery->andWhere(Db::parseDateParam('entrypublishes.publishAt', $this->publishAt, '<='));
        }

        if ($this->expire !== null) {
            $this->subQuery->andWhere(Db::parseParam('entrypublishes.expire', $this->expire));
        }

        return parent::beforePrepare();
    }
}
