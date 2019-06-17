<?php

namespace common\library\exceptions;

use Throwable;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Class InvalidModelException
 */
class InvalidModelException extends Exception
{
    public function __construct(ActiveRecord $entity, $code = 0, Throwable $previous = null)
    {
        $message = var_export($entity->errors, true);
        parent::__construct($message, $code, $previous);
    }
}
