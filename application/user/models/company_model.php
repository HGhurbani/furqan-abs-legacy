<?php
class Company_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getCompanies()
    {
        //active groups
        $adminGroupRow 			= array();
        $sqlAirlineQuery        = "SELECT * FROM {PRE}Company ORDER BY nId";
        session_start();
        $_SESSION['sess_query'] = $sqlAirlineQuery;
        $adminGroupList 		= $this->db->query($sqlAirlineQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if

        return ($adminGroupRow);
    }//end function

	function getCorresCompany()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}Company");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateCompany($data,$dataEdit)
	{
		$data 	= array('vTitle' => $this->input->post('txtTitle'),'vCode' => $this->input->post('txtCode'),
						'vAddress' => $this->input->post('txtAddress'),'vAddress2' => $this->input->post('txtAddress2'),
						'nCountry' => $this->input->post('ddlCountry'),'nDivision' => $this->input->post('ddlDivision'),
                        'nLocation' => $this->input->post('ddlLocation'),
						'vPhone' => $this->input->post('txtPhone'),'vFax' => $this->input->post('txtFax'),
						'vEmail' => $this->input->post('txtEmail'),'nCurrency' => $this->input->post('ddlCurrency'),
						'vWeb' => $this->input->post('txtWeb'),'dFromDate' => $this->input->post('txtFromDate'),
						'dToDate' => $this->input->post('txtToDate'),'vActive' => $this->input->post('chkActive'));
		$dataEdit = array_merge((array)$data, (array)$dataEdit);	
		$this->db->where('nId',$this->txtId);
		$this->db->update('{PRE}Company', $dataEdit); 

//update columns
		$currency_decimal = $this->common_model->get_field_name('Currency','vDecimal'," WHERE nId = '".$this->input->post('ddlCurrency')."' ");
		$full_len_currency = 15 + $currency_decimal;

$this->db->query("ALTER TABLE {PRE}AcmEntry MODIFY COLUMN vAmountDC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}AcmEntryDetails MODIFY COLUMN nAmount decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;");
$this->db->query("ALTER TABLE {PRE}AdmEntry MODIFY COLUMN vAmountDC decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}AdmEntryDetails MODIFY COLUMN nAmount decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Agent MODIFY COLUMN vServCalc decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Agent MODIFY COLUMN vServCal decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Agent MODIFY COLUMN vDiscCalc decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Agent MODIFY COLUMN vDiscCal decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Agent MODIFY COLUMN vAmount decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}AgentAccount MODIFY COLUMN vAgentAmt decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Airline MODIFY COLUMN nTkt decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Airline MODIFY COLUMN nXo decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Airline MODIFY COLUMN nGsa decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BankBook MODIFY COLUMN vAmountDC decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BankBook MODIFY COLUMN nTotalDebit decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BankBook MODIFY COLUMN nTotalAllocated decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BankBook MODIFY COLUMN nTotalBalance decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BankBookDetails MODIFY COLUMN nAmount decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BankBookPayment MODIFY COLUMN vAmountDC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BankBookPayment MODIFY COLUMN nTotalDebit decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BankBookPayment MODIFY COLUMN nTotalAllocated decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BankBookPayment MODIFY COLUMN nTotalBalance decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BankBookPaymentDetails MODIFY COLUMN nAmount decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Budget MODIFY COLUMN nTotal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}BudgetDetails MODIFY COLUMN nAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashAllocation MODIFY COLUMN vDebitAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashAllocation MODIFY COLUMN vAllAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashAllocation MODIFY COLUMN vBalAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashPayment MODIFY COLUMN vAmountDC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashPayment MODIFY COLUMN nTotalDebit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashPayment MODIFY COLUMN nTotalAllocated  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashPayment MODIFY COLUMN nTotalBalance  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashPaymentDetails MODIFY COLUMN nAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashReceipt MODIFY COLUMN vAmountDC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashReceipt MODIFY COLUMN nTotalDebit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashReceipt MODIFY COLUMN nTotalAllocated  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashReceipt MODIFY COLUMN nTotalBalance  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CashReceiptDetails MODIFY COLUMN nAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CreditVoucher MODIFY COLUMN nTotalDebit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CreditVoucher MODIFY COLUMN nTotalAllocated decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CreditVoucher MODIFY COLUMN nTotalBalance  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CreditVoucherDetails MODIFY COLUMN nAmount decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CustomerMaster MODIFY COLUMN vServCalc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CustomerMaster MODIFY COLUMN vServCal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CustomerMaster MODIFY COLUMN vDiscCalc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CustomerMaster MODIFY COLUMN vDiscCal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}CustomerMaster MODIFY COLUMN vAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DebitVoucher MODIFY COLUMN nTotalDebit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DebitVoucher MODIFY COLUMN nTotalAllocated decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DebitVoucher MODIFY COLUMN nTotalBalance  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DebitVoucherDetails MODIFY COLUMN nAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateAccountDetails MODIFY COLUMN vCardComm  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateAccountDetails MODIFY COLUMN vCommAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateAccountDetails MODIFY COLUMN vAmt decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalFace  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalTax  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalNetAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalServPercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;");
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalDiscAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalServAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalCommPercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;");
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalCommAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalSalesAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalCollected  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoice MODIFY COLUMN vTotalAgentAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceAccounts MODIFY COLUMN vSalesTotalAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceAccounts MODIFY COLUMN vDebit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceAccounts MODIFY COLUMN vCredit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vFare  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vTax decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vNetAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vServPercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vServAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vDisAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vComm  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vCommAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vSlsAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vCollected  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;");
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vAllocation decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;");
$this->db->query("ALTER TABLE {PRE}DuplicateInvoiceDetails MODIFY COLUMN vAgentAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}EmployeeMaster MODIFY COLUMN vServCalc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}EmployeeMaster MODIFY COLUMN vServCal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}EmployeeMaster MODIFY COLUMN vDiscCalc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}EmployeeMaster MODIFY COLUMN vDiscCal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}EmployeeMaster MODIFY COLUMN vAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelBillingDetails MODIFY COLUMN vSalesAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelBillingDetails MODIFY COLUMN vDiscount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelBillingDetails MODIFY COLUMN vDiscountAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelBillingDetails MODIFY COLUMN vAgentAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelBillingDetails MODIFY COLUMN vCollected  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelBillingDetails MODIFY COLUMN vServicePercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelBillingDetails MODIFY COLUMN vServiceAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
//$this->db->query("ALTER TABLE {PRE}HotelDetails MODIFY COLUMN nRTNhtFc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
//$this->db->query("ALTER TABLE {PRE}HotelDetails MODIFY COLUMN vTotalInFc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelDetails MODIFY COLUMN nRTNhtDc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelDetails MODIFY COLUMN vTotalInDC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelVoucher MODIFY COLUMN vTotalRTDC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}HotelVoucher MODIFY COLUMN vTotalDC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Jv MODIFY COLUMN vAmountDC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Jv MODIFY COLUMN nTotalDebit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Jv MODIFY COLUMN nTotalAllocated  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Jv MODIFY COLUMN nTotalBalance  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}JvDetails MODIFY COLUMN nAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}OpeningBalance MODIFY COLUMN vTotalDC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}OpeningBalance MODIFY COLUMN nTotalC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}OpeningBalance MODIFY COLUMN nTotalD  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}OpeningBalanceDetails MODIFY COLUMN vBalDC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}OpeningBalanceDetails MODIFY COLUMN vBalCredit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}OpeningBalanceDetails MODIFY COLUMN vBalDebit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Other MODIFY COLUMN vServCalc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Other MODIFY COLUMN vServCal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Other MODIFY COLUMN vDiscCalc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Other MODIFY COLUMN vDiscCal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Other MODIFY COLUMN vAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Reconciliation MODIFY COLUMN nNetAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Reconciliation MODIFY COLUMN vTotalTax  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Reconciliation MODIFY COLUMN vFacefare  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Reconciliation MODIFY COLUMN vCommAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}Reconciliation MODIFY COLUMN vSystemAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RecurringJv MODIFY COLUMN vAmtDc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RecurringJvSetup MODIFY COLUMN vAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosing MODIFY COLUMN vTotalNetAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosing MODIFY COLUMN vTotalServPercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosing MODIFY COLUMN vTotalServAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosing MODIFY COLUMN vTotalAgentAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosing MODIFY COLUMN vTotalPaidAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingAccounts MODIFY COLUMN vAgentAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingAccounts MODIFY COLUMN vSalesAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingAccounts MODIFY COLUMN vDiscount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingAccounts MODIFY COLUMN vDiscountAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;");
$this->db->query("ALTER TABLE {PRE}RefundClosingAccounts MODIFY COLUMN vCollected  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingAccounts MODIFY COLUMN vServicePercent   decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingAccounts MODIFY COLUMN vServiceAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingDetails MODIFY COLUMN vNetAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingDetails MODIFY COLUMN vServPercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingDetails MODIFY COLUMN vServAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingDetails MODIFY COLUMN vAgentAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundClosingDetails MODIFY COLUMN vPaidAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vFareAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vUsedFare  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vSalesAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vServiceAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vTax  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vUsedTax  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vOurCharge  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vDiscountAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vComm1  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vComm2  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vOthA1  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vOthA2  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vOtherAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vVendorAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vOthA3  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vAgentAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vNetAmt1  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vNetAmt2  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}RefundRegisterDetails MODIFY COLUMN vPaidTo  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesAccountDetails  MODIFY COLUMN vCardComm  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesAccountDetails  MODIFY COLUMN vCommAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesAccountDetails  MODIFY COLUMN vAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosedAccounts MODIFY COLUMN vSalesTotalAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;");
$this->db->query("ALTER TABLE {PRE}SalesClosedAccounts MODIFY COLUMN vDebit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosedAccounts MODIFY COLUMN vCredit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;");  
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalFace  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalTax  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalNetAmount decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalServPercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;");  
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalDiscAmount decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalServAmount decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalCommPercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalCommAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalSalesAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalCollected  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosing MODIFY COLUMN vTotalAgentAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vFare  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vTax  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vNetAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vServPercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vServAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vDisAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vComm  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vCommAmt decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vSlsAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vCollected  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SalesClosingDetails MODIFY COLUMN vAgentAmt decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SystemJvDetails MODIFY COLUMN nDebit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}SystemJvDetails MODIFY COLUMN nCredit  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketBillingDetails MODIFY COLUMN vSalesAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketBillingDetails MODIFY COLUMN vDiscount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketBillingDetails MODIFY COLUMN vDiscountAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketBillingDetails MODIFY COLUMN vAgentAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketBillingDetails MODIFY COLUMN vCollected  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketBillingDetails MODIFY COLUMN vServicePercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketBillingDetails MODIFY COLUMN vServiceAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN vFare  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN vFaceValue  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN vMKTFare  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN vTax1  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN vTax2  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN vTax3  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN vTaxTotal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN nComm  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN nCommAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN nGSAComm  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN nGSAAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN nORC  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN nORCAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN nNetVendor  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}TicketFareDetails MODIFY COLUMN vSalesAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}VendorMaster MODIFY COLUMN vServCalc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}VendorMaster MODIFY COLUMN vServCal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}VendorMaster MODIFY COLUMN vDiscCalc  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}VendorMaster MODIFY COLUMN vDiscCal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}VendorMaster MODIFY COLUMN vAmount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBillingDetails MODIFY COLUMN vSalesAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBillingDetails MODIFY COLUMN vDiscount  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBillingDetails MODIFY COLUMN vDiscountAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBillingDetails MODIFY COLUMN vAgentAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBillingDetails MODIFY COLUMN vCollected  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBillingDetails MODIFY COLUMN vServicePercent  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBillingDetails MODIFY COLUMN vServiceAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBooking MODIFY COLUMN vGrandFare  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBooking MODIFY COLUMN vGrandTax1  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBooking MODIFY COLUMN vGrandTax2  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBooking MODIFY COLUMN vGrandTax3  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBooking MODIFY COLUMN vGrandToalTax  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoBooking MODIFY COLUMN vGrandNetFare  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoPassDetails MODIFY COLUMN vFCTax  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoPassDetails MODIFY COLUMN vTax1  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoPassDetails MODIFY COLUMN vTax2  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoPassDetails MODIFY COLUMN vTax3  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoPassDetails MODIFY COLUMN vTaxTotal  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoPassDetails MODIFY COLUMN nComm  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoPassDetails MODIFY COLUMN nORCAmt  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}XoPassDetails MODIFY COLUMN nNetVendor  decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;"); 
$this->db->query("ALTER TABLE {PRE}YearClosingDetails MODIFY COLUMN vDCBal decimal(" .$full_len_currency." ," . $currency_decimal . ") NOT NULL;");


		$msgVal = 'edit_suc';
		redirect(site_url("company/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function getActiveCountry()
	{
		$typeGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0' 
											ORDER BY nId DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function

	function getActiveDivision()
	{
        $locGroup    = $this->db->query("SELECT * FROM {PRE}Division WHERE vActive = '1' ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
        {
            foreach ($locGroup->result() as $key => $row)
            {
                $locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vDivCode).')';
            }//end for each
        }//end if
        else
        {
            $locGroupRow[0] = NOVALUE;
        }//end else
        return ($locGroupRow);
	}//end function 

	function getLocation()
	{
		$typeGroupRow    = array();
		$typeGroupRow[0] = SELECT;
		return ($typeGroupRow);
	}//end function

	function getActiveLocation()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' ORDER BY nId DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function 
	
	function getActiveCurrency()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Currency WHERE vActive = '1' 
											ORDER BY nPosition DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function 

	function filterlocation($division)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Location WHERE nDivision = '".$division."' ORDER BY vTitle ASC");
		
		if ($adminGroupList->num_rows() > 0)
		{
			$adminGroupRow[0] = SELECT;
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[$row->nId] = $row->vTitle;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
	
}//end class
?>
