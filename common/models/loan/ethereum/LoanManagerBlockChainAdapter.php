<?php

namespace common\models\loan\ethereum;

use common\library\ethereum\BlockchainAPIInterface;
use common\library\exceptions\APICallException;
use common\library\exceptions\ParseException;
use yii\web\NotFoundHttpException;

class LoanManagerBlockChainAdapter
{
    const CONTRACT_NAME = 'JoosLoanManager';

    private $blockchain;

    public function __construct(BlockchainAPIInterface $blockchain)
    {
        $this->blockchain = $blockchain;
    }

    /**
     * @param integer $loanId
     * @param integer $amount
     * @param integer $currencyType
     * @param integer $period
     * @param integer $percent
     * @param integer $initType
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function initLoan($loanId, $amount, $currencyType, $period, $percent, $initType)
    {
        return $this->blockchain->execute(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeSend(),
            __FUNCTION__,
            func_get_args()
        );
    }

    /**
     * @param integer $loanId
     * @param integer $lenderId
     * @param string $lenderFullName
     * @param integer $borrowerId
     * @param string $borrowerFullName
     * @param string $personal encoded JSON from user personal data
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function setLoanParticipants($loanId, $lenderId, $lenderFullName, $borrowerId, $borrowerFullName, $personal)
    {

        return $this->blockchain->execute(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeSend(),
            __FUNCTION__,
            func_get_args()
        );
    }

    /**
     * @param integer $loanId
     * @param integer $status
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function setStatus($loanId, $status)
    {

        return $this->blockchain->execute(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeSend(),
            __FUNCTION__,
            func_get_args()
        );
    }

    /**
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isOwner()
    {

        return $this->blockchain->execute(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeCall(),
            __FUNCTION__,
            func_get_args()
        );
    }

    /**
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isDeclared($loanId)
    {
        return $this->blockchain->execute(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeCall(),
            __FUNCTION__,
            func_get_args()
        );
    }

    /**
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isFull($loanId)
    {
        return $this->blockchain->execute(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeCall(),
            __FUNCTION__,
            func_get_args()
        );
    }

    /**
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isOverdue($loanId)
    {
        return $this->blockchain->execute(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeCall(),
            __FUNCTION__,
            func_get_args()
        );
    }

    /**
     * @param integer $loanId
     * @return false|mixed
     */
    public function getStatus($loanId)
    {
        return $this->blockchain->execute(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeCall(),
            __FUNCTION__,
            func_get_args()
        );
    }

}
