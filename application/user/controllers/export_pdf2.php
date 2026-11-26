<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Export_pdf2 extends CI_Controller {

	function __construct()
	{
		parent::__construct();
        $this->load->model('profloss_report_model');
        $this->load->model('ageing_report_model');

        ini_set('memory_limit','120M');
	}

    function monthly_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
			$j = 0;
			$flag = false;
			$chkGroupId = $chkHeadId = $chkAccountId = array();
		
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                    <th>'.ACCODE.'</th>
                                                    <th>'.ACCCODENAME.'</th>
                                                    <th>'.JANUARY.'</th>
                                                    <th>'.FEBRUARY.'</th>
                                                    <th>'.MARCH.'</th>
                                                    <th>'.APRIL.'</th>
                                                    <th>'.MAY.'</th>
                                                    <th>'.JUNE.'</th>
                                                    <th>'.JULY.'</th>
                                                    <th>'.AUGUST.'</th>
                                                    <th>'.SEPTEMBER.'</th>
                                                    <th>'.OCTOBER.'</th>
                                                    <th>'.NOVEMBER.'</th>
                                                    <th>'.DECEMBER.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
        
            foreach ($sqlArray->result() as $key => $row)
            {
				 $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$row->mainId."'");
				if($mainId=='3'):
					$mainCode = '3';
					$mainName = 'Income';
				elseif($mainId=='4'):
					$mainCode = '4';
					$mainName = 'Expense';
				endif;
			
               if(!in_array($mainId,$chkGroupId)):
			   		if($j != 0):
						 $pdfBody .= '<tr>
                    	<td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                    	<td align="center">'.$total1.'</td>
						<td align="center">'.$total2.'</td>
						<td align="center">'.$total3.'</td>
						<td align="center">'.$total4.'</td>
						<td align="center">'.$total5.'</td>
						<td align="center">'.$total6.'</td>
						<td align="center">'.$total7.'</td>
						<td align="center">'.$total8.'</td>
						<td align="center">'.$total9.'</td>
						<td align="center">'.$total10.'</td>
						<td align="center">'.$total11.'</td>
						<td align="center">'.$total12.'</td>
						</tr>
						<tr>
							<td colspan="14" align="right">&nbsp;</td>
						</tr>';
				$flag = true; 
						$total1 = 0;
						$total2 = 0;
						$total3 = 0;
						$total4 = 0;
						$total5 = 0;
						$total6 = 0;
						$total7 = 0;
						$total8 = 0;
						$total9 = 0;
						$total10 = 0;
						$total11 = 0;
						$total12 = 0;
					endif;
                    $pdfBody .= '<tr>
							<td align="left"><h6>'.$mainCode.'</h6></td>
							<td align="left"><h6>'.$mainName.'</h6></td>
							<td colspan="12" align="center">&nbsp;</td>
						</tr>';
				 $j = 1;
                endif;
				
				if(!in_array($row->AccountId,$chkAccountId)):
					$total1 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-01'))));
					$total2 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-02'))));
					$total3 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-03'))));
					$total4 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-04'))));
					$total5 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-05'))));
					$total6 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-06'))));
					$total7 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-07'))));
					$total8 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-08'))));
					$total9 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-09'))));
					$total10 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-10'))));
					$total11 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-11'))));
					$total12 += $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-12'))));
					
					$pdfBody .= '<tr>
						<td>'.$row->AccountCode.'</td>
						<td>'.$row->AccountName.'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-01')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-02')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-03')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-04')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-05')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-06')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-07')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-08')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-09')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-10')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-11')))).'</td>
						<td>'.$this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-12')))).'</td>
					</tr>';
					
					 if($flag == true):
                    $totalPreviousLoop  = '';
                    $totalCurrentLoop   = '';
                    $flag               = false;
                endif;
				
                $chkGroupId[]            = $mainId;
                $chkHeadId[]             = $row->HeadId;
                $chkAccountId[]          = $row->AccountId;

                $totalPrevious          += $getOpenValues['openDebit'];
                $totalCurrent           += $getValues['debit'];
                $totalPreviousLoop      += $getOpenValues['openDebit'];
                $totalCurrentLoop       += $getValues['debit'];
				endif;
				
				if(($key+1) == $sqlArray->num_rows()):
					$pdfBody .= '<tr>
                    	<td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                    	<td align="center">'.$total1.'</td>
						<td align="center">'.$total2.'</td>
						<td align="center">'.$total3.'</td>
						<td align="center">'.$total4.'</td>
						<td align="center">'.$total5.'</td>
						<td align="center">'.$total6.'</td>
						<td align="center">'.$total7.'</td>
						<td align="center">'.$total8.'</td>
						<td align="center">'.$total9.'</td>
						<td align="center">'.$total10.'</td>
						<td align="center">'.$total11.'</td>
						<td align="center">'.$total12.'</td>
						</tr>
						<tr>
							<td colspan="14" align="right">&nbsp;</td>
						</tr>';
				endif;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), MONTHLYPLREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'monthly_profit_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function receivable_ageing_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                        <thead>
                                            <tr>
                                                <th align="left">'.ACCODE.'</th>
                                                <th align="left">'.ACCCODENAME.'</th>
                                                <th align="left" colspan="8">&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <th align="left">'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                                <th align="left">'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                                <th colspan="8">&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <th rowspan="2">'.SRNO.'</th>
                                                <th rowspan="2">'.LOCATIONNAME.'</th>
                                                <th rowspan="2">'.ACCCODENAME.'</th>
                                                <th colspan="5" align="center">'.AGEINGSLAB.'</th>
                                                <th rowspan="2">'.UNAllOCATE.'</th>
                                                <th rowspan="2">'.BALANCE.'</th>
                                            </tr>
                                            <tr>
                                                <th>'.'30' . DAYS.'</th>
                                                <th>'.'31-60'.'</th>
                                                <th>'.'61-90'.'</th>
                                                <th>'.'91-120'.'</th>
                                                <th>'.ABOVE . '120'.'</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
            $dateTotal1 = '';
            $dateTotal2 = '';
            $dateTotal3 = '';
            $dateTotal4 = '';
            $dateTotal5 = '';
            $debitTotal = '';
            $balanceTotal = '';
            $chkSubCodeArray = array();
            $srNo = 1;
            foreach ($sqlArray->result() as $key => $row)
            {
                switch($row->tblName)
                {
                    case "CR":
                        $salesType   = 'Finance';
                        break;

                    case "CP":
                        $salesType   = 'Finance';
                        break;

                    case "BR":
                        $salesType   = 'Finance';
                        break;

                    case "BP":
                        $salesType   = 'Finance';
                        break;

                    case "JV":
                        $salesType   = 'Finance';
                        break;

                    case "SJ":
                        $salesType   = 'Sales';
                        break;

                    case "CV":
                        $salesType   = 'Finance';
                        break;

                    case "DV":
                        $salesType   = 'Finance';
                        break;

                    case "ACM":
                        $salesType   = 'Finance';
                        break;

                    case "ADM":
                        $salesType   = 'Finance';
                        break;
                }//end switch

                if($row->account != '')
                {
                    switch($row->vTable)
                    {
                        case "C":
                            $SubAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row->account."'");
                            $SubAccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$row->account."'");
                            break;

                        case "V":
                            $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row->account."'");
                            $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$row->account."'");
                            break;

                        case "A":
                            $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->account."'");
                            $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row->account."'");
                            break;

                        case "O":
                            $SubAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row->account."'");
                            $SubAccountCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$row->account."'");
                            break;

                        case "AG":
                            $SubAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row->account."'");
                            $SubAccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$row->account."'");
                            break;
                    }
                }
                else
                {
                    if($row->subacc == 32 && trim($row->account) == ''):
                        $getCode        = $this->common_model->get_field_name('TicketFareDetails','nAcc'," WHERE nTicketId  = '".$row->vNumber."'");
                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                    elseif($row->subacc == 34 && trim($row->account) == ''):
                        $getCode        = $this->common_model->get_field_name('HotelVoucher','nAcCode'," WHERE nHVId  = '".$row->vNumber."'");
                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                    elseif($row->subacc == 35 && trim($row->account) == ''):
                        $getCode        = $this->common_model->get_field_name('XoBooking','nPassengerAC'," WHERE nXId = '".$row->vNumber."'");
                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                    else:
                        $SubAccountName = '&nbsp';
                        $SubAccountCode = '&nbsp';
                    endif;
                }

                if(!in_array($SubAccountCode,$chkSubCodeArray)):
                    $getVal = $this->ageing_report_model->getSummaryCntCreditDebit($SubAccountCode,$sqlArray->result());
                    $balance = ($getVal['date1']+$getVal['date2']+$getVal['date3']+$getVal['date4']+$getVal['date5'])-($getVal['wdebit']);

                    $pdfBody .= '
                            <tr>
                                <td>'.$srNo.'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.$SubAccountName.'</td>
                                <td>'.$getVal['date1'].'</td>
                                <td>'.$getVal['date2'].'</td>
                                <td>'.$getVal['date3'].'</td>
                                <td>'.$getVal['date4'].'</td>
                                <td>'.$getVal['date5'].'</td>
                                <td>'.$getVal['wdebit'].'</td>
                                <td>'.$balance.'</td>
                            </tr>';
                    $dateTotal1 += $getVal['date1'];
                    $dateTotal2 += $getVal['date2'];
                    $dateTotal3 += $getVal['date3'];
                    $dateTotal4 += $getVal['date4'];
                    $dateTotal5 += $getVal['date5'];
                    $debitTotal += $getVal['wdebit'];
                    $balanceTotal += $balance;
                    $srNo++;
                endif;
                $chkSubCodeArray[] = $SubAccountCode;
            }//end for each
                $pdfBody .= '<tr>
                                    <td colspan="3" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                                    <td>'.$dateTotal1.'</td>
                                    <td>'.$dateTotal2.'</td>
                                    <td>'.$dateTotal3.'</td>
                                    <td>'.$dateTotal4.'</td>
                                    <td>'.$dateTotal5.'</td>
                                    <td>'.$debitTotal.'</td>
                                    <td>'.$balanceTotal.'</td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                                    <td>'.$dateTotal1.'</td>
                                    <td>'.$dateTotal2.'</td>
                                    <td>'.$dateTotal3.'</td>
                                    <td>'.$dateTotal4.'</td>
                                    <td>'.$dateTotal5.'</td>
                                    <td>'.$debitTotal.'</td>
                                    <td>'.$balanceTotal.'</td>
                                </tr>
                             </tbody>
                         </table>';
        }//end if

        if(empty($pdfBody))
            $pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), RECEIVABLEAGEING, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'receivable_ageing_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function receivable_detail_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            switch($this->session->userdata('tableType'))
            {
                case "C":
                    $SubHeadName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->session->userdata('ddlSubAccount')."'");
                    $SubHeadCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$this->session->userdata('ddlSubAccount')."'");
                    break;

                case "V":
                    $SubHeadName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$this->session->userdata('ddlSubAccount')."'");
                    $SubHeadCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$this->session->userdata('ddlSubAccount')."'");
                    break;

                case "A":
                    $SubHeadName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlSubAccount')."'");
                    $SubHeadCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlSubAccount')."'");
                    break;

                case "O":
                    $SubHeadName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$this->session->userdata('ddlSubAccount')."'");
                    $SubHeadCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$this->session->userdata('ddlSubAccount')."'");
                    break;

                case "AG":
                    $SubHeadName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$this->session->userdata('ddlSubAccount')."'");
                    $SubHeadCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$this->session->userdata('ddlSubAccount')."'");
                    break;
            }//end switch

            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                    <th align="left">'.ACCODE.'</th>
                                                    <th align="left">'.ACCCODENAME.'</th>
                                                    <th align="left">'.SUBCODE.'</th>
                                                    <th align="left">'.SUBNAME.'</th>
                                                    <th align="left" colspan="11">&nbsp;</th>
                                                </tr>
                                                <tr>
                                                    <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                                    <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                                    <th>'.$SubHeadCode.'</th>
                                                    <th>'.$SubHeadName.'</th>
                                                    <th colspan="4">&nbsp;</th>
                                                    <th colspan="5">'.AGEINGSLAB.'</th>
                                                    <th rowspan="2">'.UNAllOCATE.'</th>
                                                    <th rowspan="2">'.BALANCE.'</th>
                                                </tr>
                                                <tr>
                                                    <th align="left">'.SRNO.'</th>
                                                    <th align="left">'.CUSTDATE.'</th>
                                                    <th align="left">'.DOCUMENTNUMBER.'</th>
                                                    <th align="left">'.LOCATIONNAME.'</th>
                                                    <th align="left">'.TICKETNO.'</th>
                                                    <th align="left">'.PASSENGERNAME.'</th>
                                                    <th>'.TRAVELSETUPDETAILS.'</th>
                                                    <th>'.LPO.'</th>
                                                    <th>'.'30' . DAYS.'</th>
                                                    <th>'.'31-60'.'</th>
                                                    <th>'.'61-90'.'</th>
                                                    <th>'.'91-120'.'</th>
                                                    <th>'.ABOVE . '120'.'</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="15" align="center"><strong>'.OPENINGBALANCE.'</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $dateTotal1 = '';
            $dateTotal2 = '';
            $dateTotal3 = '';
            $dateTotal4 = '';
            $dateTotal5 = '';
            $debitTotal = '';
            $balanceTotal = '';
            $date1 = $date2 = $date3 = $date4 = $date5 = '';
            $chkSubCodeArray = $chkTypeArray = array();
            $srNo = 1;
            foreach ($sqlArray->result() as $key => $row)
            {
                switch($row->tblName)
                {
                    case "CR":
                        $salesType   = 'Finance';
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "CP":
                        $salesType   = 'Finance';
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BR":
                        $salesType   = 'Finance';
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BP":
                        $salesType   = 'Finance';
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "JV":
                        $salesType   = 'Finance';
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "SJ":
                        $salesType   = 'Sales';
                        $getTktValue = $this->ageing_report_model->getTktNumberValues($row->detId);
                        $description = $getTktValue['desc'];
                        $ticketNum   = $getTktValue['tktVal'];
                        $passenger   = $getTktValue['passVal'];
                        $lpoVal      = $this->common_model->get_field_name('SalesClosingDetails','vDLPO'," WHERE nId='".$row->detId."'");
                        break;

                    case "CV":
                        $salesType   = 'Finance';
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "DV":
                        $salesType   = 'Finance';
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ACM":
                        $salesType   = 'Finance';
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ADM":
                        $salesType   = 'Finance';
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;
                }//end switch

                if($row->account != '')
                {
                    switch($row->vTable)
                    {
                        case "C":
                            $SubAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row->account."'");
                            $SubAccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$row->account."'");
                            break;

                        case "V":
                            $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row->account."'");
                            $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$row->account."'");
                            break;

                        case "A":
                            $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->account."'");
                            $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row->account."'");
                            break;

                        case "O":
                            $SubAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row->account."'");
                            $SubAccountCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$row->account."'");
                            break;

                        case "AG":
                            $SubAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row->account."'");
                            $SubAccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$row->account."'");
                            break;
                    }
                }
                else
                {
                    if($row->subacc == 32 && trim($row->account) == ''):
                        $getCode        = $this->common_model->get_field_name('TicketFareDetails','nAcc'," WHERE nTicketId  = '".$row->vNumber."'");
                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                    elseif($row->subacc == 34 && trim($row->account) == ''):
                        $getCode        = $this->common_model->get_field_name('HotelVoucher','nAcCode'," WHERE nHVId  = '".$row->vNumber."'");
                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                    elseif($row->subacc == 35 && trim($row->account) == ''):
                        $getCode        = $this->common_model->get_field_name('XoBooking','nPassengerAC'," WHERE nXId = '".$row->vNumber."'");
                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                    else:
                        $SubAccountName = '&nbsp';
                        $SubAccountCode = '&nbsp';
                    endif;
                }
                $datePerd = $this->ageing_report_model->getAgeingDateDiff($row->date);
                $pdfBody .= '<tr>
                    <td>'.$srNo.'</td>
                    <td>'.date("d-M-y", strtotime($row->date)).'</td>
                    <td>'.$row->document.'</td>
                    <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                    <td>'.$ticketNum.'</td>
                    <td>'.$passenger.'</td>
                    <td>'.$description.'</td>
                    <td>'.$lpoVal.'</td>';
                if($salesType=='Sales'):
                    $date1 = ($datePerd <= 30)?$row->allocAmt:'';
                    $date2 = ($datePerd >= 31 && $datePerd <= 60)?$row->allocAmt:'';
                    $date3 = ($datePerd >= 61 && $datePerd <= 90)?$row->allocAmt:'';
                    $date4 = ($datePerd >= 91 && $datePerd <= 120)?$row->allocAmt:'';
                    $date5 = ($datePerd > 120)?$row->allocAmt:'';
                    $balance = $date1+$date2+$date3+$date4+$date5;
                    $dateTotal1 += $date1;
                    $dateTotal2 += $date2;
                    $dateTotal3 += $date3;
                    $dateTotal4 += $date4;
                    $dateTotal5 += $date5;
                    $pdfBody .= '<td>'.$date1.'</td>
                                 <td>'.$date2.'</td>
                                 <td>'.$date3.'</td>
                                 <td>'.$date4.'</td>
                                 <td>'.$date5.'</td>
                                 <td></td>';
                else:
                    $wdebit = $row->allocAmt;
                    $balance = -1*$wdebit;
                    $debitTotal += $wdebit;
                    $pdfBody .= '<td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td>'.$wdebit.'</td>';
                endif;

                $balanceTotal += $balance;
                $pdfBody .= '<td>'.$balance.'</td>
                </tr>';
                $srNo++;
            }//end for each
            $pdfBody .= '<tr>
                                        <td colspan="8" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                                        <td>'.$dateTotal1.'</td>
                                        <td>'.$dateTotal2.'</td>
                                        <td>'.$dateTotal3.'</td>
                                        <td>'.$dateTotal4.'</td>
                                        <td>'.$dateTotal5.'</td>
                                        <td>'.$debitTotal.'</td>
                                        <td>'.$balanceTotal.'</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                                        <td>'.$dateTotal1.'</td>
                                        <td>'.$dateTotal2.'</td>
                                        <td>'.$dateTotal3.'</td>
                                        <td>'.$dateTotal4.'</td>
                                        <td>'.$dateTotal5.'</td>
                                        <td>'.$debitTotal.'</td>
                                        <td>'.$balanceTotal.'</td>
                                    </tr>
                                 </tbody></table>';
        }//end if

        if(empty($pdfBody))
            $pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), RECEIVABLEAGEING, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'receivable_detail_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>