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
     * @param string $hashId
     * @param integer $amount
     * @param integer $currencyType
     * @param integer $period
     * @param integer $fee
     * @param integer $initType
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function initLoan($hashId, $amount, $currencyType, $period, $fee, $initType)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            [$hashId, $amount, $currencyType, $period, $fee, $initType]
        );
    }

    /**
     * @param string $hashId
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
    public function setLoanParticipants($hashId, $lenderId, $lenderFullName, $borrowerId, $borrowerFullName, $personal)
    {

        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            [$hashId, $lenderId, $lenderFullName, $borrowerId, $borrowerFullName, $personal]
        );
    }

    /**
     * @param string $loanHashId
     * @param integer $amount
     * @return object|string
     */
    public function createPayment(string $loanHashId, int $amount)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            [$loanHashId, $amount]
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
     * @param string $hashId
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isDeclared($hashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$hashId]
        );
    }

    /**
     * @param string $hashId
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isFull($hashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$hashId]
        );
    }

    /**
     * @param string $hashId
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isOverdue($hashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$hashId]
        );
    }

    /**
     * @param string $hashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return integer
     */
    public function getStatus($hashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$hashId]
        );
    }

    /**
     * @param string $hashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getLoanParticipants($hashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$hashId]
        );
    }

    /**
     * @param string $hashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getLoanInfo($hashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$hashId]
        );
    }

}
