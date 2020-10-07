<?php


namespace GlobalPayments\Api\Utils;


class AccountNameManager
{
    public $transactionProcessingAccount;
    public $disputeManagementAccount;
    public $settlementReportingAccount;
    public $tokenizationAccount;

    /**
     * AccountNameManager constructor.
     * @param $transactionProcessingAccount
     * @param $disputeManagementAccount
     * @param $settlementReportingAccount
     * @param $tokenizationAccount
     */
    public function __construct(
        $transactionProcessingAccount = 'Transaction_Processing',
        $disputeManagementAccount = 'Dispute Management',
        $settlementReportingAccount = 'Settlement Reporting',
        $tokenizationAccount = 'Tokenization'
    ) {
        $this->transactionProcessingAccount = $transactionProcessingAccount;
        $this->disputeManagementAccount = $disputeManagementAccount;
        $this->settlementReportingAccount = $settlementReportingAccount;
        $this->tokenizationAccount = $tokenizationAccount;
    }


}