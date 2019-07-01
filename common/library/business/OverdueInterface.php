<?php


namespace common\library\business;

/**
 *
 * Interface OverdueInterface
 * @package common\library\business
 */
interface OverdueInterface
{
    /**
     * @return int
     */
    public function getPeriod(): int;

    /**
     * @return int
     */
    public function getSignedAt(): int;


}