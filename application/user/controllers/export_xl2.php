<?php
class Export_xl2 extends CI_Controller {

	function __construct()
	{
		parent::__construct();
        $this->load->model('profloss_report_model');
        $this->load->model('ageing_report_model');
	}

	function monthly_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        require_once 'static/classes/Classes/PHPExcel.php';
        require_once 'static/classes/Classes/PHPExcel/IOFactory.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        //set column width
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(40);

        

        $sqlArray    = $this->db->query($_SESSION['sess_query']);
        $colVal      = 1;$colVal2     = 3;
		
		
        if ($sqlArray->num_rows() > 0)
        {
            $j = 0;
			$flag = false;
			$chkGroupId = $chkHeadId = $chkAccountId = array();
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$colVal, ACCODE);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$colVal, ACCCODENAME);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$colVal, JANUARY);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$colVal, FEBRUARY);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$colVal, MARCH);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$colVal, APRIL);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$colVal, MAY);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$colVal, JUNE);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$colVal, JULY);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$colVal, AUGUST);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$colVal, SEPTEMBER);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$colVal, OCTOBER);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$colVal, NOVEMBER);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$colVal, DECEMBER);
	
			/*$objPHPExcel->getActiveSheet()->getStyle('A'.$colVal.':N1'.$colVal)->applyFromArray(
				array('fill' 	=> array(
					'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
					'color'		=> array('argb' => 'FF306EFF'),
				)
				)
			);*/
		
			
			
			
            foreach ($sqlArray->result() as $key=>$row)
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
						/*$objPHPExcel->getActiveSheet()->setCellValue('A'.$colVal2, GROUPTOTAL);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$colVal2, $total1);
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$colVal2, $total2);
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$colVal2, $total3);
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$colVal2, $total4);
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$colVal2, $total5);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$colVal2, $total6);
						$objPHPExcel->getActiveSheet()->setCellValue('I'.$colVal2, $total7);
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$colVal2, $total8);
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$colVal2, $total9);
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$colVal2, $total10);
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$colVal2, $total1);
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$colVal2, $total2);*/
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
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$colVal2, $mainCode);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$colVal2, $mainName);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('I'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$colVal2, '');
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$colVal2, '');
                     $j = 1;
                endif;
				
				$colVal3     = $colVal2 + 1;
				//$colVal4     = $colVal3 + 1;
				
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
					
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$colVal3, $row->AccountCode);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$colVal3, $row->AccountName);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-01')))));
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-02')))));
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-03')))));
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-04')))));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-05')))));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-06')))));
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-07')))));
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-08')))));
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-09')))));
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-10')))));
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-11')))));
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$colVal3, $this->profloss_report_model->getMonthlyAccValues($row->AccountId,date('Y-m',strtotime(date('Y-12')))));
	
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
						$colVal4 = $colVal3 + 1;
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$colVal4, GROUPTOTAL);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$colVal4, '');
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$colVal4, $total1);
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$colVal4, $total2);
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$colVal4, $total3);
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$colVal4, $total4);
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$colVal4, $total5);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$colVal4, $total6);
						$objPHPExcel->getActiveSheet()->setCellValue('I'.$colVal4, $total7);
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$colVal4, $total8);
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$colVal4, $total9);
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$colVal4, $total10);
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$colVal4, $total1);
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$colVal4, $total2);
					endif;
					$colVal2++;

            }//end foreach

        }//end if
        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle(MONTHLYPLREPORT);
        $objPHPExcel->getActiveSheet()->getTabColor()->setRGB('FFB6C1');

        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="monthly_profit_report'.date('Y-m-d').'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }//end function

    function receivable_ageing_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        require_once 'static/classes/Classes/PHPExcel.php';
        require_once 'static/classes/Classes/PHPExcel/IOFactory.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        //set column width
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);

        $objPHPExcel->getActiveSheet()->setCellValue('A2', ACCODE);
        $objPHPExcel->getActiveSheet()->setCellValue('B2', ACCCODENAME);

        $objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray(
            array('fill' 	=> array(
                'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('argb' => 'FF306EFF'),
            )
            )
        );

        $objPHPExcel->getActiveSheet()->setCellValue('A3', $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'"));
        $objPHPExcel->getActiveSheet()->setCellValue('B3', $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'"));

        $objPHPExcel->getActiveSheet()->setCellValue('A4', SRNO);
        $objPHPExcel->getActiveSheet()->setCellValue('B4', LOCATIONNAME);
        $objPHPExcel->getActiveSheet()->setCellValue('C4', ACCCODENAME);
        $objPHPExcel->getActiveSheet()->setCellValue('D4', '30' . DAYS);
        $objPHPExcel->getActiveSheet()->setCellValue('E4', '31-60');
        $objPHPExcel->getActiveSheet()->setCellValue('F4', '61-90');
        $objPHPExcel->getActiveSheet()->setCellValue('G4', '91-120');
        $objPHPExcel->getActiveSheet()->setCellValue('H4', ABOVE . '120');
        $objPHPExcel->getActiveSheet()->setCellValue('I4', UNAllOCATE);
        $objPHPExcel->getActiveSheet()->setCellValue('J4', BALANCE);

        $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(
            array('fill' 	=> array(
                'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('argb' => 'FF306EFF'),
            )
            )
        );

        $sqlArray    = $this->db->query($_SESSION['sess_query']);
        $colVal      = 5;
        if ($sqlArray->num_rows() > 0)
        {
            $dateTotal1 = '';
            $dateTotal2 = '';
            $dateTotal3 = '';
            $dateTotal4 = '';
            $dateTotal5 = '';
            $debitTotal = '';
            $balanceTotal = '';
            $chkSubCodeArray = array();
            $srNo = 1;
            foreach ($sqlArray->result() as $key=>$row)
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

                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$colVal, $srNo);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$colVal, stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'")));
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$colVal, $SubAccountName);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$colVal, $getVal['date1']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$colVal, $getVal['date2']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$colVal, $getVal['date3']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$colVal, $getVal['date4']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$colVal, $getVal['date5']);
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$colVal, $getVal['wdebit']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$colVal, $balance);

                    $dateTotal1 += $getVal['date1'];
                    $dateTotal2 += $getVal['date2'];
                    $dateTotal3 += $getVal['date3'];
                    $dateTotal4 += $getVal['date4'];
                    $dateTotal5 += $getVal['date5'];
                    $debitTotal += $getVal['wdebit'];
                    $balanceTotal += $balance;
                    $srNo++;
                    $colVal++;
                endif;
                $chkSubCodeArray[] = $SubAccountCode;
            }//end foreach

            $objPHPExcel->getActiveSheet()->setCellValue('C'.$colVal, TOTALAMOUNT);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$colVal, $dateTotal1);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$colVal, $dateTotal2);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$colVal, $dateTotal3);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$colVal, $dateTotal4);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$colVal, $dateTotal5);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$colVal, $debitTotal);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$colVal, $balanceTotal);
        }//end if
        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle(RECEIVABLEAGEING);
        $objPHPExcel->getActiveSheet()->getTabColor()->setRGB('FFB6C1');

        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="receivable_ageing_report'.date('Y-m-d').'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }//end function

    function receivable_detail_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        require_once 'static/classes/Classes/PHPExcel.php';
        require_once 'static/classes/Classes/PHPExcel/IOFactory.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        //set column width
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);

        $objPHPExcel->getActiveSheet()->setCellValue('A1', ACCODE);
        $objPHPExcel->getActiveSheet()->setCellValue('B1', ACCCODENAME);
        $objPHPExcel->getActiveSheet()->setCellValue('C1', SUBCODE);
        $objPHPExcel->getActiveSheet()->setCellValue('D1', SUBNAME);

        $objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray(
            array('fill' 	=> array(
                'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('argb' => 'FF306EFF'),
            )
            )
        );

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

        $objPHPExcel->getActiveSheet()->setCellValue('A2', $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'"));
        $objPHPExcel->getActiveSheet()->setCellValue('B2', $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'"));
        $objPHPExcel->getActiveSheet()->setCellValue('C2', $SubHeadCode);
        $objPHPExcel->getActiveSheet()->setCellValue('D2', $SubHeadName);

        $objPHPExcel->getActiveSheet()->setCellValue('I3', AGEINGSLAB);

        $objPHPExcel->getActiveSheet()->getStyle('I3:M3')->applyFromArray(
            array('fill' 	=> array(
                'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('rgb' => '90028f'),
            )
            )
        );

        $objPHPExcel->getActiveSheet()->setCellValue('A4', SRNO);
        $objPHPExcel->getActiveSheet()->setCellValue('B4', CUSTDATE);
        $objPHPExcel->getActiveSheet()->setCellValue('C4', DOCUMENTNUMBER);
        $objPHPExcel->getActiveSheet()->setCellValue('D4', LOCATIONNAME);
        $objPHPExcel->getActiveSheet()->setCellValue('E4', TICKETNO);
        $objPHPExcel->getActiveSheet()->setCellValue('F4', PASSENGERNAME);
        $objPHPExcel->getActiveSheet()->setCellValue('G4', TRAVELSETUPDETAILS);
        $objPHPExcel->getActiveSheet()->setCellValue('H4', LPO);
        $objPHPExcel->getActiveSheet()->setCellValue('I4', '30' . DAYS);
        $objPHPExcel->getActiveSheet()->setCellValue('J4', '31-60');
        $objPHPExcel->getActiveSheet()->setCellValue('K4', '61-90');
        $objPHPExcel->getActiveSheet()->setCellValue('L4', '91-120');
        $objPHPExcel->getActiveSheet()->setCellValue('M4', ABOVE.'120');
        $objPHPExcel->getActiveSheet()->setCellValue('N4', UNAllOCATE);
        $objPHPExcel->getActiveSheet()->setCellValue('O4', BALANCE);

        $objPHPExcel->getActiveSheet()->getStyle('A4:O4')->applyFromArray(
            array('fill' 	=> array(
                'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('argb' => 'FF306EFF'),
            )
            )
        );

        $sqlArray    = $this->db->query($_SESSION['sess_query']);
        $colVal      = 5;
        if ($sqlArray->num_rows() > 0)
        {
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
            foreach ($sqlArray->result() as $key=>$row)
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

                $objPHPExcel->getActiveSheet()->setCellValue('A'.$colVal, $srNo);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$colVal, date("d-M-y", strtotime($row->date)));
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$colVal, stripslashes($row->document));
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$colVal, stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'")));
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$colVal, @str_replace("<br>","\n",$ticketNum));
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$colVal, @str_replace("<br>","\n",$passenger));
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$colVal, @str_replace("<br>","\n",$description));
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$colVal, @str_replace("<br>","\n",$lpoVal));
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

                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$colVal, $date1);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$colVal, $date2);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$colVal, $date3);
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$colVal, $date4);
                    $objPHPExcel->getActiveSheet()->setCellValue('M'.$colVal, $date5);
                else:
                    $wdebit = $row->allocAmt;
                    $balance = -1*$wdebit;
                    $debitTotal += $wdebit;
                    $objPHPExcel->getActiveSheet()->setCellValue('N'.$colVal, $wdebit);
                endif;

                $objPHPExcel->getActiveSheet()->setCellValue('O'.$colVal, $balance);

                $balanceTotal += $balance;
                $srNo++;
                $colVal++;
            }//end foreach

            $objPHPExcel->getActiveSheet()->setCellValue('H'.$colVal, TOTALAMOUNT);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$colVal, $dateTotal1);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$colVal, $dateTotal2);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$colVal, $dateTotal3);
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$colVal, $dateTotal4);
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$colVal, $dateTotal5);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$colVal, $debitTotal);
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$colVal, $balanceTotal);
        }//end if
        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle(RECEIVABLEAGEING);
        $objPHPExcel->getActiveSheet()->getTabColor()->setRGB('FFB6C1');

        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="receivable_detail_report'.date('Y-m-d').'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>