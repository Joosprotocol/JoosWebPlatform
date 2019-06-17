<?php

namespace common\behaviors;


use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class HashIdBehavior extends AttributeBehavior
{
    /** @var string */
    public $hashIdAttribute = 'hash_id';
    /** @var int */
    public $size = 16;

    const HEX_PREFIX = '0x';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->hashIdAttribute,
            ];
        }
    }

    /**
     * @param \yii\base\Event $event
     * @return string
     */
    protected function getValue($event)
    {
        return self::HEX_PREFIX . bin2hex(Yii::$app->getSecurity()->generateRandomString($this->size));
    }

}
