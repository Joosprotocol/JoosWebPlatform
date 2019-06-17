<?php

namespace common\models\collateral\smartcontract;

use common\library\ethereum\BlockchainAPIInterface;
use common\library\exceptions\APICallException;
use common\library\exceptions\ParseException;
use yii\web\NotFoundHttpException;

class CollateralLoanManagerBlockChainAdapter
{
    const CONTRACT_NAME = 'JoosCollateralLoanManager';

    const FIELD_PERSONAL = 'personal';

    private $blockchain;

    public function __construct(BlockchainAPIInterface $blockchain)
    {
        $this->blockchain = $blockchain;
    }

    /**
     * @param string $collateralLoanHashId
     * @param string $collateralHashId
     * @param integer $loanAmount
     * @param integer $loanCurrencyType
     * @param integer $collateralLoanAmount
     * @param integer $collateralLoanCurrencyType
     * @param integer $period
     * @param integer $fee
     * @return object|string
     */
    public function initLoan(string $collateralLoanHashId, string $collateralHashId, int $loanAmount, int $loanCurrencyType, int $collateralLoanAmount, int $collateralLoanCurrencyType, int $period, int $fee)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            [$collateralLoanHashId, $collateralHashId, $loanAmount, $loanCurrencyType, $collateralLoanAmount, $collateralLoanCurrencyType, $period, $fee]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @param int $isPlatform
     * @param integer $borrowerId
     * @param string $borrowerFullName
     * @param integer $lenderId
     * @param string $lenderFullName
     * @return object|string
     */
    public function setLoanParticipants(string $collateralLoanHashId, int $isPlatform, int $borrowerId, string $borrowerFullName, int $lenderId = 0, string $lenderFullName = '')
    {

        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            [$collateralLoanHashId, $isPlatform, $borrowerId, $borrowerFullName, $lenderId, $lenderFullName]
        );
    }


    /**
     * @param string $collateralLoanHashId
     * @param integer $amount
     * @return object|string
     */
    public function createPayment(string $collateralLoanHashId, int $amount)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            [$collateralLoanHashId, $amount]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @return object|string
     */
    public function setAsWithdrawn(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            [$collateralLoanHashId]
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
     * @param string $collateralLoanHashId
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isDeclared(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @return bool
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isFull(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function calculateLoanAmountToPay(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @param int $number
     * @return string
     */
    public function getCollateralLoanHashId(string $collateralLoanHashId, int $number)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId, $number]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @return int
     */
    public function getCollateralLoanCount(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getLoanInfo(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getLoanCollateralHashId(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }



    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getCollateralStatus(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getStatus(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getCollateralInfo(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getLoanParticipants(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return object
     */
    public function getPaymentInfo(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return bool
     */
    public function isOverdue(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return int
     */
    public function getPaymentsTotalAmount(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }

    /**
     * @param string $collateralLoanHashId
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     * @return bool
     */
    public function isPaid(string $collateralLoanHashId)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractCall(),
            __FUNCTION__,
            [$collateralLoanHashId]
        );
    }
}
