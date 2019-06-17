<?php

namespace common\models\loan\smartcontract;

use common\library\ethereum\BlockchainAPIInterface;
use common\library\exceptions\APICallException;
use common\library\exceptions\ParseException;
use yii\web\NotFoundHttpException;

class LoanManagerBlockChainAdapter
{
    const CONTRACT_NAME = 'JoosLoanManager';

    const FIELD_PERSONAL = 'personal';

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
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            [$loanId, $amount, $currencyType, $period, $percent, $initType]
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

        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            [$loanId, $lenderId, $lenderFullName, $borrowerId, $borrowerFullName, $personal]
        );
    }

    /**
     * @param integer $loanId
     * @param integer $status
     * @return object|string
     * @internal param $sad
     */
    public function setStatus($loanId, $status)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            [$loanId, $status]
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
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            []
        );
    }

    /**
     * @param integer $loanId
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isDeclared($loanId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$loanId]
        );
    }

    /**
     * @param integer $loanId
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isFull($loanId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$loanId]
        );
    }

    /**
     * @param integer $loanId
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isOverdue($loanId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$loanId]
        );
    }

    /**
     * @param integer $loanId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return integer
     */
    public function getStatus($loanId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$loanId]
        );
    }

    /**
     * @param integer $loanId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getLoanParticipants($loanId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$loanId]
        );
    }

    /**
     * @param integer $loanId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getLoanInfo($loanId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$loanId]
        );
    }

}
