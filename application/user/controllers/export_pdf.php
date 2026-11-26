<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Export_pdf extends CI_Controller {


	function __construct()
	{
		parent::__construct();
        $this->load->model('agent_model');
        $this->load->model('employee_model');
        $this->load->model('other_model');
        $this->load->model('topairline_report_model');
        $this->load->model('topsector_report_model');
        $this->load->model('ageing_report_model');
        $this->load->model('gds_report_model');
        $this->load->model('ac_report_model');
        $this->load->model('trial_report_model');
        $this->load->model('balasheet_report_model');
        $this->load->model('profloss_report_model');
        $this->load->model('cash_bank_report_model');
        $this->load->model('chart_report_model');
        $this->load->model('sub_report_model');

        ini_set('memory_limit','120M');
	}

    //function for exporting account period details
	function account_period()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th>'.SLNO.'</th>
                                   <th>'.YEARREF.'</th>
                                   <th>'.ACCSTARTDATE.'</th>
                                   <th>'.ACCENDDATE.'</th>
                                   <th>'.ACCSTATUS.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $count  = $key+1;
                $dSDate = ($row->dSDate != '0000-00-00')?date('d-m-Y',strtotime($row->dSDate)):'NIL';
                $dEDate = ($row->dEDate != '0000-00-00')?date('d-m-Y',strtotime($row->dEDate)):'NIL';
                $active = ($row->vActive == 'O')?ACCOPEN:ACCCLOSED;

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                        <td>'.stripslashes($row->vYearRef).'</td>
                        <td>'.$dSDate.'</td>
                        <td>'.$dEDate.'</td>
                        <td>'.$active.'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';
			
        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ACCOUNTPERIODDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_account_period';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting employee details
    function employee()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								    <th>'.SLNO.'</th>
							       <th>'.CUSTDATE.'</th>
								   <th>'.ACCCODENAME.'</th>
								   <th>'.TRANSCODE.'</th>
								   <th>'.EMPNAME.'</th>
								   <th>'.LOCATIONADDR.'</th>
								   <th>'.COMPANYEMAIL.'</th>
							       <th>'.LOCATIONPHONE.'</th>
								   <th>'.CUSTMOBILE.'</th>
								   <th>'.COMPANYCOUNTRY.'</th>
								   <th>'.CURRNAME.'</th>
							       <th>'.IDTYPE.'</th>
								   <th>'.IDDETAILS.'</th>
								   <th>'.CREDITDAYS.'</th>
								   <th>'.AMOUNT.'</th>
								   <th>'.CUSTSERVCALC.'</th>
								   <th>'.SERVAMT.'</th>
							       <th>'.CUSTDISCCALC.'</th>
								   <th>'.DISCAMT.'</th>
								   <th>'.GALLILEOCD.'</th>
								   <th>'.AMADEUSCD.'</th>
								   <th>'.SABRE.'</th>
								   <th>'.LOCATIONNAME.'</th>
							       <th>'.EMPDESNAME.'</th>
								   <th>'.EMPLOANAC.'</th>
								   <th>'.LOCATIONNAME.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $locId     = array_filter(@split(',',$row->vLocations));
                $locName   = $this->common_model->getLocationName($locId);
                $count     = $key+1;
                $dDate     = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $servCal   = ($row->vServCalc == 'F')?'Fixed':'Percentage';
                $discCal   = ($row->vDiscCalc == 'F')?'Fixed':'Percentage';

                switch($row->vIdType)
                {
                    case "C" :
                        $idType	= 'Civil Id';
                        break;

                    case "PASS" :
                        $idType	= 'Passport';
                        break;

                    case "L" :
                        $idType	= 'License';
                        break;

                    case "ID" :
                        $idType	= 'Id Card';
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                        <td>'.$dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nFId."'")).'</td>
                        <td>'.stripslashes($row->vCode).'</td>
                        <td>'.stripslashes($row->vName).'</td>
                        <td>'.stripslashes($row->vAddress).'</td>
                        <td>'.stripslashes($row->vEmail).'</td>
                        <td>'.stripslashes($row->vPhone).'</td>
                        <td>'.stripslashes($row->vMobile).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                        <td>'.stripslashes($idType).'</td>
                        <td>'.stripslashes($row->vCivilId).'</td>
                        <td>'.stripslashes($row->vCreditDays).'</td>
                        <td>'.stripslashes($row->vAmount).'</td>
                        <td>'.stripslashes($servCal).'</td>
                        <td>'.stripslashes($row->vServCal).'</td>
                        <td>'.stripslashes($discCal).'</td>
                        <td>'.stripslashes($row->vDiscCal).'</td>
                        <td>'.stripslashes($row->vAmadeusCD).'</td>
                        <td>'.stripslashes($row->vSabre).'</td>
                        <td>'.stripslashes($row->vLocation).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Employee','vTitle', " WHERE nId='".$row->nDesignation."' ").'( '.$this->common_model->get_field_name('Employee','vCode', " WHERE nId='".$row->nDesignation."' ").'  )').'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle', " WHERE nId='".$row->nGroupMgr."'").'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode', " WHERE nId='".$row->nGroupMgr."' ").'  )').'</td>
                        <td>'.stripslashes($locName).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SUBGROUPDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_maingroup';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');



        if ($sqlArray->num_rows() > 0)
        {
            foreach ($sqlArray->result() as $key=>$row)
            {

                $pdfBody[] 	 = array(SLNO         	 => $count,
                    ACCCODENAME  	 => stripslashes($this->common_model->get_field_name('AccountHeadAccounts',                                                                       'vTitle'," WHERE nId='".$row->nFId."'")),
                    CUSTDATE     	 => ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL',
                    TRANSCODE    	 => stripslashes($row->vCode),
                    EMPNAME    	 => stripslashes($row->vName),
                    LOCATIONADDR    => stripslashes($row->vAddress),
                    COMPANYEMAIL    => stripslashes($row->vEmail),
                    LOCATIONPHONE   => stripslashes($row->vPhone),
                    CUSTMOBILE    	 => stripslashes($row->vMobile),
                    COMPANYFAX    	 => stripslashes($row->vFax),
                    COMPANYCOUNTRY  => stripslashes($this->common_model->get_field_name('Country','vName'," WHERE                                                                       nId='".$row->nCountry."'")),
                    CURRNAME     	 => stripslashes($this->common_model->get_field_name('Currency','vTitle',"                                                                       WHERE nId='".$row->nCurrency."'")),
                    IDTYPE     	 => stripslashes($idType),
                    IDDETAILS     	 => stripslashes($row->vCivilId),
                    CREDITDAYS      => stripslashes($row->vCreditDays),
                    AMOUNT          => stripslashes($row->vAmount),
                    CUSTSERVCALC    => stripslashes($servCal),
                    SERVAMT         => stripslashes($row->vServCal),
                    CUSTDISCCALC    => stripslashes($discCal),
                    DISCAMT         => stripslashes($row->vDiscCal),
                    GALLILEOCD    	 => stripslashes($row->vGallileoCD),
                    AMADEUSCD    	 => stripslashes($row->vAmadeusCD),
                    SABRE           => stripslashes($row->vSabre),
                    LOCATIONNAME    => stripslashes($row->vLocation),
                    EMPDESNAME      => stripslashes($this->common_model->get_field_name('Employee','vTitle'," WHERE nId='".$row->nDesignation."'")).'( '.stripslashes($this->common_model->get_field_name('Employee','vCode', " WHERE nId='".$row->nDesignation."'")).' )',
                    EMPLOANAC       => stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle', " WHERE nId='".$row->nGroupMgr."'")).'( '.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vACode', " WHERE nId='".$row->nGroupMgr."'")).' )',
                    LOCATIONNAME    => stripslashes($locName));
            }//end for eachs
        }//end if

        $download_fileName 	 = date('Y-m-d').'_'.time().'_agent';
        $this->common_model->exportAsPdf($pdfBody,$download_fileName,EMPDETAILS);
    }//end function

    //function for exporting agent details
	function agent()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.SLNO.'</th>
							       <th>'.CUSTDATE.'</th>
								   <th>'.ACCCODENAME.'</th>
								   <th>'.TRANSCODE.'</th>
								   <th>'.CUSTNAME.'</th>
								   <th>'.LOCATIONADDR.'</th>
								   <th>'.COMPANYEMAIL.'</th>
							       <th>'.LOCATIONPHONE.'</th>
								   <th>'.CUSTMOBILE.'</th>
								   <th>'.AGENTFAX.'</th>
								   <th>'.COMPANYCOUNTRY.'</th>
								   <th>'.CURRNAME.'</th>
								   <th>'.IDTYPE.'</th>
								   <th>'.IDDETAILS.'</th>
								   <th>'.CREDITDAYS.'</th>
								   <th>'.AMOUNT.'</th>
								   <th>'.CUSTSERVCALC.'</th>
								   <th>'.SERVAMT.'</th>
								   <th>'.CUSTDISCCALC.'</th>
								   <th>'.DISCAMT.'</th>
								   <th>'.PARENTCUST.'</th>
								   <th>'.EMPLOYEECODE.'</th>
								   <th>'.AGENTAC.'</th>
								   <th>'.LOCATIONNAME.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $locId     = array_filter(@split(',',$row->vLocations));
                $locName   = $this->common_model->getLocationName($locId);
                $count     = $key+1;
                $dDate     = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $servCalc  = ($row->vServCalc == 'F')?'Fixed':'Percentage';
                $discCalc  = ($row->vDiscCalc == 'F')?'Fixed':'Percentage';
                switch($row->vIdType)
                {
                    case "C" :
                        $idType	= 'Civil Id';
                        break;

                    case "PASS" :
                        $idType	= 'Passport';
                        break;

                    case "L" :
                        $idType	= 'License';
                        break;

                    case "ID" :
                        $idType	= 'Id Card';
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                        <td>'.$dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nFId."'")).'</td>
                        <td>'.stripslashes($row->vCode).'</td>
                        <td>'.stripslashes($row->vName).'</td>
                        <td>'.stripslashes($row->vAddress).'</td>
                        <td>'.stripslashes($row->vEmail).'</td>
                        <td>'.stripslashes($row->vPhone).'</td>
                        <td>'.stripslashes($row->vMobile).'</td>
                        <td>'.stripslashes($row->vFax).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                        <td>'.stripslashes($idType).'</td>
                        <td>'.stripslashes($row->vCivilId).'</td>
                        <td>'.stripslashes($row->vCreditDays).'</td>
                        <td>'.stripslashes($row->vAmount).'</td>
                        <td>'.stripslashes($servCalc).'</td>
                        <td>'.stripslashes($row->vServCal).'</td>
                        <td>'.stripslashes($discCalc).'</td>
                        <td>'.stripslashes($row->vDiscCal).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row->nParentAgent."'")).' ('.stripslashes($this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$row->nParentAgent."'")).' )'.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->nEmpCode."'").'( '.$this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$row->nEmpCode."' ").'  )').'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle', " WHERE nId='".$row->nAgentAc."'").'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode', " WHERE nId='".$row->nAgentAc."' ").'  )').'</td>
                        <td>'.stripslashes($locName).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), AGENTDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_agent';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A1');
	}//end function

    //function for exporting other details
	function other()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.SLNO.'</th>
							       <th>'.CUSTDATE.'</th>
								   <th>'.ACCCODENAME.'</th>
								   <th>'.TRANSCODE.'</th>
								   <th>'.CUSTNAME.'</th>
								   <th>'.LOCATIONADDR.'</th>
								   <th>'.COMPANYEMAIL.'</th>
							       <th>'.LOCATIONPHONE.'</th>
								   <th>'.CUSTMOBILE.'</th>
								   <th>'.AGENTFAX.'</th>
								   <th>'.COMPANYCOUNTRY.'</th>
								   <th>'.CURRNAME.'</th>
								   <th>'.IDTYPE.'</th>
								   <th>'.IDDETAILS.'</th>
								   <th>'.CREDITDAYS.'</th>
								   <th>'.AMOUNT.'</th>
								   <th>'.CUSTSERVCALC.'</th>
								   <th>'.SERVAMT.'</th>
								   <th>'.CUSTDISCCALC.'</th>
								   <th>'.DISCAMT.'</th>
								   <th>'.PARENTCUST.'</th>
								   <th>'.EMPLOYEECODE.'</th>
								   <th>'.AGENTAC.'</th>
								   <th>'.LOCATIONNAME.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $locId     = array_filter(@split(',',$row->vLocations));
                $locName   = $this->common_model->getLocationName($locId);
                $count     = $key+1;
                $dDate     = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $servCalc  = ($row->vServCalc == 'F')?'Fixed':'Percentage';
                $discCalc  = ($row->vDiscCalc == 'F')?'Fixed':'Percentage';
                switch($row->vIdType)
                {
                    case "C" :
                        $idType	= 'Civil Id';
                        break;

                    case "PASS" :
                        $idType	= 'Passport';
                        break;

                    case "L" :
                        $idType	= 'License';
                        break;

                    case "ID" :
                        $idType	= 'Id Card';
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                        <td>'.$dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nFId."'")).'</td>
                        <td>'.stripslashes($row->vCode).'</td>
                        <td>'.stripslashes($row->vName).'</td>
                        <td>'.stripslashes($row->vAddress).'</td>
                        <td>'.stripslashes($row->vEmail).'</td>
                        <td>'.stripslashes($row->vPhone).'</td>
                        <td>'.stripslashes($row->vMobile).'</td>
                        <td>'.stripslashes($row->vFax).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                        <td>'.stripslashes($idType).'</td>
                        <td>'.stripslashes($row->vCivilId).'</td>
                        <td>'.stripslashes($row->vCreditDays).'</td>
                        <td>'.stripslashes($row->vAmount).'</td>
                        <td>'.stripslashes($servCalc).'</td>
                        <td>'.stripslashes($row->vServCal).'</td>
                        <td>'.stripslashes($discCalc).'</td>
                        <td>'.stripslashes($row->vDiscCal).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row->nParentOther."'")).' ('.stripslashes($this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$row->nParentOther."'")).' )'.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->nEmpCode."'").'( '.$this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$row->nEmpCode."' ").'  )').'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle', " WHERE nId='".$row->nAgentAc."'").'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode', " WHERE nId='".$row->nAgentAc."' ").'  )').'</td>
                        <td>'.stripslashes($locName).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), OTHERDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_other';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A1');
	}//end function

    //function for exporting vendor details
	function vendor()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.SLNO.'</th>
							       <th>'.CUSTDATE.'</th>
								   <th>'.ACCCODENAME.'</th>
								   <th>'.TRANSCODE.'</th>
								   <th>'.CUSTNAME.'</th>
								   <th>'.LOCATIONADDR.'</th>
								   <th>'.COMPANYEMAIL.'</th>
							       <th>'.LOCATIONPHONE.'</th>
								   <th>'.CUSTMOBILE.'</th>
								   <th>'.AGENTFAX.'</th>
								   <th>'.COMPANYCOUNTRY.'</th>
								   <th>'.CURRNAME.'</th>
								   <th>'.IDTYPE.'</th>
								   <th>'.IDDETAILS.'</th>
								   <th>'.CREDITDAYS.'</th>
								   <th>'.AMOUNT.'</th>
								   <th>'.CUSTSERVCALC.'</th>
								   <th>'.SERVAMT.'</th>
								   <th>'.CUSTDISCCALC.'</th>
								   <th>'.DISCAMT.'</th>
								   <th>'.PARENTCUST.'</th>
								   <th>'.EMPLOYEECODE.'</th>
								   <th>'.AGENTAC.'</th>
								   <th>'.LOCATIONNAME.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $locId     = array_filter(@split(',',$row->vLocation));
                $locName   = $this->common_model->getLocationName($locId);
                $count     = $key+1;
                $dDate     = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $servCalc  = ($row->vServCalc == 'F')?'Fixed':'Percentage';
                $discCalc  = ($row->vDiscCalc == 'F')?'Fixed':'Percentage';
                switch($row->vIdType)
                {
                    case "C" :
                        $idType	= 'Civil Id';
                        break;

                    case "PASS" :
                        $idType	= 'Passport';
                        break;

                    case "L" :
                        $idType	= 'License';
                        break;

                    case "ID" :
                        $idType	= 'Id Card';
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                        <td>'.$dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nFId."'")).'</td>
                        <td>'.stripslashes($row->vCode).'</td>
                        <td>'.stripslashes($row->vName).'</td>
                        <td>'.stripslashes($row->vAddress).'</td>
                        <td>'.stripslashes($row->vEmail).'</td>
                        <td>'.stripslashes($row->vPhone).'</td>
                        <td>'.stripslashes($row->vMobile).'</td>
                        <td>'.stripslashes($row->vFax).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                        <td>'.stripslashes($idType).'</td>
                        <td>'.stripslashes($row->vCivilId).'</td>
                        <td>'.stripslashes($row->vCreditDays).'</td>
                        <td>'.stripslashes($row->vAmount).'</td>
                        <td>'.stripslashes($servCalc).'</td>
                        <td>'.stripslashes($row->vServCal).'</td>
                        <td>'.stripslashes($discCalc).'</td>
                        <td>'.stripslashes($row->vDiscCal).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row->nParentVendor."'")).' ('.stripslashes($this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$row->nParentVendor."'")).' )'.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->nEmpCode."'").'( '.$this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$row->nEmpCode."' ").'  )').'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle', " WHERE nId='".$row->nAgentAc."'").'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode', " WHERE nId='".$row->nAgentAc."' ").'  )').'</td>
                        <td>'.stripslashes($locName).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), VENDORDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_vendor';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A1');
	}//end function

    //function for exporting customer details
	function customer()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.SLNO.'</th>
							       <th>'.CUSTDATE.'</th>
								   <th>'.ACCCODENAME.'</th>
								   <th>'.TRANSCODE.'</th>
								   <th>'.CUSTNAME.'</th>
								   <th>'.LOCATIONADDR.'</th>
								   <th>'.COMPANYEMAIL.'</th>
							       <th>'.LOCATIONPHONE.'</th>
								   <th>'.CUSTMOBILE.'</th>
								   <th>'.AGENTFAX.'</th>
								   <th>'.COMPANYCOUNTRY.'</th>
								   <th>'.CURRNAME.'</th>
								   <th>'.IDTYPE.'</th>
								   <th>'.IDDETAILS.'</th>
								   <th>'.CREDITDAYS.'</th>
								   <th>'.AMOUNT.'</th>
								   <th>'.CUSTSERVCALC.'</th>
								   <th>'.SERVAMT.'</th>
								   <th>'.CUSTDISCCALC.'</th>
								   <th>'.DISCAMT.'</th>
								   <th>'.PARENTCUST.'</th>
								   <th>'.EMPLOYEECODE.'</th>
								   <th>'.AGENTAC.'</th>
								   <th>'.LOCATIONNAME.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $locId     = array_filter(@split(',',$row->vLocation));
                $locName   = $this->common_model->getLocationName($locId);
                $count     = $key+1;
                $dDate     = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $servCalc  = ($row->vServCalc == 'F')?'Fixed':'Percentage';
                $discCalc  = ($row->vDiscCalc == 'F')?'Fixed':'Percentage';
                switch($row->vIdType)
                {
                    case "C" :
                        $idType	= 'Civil Id';
                        break;

                    case "PASS" :
                        $idType	= 'Passport';
                        break;

                    case "L" :
                        $idType	= 'License';
                        break;

                    case "ID" :
                        $idType	= 'Id Card';
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                        <td>'.$dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nFId."'")).'</td>
                        <td>'.stripslashes($row->vCode).'</td>
                        <td>'.stripslashes($row->vName).'</td>
                        <td>'.stripslashes($row->vAddress).'</td>
                        <td>'.stripslashes($row->vEmail).'</td>
                        <td>'.stripslashes($row->vPhone).'</td>
                        <td>'.stripslashes($row->vMobile).'</td>
                        <td>'.stripslashes($row->vFax).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                        <td>'.stripslashes($idType).'</td>
                        <td>'.stripslashes($row->vCivilId).'</td>
                        <td>'.stripslashes($row->vCreditDays).'</td>
                        <td>'.stripslashes($row->vAmount).'</td>
                        <td>'.stripslashes($servCalc).'</td>
                        <td>'.stripslashes($row->vServCal).'</td>
                        <td>'.stripslashes($discCalc).'</td>
                        <td>'.stripslashes($row->vDiscCal).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row->nParentCust."'")).' ('.stripslashes($this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$row->nParentCust."'")).' )'.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->nEmpCode."'").'( '.$this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$row->nEmpCode."' ").'  )').'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle', " WHERE nId='".$row->nAgentAc."'").'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode', " WHERE nId='".$row->nAgentAc."' ").'  )').'</td>
                        <td>'.stripslashes($locName).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), CUSTDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_customer';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A1');
	}//end function

    //function for exporting division details
	function division()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th>'.SLNO.'</th>
							       <th>'.DIVISIONCODE.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th>'.CURRNAME.'</th>
							       <th>'.ADDRESS.'</th>
							       <th>'.PHONE.'</th>
							       <th>'.MOBILE.'</th>
							       <th>'.EMAIL.'</th>
							       <th>'.WEBADDR.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $count      = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                        <td>'.stripslashes($row->vDivCode).'</td>
                        <td>'.stripslashes($row->vTitle).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                        <td>'.stripslashes($row->vAddress).'</td>
                        <td>'.stripslashes($row->vPhone).'</td>
                        <td>'.stripslashes($row->vMobile).'</td>
                        <td>'.stripslashes($row->vEmail).'</td>
                        <td>'.stripslashes($row->vWeb).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), DIVISIONDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'division';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting location details
	function location()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th>'.SLNO.'</th>
							       <th>'.LOCATIONNAME.'</th>
								   <th>'.CODE.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th>'.LOCATIONADDR.'</th>
								   <th>'.LOCATIONPHONE.'</th>
							       <th>'.MOBILE.'</th>
								   <th>'.EMAIL.'</th>
								   <th>'.WEB.'</th>
								   <th>'.CURRNAME.'</th>
							       <th>'.YEARDATE.'</th>
								   <th>'.YEARTO.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $count      = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                        <td>'.stripslashes($row->locName).'</td>
                        <td>'.stripslashes($row->vCode).'</td>
                        <td>'.stripslashes($row->divName).'</td>
                        <td>'.stripslashes($row->vAddress).'</td>
                        <td>'.stripslashes($row->vPhone).'</td>
                        <td>'.stripslashes($row->vMobile).'</td>
                        <td>'.stripslashes($row->vEmail).'</td>
                        <td>'.stripslashes($row->vWeb).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                        <td>'.stripslashes($row->dYearDate).'</td>
                        <td>'.stripslashes($row->dYearTo).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), LOCATIONDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'location';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting transaction details
	function transactions()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th>'.SLNO.'</th>
								   <th>'.TRANSCODE.'</th>
								   <th>'.TRANSNAME.'</th>
							       <th>'.TRANSTYPENAME.'</th>
							       <th>'.TRANSMODE.'</th>
								   <th>'.TRANSPREFIX.'</th>
							       <th>'.TRANSYEAR.'</th>
								   <th>'.TRANSMINDIGIT.'</th>
								   <th>'.TRANSSAMPLEDOC.'</th>
							       <th>'.TRANSYEARLYRESET.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $count      = $key+1;
                $transmode  = ($row->vTranmode == 'Cr')?'Credit':'Debit';
                $reset		= ($row->vYrlyreset == 'Y')?'Yes':'No';

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                        <td>'.stripslashes($row->vCode).'</td>
                        <td>'.stripslashes($row->vTitle).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nParentId."'")).'</td>
                        <td>'.stripslashes($transmode).'</td>
                        <td>'.stripslashes($row->vPrefix).'</td>
                        <td>'.stripslashes($row->vYear).'</td>
                        <td>'.stripslashes($row->vMindigit).'</td>
                        <td>'.stripslashes($row->vSampledoc).'</td>
                        <td>'.stripslashes($reset).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), TRANSDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'transactions';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting user details
	function users()
	{
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			foreach ($sqlArray->result() as $key=>$row)
			{
				$count 		= $key+1;
				$locId      = array_filter(@split(',',$row->vLocation));
				$locName    = $this->common_model->getLocationName($locId);

				  switch($row->vType)
				  {
					case "C" :
						$this->tableName = 'CustomerMaster';
						$this->userId	 = 'nCId';
					break;
					case "E" :
						$this->tableName = 'EmployeeMaster';
						$this->userId	 = 'nEId';
					break;
					case "V" :
						$this->tableName = 'VendorMaster';
						$this->userId	 = 'nVId';
					break;
					case "A" :
						$this->tableName = 'Agent';
						$this->userId	 = 'nAId';
					break;
					case "O" :
						$this->tableName = 'Other';
						$this->userId	 = 'nOId';
					break;
				  }//end switch

				$pdfBody[] 	 = array(SLNO         => $count,
							   		 TYPENAME  	  => stripslashes($row->userType),
							  		 USERSCODE    => stripslashes($this->common_model->get_field_name($this->tableName,'vCode'," WHERE ".$this->userId." = '".$row->nUser."' ")),
							  		 USERSNAME    => stripslashes($this->common_model->get_field_name($this->tableName,'vName'," WHERE ".$this->userId." = '".$row->nUser."' ")),
							   		 COMPANYEMAIL => stripslashes($this->common_model->get_field_name($this->tableName,'vEmail'," WHERE ".$this->userId." = '".$row->nUser."' ")),
							  		 USERROLENAME => stripslashes($row->vTitle),
							  		 LOCATIONNAME => stripslashes($locName));
			}//end for each
		}//end if
		
		$download_fileName 	 = date('Y-m-d').'_'.time().'_users';
		$this->common_model->exportAsPdf($pdfBody,$download_fileName,USERDETAILS);
	}//end function

    //function for exporting bankbookentry details
	function bankbook_entry()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.SLNO.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th>'.LOCATIONNAME.'</th>
								   <th>'.CUSTDATE.'</th>
								   <th>'.TRANSNAME.'</th>
								   <th>'.DOCUMENTNUMBER.'</th>
								   <th>'.TRANSMODE.'</th>
								   <th>'.CHEQUEDATE.'</th>
								   <th>'.CHEQUENUMBER.'</th>
								   <th>'.ACCCODENAME.'</th>
								   <th>'.SUBCODE.'</th>
							       <th>'.CURRNAME.'</th>
								   <th>'.RATE.'</th>
								   <th>'.AMOUNTFC.'</th>
								   <th>'.AMOUNTDC.'</th>
								   <th>'.REFDATE.'</th>
								   <th>'.REFNUMBER.'</th>
								   <th>'.ENTEREDBY.'</th>
								   <th>'.REFNAME.'</th>
								   <th>'.REMARKS.'</th>
								   <th>'.DESCRIPTION.'</th>
								   <th>'.INVOICE.'</th>
								   <th>'.INVOICEDATE.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");

                $count      = $key+1;
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';
                $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';
                $chequeDate = ($row->dChequeDate != '0000-00-00')?date('d-m-Y',strtotime($row->dChequeDate)):'NIL';

                switch($row->vTransMode)
                {
                    case "Cr" :
                        $modeofPay	= 'Credit';
                        break;

                    case "Ca" :
                        $modeofPay	= 'Cash';
                        break;

                    case "Dr" :
                        $modeofPay	= 'Debit';
                        break;
                }//end switch

                $pdfBody .= '
                    <tr>
                        <td>'.$count.'</td>
									<td>'.stripslashes($divName).'</td>
									<td>'.stripslashes($locName).'</td>
									<td>'.$dDate.'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
									<td>'.stripslashes($row->vDocNumber).'</td>
									<td>'.stripslashes($modeofPay).'</td>
									<td>'.stripslashes($chequeDate).'</td>
									<td>'.stripslashes($row->vChequeNumber).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nAcCode."'")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->vSubCode."'")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
									<td>'.stripslashes($row->vRate).'</td>
									<td>'.stripslashes($row->vAmountFC).'</td>
									<td>'.stripslashes($row->vAmountDC).'</td>
									<td>'.stripslashes($dRefDate).'</td>
									<td>'.stripslashes($row->vRefNumber).'</td>
									<td>'.stripslashes($row->vEnteredBy).'</td>
									<td>'.stripslashes($row->vRefName).'</td>
									<td>'.stripslashes($row->vRemarks).'</td>
									<td>'.stripslashes($row->vDes).'</td>
									<td>'.stripslashes($row->vInvoice).'</td>
									<td>'.stripslashes($dInvDate).'</td>
                    </tr>';
                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}BankBookDetails WHERE nBankbookId ='".$row->nBBId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .='<tr>
                                    <th></th>
                                    <th colspan="4">'.LOCATIONNAME.'</th>
                                    <th colspan="3">'.ACCCODENAME.'</th>
                                    <th colspan="2">'.SUBCODE.'</th>
                                    <th colspan="2">'.BUDGETMODE.'</th>
                                    <th colspan="2">'.REFNUMBER.'</th>
                                    <th colspan="3">'.CURRNAME.'</th>
                                    <th colspan="2">'.EXCHANGERATE.'</th>
                                    <th colspan="2">'.AMOUNTFC.'</th>
                                    <th colspan="2">'.AMOUNT.'</th>
                             </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        switch($row1->vMode)
                        {
                            case "Cr" :
                                $modeofPay1	= 'Credit';
                                break;

                            case "Ca" :
                                $modeofPay1	= 'Cash';
                                break;

                            case "KN" :
                                $modeofPay1	= 'KNET';
                                break;
                        }//end switch

						switch($row1->vTable)
						{
							case "C" :
								$accName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row1->vACode."'");
								$subAcc	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vAcSubCode."'");
							break;
				
							case "A" :
								$accName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'");
								$subAcc	 = NOVALUE;
							break;
						}//end switch

                        $pdfBody .=	'<tr>
                                        <td></td>
                                         <td colspan="4">'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ")).'</td>
                                         <td colspan="3">'.stripslashes($accName).'</td>
                                         <td colspan="2">'.stripslashes($subAcc).'</td>
                                         <td colspan="2">'.stripslashes($modeofPay1).'</td>
                                         <td colspan="2">'.stripslashes($row1->vAcRefNumber).'</td>
                                         <td colspan="3">'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ")).'</td>
                                         <td colspan="2">'.stripslashes($row1->vExchange).'</td>
                                         <td colspan="2">'.stripslashes($row1->vFCAmount).'</td>
                                         <td colspan="2">'.stripslashes($row1->nAmount).'</td>
                                    </tr>';
                    }//end foreach
                }//end if
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), BANKBOOKENTRYDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_bankbookentry';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting bankbook payment details
	function bank_payment()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.SLNO.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th>'.LOCATIONNAME.'</th>
								   <th>'.CUSTDATE.'</th>
								   <th>'.TRANSNAME.'</th>
								   <th>'.DOCUMENTNUMBER.'</th>
								   <th>'.TRANSMODE.'</th>
								   <th>'.CHEQUEDATE.'</th>
								   <th>'.CHEQUENUMBER.'</th>
								   <th>'.ACCCODENAME.'</th>
								   <th>'.SUBCODE.'</th>
							       <th>'.CURRNAME.'</th>
								   <th>'.RATE.'</th>
								   <th>'.AMOUNTFC.'</th>
								   <th>'.AMOUNTDC.'</th>
								   <th>'.REFDATE.'</th>
								   <th>'.REFNUMBER.'</th>
								   <th>'.ENTEREDBY.'</th>
								   <th>'.REFNAME.'</th>
								   <th>'.REMARKS.'</th>
								   <th>'.DESCRIPTION.'</th>
								   <th>'.INVOICE.'</th>
								   <th>'.INVOICEDATE.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");

                $count      = $key+1;
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';
                $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';
                $chequeDate = ($row->dChequeDate != '0000-00-00')?date('d-m-Y',strtotime($row->dChequeDate)):'NIL';

                switch($row->vTransMode)
                {
                    case "Cr" :
                        $modeofPay	= 'Credit';
                        break;

                    case "Ca" :
                        $modeofPay	= 'Cash';
                        break;

                    case "Dr" :
                        $modeofPay	= 'Debit';
                        break;
                }//end switch

                $pdfBody .= '
                    <tr>
                        <td>'.$count.'</td>
									<td>'.stripslashes($divName).'</td>
									<td>'.stripslashes($locName).'</td>
									<td>'.$dDate.'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
									<td>'.stripslashes($row->vDocNumber).'</td>
									<td>'.stripslashes($modeofPay).'</td>
									<td>'.stripslashes($chequeDate).'</td>
									<td>'.stripslashes($row->vChequeNumber).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nAcCode."'")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->vSubCode."'")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
									<td>'.stripslashes($row->vRate).'</td>
									<td>'.stripslashes($row->vAmountFC).'</td>
									<td>'.stripslashes($row->vAmountDC).'</td>
									<td>'.stripslashes($dRefDate).'</td>
									<td>'.stripslashes($row->vRefNumber).'</td>
									<td>'.stripslashes($row->vEnteredBy).'</td>
									<td>'.stripslashes($row->vRefName).'</td>
									<td>'.stripslashes($row->vRemarks).'</td>
									<td>'.stripslashes($row->vDes).'</td>
									<td>'.stripslashes($row->vInvoice).'</td>
									<td>'.stripslashes($dInvDate).'</td>
                    </tr>';
                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}BankBookPaymentDetails WHERE nBankPaymentId ='".$row->nBPId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .='<tr>
                                    <th></th>
                                    <th colspan="4">'.LOCATIONNAME.'</th>
                                    <th colspan="3">'.ACCCODENAME.'</th>
                                    <th colspan="2">'.SUBCODE.'</th>
                                    <th colspan="2">'.BUDGETMODE.'</th>
                                    <th colspan="2">'.REFNUMBER.'</th>
                                    <th colspan="3">'.CURRNAME.'</th>
                                    <th colspan="2">'.EXCHANGERATE.'</th>
                                    <th colspan="2">'.AMOUNTFC.'</th>
                                    <th colspan="2">'.AMOUNT.'</th>
                             </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        switch($row1->vMode)
                        {
                            case "Cr" :
                                $modeofPay1	= 'Credit';
                                break;

                            case "Ca" :
                                $modeofPay1	= 'Cash';
                                break;

                            case "KN" :
                                $modeofPay1	= 'KNET';
                                break;
                        }//end switch

						switch($row1->vTable)
						{
							case "C" :
								$accName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row1->vACode."'");
								$subAcc	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vAcSubCode."'");
							break;
				
							case "A" :
								$accName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'");
								$subAcc	 = NOVALUE;
							break;
						}//end switch

                        $pdfBody .=	'<tr>
                                        <td></td>
                                         <td colspan="4">'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ")).'</td>
                                         <td colspan="3">'.stripslashes($accName).'</td>
                                         <td colspan="2">'.stripslashes($subAcc).'</td>
                                         <td colspan="2">'.stripslashes($modeofPay1).'</td>
                                         <td colspan="2">'.stripslashes($row1->vAcRefNumber).'</td>
                                         <td colspan="3">'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ")).'</td>
                                         <td colspan="2">'.stripslashes($row1->vExchange).'</td>
                                         <td colspan="2">'.stripslashes($row1->vFCAmount).'</td>
                                         <td colspan="2">'.stripslashes($row1->nAmount).'</td>
                                    </tr>';
                    }//end foreach
                }//end if
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), BANKBOOKPAYMENTDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_bankpayment';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting cash receipt details
	function cash_receipt()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.SLNO.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th>'.LOCATIONNAME.'</th>
								   <th>'.CUSTDATE.'</th>
								   <th>'.TRANSNAME.'</th>
								   <th>'.DOCUMENTNUMBER.'</th>
								   <th>'.TRANSMODE.'</th>
								   <th>'.ACCCODENAME.'</th>
								   <th>'.SUBCODE.'</th>
							       <th>'.CURRNAME.'</th>
								   <th>'.RATE.'</th>
								   <th>'.AMOUNTFC.'</th>
								   <th>'.AMOUNTDC.'</th>
								   <th>'.REFDATE.'</th>
								   <th>'.REFNUMBER.'</th>
								   <th>'.ENTEREDBY.'</th>
								   <th>'.REFNAME.'</th>
								   <th>'.REMARKS.'</th>
								   <th>'.DESCRIPTION.'</th>
								   <th>'.INVOICE.'</th>
								   <th>'.INVOICEDATE.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");

                $count      = $key+1;
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';
                $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';

                switch($row->vTransMode)
                {
                    case "Cr" :
                        $modeofPay	= 'Credit';
                        break;

                    case "Ca" :
                        $modeofPay	= 'Cash';
                        break;

                    case "Dr" :
                        $modeofPay	= 'Debit';
                        break;
                }//end switch

                $pdfBody .= '
                    <tr>
                        <td>'.$count.'</td>
                        <td>'.stripslashes($divName).'</td>
                        <td>'.stripslashes($locName).'</td>
                        <td>'.$dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
                        <td>'.stripslashes($row->vDocNumber).'</td>
                        <td>'.stripslashes($modeofPay).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nAcCode."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->vSubCode."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                        <td>'.stripslashes($row->vRate).'</td>
                        <td>'.stripslashes($row->vAmountFC).'</td>
                        <td>'.stripslashes($row->vAmountDC).'</td>
                        <td>'.stripslashes($dRefDate).'</td>
                        <td>'.stripslashes($row->vRefNumber).'</td>
                        <td>'.stripslashes($row->vEnteredBy).'</td>
                        <td>'.stripslashes($row->vRefName).'</td>
                        <td>'.stripslashes($row->vRemarks).'</td>
                        <td>'.stripslashes($row->vDes).'</td>
                        <td>'.stripslashes($row->vInvoice).'</td>
                        <td>'.stripslashes($dInvDate).'</td>
                    </tr>';
                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}CashReceiptDetails WHERE nCashId ='".$row->nCRId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .='<tr>
                                                <th></th>
                                                <th colspan="3">'.LOCATIONNAME.'</th>
                                                <th colspan="3">'.ACCCODENAME.'</th>
                                                <th colspan="2">'.SUBCODE.'</th>
                                                <th colspan="2">'.BUDGETMODE.'</th>
                                                <th colspan="2">'.REFNUMBER.'</th>
                                                <th colspan="2">'.CURRNAME.'</th>
                                                <th colspan="2">'.EXCHANGERATE.'</th>
                                                <th colspan="2">'.AMOUNTFC.'</th>
                                                <th colspan="2">'.AMOUNT.'</th>
                                             </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        switch($row1->vMode)
                        {
                            case "Cr" :
                                $modeofPay1	= 'Credit';
                                break;

                            case "Ca" :
                                $modeofPay1	= 'Cash';
                                break;

                            case "KN" :
                                $modeofPay1	= 'KNET';
                                break;
                        }//end switch

						switch($row1->vTable)
						{
							case "C" :
								$accName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row1->vACode."'");
								$subAcc	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vAcSubCode."'");
							break;
				
							case "A" :
								$accName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'");
								$subAcc	 = NOVALUE;
							break;
						}//end switch

                        $pdfBody .=	'<tr>
                                        <td></td>
                                        <td colspan="3">'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ")).'</td>
                                        <td colspan="3">'.stripslashes($accName).'</td>
                                        <td colspan="2">'.stripslashes($subAcc).'</td>
                                        <td colspan="2">'.stripslashes($modeofPay1).'</td>
                                        <td colspan="2">'.stripslashes($row1->vAcRefNumber).'</td>
                                        <td colspan="2">'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ")).'</td>
                                        <td colspan="2">'.stripslashes($row1->vExchange).'</td>
                                        <td colspan="2">'.stripslashes($row1->vFCAmount).'</td>
                                        <td colspan="2">'.stripslashes($row1->nAmount).'</td>
                                    </tr>';
                    }//end foreach
                }//end if
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), CASHRECEIPTDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_cashreceipt';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A2');
	}//end function

    //function for exporting cash payment details
	function cash_payment()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.SLNO.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th>'.LOCATIONNAME.'</th>
								   <th>'.CUSTDATE.'</th>
								   <th>'.TRANSNAME.'</th>
								   <th>'.DOCUMENTNUMBER.'</th>
								   <th>'.TRANSMODE.'</th>
								   <th>'.ACCCODENAME.'</th>
								   <th>'.SUBCODE.'</th>
							       <th>'.CURRNAME.'</th>
								   <th>'.RATE.'</th>
								   <th>'.AMOUNTFC.'</th>
								   <th>'.AMOUNTDC.'</th>
								   <th>'.REFDATE.'</th>
								   <th>'.REFNUMBER.'</th>
								   <th>'.ENTEREDBY.'</th>
								   <th>'.REFNAME.'</th>
								   <th>'.REMARKS.'</th>
								   <th>'.DESCRIPTION.'</th>
								   <th>'.INVOICE.'</th>
								   <th>'.INVOICEDATE.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->vLocation."'");

                $count      = $key+1;
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';
                $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';

                switch($row->vTransMode)
                {
                    case "Cr" :
                        $modeofPay	= 'Credit';
                        break;

                    case "Ca" :
                        $modeofPay	= 'Cash';
                        break;

                    case "Dr" :
                        $modeofPay	= 'Debit';
                        break;
                }//end switch

                $pdfBody .= '
                    <tr>
                        <td>'.$count.'</td>
                        <td>'.stripslashes($divName).'</td>
                        <td>'.stripslashes($locName).'</td>
                        <td>'.$dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
                        <td>'.stripslashes($row->vDocNumber).'</td>
                        <td>'.stripslashes($modeofPay).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nAcCode."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->vSubCode."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                        <td>'.stripslashes($row->vRate).'</td>
                        <td>'.stripslashes($row->vAmountFC).'</td>
                        <td>'.stripslashes($row->vAmountDC).'</td>
                        <td>'.stripslashes($dRefDate).'</td>
                        <td>'.stripslashes($row->vRefNumber).'</td>
                        <td>'.stripslashes($row->vEnteredBy).'</td>
                        <td>'.stripslashes($row->vRefName).'</td>
                        <td>'.stripslashes($row->vRemarks).'</td>
                        <td>'.stripslashes($row->vDes).'</td>
                        <td>'.stripslashes($row->vInvoice).'</td>
                        <td>'.stripslashes($dInvDate).'</td>
                    </tr>';
                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}CashPaymentDetails WHERE nCashPId ='".$row->nCPId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .='<tr>
                                                <th></th>
                                                <th colspan="3">'.LOCATIONNAME.'</th>
                                                <th colspan="3">'.ACCCODENAME.'</th>
                                                <th colspan="2">'.SUBCODE.'</th>
                                                <th colspan="2">'.BUDGETMODE.'</th>
                                                <th colspan="2">'.REFNUMBER.'</th>
                                                <th colspan="2">'.CURRNAME.'</th>
                                                <th colspan="2">'.EXCHANGERATE.'</th>
                                                <th colspan="2">'.AMOUNTFC.'</th>
                                                <th colspan="2">'.AMOUNT.'</th>
                                             </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        switch($row1->vMode)
                        {
                            case "Cr" :
                                $modeofPay1	= 'Credit';
                                break;

                            case "Ca" :
                                $modeofPay1	= 'Cash';
                                break;

                            case "KN" :
                                $modeofPay1	= 'KNET';
                                break;
                        }//end switch

						switch($row1->vTable)
						{
							case "C" :
								$accName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row1->vACode."'");
								$subAcc	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vAcSubCode."'");
							break;
				
							case "A" :
								$accName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'");
								$subAcc	 = NOVALUE;
							break;
						}//end switch

                        $pdfBody .=	'<tr>
                                        <td></td>
                                        <td colspan="3">'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ")).'</td>
                                        <td colspan="3">'.stripslashes($accName).'</td>
                                        <td colspan="2">'.stripslashes($subAcc).'</td>
                                        <td colspan="2">'.stripslashes($modeofPay1).'</td>
                                        <td colspan="2">'.stripslashes($row1->vAcRefNumber).'</td>
                                        <td colspan="2">'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ")).'</td>
                                        <td colspan="2">'.stripslashes($row1->vExchange).'</td>
                                        <td colspan="2">'.stripslashes($row1->vFCAmount).'</td>
                                        <td colspan="2">'.stripslashes($row1->nAmount).'</td>
                                    </tr>';
                    }//end foreach
                }//end if
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), CASHPAYMENTDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_cashpayment';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting credit voucher details
	function credit_voucher()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody         	 = array();
        $sqlArray        	 = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="0" cellpadding="5" border="1" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th>'.SLNO.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th colspan="2">'.LOCATIONNAME.'</th>
								   <th>'.CUSTDATE.'</th>
								   <th>'.TRANSNAME.'</th>
								   <th>'.DOCUMENTNUMBER.'</th>
								   <th>'.TRANSMODE.'</th>
							       <th>'.CURRNAME.'</th>
								   <th>'.RATE.'</th>
								   <th>'.CHEQUENUMBER.'</th>
								   <th>'.CHEQUEDATE.'</th>
								   <th>'.REFNUMBER.'</th>
								   <th>'.REFDATE.'</th>
								   <th>'.REFNAME.'</th>
								   <th>'.ENTEREDBY.'</th>
								   <th>'.REMARKS.'</th>
								   <th>'.DESCRIPTION.'</th>
								   <th>'.INVOICE.'</th>
								   <th>'.INVOICEDATE.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");

                $count      = $key+1;
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';
                $ckqDate    = ($row->dChequeDate != '0000-00-00')?date('d-m-Y',strtotime($row->dChequeDate)):'NIL';
                $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';

                switch($row->vTransMode)
                {
                    case "Cr" :
                        $modeofPay	= 'Credit';
                        break;

                    case "Ca" :
                        $modeofPay	= 'Cash';
                        break;

                    case "Dr" :
                        $modeofPay	= 'Debit';
                        break;
                }//end switch

                $pdfBody .= '<tr bgcolor="#ffffff">
                                <td>'.$count.'</td>
                                <td>'.stripslashes($divName).'</td>
                                <td colspan="2">'.stripslashes($locName).'</td>
                                <td>'.$dDate.'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
                                <td>'.stripslashes($row->vDocNumber).'</td>
                                <td>'.stripslashes($modeofPay).'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                                <td>'.stripslashes($row->vRate).'</td>
                                <td>'.stripslashes($row->vChequeNumber).'</td>
                                <td>'.stripslashes($ckqDate).'</td>
                                <td>'.stripslashes($row->vRefNumber).'</td>
                                <td>'.stripslashes($dRefDate).'</td>
                                <td>'.stripslashes($row->vRefName).'</td>
                                <td>'.stripslashes($row->vEnteredBy).'</td>
                                <td>'.stripslashes($row->vRemarks).'</td>
                                <td>'.stripslashes($row->vDes).'</td>
                                <td>'.stripslashes($row->vInvoice).'</td>
                                <td>'.stripslashes($dInvDate).'</td>
                            </tr>';
                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}CreditVoucherDetails WHERE nCreditId = '".$row->nCVId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .=	'<tr bgcolor="#ffffff">
												<th></th>
                                                <th colspan="3">'.LOCATIONNAME.'</th>
                                                <th colspan="3">'.ACCCODENAME.'</th>
                                                <th colspan="2">'.SUBCODE.'</th>
                                                <th colspan="2">'.BUDGETMODE.'</th>
                                                <th colspan="2">'.REFNUMBER.'</th>
                                                <th colspan="2">'.CURRNAME.'</th>
                                                <th colspan="2">'.EXCHANGERATE.'</th>
                                                <th colspan="2">'.AMOUNTFC.'</th>
                                                <th colspan="2">'.AMOUNT.'</th>
                                            </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        switch($row1->vMode)
                        {
                            case "Cr" :
                                $modeofPay1	= 'Credit';
                            break;

                            case "Ca" :
                                $modeofPay1	= 'Cash';
                            break;

                            case "KN" :
                                $modeofPay1	= 'KNET';
                            break;

                            case "Dr" :
                                $modeofPay1	= 'Debit';
                            break;
                        }//end switch

                        $subledgerQuery = $this->db->query("SELECT ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										     					ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
															WHERE aca.nId='".$row1->vACode."' ORDER BY ahc.nId DESC");
                        $subledgerResult    = $subledgerQuery->row();
                        $this->txtsubLedger	= $subledgerResult->vSubLedgerType;

                        switch($this->txtsubLedger)
                        {
                            case "C":
                                $subAcc = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row1->vAcSubCode."'");
                                break;

                            case "V":
                                $subAcc = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row1->vAcSubCode."'");
                                break;

                            case "A":
                                $subAcc = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row1->vAcSubCode."'");
                                break;

                            case "O":
                                $subAcc = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row1->vAcSubCode."'");
                                break;

                            case "E":
                                $subAcc = $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row1->vAcSubCode."'");
                                break;

                            case "N":
                                $subAcc = '';
                                break;
                        }//end switch

                        $pdfBody .=	'<tr bgcolor="#ffffff">
                            <td></td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ")).'</td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'")).'</td>
                            <td colspan="2">'.stripslashes($subAcc).'</td>
                            <td colspan="2">'.stripslashes($modeofPay1).'</td>
                            <td colspan="2">'.stripslashes($row1->vAcRefNumber).'</td>
                            <td colspan="2">'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ")).'</td>
                            <td colspan="2">'.stripslashes($row1->vExchange).'</td>
                            <td colspan="2">'.stripslashes($row1->vFCAmount).'</td>
                            <td colspan="2">'.stripslashes($row1->nAmount).'</td>
						</tr>';
                    }//end foreach
                }//end if
            }//end for each

            $pdfBody  .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), CREDITVOUCHERDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_credit_voucher';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    //function for exporting debit voucher details
    function debit_voucher()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody         	 = array();
        $sqlArray        	 = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="0" cellpadding="5" border="1" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th>'.SLNO.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th colspan="2">'.LOCATIONNAME.'</th>
								   <th>'.CUSTDATE.'</th>
								   <th>'.TRANSNAME.'</th>
								   <th>'.DOCUMENTNUMBER.'</th>
								   <th>'.TRANSMODE.'</th>
							       <th>'.CURRNAME.'</th>
								   <th>'.RATE.'</th>
								   <th>'.CHEQUENUMBER.'</th>
								   <th>'.CHEQUEDATE.'</th>
								   <th>'.REFNUMBER.'</th>
								   <th>'.REFDATE.'</th>
								   <th>'.REFNAME.'</th>
								   <th>'.ENTEREDBY.'</th>
								   <th>'.REMARKS.'</th>
								   <th>'.DESCRIPTION.'</th>
								   <th>'.INVOICE.'</th>
								   <th>'.INVOICEDATE.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");

                $count      = $key+1;
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';
                $ckqDate    = ($row->dChequeDate != '0000-00-00')?date('d-m-Y',strtotime($row->dChequeDate)):'NIL';
                $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';

                switch($row->vTransMode)
                {
                    case "Cr" :
                        $modeofPay	= 'Credit';
                        break;

                    case "Ca" :
                        $modeofPay	= 'Cash';
                        break;

                    case "Dr" :
                        $modeofPay	= 'Debit';
                        break;
                }//end switch

                $pdfBody .= '<tr bgcolor="#ffffff">
                                <td>'.$count.'</td>
                                <td>'.stripslashes($divName).'</td>
                                <td colspan="2">'.stripslashes($locName).'</td>
                                <td>'.$dDate.'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
                                <td>'.stripslashes($row->vDocNumber).'</td>
                                <td>'.stripslashes($modeofPay).'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                                <td>'.stripslashes($row->vRate).'</td>
                                <td>'.stripslashes($row->vChequeNumber).'</td>
                                <td>'.stripslashes($ckqDate).'</td>
                                <td>'.stripslashes($row->vRefNumber).'</td>
                                <td>'.stripslashes($dRefDate).'</td>
                                <td>'.stripslashes($row->vRefName).'</td>
                                <td>'.stripslashes($row->vEnteredBy).'</td>
                                <td>'.stripslashes($row->vRemarks).'</td>
                                <td>'.stripslashes($row->vDes).'</td>
                                <td>'.stripslashes($row->vInvoice).'</td>
                                <td>'.stripslashes($dInvDate).'</td>
                            </tr>';
                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}DebitVoucherDetails WHERE nDebitId = '".$row->nDVId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .=	'<tr bgcolor="#ffffff">
												<th></th>
                                                <th colspan="3">'.LOCATIONNAME.'</th>
                                                <th colspan="3">'.ACCCODENAME.'</th>
                                                <th colspan="2">'.SUBCODE.'</th>
                                                <th colspan="2">'.BUDGETMODE.'</th>
                                                <th colspan="2">'.REFNUMBER.'</th>
                                                <th colspan="2">'.CURRNAME.'</th>
                                                <th colspan="2">'.EXCHANGERATE.'</th>
                                                <th colspan="2">'.AMOUNTFC.'</th>
                                                <th colspan="2">'.AMOUNT.'</th>
                                            </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        switch($row1->vMode)
                        {
                            case "Cr" :
                                $modeofPay1	= 'Credit';
                                break;

                            case "Ca" :
                                $modeofPay1	= 'Cash';
                                break;

                            case "KN" :
                                $modeofPay1	= 'KNET';
                                break;

                            case "Dr" :
                                $modeofPay1	= 'Debit';
                                break;
                        }//end switch

                        $subledgerQuery = $this->db->query("SELECT ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										     ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE aca.nId='".$row1->vACode."' ORDER BY ahc.nId DESC");
                        $subledgerResult    = $subledgerQuery->row();
                        $this->txtsubLedger	= $subledgerResult->vSubLedgerType;

                        switch($this->txtsubLedger)
                        {
                            case "C":
                                $subAcc = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row1->vAcSubCode."'");
                                break;

                            case "V":
                                $subAcc = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row1->vAcSubCode."'");
                                break;

                            case "A":
                                $subAcc = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row1->vAcSubCode."'");
                                break;

                            case "O":
                                $subAcc = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row1->vAcSubCode."'");
                                break;

                            case "E":
                                $subAcc = $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row1->vAcSubCode."'");
                                break;

                            case "N":
                                $subAcc = '';
                                break;
                        }//end switch

                        $pdfBody .=	'<tr bgcolor="#ffffff">
                            <td></td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ")).'</td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'")).'</td>
                            <td colspan="2">'.stripslashes($subAcc).'</td>
                            <td colspan="2">'.stripslashes($modeofPay1).'</td>
                            <td colspan="2">'.stripslashes($row1->vAcRefNumber).'</td>
                            <td colspan="2">'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ")).'</td>
                            <td colspan="2">'.stripslashes($row1->vExchange).'</td>
                            <td colspan="2">'.stripslashes($row1->vFCAmount).'</td>
                            <td colspan="2">'.stripslashes($row1->nAmount).'</td>
						</tr>';
                    }//end foreach
                }//end if
            }//end for each

            $pdfBody  .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), DEBITVOUCHERDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_debit_voucher';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function acm_entry()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody         	 = array();
        $sqlArray        	 = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="0" cellpadding="5" border="1" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th>'.SLNO.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th colspan="2">'.LOCATIONNAME.'</th>
								   <th>'.CUSTDATE.'</th>
								   <th>'.TRANSNAME.'</th>
								   <th>'.DOCUMENTNUMBER.'</th>
								   <th>'.TRANSMODE.'</th>
							       <th>'.CURRNAME.'</th>
								   <th>'.RATE.'</th>
								   <th>'.CHEQUENUMBER.'</th>
								   <th>'.CHEQUEDATE.'</th>
								   <th>'.REFNUMBER.'</th>
								   <th>'.REFDATE.'</th>
								   <th>'.REFNAME.'</th>
								   <th>'.ENTEREDBY.'</th>
								   <th>'.REMARKS.'</th>
								   <th>'.DESCRIPTION.'</th>
								   <th>'.INVOICE.'</th>
								   <th>'.INVOICEDATE.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");

                $count      = $key+1;
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';
                $ckqDate    = ($row->dChequeDate != '0000-00-00')?date('d-m-Y',strtotime($row->dChequeDate)):'NIL';
                $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';

                switch($row->vTransMode)
                {
                    case "Cr" :
                        $modeofPay	= 'Credit';
                        break;

                    case "Ca" :
                        $modeofPay	= 'Cash';
                        break;

                    case "Dr" :
                        $modeofPay	= 'Debit';
                        break;
                }//end switch

                $pdfBody .= '<tr bgcolor="#ffffff">
                                <td>'.$count.'</td>
                                <td>'.stripslashes($divName).'</td>
                                <td colspan="2">'.stripslashes($locName).'</td>
                                <td>'.$dDate.'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
                                <td>'.stripslashes($row->vDocNumber).'</td>
                                <td>'.stripslashes($modeofPay).'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                                <td>'.stripslashes($row->vRate).'</td>
                                <td>'.stripslashes($row->vChequeNumber).'</td>
                                <td>'.stripslashes($ckqDate).'</td>
                                <td>'.stripslashes($row->vRefNumber).'</td>
                                <td>'.stripslashes($dRefDate).'</td>
                                <td>'.stripslashes($row->vRefName).'</td>
                                <td>'.stripslashes($row->vEnteredBy).'</td>
                                <td>'.stripslashes($row->vRemarks).'</td>
                                <td>'.stripslashes($row->vDes).'</td>
                                <td>'.stripslashes($row->vInvoice).'</td>
                                <td>'.stripslashes($dInvDate).'</td>
                            </tr>';
                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}AcmEntryDetails WHERE nAcId = '".$row->nACId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .=	'<tr bgcolor="#ffffff">
												<th></th>
                                                <th colspan="3">'.LOCATIONNAME.'</th>
                                                <th colspan="3">'.ACCCODENAME.'</th>
                                                <th colspan="2">'.SUBCODE.'</th>
                                                <th colspan="2">'.BUDGETMODE.'</th>
                                                <th colspan="2">'.REFNUMBER.'</th>
                                                <th colspan="2">'.CURRNAME.'</th>
                                                <th colspan="2">'.EXCHANGERATE.'</th>
                                                <th colspan="2">'.AMOUNTFC.'</th>
                                                <th colspan="2">'.AMOUNT.'</th>
                                            </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        switch($row1->vMode)
                        {
                            case "Cr" :
                                $modeofPay1	= 'Credit';
                                break;

                            case "Ca" :
                                $modeofPay1	= 'Cash';
                                break;

                            case "KN" :
                                $modeofPay1	= 'KNET';
                                break;

                            case "Dr" :
                                $modeofPay1	= 'Debit';
                                break;
                        }//end switch

                        $subledgerQuery = $this->db->query("SELECT ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										     ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE aca.nId='".$row1->vACode."' ORDER BY ahc.nId DESC");
                        $subledgerResult    = $subledgerQuery->row();
                        $this->txtsubLedger	= $subledgerResult->vSubLedgerType;

                        switch($this->txtsubLedger)
                        {
                            case "C":
                                $subAcc = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row1->vAcSubCode."'");
                                break;

                            case "V":
                                $subAcc = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row1->vAcSubCode."'");
                                break;

                            case "A":
                                $subAcc = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row1->vAcSubCode."'");
                                break;

                            case "O":
                                $subAcc = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row1->vAcSubCode."'");
                                break;

                            case "E":
                                $subAcc = $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row1->vAcSubCode."'");
                                break;

                            case "N":
                                $subAcc = '';
                                break;
                        }//end switch

                        $pdfBody .=	'<tr bgcolor="#ffffff">
                            <td></td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ")).'</td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'")).'</td>
                            <td colspan="2">'.stripslashes($subAcc).'</td>
                            <td colspan="2">'.stripslashes($modeofPay1).'</td>
                            <td colspan="2">'.stripslashes($row1->vAcRefNumber).'</td>
                            <td colspan="2">'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ")).'</td>
                            <td colspan="2">'.stripslashes($row1->vExchange).'</td>
                            <td colspan="2">'.stripslashes($row1->vFCAmount).'</td>
                            <td colspan="2">'.stripslashes($row1->nAmount).'</td>
						</tr>';
                    }//end foreach
                }//end if
            }//end for each

            $pdfBody  .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ACMENTRYDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_acm_entry';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function adm_entry()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody         	 = array();
        $sqlArray        	 = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="0" cellpadding="5" border="1" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th>'.SLNO.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th colspan="2">'.LOCATIONNAME.'</th>
								   <th>'.CUSTDATE.'</th>
								   <th>'.TRANSNAME.'</th>
								   <th>'.DOCUMENTNUMBER.'</th>
								   <th>'.TRANSMODE.'</th>
							       <th>'.CURRNAME.'</th>
								   <th>'.RATE.'</th>
								   <th>'.CHEQUENUMBER.'</th>
								   <th>'.CHEQUEDATE.'</th>
								   <th>'.REFNUMBER.'</th>
								   <th>'.REFDATE.'</th>
								   <th>'.REFNAME.'</th>
								   <th>'.ENTEREDBY.'</th>
								   <th>'.REMARKS.'</th>
								   <th>'.DESCRIPTION.'</th>
								   <th>'.INVOICE.'</th>
								   <th>'.INVOICEDATE.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");

                $count      = $key+1;
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';
                $ckqDate    = ($row->dChequeDate != '0000-00-00')?date('d-m-Y',strtotime($row->dChequeDate)):'NIL';
                $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';

                switch($row->vTransMode)
                {
                    case "Cr" :
                        $modeofPay	= 'Credit';
                        break;

                    case "Ca" :
                        $modeofPay	= 'Cash';
                        break;

                    case "Dr" :
                        $modeofPay	= 'Debit';
                        break;
                }//end switch

                $pdfBody .= '<tr bgcolor="#ffffff">
                                <td>'.$count.'</td>
                                <td>'.stripslashes($divName).'</td>
                                <td colspan="2">'.stripslashes($locName).'</td>
                                <td>'.$dDate.'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
                                <td>'.stripslashes($row->vDocNumber).'</td>
                                <td>'.stripslashes($modeofPay).'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                                <td>'.stripslashes($row->vRate).'</td>
                                <td>'.stripslashes($row->vChequeNumber).'</td>
                                <td>'.stripslashes($ckqDate).'</td>
                                <td>'.stripslashes($row->vRefNumber).'</td>
                                <td>'.stripslashes($dRefDate).'</td>
                                <td>'.stripslashes($row->vRefName).'</td>
                                <td>'.stripslashes($row->vEnteredBy).'</td>
                                <td>'.stripslashes($row->vRemarks).'</td>
                                <td>'.stripslashes($row->vDes).'</td>
                                <td>'.stripslashes($row->vInvoice).'</td>
                                <td>'.stripslashes($dInvDate).'</td>
                            </tr>';
                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}AdmEntryDetails WHERE nAdId = '".$row->nADId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .=	'<tr bgcolor="#ffffff">
												<th></th>
                                                <th colspan="3">'.LOCATIONNAME.'</th>
                                                <th colspan="3">'.ACCCODENAME.'</th>
                                                <th colspan="2">'.SUBCODE.'</th>
                                                <th colspan="2">'.BUDGETMODE.'</th>
                                                <th colspan="2">'.REFNUMBER.'</th>
                                                <th colspan="2">'.CURRNAME.'</th>
                                                <th colspan="2">'.EXCHANGERATE.'</th>
                                                <th colspan="2">'.AMOUNTFC.'</th>
                                                <th colspan="2">'.AMOUNT.'</th>
                                            </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        switch($row1->vMode)
                        {
                            case "Cr" :
                                $modeofPay1	= 'Credit';
                                break;

                            case "Ca" :
                                $modeofPay1	= 'Cash';
                                break;

                            case "KN" :
                                $modeofPay1	= 'KNET';
                                break;

                            case "Dr" :
                                $modeofPay1	= 'Debit';
                                break;
                        }//end switch

                        $subledgerQuery = $this->db->query("SELECT ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										     ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE aca.nId='".$row1->vACode."' ORDER BY ahc.nId DESC");
                        $subledgerResult    = $subledgerQuery->row();
                        $this->txtsubLedger	= $subledgerResult->vSubLedgerType;

                        switch($this->txtsubLedger)
                        {
                            case "C":
                                $subAcc = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row1->vAcSubCode."'");
                                break;

                            case "V":
                                $subAcc = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row1->vAcSubCode."'");
                                break;

                            case "A":
                                $subAcc = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row1->vAcSubCode."'");
                                break;

                            case "O":
                                $subAcc = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row1->vAcSubCode."'");
                                break;

                            case "E":
                                $subAcc = $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row1->vAcSubCode."'");
                                break;

                            case "N":
                                $subAcc = '';
                                break;
                        }//end switch

                        $pdfBody .=	'<tr bgcolor="#ffffff">
                            <td></td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ")).'</td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'")).'</td>
                            <td colspan="2">'.stripslashes($subAcc).'</td>
                            <td colspan="2">'.stripslashes($modeofPay1).'</td>
                            <td colspan="2">'.stripslashes($row1->vAcRefNumber).'</td>
                            <td colspan="2">'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ")).'</td>
                            <td colspan="2">'.stripslashes($row1->vExchange).'</td>
                            <td colspan="2">'.stripslashes($row1->vFCAmount).'</td>
                            <td colspan="2">'.stripslashes($row1->nAmount).'</td>
						</tr>';
                    }//end foreach
                }//end if
            }//end for each

            $pdfBody  .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ADMENTRYDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_adm_entry';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    //function for exporting journal voucher details
	function jv()
	{
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			$pdfBody = '<table width="100%" border="0" cellpadding="5" border="1" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th>'.SLNO.'</th>
								   <th>'.DIVISIONNAME.'</th>
							       <th>'.LOCATIONNAME.'</th>
								   <th>'.CUSTDATE.'</th>
								   <th>'.TRANSNAME.'</th>
								   <th>'.DOCUMENTNUMBER.'</th>
							       <th>'.CURRNAME.'</th>
								   <th>'.RATE.'</th>
								   <th>'.APPRNUMBER.'</th>
								   <th>'.APPRDATE.'</th>
								   <th>'.AMOUNTDC.'</th>
								   <th>'.AMOUNTFC.'</th>
								   <th>'.REFNUMBER.'</th>
								   <th>'.REFDATE.'</th>
								   <th>'.REFNAME.'</th>
								   <th>'.ENTEREDBY.'</th>
								   <th>'.REMARKS.'</th>
								   <th>'.DESCRIPTION.'</th>
								   <th>'.INVOICE.'</th>
								   <th>'.INVOICEDATE.'</th>
							    </tr>
							</thead>
							<tbody>';
			foreach ($sqlArray->result() as $key=>$row)
			{
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");

                $count      = $key+1;
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';
                $AprDate    = ($row->dChequeDate != '0000-00-00')?date('d-m-Y',strtotime($row->dChequeDate)):'NIL';
                $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';

				$pdfBody .= '<tr bgcolor="#ffffff">
                                <td>'.$count.'</td>
                                <td>'.stripslashes($divName).'</td>
                                <td>'.stripslashes($locName).'</td>
                                <td>'.$dDate.'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
                                <td>'.stripslashes($row->vDocNumber).'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'")).'</td>
                                <td>'.stripslashes($row->vRate).'</td>
                                <td>'.stripslashes($row->vApprNumber).'</td>
                                <td>'.stripslashes($AprDate).'</td>
                                <td>'.stripslashes($row->vAmountDC).'</td>
                                <td>'.stripslashes($row->vAmountFC).'</td>
                                <td>'.stripslashes($row->vRefNumber).'</td>
                                <td>'.stripslashes($dRefDate).'</td>
                                <td>'.stripslashes($row->vRefName).'</td>
                                <td>'.stripslashes($row->vEnteredBy).'</td>
                                <td>'.stripslashes($row->vRemarks).'</td>
                                <td>'.stripslashes($row->vDes).'</td>
                                <td>'.stripslashes($row->vInvoice).'</td>
                                <td>'.stripslashes($dInvDate).'</td>
                    </tr>';
				$viewAccessRow   = array();
				$viewAccessList  = $this->db->query("SELECT * FROM {PRE}JvDetails WHERE nJournalId = '".$row->nJVId."'");
				if($viewAccessList->num_rows() > 0)
				{
								$pdfBody .=	'<tr bgcolor="#ffffff">
												<th></th>
                                                <th colspan="3">'.LOCATIONNAME.'</th>
                                                <th colspan="2">'.ACCCODENAME.'</th>
                                                <th colspan="2">'.SUBCODE.'</th>
                                                <th colspan="2">'.BUDGETMODE.'</th>
                                                <th colspan="2">'.REFNUMBER.'</th>
                                                <th colspan="2">'.CURRNAME.'</th>
                                                <th colspan="2">'.EXCHANGERATE.'</th>
                                                <th colspan="2">'.AMOUNTFC.'</th>
                                                <th colspan="2">'.AMOUNT.'</th>
                                            </tr>';
					foreach ($viewAccessList->result() as $key1=>$row1)
					{
                        switch($row1->vMode)
                        {
                            case "Cr" :
                                $modeofPay1	= 'Credit';
                                break;

                            case "Ca" :
                                $modeofPay1	= 'Cash';
                                break;

                            case "KN" :
                                $modeofPay1	= 'KNET';
                                break;

                            case "Dr" :
                                $modeofPay1	= 'Debit';
                                break;
                        }//end switch

                        $subledgerQuery = $this->db->query("SELECT ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										     ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE aca.nId='".$row1->nACode."' ORDER BY ahc.nId DESC");
                        $subledgerResult    = $subledgerQuery->row();
                        $this->txtsubLedger	= $subledgerResult->vSubLedgerType;

                        switch($this->txtsubLedger)
                        {
                            case "C":
                                $subAcc = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row1->vAcSubCode."'");
                                break;

                            case "V":
                                $subAcc = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row1->vAcSubCode."'");
                                break;

                            case "A":
                                $subAcc = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row1->vAcSubCode."'");
                                break;

                            case "O":
                                $subAcc = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row1->vAcSubCode."'");
                                break;

                            case "E":
                                $subAcc = $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row1->vAcSubCode."'");
                                break;

                            case "N":
                                $subAcc = '';
                                break;
                        }//end switch

                        $pdfBody .=	'<tr bgcolor="#ffffff">
                            <td></td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ")).'</td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->nACode."'")).'</td>
                            <td colspan="2">'.stripslashes($subAcc).'</td>
                            <td colspan="2">'.stripslashes($modeofPay1).'</td>
                            <td colspan="2">'.stripslashes($row1->vAcRefNumber).'</td>
                            <td colspan="2">'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ")).'</td>
                            <td colspan="2">'.stripslashes($row1->vExchange).'</td>
                            <td colspan="2">'.stripslashes($row1->vFCAmount).'</td>
                            <td colspan="2">'.stripslashes($row1->nAmount).'</td>
						</tr>';
					}//end foreach
				}//end if
			}//end for each
			
			$pdfBody  .= '</tbody></table>';
		}//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), JOURNALVOUCHERDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_journalvoucher';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting transaction document details
	function document()
	{
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			$pdfBody = '<table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.SLNO.'</strong></th>
								   <th><strong>'.DOCUMENTYEARREF.'</strong></th>
							       <th colspan="7"><strong>'.LOCATIONNAME.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
			foreach ($sqlArray->result() as $key=>$row)
			{
				$locId      = array_filter(@split(',',$row->vLocation));
				$locName    = $this->common_model->getLocationName($locId);
				$count      = $key+1;

				$pdfBody .= '<tr bgcolor="#ffffff">
									<td>'.$count.'</td>
									<td>'.stripslashes($row->vYearref).'</td>
									<td colspan="7">'.stripslashes($locName).'</td>
</tr>';
									
				$viewAccessRow   = array();
				$viewAccessList  = $this->db->query("SELECT d.*,dd.* FROM {PRE}Document d LEFT JOIN {PRE}DocumentDetails dd
															ON d.nDId = dd.nDocId
														  WHERE d.nDId='".$row->nDId."'");
				if($viewAccessList->num_rows() > 0)
				{
								$pdfBody .=	'<tr bgcolor="#ffffff">
												<td colspan="7"><strong>'.TRANSDETAILS.'</strong></td>
												 </tr>
												 <tr bgcolor="#ffffff">
												 	<th><strong>'.TRANSNAME.'</strong></th>
												    <th><strong>'.TRANSPREFIX.'</strong></th>
												    <th><strong>'.TRANSMINDIGIT.'</strong></th>
												    <th><strong>'.DOCUMENTNUMBER.'<strong></th>
												    <th colspan="7"><strong>'.TRANSSAMPLEDOC.'<strong></th>
												</tr>';											
					foreach ($viewAccessList->result() as $key1=>$row1)
					{
	
										$pdfBody .=	'<tr bgcolor="#ffffff">
														<td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row1->nTransaction."'")).'</td>
														 <td>'.stripslashes($row1->vPrefix).'</td>
														 <td>'.stripslashes($row1->vMindigit).'</td>
														 <td>'.stripslashes($row1->nNumber).'</td>
														 <td colspan="7">'.stripslashes($row1->vSampledoc).'</td>
													</tr>';
					}//end foreach
					$pdfBody .=	'<tr bgcolor="#ffffff"><td colspan="7">&nbsp;</td></tr>';	
				}//end if
			}//end for each
			
			$pdfBody  .= '</tbody></table>';
		}//end if
	
		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';
	
		$pdfFormat = read_file('./static/pdf/pdf.html');
		
		
		$arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
		$arrReplace	= array($this->common_model->get_site_title(), DOCDETAILS, $pdfBody);
		$pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
		
		//write content to file
		write_file('./static/pdf/pdf1.html', $pdfBody);
		$download_fileName = date('Y-m-d').'_'.time().'_document';

		$this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting travel setup details
	function travel_setup()
	{
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			$pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.SLNO.'</strong></th>
								   <th><strong>'.TRANSNAME.'</strong></th>
							       <th><strong>'.LOCATIONNAME.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
			foreach ($sqlArray->result() as $key=>$row)
			{
				$locId      = array_filter(@split(',',$row->vLocation));
				$locName    = $this->common_model->getLocationName($locId);
				$count      = $key+1;

				$pdfBody .= '<tr bgcolor="#ffffff">
									<td>'.$count.'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
									<td>'.stripslashes($locName).'</td>
</tr>';
									
				$viewAccessRow   = array();
				$viewAccessList  = $this->db->query("SELECT tsd.nAHAId,aha.vACode as accCode,aha.vTitle as                                                       accName,tsd.nFlag,tsd.nId as dId 
				                                      FROM {PRE}TravelSetup ts 
										 	         LEFT JOIN {PRE}TravelSetupDetails tsd ON ts.nId=tsd.nAcId
											          LEFT JOIN {PRE}AccountHeadAccounts aha ON tsd.nAHAId=aha.nId 
													 WHERE tsd.nAcId ='".$row->nId."'");
				if($viewAccessList->num_rows() > 0)
				{
								$pdfBody .=	'<tr bgcolor="#ffffff">
												<td colspan="3"><strong>'.TRAVELSETUPDETAILS.'</strong></td>
												 </tr>
												 <tr bgcolor="#ffffff">
												 	<th><strong>'.ACCCODENAME.'</strong></th>
												    <th><strong>'.ACCNAME.'</strong></th>
												    <th><strong>'.TRAVELFLAG.'</strong></th>
												</tr>';											
					foreach ($viewAccessList->result() as $key1=>$row1)
					{
	
										$pdfBody .=	'<tr bgcolor="#ffffff">
														<td>'.stripslashes($row1->accCode).'</td>
														 <td>'.stripslashes($row1->accName).'</td>
														 <td>'.stripslashes($this->common_model->get_field_name('Flags','vTitle'," WHERE nId='".$row1->nFlag."'")).'</td>
													</tr>';
					}//end foreach
					$pdfBody .=	'<tr bgcolor="#ffffff"><td colspan="3">&nbsp;</td></tr>';	
				}//end if
			}//end for each
			
			$pdfBody  .= '</tbody></table>';
		}//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';
		
		$pdfFormat = read_file('./static/pdf/pdf.html');
		
		
		$arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
		$arrReplace	= array($this->common_model->get_site_title(), TRAVELSETUPDETAILS, $pdfBody);
		$pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
		
		//write content to file
		write_file('./static/pdf/pdf1.html', $pdfBody);
		$download_fileName = date('Y-m-d').'_'.time().'_travelsetup';

		$this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting main group details
	function main_group()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								    <th>'.SLNO.'</th>
                                    <th>'.MAINGROUPCODE.'</th>
                                    <th>'.MAINGROUPNAME.'</th>
                                    <th>'.SUBGROUP.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->vTitle)
                {
                    case "1" :
                        $mainGroup = '1 - Assets';
                        break;

                    case "2" :
                        $mainGroup = '2 - Liability';
                        break;

                    case "3" :
                        $mainGroup = '3 - Income';
                        break;

                    case "4" :
                        $mainGroup = '4 - Expense';
                        break;
                }//end switch

                $count                = $key+1;

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('FinanceModule','vCode'," WHERE nParent='".$row->nId."'")).'</td>
                        <td>'.$mainGroup.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nParent='".$row->nId."'")).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SUBGROUPDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_maingroup';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function
	
    //function for exporting account head details
	function account_head()
	{
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			$pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th colspan="2"><strong>'.SLNO.'</strong></th>
								   <th colspan="3"><strong>'.ACCGROUPNAME.'</strong></th>
								   <th colspan="3"><strong>'.SUBCLSCODE.'</strong></th>
								   <th colspan="3"><strong>'.ACCHEADNAME.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
			foreach ($sqlArray->result() as $key=>$row)
			{
				$locId      = array_filter(@split(',',$row->vLocations));
				$locName    = $this->common_model->getLocationName($locId);
				$count      = $key+1;

				$pdfBody .= '<tr bgcolor="#ffffff">
									<td colspan="2">'.$count.'</td>
									<td colspan="3">'.stripslashes($this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$row->nAId."'")).'</td>
									<td colspan="3">'.stripslashes($this->common_model->get_field_name('AccountHead','vHCode'," WHERE nAId='".$row->nAId."'")).'</td>
									<td colspan="3">'.$this->common_model->get_field_name('AccountHead','vAcname'," WHERE nAId='".$row->nAId."'").'</td>
                            </tr>';
									
				$viewAccessRow   = array();
				$viewAccessList  = $this->db->query("SELECT aca.*,acc.*,CASE WHEN acc.vSubSale = '1' THEN 'Yes' WHEN acc.vSubSale = '0' THEN 'No'
                                                WHEN acc.vSubSale = 'C' THEN 'Cash A/C' WHEN acc.vSubSale = 'S' THEN 'Sales' WHEN
                                                acc.vSubSale = 'P' THEN 'P & L Earing' WHEN acc.vSubSale = 'A' THEN 'Advance' ELSE 'Bank' END
                                                as SubSale,CASE WHEN acc.vAllocation = '1' THEN 'Yes' ELSE 'No' END as Allocation,CASE WHEN
                                                acc.vRefund = '1' THEN 'Yes' ELSE 'No' END as Refund, CASE vSLedgerAuto WHEN acc.vSubLedger = 'O' THEN 'Other' ELSE 'Sub Ledger' END
                                                as SubLedger, CASE WHEN acc.vSubLedgerType = 'C' THEN 'Customer' WHEN acc.vSubLedgerType = 'O'
                                                THEN 'Others' WHEN acc.vSubLedgerType = 'V' THEN 'Vendor' WHEN acc.vSubLedgerType = 'E' THEN '
                                                Employee' WHEN acc.vSubLedgerType = 'A' THEN 'Agent' ELSE 'Normal' END as SubLedgerType, CASE
                                                WHEN acc.vSLedgerAuto = '1' THEN 'Yes' ELSE 'No' END as SLedgerAuto FROM {PRE}AccountHeadAccounts
                                                aca LEFT JOIN {PRE}AccountHeadControls acc ON aca.nId = acc.nActHId WHERE acc.nHAId = '".$row->nAId."'");
				if($viewAccessList->num_rows() > 0)
				{
					$pdfBody .=	'<tr>
					                <th colspan="11"><strong>'.ACCNAME.'</strong></th>
								</tr>
                                 <tr>
                                    <td><strong>'.ACCCODENAME.'</strong></td>
                                    <td><strong>'.ACCNAME.'</strong></td>
                                    <td><strong>'.DIVISIONNAME.'</strong></td>
                                    <td><strong>'.LOCATIONNAME.'</strong></td>
                                    <td><strong>'.SUBSIDARYSALE.'</strong></td>
                                    <td><strong>'.SUBLEDGERNOT.'</strong></td>
                                    <td><strong>'.SUBLEDGERTYPE.'</strong></td>
                                    <td><strong>'.SUBLEDGERAUTO.'</strong></td>
                                    <td><strong>'.TRANSPREFIX.'</strong></td>
                                    <td><strong>'.TRANSMINDIGIT.'</strong></td>
                                    <td><strong>'.SUBLEDGERNUM.'</strong></td>
                                    <td><strong>'.ALLOCATIONFLAG.'</strong></td>
                                    <td><strong>'.REFUNDCONTROL.'</strong></td>
                                </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
					{
						$pdfBody .=    '<tr>
                                         <td>'.stripslashes($row1->vACode).'</td>
                                         <td>'.stripslashes($row1->vTitle).'</td>
                                         <td>'.stripslashes($this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$row1->nDivision."'")).'</td>
                                         <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row1->nLocation."'")).'</td>
                                         <td>'.stripslashes($row1->SubSale).'</td>
                                         <td>'.stripslashes($row1->SubLedger).'</td>
                                         <td>'.stripslashes($row1->SubLedgerType).'</td>
                                         <td>'.stripslashes($row1->SLedgerAuto).'</td>
                                         <td>'.stripslashes($row1->vPrefix).'</td>
                                         <td>'.stripslashes($row1->nMinDigit).'</td>
                                         <td>'.stripslashes($row1->vSubLedgerNo).'</td>
                                         <td>'.stripslashes($row1->Allocation).'</td>
                                         <td>'.stripslashes($row1->Refund).'</td>
                                    </tr>';
					}//end foreach
				}//end if
			}//end for each
			
			$pdfBody  .= '</tbody></table>';
		}//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ACCDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_accounthead';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting Monthly budget details
	function monthly_budget()
	{
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			$pdfBody = '<table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.SLNO.'</strong></th>
								   <th><strong>'.BUDGETMONTH.'</strong></th>
							       <th><strong>'.BUDGETYEAR.'</strong></th>
								   <th><strong>'.DIVISIONNAME.'</strong></th>
							       <th><strong>'.LOCATIONNAME.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
			foreach ($sqlArray->result() as $key=>$row)
			{
				switch($row->vMonth)
				{
					case "01" :
						$month = 'January';
					break;

					case "02" :
						$month = 'February';
					break;
					
					case "03" :
						$month = 'March';
					break;
					
					case "04" :
						$month = 'April';
					break;
					
					case "05" :
						$month = 'May';
					break;
					
					case "06" :
						$month = 'June';
					break;
					
					case "07" :
						$month = 'July';
					break;
					
					case "08" :
						$month = 'August';
					break;
					
					case "09" :
						$month = 'September';
					break;
					
					case "10" :
						$month = 'October';
					break;
					
					case "11" :
						$month = 'November';
					break;
					
					case "12" :
						$month = 'December';
					break;
				}//end switch
				
				$locId      = array_filter(@split(',',$row->vLocation));
				$locName    = $this->common_model->getLocationName($locId);
				$count      = $key+1;

				$pdfBody .= '<tr bgcolor="#ffffff">
									<td>'.$count.'</td>
									<td>'.stripslashes($month).'</td>
									<td>'.stripslashes($row->vYear).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'")).'</td>
									<td>'.stripslashes($locName).'</td>
							</tr>';
									
				$viewAccessRow   = array();
				$viewAccessList  = $this->db->query("SELECT b.nBId as bId,b.nTotal,bd.*,bd.nId as 																			                                                       bdId,b.vLocation,b.vYear,b.vCopyref,b.vDocNumber,
													  aha.*,aha.vTitle as accName,aha.vACode as accCode,
													  CASE 
														WHEN bd.vMode = 'Cr' THEN 'Credit'
													  ELSE 'Debit' END as mode FROM {PRE}BudgetDetails 
														bd LEFT JOIN {PRE}Budget b
													 ON b.nBId = bd.nBudId 
													  LEFT JOIN {PRE}AccountHeadAccounts aha ON bd.nAHAId = aha.nId
													 WHERE b.nBId='".$row->nBId."' ORDER BY bd.nId DESC");
				if($viewAccessList->num_rows() > 0)
				{
					$pdfBody .=	'<tr>
								  <td colspan="4"><strong>'.BUDGETDETAILS.'</strong></td>
								 </tr>
								 <tr>
										<th><strong>'.ACCCODENAME.'</strong></th>
										<th><strong>'.ACCNAME.'</strong></th>
										<th><strong>'.BUDGETMODE.'</strong></th>
										<th><strong>'.BUDGETAMOUNT.'</strong></th>
								 </tr>';											
					foreach ($viewAccessList->result() as $key1=>$row1)
					{
	
						$pdfBody .=	'<tr>
										 <td>'.stripslashes($row1->accCode).'</td>
										 <td>'.stripslashes($row1->accName).'</td>
										 <td>'.stripslashes($row1->mode).'</td>
										 <td>'.stripslashes($row1->nAmount).'</td>
									  </tr>';
					}//end foreach
					$pdfBody .=	'<tr>
									<td colspan="3" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
									<td>'.stripslashes($row1->nTotal).'</td>
								  </tr>';	
				}//end if
			}//end for each
			
			$pdfBody  .= '</tbody></table>';
		}//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';
		
		$pdfFormat = read_file('./static/pdf/pdf.html');
		
		
		$arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
		$arrReplace	= array($this->common_model->get_site_title(), BUDGETDETAILS, $pdfBody);
		$pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
		
		//write content to file
		write_file('./static/pdf/pdf1.html', $pdfBody);
		$download_fileName = date('Y-m-d').'_'.time().'_budget';

		$this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting Ledger opening details
	function ledger_opening()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.SLNO.'</th>
								   <th>'.LEDGERNAME.'</th>
							       <th>'.SUBLEDGERNAME.'</th>
								   <th colspan="2">'.DIVISIONNAME.'</th>
								   <th colspan="2">'.LOCATIONNAME.'</th>
								   <th colspan="2">'.CUSTDATE.'</th>
								   <th colspan="2">'.YEARREF.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $count      = $key+1;
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
                switch($row->vLedger)
                {
                    case "SL" :
                        $ledger	= 'Sub Ledger';
                        break;

                    case "GL" :
                        $ledger	= 'General Ledger';
                        break;
                }//end switch

                switch($row->vSubLedger)
                {
                    case "C" :
                        $subLedger	= 'Customer';
                        break;

                    case "E" :
                        $subLedger	= 'Employee';
                        break;

                    case "V" :
                        $subLedger	= 'Vendor';
                        break;

                    case "A" :
                        $subLedger	= 'Agent';
                        break;

                    case "O" :
                        $subLedger	= 'Other';
                        break;
                }//end switch

                $pdfBody .= '
                    <tr>
                        <td>'.$count.'</td>
                        <td>'.stripslashes($ledger).'</td>
                        <td>'.stripslashes($subLedger).'</td>
                        <td colspan="2">'.stripslashes($divName).'</td>
                        <td colspan="2">'.stripslashes($locName).'</td>
                        <td colspan="2">'.$dDate.'</td>
                        <td colspan="2">'.stripslashes($row->vYear).'</td>
                    </tr>';
                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}OpeningBalanceDetails WHERE nBalId='".$row->nOBId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .='<tr>
                                    <th></th>
                                    <th>'.LOCATIONNAME.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SUBCODE.'</th>
                                    <th>'.CURRNAME.'</th>
                                    <th>'.EXCHANGERATE.'</th>
                                    <th>'.BALANCEDC.'</th>
                                    <th>'.BALANCEFC.'</th>
                                    <th>'.BUDGETMODE.'</th>
                                    <th>'.BALANCECREDIT.'</th>
                                    <th>'.BALANCEDEBIT.'</th>
                                 </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        switch($row1->vMode)
                        {
                            case "Cr" :
                                $modeofPay1	= 'Credit';
                                break;

                            case "Dr" :
                                $modeofPay1	= 'Debit';
                                break;
                        }//end switch

                        $subledgerQuery = $this->db->query("SELECT ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										     ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE aca.nId='".$row1->vACode."' ORDER BY ahc.nId DESC");
                        $subledgerResult    = $subledgerQuery->row();
                        $this->txtsubLedger	= $subledgerResult->vSubLedgerType;

                        switch($this->txtsubLedger)
                        {
                            case "C":
                                $subAcc = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row1->vAcSubCode."'");
                                break;

                            case "V":
                                $subAcc = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row1->vAcSubCode."'");
                                break;

                            case "A":
                                $subAcc = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row1->vAcSubCode."'");
                                break;

                            case "O":
                                $subAcc = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row1->vAcSubCode."'");
                                break;

                            case "E":
                                $subAcc = $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row1->vAcSubCode."'");
                                break;

                            case "N":
                                $subAcc = '';
                                break;
                        }//end switch

                        $pdfBody .=	'<tr>
                                        <td></td>
                                        <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ").'</td>
                                        <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'").'</td>
                                        <td>'.$subAcc.'</td>
                                        <td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ").'</td>
                                        <td>'.$row1->vRate.'</td>
                                        <td>'.$row1->vBalDC.'</td>
                                        <td>'.$row1->vBalFC.'</td>
                                        <td>'.$modeofPay1.'</td>
                                        <td>'.$row1->vBalCredit.'</td>
                                        <td>'.$row1->vBalDebit.'</td>
                                    </tr>';
                    }//end foreach
                }//end if
                $pdfBody .='<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>'.TOTALAMOUNT.'</td>
                                <td>'.$row->vTotalDC.'</td>
                                <td>'.$row->vTotalFC.'</td>
                                <td></td>
                                <td>'.$row->nTotalC.'</td>
                                <td>'.$row->nTotalD.'</td>
                            </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), OPENINGDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_Opening_balance';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function
	
    //function for exporting Sector details
	function sector()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.SLNO.'</th>
								   <th>'.SECTORCODE.'</th>
								   <th colspan="2">'.SECTORNAME.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $count      = $key+1;
                $pdfBody .= '
                    <tr>
                        <td>'.$count.'</td>
                        <td>'.stripslashes($row->vRegCode).'</td>
                        <td colspan="2">'.stripslashes($row->vName).'</td>
                    </tr>
                    <tr>
                        <td colspan="4">'.SECTORDETAILS.'</td>
                    </tr>
                    <tr>
                        <th>'.SECCOUNTRY.'</th>
                        <th>'.SECTOR.'</th>
                        <th>'.SECTORDES.'</th>
                        <th>'.DOMESTIC.'</th>
                    </tr>';
                $viewAccessList  = $this->db->query("SELECT c.vName as countryName,sd.vSector,
												sd.vName, sd.vDomestic FROM {PRE}Sector s
													LEFT JOIN {PRE}SectorMasterDetails sd ON s.nSecId = sd.nSector
												LEFT JOIN {PRE}Country c ON sd.nCountry = c.nId
												WHERE s.nSecId = '".$row->nSecId."' AND c.nParentId = '0'");
                if($viewAccessList->num_rows() > 0)
                {
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        $pdfBody .= '<tr>
                                         <td>'.stripslashes($row1->countryName).'</td>
                                         <td>'.stripslashes($row1->vSector).'</td>
                                         <td>'.stripslashes($row1->vName).'</td>
                                         <td>'.($row->vDomestic == '1'?'Yes':'No').'</td>
                                      </tr>';
                    }//end foreach
                }//end if
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SECTORDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_sector';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function	
	
    //function for exporting airline details
	function airline()
	{
	   @ini_set('memory_limit','120M');
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			$pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.SLNO.'</strong></th>
                                   <th><strong>'.AIRLINENO.'</strong></th>
                                   <th><strong>'.AIRLINEALPHA.'</strong></th>
                                   <th><strong>'.AIRLINENAME.'</strong></th>
                                   <th><strong>'.AIRLINETYPE.'</strong></th>
                                   <th><strong>'.ARAC.'</strong></th>
                                   <th><strong>'.SUBAC.'</strong></th>
                                   <th><strong>'.APAC.'</strong></th>
                                   <th><strong>'.SUBAC.'</strong></th>
                                   <th><strong>'.TKTCOMMISSION.'</strong></th>
                                   <th><strong>'.XOCOMMISSION.'</strong></th>
                                   <th><strong>'.GSACOMMISSION.'</strong></th>
                                   <th><strong>'.NAME.'</strong></th>
                                   <th><strong>'.DESIGNATION.'</strong></th>
                                   <th><strong>'.MOBILE.'</strong></th>
                                   <th><strong>'.LANDPHONE.'</strong></th>
                                   <th><strong>'.EMAIL.'</strong></th>
                                   <th><strong>'.ADDRESS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
			foreach ($sqlArray->result() as $key=>$row)
			{
				$count       = $key+1;
				$airlineType = ($row->vType == 'B')?'BSP':'Non BSP';
				
				$pdfBody .= '<tr bgcolor="#ffffff">
							<td>'.$count.'</td>
                            <td>'.stripslashes($row->vAirlineNo).'</td>
                            <td>'.stripslashes($row->vAlpha).'</td>
                            <td>'.stripslashes($row->vTitle).'</td>
                            <td>'.stripslashes($airlineType).'</td>
                            <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row->nAR."' ")).'</td>
                            <td>'.stripslashes($this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row->nSubAr."' ")).'</td>
                            <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row->nAp."' ")).'</td>
                            <td>'.stripslashes($this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$row->nSubAp."' ")).'</td>
                            <td>'.stripslashes($row->nTkt).'</td>
                            <td>'.stripslashes($row->nXo).'</td>
                            <td>'.stripslashes($row->nGsa).'</td>
                            <td>'.stripslashes($row->vName).'</td>
                            <td>'.stripslashes($row->vDesignation).'</td>
                            <td>'.stripslashes($row->vMobile).'</td>
                            <td>'.stripslashes($row->vPhone).'</td>
                            <td>'.stripslashes($row->vEmail).'</td>
                            <td>'.stripslashes($row->vAddress).'</td>
                        </tr>';

                $pdfBody .=	'<tr>
									<th colspan="18"><strong>'.FARECODE.'</strong></th>
								 </tr>
								 <tr>
									<th colspan="8"><strong>'.CODE.'</strong></th>
									<th colspan="10"><strong>'.FAREVALUE.'</strong></th>
								 </tr>';

				$viewAccessList  = $this->db->query("SELECT * FROM {PRE}FareCode WHERE nAirline = '".$row->nArId."'");
				if($viewAccessList->num_rows() > 0)
				{
					foreach ($viewAccessList->result() as $key1=>$row1)
					{
	
						$pdfBody .=	'<tr>
										<td colspan="8">'.stripslashes($row1->vCode).'</td>
										<td colspan="10">'.stripslashes($row1->vValue).'</td>
									 </tr>';
					}//end foreach
				}//end if
			}//end for eachs
			$pdfBody  .= '</tbody></table>';
		}//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';
		
		$pdfFormat = read_file('./static/pdf/pdf.html');
		$arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
		$arrReplace	= array($this->common_model->get_site_title(), AIRLINEDETAILS, $pdfBody);
		$pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
		
		//write content to file
		write_file('./static/pdf/pdf1.html', $pdfBody);
		$download_fileName = date('Y-m-d').'_'.time().'_airline';

		$this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
	}//end function

    //function for exporting stock entry details
	function stock_entry()
	{
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			$pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.SLNO.'</strong></th>
								   <th><strong>'.DIVISIONNAME.'</strong></th>
								   <th><strong>'.LOCATIONNAME.'</strong></th>
							       <th><strong>'.CUSTDATE.'</strong></th>
								   <th><strong>'.YEARREF.'</strong></th>
								   <th><strong>'.DOCNUMBER.'</strong></th>
							       <th><strong>'.RECEIVEDBY.'</strong></th>
								   <th><strong>'.REMARKS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
			foreach ($sqlArray->result() as $key=>$row)
			{
				$count      = $key+1;

				$pdfBody .= '<tr bgcolor="#ffffff">
									<td>'.$count.'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$row->nDivision."'")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
									<td>'.stripslashes($row->dDate).'</td>
									<td>'.stripslashes($row->vYearref).'</td>
									<td>'.stripslashes($row->vDocNumber).'</td>
									<td>'.stripslashes($row->vReceivedBy).'</td>
									<td>'.stripslashes($row->vRemarks).'</td>
							</tr>';
									
				$viewAccessRow   = array();
				$viewAccessList  = $this->db->query("SELECT se.nSEId as seId,sed.nId as sedId,se.nLocation,se.dDate,se.vDocNumber,
														se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
													 FROM {PRE}StockEntry 
														se LEFT JOIN {PRE}StockEntryDetails sed
													 ON se.nSEId = sed.nStock LEFT JOIN {PRE}Stock s ON s.nStkId = sed.nStocktype
													 WHERE se.nSEId='".$row->nSEId."' ORDER BY sed.nId DESC");
				if($viewAccessList->num_rows() > 0)
				{
								$pdfBody .=	'<tr bgcolor="#ffffff">
												<td><strong>'.STOCKENTRYDETAILS.'</strong></td>
												 </tr>
												 <tr bgcolor="#ffffff">
												 	<th><strong>'.STOCKTYPE.'</strong></th>
												    <th><strong>'.STOCKCODE.'</strong></th>
												    <th><strong>'.STOCKSTARTNO.'</strong></th>
												    <th><strong>'.STOCKENDNO.'</strong></th>
												    <th><strong>'.STOCKQTY.'</strong></th>
												</tr>';											
					foreach ($viewAccessList->result() as $key1=>$row1)
					{
	
										$pdfBody .=	'<tr bgcolor="#ffffff">
														<td>'.stripslashes($this->common_model->get_field_name('Stock','vName'," WHERE nStkId = '".$row1->nStocktype."' ")).'</td>
														<td>'.stripslashes($row1->vCode).'</td>
														<td>'.stripslashes($row1->vStartNo).'</td>
														<td>'.stripslashes($row1->vEndNo).'</td>
														<td>'.stripslashes($row1->nQuantity).'</td>
													</tr>';
					}//end foreach
					$pdfBody .=	'<tr bgcolor="#ffffff"><td colspan="3">&nbsp;</td></tr>';	
				}//end if
			}//end for each
			
			$pdfBody  .= '</tbody></table>';
		}//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';
		
		$pdfFormat = read_file('./static/pdf/pdf.html');
		$arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
		$arrReplace	= array($this->common_model->get_site_title(), STOCKENTRYDETAILS, $pdfBody);
		$pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
		
		//write content to file
		write_file('./static/pdf/pdf1.html', $pdfBody);
		$download_fileName = date('Y-m-d').'_'.time().'_stockentry';

		$this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting stock transfer details
	function stock_transfer()
	{
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			$pdfBody = '<table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.SLNO.'</strong></th>
								   <th><strong>'.LOCATIONNAME.'</strong></th>
							       <th><strong>'.CUSTDATE.'</strong></th>
								   <th><strong>'.YEARREF.'</strong></th>
								   <th><strong>'.DOCNUMBER.'</strong></th>
							       <th><strong>'.RECEIVEDBY.'</strong></th>
								   <th><strong>'.REMARKS.'</strong></th>
								   <th><strong>'.TOLOCATION.'</strong></th>
								   <th><strong>'.CUSTNAME.'</strong></th>
							       <th><strong>'.ISSUEDDTO.'</strong></th>
								   <th><strong>'.CODE.'</strong></th>
								   <th><strong>'.START.'</strong></th>
							       <th><strong>'.END.'</strong></th>
								   <th><strong>'.STOCKQTY.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
			foreach ($sqlArray->result() as $key=>$row)
			{
				$count      = $key+1;

				$pdfBody .= '<tr bgcolor="#ffffff">
									<td>'.$count.'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
									<td>'.stripslashes($row->dDate).'</td>
									<td>'.stripslashes($row->vYearref).'</td>
									<td>'.stripslashes($row->vDocNumber).'</td>
									<td>'.stripslashes($row->vIssuedBy).'</td>
									<td>'.stripslashes($row->vRemarks).'</td>
									<td>'.stripslashes($row->vToLocation).'</td>
									<td>'.stripslashes($row->vCustomer).'</td>
									<td>'.stripslashes($row->vIssuedTo).'</td>
									<td>'.stripslashes($row->vCode).'</td>
									<td>'.stripslashes($row->vStart).'</td>
									<td>'.stripslashes($row->vEnd).'</td>
									<td>'.stripslashes($row->nQuantity).'</td>
							</tr>';
									
			}//end for each
			
			$pdfBody  .= '</tbody></table>';
		}//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';
		
		$pdfFormat = read_file('./static/pdf/pdf.html');
		$arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
		$arrReplace	= array($this->common_model->get_site_title(), TRANSFERDETAILS, $pdfBody);
		$pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
		
		//write content to file
		write_file('./static/pdf/pdf1.html', $pdfBody);
		$download_fileName = date('Y-m-d').'_'.time().'_stocktransfer';

		$this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting Year closing details
	function year_closing()
	{
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <tr>
                                       <th>'.SLNO.'</th>
                                       <th>'.DIVISIONNAME.'</th>
                                       <th>'.YEARFROM.'</th>
                                       <th>'.YEARTO.'</th>
                                       <th>'.DATEFROM.'</th>
                                       <th>'.DATETO.'</th>
                                    </tr>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $locId      		 = array_filter(@split(',',$row->vLocation));
                $locName    		 = $this->common_model->getLocationName($locId);
                $count      		 = $key+1;

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$count.'</td>
                            <td>'.stripslashes($row->vTitle).'</td>
                            <td>'.stripslashes($row->vFYear).'</td>
                            <td>'.stripslashes($row->vTYear).'</td>
                            <td>'.stripslashes($row->dFDate).'</td>
                            <td>'.stripslashes($row->dTDate).'</td>
                    </tr>';
                $viewAccessList  = $this->db->query("SELECT yc.*,ycd.*,
													  aha.vTitle as accName,aha.vACode as accCode,
													  CASE
														WHEN ycd.vMode = 'Cr' THEN 'Credit'
													  ELSE 'Debit' END as mode FROM {PRE}YearClosingDetails
														ycd LEFT JOIN {PRE}YearClosing yc
													 ON yc.nYCId = ycd.nYearCId
													  LEFT JOIN {PRE}AccountHeadAccounts aha ON ycd.nAHAId = aha.nId
													 WHERE yc.nYCId='".$row->nYCId."' ORDER BY ycd.nId DESC");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .=	'<tr>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.ACCNAME.'</th>
                                    <th>'.BUDGETMODE.'</th>
                                    <th>'.AMOUNTFC.'</th>
                                    <th colspan="2">'.AMOUNTDC.'</th>
                                 </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {

                        $pdfBody .= '<tr>
                                         <td>'.stripslashes($row1->accCode).'</td>
                                         <td>'.stripslashes($row1->accName).'</td>
                                         <td>'.stripslashes($row1->mode).'</td>
                                         <td>'.stripslashes($row1->vFCBal).'</td>
                                         <td colspan="2">'.stripslashes($row1->vDCBal).'</td>
                                     </tr>';
                    }//end foreach
                }//end if
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), YEARCLOSINGDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_yearclosing';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
	}//end function

    //function for exporting ticket booking details
	function ticket_booking()
	{
	   @ini_set('memory_limit','120M');
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			$pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.SLNO.'</strong></th>
								   <th><strong>'.DIVISIONNAME.'</strong></th>
							       <th><strong>'.LOCATIONNAME.'</strong></th>
								   <th><strong>'.TYPE.'</strong></th>
								   <th><strong>'.CUSTDATE.'</strong></th>
								   <th><strong>'.BUDGETDOCNUMBER.'</strong></th>
								   <th><strong>'.GDS.'</strong></th>
								   <th><strong>'.AIRLINENAME.'</strong></th>
								   <th><strong>'.TICKETNAME.'</strong></th>
								   <th><strong>'.ORIGINALTKT.'</strong></th>
								   <th><strong>'.PASSENGER.'</strong></th>
								   <th><strong>'.ISSUEDBY.'</strong></th>
							       <th><strong>'.BOOKEDBY.'</strong></th>
								   <th><strong>'.GDSPNR.'</strong></th>
								   <th><strong>'.AIRPNR.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
			foreach ($sqlArray->result() as $key=>$row)
			{
				$divName    = $this->common_model->get_field_name('Division','vTitle',' WHERE nId="'.$row->nDivision.'"');
				$locName    = $this->common_model->get_field_name('Location','vCode',' WHERE nId ="'.$row->nLocation.'"');
				$count      = $key+1;
				$dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';

				$pdfBody .= '<tr bgcolor="#ffffff">
									<td>'.$count.'</td>
									<td>'.stripslashes($divName).'</td>
									<td>'.stripslashes($locName).'</td>
									<td>'.stripslashes($row->tbType).'</td>
									<td>'.$dDate.'</td>
									<td>'.stripslashes($row->vDocNumber).'</td>
									<td>'.stripslashes($row->gdsName).'</td>
									<td>'.stripslashes($row->airlineNo).' - '.stripslashes($row->airlineName).'</td>
									<td>'.stripslashes($row->vTicketNumber).'</td>
									<td>'.stripslashes($row->vOriginalTkt).'</td>
									<td>'.stripslashes($row->traveller).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->vIssuedBy."' ")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->vBookedBy."' ")).'</td>
									<td>'.stripslashes($row->vGdspnr).'</td>
									<td>'.stripslashes($row->vAirpnr).'</td>
								</tr>';
									
				$viewAccessRow   = array();
				$viewAccessList  = $this->db->query("SELECT tb.*,td.*,tfd.*,d.vTitle as divName,l.vTitle as locName,
														t.vTitle as transName,ar.vAirlineNo as airlineNo,ar.vTitle as airlineName,
													 gds.vName as gdsName, 
														CASE 
															WHEN tb.vType = 'N' THEN 'Normal'
														WHEN tb.vType = 'V' THEN 'Void'
															WHEN tb.vType = 'C' THEN 'Conjuction'
														ELSE 'Re issue' END as tbType
													  FROM {PRE}TicketBooking tb LEFT JOIN {PRE}Division d ON d.nId = tb.nDivision 
														  LEFT JOIN {PRE}Location l ON l.nId = tb.nLocation LEFT JOIN 
													  {PRE}Transactions t ON t.nId = tb.nTransaction
														 LEFT JOIN {PRE}Gds gds ON tb.nGDS = gds.nGId
													  LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
													  	LEFT JOIN {PRE}TicketDetails td 
														 ON tb.nTId = td.nTicketId LEFT JOIN {PRE}TicketFareDetails tfd 
													  ON tfd.nTicketId = tb.nTId 
													  WHERE nTId = '".$row->nTId."' ORDER BY nTId DESC");
				if($viewAccessList->num_rows() > 0)
				{
					$pdfBody .=	'<tr bgcolor="#ffffff">
									<td colspan="22"><strong>'.TICKETDETAILS.'</strong></td>
									 </tr>
									 <tr bgcolor="#ffffff">
										<th><strong>'.SRNO.'</strong></th>
										<th><strong>'.CARRIER.'</strong></th>
										<th><strong>'.FLIGHTNO.'</strong></th>
										<th><strong>'.TKTCLASS.'</strong></th>
										<th><strong>'.TRAVELDATE.'</strong></th>
										<th><strong>'.SECTOR.'</strong></th>
										<th>&nbsp;</th>
										<th><strong>'.STATUS.'</strong></th>
										<th><strong>'.TIMEDEP.'</strong></th>
										<th><strong>'.TIMEARR.'</strong></th>
										<th><strong>'.DAY.'</strong></th>
									</tr>';											
					foreach ($viewAccessList->result() as $key1=>$row1)
					{
						$pdfBody .=	'<tr bgcolor="#ffffff">
										<td>1</td>
										<td>'.$row1->vCarrier1.'</td>										
										<td>'.$row1->vFlight1.'</td>
										<td>'.$row1->vClass1.'</td>
										<td>'.$row1->dTravelDate1.'</td>
										<td>'.$row1->vSector1.'</td>
										<td>'.$row1->vSector12.'</td>		
										<td>'.$row1->vStatus1.'</td>
										<td>'.$row1->vTimeDept1.'</td>
										<td>'.$row1->vTimeArr1.'</td>
										<td>'.$row1->vDay1.'</td>
									</tr>
									
									<tr bgcolor="#ffffff">
										<td>2</td>
										<td>'.$row1->vCarrier2.'</td>										
										<td>'.$row1->vFlight2.'</td>
										<td>'.$row1->vClass2.'</td>
										<td>'.$row1->dTravelDate2.'</td>
										<td>'.$row1->vSector2.'</td>
										<td>'.$row1->vSector22.'</td>		
										<td>'.$row1->vStatus2.'</td>
										<td>'.$row1->vTimeDept2.'</td>
										<td>'.$row1->vTimeArr2.'</td>
										<td>'.$row1->vDay2.'</td>
									</tr>
									
									<tr bgcolor="#ffffff">
										<td>3</td>
										<td>'.$row1->vCarrier3.'</td>										
										<td>'.$row1->vFlight3.'</td>
										<td>'.$row1->vClass3.'</td>
										<td>'.$row1->dTravelDate3.'</td>
										<td>'.$row1->vSector3.'</td>
										<td>'.$row1->vSector33.'</td>		
										<td>'.$row1->vStatus3.'</td>
										<td>'.$row1->vTimeDept3.'</td>
										<td>'.$row1->vTimeArr3.'</td>
										<td>'.$row1->vDay3.'</td>
									</tr>
									
									<tr bgcolor="#ffffff">
										<td>4</td>
										<td>'.$row1->vCarrier4.'</td>										
										<td>'.$row1->vFlight4.'</td>
										<td>'.$row1->vClass4.'</td>
										<td>'.$row1->dTravelDate4.'</td>
										<td>'.$row1->vSector4.'</td>
										<td>'.$row1->vSector44.'</td>		
										<td>'.$row1->vStatus4.'</td>
										<td>'.$row1->vTimeDept4.'</td>
										<td>'.$row1->vTimeArr4.'</td>
										<td>'.$row1->vDay4.'</td>
									</tr>
									
									<tr>
										<td><strong>'.SEGMENT.'</strong></td>
										<td>'.$row1->vSegment.'</td>
										
										<td><strong>'.DESTINATION.'</strong></td>
										<td>'.$row1->vDestination.'</td>
								    </tr>';

						$conjId	     = $this->common_model->get_field_name('TicketBooking','nTId',
																					" WHERE vConjTkt = '".$row1->nTId."'");
						$conjTkt	 = $this->common_model->get_field_name('TicketBooking','vTicketNumber',
																					" WHERE vConjTkt = '".$row1->nTId."'");																					
						 
						$showConjVal = $this->db->query("SELECT * FROM {PRE}TicketDetails WHERE nTDId = '".$conjId."'");
						foreach ($showConjVal->result() as $key2 => $row2)
						{
							$pdfBody .= '<tr>
										<td colspan="12"><strong>'.CONJUCTIONDETAILS.'</strong></td>
									</tr>
						
									<tr>
										<td><strong>'.CONJUCTIONTKT.':</strong></td>
										<td>'.$conjTkt.'</td>
									</tr>
									
									<tr>
										<td><strong>'.SRNO.'</strong></td>
										<td><strong>'.CARRIER.'</strong></td>
										<td><strong>'.FLIGHTNO.'</strong></td>
										<td><strong>'.TKTCLASS.'</strong></td>
										<td><strong>'.TRAVELDATE.'</strong></td>
										<td><strong>'.SECTOR.'</strong></td>
										<td>&nbsp;</td>
										<td><strong>'.STATUS.'</strong></td>
										<td><strong>'.TIMEDEP.'</strong></td>
										<td><strong>'.TIMEARR.'</strong></td>
										<td><strong>'.DAY.'</strong></td>
									</tr>
									<tr>
										<td>1</td>
										<td>'.$row2->vCarrier1.'</td>
										<td>'.$row2->vFlight1.'</td>
										<td>'.$row2->vClass1.'</td>
										<td>'.$row2->dTravelDate1.'</td>
										<td>'.$row2->vSector1.'</td>
										<td>'.$row2->vSector12.'</td>
										<td>'.$row2->vStatus1.'</td>
										<td>'.$row2->vTimeDept1.'</td>
										<td>'.$row2->vTimeArr1.'</td>
										<td>'.$row2->vDay1.'</td>
									</tr>
						
									<tr>
										<td>2</td>
										<td>'.$row2->vCarrier2.'</td>
										<td>'.$row2->vFlight2.'</td>
										<td>'.$row2->vClass2.'</td>
										<td>'.$row2->dTravelDate2.'</td>
										<td>'.$row2->vSector2.'</td>
										<td>'.$row2->vSector22.'</td>
										<td>'.$row2->vStatus2.'</td>
										<td>'.$row2->vTimeDept2.'</td>
										<td>'.$row2->vTimeArr2.'</td>
										<td>'.$row2->vDay2.'</td>
									</tr>
						
									<tr>
										<td>3</td>
										<td>'.$row2->vCarrier3.'</td>
										<td>'.$row2->vFlight3.'</td>
										<td>'.$row2->vClass3.'</td>
										<td>'.$row2->dTravelDate3.'</td>
										<td>'.$row2->vSector3.'</td>
										<td>'.$row2->vSector33.'</td>
										<td>'.$row2->vStatus3.'</td>
										<td>'.$row2->vTimeDept3.'</td>
										<td>'.$row2->vTimeArr3.'</td>
										<td>'.$row2->vDay3.'</td>
									</tr>
						
									<tr>
										<td>4</td>
										<td>'.$row2->vCarrier4.'</td>
										<td>'.$row2->vFlight4.'</td>
										<td>'.$row2->vClass4.'</td>
										<td>'.$row2->dTravelDate4.'</td>
										<td>'.$row2->vSector4.'</td>
										<td>'.$row2->vSector44.'</td>
										<td>'.$row2->vStatus4.'</td>
										<td>'.$row2->vTimeDept4.'</td>
										<td>'.$row2->vTimeArr4.'</td>
										<td>'.$row2->vDay4.'</td>
									</tr>
	
									<tr>
										<td><strong>'.SEGMENT.'</strong></td>
										<td>'.$row2->vSegment.'</td>
										
										<td><strong>'.DESTINATION.'</strong></td>
										<td>'.$row2->vDestination.'</td>
									</tr>';
								
						}//end foreach
						
					$pdfBody .= '<tr>
										<td><strong>'.REMARKS.'</strong></td>
										<td>'.$row1->vRemarks.'</td>
									</tr>';
					
					switch($row1->vFare)
					{
						case "S" :
							$fare	= 'Special';
						break;
						
						case "N" :
							$fare= 'Normal';
						break;
					}//end switch
					
					$pdfBody .= '
								<tr bgcolor="#ffffff">
									<td colspan="22"><strong>'.FAREDETAILS.'</strong></td>
								</tr>
								
								<tr>
									<td><strong>'.FARE.':</strong></td>
									<td>'.$fare.'</td>
					
									<td><strong>'.TKTFARECODE.':</strong></td>
									<td>'.$row1->vFareCode.'</td>

									<td><strong>'.FACE.':</strong></td>
									<td>'.$row1->vFaceValue.'</td>
									
									<td><strong>'.MARKETFARE.':</strong></td>
									<td>'.$row1->vMKTFare.'</td>
								</tr>
								
								<tr>
									<td><strong>'.FCTAX.':</strong></td>
									<td>'.$row1->vTax1.'</td>
					
									<td><strong>'.FCTAX.':</strong></td>
									<td>'.$row1->vTax2.'</td>
					
									<td><strong>'.FCTAX.':</strong></td>
									<td>'.$row1->vTax3.'</td>
									
									<td><strong>'.TOTALTAX.':</strong></td>
									<td>'.$row1->vTaxTotal.'</td>

									<td><strong>'.FCFARE.':</strong></td>
									<td>'.$row1->vFCFare.'</td>

									<td><strong>'.FCTAX.':</strong></td>
									<td colspan="3">'.$row1->vFCTax.'</td>
								</tr> 
								
								<tr>
									<td><strong>'.CURRNAME.':</strong></td>
									<td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle', " WHERE nId='".$row1->nCurrency."' ")).'</td>

									<td><strong>'.CURRRATE.':</strong></td>
									<td colspan="3">'.stripslashes($this->common_model->get_field_name('Currency','vRate', " WHERE nId='".$row->nCurrency."' ")).'</td>
								</tr> 
								
								<tr>
									<td><strong>'.COMM.':</strong></td>
									<td>'.$row1->nComm.'</td>
					
									<td><strong>'.COMMAMT.':</strong></td>
									<td>'.$row1->nCommAmt.'</td>
					
									<td><strong>'.GSAPERCENT.':</strong></td>
									<td>'.$row1->nGSAComm.'</td>
									
									<td><strong>'.COMMAMT.':</strong></td>
									<td>'.$row1->nGSAAmt.'</td>
					
									<td><strong>'.ORCPERCENT.':</strong></td>
									<td>'.$row1->nORC.'</td>
									
									<td><strong>'.COMMAMT.' :</strong></td>
									<td>'.$row1->nORCAmt.'</td>
					
									<td><strong>'.SALESAMT.'</strong></td>
									<td>'.$row1->vSalesAmt.'</td>
								</tr>
								
								<tr>
									<td><strong>'.ACCCODENAME.':</strong></td>
									<td>'.$this->common_model->get_field_name('VendorMaster','vName',' WHERE nVId = "'.$row1->nAcc.'"').'( '.$this->common_model->get_field_name('VendorMaster','vCode',' WHERE nVId = "'.$row1->nAcc.'"').' )'.'</td>	

									<td><strong>'.ACCHEAD.':</strong></td>
									<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$row1->nAccHead.'"').'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode',' WHERE nId = "'.$row1->nAccHead.'"').' )'.'</td>	
																
									<td><strong>'.NETTOVENDOR.':</strong></td>
									<td>'.$row1->nNetVendor.'</td>
								</tr>';
					}//end foreach

				}//end if
			}//end for each
			
			$pdfBody  .= '</tbody></table>';
		}//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';
		
		$pdfFormat = read_file('./static/pdf/pdf.html');
		$arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
		$arrReplace	= array($this->common_model->get_site_title(), TICKETBOOKINGDETAILS, $pdfBody);
		$pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
		
		//write content to file
		write_file('./static/pdf/pdf1.html', $pdfBody);
		$download_fileName = date('Y-m-d').'_'.time().'_ticketbooking';

		$this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A2');
	}//end function

    //function for exporting hotel voucher details
	function hotel_voucher()
	{
	   $this->common_model->check_admin_authorization();
	   session_start();
					
		$pdfBody         	 = array();
		$sqlArray        	 = $this->db->query($_SESSION['sess_query']);

		if ($sqlArray->num_rows() > 0)
		{
			$pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.SLNO.'</strong></th>
								   <th><strong>'.DIVISIONNAME.'</strong></th>
								   <th><strong>'.LOCATIONNAME.'</strong></th>
								   <th><strong>'.CUSTDATE.'</strong></th>
								   <th><strong>'.TRANSNAME.'</strong></th>
								   <th><strong>'.DOCUMENTNUMBER.'</strong></th>
								   <th><strong>'.TYPE.'</strong></th>
								   <th><strong>'.VOUCHERID.'</strong></th>
								   <th><strong>'.FILEID.'</strong></th>
								   <th><strong>'.ISSUEDBY.'</strong></th>
								   <th><strong>'.BOOKEDBY.'</strong></th>
								   <th><strong>'.PASSENGER.'</strong></th>
								   <th><strong>'.COUNTRYNAME.'</strong></th>
								   <th><strong>'.CITYNAME.'</strong></th>
								   <th><strong>'.HOTELNAME.'</strong></th>
								   <th><strong>'.ROOMTYPE.'</strong></th>
								   <th><strong>'.NOOFUNIT.'</strong></th>
								   <th><strong>'.SERVICES.'</strong></th>
								   <th><strong>'.CHECKIN.'</strong></th>
								   <th><strong>'.CHECKOUT.'</strong></th>
								   <th><strong>'.NIGHTS.'</strong></th>
								   <th><strong>'.ADULT.'</strong></th>
								   <th><strong>'.CHILD.'</strong></th>
								   <th><strong>'.REMARKS.'</strong></th>
								   <th><strong>'.ACCCODENAME.'</strong></th>
								   <th><strong>'.CURRNAME.'</strong></th>
								   <th><strong>'.CURRRATE.'</strong></th>
								</tr>
							</thead>
							<tbody>';
			foreach ($sqlArray->result() as $key=>$row)
			{
				switch($row->vModeofpay)
				{
					case "Cr" :
						$modeofPay	= 'Credit';
					break;
					
					case "Ca" :
						$modeofPay	= 'Cash';
					break;
		
					case "Card" :
						$modeofPay	= 'Card';
					break;
				}//end switch

				$divName    = $this->common_model->get_field_name('Division','vTitle',' WHERE nId="'.$row->nDivision.'"');
				$locName    = $this->common_model->get_field_name('Location','vCode',' WHERE nId ="'.$row->vLocation.'"');
				$count      = $key+1;
				$dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';

				$pdfBody .= '<tr>
									<td>'.$count.'</td>
									<td>'.stripslashes($divName).'</td>
									<td>'.stripslashes($locName).'</td>
									<td>'.$dDate.'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$row->nTransaction."'")).'</td>
									<td>'.stripslashes($row->hvType).'</td>
									<td>'.stripslashes($row->vVoucherId).'</td>
									<td>'.stripslashes($row->vFileId).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->vIssuedBy."' ")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->vBookedBy."' ")).'</td>
									<td>'.stripslashes($row->vPassenger).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Country','vName',' WHERE nId = "'.$row->nCountry.'" AND nParentId = "0" ')).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Country','vName',' WHERE nId = "'.$row->nCity.'" AND nParentId != "0" ')).'</td>
									
									<td>'.stripslashes($this->common_model->get_field_name('Hotel','vName',' WHERE nHId = "'.$row->vHotelName.'" ')).'</td>
									<td>'.stripslashes($row->vRoomType).'</td>
									<td>'.stripslashes($row->vNoOfUnit).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Services','vTitle',' WHERE nServId = "'.$row->vServices.'" ')).'</td>
									<td>'.stripslashes($row->dCheckIn).'</td>
									<td>'.stripslashes($row->dCheckOut).'</td>
									<td>'.stripslashes($row->vNight).'</td>
									<td>'.stripslashes($row->vAdult).'</td>
									<td>'.stripslashes($row->vChild).'</td>
									<td>'.stripslashes($row->vRemarks).'</td>
									<td>'.($row->accName).'( '.($row->accCode).' )'.'</td>
									<td>'.$this->common_model->get_field_name('Currency','vTitle',' WHERE nId = "'.$row->nCurrency.'" ').'</td>
									<td>'.$row->nRate.'</td></tr>';
									
				$viewAccessRow   = array();
				$viewAccessList  = $this->db->query("SELECT *,
														CASE 
															WHEN hd.vRoomType = 'SGL' THEN 'Single'
															WHEN hd.vRoomType = 'DBL' THEN 'Double'
														ELSE 'SUV' END as roomType FROM {PRE}HotelDetails hd LEFT JOIN {PRE}HotelVoucher hv ON hv.nHVId =                                                     nHotelId WHERE hv.nHVId = '".$row->nHVId."'");
				if($viewAccessList->num_rows() > 0)
				{
					$pdfBody .=	'<tr>
									<td colspan="11"><strong>'.HOTELDETAILS.'</strong></td>
								 </tr>
								 <tr>
									<th><strong>'.SRNO.'</strong></th>
									<th><strong>'.ROOMTYPE.'</strong></th>
									<th><strong>'.INDATE.'</strong></th>
									<th><strong>'.OUTDATE.'</strong></th>
									<th><strong>'.NIGHTS.'</strong></th>
									<th><strong>'.NOOFROOMS.'</strong></th>
									<th><strong>'.RATENETFC.'</strong></th>
									<th><strong>'.TOTALINFC.'</strong></th>
									<th><strong>'.RATENETDC.'</strong></th>
									<th><strong>'.TOTALINDC.'</strong></th>
									<th><strong>'.EXCHRATE.'</strong></th>
								</tr>';											
					foreach ($viewAccessList->result() as $key1=>$row1)
					{
						$pdfBody .=	'<tr>
										 <td>'.($key1+1).'</td>
										 <td>'.$row1->roomType.'</td>
										 <td>'.$row1->dCheckInDate.'</td>
										 <td>'.$row1->dCheckOutDate.'</td>
										 <td>'.$row1->nNights.'</td>
										 <td>'.$row1->nNoOfRooms.'</td>
										 <td>'.$row1->nRTNhtFc.'</td>
										 <td>'.$row1->vTotalInFc.'</td>
										 <td>'.$row1->nRTNhtDc.'</td>
										 <td>'.$row1->vTotalInDC.'</td>
										 <td>'.$row1->vExchRate.'</td>
									</tr>';
					}//end foreach
					
				$pdfBody .= '<tr>
									<td colspan="6" align="right"><strong>'.TOTALAMT.'</strong></td>
									<td><strong>'.$row->vTotalRTFC.'</strong></td>
									<td><strong>'.$row->vTotalFC.'</strong></td>
									<td><strong>'.$row->vTotalRTDC.'</strong></td>
									<td colspan="2"><strong>'.$row->vTotalDC.'</strong></td>
								 </tr>';
								 
				$pdfBody .= '<tr>
								<td><strong>'.AGENTCONFID.'</strong></td>
								<td>'.$row->vAgentConfId.'</td>
								<td><strong>'.HOTELCONFID.'</strong></td>
								<td>'.$row->vHotelConfId.'</td>
								<td><strong>'.BOOKINGID.'</strong></td>
								<td colspan="7">'.$row->vBookingId.'</td>
							  </tr>';

				$pdfBody  .=	'<tr>
									<td colspan="11"><strong>'.BILLINGDETAILS.'</strong></td>
								   </tr>
				
								   <tr>
									<td><strong>'.INVOICE.':</strong></td>
									<td>'.$row->vInvoice.'</td>
				
									<td><strong>'.MODEOFPAYMENT.':</strong></td>
									<td>'.$modeofPay.'</td>
				
									<td><strong>'.ACCNAME.':</strong></td>
									<td colspan="6">'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$row->nAccount.'"').'</td>
								   </tr>
					
								   <tr>
									<td><strong>'.SUBNAME.':</strong></td>
									<td>'.$row->vSubName.'</td>
				
									<td><strong>'.AGENTAC.':</strong></td>
									<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$row->nAgentAc.'"').'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode',' WHERE nId = "'.$row->nAgentAc.'"').' )'.'</td>
				
									<td><strong>'.AGENTAMT.':</strong></td>
									<td >'.$row->vAgentAmt.'</td>
				
									<td><strong>'.LPO.':</strong></td>
									<td colspan="4">'.$row->vLPO.'</td>
								   </tr>
				
								   <tr>
									<td><strong>'.SALESAMT.':</strong></td>
									<td>'.$row->vSalesAmt.'</td>
				
									<td><strong>'.DISCOUNT.':</strong></td>
									<td>'.$row->vDiscount.'</td>
				
									<td><strong>'.DISCOUNTAMT.':</strong></td>
									<td colspan="6">'.$row->vDiscountAmt.'</td>
								   </tr>	
								   
								   <tr>
									<td><strong>'.COLECTED.':</strong></td>
									<td>'.$row->vCollected.'</td>
									
									<td><strong>'.SERVICEPERCENT.':</strong></td>
									<td >'.$row->vServicePercent.'</td>
				
									<td><strong>'.SERVICEAMT.':</strong></td>
									<td colspan="6">'.$row->vServiceAmt.'</td>
									</tr>';
				}//end if
			}//end for each
			
			$pdfBody  .= '</tbody></table>';
		}//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';
		
		$pdfFormat = read_file('./static/pdf/pdf.html');
		$arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
		$arrReplace	= array($this->common_model->get_site_title(), HOTELVOUCHERDETAILS, $pdfBody);
		$pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
		
		//write content to file
		write_file('./static/pdf/pdf1.html', $pdfBody);
		$download_fileName = date('Y-m-d').'_'.time().'_hotelvoucher';
		$this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
	}//end function

    //function for exporting refund register details
    function refund_register()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody         	 = array();
        $sqlArray        	 = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
								   <th>'.DIVISIONNAME.'</th>
							       <th>'.LOCATIONNAME.'</th>
								   <th>'.CUSTDATE.'</th>
								   <th>'.DOCNUMBER.'</th>
								   <th>'.TYPE.'</th>
								   <th>'.PASSENGER.'</th>
								   <th>'.ACCCODENAME.'</th>
								   <th>'.SECTOR.'</th>
								   <th>'.SEGMENT.'</th>
							       <th>'.DESTINATION.'</th>
								   <th>'.ACAPID.'</th>
								   <th>'.MODEOFPAYMENT.'</th>
								   <th>'.CURRNAME.'</th>
								   <th>'.CURRRATE.'</th>
								   <th>'.GDS.'</th>
								   <th>'.SALESDT.'</th>
								   <th>'.REFUNDDT.'</th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->nLocation."'");
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';

                $pdfBody .= '<tr>
									<td>'.stripslashes($divName).'</td>
									<td>'.stripslashes($locName).'</td>
									<td>'.$dDate.'</td>
									<td>'.stripslashes($row->vDocNumber).'</td>
									<td>'.stripslashes($row->rType).'</td>
									<td>'.stripslashes($row->vPassenger).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('VendorMaster','vName',' WHERE nVId = "'.$row->nAccount.'"').'('.$this->common_model->get_field_name('VendorMaster','vCode',' WHERE nVId = "'.$row->nAccount.'"').')').'</td>
									<td>'.stripslashes($row->vSector).'</td>
									<td>'.stripslashes($row->vSegment).'</td>
									<td>'.stripslashes($row->vDestination).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('CustomerMaster','vName',' WHERE nCId = "'.$row->vAcAPID.'"').'( '.$this->common_model->get_field_name('CustomerMaster','vCode',' WHERE nCId = "'.$row->vAcAPID.'"').' )').'</td>
									<td>'.stripslashes($row->rMode).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Currency', 'vTitle', " WHERE nId='" . $row->nCurrency . "' ")).'</td>
									<td>'.stripslashes($row->nRate).'</td>
									<td>'.stripslashes($row->nGDS).'</td>
									<td>'.stripslashes($row->vSalesDet).'</td>
									<td>'.stripslashes($row->vRefundDet).'</td>
							</tr>';

                $pdfBody .=	'<tr bgcolor="#ffffff">
                                <td><strong>'.BILLINGDETAILS.'</strong></td>
                             </tr>
                             <tr bgcolor="#ffffff">
                                <th><strong>'.INVOICE.'</strong></th>
                                <th><strong>'.MODEOFPAYMENT.'</strong></th>
                                <th><strong>'.ACCCODENAME.'</strong></th>
                                <th><strong>'.SUBCODE.'</strong></th>
                                <th><strong>'.AGENTNAME.'</strong></th>
                                <th><strong>'.AGENTAMT.'</strong></th>
                                <th><strong>'.LPO.'</strong></th>
                                <th><strong>'.SALESAMT.'</strong></th>
                                <th><strong>'.DISCOUNT.'</strong></th>
                                <th><strong>'.DISCOUNTAMT.'</strong></th>
                                <th><strong>'.COLECTED.'</strong></th>
                                <th><strong>'.SERVICEPERCENT.'</strong></th>
                                <th><strong>'.SERVICEAMT.'</strong></th>
                            </tr>';
                    $pdfBody .=	'<tr bgcolor="#ffffff">
                                    <td>'.stripslashes($row->vInvoice).'</td>
                                    <td>'.stripslashes($row->rbMode).'</td>
                                    <td>'.stripslashes($row->nAcCode).'</td>
                                    <td>'.stripslashes($row->vAcSubCode).'</td>
                                    <td>'.stripslashes($row->nAgentAc).'</td>
                                    <td>'.stripslashes($row->rbAgentAmt).'</td>
                                    <td>'.stripslashes($row->vLPO).'</td>
                                    <td>'.stripslashes($row->rbSale).'</td>
                                    <td>'.stripslashes($row->vDiscount).'</td>
                                    <td>'.stripslashes($row->rbDisc).'</td>
                                    <td>'.stripslashes($row->vCollected).'</td>
                                    <td>'.stripslashes($row->vServicePercent).'</td>
                                    <td>'.stripslashes($row->rbServ).'</td>
                                </tr>';

                $pdfBody .=	'<tr bgcolor="#ffffff">
                                <td><strong>'.NETFROMVENDOR.'</strong></td>
                             </tr>
                             <tr bgcolor="#ffffff">
                                <th><strong>'.FARE.'</strong></th>
                                <th><strong>'.USEDFARE.'</strong></th>
                                <th><strong>'.SALESAMT.'</strong></th>
                                <th><strong>'.SERVICEAMT.'</strong></th>
                                <th><strong>'.COUNTRYNAME.'</strong></th>
                                <th><strong>'.TAX.'</strong></th>
                                <th><strong>'.USEDTAX.'</strong></th>
                                <th><strong>'.OURCHARGE.'</strong></th>
                                <th><strong>'.DISCAMT.'</strong></th>
                                <th><strong>'.CITYNAME.'</strong></th>
                                <th><strong>'.COMM.'</strong></th>
                                <th><strong>'.COMM.'</strong></th>
                                <th><strong>'.OTHADJ1.'</strong></th>
                                <th><strong>'.OTHADJ2.'</strong></th>
                                <th><strong>'.HOTELNAME.'</strong></th>
                                <th><strong>'.OTHER.'</strong></th>
                                <th><strong>'.VENDORNAME.'</strong></th>
                                <th><strong>'.OTHADJ.'</strong></th>
                                <th><strong>'.AGENTAMT.'</strong></th>
                                <th><strong>'.ISSUEDBY.'</strong></th>
                                <th><strong>'.NETAMT.'</strong></th>
                                <th><strong>'.NETAMT.'</strong></th>
                                <th><strong>'.PAIDTO.'</strong></th>
                            </tr>';
                $pdfBody .=	'<tr bgcolor="#ffffff">
                                    <td>'.stripslashes($row->vFareAmt).'</td>
                                    <td>'.stripslashes($row->vUsedFare).'</td>
                                    <td>'.stripslashes($row->rvdSale).'</td>
                                    <td>'.stripslashes($row->rvdServ).'</td>
                                    <td>'.stripslashes($this->common_model->get_field_name('Country', 'vName', " WHERE nId='" . $row->nCountry . "' ")).'</td>
                                    <td>'.stripslashes($row->vTax).'</td>
                                    <td>'.stripslashes($row->vUsedTax).'</td>
                                    <td>'.stripslashes($row->vOurCharge).'</td>
                                    <td>'.stripslashes($row->rvdDisc).'</td>
                                    <td>'.stripslashes($this->common_model->get_field_name('Country', 'vName', " WHERE nId='" . $row->nCity . "' ")).'</td>
                                    <td>'.stripslashes($row->vComm1).'</td>
                                    <td>'.stripslashes($row->vComm2).'</td>
                                    <td>'.stripslashes($row->vOthA1).'</td>
                                    <td>'.stripslashes($row->vOthA2).'</td>
                                    <td>'.stripslashes($this->common_model->get_field_name('Hotel', 'vName', " WHERE nHId='" . $row->nHotel . "' ")).'</td>
                                    <td>'.stripslashes($row->nOther).'</td>
                                    <td>'.stripslashes($row->nVendor).'</td>
                                    <td>'.stripslashes($row->vOthA3).'</td>
                                    <td>'.stripslashes($row->rvdAgentAmt).'</td>
                                    <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='" . $row->vIssuedBy . "' ")).'</td>
                                    <td>'.stripslashes($row->vNetAmt1).'</td>
                                    <td>'.stripslashes($row->vNetAmt2).'</td>
                                    <td>'.stripslashes($row->vPaidTo).'</td>
                                </tr>';

                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT *,CASE WHEN vTktType = 'Tkt' THEN 'Ticket' WHEN vTktType = 'Xo' THEN 'Xo' ELSE 'Hotel'
		                                    	END as type FROM {PRE}RefundRegisterDetails WHERE nRefundId='".$row->nRId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .=	'<tr bgcolor="#ffffff">
									<td><strong>'.REFUNDDETAILS.'</strong></td>
                                 </tr>
                                 <tr bgcolor="#ffffff">
                                    <th><strong>'.TYPE.'</strong></th>
                                    <th><strong>'.NEWOLD.'</strong></th>
                                    <th><strong>'.TICKETNO.'</strong></th>
                                    <th><strong>'.ORGTKT.'</strong></th>
                                    <th><strong>'.AIRLINE.'</strong></th>
                                    <th><strong>'.PASSENGER.'</strong></th>
                                    <th><strong>'.REMARKS.'</strong></th>
                                </tr>';
                    foreach ($viewAccessList->result() as $key1=>$row1)
                    {
                        switch($row1->type)
                        {
                            case "Xo" :
                                $this->_TableName   = 'XoBooking';
                                $this->_extraField  = 'vXoNumber';
                                $this->dbId         = 'nXId';
                                break;

                            case "Ticket" :
                                $this->_TableName   = 'TicketBooking';
                                $this->_extraField  = 'vTicketNumber';
                                $this->dbId         = 'nTId';
                                break;

                            case "Hotel" :
                                $this->_TableName   = 'HotelVoucher';
                                $this->_extraField  = 'vVoucherId';
                                $this->dbId         = 'nHVId';
                                break;
                        }//end switch

                        switch($row1->vNewOld)
                        {
                            case "N" :
                                $newStatus   = 'New';
                            break;

                            case "O" :
								$newStatus   = 'Old';                               
							break;
                        }//end switch

                        $pdfBody .=	'<tr bgcolor="#ffffff">
                                        <td>'.stripslashes($row1->type).'</td>
                                        <td>'.stripslashes($newStatus).'</td>
                                        <td>'.stripslashes($this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->dbId." = '".$row1->nTktNum."'")).'</td>
                                        <td>'.stripslashes($row1->vOrgTktNum).'</td>
                                        <td>'.stripslashes($row1->vAirline).'</td>
                                        <td>'.stripslashes($row1->vPassenger).'</td>
                                        <td>'.stripslashes($row1->vRemarks).'</td>
                                    </tr>';
                    }//end foreach
                }//end if
            }//end for each

            $pdfBody  .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), REFUNDDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_refund';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    //function for exporting xo booking details
    function xo_booking()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody         	 = array();
        $sqlArray        	 = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.DIVISIONNAME.'</strong></th>
							       <th><strong>'.LOCATIONNAME.'</strong></th>
								   <th><strong>'.CUSTDATE.'</strong></th>
								   <th><strong>'.TRANSNAME.'</strong></th>
								   <th><strong>'.DOCNUMBER.'</strong></th>
								   <th><strong>'.TYPE.'</strong></th>
								   <th><strong>'.AIRLINENAME.'</strong></th>
								   <th><strong>'.TICKETNAME.'</strong></th>
								   <th><strong>'.XONAME.'</strong></th>
							       <th><strong>'.GDS.'</strong></th>
								   <th><strong>'.BOOKEDBY.'</strong></th>
								   <th><strong>'.ISSUEDBY.'</strong></th>
								   <th><strong>'.PASSENGER.'</strong></th>
								   <th><strong>'.ORIGINALTKT.'</strong></th>
								   <th><strong>'.SEGMENT.'</strong></th>
								   <th><strong>'.DESTINATION.'</strong></th>
								   <th><strong>'.GDSPNR.'</strong></th>
								   <th><strong>'.AIRPNR.'</strong></th>
								   <th><strong>'.CURRNAME.'</strong></th>
								   <th><strong>'.CURRRATE.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->nLocation."'");
                $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';

                $pdfBody .= '<tr bgcolor="#ffffff">
									<td>'.stripslashes($divName).'</td>
									<td>'.stripslashes($locName).'</td>
									<td>'.$dDate.'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$row->nTransaction."'")).'</td>
									<td>'.stripslashes($row->vType).'</td>
									<td>'.stripslashes($row->airlineName . ' (' . $row->airlineNo . ')').'</td>
									<td>'.stripslashes($row->tktNumber).'</td>
									<td>'.stripslashes($row->vXoNumber).'</td>
									<td>'.stripslashes($row->gdsName).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='" . $row->vBookedBy . "' ")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='" . $row->vIssuedBy . "' ")).'</td>
									<td>'.stripslashes($row->traveller).'</td>
									<td>'.stripslashes($row->vOriginalXo).'</td>
									<td>'.stripslashes($row->vSegment).'</td>
									<td>'.stripslashes($row->vDestination).'</td>
									<td>'.stripslashes($row->vGdspnr).'</td>
									<td>'.stripslashes($row->vAirpnr).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Currency', 'vTitle', " WHERE nId='" . $row->nCurrency . "' ")).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('Currency', 'vRate', " WHERE nId='" . $row->nCurrency . "' ")).'</td></tr>';

                $pdfBody .=	'<tr bgcolor="#ffffff">
                                <td colspan="20"><strong>'.TICKETDETAILS.'</strong></td>
                             </tr>
                             <tr bgcolor="#ffffff">
                                <th><strong>'.CARRIER.'</strong></th>
                                <th><strong>'.FLIGHTNO.'</strong></th>
                                <th><strong>'.TKTCLASS.'</strong></th>
                                <th><strong>'.TRAVELDATE.'</strong></th>
                                <th><strong>'.SECTOR.'</strong></th>
                                <th><strong>'.STATUS.'</strong></th>
                                <th><strong>'.TIMEDEP.'</strong></th>
                                <th><strong>'.TIMEARR.'</strong></th>
                                <th><strong>'.DAY.'</strong></th>
								<th colspan="10">&nbsp;</th>
                            </tr>';
                //fetching Ticket details
                $getTicketDetails=$this->db->query("SELECT * FROM {PRE}XoDetails WHERE nXoId='".$row->nXId."'");
                foreach ($getTicketDetails->result() as $key1 => $row1) :
                $pdfBody .=	'<tr bgcolor="#ffffff">
                                    <td>'.stripslashes($row1->vCarrier).'</td>
                                    <td>'.stripslashes($row1->vFlight).'</td>
                                    <td>'.stripslashes($row1->vClass).'</td>
                                    <td>'.stripslashes($row1->dTravelDate).'</td>
                                    <td>'.stripslashes($row1->vSector).'</td>
                                    <td>'.stripslashes($row1->vSector2).'</td>
                                    <td>'.stripslashes($row1->vStatus).'</td>
                                    <td>'.stripslashes($row1->vTimeDept).'</td>
                                    <td>'.stripslashes($row1->vTimeArr).'</td>
                                    <td>'.stripslashes($row1->vDay).'</td>
                                </tr>';
                endforeach;

                $pdfBody .=	'<tr bgcolor="#ffffff">
                                <td colspan="20"><strong>'.BILLINGDETAILS.'</strong></td>
                             </tr>
                             <tr bgcolor="#ffffff">
                                <th><strong>'.INVOICE.'</strong></th>
                                <th><strong>'.MODEOFPAYMENT.'</strong></th>
                                <th><strong>'.CUSTNAME.'</strong></th>
                                <th><strong>'.AGENTNAME.'</strong></th>
                                <th><strong>'.AGENTAC.'</strong></th>
                                <th><strong>'.AGENTAMT.'</strong></th>
                                <th><strong>'.LPO.'</strong></th>
                                <th><strong>'.SALESAMT.'</strong></th>
                                <th><strong>'.DISCOUNT.'</strong></th>
                                <th><strong>'.DISCOUNTAMT.'</strong></th>
                                <th><strong>'.COLECTED.'</strong></th>
                                <th><strong>'.SERVICEPERCENT.'</strong></th>
                                <th><strong>'.SERVICEAMT.'</strong></th>
                            </tr>';
                $pdfBody .=	'<tr bgcolor="#ffffff">
                                    <td>'.stripslashes($row->vInvoice).'</td>
                                    <td>'.stripslashes($row->vModeofpay).'</td>
                                    <td>'.stripslashes($this->common_model->get_field_name('CustomerMaster','vName',' WHERE nCId = "'.$row->nCustomer.'"')).'</td>
                                    <td>'.stripslashes($this->common_model->get_field_name('Agent','vName',' WHERE nAId = "'.$row->nAgent.'"')).'</td>
                                    <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$row->nAgentAc.'"').'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode',' WHERE nId = "'.$row->nAgentAc.'"').' )').'</td>
                                    <td>'.stripslashes($row->vAgentAmt).'</td>
                                    <td>'.stripslashes($row->vLPO).'</td>
                                    <td>'.stripslashes($row->vSalesAmt).'</td>
                                    <td>'.stripslashes($row->vDiscount).'</td>
                                    <td>'.stripslashes($row->vDiscountAmt).'</td>
                                    <td>'.stripslashes($row->vCollected).'</td>
                                    <td>'.stripslashes($row->vServicePercent).'</td>
                                    <td>'.stripslashes($row->vServiceAmt).'</td>
                                </tr>';

                $viewAccessRow   = array();
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}XoPassDetails WHERE nXoId='".$row->nXId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .=	'<tr bgcolor="#ffffff">
									<td colspan="20"><strong>'.PASSENGERDETAILS.'</strong></td>
                                 </tr>
                                 <tr bgcolor="#ffffff">
                                    <th><strong>'.ACCCODENAME.'</strong></th>
                                    <th><strong>'.REMARKS.'</strong></th>
									<th colspan="18">&nbsp;</th>
                                </tr>';
                    $pdfBody .=	'<tr bgcolor="#ffffff">
					                    <td>'.stripslashes($row->vRemarks).'</td>
                                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$row->nPassengerAC.'"').'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode',' WHERE nId = "'.$row->nPassengerAC.'"').' )').'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>'.PASSENGER.'</strong></td>
                                        <td><strong>'.FARE.'</strong></td>
                                        <td><strong>'.COMM.'</strong></td>
                                        <td><strong>'.COMMAMT.'</strong></td>
                                        <td><strong>'.FCTAX.'</strong></td>
                                        <td><strong>'.FCTAX.'</strong></td>
                                        <td><strong>'.FCTAX.'</strong></td>
                                        <td><strong>'.TOTALTAX.'</strong></td>
                                        <td><strong>'.NETTOVENDOR.'</strong></td>
                                    </tr>';
                    foreach ($viewAccessList->result() as $key2=>$row2)
                    {
                        $pdfBody .=	'<tr bgcolor="#ffffff">
                                        <td>'.stripslashes($this->common_model->get_field_name('CustomerMaster','vName', ' WHERE nCId = "'.$row2->vPass.'" ')).'( '.stripslashes($this->common_model->get_field_name('CustomerMaster','vCode', ' WHERE nCId = "'.$row2->vPass.'" ')).' )'.'</td>
                                        <td>'.stripslashes($row2->vFCTax).'</td>
                                        <td>'.stripslashes($row2->nComm).'</td>
                                        <td>'.stripslashes($row2->nORCAmt).'</td>
                                        <td>'.stripslashes($row2->vTax1).'</td>
                                        <td>'.stripslashes($row2->vTax2).'</td>
                                        <td>'.stripslashes($row2->vTax3).'</td>
                                        <td>'.stripslashes($row2->vTaxTotal).'</td>
                                        <td>'.stripslashes($row2->nNetVendor).'</td>
                                    </tr>';
                    }//end foreach
                }//end if
                 $pdfBody .='<tr>
                    <td><strong>'.GRANDTOTAL.':</strong></td>
                    <td>'.$row->vGrandFare.'</td>
                    <td></td>
                    <td></td>
                    <td>'.$row->vGrandTax1.'</td>
                    <td>'.$row->vGrandTax2.'</td>
                    <td>'.$row->vGrandTax3.'</td>
                    <td>'.$row->vGrandToalTax.'</td>
                    <td>'.$row->vGrandNetFare.'</td>
                 </tr>';
            }//end for each

            $pdfBody  .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), XOBOOKINGDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_Xo_booking';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    //function for exporting duplicate invoice details
    function duplicate_invoice()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody         	 = array();
        $sqlArray        	 = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.DIVISIONNAME.'</strong></th>
							       <th><strong>'.LOCATIONNAME.'</strong></th>
								   <th><strong>'.CUSTDATE.'</strong></th>
								   <th><strong>'.SALESTYPE.'</strong></th>
								   <th><strong>'.INVOICE.'</strong></th>
								   <th><strong>'.LPO.'</strong></th>
								   <th><strong>'.CRV.'</strong></th>
							       <th><strong>'.REFTKT.'</strong></th>
								   <th><strong>'.AGENTAC.'</strong></th>
								   <th><strong>'.TOTALNET.'</strong></th>
								   <th><strong>'.TOTALSERP.'</strong></th>
								   <th><strong>'.TOTALSERA.'</strong></th>
								   <th><strong>'.TOTALDISC.'</strong></th>
								   <th><strong>'.TOTALCOMMP.'</strong></th>
								   <th><strong>'.TOTALCOMMA.'</strong></th>
								   <th><strong>'.TOTALSAL.'</strong></th>
								   <th><strong>'.TOTALCOLL.'</strong></th>
								   <th><strong>'.TOTALAGENT.'</strong></th>
								   <th><strong>'.INVOICESTATUS.'</strong></th>
								   <th><strong>'.INVOICEREMARKS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation ."'");
                $mode    = $this->common_model->get_field_name('Transactions','vTitle'," WHERE nId = '".$row->vTransMode ."'");

                switch($row->nInvoiceStatus)
                {
                    case "N" :
                        $iStatus	= 'Normal';
                        break;

                    case "E" :
                        $iStatus	= 'Edited';
                        break;

                    case "R" :
                        $iStatus	= 'Refund';
                        break;

                    case "V" :
                        $iStatus	= 'Void';
                        break;
                }//end switch
                $pdfBody .= '
								<tr bgcolor="#ffffff">
									<td>'.stripslashes($divName).'</td>
									<td>'.stripslashes($locName).'</td>
									<td>'.$row->dDate.'</td>
									<td>'.stripslashes($mode).'</td>
									<td>'.$this->common_model->get_field_name('SalesClosing','vInvoiceNumber'," WHERE nSCId='".$row->vInvoiceNumber."'").'</td>
									<td>'.stripslashes($row->vLPO).'</td>
									<td>'.stripslashes($row->vCRV).'</td>
									<td>'.stripslashes($row->vRefTkt).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row->nAgentAc."' ")).'</td>
									<td>'.stripslashes($row->vTotalNetAmount).'</td>
									<td>'.stripslashes($row->vTotalServPercent).'</td>
									<td>'.stripslashes($row->vTotalServAmount).'</td>
									<td>'.stripslashes($row->vTotalDiscAmount).'</td>
									<td>'.stripslashes($row->vTotalCommPercent).'</td>
									<td>'.stripslashes($row->vTotalCommAmt).'</td>
									<td>'.stripslashes($row->vTotalSalesAmt).'</td>
									<td>'.stripslashes($row->vTotalCollected).'</td>
									<td>'.stripslashes($row->vTotalAgentAmt).'</td>
									<td>'.stripslashes($iStatus).'</td>
									<td>'.stripslashes($row->vRemarks).'</td>
								</tr>';
                $pdfBody .=	'
					<tr bgcolor="#ffffff">
                        <td><strong>'.TYPE.'</strong></td>
                        <td><strong>'.TICKETNAME.'</strong></td>
                        <td colspan="2"><strong>'.EMPNAME.'</strong></td>
                        <td colspan="2"><strong>'.AIRLINE.'</strong></td>
                        <td><strong>'.FACEFARE.'</strong></td>
                        <td><strong>'.TAX.'</strong></td>
                        <td><strong>'.COMMPERCENT.'</strong></td>
                        <td colspan="2"><strong>'.COMMISSIONAMT.'</strong></td>
                        <td><strong>'.NETAMT.'</strong></td>
                        <td><strong>'.SER.'</strong></td>
                        <td colspan="2"><strong>'.SERAMT.'</strong></td>
                        <td><strong>'.SLSAMT.'</strong></td>
                        <td><strong>'.DISCAMT.'</strong></td>
                        <td><strong>'.COLECTED.'</strong></td>
                        <td><strong>'.AGENTNAME.'</strong></td>
                        <td><strong>'.LPO.'</strong></td>
                    </tr>';
                //fetching Ticket details
                $getDetails = $this->db->query("SELECT *,CASE WHEN vType = 'Tkt' THEN 'Ticket' WHEN vType = 'Xo' THEN 'Xo' ELSE 'Hotel'
		                                    	END as type FROM {PRE}DuplicateInvoiceDetails WHERE nDupId = '".$row->nDId."'
											  ORDER BY nId DESC");
                foreach ($getDetails->result() as $key1 => $row1) :
                    switch($row1->type)
                    {
                        case "Xo" :
                            $this->_TableName   = 'XoBooking';
                            $this->_extraField  = 'vTicktManual';
                            $this->_tblld       = 'nXId';
                            break;

                        case "Ticket" :
                            $this->_TableName   = 'TicketBooking';
                            $this->_extraField  = 'vTicktManual';
                            $this->_tblld       = 'nTId';
                            break;

                        case "Hotel" :
                            $this->_TableName   = 'HotelVoucher';
                            $this->_extraField  = 'vTicktManual';
                            $this->_tblld       = 'nHVId';
                            break;
                    }//end switch

                    $pdfBody .=	'
						<tr bgcolor="#ffffff">
                            <td>'.stripslashes($row1->type).'</td>
                            <td>'.$this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->_tblld." = '".$row1->vTktNumber."'").'</td>
                            <td colspan="2">'.stripslashes($row1->vEmployee).'</td>
                            <td colspan="2">'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE vAirlineNo = '".$row1->nAirline."'")).'</td>
                            <td>'.stripslashes($row1->vFare).'</td>
                            <td>'.stripslashes($row1->vTax).'</td>
                            <td>'.stripslashes($row1->vComm).'</td>
                            <td colspan="2">'.stripslashes($row1->vCommAmt).'</td>
                            <td>'.stripslashes($row1->vNetAmt).'</td>
                            <td>'.stripslashes($row1->vServPercent).'</td>
                            <td colspan="2">'.stripslashes($row1->vServAmt).'</td>
                            <td>'.stripslashes($row1->vSlsAmt).'</td>
                            <td>'.stripslashes($row1->vDisAmt).'</td>
                            <td>'.stripslashes($row1->vCollected).'</td>
                            <td>'.stripslashes($row1->vAgentAmt).'</td>
                            <td>'.stripslashes($row1->vDLPO).'</td>
                        </tr>';
                endforeach;

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td colspan="3"><strong>'.DRACCNAME.'</strong></td>
                        <td colspan="3"><strong>'.ACHEAD.'</strong></td>
                        <td colspan="3"><strong>'.CARDNAME.'</strong></td>
                        <td colspan="2"><strong>'.COMMPERCENT.'</strong></td>
                        <td colspan="2"><strong>'.COMMISSIONAMT.'</strong></td>
                        <td colspan="2"><strong>'.AMT.'</strong></td>
                        <td colspan="2"><strong>'.CRV.'</strong></td>
                        <td colspan="3"><strong>'.REFTKT1.'</strong></td>
                    </tr>';
                $getDetails2=$this->db->query("SELECT * FROM {PRE}DuplicateAccountDetails WHERE nDupId = '".$row->nDId."'
											  ORDER BY nId DESC");
                foreach ($getDetails2->result() as $key2 => $row2) :
                    switch($row2->vTable)
                    {
                        case "C" :
                            $accNameId       = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row2->nDrAccount."' ");
                            break;

                        case "V" :
                            $accNameId       = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$row2->nDrAccount."' ");
                            break;

                        case "AG" :
                            $accNameId       = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '".$row2->nDrAccount."' ");
                            break;

                        case "O" :
                            $accNameId       = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '".$row2->nDrAccount."' ");
                            break;

                        case "A" :
                            $accNameId       = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row2->nDrAccount."' ");
                            break;
                    }//end switch

                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="3">'.$accNameId.'</td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row2->nAcHead."' ")).'</td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('Card','vName'," WHERE nCId = '".$row2->nCard."' ")).'</td>
                            <td colspan="2">'.stripslashes($row2->vCardComm).'</td>
                            <td colspan="2">'.stripslashes($row2->vCommAmt).'</td>
                            <td colspan="2">'.stripslashes($row2->vAmt).'</td>
                            <td colspan="2">'.stripslashes($row2->vCRV).'</td>
                            <td colspan="3">'.stripslashes($row2->vRefTkt).'</td>
                        </tr>';
                endforeach;
            }//end for each
            $pdfBody  .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), DUPLICATEINVOICEDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_duplicate_invoice';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A2');
    }//end function

    //function for exporting sales closing details
    function sales_closing()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody         	 = array();
        $sqlArray        	 = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.DIVISIONNAME.'</strong></th>
							       <th><strong>'.LOCATIONNAME.'</strong></th>
								   <th><strong>'.CUSTDATE.'</strong></th>
								   <th><strong>'.SALESTYPE.'</strong></th>
								   <th><strong>'.INVOICE.'</strong></th>
								   <th><strong>'.LPO.'</strong></th>
								   <th><strong>'.CRV.'</strong></th>
							       <th><strong>'.REFTKT.'</strong></th>
								   <th><strong>'.AGENTAC.'</strong></th>
								   <th><strong>'.TOTALNET.'</strong></th>
								   <th><strong>'.TOTALSERP.'</strong></th>
								   <th><strong>'.TOTALSERA.'</strong></th>
								   <th><strong>'.TOTALDISC.'</strong></th>
								   <th><strong>'.TOTALCOMMP.'</strong></th>
								   <th><strong>'.TOTALCOMMA.'</strong></th>
								   <th><strong>'.TOTALSAL.'</strong></th>
								   <th><strong>'.TOTALCOLL.'</strong></th>
								   <th><strong>'.TOTALAGENT.'</strong></th>
								   <th><strong>'.INVOICESTATUS.'</strong></th>
								   <th><strong>'.INVOICEREMARKS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation ."'");
                $mode    = $this->common_model->get_field_name('Transactions','vTitle'," WHERE nId = '".$row->vTransMode ."'");

                switch($row->nInvoiceStatus)
                {
                    case "N" :
                        $iStatus	= 'Normal';
                    break;

                    case "E" :
                        $iStatus	= 'Edited';
                    break;

                    case "R" :
                        $iStatus	= 'Refund';
                    break;

                    case "V" :
                        $iStatus	= 'Void';
                    break;
                }//end switch
                $pdfBody .= '
								<tr bgcolor="#ffffff">
									<td>'.stripslashes($divName).'</td>
									<td>'.stripslashes($locName).'</td>
									<td>'.$row->dDate.'</td>
									<td>'.stripslashes($mode).'</td>
									<td>'.stripslashes($row->vInvoiceNumber).'</td>
									<td>'.stripslashes($row->vLPO).'</td>
									<td>'.stripslashes($row->vCRV).'</td>
									<td>'.stripslashes($row->vRefTkt).'</td>
									<td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row->nAgentAc."' ")).'</td>
									<td>'.stripslashes($row->vTotalNetAmount).'</td>
									<td>'.stripslashes($row->vTotalServPercent).'</td>
									<td>'.stripslashes($row->vTotalServAmount).'</td>
									<td>'.stripslashes($row->vTotalDiscAmount).'</td>
									<td>'.stripslashes($row->vTotalCommPercent).'</td>
									<td>'.stripslashes($row->vTotalCommAmt).'</td>
									<td>'.stripslashes($row->vTotalSalesAmt).'</td>
									<td>'.stripslashes($row->vTotalCollected).'</td>
									<td>'.stripslashes($row->vTotalAgentAmt).'</td>
									<td>'.stripslashes($iStatus).'</td>
									<td>'.stripslashes($row->vRemarks).'</td>
								</tr>';
                $pdfBody .=	'
					<tr bgcolor="#ffffff">
                        <td><strong>'.TYPE.'</strong></td>
                        <td><strong>'.TICKETNAME.'</strong></td>
                        <td colspan="2"><strong>'.EMPNAME.'</strong></td>
                        <td colspan="2"><strong>'.AIRLINE.'</strong></td>
                        <td><strong>'.FACEFARE.'</strong></td>
                        <td><strong>'.TAX.'</strong></td>
                        <td><strong>'.COMMPERCENT.'</strong></td>
                        <td colspan="2"><strong>'.COMMISSIONAMT.'</strong></td>
                        <td><strong>'.NETAMT.'</strong></td>
                        <td><strong>'.SER.'</strong></td>
                        <td colspan="2"><strong>'.SERAMT.'</strong></td>
                        <td><strong>'.SLSAMT.'</strong></td>
                        <td><strong>'.DISCAMT.'</strong></td>
                        <td><strong>'.COLECTED.'</strong></td>
                        <td><strong>'.AGENTNAME.'</strong></td>
                        <td><strong>'.LPO.'</strong></td>
                    </tr>';
                //fetching Ticket details
                $getDetails = $this->db->query("SELECT *,CASE WHEN vType = 'Tkt' THEN 'Ticket' WHEN vType = 'Xo' THEN 'Xo' ELSE 'Hotel'
		                                    	 END as type FROM {PRE}SalesClosingDetails WHERE nSalesCloseId = '".$row->nSCId."' 
												ORDER BY nId DESC");
                foreach ($getDetails->result() as $key1 => $row1) :
                    switch($row1->type)
                    {
                        case "Xo" :
                            $this->_TableName   = 'XoBooking';
                            $this->_extraField  = 'vXoNumber';
                            $this->_TbId        = 'nXId';
                            break;

                        case "Ticket" :
                            $this->_TableName   = 'TicketBooking';
                            $this->_extraField  = 'vTicketNumber';
                            $this->_TbId        = 'nTId';
                            break;

                        case "Hotel" :
                            $this->_TableName   = 'HotelVoucher';
                            $this->_extraField  = 'vVoucherId';
                            $this->_TbId        = 'nHVId';
                            break;
                    }//end switch

                    $pdfBody .=	'
						<tr bgcolor="#ffffff">
                            <td>'.stripslashes($row1->type).'</td>
                            <td>'.stripslashes($this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->_TbId." = '".$row1->vTktNumber."'")).'</td>
                            <td colspan="2">'.stripslashes($row1->vEmployee).'</td>
                            <td colspan="2">'.stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo = '".$row1->nAirline."'")).'</td>
                            <td>'.stripslashes($row1->vFare).'</td>
                            <td>'.stripslashes($row1->vTax).'</td>
                            <td>'.stripslashes($row1->vComm).'</td>
                            <td colspan="2">'.stripslashes($row1->vCommAmt).'</td>
                            <td>'.stripslashes($row1->vNetAmt).'</td>
                            <td>'.stripslashes($row1->vServPercent).'</td>
                            <td colspan="2">'.stripslashes($row1->vServAmt).'</td>
                            <td>'.stripslashes($row1->vSlsAmt).'</td>
                            <td>'.stripslashes($row1->vDisAmt).'</td>
                            <td>'.stripslashes($row1->vCollected).'</td>
                            <td>'.stripslashes($row1->vAgentAmt).'</td>
                            <td>'.stripslashes($row1->vDLPO).'</td>
                        </tr>';
                endforeach;

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td colspan="3"><strong>'.DRACCNAME.'</strong></td>
                        <td colspan="3"><strong>'.ACHEAD.'</strong></td>
                        <td colspan="3"><strong>'.CARDNAME.'</strong></td>
                        <td colspan="2"><strong>'.COMMPERCENT.'</strong></td>
                        <td colspan="2"><strong>'.COMMISSIONAMT.'</strong></td>
                        <td colspan="2"><strong>'.AMT.'</strong></td>
                        <td colspan="2"><strong>'.CRV.'</strong></td>
                        <td colspan="3"><strong>'.REFTKT1.'</strong></td>
                    </tr>';
                $getDetails2=$this->db->query("SELECT * FROM {PRE}SalesAccountDetails WHERE nSalesCloseId = '".$row->nSCId."'
											  ORDER BY nId DESC");
                foreach ($getDetails2->result() as $key2 => $row2) :
                    switch($row2->vTable)
                    {
                        case "C" :
                            $accNameId       = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row1->nDrAccount."' ");
                            break;

                        case "V" :
                            $accNameId       = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$row1->nDrAccount."' ");
                            break;

                        case "AG" :
                            $accNameId       = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '".$row1->nDrAccount."' ");
                            break;

                        case "O" :
                            $accNameId       = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '".$row1->nDrAccount."' ");
                            break;

                        case "A" :
                            $accNameId       = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row1->nDrAccount."' ");
                            break;
                    }//end switch
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="3">'.$accNameId.'</td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row2->nAcHead."' ")).'</td>
                            <td colspan="3">'.stripslashes($this->common_model->get_field_name('Card','vName'," WHERE nCId = '".$row2->nCard."' ")).'</td>
                            <td colspan="2">'.stripslashes($row2->vCardComm).'</td>
                            <td colspan="2">'.stripslashes($row2->vCommAmt).'</td>
                            <td colspan="2">'.stripslashes($row2->vAmt).'</td>
                            <td colspan="2">'.stripslashes($row2->vCRV).'</td>
                            <td colspan="3">'.stripslashes($row2->vRefTkt).'</td>
                        </tr>';
                endforeach;
            }//end for each
            $pdfBody  .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SALESCLOSINGDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_sales_closing';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A2');
    }//end function

    function sales_unclosed()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody         	 = array();
        $sqlArray        	 = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.INVOICE.'</strong></th>
							       <th><strong>'.INVDATE.'</strong></th>
								   <th><strong>'.ACCNAME.'</strong></th>
								   <th><strong>'.SALETYPE.'</strong></th>
								   <th><strong>'.ISSUEDBY.'</strong></th>
								   <th><strong>'.AIRLINECODE.'</strong></th>
								   <th><strong>'.TICKETNAME.'</strong></th>
							       <th><strong>'.ISSUEDDATE.'</strong></th>
								   <th><strong>'.LPO.'</strong></th>
								   <th><strong>'.PASSENGER.'</strong></th>
								   <th><strong>'.NETAMT.'</strong></th>
								   <th><strong>'.SALESAMT.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $pdfBody .= '<tr bgcolor="#ffffff">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>'.stripslashes($row->sale_type).'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId = '".$row->vIssuedBy."'")).'</td>
                                <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo', " WHERE nArId = '".$row->airlineName."'")).'</td>
                                <td>'.stripslashes($row->tktNumber).'</td>
                                <td>'.stripslashes(date('d-m-Y',strtotime($row->dDate))).'</td>
                                <td>'.stripslashes($row->lpo).'</td>
                                <td>'.stripslashes($row->vPassenger).'</td>
                                <td>'.stripslashes($row->netAmt).'</td>
                                <td>'.stripslashes($row->vSalesAmt).'</td>
                            </tr>';
            }//end for each
            $pdfBody  .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), UNCLOSEDTKT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_unclosed_tickets';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    //function for exporting refund closing details
    function refund_closing()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.DIVISIONNAME.'</strong></th>
							       <th><strong>'.LOCATIONNAME.'</strong></th>
								   <th><strong>'.CUSTDATE.'</strong></th>
								   <th><strong>'.SALESTYPE.'</strong></th>
								   <th><strong>'.REFUNDNUMBER.'</strong></th>
								   <th><strong>'.LPO.'</strong></th>
								   <th><strong>'.DRACCNAME.'</strong></th>
								   <th><strong>'.CPV.'</strong></th>
								   <th><strong>'.NEWTKT.'</strong></th>
							       <th><strong>'.ISSUEDBY.'</strong></th>
								   <th><strong>'.SUBAGENTAC.'</strong></th>
								   <th><strong>'.CURRNAME.'</strong></th>
								   <th><strong>'.CURRRATE.'</strong></th>
								   <th><strong>'.TOTALNET.'</strong></th>
								   <th><strong>'.TOTALSERP.'</strong></th>
								   <th><strong>'.TOTALSERA.'</strong></th>
								   <th><strong>'.TOTALSAL.'</strong></th>
								   <th><strong>'.TOTALAGENT.'</strong></th>
								   <th><strong>'.INVOICESTATUS.'</strong></th>
								   <th><strong>'.INVOICEREMARKS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
                $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation ."'");

                switch($row->nInvoiceStatus)
                {
                    case "N" :
                        $iStatus	= 'Normal';
                        break;

                    case "E" :
                        $iStatus	= 'Edited';
                        break;

                    case "C" :
                        $iStatus	= 'Cancel';
                        break;

                    case "P" :
                        $iStatus	= 'Paid';
                        break;
                }//end switch
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.stripslashes($divName).'</td>
                        <td>'.stripslashes($locName).'</td>
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Transactions','vTitle'," WHERE nId = '".$row->vTransMode."' ")).'</td>
                        <td>'.stripslashes($row->vDocNumber).'</td>
                        <td>'.stripslashes($row->vLPO).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row->nDrAccount."' ")).'</td>
                        <td>'.stripslashes($row->vCPV).'</td>
                        <td>'.stripslashes($row->vNewTkt).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId = '".$row->nIssued."' ")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row->nAgentAccount."' ")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row->nCurrency."' ")).'</td>
                        <td>'.stripslashes($row->vRate).'</td>
                        <td>'.stripslashes($row->vTotalNetAmount).'</td>
                        <td>'.stripslashes($row->vTotalServPercent).'</td>
                        <td>'.stripslashes($row->vTotalServAmount).'</td>
                        <td>'.stripslashes($row->vTotalSalesAmt).'</td>
                        <td>'.stripslashes($row->vTotalAgentAmt).'</td>
                        <td>'.stripslashes($iStatus).'</td>
                        <td>'.stripslashes($row->vRemarks).'</td>
                    </tr>';

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td colspan="2"><strong>'.MODEOFPAYMENT.'</strong></td>
                        <td colspan="4"><strong>'.CUSTACCNAME.'</strong></td>
                        <td colspan="2"><strong>'.AGENTAC.'</strong></td>
                        <td colspan="2"><strong>'.AGENTAMT.'</strong></td>
                        <td><strong>'.LPO.'</strong></td>
                        <td colspan="2"><strong>'.SALESAMT.'</strong></td>
                        <td><strong>'.DISCOUNT.'</strong></td>
                        <td><strong>'.DISCOUNTAMT.'</strong></td>
                        <td><strong>'.COLECTED.'</strong></td>
                        <td colspan="2"><strong>'.SERVICEPERCENT.'</strong></td>
                        <td colspan="2"><strong>'.SERVICEAMT.'</strong></td>
                    </tr>';
                $getAccountDetails = $this->db->query("SELECT * FROM {PRE}RefundClosingAccounts WHERE nRefundId = '".$row->nRCId."'
												ORDER BY nRBId DESC");
                foreach ($getAccountDetails->result() as $key2 => $row2) :
                    switch($row2->vModeofpay)
                    {
                        case "Cr" :
                            $modeofPay	= 'Credit';
                            break;

                        case "Ca" :
                            $modeofPay	= 'Cash';
                            break;

                        case "KN" :
                            $modeofPay	= 'KNET';
                            break;
                    }//end switch

                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="2">'.stripslashes($modeofPay).'</td>
                            <td colspan="4">'.stripslashes($this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row2->nCustomerAc."' ")).'</td>
                            <td colspan="2">'.stripslashes($this->common_model->get_field_name('Agent','vName'," WHERE nAId = '".$row2->nAgentAc."' ")).'</td>
                            <td colspan="2">'.stripslashes($row2->vAgentAmt).'</td>
                            <td>'.stripslashes($row2->vLPO).'</td>
                            <td colspan="2">'.stripslashes($row2->vSalesAmt).'</td>
                            <td>'.stripslashes($row2->vDiscount).'</td>
                            <td>'.stripslashes($row2->vDiscountAmt).'</td>
                            <td>'.stripslashes($row2->vCollected).'</td>
                            <td colspan="2">'.stripslashes($row2->vServicePercent).'</td>
                            <td colspan="2">'.stripslashes($row2->vServiceAmt).'</td>
                        </tr>';
                endforeach;

                $pdfBody .=	'
                    <tr bgcolor="#ffffff">
                        <td><strong>'.TYPE.'</strong></td>
                        <td colspan="4"><strong>'.TICKETNAME.'</strong></td>
                        <td><strong>'.AIRLINE.'</strong></td>
                        <td><strong>'.ORIGINALTKT.'</strong></td>
                        <td colspan="2"><strong>'.NETAMT.'</strong></td>
                        <td><strong>'.SER.'</strong></td>
                        <td><strong>'.SERAMT.'</strong></td>
                        <td colspan="2"><strong>'.SLSAMT.'</strong></td>
                        <td><strong>'.AGENTNAME.'</strong></td>
                        <td><strong>'.PAIDAMT.'</strong></td>
                        <td colspan="4"><strong>'.DRACCNAME.'</strong></td>
                        <td><strong>'.LPO.'</strong></td>
                    </tr>';
                //fetching Ticket details
                $getDetails=$this->db->query("SELECT *,CASE WHEN vType = 'Tkt' THEN 'Ticket' WHEN vType = 'Xo' THEN 'Xo' ELSE 'Hotel'
		                                    	END as type FROM {PRE}RefundClosingDetails WHERE nRefundCloseId = '".$row->nRCId."' 
											  ORDER BY nId DESC");
                foreach ($getDetails->result() as $key1 => $row1) :
                    switch($row1->type)
                    {
                        case "Xo" :
                            $this->_TableName   = 'XoBooking';
                            $this->_extraField  = 'vXoNumber';
                            $this->dbId         = 'nXId';
                            $this->tktNumbr     = $this->common_model->get_field_name('XoBooking','vXoNumber', " WHERE nXId = '".$row1->nTicketNumber."'");
                            break;

                        case "Ticket" :
                            $this->_TableName   = 'TicketBooking';
                            $this->_extraField  = 'vTicketNumber';
                            $this->dbId         = 'nTId';
                            $this->tktNumbr     = $this->common_model->get_field_name('TicketBooking','vTicketNumber', " WHERE nTId = '".$row1->nTicketNumber."'");
                            break;

                        case "Hotel" :
                            $this->_TableName   = 'HotelVoucher';
                            $this->_extraField  = 'vVoucherId';
                            $this->dbId         = 'nHVId';
                            $this->tktNumbr     = $this->common_model->get_field_name('HotelVoucher','vVoucherId', " WHERE nHVId = '".$row1->nTicketNumber."'");
                            break;
                    }//end switch

                    $pdfBody .=	'
                        <tr bgcolor="#ffffff">
                            <td>'.stripslashes($row1->type).'</td>
                            <td colspan="4">'.stripslashes($this->common_model->get_field_name('StockEntryDetails','vCode', " WHERE nId = '".$this->tktNumbr."'").' - '.$this->common_model->get_field_name('StockEntryDetails','vStartNo', " WHERE nId = '".$this->tktNumbr."'").' - '.$this->common_model->get_field_name('StockEntryDetails','vEndNo', " WHERE nId = '".$this->tktNumbr."'").' - '.$this->common_model->get_field_name('StockEntryDetails','nQuantity', " WHERE nId = '".$this->tktNumbr."'")).'</td>
                            <td>'.stripslashes($row1->nAirline).'</td>
                            <td>'.stripslashes($row1->vORG).'</td>
                            <td colspan="2">'.stripslashes($row1->vNetAmt).'</td>
                            <td>'.stripslashes($row1->vServPercent).'</td>
                            <td>'.stripslashes($row1->vServAmt).'</td>
                            <td colspan="2">'.stripslashes($row1->vSlsAmt).'</td>
                            <td>'.stripslashes($row1->vAgentAmt).'</td>
                            <td>'.stripslashes($row1->vPaidAmt).'</td>
                            <td colspan="4">'.stripslashes($this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row1->nDrAcc."' ")).'</td>
                            <td>'.stripslashes($row1->vLPO).'</td>
                        </tr>';
                endforeach;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), REFUNDCLOSINGDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'refund_closing';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function unpost_reprt()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th align="left">'.SRNO.'</th>
                                    <th align="left">'.LOCATIONNAME.'</th>
                                    <th align="left">'.AIRLINECODE.'</th>
                                    <th align="left">'.TICKETNAME.'</th>
                                    <th align="left">'.CUSTDATE.'</th>
                                    <th align="left">'.ISSUEDBY.'</th>
                                    <th align="left">'.PASSENGER.'</th>
                                    <th align="left">'.SECTOR.'</th>
                                    <th align="left">'.FARE.'</th>
                                    <th align="left">'.TAX.'</th>
                                    <th align="left">'.COMMAMT.'</th>
                                    <th align="left">'.NETAMT.'</th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $sector = '';
                switch($row->tktType)
                {
                    case "Xo" :
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tktId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tktId."'");
                        break;

                    case "Tkt" :
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tktId."'");
                        break;

                    case "Htl" :
                        $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$row->tktId."'");
                        $hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$row->tktId."'");
                        $sector  = $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$row->tktId."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$row->tktId."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$row->tktId."'");
                        break;
                }//end switch

                if($sector==''):
                    $sector = stripslashes($row->vSector1).'-'.stripslashes($row->vSector12).'-'.stripslashes($row->vSector2).'-'.stripslashes($row->vSector22).'-'.stripslashes($row->vSector3).'-'.stripslashes($row->vSector33).'-'.stripslashes($row->vSector4).'-'.stripslashes($row->vSector44);
                endif;

                $srNo = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode',' WHERE nId="'.$row->nLocation.'"')).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo', " WHERE nArId='".$row->nAirline."' ")).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.date('d-m-Y',strtotime($row->dDate)).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$row->vIssuedBy."' ")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.$sector.'</td>
                        <td>'.stripslashes($row->Fare).'</td>
                        <td>'.stripslashes($row->totTax).'</td>
                        <td>'.stripslashes($row->Comm).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), UNPOST, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_unpost_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function unpost_staff_reprt()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkStaffId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th align="left">'.SRNO.'</th>
                                    <th align="left">'.LOCATIONNAME.'</th>
                                    <th align="left">'.AIRLINECODE.'</th>
                                    <th align="left">'.TICKETNAME.'</th>
                                    <th align="left">'.CUSTDATE.'</th>
                                    <th align="left">'.ISSUEDBY.'</th>
                                    <th align="left">'.PASSENGER.'</th>
                                    <th align="left">'.SECTOR.'</th>
                                    <th align="left">'.FARE.'</th>
                                    <th align="left">'.TAX.'</th>
                                    <th align="left">'.COMMAMT.'</th>
                                    <th align="left">'.NETAMT.'</th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $sector = '';
                switch($row->tktType)
                {
                    case "Xo" :
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tktId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tktId."'");
                        break;

                    case "Tkt" :
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tktId."'");
                        break;

                    case "Htl" :
                        $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$row->tktId."'");
                        $hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$row->tktId."'");
                        $sector  = $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$row->tktId."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$row->tktId."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$row->tktId."'");
                        break;
                }//end switch

                if($sector==''):
                    $sector = stripslashes($row->vSector1).'-'.stripslashes($row->vSector12).'-'.stripslashes($row->vSector2).'-'.stripslashes($row->vSector22).'-'.stripslashes($row->vSector3).'-'.stripslashes($row->vSector33).'-'.stripslashes($row->vSector4).'-'.stripslashes($row->vSector44);
                endif;

                $srNo = $key+1;
                if(!in_array($row->vIssuedBy,$chkStaffId)):
                    $pdfBody .= '<tr>
                                    <td align="left" colspan="12"><strong>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->vIssuedBy."' ")).' ('.stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$row->vIssuedBy."' ")).')'.'</strong></td>
                                 </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode',' WHERE nId="'.$row->nLocation.'"')).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo', " WHERE nArId='".$row->nAirline."' ")).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.date('d-m-Y',strtotime($row->dDate)).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$row->vIssuedBy."' ")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.$sector.'</td>
                        <td>'.stripslashes($row->Fare).'</td>
                        <td>'.stripslashes($row->totTax).'</td>
                        <td>'.stripslashes($row->Comm).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                    </tr>';
                $chkStaffId[] = $row->vIssuedBy;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

        if(empty($pdfBody))
            $pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), UNPOST, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_unpost_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function unpost_date_reprt()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkDateVal = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th align="left">'.SRNO.'</th>
                                    <th align="left">'.LOCATIONNAME.'</th>
                                    <th align="left">'.AIRLINECODE.'</th>
                                    <th align="left">'.TICKETNAME.'</th>
                                    <th align="left">'.CUSTDATE.'</th>
                                    <th align="left">'.ISSUEDBY.'</th>
                                    <th align="left">'.PASSENGER.'</th>
                                    <th align="left">'.SECTOR.'</th>
                                    <th align="left">'.FARE.'</th>
                                    <th align="left">'.TAX.'</th>
                                    <th align="left">'.COMMAMT.'</th>
                                    <th align="left">'.NETAMT.'</th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $sector = '';
                switch($row->tktType)
                {
                    case "Xo" :
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tktId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tktId."'");
                        break;

                    case "Tkt" :
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tktId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tktId."'");
                        break;

                    case "Htl" :
                        $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$row->tktId."'");
                        $hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$row->tktId."'");
                        $sector  = $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$row->tktId."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$row->tktId."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$row->tktId."'");
                        break;
                }//end switch

                if($sector==''):
                    $sector = stripslashes($row->vSector1).'-'.stripslashes($row->vSector12).'-'.stripslashes($row->vSector2).'-'.stripslashes($row->vSector22).'-'.stripslashes($row->vSector3).'-'.stripslashes($row->vSector33).'-'.stripslashes($row->vSector4).'-'.stripslashes($row->vSector44);
                endif;

                $srNo = $key+1;
                if(!in_array($row->dDate,$chkDateVal)):
                    $pdfBody .= '<tr>
                                    <td align="left" colspan="12"><strong>'.date('d-m-Y',strtotime($row->dDate)).'</strong></td>
                                 </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode',' WHERE nId="'.$row->nLocation.'"')).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo', " WHERE nArId='".$row->nAirline."' ")).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.date('d-m-Y',strtotime($row->dDate)).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$row->vIssuedBy."' ")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.$sector.'</td>
                        <td>'.stripslashes($row->Fare).'</td>
                        <td>'.stripslashes($row->totTax).'</td>
                        <td>'.stripslashes($row->Comm).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                    </tr>';
                $chkDateVal[] = $row->dDate;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

        if(empty($pdfBody))
            $pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), UNPOST, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_unpost_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function customer_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.DOCNUMBER.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.PASSENGER.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.DISCOUNTAMT.'</strong></th>
                                   <th><strong>'.COLECTED.'</strong></th>
                                   <th><strong>'.SERVICEAMT.'</strong></th>
                                   <th><strong>'.SERVICEPERCENT.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vDocNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->salesAmt).'</td>
                        <td>'.stripslashes($row->discAmt).'</td>
                        <td>'.stripslashes($row->collected).'</td>
                        <td>'.stripslashes($row->ServiceAmt).'</td>
                        <td>'.stripslashes($row->ServicePercent).'</td>
                    </tr>';
                $totalFare = $totalFare+$row->fareVal;
                $totalTax  = $totalTax+$row->taxTotal;
                $totalComm = $totalComm+$row->cmtAmount;
                $totalNet  = $totalNet+$row->netAmt;
                $totalSale = $totalSale+$row->salesAmt;
                $totalDisc = $totalDisc+$row->discAmt;
                $totalColl = $totalColl+$row->collected;
                $totalServ = $totalServ+$row->ServiceAmt;
                $totalSerP = $totalSerP+$row->ServicePercent;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="7"><strong>'.TOTALAMT.'</strong></td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>'.stripslashes($totalSale).'</td>
                        <td>'.stripslashes($totalDisc).'</td>
                        <td>'.stripslashes($totalColl).'</td>
                        <td>'.stripslashes($totalServ).'</td>
                        <td>'.stripslashes($totalSerP).'</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), CUSTOMERREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_customer_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function customer_airline_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAirlineId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.DOCNUMBER.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.PASSENGER.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.DISCOUNTAMT.'</strong></th>
                                   <th><strong>'.COLECTED.'</strong></th>
                                   <th><strong>'.SERVICEAMT.'</strong></th>
                                   <th><strong>'.SERVICEPERCENT.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                if(!in_array($row->airline,$chkAirlineId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="16"><strong>'.stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$row->airline."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vDocNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->salesAmt).'</td>
                        <td>'.stripslashes($row->discAmt).'</td>
                        <td>'.stripslashes($row->collected).'</td>
                        <td>'.stripslashes($row->ServiceAmt).'</td>
                        <td>'.stripslashes($row->ServicePercent).'</td>
                    </tr>';
                $totalFare = $totalFare+$row->fareVal;
                $totalTax  = $totalTax+$row->taxTotal;
                $totalComm = $totalComm+$row->cmtAmount;
                $totalNet  = $totalNet+$row->netAmt;
                $totalSale = $totalSale+$row->salesAmt;
                $totalDisc = $totalDisc+$row->discAmt;
                $totalColl = $totalColl+$row->collected;
                $totalServ = $totalServ+$row->ServiceAmt;
                $totalSerP = $totalSerP+$row->ServicePercent;
                $chkAirlineId[] = $row->airline;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="7"><strong>'.TOTALAMT.'</strong></td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>'.stripslashes($totalSale).'</td>
                        <td>'.stripslashes($totalDisc).'</td>
                        <td>'.stripslashes($totalColl).'</td>
                        <td>'.stripslashes($totalServ).'</td>
                        <td>'.stripslashes($totalSerP).'</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), AIRLINEREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_airline_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function customer_hotel_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkHotelId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.DOCNUMBER.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.PASSENGER.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.DISCOUNTAMT.'</strong></th>
                                   <th><strong>'.COLECTED.'</strong></th>
                                   <th><strong>'.SERVICEAMT.'</strong></th>
                                   <th><strong>'.SERVICEPERCENT.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                if(!in_array($row->vHotelName,$chkHotelId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="16"><strong>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vDocNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->salesAmt).'</td>
                        <td>'.stripslashes($row->discAmt).'</td>
                        <td>'.stripslashes($row->collected).'</td>
                        <td>'.stripslashes($row->ServiceAmt).'</td>
                        <td>'.stripslashes($row->ServicePercent).'</td>
                    </tr>';
                $chkHotelId[] = $row->vHotelName;
                $totalFare += $row->fareVal;
                $totalTax  += $row->taxTotal;
                $totalComm += $row->cmtAmount;
                $totalNet  += $row->netAmt;
                $totalSale += $row->salesAmt;
                $totalDisc += $row->discAmt;
                $totalColl += $row->collected;
                $totalServ += $row->ServiceAmt;
                $totalSerP += $row->ServicePercent;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="7"><strong>'.TOTALAMT.'</strong></td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>'.stripslashes($totalSale).'</td>
                        <td>'.stripslashes($totalDisc).'</td>
                        <td>'.stripslashes($totalColl).'</td>
                        <td>'.stripslashes($totalServ).'</td>
                        <td>'.stripslashes($totalSerP).'</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), HOTELREPORTBYHOTEL, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_hotel_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function refund_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                       <th><strong>'.REGISTER.'</strong></th>
                                       <th><strong>'.AIRCODE.'</strong></th>
                                       <th><strong>'.TICKETNAME.'</strong></th>
                                       <th><strong>'.ISSUEDBY.'</strong></th>
                                       <th><strong>'.PASSENGERNAME.'</strong></th>
                                       <th><strong>'.FARE.'</strong></th>
                                       <th><strong>'.TAX.'</strong></th>
                                       <th><strong>'.NETAMT.'</strong></th>
                                       <th><strong>'.VENDORCHANGE.'</strong></th>
                                       <th><strong>'.RECEIVEDAMOUNT.'</strong></th>
                                       <th><strong>'.OFFICECHARGE.'</strong></th>
                                       <th><strong>'.PAIDAMOUNT.'</strong></th>
                                       <th><strong>'.ACCOUNTNAME.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            $chkType = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                if(!in_array($row->sale_type,$chkType)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="14"><strong>'.stripslashes($row->sale_type).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($row->register).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->vendor).'</td>
                        <td>'.stripslashes($row->received).'</td>
                        <td>'.stripslashes($row->officeCharge).'</td>
                        <td>'.stripslashes($row->paidAmt).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row->account."'")).'</td>
                    </tr>';
                $chkType[] = $row->sale_type;
                $totalFare      = $totalFare+$row->fareVal;
                $totalTax       = $totalTax+$row->taxTotal;
                $totalNet       = $totalNet+$row->netAmt;
                $totalVendor    = $totalVendor+$row->vendor;
                $totalReceived  = $totalReceived+$row->received;
                $totalOffc      = $totalOffc+$row->officeCharge;
                $totalPaid      = $totalPaid+$row->paidAmt;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="6">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>'.stripslashes($totalVendor).'</td>
                        <td>'.stripslashes($totalReceived).'</td>
                        <td>'.stripslashes($totalOffc).'</td>
                        <td>'.stripslashes($totalPaid).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), REFUNDREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_refund_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function sales_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);
        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                       <th><strong>'.CUSTDATE.'</strong></th>
                                       <th><strong>'.DOCNUMBER.'</strong></th>
                                       <th><strong>'.AIRCODE.'</strong></th>
                                       <th><strong>'.TICKETNAME.'</strong></th>
                                       <th><strong>'.ISSUEDBY.'</strong></th>
                                       <th><strong>'.PASSENGER.'</strong></th>
                                       <th><strong>'.SECTOR.'</strong></th>
                                       <th><strong>'.FARE.'</strong></th>
                                       <th><strong>'.TAX.'</strong></th>
                                       <th><strong>'.COMMAMT.'</strong></th>
                                       <th><strong>'.NETAMT.'</strong></th>
                                       <th><strong>'.MARGIN.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            $chkType = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                if(!in_array($row->sale_type,$chkType)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="12"><strong>'.stripslashes($row->sale_type).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($row->vDocNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->margin).'</td>
                    </tr>';
                $chkType[] = $row->sale_type;
                $totalFare      = $totalFare+$row->fareVal;
                $totalTax       = $totalTax+$row->taxTotal;
                $totalComm      = $totalComm+$row->cmtAmount;
                $totalNet       = $totalNet+$row->netAmt;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="7">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SALESREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_sales_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function airline_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAirlineId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                if(!in_array($row->airline,$chkAirlineId)):
					switch($row->vType)
					{
						case "N":
							$status = 'Normal';
						break;
						
						case "V":
							$status = 'Void';
						break;
						
						case "C":
							$status = 'Conjuction';
						break;
						
						case "R":
							$status = 'Refund';
						break;
					}//end switch

                    switch($row->sale_type)
                    {
                        case "Ticket":
                            $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                            break;

                        case "Xo":
                            $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                            break;
                    }//end switch

                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="13"><strong>'.stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$row->airline."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($status).'</td>
                    </tr>';
                $chkAirlineId[] = $row->airline;
                $totalFare      = $totalFare+$row->fareVal;
                $totalTax       = $totalTax+$row->taxTotal;
                $totalComm      = $totalComm+$row->cmtAmount;
                $totalNet       = $totalNet+$row->netAmt;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="8">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), AIRLINEREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_airline_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function gds_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.TRAVELLER.'</strong></th>
                                   <th><strong>'.TRAVELDATE.'</strong></th>
                                   <th><strong>'.BOOKEDBY.'</strong></th>
                                   <th><strong>'.AIRLINE.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.TIMELIMIT.'</strong></th>
                                   <th><strong>'.PASSENGERCONTACT.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            $chkGDSId = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                if(!in_array($row->nGDS,$chkGDSId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="12"><strong>'.stripslashes($this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$row->nGDS."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($row->traveller).'</td>
                        <td>'.stripslashes($row->travelDate).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->BookedBy."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->timeLimit).'</td>
                        <td>'.stripslashes($row->passengerContact).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $chkGDSId[] = $row->nGDS;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

		if(trim($this->session->userdata('pdfTitle')) != ''):
			$stitle = $this->session->userdata('pdfTitle');
		else:
			$stitle = GDSREPORT;
		endif;
		
        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), $stitle, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_gds_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function hotel_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkHotelId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.CHECKIN.'</strong></th>
                                   <th><strong>'.CHECKOUT.'</strong></th>
                                   <th><strong>'.NIGHTS.'</strong></th>
                                   <th><strong>'.VOUCHERID.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.CITYNAME.'</strong></th>
                                   <th><strong>'.COUNTRYNAME.'</strong></th>
                                   <th><strong>'.HOTELNAME.'</strong></th>
                                   <th><strong>'.AMOUNTNIGHT.'</strong></th>
                                   <th><strong>'.TOTALAMOUNT.'</strong></th>
                                </tr>
							</thead>
							<tbody>';
							
            foreach ($sqlArray->result() as $key=>$row)
            {
                if(!in_array($row->vHotelName,$chkHotelId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="16"><strong>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->dCheckIn).'</td>
                        <td>'.stripslashes($row->dCheckOut).'</td>
                        <td>'.stripslashes($row->vNight).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                    </tr>';
                $chkHotelId[] = $row->vHotelName;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';
			
		if(trim($this->session->userdata('pdfTitle')) != ''):
			$stitle = $this->session->userdata('pdfTitle');
		else:
			$stitle = HOTELREPORTBYHOTEL;
		endif;
		
        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), $stitle, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_hotel_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function account_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAccount = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                if(!in_array($row->account,$chkAccount)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="13"><strong>'.stripslashes($this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row->account."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $chkAccount[] = $row->account;
                $totalFare      = $totalFare+$row->fareVal;
                $totalTax       = $totalTax+$row->taxTotal;
                $totalComm      = $totalComm+$row->cmtAmount;
                $totalNet       = $totalNet+$row->netAmt;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="8">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), PAYABLEACCOUNTSREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_account_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function gds_ticketed_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkGDSId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                if(!in_array($row->nGDS,$chkGDSId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="13"><strong>'.stripslashes($this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$row->nGDS."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $chkGDSId[] = $row->nGDS;
                $totalFare  = $totalFare+$row->fareVal;
                $totalTax   = $totalTax+$row->taxTotal;
                $totalComm  = $totalComm+$row->cmtAmount;
                $totalNet   = $totalNet+$row->netAmt;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="8">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), GDSTICKETEDREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_gds_ticketed_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function city_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkHotelId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.CHECKIN.'</strong></th>
                                   <th><strong>'.CHECKOUT.'</strong></th>
                                   <th><strong>'.NIGHTS.'</strong></th>
                                   <th><strong>'.VOUCHERID.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.CITYNAME.'</strong></th>
                                   <th><strong>'.COUNTRYNAME.'</strong></th>
                                   <th><strong>'.HOTELNAME.'</strong></th>
                                   <th><strong>'.AMOUNTNIGHT.'</strong></th>
                                   <th><strong>'.TOTALAMOUNT.'</strong></th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                if(!in_array($row->nCity,$chkHotelId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="16"><strong>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->dCheckIn).'</td>
                        <td>'.stripslashes($row->dCheckOut).'</td>
                        <td>'.stripslashes($row->vNight).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                    </tr>';
                $chkHotelId[] = $row->nCity;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), REPORTBYCITY, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_city_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function country_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkHotelId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.CHECKIN.'</strong></th>
                                   <th><strong>'.CHECKOUT.'</strong></th>
                                   <th><strong>'.NIGHTS.'</strong></th>
                                   <th><strong>'.VOUCHERID.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.CITYNAME.'</strong></th>
                                   <th><strong>'.COUNTRYNAME.'</strong></th>
                                   <th><strong>'.HOTELNAME.'</strong></th>
                                   <th><strong>'.AMOUNTNIGHT.'</strong></th>
                                   <th><strong>'.TOTALAMOUNT.'</strong></th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                if(!in_array($row->nCountry,$chkHotelId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="16"><strong>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->dCheckIn).'</td>
                        <td>'.stripslashes($row->dCheckOut).'</td>
                        <td>'.stripslashes($row->vNight).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                    </tr>';
                $chkHotelId[] = $row->nCountry;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), REPORTBYCOUNTRY, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_country_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function destination_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkHotelId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.CHECKIN.'</strong></th>
                                   <th><strong>'.CHECKOUT.'</strong></th>
                                   <th><strong>'.NIGHTS.'</strong></th>
                                   <th><strong>'.VOUCHERID.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.CITYNAME.'</strong></th>
                                   <th><strong>'.COUNTRYNAME.'</strong></th>
                                   <th><strong>'.HOTELNAME.'</strong></th>
                                   <th><strong>'.AMOUNTNIGHT.'</strong></th>
                                   <th><strong>'.TOTALAMOUNT.'</strong></th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                if(!in_array($row->vDestination,$chkHotelId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="16"><strong>'.stripslashes($row->vDestination).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->dCheckIn).'</td>
                        <td>'.stripslashes($row->dCheckOut).'</td>
                        <td>'.stripslashes($row->vNight).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                    </tr>';
                $chkHotelId[] = $row->vDestination;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), DESTINATIONREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_destination_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function employee_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAirlineId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                if(!in_array($row->vIssuedBy,$chkAirlineId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="13"><strong>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $chkAirlineId[] = $row->vIssuedBy;
                $totalFare  = $totalFare+$row->fareVal;
                $totalTax   = $totalTax+$row->taxTotal;
                $totalComm  = $totalComm+$row->cmtAmount;
                $totalNet   = $totalNet+$row->netAmt;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="8">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), EMPLOYEEREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_employee_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function sector_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $totalFare  = $totalFare+$row->fareVal;
                $totalTax   = $totalTax+$row->taxTotal;
                $totalComm  = $totalComm+$row->cmtAmount;
                $totalNet   = $totalNet+$row->netAmt;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="8">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SECTORREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_sector_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function status_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAirlineId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "N": $status = "Normal";
                    break;

                    case "C": $status = "Conjuction";
                    break;

                    case "R": $status = "Reissued";
                    break;

                    case "V": $status = "Void";
                    break;
                }//end switch

                if(!in_array($row->vType,$chkAirlineId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="13"><strong>'.stripslashes($status).'</strong></td>
                        </tr>';
                endif;

                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $chkAirlineId[] = $row->vType;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), BYSTATUSREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_status_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function airline_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAirlineId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.PROFIT.'</strong></th>
                                   <th><strong>'.PROFITPERCENTAGE.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $profit           = $row->netAmt-$row->salesAmt;
                if($profit >0 && $row->netAmt >0)
                    $profitPercentage = round((($profit*100)/$row->netAmt),2);
                else
                    $profitPercentage = 0;

                if(!in_array($row->airline,$chkAirlineId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="15"><strong>'.stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$row->airline."'")).'</strong></td>
                        </tr>';
                endif;

                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($profit).'</td>
                        <td>'.stripslashes($profitPercentage).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $chkAirlineId[] = $row->airline;
                $totalFare  = $totalFare+$row->fareVal;
                $totalTax   = $totalTax+$row->taxTotal;
                $totalComm  = $totalComm+$row->cmtAmount;
                $totalNet   = $totalNet+$row->netAmt;
                $totalProf  = $totalProf+$profit;
                $totalProP  = $totalProP+$profitPercentage;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="8">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>'.stripslashes($totalProf).'</td>
                        <td>'.stripslashes($totalProP).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), AIRLINEREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_airline_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function gds_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.TRAVELLER.'</strong></th>
                                   <th><strong>'.TRAVELDATE.'</strong></th>
                                   <th><strong>'.BOOKEDBY.'</strong></th>
                                   <th><strong>'.AIRLINE.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.TIMELIMIT.'</strong></th>
                                   <th><strong>'.PASSENGERCONTACT.'</strong></th>
                                   <th><strong>'.PROFIT.'</strong></th>
                                   <th><strong>'.PROFITPERCENTAGE.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            $chkGDSId = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                $profit           = $row->netAmt-$row->salesAmt;
                if($profit >0 && $row->netAmt >0)
                    $profitPercentage = round((($profit*100)/$row->netAmt),2);
                else
                    $profitPercentage = 0;

                if(!in_array($row->nGDS,$chkGDSId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="14"><strong>'.stripslashes($this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$row->nGDS."'")).'</strong></td>
                        </tr>';
                endif;

                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($row->traveller).'</td>
                        <td>'.stripslashes($row->travelDate).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->BookedBy."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->timeLimit).'</td>
                        <td>'.stripslashes($row->passengerContact).'</td>
                        <td>'.stripslashes($profit).'</td>
                        <td>'.stripslashes($profitPercentage).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $chkGDSId[] = $row->nGDS;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), GDSREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_gds_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function hotel_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkHotelId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.CHECKIN.'</strong></th>
                                   <th><strong>'.CHECKOUT.'</strong></th>
                                   <th><strong>'.NIGHTS.'</strong></th>
                                   <th><strong>'.VOUCHERID.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.CITYNAME.'</strong></th>
                                   <th><strong>'.COUNTRYNAME.'</strong></th>
                                   <th><strong>'.HOTELNAME.'</strong></th>
                                   <th><strong>'.AMOUNTNIGHT.'</strong></th>
                                   <th><strong>'.TOTALAMOUNT.'</strong></th>
                                   <th><strong>'.PROFIT.'</strong></th>
                                   <th><strong>'.PROFITPERCENTAGE.'</strong></th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $profit           = $row->netAmt-$row->salesAmt;
                if($profit >0 && $row->netAmt >0)
                    $profitPercentage = round((($profit*100)/$row->netAmt),2);
                else
                    $profitPercentage = 0;

                if(!in_array($row->vHotelName,$chkHotelId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="15"><strong>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->dCheckIn).'</td>
                        <td>'.stripslashes($row->dCheckOut).'</td>
                        <td>'.stripslashes($row->vNight).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($profit).'</td>
                        <td>'.stripslashes($profitPercentage).'</td>
                    </tr>';
                $chkHotelId[] = $row->vHotelName;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

		if($this->session->userdata('pageName2') != '')
			$title = $this->session->userdata('pageName2');
		else
			$title = HOTELREPORTBYHOTEL;
		
        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), $title, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_hotel_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function account_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAccount = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.PROFIT.'</strong></th>
                                   <th><strong>'.PROFITPERCENTAGE.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $profit           = $row->netAmt-$row->salesAmt;
                if($profit >0 && $row->netAmt >0)
                    $profitPercentage = round((($profit*100)/$row->netAmt),2);
                else
                    $profitPercentage = 0;

                if(!in_array($row->account,$chkAccount)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="15"><strong>'.stripslashes($this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row->account."'")).'</strong></td>
                        </tr>';
                endif;

                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($ector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($profit).'</td>
                        <td>'.stripslashes($profitPercentage).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $chkAccount[] = $row->account;
                $totalFare  = $totalFare+$row->fareVal;
                $totalTax   = $totalTax+$row->taxTotal;
                $totalComm  = $totalComm+$row->cmtAmount;
                $totalNet   = $totalNet+$row->netAmt;
                $totalProf  = $totalProf+$profit;
                $totalProP  = $totalProP+$profitPercentage;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="8">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>'.stripslashes($totalProf).'</td>
                        <td>'.stripslashes($totalProP).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), PAYABLEACCOUNTSREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_account_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function gds_ticketed_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkGDSId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.PROFIT.'</strong></th>
                                   <th><strong>'.PROFITPERCENTAGE.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $profit           = $row->netAmt-$row->salesAmt;
                if($profit >0 && $row->netAmt >0)
                    $profitPercentage = round((($profit*100)/$row->netAmt),2);
                else
                    $profitPercentage = 0;

                if(!in_array($row->nGDS,$chkGDSId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="15"><strong>'.stripslashes($this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$row->nGDS."'")).'</strong></td>
                        </tr>';
                endif;

                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($profit).'</td>
                        <td>'.stripslashes($profitPercentage).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $chkGDSId[] = $row->nGDS;
                $totalFare  = $totalFare+$row->fareVal;
                $totalTax   = $totalTax+$row->taxTotal;
                $totalComm  = $totalComm+$row->cmtAmount;
                $totalNet   = $totalNet+$row->netAmt;
                $totalProf  = $totalProf+$profit;
                $totalProP  = $totalProP+$profitPercentage;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="8">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>'.stripslashes($totalProf).'</td>
                        <td>'.stripslashes($totalProP).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), GDSTICKETEDREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_gds_ticketed_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function city_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkHotelId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.CHECKIN.'</strong></th>
                                   <th><strong>'.CHECKOUT.'</strong></th>
                                   <th><strong>'.NIGHTS.'</strong></th>
                                   <th><strong>'.VOUCHERID.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.CITYNAME.'</strong></th>
                                   <th><strong>'.COUNTRYNAME.'</strong></th>
                                   <th><strong>'.HOTELNAME.'</strong></th>
                                   <th><strong>'.AMOUNTNIGHT.'</strong></th>
                                   <th><strong>'.TOTALAMOUNT.'</strong></th>
                                   <th><strong>'.PROFIT.'</strong></th>
                                   <th><strong>'.PROFITPERCENTAGE.'</strong></th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $profit           = $row->netAmt-$row->salesAmt;
                if($profit >0 && $row->netAmt >0)
                    $profitPercentage = round((($profit*100)/$row->netAmt),2);
                else
                    $profitPercentage = 0;

                if(!in_array($row->nCity,$chkHotelId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="15"><strong>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->dCheckIn).'</td>
                        <td>'.stripslashes($row->dCheckOut).'</td>
                        <td>'.stripslashes($row->vNight).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($profit).'</td>
                        <td>'.stripslashes($profitPercentage).'</td>
                    </tr>';
                $chkHotelId[] = $row->nCity;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), REPORTBYCITY, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_city_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function country_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkHotelId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.CHECKIN.'</strong></th>
                                   <th><strong>'.CHECKOUT.'</strong></th>
                                   <th><strong>'.NIGHTS.'</strong></th>
                                   <th><strong>'.VOUCHERID.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.CITYNAME.'</strong></th>
                                   <th><strong>'.COUNTRYNAME.'</strong></th>
                                   <th><strong>'.HOTELNAME.'</strong></th>
                                   <th><strong>'.AMOUNTNIGHT.'</strong></th>
                                   <th><strong>'.TOTALAMOUNT.'</strong></th>
                                   <th><strong>'.PROFIT.'</strong></th>
                                   <th><strong>'.PROFITPERCENTAGE.'</strong></th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $profit           = $row->netAmt-$row->salesAmt;
                if($profit >0 && $row->netAmt >0)
                    $profitPercentage = round((($profit*100)/$row->netAmt),2);
                else
                    $profitPercentage = 0;

                if(!in_array($row->nCountry,$chkHotelId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="15"><strong>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->dCheckIn).'</td>
                        <td>'.stripslashes($row->dCheckOut).'</td>
                        <td>'.stripslashes($row->vNight).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($profit).'</td>
                        <td>'.stripslashes($profitPercentage).'</td>
                    </tr>';
                $chkHotelId[] = $row->nCountry;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), REPORTBYCOUNTRY, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_country_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function destination_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkHotelId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.CHECKIN.'</strong></th>
                                   <th><strong>'.CHECKOUT.'</strong></th>
                                   <th><strong>'.NIGHTS.'</strong></th>
                                   <th><strong>'.VOUCHERID.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.CITYNAME.'</strong></th>
                                   <th><strong>'.COUNTRYNAME.'</strong></th>
                                   <th><strong>'.HOTELNAME.'</strong></th>
                                   <th><strong>'.AMOUNTNIGHT.'</strong></th>
                                   <th><strong>'.TOTALAMOUNT.'</strong></th>
                                   <th><strong>'.PROFIT.'</strong></th>
                                   <th><strong>'.PROFITPERCENTAGE.'</strong></th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $profit           = $row->netAmt-$row->salesAmt;
                if($profit >0 && $row->netAmt >0)
                    $profitPercentage = round((($profit*100)/$row->netAmt),2);
                else
                    $profitPercentage = 0;

                if(!in_array($row->vDestination,$chkHotelId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="15"><strong>'.stripslashes($row->vDestination).'</strong></td>
                        </tr>';
                endif;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->dCheckIn).'</td>
                        <td>'.stripslashes($row->dCheckOut).'</td>
                        <td>'.stripslashes($row->vNight).'</td>
                        <td>'.stripslashes($row->vTicktManual).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'")).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$row->vHotelName."'")).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($row->vTotalRTFC).'</td>
                        <td>'.stripslashes($profit).'</td>
                        <td>'.stripslashes($profitPercentage).'</td>
                    </tr>';
                $chkHotelId[] = $row->vDestination;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), DESTINATIONREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_destination_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function employee_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAirlineId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.PROFIT.'</strong></th>
                                   <th><strong>'.PROFITPERCENTAGE.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $profit           = $row->netAmt-$row->salesAmt;
                if($profit >0 && $row->netAmt >0)
                    $profitPercentage = round((($profit*100)/$row->netAmt),2);
                else
                    $profitPercentage = 0;

                if(!in_array($row->vIssuedBy,$chkAirlineId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="15"><strong>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</strong></td>
                        </tr>';
                endif;

                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($profit).'</td>
                        <td>'.stripslashes($profitPercentage).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $chkAirlineId[] = $row->vIssuedBy;
                $totalFare  = $totalFare+$row->fareVal;
                $totalTax   = $totalTax+$row->taxTotal;
                $totalComm  = $totalComm+$row->cmtAmount;
                $totalNet   = $totalNet+$row->netAmt;
                $totalProf  = $totalProf+$profit;
                $totalProP  = $totalProP+$profitPercentage;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="8">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>'.stripslashes($totalProf).'</td>
                        <td>'.stripslashes($totalProP).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), EMPLOYEEREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_employee_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function sector_profit_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.LOCATIONNAME.'</strong></th>
                                   <th><strong>'.AIRPNR.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.ISSUEDBY.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.COMMAMT.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.PROFIT.'</strong></th>
                                   <th><strong>'.PROFITPERCENTAGE.'</strong></th>
                                   <th><strong>'.STATUS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $profit           = $row->netAmt-$row->salesAmt;
                if($profit >0 && $row->netAmt >0)
                    $profitPercentage = round((($profit*100)/$row->netAmt),2);
                else
                    $profitPercentage = 0;


                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->nLocation."'")).'</td>
                        <td>'.stripslashes($row->vAirpnr).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."'")).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->cmtAmount).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($profit).'</td>
                        <td>'.stripslashes($profitPercentage).'</td>
                        <td>'.stripslashes($row->vStatus).'</td>
                    </tr>';
                $totalFare  = $totalFare+$row->fareVal;
                $totalTax   = $totalTax+$row->taxTotal;
                $totalComm  = $totalComm+$row->cmtAmount;
                $totalNet   = $totalNet+$row->netAmt;
                $totalProf  = $totalProf+$profit;
                $totalProP  = $totalProP+$profitPercentage;
            }//end for each
            $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td align="right" colspan="8">'.TOTALAMT.'</td>
                        <td>'.stripslashes($totalFare).'</td>
                        <td>'.stripslashes($totalTax).'</td>
                        <td>'.stripslashes($totalComm).'</td>
                        <td>'.stripslashes($totalNet).'</td>
                        <td>'.stripslashes($totalProf).'</td>
                        <td>'.stripslashes($totalProP).'</td>
                        <td>&nbsp;</td>
                    </tr>';
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SECTORREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_sector_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function airline_report_details()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAirlineId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.FACEFARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.NETFARE.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.SEGMENT.'</strong></th>
                                   <th><strong>'.GDS.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                if(!in_array($row->airline,$chkAirlineId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="13"><strong>'.stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$row->airline."'")).'</strong></td>
                        </tr>';
                endif;

                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->salesAmt).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->vSegment).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$row->nGDS."'")).'</td>
                    </tr>';
                $chkAirlineId[] = $row->airline;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), AIRLINEREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_airline_details_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function airline_report_summary()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAirlineId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                   <th><strong></strong></th>
                                   <th colspan="5"><strong>'.SALES.'</strong></th>
                                   <th colspan="5"><strong>'.REFUNDS.'</strong></th>
                                   <th colspan="5"><strong>'.NETSALES.'</strong></th>
                                </tr>

                                <tr bgcolor="#ffffff">
                                   <th><strong>'.AIRLINE.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.SEGMENT.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.SEGMENT.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.SEGMENT.'</strong></th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
				$getSales  = $this->gds_report_model->getAirlineSalesTotal($row->airline);
				$getRefund = $this->gds_report_model->getAirlineRefundTotal($row->airline);
			
                $pdfBody .= '<tr bgcolor="#ffffff">
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$row->airline."'")).'</td>
						   <td>'.$getSales['fareVal'].'</td>
							<td>'.$getSales['taxTotal'].'</td>
							<td>'.$getSales['netAmt'].'</td>
							<td>'.$getSales['salesAmt'].'</td>
							<td>'.$getSales['vSegment'].'</td>
							<td>'.$getRefund['fareVal'].'</td>
							<td>'.$getRefund['taxTotal'].'</td>
							<td>'.$getRefund['netAmt'].'</td>
							<td>'.$getRefund['salesAmt'].'</td>
							<td>'.$getRefund['vSegment'].'</td>
							<td>'.stripslashes($row->fareVal).'</td>
							<td>'.stripslashes($row->taxTotal).'</td>
							<td>'.stripslashes($row->netAmt).'</td>
							<td>'.stripslashes($row->salesAmt).'</td>
							<td>'.stripslashes($row->vSegment).'</td></tr>';

            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), AIRLINEREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_airline_summary_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function gds_report_details()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $chkAirlineId = array();
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
								   <th><strong>'.CUSTDATE.'</strong></th>
                                   <th><strong>'.AIRCODE.'</strong></th>
                                   <th><strong>'.TICKETNAME.'</strong></th>
                                   <th><strong>'.PASSENGERNAME.'</strong></th>
                                   <th><strong>'.FACEFARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.NETFARE.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.SECTOR.'</strong></th>
                                   <th><strong>'.SEGMENT.'</strong></th>
							    </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                if(!in_array($row->nGDS,$chkAirlineId)):
                    $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td colspan="10"><strong>'.stripslashes($this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$row->nGDS."'")).'</strong></td>
                        </tr>';
                endif;

                switch($row->sale_type)
                {
                    case "Ticket":
                        $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->tableId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->tableId."'");
                        break;

                    case "Xo":
                        $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->tableId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->tableId."'");
                        break;
                }//end switch

                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$row->dDate.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                        <td>'.stripslashes($row->tktNumber).'</td>
                        <td>'.stripslashes($row->vPassenger).'</td>
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->salesAmt).'</td>
                        <td>'.stripslashes($sector).'</td>
                        <td>'.stripslashes($row->vSegment).'</td>
                    </tr>';
                $chkAirlineId[] = $row->nGDS;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), GDSREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_gds_details_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function gds_report_summary()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                   <th><strong></strong></th>
                                   <th colspan="5"><strong>'.SALES.'</strong></th>
                                   <th colspan="5"><strong>'.REFUNDS.'</strong></th>
                                   <th colspan="5"><strong>'.NETSALES.'</strong></th>
                                </tr>

                                <tr bgcolor="#ffffff">
                                   <th><strong>'.GDS.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.SEGMENT.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.SEGMENT.'</strong></th>
                                   <th><strong>'.FARE.'</strong></th>
                                   <th><strong>'.TAX.'</strong></th>
                                   <th><strong>'.NETAMT.'</strong></th>
                                   <th><strong>'.SALESAMT.'</strong></th>
                                   <th><strong>'.SEGMENT.'</strong></th>
                                </tr>

							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.stripslashes($this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$row->nGDS."'")).'</td>';

                        $extraCheck_t = $this->session->userdata('extraCheck_t');
                        $extraCheck_x = $this->session->userdata('extraCheck_x');
                        $salesGroupList = $this->db->query("(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                                            tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
                                                            AND tb.nGDS = '".$row->nGDS."'
                                                            GROUP BY tb.nAirline ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                                            scd WHERE scd.vType = 'Xo') ".$extraCheck_x." AND xo.nGDS = '".$row->nGDS."' GROUP BY xo.nAirline ORDER BY xo.nXId DESC)");

                        if ($salesGroupList->num_rows() > 0)
                        {
                            foreach ($salesGroupList->result() as $key1=>$row1)
                            {
                                $pdfBody .= '<td>'.stripslashes($row1->fareVal).'</td>
                                            <td>'.stripslashes($row1->taxTotal).'</td>
                                            <td>'.stripslashes($row1->netAmt).'</td>
                                            <td>'.stripslashes($row1->salesAmt).'</td>
                                            <td>'.stripslashes($row1->vSegment).'</td>';

                            }//end for each
                        } //end if

                $refundGroupList = $this->db->query("(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                                    tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                                    tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                                    tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                                    tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                                    {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                                    {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                                    ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                                    LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                                    IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                                    AND tb.nGDS = '".$row->nGDS."'
                                                    GROUP BY tb.nAirline ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                                    'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                                    xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                                    ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                                    xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                                    LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                                    LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                                    aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT rcd.nTicketNumber FROM
                                                    {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo') ".$extraCheck_x." AND xo.nGDS = '".$row->nGDS."'
                                                    GROUP BY xo.nAirline ORDER BY xo.nXId DESC)");

                        if ($refundGroupList->num_rows() > 0)
                        {
                            foreach ($refundGroupList->result() as $key2=>$row2)
                            {
                                $pdfBody .= '<td>'.stripslashes($row2->fareVal).'</td>
                                            <td>'.stripslashes($row2->taxTotal).'</td>
                                            <td>'.stripslashes($row2->netAmt).'</td>
                                            <td>'.stripslashes($row2->salesAmt).'</td>
                                            <td>'.stripslashes($row2->vSegment).'</td>';

                            }//end for each
                        } //end if

                        $pdfBody .= '
                        <td>'.stripslashes($row->fareVal).'</td>
                        <td>'.stripslashes($row->taxTotal).'</td>
                        <td>'.stripslashes($row->netAmt).'</td>
                        <td>'.stripslashes($row->salesAmt).'</td>
                        <td>'.stripslashes($row->vSegment).'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), GDSREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'_airline_summary_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function top_airline_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th>'.AIRCODE.'</th>
                                    <th>'.AIRLINE.'</th>
                                    <th colspan="2">'.JANUARY.'</th>
                                    <th colspan="2">'.FEBRUARY.'</th>
                                    <th colspan="2">'.MARCH.'</th>
                                    <th colspan="2">'.APRIL.'</th>
                                    <th colspan="2">'.MAY.'</th>
                                    <th colspan="2">'.JUNE.'</th>
                                    <th colspan="2">'.JULY.'</th>
                                    <th colspan="2">'.AUGUST.'</th>
                                    <th colspan="2">'.SEPTEMBER.'</th>
                                    <th colspan="2">'.OCTOBER.'</th>
                                    <th colspan="2">'.NOVEMBER.'</th>
                                    <th colspan="2">'.DECEMBER.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="2">'.TOPTENAIRLINE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                switch(substr(date('m',strtotime($row->dDate)),0,1))
                {
                    case "0":
                        $varMonth = substr(date('m',strtotime($row->dDate)),1,1);
                        break;

                    case "1":
                        $varMonth = date('m',strtotime($row->dDate));
                        break;
                }//end switch

                $varYear = date('Y',strtotime($row->dDate));
                $noDays     = cal_days_in_month(CAL_GREGORIAN, $varMonth, $varYear);
                $txtFrom    = date('Y-m-d',strtotime($varYear.'-'.$varMonth.'-01'));
                $txtTo      = date('Y-m-d',strtotime($varYear.'-'.$varMonth.'-'.$noDays));

                $monthVal   = $this->topairline_report_model->getMonthWiseReport($txtFrom,$txtTo,$row->airline);

                $pdfBody .= '<tr bgcolor="#ffffff">
                            <td>'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->airline."'")).'</td>
                            <td>'.stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$row->airline."'")).'</td>';
                $pdfBody .= '<td>';
                if($varMonth=='1')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='1')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='2')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='2')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='3')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='3')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='4')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='4')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='5')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='5')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='6')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='6')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='7')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='7')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='8')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='8')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='9')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='9')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='10')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='10')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='11')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='11')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='12')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='12')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td></tr>';

            }//end for each
            $pdfBody  .= '
                    <tr>
                        <td colspan="2" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), TOPTENAIRLINE, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'top_airline_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function top_sector_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th>'.AIRCODE.'</th>
                                    <th>'.AIRLINE.'</th>
                                    <th colspan="2">'.JANUARY.'</th>
                                    <th colspan="2">'.FEBRUARY.'</th>
                                    <th colspan="2">'.MARCH.'</th>
                                    <th colspan="2">'.APRIL.'</th>
                                    <th colspan="2">'.MAY.'</th>
                                    <th colspan="2">'.JUNE.'</th>
                                    <th colspan="2">'.JULY.'</th>
                                    <th colspan="2">'.AUGUST.'</th>
                                    <th colspan="2">'.SEPTEMBER.'</th>
                                    <th colspan="2">'.OCTOBER.'</th>
                                    <th colspan="2">'.NOVEMBER.'</th>
                                    <th colspan="2">'.DECEMBER.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="2">'.TOPTENSECTOR.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.FARE.'</th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                switch(substr(date('m',strtotime($row->dDate)),0,1))
                {
                    case "0":
                        $varMonth = substr(date('m',strtotime($row->dDate)),1,1);
                        break;

                    case "1":
                        $varMonth = date('m',strtotime($row->dDate));
                        break;
                }//end switch

                $varYear = date('Y',strtotime($row->dDate));
                $noDays     = cal_days_in_month(CAL_GREGORIAN, $varMonth, $varYear);
                $txtFrom    = date('Y-m-d',strtotime($varYear.'-'.$varMonth.'-01'));
                $txtTo      = date('Y-m-d',strtotime($varYear.'-'.$varMonth.'-'.$noDays));

                $monthVal   = $this->topsector_report_model->getMonthWiseReport2($txtFrom,$txtTo,$row->Sector1);

                $pdfBody .= '
                        <tr bgcolor="#ffffff">
                            <td>'.stripslashes($row->Sector1).'</td>
                            <td>'.stripslashes($row->Sector44).'</td>';
                $pdfBody .= '<td>';
                if($varMonth=='1')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='1')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='2')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='2')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='3')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='3')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='4')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='4')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='5')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='5')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='6')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='6')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='7')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='7')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='8')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='8')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='9')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='9')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='10')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='10')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='11')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='11')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td><td>';
                if($varMonth=='12')
                    $pdfBody .= $monthVal['count'];
                $pdfBody .= '</td><td>';
                if($varMonth=='12')
                    $pdfBody .= $monthVal['fareval'];
                $pdfBody .= '</td></tr>';

            }//end for each
            $pdfBody  .= '
                    <tr>
                        <td colspan="2" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), TOPTENSECTOR, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'top_sector_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function detail_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();

        if(trim($_SESSION['sess_opening']) == ''):
            $debitVal = $creditVal = $balanceVal = 0;
        else:
            $openSqlArray = $this->db->query($_SESSION['sess_opening']);
            if ($openSqlArray->num_rows() > 0)
            {
                $debit = 0;
                $credit = 0;
                foreach ($openSqlArray->result() as $key=>$row)
                {
                    $debit   += $row->debit;
                    $credit  += $row->credit;
                }//end for each
                $debitVal   = $debit;
                $creditVal  = $credit;
                $balanceVal = $debitVal - $creditVal;
            }
        endif;

        if(trim($_SESSION['sess_year']) == ''):
            $debitVal2 = $creditVal2 = $balanceVal2 = 0;
        else:
            $openSqlArray = $this->db->query($_SESSION['sess_year']);
            if ($openSqlArray->num_rows() > 0)
            {
                $debit2 = 0;
                $credit2 = 0;
                foreach ($openSqlArray->result() as $key=>$row)
                {
                    $debit2   += $row->DCTot;
                    $credit2  += $row->FCTot;
                }//end for each
                $debitVal2   = $debit2;
                $creditVal2  = $credit2;
                $balanceVal2 = $debitVal2 - $creditVal2;
            }
        endif;

        $sqlArray = $this->db->query($_SESSION['sess_query']);
        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
                            <tr bgcolor="#ffffff">
                                <th>'.ACCODE.'</th>
                                <th>'.ACCCODENAME.'</th>
                                <th>'.SUBCODE.'</th>
                                <th>'.SUBNAME.'</th>
                                <th colspan="6">&nbsp;</th>
                            </tr>
                            <tr bgcolor="#ffffff">
                                <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                <th>'.$this->session->userdata('SubCode').'</th>
                                <th>'.$this->session->userdata('SubName').'</th>
                                <th colspan="2">&nbsp;</th>
                                <th colspan="2" align="center">'.TRANSACTION.'</th>
                                <th colspan="2" align="center">'.BALANCE.'</th>
                            </tr>
                            <tr bgcolor="#ffffff">
                                <th>'.SRNO.'</th>
                                <th>'.CUSTDATE.'</th>
                                <th>'.LOCATIONNAME.'</th>
                                <th>'.DOCUMENTNUMBER.'</th>
                                <th>'.TICKETNAME.'</th>
                                <th>'.DESCRIPTION.'</th>
                                <th>'.DEBIT.'</th>
                                <th>'.CREDIT.'</th>
                                <th>'.DEBIT.'</th>
                                <th>'.CREDIT.'</th>
                            </tr>
                            <tr bgcolor="#ffffff">
                                            <th colspan="6" align="centre">'.YEARCLOSING.'</th>
                                            <td>'.$debitVal2.'</td>
                                            <td>'.$creditVal2.'</td>
                                            <td>'.$balanceVal2.'</td>
                                            <td>&nbsp;</td>
                                        </tr>
                            <tr bgcolor="#ffffff">
                                <th colspan="6" align="centre">'.OPENINGBALANCE.'</th>
                                <td>'.$debitVal.'</td>
                                <td>'.$creditVal.'</td>
                                <td>'.$balanceVal.'</td>
                                <td>&nbsp;</td>
                            </tr>
                        </thead>
							<tbody>';
            $totalDebit = 0;
            $totalCredit = 0;
            $totalopenDebit = 0;

            $this->session->unset_userdata('newDebit');
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->tblName)
                {
                    case "CR":
                        $description = $row->description;
                        break;

                    case "CP":
                        $description = $row->description;
                        break;

                    case "BR":
                        $description = $row->description;
                        break;

                    case "BP":
                        $description = $row->description;
                        break;

                    case "JV":
                        $description = $row->description;
                        break;

                    case "SJ":
                        $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$row->tableId."'");
                        $ticketType  = $this->common_model->get_field_name('SystemJvDetails','vType'," WHERE nSjDId='".$row->detId."'");
                        $ticketId    = $this->common_model->get_field_name('SystemJvDetails','vNumber'," WHERE nSjDId='".$row->detId."'");
                        switch($ticketType)
                        {
                            case "Xo" :
                                $description  = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$ticketId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$ticketId."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('XoDetails','dTravelDate'," WHERE nXoId='".$ticketId."'")));
                                $ticketNum    = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$ticketId."'");
                                break;

                            case "Tkt" :
                                $description  = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$ticketId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$ticketId."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$ticketId."'")));
                                $ticketNum    = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$ticketId."'");
                                break;

                            case "Htl" :
                                $cityName     = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$ticketId."'");
                                $hotel        = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$ticketId."'");
                                $description  = $this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'-'.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$ticketId."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$ticketId."'"))).'-'.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$ticketId."'");
                                $ticketNum    = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$ticketId."'");
                                break;
                        }//end switch
                        break;

                    case "CV":
                        $description = $row->description;
                        break;

                    case "DV":
                        $description = $row->description;
                        break;

                    case "ACM":
                        $description = $row->description;
                        break;

                    case "ADM":
                        $description = $row->description;
                        break;
                }//end switch

                switch($row->mode)
                {
                    case "Dr":
                        $debit = $row->debit;
                        $credit = 0;
                        break;

                    case "Cr":
                        $debit = 0;
                        $credit = $row->debit;
                        break;

                    default:
                        $debit = ($row->debit==' ')?0:$row->debit;
                        $credit = ($row->credit==' ')?0:$row->credit;
                        break;
                }

                $openDebit = $this->ac_report_model->getCalDebit($debit,$credit);

                if($openDebit >= 0):
                    $totalopenDebit = $openDebit;
                endif;

                if($openDebit <= 0):
                    $openCredit = substr($openDebit,1,strlen($openDebit));
                    $totalopenCredit = $openCredit;
                endif;

                $srNo = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.date("d-M-y", strtotime($row->date)).'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'")).'</td>
                        <td>'.$row->document.'</td>
                        <td>'.$ticketNum.'</td>
                        <td>'.stripslashes($description).'</td>
                        <td>'.stripslashes($debit).'</td>
                        <td>'.stripslashes($credit).'</td>
                        <td>'.stripslashes($openDebit).'</td>
                        <td></td>
                    </tr>';
                $totalDebit += $debit;
                $totalCredit += $credit;
            }//end for each
            $pdfBody .= '
                    <tr>
                        <td colspan="6" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>'.$totalopenDebit.'</td>
                        <td>'.$totalopenCredit.'</td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>'.$totalopenDebit.'</td>
                        <td>'.$totalopenCredit.'</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), DETAILREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'detail_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function daily_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        if(trim($_SESSION['sess_opening']) == ''):
            $debitVal = $creditVal = $balanceVal = 0;
        else:
            $openSqlArray = $this->db->query($_SESSION['sess_opening']);
            if ($openSqlArray->num_rows() > 0)
            {
                $debit = 0;
                $credit = 0;
                foreach ($openSqlArray->result() as $key=>$row)
                {
                    $debit   += $row->debit;
                    $credit  += $row->credit;
                }//end for each
                $debitVal   = $debit;
                $creditVal  = $credit;
                $balanceVal = $debitVal - $creditVal;
            }
        endif;

        if(trim($_SESSION['sess_year']) == ''):
            $debitVal2 = $creditVal2 = $balanceVal2 = 0;
        else:
            $openSqlArray = $this->db->query($_SESSION['sess_year']);
            if ($openSqlArray->num_rows() > 0)
            {
                $debit2 = 0;
                $credit2 = 0;
                foreach ($openSqlArray->result() as $key=>$row)
                {
                    $debit2   += $row->DCTot;
                    $credit2  += $row->FCTot;
                }//end for each
                $debitVal2   = $debit2;
                $creditVal2  = $credit2;
                $balanceVal2 = $debitVal2 - $creditVal2;
            }
        endif;

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th>'.ACCODE.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SUBCODE.'</th>
                                    <th>'.SUBNAME.'</th>
                                    <th colspan="6">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                    <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                    <th>'.$this->session->userdata('SubCode').'</th>
                                    <th>'.$this->session->userdata('SubName').'</th>
                                    <th colspan="2">&nbsp;</th>
                                    <th colspan="2">'.TRANSACTION.'</th>
                                    <th colspan="2">'.BALANCE.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.SRNO.'</th>
                                    <th>'.CUSTDATE.'</th>
                                    <th>'.LOCATIONNAME.'</th>
                                    <th>'.SALESTYPE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.DESCRIPTION.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                            <th colspan="6" align="centre">'.YEARCLOSING.'</th>
                                            <td>'.$debitVal2.'</td>
                                            <td>'.$creditVal2.'</td>
                                            <td>'.$balanceVal2.'</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="6" align="centre">'.OPENINGBALANCE.'</th>
                                    <td>'.$debitVal.'</td>
                                    <td>'.$creditVal.'</td>
                                    <td>'.$balanceVal.'</td>
                                    <td>&nbsp;</td>
                                </tr>
							</thead>
							<tbody>';
            $totalDebit = 0;
            $totalCredit = 0;
            $totalopenDebit = 0;
            $chkDateArray = array();
            $chkTypeArray = array();

            $this->session->unset_userdata('newDebit');
            $openDebit = $this->ac_report_model->getCalDebit($debitVal,$creditVal);
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->tblName)
                {
                    case "CR":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "CP":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "BR":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "BP":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "JV":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "SJ":
                        $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$row->tableId."'");
                        $ticketType  = $this->common_model->get_field_name('SystemJvDetails','vType'," WHERE nSjDId='".$row->detId."'");
                        $ticketId    = $this->common_model->get_field_name('SystemJvDetails','vNumber'," WHERE nSjDId='".$row->detId."'");
                        $salesType   = SALESENTRY;
                        switch($ticketType)
                        {
                            case "Xo" :
                                $description = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$ticketId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$ticketId."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('XoDetails','dTravelDate'," WHERE nXoId='".$ticketId."'")));
                                break;

                            case "Tkt" :
                                $description = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$ticketId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$ticketId."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$ticketId."'")));
                                break;

                            case "Htl" :
                                $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$ticketId."'");
                                $hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$ticketId."'");
                                $description  = $this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'-'.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$ticketId."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$ticketId."'"))).'-'.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$ticketId."'");
                                break;
                        }//end switch
                        break;

                    case "CV":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "DV":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "ACM":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "ADM":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;
                }//end switch

                switch($row->mode)
                {
                    case "Dr":
                        $debit = $row->debit;
                        $credit = 0;
                        break;

                    case "Cr":
                        $debit = 0;
                        $credit = $row->debit;
                        break;

                    default:
                        $debit = ($row->debit==' ')?0:$row->debit;
                        $credit = ($row->credit==' ')?0:$row->credit;
                        break;
                }
                
                $openDebit = $this->ac_report_model->getCalDebit($debit,$credit);

                $srNo = $key+1;
                if(!in_array($row->date,$chkDateArray) || $chkTypeArray[$row->date] != $salesType):
                    $getVal = $this->ac_report_model->getDailyCntCreditDebit($salesType,$row->date,$sqlArray->result());
                            $pdfBody .= '
                                <tr bgcolor="#ffffff">
                                    <td>'.$srNo.'</td>
                                    <td>'.date("d-M-y", strtotime($row->date)).'</td>
                                    <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'")).'</td>
                                    <td>'.$salesType.'</td>
                                    <td>'.$getVal['cnt'].'</td>
                                    <td>'.stripslashes($description).'</td>
                                    <td>'.$getVal['wdebit'].'</td>
                                    <td>'.$getVal['wcredit'].'</td>
                                    <td>'.$getVal['openDebit'].'</td>
                                    <td>'.$getVal['openCredit'].'</td>
                                </tr>';
                endif;
                $totalDebit += $debit;
                $totalCredit += $credit;
                $totalopenDebit += $openDebit;
                if(!in_array($salesType,$chkTypeArray))
                    $chkDateArray[] = $row->date;
                $chkTypeArray[$row->date] = $salesType;
            }//end for each
            $pdfBody .= '
                    <tr>
                        <td colspan="6" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), DAILYREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'daily_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function weekly_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        if(trim($_SESSION['sess_opening']) == ''):
            $debitVal = $creditVal = $balanceVal = 0;
        else:
            $openSqlArray = $this->db->query($_SESSION['sess_opening']);
            if ($openSqlArray->num_rows() > 0)
            {
                $debit = 0;
                $credit = 0;
                foreach ($openSqlArray->result() as $key=>$row)
                {
                    $debit   += $row->debit;
                    $credit  += $row->credit;
                }//end for each
                $debitVal   = $debit;
                $creditVal  = $credit;
                $balanceVal = $debitVal - $creditVal;
            }
        endif;

        if(trim($_SESSION['sess_year']) == ''):
            $debitVal2 = $creditVal2 = $balanceVal2 = 0;
        else:
            $openSqlArray = $this->db->query($_SESSION['sess_year']);
            if ($openSqlArray->num_rows() > 0)
            {
                $debit2 = 0;
                $credit2 = 0;
                foreach ($openSqlArray->result() as $key=>$row)
                {
                    $debit2   += $row->DCTot;
                    $credit2  += $row->FCTot;
                }//end for each
                $debitVal2   = $debit2;
                $creditVal2  = $credit2;
                $balanceVal2 = $debitVal2 - $creditVal2;
            }
        endif;

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th>'.ACCODE.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SUBCODE.'</th>
                                    <th>'.SUBNAME.'</th>
                                    <th colspan="6">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                    <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                    <th>'.$this->session->userdata('SubCode').'</th>
                                    <th>'.$this->session->userdata('SubName').'</th>
                                    <th colspan="2">&nbsp;</th>
                                    <th colspan="2">'.TRANSACTION.'</th>
                                    <th colspan="2">'.BALANCE.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.SRNO.'</th>
                                    <th>'.CUSTDATE.'</th>
                                    <th>'.LOCATIONNAME.'</th>
                                    <th>'.SALESTYPE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.DESCRIPTION.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                            <th colspan="6" align="centre">'.YEARCLOSING.'</th>
                                            <td>'.$debitVal2.'</td>
                                            <td>'.$creditVal2.'</td>
                                            <td>'.$balanceVal2.'</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="6" align="centre">'.OPENINGBALANCE.'</th>
                                    <td>'.$debitVal.'</td>
                                    <td>'.$creditVal.'</td>
                                    <td>'.$balanceVal.'</td>
                                    <td>&nbsp;</td>
                                </tr>
							</thead>
							<tbody>';
            $totalDebit = 0;
            $totalCredit = 0;
            $totalopenDebit = 0;
            $srNo = 1;
            $date_weeks         = $this->ac_report_model->getNoWeeks($this->session->userdata('txtFromDate'),$this->session->userdata('txtToDate'));
            $no_weeks           = $date_weeks['weeknos'];
            $start_date         = $date_weeks['startDate'];
            if(!empty($no_weeks)):

			    foreach($no_weeks as $wkey => $day):
                    $salesArray = array();
                    $cnt = $wdebit = $wcredit = 0;
                    if(($wkey+1) == count($no_weeks))
                        $endDate = $this->session->userdata('txtToDate');
                    else
                        $endDate = $start_date[($wkey+1)];
                    $pdfBody .= '<tr>
							<td colspan="6" align="center"><strong>'.$day.' Week</strong></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
    				</tr>';
                    $this->session->unset_userdata('newDebit');

                    $openDebit = $this->ac_report_model->getCalDebit($debitVal,$creditVal);
                foreach ($sqlArray->result() as $key => $row)
                {
                    //start date
                    $sDate = $start_date[$wkey];
                    //end date
                    $eDate = $endDate;
                    if($row->date >= $sDate && $row->date < $eDate):
                        switch($row->tblName)
                        {
                            case "CR":
                                $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$row->tableId;
                                $description = $row->description;
                                $salesType   = FINANCEENTRY;
                                break;

                            case "CP":
                                $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$row->tableId;
                                $description = $row->description;
                                $salesType   = FINANCEENTRY;
                                break;

                            case "BR":
                                $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$row->tableId;
                                $description = $row->description;
                                $salesType   = FINANCEENTRY;
                                break;

                            case "BP":
                                $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$row->tableId;
                                $description = $row->description;
                                $salesType   = FINANCEENTRY;
                                break;

                            case "JV":
                                $path = base_url().'index.php/jv/editJv/5/e/'.$row->tableId;
                                $description = $row->description;
                                $salesType   = FINANCEENTRY;
                                break;

                            case "SJ":
                                $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$row->tableId."'");
                                $path        = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$row->tableId.'/cl';
                                $ticketType  = $this->common_model->get_field_name('SystemJvDetails','vType'," WHERE nSjDId='".$row->detId."'");
                                $ticketId    = $this->common_model->get_field_name('SystemJvDetails','vNumber'," WHERE nSjDId='".$row->detId."'");
                                $salesType   = SALESENTRY;
                                $getTktValue = $this->ac_report_model->getTktNumberValues($row->detId,$row->tableId);
                                $description = $getTktValue['desc'];
                                $ticketNum   = $getTktValue['tktVal'];
                                break;

                            case "CV":
                                $path        = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$row->tableId;
                                $description = $row->description;
                                $salesType   = FINANCEENTRY;
                                break;

                            case "DV":
                                $path        = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$row->tableId;
                                $description = $row->description;
                                $salesType   = FINANCEENTRY;
                                break;

                            case "ACM":
                                $path        = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$row->tableId;
                                $description = $row->description;
                                $salesType   = FINANCEENTRY;
                                break;

                            case "ADM":
                                $path        = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$row->tableId;
                                $description = $row->description;
                                $salesType   = FINANCEENTRY;
                                break;
                        }//end switch

                        switch($row->mode)
                        {
                            case "Dr":
                                $debit = $row->debit;
                                $credit = 0;
                                break;

                            case "Cr":
                                $debit = 0;
                                $credit = $row->debit;
                                break;

                            default:
                                $debit = ($row->debit==' ')?0:$row->debit;
                                $credit = ($row->credit==' ')?0:$row->credit;
                                break;
                        }
                        //$openDebit = $this->ac_report_model->getCalDebit($debit,$credit);
                        if(!in_array($salesType,$salesArray)):
                            $cnt++;
                            $getVal = $this->ac_report_model->getWeekCntCreditDebit($salesType,$sDate,$eDate,$sqlArray->result());

                            $pdfBody .= '
                            <tr>
                                <td>'.$srNo.'</td>
                                <td>'.date("M-y", strtotime($row->date)).'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.$salesType.'</td>
                                <td>'.$getVal['cnt'].'</td>
                                <td>'.$description.'</td>
                                <td>'.$getVal['wdebit'].'</td>
                                <td>'.$getVal['wcredit'].'</td>
                                <td>'.$getVal['openDebit'].'</td>
                                <td>'.$getVal['openCredit'].'</td>
                            </tr>';
                            $srNo++;
                            $wcredit = $credit;
                            $wdebit  = $debit;
                        endif;//sale array check it
                        $salesArray[] = $salesType;

                        $totalDebit += $debit;
                        $totalCredit += $credit;
                    endif;
                }//end foreach
                endforeach;
            endif;
            $pdfBody .= '
                    <tr>
                        <td colspan="6" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), WEEKLYREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'weekly_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function monthly_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        if(trim($_SESSION['sess_opening']) == ''):
            $debitVal = $creditVal = $balanceVal = 0;
        else:
            $openSqlArray = $this->db->query($_SESSION['sess_opening']);
            if ($openSqlArray->num_rows() > 0)
            {
                $debit = 0;
                $credit = 0;
                foreach ($openSqlArray->result() as $key=>$row)
                {
                    $debit   += $row->debit;
                    $credit  += $row->credit;
                }//end for each
                $debitVal   = $debit;
                $creditVal  = $credit;
                $balanceVal = $debitVal - $creditVal;
            }
        endif;

        if(trim($_SESSION['sess_year']) == ''):
            $debitVal2 = $creditVal2 = $balanceVal2 = 0;
        else:
            $openSqlArray = $this->db->query($_SESSION['sess_year']);
            if ($openSqlArray->num_rows() > 0)
            {
                $debit2 = 0;
                $credit2 = 0;
                foreach ($openSqlArray->result() as $key=>$row)
                {
                    $debit2   += $row->DCTot;
                    $credit2  += $row->FCTot;
                }//end for each
                $debitVal2   = $debit2;
                $creditVal2  = $credit2;
                $balanceVal2 = $debitVal2 - $creditVal2;
            }
        endif;

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th>'.ACCODE.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SUBCODE.'</th>
                                    <th>'.SUBNAME.'</th>
                                    <th colspan="6">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                    <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                    <th>'.$this->session->userdata('SubCode').'</th>
                                    <th>'.$this->session->userdata('SubName').'</th>
                                    <th colspan="2">&nbsp;</th>
                                    <th colspan="2">'.TRANSACTION.'</th>
                                    <th colspan="2">'.BALANCE.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.SRNO.'</th>
                                    <th>'.CUSTDATE.'</th>
                                    <th>'.LOCATIONNAME.'</th>
                                    <th>'.SALESTYPE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.DESCRIPTION.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                            <th colspan="6" align="centre">'.YEARCLOSING.'</th>
                                            <td>'.$debitVal2.'</td>
                                            <td>'.$creditVal2.'</td>
                                            <td>'.$balanceVal2.'</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="6" align="centre">'.OPENINGBALANCE.'</th>
                                    <td>'.$debitVal.'</td>
                                    <td>'.$creditVal.'</td>
                                    <td>'.$balanceVal.'</td>
                                    <td>&nbsp;</td>
                                </tr>
							</thead>
							<tbody>';
            $totalDebit = 0;
            $totalCredit = 0;
            $totalopenDebit = 0;
                $srNo = 1;
            $date_weeks         = $this->ac_report_model->getNoMonth($this->session->userdata('txtFromDate'),$this->session->userdata('txtToDate'));
            $no_weeks           = $date_weeks['monthnos'];
            $start_date         = $date_weeks['startDate'];
            if(!empty($no_weeks)):

			    foreach($no_weeks as $wkey => $day):
                    $salesArray = array();
                    $cnt = $wdebit = $wcredit = 0;
                    if(($wkey+1) == count($no_weeks))
                        $endDate = $this->session->userdata('txtToDate');
                    else
                        $endDate = $start_date[($wkey+1)];
                    $pdfBody .= '<tr>
							<td colspan="6" align="center"><strong>'.$day.'</strong></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
    				</tr>';
                    $this->session->unset_userdata('newDebit');

                    $openDebit = $this->ac_report_model->getCalDebit($debitVal,$creditVal);
                foreach ($sqlArray->result() as $key => $row)
                {
                    //start date
                    $sDate = $start_date[$wkey];
                    //end date
                    $eDate = $endDate;
                    if($row->date >= $sDate && $row->date < $eDate):
                    switch($row->tblName)
                    {
                        case "CR":
                            $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$row->tableId;
                            $description = $row->description;
                            $salesType   = FINANCEENTRY;
                            break;

                        case "CP":
                            $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$row->tableId;
                            $description = $row->description;
                            $salesType   = FINANCEENTRY;
                            break;

                        case "BR":
                            $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$row->tableId;
                            $description = $row->description;
                            $salesType   = FINANCEENTRY;
                            break;

                        case "BP":
                            $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$row->tableId;
                            $description = $row->description;
                            $salesType   = FINANCEENTRY;
                            break;

                        case "JV":
                            $path = base_url().'index.php/jv/editJv/5/e/'.$row->tableId;
                            $description = $row->description;
                            $salesType   = FINANCEENTRY;
                            break;

                        case "SJ":
                            $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$row->tableId."'");
                            $path        = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$row->tableId.'/cl';
                            $ticketType  = $this->common_model->get_field_name('SystemJvDetails','vType'," WHERE nSjDId='".$row->detId."'");
                            $ticketId    = $this->common_model->get_field_name('SystemJvDetails','vNumber'," WHERE nSjDId='".$row->detId."'");
                            $salesType   = SALESENTRY;
                            $getTktValue = $this->ac_report_model->getTktNumberValues($row->detId,$row->tableId);
                            $description = $getTktValue['desc'];
                            $ticketNum   = $getTktValue['tktVal'];
                            break;

                        case "CV":
                            $path        = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$row->tableId;
                            $description = $row->description;
                            $salesType   = FINANCEENTRY;
                            break;

                        case "DV":
                            $path        = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$row->tableId;
                            $description = $row->description;
                            $salesType   = FINANCEENTRY;
                            break;

                        case "ACM":
                            $path        = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$row->tableId;
                            $description = $row->description;
                            $salesType   = FINANCEENTRY;
                            break;

                        case "ADM":
                            $path        = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$row->tableId;
                            $description = $row->description;
                            $salesType   = FINANCEENTRY;
                            break;
                    }//end switch

                        switch($row->mode)
                        {
                            case "Dr":
                                $debit = $row->debit;
                                $credit = 0;
                                break;

                            case "Cr":
                                $debit = 0;
                                $credit = $row->debit;
                                break;

                            default:
                                $debit = ($row->debit==' ')?0:$row->debit;
                                $credit = ($row->credit==' ')?0:$row->credit;
                                break;
                        }

                        //$openDebit = $this->ac_report_model->getCalDebit($debit,$credit);
                    if(!in_array($salesType,$salesArray)):
                        $cnt++;
                        $getVal = $this->ac_report_model->getWeekCntCreditDebit($salesType,$sDate,$eDate,$sqlArray->result());

                $pdfBody .= '<tr bgcolor="#ffffff">
                                <td>'.$srNo.'</td>
                                <td>'.date("M-y", strtotime($row->date)).'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.$salesType.'</td>
                                <td>'.$getVal['cnt'].'</td>
                                <td>'.$description.'</td>
                                <td>'.$getVal['wdebit'].'</td>
                                <td>'.$getVal['wcredit'].'</td>
                                <td>'.$getVal['openDebit'].'</td>
                                <td>'.$getVal['openCredit'].'</td>
                            </tr>';
                        $srNo++;
                        $wcredit = $credit;
                        $wdebit  = $debit;
                    endif;//sale array check it
                        $salesArray[] = $salesType;

                        $totalDebit += $debit;
                        $totalCredit += $credit;
                    endif;
                }//end foreach
                endforeach;
            endif;
            $pdfBody .= '<tr>
                        <td colspan="6" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), MONTHLYREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'monthly_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function yearly_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        if(trim($_SESSION['sess_opening']) == ''):
            $debitVal = $creditVal = $balanceVal = 0;
        else:
            $openSqlArray = $this->db->query($_SESSION['sess_opening']);
            if ($openSqlArray->num_rows() > 0)
            {
                $debit = 0;
                $credit = 0;
                foreach ($openSqlArray->result() as $key=>$row)
                {
                    $debit   += $row->debit;
                    $credit  += $row->credit;
                }//end for each
                $debitVal   = $debit;
                $creditVal  = $credit;
                $balanceVal = $debitVal - $creditVal;
            }
        endif;

        if(trim($_SESSION['sess_year']) == ''):
            $debitVal2 = $creditVal2 = $balanceVal2 = 0;
        else:
            $openSqlArray = $this->db->query($_SESSION['sess_year']);
            if ($openSqlArray->num_rows() > 0)
            {
                $debit2 = 0;
                $credit2 = 0;
                foreach ($openSqlArray->result() as $key=>$row)
                {
                    $debit2   += $row->DCTot;
                    $credit2  += $row->FCTot;
                }//end for each
                $debitVal2   = $debit2;
                $creditVal2  = $credit2;
                $balanceVal2 = $debitVal2 - $creditVal2;
            }
        endif;

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th>'.ACCODE.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SUBCODE.'</th>
                                    <th>'.SUBNAME.'</th>
                                    <th colspan="6">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                    <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                    <th>'.$this->session->userdata('SubCode').'</th>
                                    <th>'.$this->session->userdata('SubName').'</th>
                                    <th colspan="2">&nbsp;</th>
                                    <th colspan="2">'.TRANSACTION.'</th>
                                    <th colspan="2">'.BALANCE.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.SRNO.'</th>
                                    <th>'.CUSTDATE.'</th>
                                    <th>'.LOCATIONNAME.'</th>
                                    <th>'.SALESTYPE.'</th>
                                    <th>'.COUNT.'</th>
                                    <th>'.DESCRIPTION.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                            <th colspan="6" align="centre">'.YEARCLOSING.'</th>
                                            <td>'.$debitVal2.'</td>
                                            <td>'.$creditVal2.'</td>
                                            <td>'.$balanceVal2.'</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="6" align="centre">'.OPENINGBALANCE.'</th>
                                    <td>'.$debitVal.'</td>
                                    <td>'.$creditVal.'</td>
                                    <td>'.$balanceVal.'</td>
                                    <td>&nbsp;</td>
                                </tr>
							</thead>
							<tbody>';
            $totalDebit = 0;
            $totalCredit = 0;
            $totalopenDebit = 0;
               $srNo = 1;
            $date_weeks         = $this->ac_report_model->getNoYears($this->session->userdata('txtFromDate'),$this->session->userdata('txtToDate'));
            $no_weeks           = $date_weeks['yearnos'];
            $start_date         = $date_weeks['startDate'];
            if(!empty($no_weeks)):

                foreach($no_weeks as $wkey => $day):
                    $salesArray = array();
                    $cnt = $wdebit = $wcredit = 0;
                    if(($wkey+1) == count($no_weeks))
                        $endDate = $this->session->userdata('txtToDate');
                    else
                        $endDate = $start_date[($wkey+1)];
                    $pdfBody .= '<tr>
							<td colspan="6" align="center"><strong>'.$day.'</strong></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
    				</tr>';
                    $this->session->unset_userdata('newDebit');

                    $openDebit = $this->ac_report_model->getCalDebit($debitVal,$creditVal);
                    foreach ($sqlArray->result() as $key => $row)
                    {
                        //start date
                        $sDate = $start_date[$wkey];
                        //end date
                        $eDate = $endDate;
                        if($row->date >= $sDate && $row->date < $eDate):
                            switch($row->tblName)
                            {
                                case "CR":
                                    $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$row->tableId;
                                    $description = $row->description;
                                    $salesType   = FINANCEENTRY;
                                    break;

                                case "CP":
                                    $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$row->tableId;
                                    $description = $row->description;
                                    $salesType   = FINANCEENTRY;
                                    break;

                                case "BR":
                                    $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$row->tableId;
                                    $description = $row->description;
                                    $salesType   = FINANCEENTRY;
                                    break;

                                case "BP":
                                    $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$row->tableId;
                                    $description = $row->description;
                                    $salesType   = FINANCEENTRY;
                                    break;

                                case "JV":
                                    $path = base_url().'index.php/jv/editJv/5/e/'.$row->tableId;
                                    $description = $row->description;
                                    $salesType   = FINANCEENTRY;
                                    break;

                                case "SJ":
                                    $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$row->tableId."'");
                                    $path        = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$row->tableId.'/cl';
                                    $ticketType  = $this->common_model->get_field_name('SystemJvDetails','vType'," WHERE nSjDId='".$row->detId."'");
                                    $ticketId    = $this->common_model->get_field_name('SystemJvDetails','vNumber'," WHERE nSjDId='".$row->detId."'");
                                    $salesType   = SALESENTRY;
                                    $getTktValue = $this->ac_report_model->getTktNumberValues($row->detId,$row->tableId);
                                    $description = $getTktValue['desc'];
                                    $ticketNum   = $getTktValue['tktVal'];
                                    break;

                                case "CV":
                                    $path        = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$row->tableId;
                                    $description = $row->description;
                                    $salesType   = FINANCEENTRY;
                                    break;

                                case "DV":
                                    $path        = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$row->tableId;
                                    $description = $row->description;
                                    $salesType   = FINANCEENTRY;
                                    break;

                                case "ACM":
                                    $path        = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$row->tableId;
                                    $description = $row->description;
                                    $salesType   = FINANCEENTRY;
                                    break;

                                case "ADM":
                                    $path        = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$row->tableId;
                                    $description = $row->description;
                                    $salesType   = FINANCEENTRY;
                                    break;
                            }//end switch

                            switch($row->mode)
                            {
                                case "Dr":
                                    $debit = $row->debit;
                                    $credit = 0;
                                    break;

                                case "Cr":
                                    $debit = 0;
                                    $credit = $row->debit;
                                    break;

                                default:
                                    $debit = ($row->debit==' ')?0:$row->debit;
                                    $credit = ($row->credit==' ')?0:$row->credit;
                                    break;
                            }

                            //$openDebit = $this->ac_report_model->getCalDebit($debit,$credit);
                            if(!in_array($salesType,$salesArray)):
                                $cnt++;
                                $getVal = $this->ac_report_model->getWeekCntCreditDebit($salesType,$sDate,$eDate,$sqlArray->result());

                                $pdfBody .= '<tr bgcolor="#ffffff">
                                <td>'.$srNo.'</td>
                                <td>'.date("M-y", strtotime($row->date)).'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.$salesType.'</td>
                                <td>'.$getVal['cnt'].'</td>
                                <td>'.$description.'</td>
                                <td>'.$getVal['wdebit'].'</td>
                                <td>'.$getVal['wcredit'].'</td>
                                <td>'.$getVal['openDebit'].'</td>
                                <td>'.$getVal['openCredit'].'</td>
                            </tr>';
                                $srNo++;
                                $wcredit = $credit;
                                $wdebit  = $debit;
                            endif;//sale array check it
                            $salesArray[] = $salesType;

                            $totalDebit += $debit;
                            $totalCredit += $credit;
                        endif;
                    }//end foreach
                endforeach;
            endif;
            $pdfBody .= '
                    <tr>
                        <td colspan="6" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), YEARLYREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'yearly_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function summary_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();

        $pdfBody = $_SESSION['summary_report'];
//echo $pdfBody;
        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(),"", $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'summary_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function summary_report_group()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $i = 1;
            $chkAccountId = $tablArray = array();
            $cashDebit = $cashCredit = 0;

            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                            <th>'.ACCODE.'</th>
                                            <th>'.ACCCODENAME.'</th>
                                            <th>'.SUBCODE.'</th>
                                            <th>'.SUBNAME.'</th>
                                            <th colspan="8">&nbsp;</th>
                                        </tr>
                                        <tr bgcolor="#ffffff">
                                            <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                            <th>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</th>
                                            <th>'.$this->session->userdata('SubCode').'</th>
                                            <th>'.$this->session->userdata('SubName').'</th>
                                            <th align="left">&nbsp;</th>
                                            <th align="left">&nbsp;</th>
                                            <th align="center" colspan="2" align="center">'.OPENING.'</th>
                                            <th align="center" colspan="2" align="center">'.TRANSACTION.'</th>
                                            <th align="center" colspan="2" align="center">'.BALANCE.'</th>
                                        </tr>
                                        <tr bgcolor="#ffffff">
                                            <th>'.LOCATIONNAME.'</th>
                                            <th>'.ACCODE.'</th>
                                            <th>'.ACCCODENAME.'</th>
                                            <th>'.SUBCODE.'</th>
                                            <th>'.SUBNAME.'</th>
                                            <th>'.COUNT.'</th>
                                            <th>'.DEBIT.'</th>
                                            <th>'.CREDIT.'</th>
                                            <th>'.DEBIT.'</th>
                                            <th>'.CREDIT.'</th>
                                            <th>'.DEBIT.'</th>
                                            <th>'.CREDIT.'</th>
                                        </tr>
							</thead>
							<tbody>';
            $totalDebit = 0;
            $totalCredit = 0;
            $totalopenDebit = 0;

            $this->session->unset_userdata('newDebit');
            foreach ($sqlArray->result() as $key => $row)
            {
                $this->session->unset_userdata('newDebit');
                switch($row->tblName)
                {
                    case "CR":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "CP":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "BR":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "BP":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "JV":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "SJ":
                        $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$row->tableId."'");
                        $ticketType  = $this->common_model->get_field_name('SystemJvDetails','vType'," WHERE nSjDId='".$row>detId."'");
                        $ticketId    = $this->common_model->get_field_name('SystemJvDetails','vNumber'," WHERE nSjDId='".$row->detId."'");
                        $salesType   = SALESENTRY;
                        switch($ticketType)
                        {
                            case "Xo" :
                                $description = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$ticketId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$ticketId."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('XoDetails','dTravelDate'," WHERE nXoId='".$ticketId."'")));
                                break;

                            case "Tkt" :
                                $description = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$ticketId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$ticketId."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$ticketId."'")));
                                break;

                            case "Htl" :
                                $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$ticketId."'");
                                $hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$ticketId."'");
                                $description  = $this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'-'.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$ticketId."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$ticketId."'"))).'-'.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$ticketId."'");
                                break;
                        }//end switch
                        break;

                    case "CV":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "DV":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "ACM":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;

                    case "ADM":
                        $description = $row->description;
                        $salesType   = FINANCEENTRY;
                        break;
                }//end switch

                switch($row->mode)
                {
                    case "Dr":
                        $debit = $row->debit;
                        $credit = 0;
                        break;

                    case "Cr":
                        $debit = 0;
                        $credit = $row->debit;
                        break;

                    default:
                        $debit = ($row->debit==' ')?0:$row->debit;
                        $credit = ($row->credit==' ')?0:$row->credit;
                        break;
                }

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

                $openDebit = $this->ac_report_model->getCalDebit($debit,$credit);

                $srNo = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                        <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row->subacc."'").'</td>
                        <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->subacc."'").'</td>
                        <td>'.$SubAccountCode.'</td>
                        <td>'.$SubAccountName.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>'.$debit.'</td>
                        <td>'.$credit.'</td>
                        <td>'.$openDebit.'</td>
                        <td>&nbsp;</td>
                    </tr>';
                $totalDebit += $debit;
                $totalCredit += $credit;
                $totalopenDebit += $openDebit;

                if(!in_array($row->tblName,$tablArray))
                {
                    switch($row->mainMode)
                    {
                        case "Dr":
                            $cashDebit += $row->mainAmount;
                            $totalDebit += $cashDebit;
                            break;

                        case "Cr":
                            $cashCredit += $row->mainAmount;
                            $totalCredit += $cashCredit;
                            break;
                    }//end swtich
                }//end if

                $tablArray[]     = $row->tblName;

            }//end for each
            $pdfBody .= '
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</td>
                        <td align="left">'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'").'</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>'.$cashDebit.'</td>
                        <td>'.$cashCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SUMMARYREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'summary_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function receivable_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th>'.ACCODE.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SUBCODE.'</th>
                                    <th>'.SUBNAME.'</th>
                                    <th colspan="7">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th colspan="7">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.SRNO.'</th>
                                    <th>'.CUSTDATE.'</th>
                                    <th>'.DOCUMENTNUMBER.'</th>
                                    <th>'.LOCATIONNAME.'</th>
                                    <th>'.TICKETNAME.'</th>
                                    <th>'.PASSENGERNAME.'</th>
                                    <th>'.TRAVELSETUPDETAILS.'</th>
                                    <th>'.LPO.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                    <th>'.BALANCE.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="11" align="centre">'.OPENINGBALANCE.'</th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->tblName)
                {
                    case "CR":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "CP":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BR":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BP":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "JV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "SJ":
                        $lpoVal      = $this->common_model->get_field_name('SalesClosingDetails','vDLPO'," WHERE nSalesCloseId='".$row->tableId."'");
                        $getTktValue = $this->sub_report_model->getTktNumberValues($row->detId,$row->tableId);
                        $description = $getTktValue['desc'];
                        $ticketNum   = $getTktValue['tktVal'];
                        $passenger   = $getTktValue['passVal'];
                        break;

                    case "CV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "DV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ACM":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ADM":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;
                }//end switch

                switch($row->mode)
                {
                    case "Dr":
                        $debit = $row->debit;
                        $credit = 0;
                        break;

                    case "Cr":
                        $debit = 0;
                        $credit = $row->debit;
                        break;

                    default:
                        $debit = ($row->debit==' ')?0:$row->debit;
                        $credit = ($row->credit==' ')?0:$row->credit;
                        break;
                }
                $balance = $debit-$credit;
                if($balance < 0):
                    $balance = substr($balance,1,strlen($balance));
                endif;

                $srNo = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.date("d-M-y", strtotime($row->date)).'</td>
                        <td>'.$row->document.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'")).'</td>
                        <td>'.$ticketNum.'</td>
                        <td>'.$passenger.'</td>
                        <td>'.$description.'</td>
                        <td>'.$lpoVal.'</td>
                        <td>'.$debit.'</td>
                        <td>'.$credit.'</td>
                        <td>'.$balance.'</td>
                    </tr>';
                $totalDebit   += $debit;
                $totalCredit  += $credit;
                $totalBalance  = $balance;
            }//end for each
            $pdfBody .= '
                    <tr>
                        <td colspan="8" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>'.$totalBalance.'</td>
                    </tr>
                    <tr>
                        <td colspan="8" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>'.$totalBalance.'</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), RECEIVABLEREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'receivable_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function receivable_net_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
                                <tr bgcolor="#ffffff">
                                    <th>'.ACCODE.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SUBCODE.'</th>
                                    <th>'.SUBNAME.'</th>
                                    <th colspan="15">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left" colspan="4">&nbsp;</th>
                                    <th align="center" colspan="3">'.SALESDETAILS.'</th>
                                    <th align="center" colspan="6">'.NETDETAILS.'</th>
                                    <th align="center" colspan="2">'.MARGIN.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.SRNO.'</th>
                                    <th>'.CUSTDATE.'</th>
                                    <th>'.DOCUMENTNUMBER.'</th>
                                    <th>'.LOCATIONNAME.'</th>
                                    <th>'.TICKETNAME.'</th>
                                    <th>'.PASSENGERNAME.'</th>
                                    <th>'.TRAVELSETUPDETAILS.'</th>
                                    <th>'.LPO.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                    <th>'.BALANCE.'</th>
                                    <th>'.VENDORNAME.'</th>
                                    <th>'.NETAMT.'</th>
                                    <th>'.PAID.'</th>
                                    <th>'.BALANCE.'</th>
                                    <th>'.PAIDDT.'</th>
                                    <th>'.PAID.'</th>
                                    <th>'.PROFIT.'</th>
                                    <th>'.PROFITPERCENTAGE.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="19" align="centre">'.OPENINGBALANCE.'</th>
                                </tr>
                            </thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->tblName)
                {
                    case "CR":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "CP":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BR":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BP":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "JV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "SJ":
                        $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$row->tableId."'");
                        $lpoVal      = $this->common_model->get_field_name('SalesClosingDetails','vDLPO'," WHERE nSalesCloseId='".$row->tableId."'");
                        $netVal      = $this->common_model->get_field_name('SalesClosingDetails','vNetAmt'," WHERE nSalesCloseId='".$row->tableId."'");
                        $serVal      = $this->common_model->get_field_name('SalesClosingDetails','vServAmt'," WHERE nSalesCloseId='".$row->tableId."'");
                        $PerVal      = $this->common_model->get_field_name('SalesClosingDetails','vServPercent'," WHERE nSalesCloseId='".$row->tableId."'");

                        $getTktValue  = $this->sub_report_model->getTktNumberValues($row->detId,$row->tableId);
                        $description = $getTktValue['desc'];
                        $ticketNum = $getTktValue['tktVal'];
                        $passenger = $getTktValue['passVal'];
                        break;

                    case "CV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "DV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ACM":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ADM":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;
                }//end switch

                switch($row->mode)
                {
                    case "Dr":
                        $debit = $row->debit;
                        $credit = 0;
                        break;

                    case "Cr":
                        $debit = 0;
                        $credit = $row->debit;
                        break;

                    default:
                        $debit = ($row->debit==' ')?0:$row->debit;
                        $credit = ($row->credit==' ')?0:$row->credit;
                        break;
                }
                $balance = $debit-$credit;
                if($balance < 0):
                    $balance = substr($balance,1,strlen($balance));
                endif;

                $srNo = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.date("d-M-y", strtotime($row->date)).'</td>
                        <td>'.$row->document.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'")).'</td>
                        <td>'.$ticketNum.'</td>
                        <td>'.$passenger.'</td>
                        <td>'.$description.'</td>
                        <td>'.$lpoVal.'</td>
                        <td>'.$debit.'</td>
                        <td>'.$credit.'</td>
                        <td>'.$balance.'</td>
                        <td></td>
                        <td>'.$netVal.'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>'.$serVal.'</td>
                        <td>'.$PerVal.'</td>
                    </tr>';
                $totDebit += $debit;
                $totCredit += $credit;
                $totBalance = $balance;
                $totNet += $netVal;
                $totSer += $serVal;
                $totPer += $PerVal;
            }//end for each
            $pdfBody .= '
                    <tr>
                        <td colspan="8" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>'.$totDebit.'</td>
                        <td>'.$totCredit.'</td>
                        <td>'.$totBalance.'</td>
                        <td></td>
                        <td>'.$totNet.'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>'.$totSer.'</td>
                        <td>'.$totPer.'</td>
                    </tr>
                    <tr>
                        <td colspan="8" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>'.$totDebit.'</td>
                        <td>'.$totCredit.'</td>
                        <td>'.$totBalance.'</td>
                        <td></td>
                        <td>'.$totNet.'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>'.$totSer.'</td>
                        <td>'.$totPer.'</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), RECEIVABLEWITHNETDETAIL, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'receivable_net_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A2');
    }//end function

    function payable_net_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
                                <tr bgcolor="#ffffff">
                                    <th>'.ACCODE.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SUBCODE.'</th>
                                    <th>'.SUBNAME.'</th>
                                    <th colspan="16">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left" colspan="5">&nbsp;</th>
                                    <th align="center" colspan="3">'.SALESDETAILS.'</th>
                                    <th align="center" colspan="6">'.NETDETAILS.'</th>
                                    <th align="center" colspan="2">'.MARGIN.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.SRNO.'</th>
                                    <th>'.CUSTDATE.'</th>
                                    <th>'.DOCUMENTNUMBER.'</th>
                                    <th>'.LOCATIONNAME.'</th>
                                    <th>'.VCH.'</th>
                                    <th>'.CONF.'</th>
                                    <th>'.ISSUEDBY.'</th>
                                    <th>'.PASSENGERNAME.'</th>
                                    <th>'.TRAVELDETAILS.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                    <th>'.BALANCE.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SALESAMT.'</th>
                                    <th>'.RCVD.'</th>
                                    <th>'.BALANCE.'</th>
                                    <th>'.RCVDT.'</th>
                                    <th>'.RECEIVE.'</th>
                                    <th>'.PROFIT.'</th>
                                    <th>'.PROFITPERCENTAGE.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="20" align="centre">'.OPENINGBALANCE.'</th>
                                </tr>
                            </thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->tblName)
                {
                    case "CR":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "CP":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BR":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BP":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "JV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "SJ":
                        $netVal      = $this->common_model->get_field_name('SalesClosingDetails','vNetAmt'," WHERE nSalesCloseId='".$row->tableId."'");
                        $serVal      = $this->common_model->get_field_name('SalesClosingDetails','vServAmt'," WHERE nSalesCloseId='".$row->tableId."'");
                        $PerVal      = $this->common_model->get_field_name('SalesClosingDetails','vServPercent'," WHERE nSalesCloseId='".$row->tableId."'");

                        $getTktValue  = $this->sub_report_model->getTktNumberValues($row->detId,$row->tableId);
                        $description = $getTktValue['desc'];
                        $ticketNum = $getTktValue['tktVal'];
                        $passenger = $getTktValue['passVal'];
                        break;

                    case "CV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "DV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ACM":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ADM":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;
                }//end switch

                switch($row->mode)
                {
                    case "Dr":
                        $debit = $row->debit;
                        $credit = 0;
                        break;

                    case "Cr":
                        $debit = 0;
                        $credit = $row->debit;
                        break;

                    default:
                        $debit = ($row->debit==' ')?0:$row->debit;
                        $credit = ($row->credit==' ')?0:$row->credit;
                        break;
                }
                $balance = $debit-$credit;
                if($balance < 0):
                    $balance = substr($balance,1,strlen($balance));
                endif;

                $srNo = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.date("d-M-y", strtotime($row->date)).'</td>
                        <td>'.$row->document.'</td>
                        <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId='".$row->location."'").'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>'.$passenger.'</td>
                        <td>'.$description.'</td>
                        <td>'.$debit.'</td>
                        <td>'.$credit.'</td>
                        <td>'.$balance.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>'.$serVal.'</td>
                        <td>'.$PerVal.'</td>
                    </tr>';
                $totalDebit   += $debit;
                $totalCredit  += $credit;
                $totalBalance = $balance;
                $totalSer     += $serVal;
                $totalPer     += $PerVal;
            }//end for each
            $pdfBody .= '
                    <tr>
                        <td colspan="9" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>'.$totalBalance.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>'.$totalSer.'</td>
                        <td>'.$totalPer.'</td>
                    </tr>
                    <tr>
                        <td colspan="9" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>'.$totalBalance.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>'.$totalSer.'</td>
                        <td>'.$totalPer.'</td>
                    </tr>
                </tbody></table>';
        }//end if

        if(empty($pdfBody))
            $pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), PAYABLEWITHSALESDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'payable_net_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A2');
    }//end function

    function fcdc_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
                                    <th align="left">'.ACCODE.'</th>
                                    <th align="left">'.ACCCODENAME.'</th>
                                    <th align="left">'.SUBCODE.'</th>
                                    <th align="left">'.SUBNAME.'</th>
                                    <th align="left" colspan="12">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left" colspan="5">&nbsp;</th>
                                    <th align="center" colspan="4">'.FOREIGNCURRENCY.'</th>
                                    <th align="center" colspan="3">'.DOMESTICCURRENCY.'</th>
                                </tr>
                                <tr>
                                    <th align="left">'.SRNO.'</th>
                                    <th align="left">'.CUSTDATE.'</th>
                                    <th align="left">'.DOCUMENTNUMBER.'</th>
                                    <th align="left">'.LOCATIONNAME.'</th>
                                    <th align="left">'.VCH.'</th>
                                    <th align="left">'.CONF.'</th>
                                    <th align="left">'.ISSUEDBY.'</th>
                                    <th align="left">'.PASSENGERNAME.'</th>
                                    <th align="left">'.TRAVELSETUPDETAILS.'</th>
                                    <th align="left">'.CURRNAME.'</th>
                                    <th align="left">'.DEBIT.'</th>
                                    <th align="left">'.CREDIT.'</th>
                                    <th align="left">'.BALANCE.'</th>
                                    <th align="left">'.DEBIT.'</th>
                                    <th align="left">'.CREDIT.'</th>
                                    <th align="left">'.BALANCE.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="16" align="centre">'.OPENINGBALANCE.'</th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->tblName)
                {
                    case "CR":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "CP":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BR":
                        $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BP":
                        $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "JV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "SJ":
                        $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$row->tableId."'");
                        $lpoVal      = $this->common_model->get_field_name('SalesClosingDetails','vDLPO'," WHERE nSalesCloseId='".$row->tableId."'");
                        $netVal      = $this->common_model->get_field_name('SalesClosingDetails','vNetAmt'," WHERE nSalesCloseId='".$row->tableId."'");
                        $serVal      = $this->common_model->get_field_name('SalesClosingDetails','vServAmt'," WHERE nSalesCloseId='".$row->tableId."'");
                        $PerVal      = $this->common_model->get_field_name('SalesClosingDetails','vServPercent'," WHERE nSalesCloseId='".$row->tableId."'");

                        $getTktValue  = $this->sub_report_model->getTktNumberValues($row->detId,$row->tableId);
                        $description = $getTktValue['desc'];
                        $ticketNum = $getTktValue['tktVal'];
                        $passenger = $getTktValue['passVal'];
                        break;

                    case "CV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "DV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ACM":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ADM":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;
                }//end switch

                switch($row->mode)
                {
                    case "Dr":
                        $debit = $row->debit;
                        $credit = 0;
                        break;

                    case "Cr":
                        $debit = 0;
                        $credit = $row->debit;
                        break;

                    default:
                        $debit = ($row->debit==' ')?0:$row->debit;
                        $credit = ($row->credit==' ')?0:$row->credit;
                        break;
                }
                $balance = $debit-$credit;
                if($balance < 0):
                    $balance = substr($balance,1,strlen($balance));
                endif;

                $srNo = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.date("d-M-y", strtotime($row->date)).'</td>
                        <td>'.$row->document.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'")).'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>'.$passenger.'</td>
                        <td>'.$description.'</td>
                        <td></td>
                        <td>'.$debit.'</td>
                        <td>'.$credit.'</td>
                        <td>'.$balance.'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
                $totalDebit   += $debit;
                $totalCredit  += $credit;
                $totalBalance  = $balance;
            }//end for each
            $pdfBody .= '
                    <tr>
                        <td colspan="10" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>'.$totalBalance.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="10" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>'.$totalBalance.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), FCDCREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'fcdc_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function fcdc_detail_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr>
                                    <th align="left">'.ACCODE.'</th>
                                    <th align="left">'.ACCCODENAME.'</th>
                                    <th align="left">'.SUBCODE.'</th>
                                    <th align="left">'.SUBNAME.'</th>
                                    <th align="left" colspan="9">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left">&nbsp;</th>
                                    <th align="left" colspan="9">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th align="left">'.SRNO.'</th>
                                    <th align="left">'.CUSTDATE.'</th>
                                    <th align="left">'.DOCUMENTNUMBER.'</th>
                                    <th align="left">'.LOCATIONNAME.'</th>
                                    <th align="left">'.VCH.'</th>
                                    <th align="left">'.CONF.'</th>
                                    <th align="left">'.ISSUEDBY.'</th>
                                    <th align="left">'.PASSENGERNAME.'</th>
                                    <th align="left">'.TRAVELDETAILS.'</th>
                                    <th align="left">'.CURRNAME.'</th>
                                    <th align="left">'.DEBIT.'</th>
                                    <th align="left">'.CREDIT.'</th>
                                    <th align="left">'.BALANCE.'</th>
                                </tr>
                                <tr>
                                    <th colspan="13" align="center">'.OPENINGBALANCE.'</th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->tblName)
                {
                    case "CR":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "CP":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BR":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BP":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "JV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "SJ":
                        $getTktValue  = $this->sub_report_model->getTktNumberValues($row->detId,$row->tableId);
                        $description = $getTktValue['desc'];
                        $ticketNum = $getTktValue['tktVal'];
                        $passenger = $getTktValue['passVal'];
                        break;

                    case "CV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "DV":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ACM":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ADM":
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;
                }//end switch

                switch($row->mode)
                {
                    case "Dr":
                        $debit = $row->debit;
                        $credit = 0;
                        break;

                    case "Cr":
                        $debit = 0;
                        $credit = $row->debit;
                        break;

                    default:
                        $debit = ($row->debit==' ')?0:$row->debit;
                        $credit = ($row->credit==' ')?0:$row->credit;
                        break;
                }
                $balance = $debit-$credit;
                if($balance < 0):
                    $balance = substr($balance,1,strlen($balance));
                endif;

                $srNo = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.date("d-M-y", strtotime($row->date)).'</td>
                        <td>'.$row->document.'</td>
                        <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId='".$row->location."'").'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>'.$passenger.'</td>
                        <td>'.$description.'</td>
                        <td>&nbsp;</td>
                        <td>'.$debit.'</td>
                        <td>'.$credit.'</td>
                        <td>'.$balance.'</td>
                    </tr>';
                $totalDebit   += $debit;
                $totalCredit  += $credit;
                $totalBalance  = $balance;
            }//end for each
            $pdfBody .= '
                    <tr>
                        <td colspan="10" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>'.$totalBalance.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="10" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>'.$totalDebit.'</td>
                        <td>'.$totalCredit.'</td>
                        <td>'.$totalBalance.'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if

        if(empty($pdfBody))
            $pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), FCDCREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'fcdc_detail_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function sub_agent_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th>'.ACCODE.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SUBCODE.'</th>
                                    <th>'.SUBNAME.'</th>
                                    <th colspan="8">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th colspan="8">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.SRNO.'</th>
                                    <th>'.CUSTDATE.'</th>
                                    <th>'.DOCUMENTNUMBER.'</th>
                                    <th>'.LOCATIONNAME.'</th>
                                    <th>'.TICKETNAME.'</th>
                                    <th>'.ISSUEDBY.'</th>
                                    <th>'.PASSENGERNAME.'</th>
                                    <th>'.TRAVELSETUPDETAILS.'</th>
                                    <th>'.LPO.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                    <th>'.BALANCE.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="12" align="centre">'.OPENINGBALANCE.'</th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                $srNo = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.date("d-M-y", strtotime($row->date)).'</td>
                        <td>'.$row->document.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'")).'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
            }//end for each
            $pdfBody .= '
                    <tr>
                        <td colspan="9" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="9" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SUBAGENTREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'sub_agent_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function agent_detail_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#ffffff">
							<thead>
								<tr bgcolor="#ffffff">
                                    <th>'.ACCODE.'</th>
                                    <th>'.ACCCODENAME.'</th>
                                    <th>'.SUBCODE.'</th>
                                    <th>'.SUBNAME.'</th>
                                    <th colspan="15">&nbsp;</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th colspan="4">&nbsp;</th>
                                    <th colspan="3">'.SALESDETAILS.'</th>
                                    <th colspan="6">'.NETDETAILS.'</th>
                                    <th colspan="2">'.MARGIN.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th>'.SRNO.'</th>
                                    <th>'.CUSTDATE.'</th>
                                    <th>'.DOCUMENTNUMBER.'</th>
                                    <th>'.LOCATIONNAME.'</th>
                                    <th>'.TICKETNAME.'</th>
                                    <th>'.PASSENGERNAME.'</th>
                                    <th>'.TRAVELSETUPDETAILS.'</th>
                                    <th>'.LPO.'</th>
                                    <th>'.DEBIT.'</th>
                                    <th>'.CREDIT.'</th>
                                    <th>'.BALANCE.'</th>
                                    <th>'.VENDORNAME.'</th>
                                    <th>'.NETAMT.'</th>
                                    <th>'.PAIDAMOUNT.'</th>
                                    <th>'.BALANCE.'</th>
                                    <th>'.PAIDDT.'</th>
                                    <th>'.PAID.'</th>
                                    <th>'.PROFIT.'</th>
                                    <th>'.PROFITPERCENTAGE.'</th>
                                </tr>
                                <tr bgcolor="#ffffff">
                                    <th colspan="19" align="centre">'.OPENINGBALANCE.'</th>
                                </tr>
							</thead>
							<tbody>';
            foreach ($sqlArray->result() as $key=>$row)
            {
                switch($row->tblName)
                {
                    case "CR":
                        $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "CP":
                        $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BR":
                        $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "BP":
                        $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "JV":
                        $path = base_url().'index.php/jv/editJv/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "SJ":
                        $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$row->tableId."'");
                        $lpoVal      = $this->common_model->get_field_name('SalesClosingDetails','vDLPO'," WHERE nSalesCloseId='".$row->tableId."'");
                        $netVal      = $this->common_model->get_field_name('SalesClosingDetails','vNetAmt'," WHERE nSalesCloseId='".$row->tableId."'");
                        $serVal      = $this->common_model->get_field_name('SalesClosingDetails','vServAmt'," WHERE nSalesCloseId='".$row->tableId."'");
                        $PerVal      = $this->common_model->get_field_name('SalesClosingDetails','vServPercent'," WHERE nSalesCloseId='".$row->tableId."'");

                        $path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$row->tableId.'/cl';

                        $getTktValue  = $this->sub_report_model->getTktNumberValues($row->detId,$row->tableId);
                        $description = $getTktValue['desc'];
                        $ticketNum = $getTktValue['tktVal'];
                        $passenger = $getTktValue['passVal'];
                        break;

                    case "CV":
                        $path = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "DV":
                        $path = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ACM":
                        $path = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;

                    case "ADM":
                        $path = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$row->tableId;
                        $description = $row->description;
                        $ticketNum   = '&nbsp';
                        break;
                }//end switch

                switch($row->mode)
                {
                    case "Dr":
                        $debit = $row->debit;
                        $credit = 0;
                        break;

                    case "Cr":
                        $debit = 0;
                        $credit = $row->debit;
                        break;

                    default:
                        $debit = ($row->debit==' ')?0:$row->debit;
                        $credit = ($row->credit==' ')?0:$row->credit;
                        break;
                }
                $balance = $debit-$credit;
                if($balance < 0):
                    $balance = substr($balance,1,strlen($balance));
                endif;

                $srNo = $key+1;
                $pdfBody .= '
                    <tr bgcolor="#ffffff">
                        <td>'.$srNo.'</td>
                        <td>'.date("d-M-y", strtotime($row->date)).'</td>
                        <td>'.$row->document.'</td>
                        <td>'.stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'")).'</td>
                        <td>'.$ticketNum.'</td>
                        <td></td>
                        <td>'.$passenger.'</td>
                        <td>'.$description.'</td>
                        <td>'.$lpoVal.'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>'.$serVal.'</td>
                        <td>'.$PerVal.'</td>
                    </tr>';
            }//end for each
            $pdfBody .= '
                    <tr>
                        <td colspan="8" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), AGENTDETAILS, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'agent_detail_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function receivable_allocation_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr bgcolor="#ffffff">
                                                    <th align="left">'.ACCODE.'</th>
                                                    <th align="left">'.ACCCODENAME.'</th>
                                                    <th align="left">'.SUBCODE.'</th>
                                                    <th align="left">'.SUBNAME.'</th>
                                                    <th align="left" colspan="10" rowspan="2">&nbsp;</th>
                                                </tr>
                                                <tr>
                                                    <th align="left">&nbsp;</th>
                                                    <th align="left">&nbsp;</th>
                                                    <th align="left">&nbsp;</th>
                                                    <th align="left">&nbsp;</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="6" align="center">'.RECEIVEDDETAILS.'</th>
                                                    <th colspan="8" align="center">'.ALLOCATEDDETAILS.'</th>
                                                </tr>
                                                <tr>
                                                    <th align="left">'.CUSTDATE.'</th>
                                                    <th align="left">'.LOCATIONNAME.'</th>
                                                    <th align="left">'.DOCUMENTNUMBER.'</th>
                                                    <th align="left">'.DESCRIPTION.'</th>
                                                    <th align="left">'.CREDITAMOUNT.'</th>
                                                    <th align="left">'.ALLOCATEDAMT.'</th>
                                                    <th align="left">'.SRNO.'</th>
                                                    <th align="left">'.CUSTDATE.'</th>
                                                    <th align="left">'.LOCATIONNAME.'</th>
                                                    <th align="left">'.TICKETNAME.'</th>
                                                    <th align="left">'.DEBITAMOUNT.'</th>
                                                    <th align="left">'.ALLOCATEDAMT.'</th>
                                                    <th align="left">'.BALANCEAMT.'</th>
                                                    <th align="left">'.DOCUMENTNUMBER.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '
                            <tr>
                                <td>'.date("d-M-y", strtotime($row->date)).'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.$row->document.'</td>
                                <td>'.$row->description.'</td>
                                <td>'.$row->credit.'</td>
                                <td>'.$row->allocated.'</td>
                                <td colspan="8">&nbsp;</td>
                            </tr>';
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}CashAllocation WHERE nReceiptDId='".$row->tableId."' AND vAllType='".$row->tblName."'");
                if($viewAccessList->num_rows() > 0)
                {
                    foreach ($viewAccessList->result() as $key1=>$allocItem):
                        $tktId   = $this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE nId = '".$allocItem->nSalesCloseDetId."'");
                        $tktType = $this->common_model->get_field_name('SalesClosingDetails','vType'," WHERE nId = '".$allocItem->nSalesCloseDetId."'");
                        switch($tktType)
                        {
                            case "Tkt" :
                                $tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$tktId."'");
                                break;

                            case "Xo" :
                                $tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$tktId."'");
                                break;

                            case "Htl" :
                                $tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$tktId."'");
                                break;
                        }//end switch
                        $num = $key1+1;
                        $pdfBody .= '<tr>
                            <td colspan="6">&nbsp;</td>
                            <td>'.$num.'</td>
                            <td>'.date("d-M-y", strtotime($allocItem->date)).'</td>
                            <td>'.$allocItem->vLocation.'</td>
                            <td>'.$tktNumber.'</td>
                            <td>'.$allocItem->vDebitAmt.'</td>
                            <td>'.$allocItem->vAllAmt.'</td>
                            <td>'.$allocItem->vBalAmt.'</td>
                            <td>'.$this->common_model->get_field_name('SalesClosing','vInvoiceNumber'," WHERE nSCId = '".$allocItem->nSalesCloseId."'").'</td>
                        </tr>';
                        $totalDebit = $totalDebit+$allocItem->vDebitAmt;
                        $totalAlloc = $totalAlloc+$allocItem->vAllAmt;
                        $totalBal   = $totalBal+$allocItem->vBalAmt;
                    endforeach;
                    $pdfBody .= '<tr>
                                                <td colspan="6"><strong>&nbsp;</strong></td>
                                                <td colspan="4" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                                                <td>'.$totalDebit.'</td>
                                                <td>'.$totalAlloc.'</td>
                                                <td>'.$totalBal.'</td>
                                                <td>&nbsp;</td>
                                            </tr>';
                }
                $credited += $row->credit;
                $allocated += $row->allocated;

                $GrandTotalDebit += $totalDebit;
                $GrandTotalAlloc += $totalAlloc;
                $GrandTotalBal   += $totalBal;
            }//end for each
            $pdfBody .= '<tr>
                                        <td colspan="4" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                                        <td>'.$credited.'</td>
                                        <td>'.$allocated.'</td>
                                        <td colspan="4">&nbsp;</td>
                                        <td>'.$GrandTotalDebit.'</td>
                                        <td>'.$GrandTotalAlloc.'</td>
                                        <td>'.$GrandTotalBal.'</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), RECEIVABLEALLOCATIONREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'receivable_allocation_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function payable_allocation_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr bgcolor="#ffffff">
                                                    <th align="left">'.ACCODE.'</th>
                                                    <th align="left">'.ACCCODENAME.'</th>
                                                    <th align="left">'.SUBCODE.'</th>
                                                    <th align="left">'.SUBNAME.'</th>
                                                    <th align="left" colspan="10" rowspan="2">&nbsp;</th>
                                                </tr>
                                                <tr>
                                                    <th align="left">&nbsp;</th>
                                                    <th align="left">&nbsp;</th>
                                                    <th align="left">&nbsp;</th>
                                                    <th align="left">&nbsp;</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="6" align="center">'.PAIDDETAILS.'</th>
                                                    <th colspan="8" align="center">'.ALLOCATEDDETAILS.'</th>
                                                </tr>
                                                <tr>
                                                    <th align="left">'.CUSTDATE.'</th>
                                                    <th align="left">'.LOCATIONNAME.'</th>
                                                    <th align="left">'.DOCUMENTNUMBER.'</th>
                                                    <th align="left">'.DESCRIPTION.'</th>
                                                    <th align="left">'.DEBITAMOUNT.'</th>
                                                    <th align="left">'.ALLOCATEDAMT.'</th>
                                                    <th align="left">'.SRNO.'</th>
                                                    <th align="left">'.CUSTDATE.'</th>
                                                    <th align="left">'.LOCATIONNAME.'</th>
                                                    <th align="left">'.TICKETNAME.'</th>
                                                    <th align="left">'.CREDITAMOUNT.'</th>
                                                    <th align="left">'.ALLOCATEDAMT.'</th>
                                                    <th align="left">'.BALANCEAMT.'</th>
                                                    <th align="left">'.DOCUMENTNUMBER.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '
                            <tr>
                                <td>'.date("d-M-y", strtotime($row->date)).'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.$row->document.'</td>
                                <td>'.$row->description.'</td>
                                <td>'.$row->credit.'</td>
                                <td>'.$row->allocated.'</td>
                                <td colspan="8">&nbsp;</td>
                            </tr>';
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}CashAllocation WHERE nReceiptDId='".$row->tableId."' AND vAllType='".$row->tblName."'");
                if($viewAccessList->num_rows() > 0)
                {
                    foreach ($viewAccessList->result() as $key1=>$allocItem):
                        $tktId   = $this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE nId = '".$allocItem->nSalesCloseDetId."'");
                        $tktType = $this->common_model->get_field_name('SalesClosingDetails','vType'," WHERE nId = '".$allocItem->nSalesCloseDetId."'");
                        switch($tktType)
                        {
                            case "Tkt" :
                                $tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$tktId."'");
                                break;

                            case "Xo" :
                                $tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$tktId."'");
                                break;

                            case "Htl" :
                                $tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$tktId."'");
                                break;
                        }//end switch
                        $num = $key1+1;
                        $pdfBody .= '<tr>
                            <td colspan="6">&nbsp;</td>
                            <td>'.$num.'</td>
                            <td>'.date("d-M-y", strtotime($allocItem->date)).'</td>
                            <td>'.$allocItem->vLocation.'</td>
                            <td>'.$tktNumber.'</td>
                            <td>'.$allocItem->vDebitAmt.'</td>
                            <td>'.$allocItem->vAllAmt.'</td>
                            <td>'.$allocItem->vBalAmt.'</td>
                            <td>'.$this->common_model->get_field_name('SalesClosing','vInvoiceNumber'," WHERE nSCId = '".$allocItem->nSalesCloseId."'").'</td>
                        </tr>';
                        $totalDebit = $totalDebit+$allocItem->vDebitAmt;
                        $totalAlloc = $totalAlloc+$allocItem->vAllAmt;
                        $totalBal   = $totalBal+$allocItem->vBalAmt;
                    endforeach;
                    $pdfBody .= '<tr>
                                                <td colspan="6"><strong>&nbsp;</strong></td>
                                                <td colspan="4" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                                                <td>'.$totalDebit.'</td>
                                                <td>'.$totalAlloc.'</td>
                                                <td>'.$totalBal.'</td>
                                                <td>&nbsp;</td>
                                            </tr>';
                }
                $credited += $row->credit;
                $allocated += $row->allocated;

                $GrandTotalDebit += $totalDebit;
                $GrandTotalAlloc += $totalAlloc;
                $GrandTotalBal   += $totalBal;
            }//end for each
            $pdfBody .= '<tr>
                                        <td colspan="4" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                                        <td>'.$credited.'</td>
                                        <td>'.$allocated.'</td>
                                        <td colspan="4">&nbsp;</td>
                                        <td>'.$GrandTotalDebit.'</td>
                                        <td>'.$GrandTotalAlloc.'</td>
                                        <td>'.$GrandTotalBal.'</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), PAYABLEALLOCATIONREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'payable_allocation_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function register_report()
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
                                                    <th>'.SRNO.'</th>
                                                    <th>'.LOCATIONNAME.'</th>
                                                    <th>'.CUSTDATE.'</th>
                                                    <th>'.DOCUMENTNUMBER.'</th>
                                                    <th>'.SUBCODE.'</th>
                                                    <th>'.SUBNAME.'</th>
                                                    <th>'.REFNUMBER.'</th>
                                                    <th>'.REFDATE.'</th>
                                                    <th>'.CURRNAME.'</th>
                                                    <th>'.AMOUNTFC.'</th>
                                                    <th>'.EXCHANGERATE.'</th>
                                                    <th>'.AMOUNTDC.'</th>
                                                    <th>'.REMARKS.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '
                            <tr>
                                <td>'.$srNo.'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.date("d-M-y", strtotime($row->date)).'</td>
                                <td>'.$row->document.'</td>
                                <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row->subacc."'").'</td>
                                <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->subacc."'").'</td>
                                <td>'.$row->vRefNumber.'</td>
                                <td>'.date("d-M-y", strtotime($row->dRefDate)).'</td>
                                <td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'").'</td>
                                <td>'.$row->amountFC.'</td>
                                <td>'.$row->vRate.'</td>
                                <td>'.$row->amountDC.'</td>
                                <td>'.$row->description.'</td>
                            </tr>';
                $totalFC += $row->amountFC;
                $totalDC += $row->amountDC;
            }//end for each
            $pdfBody .= '<tr>
                            <td colspan="9" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                            <td>'.$totalFC.'</td>
                            <td>&nbsp;</td>
                            <td>'.$totalDC.'</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="9" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                     </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), REGISTERREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'register_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function transaction_report()
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
                                                    <th>'.SRNO.'</th>
                                                    <th>'.LOCATIONNAME.'</th>
                                                    <th>'.CUSTDATE.'</th>
                                                    <th>'.DOCUMENTNUMBER.'</th>
                                                    <th>'.ACCODE.'</th>
                                                    <th>'.ACCCODENAME.'</th>
                                                    <th>'.SUBCODE.'</th>
                                                    <th>'.SUBNAME.'</th>
                                                    <th>'.REFNUMBER.'</th>
                                                    <th>'.REFDATE.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
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

                    default:
                        $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row->account."'");
                        $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->account."'");
                        break;
                }

                $showCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row->subacc."'");
                $showName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->subacc."'");

                $srNo = $key+1;
                $pdfBody .= '
                            <tr>
                                <td>'.$srNo.'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.date("d-M-y", strtotime($row->date)).'</td>
                                <td>'.$row->document.'</td>
                                <td>'.$SubAccountCode.'</td>
                                <td>'.$SubAccountName.'</td>
                                <td>'.$showCode.'</td>
                                <td>'.$showName.'</td>
                                <td>'.$row->vRefNumber.'</td>
                                <td>'.date("d-M-y", strtotime($row->dRefDate)).'</td>
                                <td>'.$row->debit.'</td>
                                <td>'.$row->credit.'</td>
                            </tr>';
                $getDetails = $this->cash_bank_report_model->getDetailValues($row->type,$row->tableId);
                if(!empty($getDetails)):
                    foreach($getDetails as $key2=>$row2):
                        switch($row2->vTable)
                        {
                            case "C":
                                $SubAccountName2 = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row2->vACode."'");
                                $SubAccountCode2 = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$row2->vACode."'");
                                break;

                            case "V":
                                $SubAccountName2 = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row2->vACode."'");
                                $SubAccountCode2 = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$row2->vACode."'");
                                break;

                            case "A":
                                $SubAccountName2 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row2->vACode."'");
                                $SubAccountCode2 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row2->vACode."'");
                                break;

                            case "O":
                                $SubAccountName2 = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row2->vACode."'");
                                $SubAccountCode2 = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$row2->vACode."'");
                                break;

                            case "AG":
                                $SubAccountName2 = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row2->vACode."'");
                                $SubAccountCode2 = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$row2->vACode."'");
                                break;

                            default:
                                $SubAccountCode2 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row2->vACode."'");
                                $SubAccountName2 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row2->vACode."'");
                                break;
                        }

                        $showCode2 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row2->vAcSubCode."'");
                        $showName2 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row2->vAcSubCode."'");

                        $pdfBody .='<tr>
                            <td colspan="4"></td>
                            <td>'.$SubAccountCode2.'</td>
                            <td>'.$SubAccountName2.'</td>
                            <td>'.$showCode2.'</td>
                            <td>'.$showName2.'</td>
                            <td colspan="2"></td>
                            <td>'.$row2->debitVal.'</td>
                            <td>'.$row2->creditVal.'</td>
                        </tr>';

                        $totalDebit   += $row2->debitVal;
                        $totalCredit  += $row2->creditVal;
                    endforeach;
                endif;
                $totalDebit   += $row->debit;
                $totalCredit  += $row->credit;
            }//end for each
            $pdfBody .= '<tr>
                            <td colspan="10" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                            <td>'.$totalDebit.'</td>
                            <td>'.$totalCredit.'</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                            <td>'.$totalDebit.'</td>
                            <td>'.$totalCredit.'</td>
                        </tr>
                     </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), TRANSACTIONREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'transaction_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function transaction_summary_report()
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
                                                    <th>'.SRNO.'</th>
                                                    <th>'.LOCATIONNAME.'</th>
                                                    <th>'.CUSTDATE.'</th>
                                                    <th>'.DOCUMENTNUMBER.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '
                            <tr>
                                <td>'.$srNo.'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.date("d-M-y", strtotime($row->date)).'</td>
                                <td>'.$row->document.'</td>
                                <td>'.$row->debit.'</td>
                                <td>'.$row->credit.'</td>
                            </tr>';
                $totalFC += $row->debit;
                $totalDC += $row->credit;
            }//end for each
            $pdfBody .= '<tr>
                            <td colspan="4" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                            <td>'.$totalFC.'</td>
                            <td>'.$totalDC.'</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                            <td>'.$totalFC.'</td>
                            <td>'.$totalDC.'</td>
                        </tr>
                     </tbody></table>';
        }//end if

        if(empty($pdfBody))
            $pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), TRANSACTIONREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'transaction_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function cash_summary_report()
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
                                                    <th>'.SRNO.'</th>
                                                    <th>'.LOCATIONNAME.'</th>
                                                    <th>'.CUSTDATE.'</th>
                                                    <th>'.DOCUMENTNUMBER.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '
                            <tr>
                                <td>'.$srNo.'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.date("d-M-y", strtotime($row->date)).'</td>
                                <td>'.$row->document.'</td>
                                <td>'.$row->debit.'</td>
                                <td>'.$row->credit.'</td>
                            </tr>';
                $totalFC += $row->debit;
                $totalDC += $row->credit;
            }//end for each
            $pdfBody .= '<tr>
                            <td colspan="4" align="right"><strong>'.TOTALAMOUNT.'</strong></td>
                            <td>'.$totalFC.'</td>
                            <td>'.$totalDC.'</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                            <td>'.$totalFC.'</td>
                            <td>'.$totalDC.'</td>
                        </tr>
                     </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SUMMARYREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'cash_summary_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function actual_report()
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
                                                    <th colspan="4">&nbsp;</th>
                                                    <th colspan="3">'.DURING.'</th>
                                                    <th colspan="3">'.UPTO.'</th>
                                                </tr>
                                                <tr>
                                                    <th align="left">'.SRNO.'</th>
                                                    <th align="left">'.ACCODE.'</th>
                                                    <th align="left">'.ACCCODENAME.'</th>
                                                    <th align="left">'.LOCATIONNAME.'</th>
                                                    <th align="left">'.BUDGETED.'</th>
                                                    <th align="left">'.ACTUAL.'</th>
                                                    <th align="left">'.VARIANCE.'</th>
                                                    <th align="left">'.BUDGETED.'</th>
                                                    <th align="left">'.ACTUAL.'</th>
                                                    <th align="left">'.VARIANCE.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                switch($row->vMode)
                {
                    case "Dr":
                        $amount = $row->nAmount;
                        break;
                    case "Cr":
                        $amount = '-'.$row->nAmount;
                        break;
                }//end switch

                $srNo = $key+1;
                $pdfBody .= '
                            <tr>
                                <td>'.$srNo.'</td>
                                <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row->account."'").'</td>
                                <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->account."'").'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.$amount.'</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>';
                $total += $amount;
            }//end for each
            $pdfBody .= '<tr>
                            <td colspan="4" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                            <td>'.$total.'</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                     </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ACTUALBUDGETREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'actual_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function budget_report()
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
                                                    <th align="left">'.SRNO.'</th>
                                                    <th align="left">'.ACCODE.'</th>
                                                    <th align="left">'.ACCCODENAME.'</th>
                                                    <th align="left">'.LOCATIONNAME.'</th>
                                                    <th align="left">'.AMOUNT.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                switch($row->vMode)
                {
                    case "Dr":
                        $amount = $row->nAmount;
                        break;
                    case "Cr":
                        $amount = '-'.$row->nAmount;
                        break;
                }//end switch

                $srNo = $key+1;
                $pdfBody .= '
                            <tr>
                                <td>'.$srNo.'</td>
                                <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row->account."'").'</td>
                                <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->account."'").'</td>
                                <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$row->location."'").'</td>
                                <td>'.$amount.'</td>
                            </tr>';
                $total += $amount;
            }//end for each
            $pdfBody .= '<tr>
                            <td colspan="4" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                            <td>'.$total.'</td>
                        </tr>
                     </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), BUDGETREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'budget_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function open_balance_report()
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
                                                    <th>'.SRNO.'</th>
                                                    <th>'.ACCODE.'</th>
                                                    <th>'.ACCCODENAME.'</th>
                                                    <th>'.SUBCODE.'</th>
                                                    <th>'.SUBNAME.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '
                            <tr>
                                <td>'.$srNo.'</td>
                                <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row->account."'").'</td>
                                <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->account."'").'</td>
                                <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row->subacc."'").'</td>
                                <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->subacc."'").'</td>
                                <td>'.$row->debit.'</td>
                                <td>'.$row->credit.'</td>
                            </tr>';
                $totalDebit  += $row->debit;
                $totalCredit += $row->credit;
            }//end for each
            $pdfBody .= '<tr>
                            <td colspan="5" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                            <td>'.$totalDebit.'</td>
                            <td>'.$totalCredit.'</td>
                        </tr>
                     </tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), OPENINGBALANCE, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'open_balance_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function account_group_report()
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
                                    <th>'.ACCGROUPNAME.'</th>
                                    <th>'.OPENING.'</th>
                                    <th>'.DURING.'</th>
                                    <th>'.YEARTODATE.'</th>
                                </tr>
                            </thead>
                            <tbody>';
            $j = 0;
            $flag = false;
            $chkGroupId = $chkHeadId = $chkAccountId = array();
            $totalPreviousLoop = $totalCurrentLoop = $totalPrevious = $totalCurrent = $totalOpenDebit = $totalOpenCredit = '';
            foreach ($sqlArray->result() as $key => $row)
            {
                $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$row->mainId."'");
                if($mainId=='3'):
                    $mainName = 'Income';
                elseif($mainId=='4'):
                    $mainName = 'Expense';
                endif;

                if(!in_array($mainId,$chkGroupId)):
                    if($j != 0):
                        $pdfBody .= '<tr>
                            <td align="right"><strong>'.GROUPTOTAL.'</strong></td>
                            <td><strong>'.$totalPreviousLoop.'</strong></td>
                            <td><strong>'.$totalCurrentLoop.'</strong></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right">&nbsp;</td>
                        </tr>';
                        $flag = true;
                    endif;
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$mainName.'</strong></td>
                        <td colspan="3" align="center">&nbsp;</td>
                    </tr>';
                    $j = 1;
                endif;
                if(!in_array($row->HeadId,$chkHeadId)):
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$row->HeadName.'</strong></td>
                        <td colspan="3" align="center">&nbsp;</td>
                    </tr>';
                endif;

                if(!in_array($row->AccountId,$chkAccountId)):
                    $pdfBody .= '<tr>';

                    $getValues          = $this->profloss_report_model->getAccValues($row->AccountId);
                    $getOpenValues      = $this->profloss_report_model->getOpenAccValues($row->AccountId);

                    $pdfBody .= '
                            <td>'.$row->AccountName.'</td>
                            <td>'.$getOpenValues['openDebit'].'</td>
                            <td>'.$getValues['debit'].'</td>
                            <td>&nbsp;</td>
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
                if(($key+1) == count($sqlArray->result())):
                    $pdfBody .= '
                    <tr>
                        <td align="right"><strong>'.GROUPTOTAL.'</strong></td>
                        <td><strong>'.$totalPreviousLoop.'</strong></td>
                        <td><strong>'.$totalCurrentLoop.'</strong></td>
                        <td>&nbsp;</td>
                    </tr>';
                endif;
            }//end for each
            $pdfBody .= '<tr>
                        <td align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td><strong>'.$totalPrevious.'</strong></td>
                        <td><strong>'.$totalCurrent.'</strong></td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if
        else
        {
            $pdfBody = 'Sorry, no records found';
        }//end else

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), PLACCOUNTGROUPREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'account_group_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function account_head_report()
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
                                    <th>'.ACCGROUPNAME.'</th>
                                    <th>'.OPENING.'</th>
                                    <th>'.DURING.'</th>
                                    <th>'.YEARTODATE.'</th>
                                </tr>
                            </thead>
                            <tbody>';
            $j = 0;
            $flag = false;
            $chkGroupId = $chkHeadId = $chkAccountId = array();
            $totalPreviousLoop = $totalCurrentLoop = $totalPrevious = $totalCurrent = $totalOpenDebit = $totalOpenCredit = '';
            foreach ($sqlArray->result() as $key => $row)
            {
                $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$row->mainId."'");
                if($mainId=='3'):
                    $mainName = 'Income';
                elseif($mainId=='4'):
                    $mainName = 'Expense';
                endif;

                if(!in_array($mainId,$chkGroupId)):
                    if($j != 0):
                        $pdfBody .= '<tr>
                            <td align="right"><strong>'.GROUPTOTAL.'</strong></td>
                            <td><strong>'.$totalPreviousLoop.'</strong></td>
                            <td><strong>'.$totalCurrentLoop.'</strong></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right">&nbsp;</td>
                        </tr>';
                        $flag = true;
                    endif;
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$mainName.'</strong></td>
                        <td colspan="3" align="center">&nbsp;</td>
                    </tr>';
                    $j = 1;
                endif;

                if(!in_array($row->HeadId,$chkHeadId)):
                    $pdfBody .= '<tr>';

                    $getValues          = $this->profloss_report_model->getHeadAccValues($row->HeadId);
                    $getOpenValues      = $this->profloss_report_model->getOpenHeadAccValues($row->HeadId);

                    $pdfBody .= '
                            <td>'.$row->HeadName.'</td>
                            <td>'.$getOpenValues['openDebit'].'</td>
                            <td>'.$getValues['debit'].'</td>
                            <td>&nbsp;</td>
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
                if(($key+1) == count($sqlArray->result())):
                    $pdfBody .= '
                    <tr>
                        <td align="right"><strong>'.GROUPTOTAL.'</strong></td>
                        <td><strong>'.$totalPreviousLoop.'</strong></td>
                        <td><strong>'.$totalCurrentLoop.'</strong></td>
                        <td>&nbsp;</td>
                    </tr>';
                endif;
            }//end for each
            $pdfBody .= '<tr>
                        <td align="right"><strong>'.GRANDTOTAL.'</strong></td>
                        <td><strong>'.$totalPrevious.'</strong></td>
                        <td><strong>'.$totalCurrent.'</strong></td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody></table>';
        }//end if
        else
        {
            $pdfBody = 'Sorry, no records found';
        }//end else

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), PLACCOUNTHEADREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'account_head_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function monthly_profit_report()
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
            $chkGroupId = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                if(!in_array($row->aid,$chkGroupId)):
                    $pdfBody .= '<tr>
                    <td><strong>'.$row->ahead.'</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>';
                endif;
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
                $chkGroupId[] = $row->aid;
            }//end for each
            $pdfBody .= '
                     </tbody></table>';
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
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

	function system_jv()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $debitTotal = $creditTotal = 0;$chkSubCodeArray = array('82','72','91');
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                <th align="left">#</th>
                                                <th align="left">'.DIVISIONNAME.'</th>
                                                <th align="left">'.LOCATIONNAME.'</th>
                                                <th align="left">'.JVNUMBER.'</th>
                                                <th align="left">'.INVOICE.'</th>
                                                <th align="left">'.CUSTDATE.'</th>
                                                <th align="left">'.DEBIT.'</th>
                                                <th align="left">'.CREDIT.'</th>
                                            </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;

                switch($row->vType)
                {
                    case "Tkt":
                        $sType = 'Ticket';
                        break;

                    case "Xo":
                        $sType = 'Xo';
                        break;

                    case "Htl":
                        $sType = 'Hotel';
                        break;
                }//end switch

                $pdfBody .= '
                            <tr>
                                <td>'.$srNo.'</td>
                                <td align="left">'.stripslashes($row->dName).'</td>
                                <td align="left">'.stripslashes($row->lName).'</td>
                                <td align="left">'.stripslashes($row->vJVNo).'</td>
                                <td align="left">'.stripslashes($row->nInvoice).'</td>
                                <td align="left">'.stripslashes($row->dDate).'</td>
                                <td align="left">0</td>
                                <td align="left">0</td>
                            </tr>';
                $viewAccessList  = $this->db->query("SELECT sj.*,sjd.vType,sjd.vDes,sjd.nDebit,sjd.nCredit,sjd.vNumber,
											aha.vTitle as accName,aha.vACode as accCode,
										sad.vTable,sad.nDrAccount,l.vTitle as loc,d.vTitle as dName,
										l.vTitle as lName,sjd.nAccHeadId,
										CASE
											WHEN sad.vTable = 'c' THEN
										 		(SELECT vName FROM {PRE}CustomerMaster WHERE
										 			nCId = sad.nDrAccount)
											ELSE
												(SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE
										 			nId = sad.nDrAccount)
										END as subCode,
										CASE
											WHEN sjd.vType = 'Tkt' THEN
										 		(SELECT vTicktManual FROM {PRE}TicketBooking WHERE
										 			nTId = sjd.vNumber)
											WHEN sjd.vType = 'Htl' THEN
										 		(SELECT vTicktManual FROM {PRE}HotelVoucher WHERE
										 			nHVId = sjd.vNumber)
											ELSE
												(SELECT vTicktManual FROM {PRE}XoBooking WHERE
										 			nXId = sjd.vNumber)
										END as tNumber
										 FROM {PRE}SystemJv sj
									LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId
										LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = sjd.nAccHeadId
									LEFT JOIN {PRE}AccountHead ah ON ah.nAId = sjd.nAGId
										LEFT JOIN {PRE}SalesAccountDetails sad
								   ON sad.nSalesCloseId  = sj.nSalesCId
								   		LEFT JOIN {PRE}Division d ON d.nId = sj.nDivision
									LEFT JOIN {PRE}Location l ON l.nId = sj.nLocation
		                          			 WHERE sj.nId = '".$row->nId."' ORDER BY sj.nId DESC");
                if($viewAccessList->num_rows() > 0)
                {
                    $pdfBody .= '
                                <tr>
                                    <th align="left">&nbsp;</th>
									<th align="left">'.LOCATIONNAME.'</th>
									<th align="left">'.ACCODE.'</th>
									<th align="left">'.SUBCODE.'</th>
									<th align="left">'.REFNUMBER.'</th>
									<th align="left">'.DESCRIPTION.'</th>
									<th align="left">'.DEBIT.'</th>
									<th align="left">'.CREDIT.'</th>
								</tr>';
                    foreach ($viewAccessList->result() as $key1=>$details):
                        switch($details->vType)
                        {
                            case "Tkt":
                                $sType = 'Ticket';
                                break;

                            case "Xo":
                                $sType = 'Xo';
                                break;

                            case "Htl":
                                $sType = 'Hotel';
                                break;
                        }//end swithc


                        $showTktNo       = ($details->vNumber != 0)?($sType.'-'.$details->tNumber):'';


                        if($details->vNumber != 0):
                            $showSubCode = '';
                        else:
                            if(in_array($details->nAccHeadId,$chkSubCodeArray)):
                                $acCode      = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId = '".$details->nAccHeadId."'");
                                $showSubCode = $this->common_model->get_field_name('AccountHead','vAcname'," WHERE nAId = '".$acCode."'");
                            else:
                                $showSubCode = $details->subCode;
                            endif;
                        endif;

                        $debitTotal      += $details->nDebit;
                        $creditTotal     += $details->nCredit;

                        $pdfBody .= '<tr>
                                    <td>&nbsp;</td>
									<td align="left">'.$this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$details->nLocation."'").'</td>
                                    <td align="left">'.stripslashes($details->accName).'</td>
                                    <td align="left">'.$showSubCode.'</td>
                                    <td align="left">'.$showTktNo.'</td>
                                    <td align="left">'.stripslashes($details->vDes).'</td>
                                    <td align="left">'.stripslashes($details->nDebit).'</td>
                                    <td align="left">'.stripslashes($details->nCredit).'</td>
								</tr>';
                    endforeach;

                    $pdfBody .= '<tr>
                                <td align="right" colspan="6"><strong>'.TOTALAMOUNT.'</strong></td>
                                <td align="left">'.$debitTotal.'</td>
                                <td align="left">'.$creditTotal.'</td>
							</tr>';
                }//end if
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SYSTEMJVMANAGEMENT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'system_jv';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A4');
    }//end function

    function account_group_trial()
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
            $totalOpenDebitLoop = $totalOpenCreditLoop = $totalDebitLoop = $totalCreditLoop = $totalBalanceDebitLoop = $totalBalanceCreditLoop = '';
            $totalOpenDebit = $totalOpenCredit = $totalDebit = $totalCredit = $totalBalanceDebit = $totalBalanceCredit = '';
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>&nbsp;</th>
                                                    <th colspan="2">'.OPENING.'</th>
                                                    <th colspan="2">'.DURING.'</th>
                                                    <th colspan="2">'.BALANCE.'</th>
                                                </tr>
                                                <tr>
                                                    <th>'.ACCODE.'</th>
                                                    <th>'.ACCCODENAME.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkEmployee = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                if(!in_array($row->GroupId,$chkGroupId)):
                    if($j != 0):
                        $pdfBody .= '<tr>
                            <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                            <td><strong>'.$totalOpenDebitLoop.'</strong></td>
                            <td><strong>'.$totalOpenCreditLoop.'</strong></td>
                            <td><strong>'.$totalDebitLoop.'</strong></td>
                            <td><strong>'.$totalCreditLoop.'</strong></td>
                            <td><strong>'.$totalBalanceDebitLoop.'</strong></td>
                            <td><strong>'.$totalBalanceCreditLoop.'</strong></td>
                        </tr>
                        <tr>
                            <td colspan="8" align="right">&nbsp;</td>
                        </tr>';
                        $flag = true;
                    endif;
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$row->GroupCode.'</strong></td>
                        <td align="left"><strong>'.$row->GroupName.'</strong></td>
                        <td colspan="6" align="center">&nbsp;</td>
                    </tr>';
                    $j = 1;
                endif;
                if(!in_array($row->HeadId,$chkHeadId)):
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$row->HeadCode.'</strong></td>
                        <td align="left"><strong>'.$row->HeadName.'</strong></td>
                        <td colspan="6" align="center">&nbsp;</td>
                    </tr>';
                endif;

                if(!in_array($row->AccountId,$chkAccountId)):
                    $pdfBody .= '<tr>';
                    $getValues      = $this->trial_report_model->getAccValues($row->AccountId);
                    $getOpenValues  = $this->trial_report_model->getOpenAccValues($row->AccountId);
                    $getBalance     = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);

                    if($getBalance > 0):
                        $balanceDebit = $getBalance;
                        $balanceCredit = '';
                    else:
                        $balanceDebit = '';
                        $balanceCredit = substr($getBalance,1,strlen($getBalance));
                    endif;

                    $pdfBody .= '<td>'.$row->AccountCode.'</td>
                            <td>'.$row->AccountName.'</td>
                            <td>'.$getOpenValues['openDebit'].'</td>
                            <td>'.$getOpenValues['openCredit'].'</td>
                            <td>'.$getValues['debit'].'</td>
                            <td>'.$getValues['credit'].'</td>
                            <td>'.$balanceDebit.'</td>
                            <td>'.$balanceCredit.'</td>
                        </tr>';

                    $totalOpenDebit     += $getOpenValues['openDebit'];
                    $totalOpenCredit    += $getOpenValues['openCredit'];
                    $totalDebit         += $getValues['debit'];
                    $totalCredit        += $getValues['credit'];
                    $totalBalanceDebit  += $balanceDebit;
                    $totalBalanceCredit += $balanceCredit;

                    if($flag == true):
                        $totalOpenDebitLoop  = '';
                        $totalOpenCreditLoop = '';
                        $totalDebitLoop      = '';
                        $totalCreditLoop     = '';
                        $totalBalanceDebitLoop = '';
                        $totalBalanceCreditLoop = '';
                        $flag                = false;
                    endif;

                    $chkGroupId[]            = $row->GroupId;
                    $chkHeadId[]             = $row->HeadId;
                    $chkAccountId[]          = $row->AccountId;

                    $totalOpenDebitLoop     += $getOpenValues['openDebit'];
                    $totalOpenCreditLoop    += $getOpenValues['openCredit'];
                    $totalDebitLoop         += $getValues['debit'];
                    $totalCreditLoop        += $getValues['credit'];
                    $totalBalanceDebitLoop  += $balanceDebit;
                    $totalBalanceCreditLoop += $balanceCredit;
                endif;
                if(($key+1) == count($sqlArray->result())):
                    $pdfBody .= '<tr>
                        <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                        <td><strong>'.$totalOpenDebitLoop.'</strong></td>
                        <td><strong>'.$totalOpenCreditLoop.'</strong></td>
                        <td><strong>'.$totalDebitLoop.'</strong></td>
                        <td><strong>'.$totalCreditLoop.'</strong></td>
                        <td><strong>'.$totalBalanceDebitLoop.'</strong></td>
                        <td><strong>'.$totalBalanceCreditLoop.'</strong></td>
                    </tr>';
                endif;
            }//end foreach

            $pdfBody .= '<tr>
                <td colspan="2" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                <td><strong>'.$totalOpenDebit.'</strong></td>
                <td><strong>'.$totalOpenCredit.'</strong></td>
                <td><strong>'.$totalDebit.'</strong></td>
                <td><strong>'.$totalCredit.'</strong></td>
                <td><strong>'.$totalBalanceDebit.'</strong></td>
                <td><strong>'.$totalBalanceCredit.'</strong></td>
            </tr>
            </tbody></table>';
        }//end if

        else
        {
            $pdfBody = '<tr>
                <td colspan="8" align="center">'.SORRYNORECORD.'</td>
            </tr>';
        }

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ACCOUNTGROUP, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'account_group_trial';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function account_head_trial()
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
            $totalOpenDebitLoop = $totalOpenCreditLoop = $totalDebitLoop = $totalCreditLoop = $totalBalanceDebitLoop = $totalBalanceCreditLoop = '';
            $totalOpenDebit = $totalOpenCredit = $totalDebit = $totalCredit = $totalBalanceDebit = $totalBalanceCredit = '';
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>&nbsp;</th>
                                                    <th colspan="2">'.OPENING.'</th>
                                                    <th colspan="2">'.DURING.'</th>
                                                    <th colspan="2">'.BALANCE.'</th>
                                                </tr>
                                                <tr>
                                                    <th>'.ACCODE.'</th>
                                                    <th>'.ACCCODENAME.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkEmployee = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                if(!in_array($row->HeadId,$chkHeadId)):
                    if($j != 0):
                        $pdfBody .= '<tr>
                            <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                            <td><strong>'.$totalOpenDebitLoop.'</strong></td>
                            <td><strong>'.$totalOpenCreditLoop.'</strong></td>
                            <td><strong>'.$totalDebitLoop.'</strong></td>
                            <td><strong>'.$totalCreditLoop.'</strong></td>
                            <td><strong>'.$totalBalanceDebitLoop.'</strong></td>
                            <td><strong>'.$totalBalanceCreditLoop.'</strong></td>
                        </tr>
                        <tr>
                            <td colspan="8" align="right">&nbsp;</td>
                        </tr>';
                        $flag = true;
                    endif;
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$row->HeadCode.'</strong></td>
                        <td align="left"><strong>'.$row->HeadName.'</strong></td>
                        <td colspan="6" align="center">&nbsp;</td>
                    </tr>';
                    $j = 1;
                endif;

                if(!in_array($row->AccountId,$chkAccountId)):
                    $pdfBody .= '<tr>';
                    $getValues      = $this->trial_report_model->getAccValues($row->AccountId);
                    $getOpenValues  = $this->trial_report_model->getOpenAccValues($row->AccountId);
                    $getBalance     = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);

                    if($getBalance > 0):
                        $balanceDebit = $getBalance;
                        $balanceCredit = '';
                    else:
                        $balanceDebit = '';
                        $balanceCredit = substr($getBalance,1,strlen($getBalance));
                    endif;

                    $pdfBody .= '<td>'.$row->AccountCode.'</td>
                            <td>'.$row->AccountName.'</td>
                            <td>'.$getOpenValues['openDebit'].'</td>
                            <td>'.$getOpenValues['openCredit'].'</td>
                            <td>'.$getValues['debit'].'</td>
                            <td>'.$getValues['credit'].'</td>
                            <td>'.$balanceDebit.'</td>
                            <td>'.$balanceCredit.'</td>
                        </tr>';

                    $totalOpenDebit     += $getOpenValues['openDebit'];
                    $totalOpenCredit    += $getOpenValues['openCredit'];
                    $totalDebit         += $getValues['debit'];
                    $totalCredit        += $getValues['credit'];
                    $totalBalanceDebit  += $balanceDebit;
                    $totalBalanceCredit += $balanceCredit;

                    if($flag == true):
                        $totalOpenDebitLoop  = '';
                        $totalOpenCreditLoop = '';
                        $totalDebitLoop      = '';
                        $totalCreditLoop     = '';
                        $totalBalanceDebitLoop = '';
                        $totalBalanceCreditLoop = '';
                        $flag                = false;
                    endif;

                    $chkGroupId[]            = $row->GroupId;
                    $chkHeadId[]             = $row->HeadId;
                    $chkAccountId[]          = $row->AccountId;

                    $totalOpenDebitLoop     += $getOpenValues['openDebit'];
                    $totalOpenCreditLoop    += $getOpenValues['openCredit'];
                    $totalDebitLoop         += $getValues['debit'];
                    $totalCreditLoop        += $getValues['credit'];
                    $totalBalanceDebitLoop  += $balanceDebit;
                    $totalBalanceCreditLoop += $balanceCredit;
                endif;
                if(($key+1) == count($sqlArray->result())):
                    $pdfBody .= '<tr>
                        <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                        <td><strong>'.$totalOpenDebitLoop.'</strong></td>
                        <td><strong>'.$totalOpenCreditLoop.'</strong></td>
                        <td><strong>'.$totalDebitLoop.'</strong></td>
                        <td><strong>'.$totalCreditLoop.'</strong></td>
                        <td><strong>'.$totalBalanceDebitLoop.'</strong></td>
                        <td><strong>'.$totalBalanceCreditLoop.'</strong></td>
                    </tr>';
                endif;
            }//end foreach

            $pdfBody .= '<tr>
                <td colspan="2" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                <td><strong>'.$totalOpenDebit.'</strong></td>
                <td><strong>'.$totalOpenCredit.'</strong></td>
                <td><strong>'.$totalDebit.'</strong></td>
                <td><strong>'.$totalCredit.'</strong></td>
                <td><strong>'.$totalBalanceDebit.'</strong></td>
                <td><strong>'.$totalBalanceCredit.'</strong></td>
            </tr>
            </tbody></table>';
        }//end if

        else
        {
            $pdfBody = '<tr>
                <td colspan="8" align="center">'.SORRYNORECORD.'</td>
            </tr>';
        }

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ACCOUNTGROUP, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'account_head_trial';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function sub_group_trial()
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
            $totalOpenDebitLoop = $totalOpenCreditLoop = $totalDebitLoop = $totalCreditLoop = $totalBalanceDebitLoop = $totalBalanceCreditLoop = '';
            $totalOpenDebit = $totalOpenCredit = $totalDebit = $totalCredit = $totalBalanceDebit = $totalBalanceCredit = '';
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>&nbsp;</th>
                                                    <th colspan="2">'.OPENING.'</th>
                                                    <th colspan="2">'.DURING.'</th>
                                                    <th colspan="2">'.BALANCE.'</th>
                                                </tr>
                                                <tr>
                                                    <th>'.ACCODE.'</th>
                                                    <th>'.ACCCODENAME.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkEmployee = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                if(!in_array($row->GroupId,$chkGroupId)):
                    if($j != 0):
                        $pdfBody .= '<tr>
                            <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                            <td><strong>'.$totalOpenDebitLoop.'</strong></td>
                            <td><strong>'.$totalOpenCreditLoop.'</strong></td>
                            <td><strong>'.$totalDebitLoop.'</strong></td>
                            <td><strong>'.$totalCreditLoop.'</strong></td>
                            <td><strong>'.$totalBalanceDebitLoop.'</strong></td>
                            <td><strong>'.$totalBalanceCreditLoop.'</strong></td>
                        </tr>
                        <tr>
                            <td colspan="8" align="right">&nbsp;</td>
                        </tr>';
                        $flag = true;
                    endif;
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$row->GroupCode.'</strong></td>
                        <td align="left"><strong>'.$row->GroupName.'</strong></td>
                        <td colspan="6" align="center">&nbsp;</td>
                    </tr>';
                    $j = 1;
                endif;
                if(!in_array($row->HeadId,$chkHeadId)):
                    $pdfBody .= '<tr>';
                    $getValues      = $this->trial_report_model->getAccValuesSubGroup($row->HeadId);
                    $getOpenValues  = $this->trial_report_model->getOpenAccValuesSubGroup($row->HeadId);
                    $getBalance     = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);

                    if($getBalance > 0):
                        $balanceDebit = $getBalance;
                        $balanceCredit = '';
                    else:
                        $balanceDebit = '';
                        $balanceCredit = substr($getBalance,1,strlen($getBalance));
                    endif;

                    $pdfBody .= '<td>'.$row->HeadCode.'</td>
                            <td>'.$row->HeadName.'</td>
                            <td>'.$getOpenValues['openDebit'].'</td>
                            <td>'.$getOpenValues['openCredit'].'</td>
                            <td>'.$getValues['debit'].'</td>
                            <td>'.$getValues['credit'].'</td>
                            <td>'.$balanceDebit.'</td>
                            <td>'.$balanceCredit.'</td>
                        </tr>';

                    $totalOpenDebit     += $getOpenValues['openDebit'];
                    $totalOpenCredit    += $getOpenValues['openCredit'];
                    $totalDebit         += $getValues['debit'];
                    $totalCredit        += $getValues['credit'];
                    $totalBalanceDebit  += $balanceDebit;
                    $totalBalanceCredit += $balanceCredit;

                    if($flag == true):
                        $totalOpenDebitLoop  = '';
                        $totalOpenCreditLoop = '';
                        $totalDebitLoop      = '';
                        $totalCreditLoop     = '';
                        $totalBalanceDebitLoop = '';
                        $totalBalanceCreditLoop = '';
                        $flag                = false;
                    endif;

                    $chkGroupId[]            = $row->GroupId;
                    $chkHeadId[]             = $row->HeadId;
                    $chkAccountId[]          = $row->AccountId;

                    $totalOpenDebitLoop     += $getOpenValues['openDebit'];
                    $totalOpenCreditLoop    += $getOpenValues['openCredit'];
                    $totalDebitLoop         += $getValues['debit'];
                    $totalCreditLoop        += $getValues['credit'];
                    $totalBalanceDebitLoop  += $balanceDebit;
                    $totalBalanceCreditLoop += $balanceCredit;
                endif;
                if(($key+1) == count($sqlArray->result())):
                    $pdfBody .= '<tr>
                        <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                        <td><strong>'.$totalOpenDebitLoop.'</strong></td>
                        <td><strong>'.$totalOpenCreditLoop.'</strong></td>
                        <td><strong>'.$totalDebitLoop.'</strong></td>
                        <td><strong>'.$totalCreditLoop.'</strong></td>
                        <td><strong>'.$totalBalanceDebitLoop.'</strong></td>
                        <td><strong>'.$totalBalanceCreditLoop.'</strong></td>
                    </tr>';
                endif;
            }//end foreach

            $pdfBody .= '<tr>
                <td colspan="2" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                <td><strong>'.$totalOpenDebit.'</strong></td>
                <td><strong>'.$totalOpenCredit.'</strong></td>
                <td><strong>'.$totalDebit.'</strong></td>
                <td><strong>'.$totalCredit.'</strong></td>
                <td><strong>'.$totalBalanceDebit.'</strong></td>
                <td><strong>'.$totalBalanceCredit.'</strong></td>
            </tr>
            </tbody></table>';
        }//end if

        else
        {
            $pdfBody = '<tr>
                <td colspan="8" align="center">'.SORRYNORECORD.'</td>
            </tr>';
        }

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ACCOUNTGROUP, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'account_group_trial';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function sub_account_trial()
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
            $totalOpenDebitLoop = $totalOpenCreditLoop = $totalDebitLoop = $totalCreditLoop = $totalBalanceDebitLoop = $totalBalanceCreditLoop = '';
            $totalOpenDebit = $totalOpenCredit = $totalDebit = $totalCredit = $totalBalanceDebit = $totalBalanceCredit = '';
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>&nbsp;</th>
                                                    <th colspan="2">'.OPENING.'</th>
                                                    <th colspan="2">'.DURING.'</th>
                                                    <th colspan="2">'.BALANCE.'</th>
                                                </tr>
                                                <tr>
                                                    <th>'.ACCODE.'</th>
                                                    <th>'.ACCCODENAME.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                    <th>'.DEBIT.'</th>
                                                    <th>'.CREDIT.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkEmployee = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                if(!in_array($row->HeadId,$chkHeadId)):
                    if($j != 0):
                        $pdfBody .= '<tr>
                            <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                            <td><strong>'.$totalOpenDebitLoop.'</strong></td>
                            <td><strong>'.$totalOpenCreditLoop.'</strong></td>
                            <td><strong>'.$totalDebitLoop.'</strong></td>
                            <td><strong>'.$totalCreditLoop.'</strong></td>
                            <td><strong>'.$totalBalanceDebitLoop.'</strong></td>
                            <td><strong>'.$totalBalanceCreditLoop.'</strong></td>
                        </tr>
                        <tr>
                            <td colspan="8" align="right">&nbsp;</td>
                        </tr>';
                        $flag = true;
                    endif;
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$row->HeadCode.'</strong></td>
                        <td align="left"><strong>'.$row->HeadName.'</strong></td>
                        <td colspan="6" align="center">&nbsp;</td>
                    </tr>';
                    $j = 1;
                endif;

                if(!in_array($row->AccountId,$chkAccountId)):

                    $getSubValues   = $this->trial_report_model->getAllSubAccValues($row->AccountId);
                    $getOpenValues  = $this->trial_report_model->getAllSubOpenValues($row->AccountId);

                    if(!empty($getSubValues)):

                        $chkDebit   = $getSubValues['debit'];
                        $chkCredit  = $getSubValues['credit'];
                        $chkSubId 	= $getSubValues['sub'];
                        $chkTable 	= $getSubValues['table'];
                        $openCredit = $getOpenValues['openCredit'];
                        $openDebit 	= $getOpenValues['openDebit'];
                        $tnumber    = $getSubValues['tnumber'];
                        $totalOpenDebit2     = 0;
                        $totalOpenCredit2    = 0;
                        $totalDebit2         = 0;
                        $totalCredit2        = 0;
                        $totalBalanceDebit2  = 0;
                        $totalBalanceCredit2 = 0;

                        foreach($chkDebit as $key2 => $row2):
                            $getBalance     = ($openDebit[$key2]-$openCredit[$key2])+($chkDebit[$key2]-$chkCredit[$key2]);
                            if($getBalance > 0):
                                $balanceDebit = $getBalance;
                                $balanceCredit = '';
                            else:
                                $balanceDebit = '';
                                $balanceCredit = substr($getBalance,1,strlen($getBalance));
                            endif;

                            if($chkSubId[$key2] !=0):
                                switch($chkTable[$key2])
                                {
                                    case "C":
                                        $SubAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$chkSubId[$key2]."'");
                                        $SubAccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$chkSubId[$key2]."'");
                                        break;

                                    case "V":
                                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$chkSubId[$key2]."'");
                                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$chkSubId[$key2]."'");
                                        break;

                                    case "A":
                                        $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$chkSubId[$key2]."'");
                                        $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$chkSubId[$key2]."'");
                                        break;

                                    case "O":
                                        $SubAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$chkSubId[$key2]."'");
                                        $SubAccountCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$chkSubId[$key2]."'");
                                        break;

                                    case "AG":
                                        $SubAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$chkSubId[$key2]."'");
                                        $SubAccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$chkSubId[$key2]."'");
                                        break;

                                    default:
                                        $SubAccountName = $row->AccountName;
                                        $SubAccountCode = $row->AccountCode;
                                        break;
                                }//end switch
                            else:
                                $SubAccountName = $row->AccountName;
                                $SubAccountCode = $row->AccountCode;
                            endif;

                            if($SubAccountName == 'BSP Payable'):
                                $getCode     = $this->common_model->get_field_name('TicketFareDetails','nAcc',
                                    " WHERE nTicketId  = '".$tnumber[$key2]."'");
                                $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName',
                                    " WHERE nVId  = '".$getCode."'");
                            elseif($SubAccountName == 'Tour Agent & Hotels Payable'):
                                $getCode     = $this->common_model->get_field_name('HotelVoucher','nAcCode',
                                    " WHERE nHVId  = '".$tnumber[$key2]."'");
                                $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName',
                                    " WHERE nVId  = '".$getCode."'");
                            elseif($SubAccountName == 'XO Payable'):
                                $getCode     = $this->common_model->get_field_name('XoBooking','nPassengerAC',
                                    " WHERE nXId = '".$tnumber[$key2]."'");
                                $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName',
                                    " WHERE nVId  = '".$getCode."'");
                            else:
                                $SubAccountName = $SubAccountName;
                            endif;

                            $totalOpenDebit2     += $openDebit[$key2];
                            $totalOpenCredit2    += $openCredit[$key2];
                            $totalDebit2         += $chkDebit[$key2];
                            $totalCredit2        += $chkCredit[$key2];
                            $totalBalanceDebit2  += $balanceDebit;
                            $totalBalanceCredit2 += $balanceCredit;

                            $ValSubAccountCode = ($chkName != $SubAccountName)?$SubAccountCode:$SubAccountCode;
                            $ValSubAccountName = ($chkName != $SubAccountName)?$SubAccountName:$SubAccountName;
                            $ValTotalOpenDebit2 = ($chkName != $SubAccountName)?$openDebit[$key2]:$totalOpenDebit2;
                            $ValTotalOpenCredit2 = ($chkName != $SubAccountName)?$openCredit[$key2]:$totalOpenCredit2;
                            $ValTotalDebit2 = ($chkName != $SubAccountName)?$chkDebit[$key2]:$totalDebit2;
                            $ValTotalCredit2 = ($chkName != $SubAccountName)?$chkCredit[$key2]:$totalCredit2;
                            $ValTotalBalanceDebit2 = ($chkName != $SubAccountName)?$balanceDebit:$totalBalanceDebit2;
                            $ValTotalBalanceCredit2 = ($chkName != $SubAccountName)?$balanceCredit:$totalBalanceCredit2;

                            $pdfBody .= '<tr>
                            <td>'.$ValSubAccountCode.'</td>
                            <td>'.$ValSubAccountName.'</td>
                            <td>'.$ValTotalOpenDebit2.'</td>
                            <td>'.$ValTotalOpenCredit2.'</td>
                            <td>'.$ValTotalDebit2.'</td>
                            <td>'.$ValTotalCredit2.'</td>
                            <td>'.$ValTotalBalanceDebit2.'</td>
                            <td>'.$ValTotalBalanceCredit2.'</td>
                        </tr>';
                            $totalOpenDebit     += $openDebit[$key2];
                            $totalOpenCredit    += $openCredit[$key2];
                            $totalDebit         += $chkDebit[$key2];
                            $totalCredit        += $chkCredit[$key2];
                            $totalBalanceDebit  += $balanceDebit;
                            $totalBalanceCredit += $balanceCredit;

                            $chkName = $SubAccountName;

                            if($flag == true):
                                $totalOpenDebitLoop  = '';
                                $totalOpenCreditLoop = '';
                                $totalDebitLoop      = '';
                                $totalCreditLoop     = '';
                                $totalBalanceDebitLoop = '';
                                $totalBalanceCreditLoop = '';
                                $flag                = false;
                            endif;

                            $chkGroupId[]            = $row->GroupId;
                            $chkHeadId[]             = $row->HeadId;
                            $chkAccountId[]          = $row->AccountId;
                            $chkSubAccount[]         = $SubAccountName;

                            $totalOpenDebitLoop     += $openDebit[$key2];
                            $totalOpenCreditLoop    += $openCredit[$key2];
                            $totalDebitLoop         += $chkDebit[$key2];
                            $totalCreditLoop        += $chkCredit[$key2];
                            $totalBalanceDebitLoop  += $balanceDebit;
                            $totalBalanceCreditLoop += $balanceCredit;
                        endforeach;
                    endif;
                endif;
                if(($key+1) == count($sqlArray->result())):
                    $pdfBody .= '<tr>
                                <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
                                <td><strong>'.$totalOpenDebitLoop.'</strong></td>
                                <td><strong>'.$totalOpenCreditLoop.'</strong></td>
                                <td><strong>'.$totalDebitLoop.'</strong></td>
                                <td><strong>'.$totalCreditLoop.'</strong></td>
                                <td><strong>'.$totalBalanceDebitLoop.'</strong></td>
                                <td><strong>'.$totalBalanceCreditLoop.'</strong></td>
                            </tr>';
                endif;
            }//end foreach

            $pdfBody .= '<tr>
                <td colspan="2" align="right"><strong>'.GRANDTOTAL.'</strong></td>
                <td><strong>'.$totalOpenDebit.'</strong></td>
                <td><strong>'.$totalOpenCredit.'</strong></td>
                <td><strong>'.$totalDebit.'</strong></td>
                <td><strong>'.$totalCredit.'</strong></td>
                <td><strong>'.$totalBalanceDebit.'</strong></td>
                <td><strong>'.$totalBalanceCredit.'</strong></td>
            </tr>
            </tbody></table>';
        }//end if

        else
        {
            $pdfBody = '<tr>
                <td colspan="8" align="center">'.SORRYNORECORD.'</td>
            </tr>';
        }

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SUBGROUPWITHSUBACCOUNT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'sub_account_group_trial';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function general_details_report()
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
            $totalPreviousLoop = $totalCurrentLoop = $totalPrevious = $totalCurrent = '';

            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                    <th>'.ACCODE.'</th>
                                                    <th>'.ACCCODENAME.'</th>
                                                    <th>'.LASTYEAR.'</th>
                                                    <th>'.CURRENTYEAR.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkEmployee = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$row->mainId."'");
                if($mainId=='1'):
                    $mainCode = '1';
                    $mainName = 'Asset';
                elseif($mainId=='2'):
                    $mainCode = '2';
                    $mainName = 'Liabilities';
                endif;

                if(!in_array($mainId,$chkGroupId)):
                    if($j != 0):
                        $pdfBody .= '<tr>
                            <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                            <td><strong>'.$totalPreviousLoop.'</strong></td>
                            <td><strong>'.$totalCurrentLoop.'</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right">&nbsp;</td>
                        </tr>';
                        $flag = true;
                    endif;
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$mainCode.'</strong></td>
                        <td align="left"><strong>'.$mainName.'</strong></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>';
                    $j = 1;
                endif;
                if(!in_array($row->HeadId,$chkHeadId)):
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$row->HeadCode.'</strong></td>
                        <td align="left"><strong>'.$row->HeadName.'</strong></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>';
                endif;

                if(!in_array($row->AccountId,$chkAccountId)):
                    $pdfBody .= '<tr>';

                    $getValues          = $this->balasheet_report_model->getAccValues($row->AccountId);
                    $getOpenValues      = $this->balasheet_report_model->getOpenAccValues($row->AccountId);
                    $balanceDebit       = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);
                    if($mainId==2)
                        $balanceDebit = $balanceDebit*-1;

                    $pdfBody .= '<td>'.$row->AccountCode.'</td>
                            <td>'.$row->AccountName.'</td>
                            <td>'.$getOpenValues['openDebit'].'</td>
                            <td>'.$balanceDebit.'</td>
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
                    $totalCurrent           += $balanceDebit;
                    $totalPreviousLoop      += $getOpenValues['openDebit'];
                    $totalCurrentLoop       += $balanceDebit;

                endif;

                if(($key+1) == count($sqlArray->result())):
                    $getPL  = $this->balasheet_report_model->getPLValues();
                    $totalPreviousLoop += $getPL['previous'];
                    $totalCurrentLoop += $getPL['current'];
                    $pdfBody .= '
                    <tr>
                        <td><strong>'.$getPL['acCode'].'</strong></td>
                        <td><strong>'.$getPL['acName'].'</strong></td>
                        <td><strong>'.$getPL['previous'].'</strong></td>
                        <td><strong>'.$getPL['current'].'</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                        <td><strong>'.$totalPreviousLoop.'</strong></td>
                        <td><strong>'.$totalCurrentLoop.'</strong></td>
                    </tr>';
                endif;
            }//end foreach

            $pdfBody .= '
            </tbody></table>';
        }//end if

        else
        {
            $pdfBody = '<tr>
                <td colspan="4" align="center">'.SORRYNORECORD.'</td>
            </tr>';
        }

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), BALANCESHEETGENERAL, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'general_details_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function general_summary_report()
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
            $totalPreviousLoop = $totalCurrentLoop = $totalPrevious = $totalCurrent = '';

            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                    <th>'.ACCODE.'</th>
                                                    <th>'.ACCCODENAME.'</th>
                                                    <th>'.LASTYEAR.'</th>
                                                    <th>'.CURRENTYEAR.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkEmployee = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$row->mainId."'");
                if($mainId=='1'):
                    $mainCode = '1';
                    $mainName = 'Asset';
                elseif($mainId=='2'):
                    $mainCode = '2';
                    $mainName = 'Liabilities';
                endif;

                if(!in_array($mainId,$chkAccountId)):
                    if($j != 0):
                        $pdfBody .= '<tr>
                            <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                            <td><strong>'.$totalPreviousLoop.'</strong></td>
                            <td><strong>'.$totalCurrentLoop.'</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right">&nbsp;</td>
                        </tr>';
                        $flag = true;
                    endif;
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$mainCode.'</strong></td>
                        <td align="left"><strong>'.$mainName.'</strong></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>';
                    $j = 1;
                endif;
                if(!in_array($row->GroupId,$chkGroupId)):
                    $pdfBody .= '<tr>
                        <td align="left"><strong>'.$row->GroupCode.'</strong></td>
                        <td align="left"><strong>'.$row->GroupName.'</strong></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>';
                endif;

                if(!in_array($row->HeadId,$chkHeadId)):
                    $pdfBody .= '<tr>';

                    $getValues          = $this->balasheet_report_model->getAccHeadValues($row->HeadId);
                    $getOpenValues      = $this->balasheet_report_model->getOpenHeadAccValues($row->HeadId);
                    $balanceDebit       = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);
                    if($mainId==2)
                        $balanceDebit = $balanceDebit*-1;

                    $pdfBody .= '<td>'.$row->HeadCode.'</td>
                            <td>'.$row->HeadName.'</td>
                            <td>'.$getOpenValues['openDebit'].'</td>
                            <td>'.$balanceDebit.'</td>
                        </tr>';

                    if($flag == true):
                        $totalPreviousLoop  = '';
                        $totalCurrentLoop   = '';
                        $flag               = false;
                    endif;

                    $chkGroupId[]            = $row->GroupId;
                    $chkHeadId[]             = $row->HeadId;
                    $chkAccountId[]          = $mainId;

                    $totalPrevious          += $getOpenValues['openDebit'];
                    $totalCurrent           += $balanceDebit;
                    $totalPreviousLoop      += $getOpenValues['openDebit'];
                    $totalCurrentLoop       += $balanceDebit;

                endif;

                if(($key+1) == count($sqlArray->result())):
                    $getPL  = $this->balasheet_report_model->getPLValues();
                    $totalPreviousLoop += $getPL['previous'];
                    $totalCurrentLoop += $getPL['current'];
                    $pdfBody .= '
                    <tr>
                        <td><strong>'.$getPL['acCode'].'</strong></td>
                        <td><strong>'.$getPL['acName'].'</strong></td>
                        <td><strong>'.$getPL['previous'].'</strong></td>
                        <td><strong>'.$getPL['current'].'</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                        <td><strong>'.$totalPreviousLoop.'</strong></td>
                        <td><strong>'.$totalCurrentLoop.'</strong></td>
                    </tr>';
                endif;
            }//end foreach

            $pdfBody .= '
            </tbody></table>';
        }//end if

        else
        {
            $pdfBody = '<tr>
                <td colspan="4" align="center">'.SORRYNORECORD.'</td>
            </tr>';
        }

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), BALANCESHEETGENERAL, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'general_summary_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function balance_sheet_report()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();
        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                                        <tr>
                                                            <th colspan="2">'.LIABILITIES.'</th>
                                                            <th>'.BALANCE.'</th>
                                                        </tr>';
            $chkGroupId = $chkHeadId = $chkAccountId = array();
            $totalCurrentLoop2 = '';

            foreach($sqlArray->result() as $key => $row):
                $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$row->mainId."'");
                if($mainId == '2') :
                    if(!in_array($row->HeadId,$chkHeadId)):
                        $pdfBody .= '<tr>
                                                                    <td align="left"><strong>'.$row->HeadCode.'</strong></td>
                                                                    <td align="left"><strong>'.$row->HeadName.'</strong></td>
                                                                    <td align="center">&nbsp;</td>
                                                                </tr>';
                    endif;

                    if(!in_array($row->AccountId,$chkAccountId)):
                        $pdfBody .= '<tr>';

                        $getValues          = $this->balasheet_report_model->getBalanceAccValues($row->AccountId,$mainId);

                        $pdfBody .= '<td>'.$row->AccountCode.'</td>
                                    <td>'.$row->AccountName.'</td>
                                    <td>'.$getValues['debit'].'</td>
                                </tr>';

                        $chkGroupId[]            = $mainId;
                        $chkHeadId[]             = $row->HeadId;
                        $chkAccountId[]          = $row->AccountId;

                        $totalCurrentLoop2       += $getValues['debit'];
                    endif;
                endif;
            endforeach;
            $getPL  = $this->balasheet_report_model->getPLValues();
            $totalCurrentLoop2 += $getPL['current'];
            $pdfBody .= '
                        <tr>
                            <td align="left">'.$getPL['acCode'].'</td>
                            <td align="left">'.$getPL['acName'].'</td>
                            <td align="left">'.$getPL['current'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                            <td><strong>'.$totalCurrentLoop2.'</strong></td>
                        </tr>';
            $pdfBody .= '</table>
                                                </td>
                                                <td>
                                                    <table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                                        <tr>
                                                            <th colspan="2">'.ASSETS.'</th>
                                                            <th>'.BALANCE.'</th>
                                                        </tr>';
            $chkGroupId = $chkHeadId = $chkAccountId = array();
            $totalCurrentLoop = '';
            foreach($sqlArray->result() as $key => $row):
                $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$row->mainId."'");
                if($mainId == '1') :
                    if(!in_array($row->HeadId,$chkHeadId)):
                        $pdfBody .= '<tr>
                                        <td align="left"><strong>'.$row->HeadCode.'</strong></td>
                                        <td align="left"><strong>'.$row->HeadName.'</strong></td>
                                        <td align="center">&nbsp;</td>
                                    </tr>';
                    endif;

                    if(!in_array($row->AccountId,$chkAccountId)):
                        $pdfBody .= '<tr>';

                        $getValues          = $this->balasheet_report_model->getBalanceAccValues($row->AccountId,$mainId);

                        $pdfBody .= '<td>'.$row->AccountCode.'</td>
                                                                        <td>'.$row->AccountName.'</td>
                                                                        <td>'.$getValues['debit'].'</td>
                                                                    </tr>';

                        $chkGroupId[]            = $mainId;
                        $chkHeadId[]             = $row->HeadId;
                        $chkAccountId[]          = $row->AccountId;

                        $totalCurrentLoop       += $getValues['debit'];
                    endif;
                endif;
            endforeach;
            $pdfBody .= '<tr>
                                                            <td colspan="2" align="right"><strong>'.GROUPTOTAL.'</strong></td>
                                                            <td><strong>'.$totalCurrentLoop.'</strong></td>
                                                        </tr>';
            $pdfBody .= '</table>
                                                </td>
                                            </tr>
                                        </tbody>
                                   </table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), BALANCESHEETGENERAL, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'balance_sheet_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function chart_account_group()
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
                                                    <th align="left">'.CODE.'</th>
                                                    <th align="left">'.GROUPNAME.'</th>
                                                    <th align="left">'.GROUP.'</th>
                                                    <th align="left">'.DESCRIPTION.'</th>
                                                    <th align="left">'.CODE.'</th>
                                                    <th align="left">'.SUBGROUPNAME.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkMainCode = array();
            $chkMainGroup   = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                switch($row->vTitle)
                {
                    case "1" :
                        $mainGroup = 'Assets';
                        $mainCode  = '1';
                        break;

                    case "2" :
                        $mainGroup = 'Liability';
                        $mainCode  = '2';
                        break;

                    case "3" :
                        $mainGroup = 'Income';
                        $mainCode  = '3';
                        break;

                    case "4" :
                        $mainGroup = 'Expense';
                        $mainCode  = '4';
                        break;
                }//end switch

                if(!in_array($mainCode,$chkMainCode)):
                    $pdfBody .= '<tr>
                    <td><strong>'.$mainCode.'</strong></td>
                    <td><strong>'.$mainGroup.'</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>';
                endif;
                $pdfBody .= '<tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>'.$this->common_model->get_field_name('FinanceModule','vCode'," WHERE nParent='".$row->nId."'").'</td>
                    <td>'.$this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nParent='".$row->nId."'").'</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>';
                $finance =  $this->common_model->get_field_name('FinanceModule','nId'," WHERE nParent='".$row->nId."'");
                $viewAccessList  = $this->db->query("SELECT ah.vHCode,ah.vAcname,ah.nAId,ah.nAGroup FROM {PRE}FinanceModule fm
                                         LEFT JOIN {PRE}AccountHead ah ON fm.nId = ah.nAGroup
                                         WHERE ah.vActive = '1' AND fm.nId='".$finance."' ORDER BY ah.nAId ASC");
                if($viewAccessList->num_rows() > 0):
                    foreach($viewAccessList->result() as $key1=>$accItem):
                        $pdfBody .= '<tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>'.$accItem->vHCode.'</td>
                            <td>'.$accItem->vAcname.'</td>
                        </tr>';
                    endforeach;
                endif;

                $chkMainCode[] = $mainCode;
                $chkMainGroup[] = $mainGroup;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ACCOUNTGROUPLIST, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'chart_account_group';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function chart_account_head()
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
                                                    <th align="left">'.CODE.'</th>
                                                    <th align="left">'.GROUPNAME.'</th>
                                                    <th align="left">'.GROUP.'</th>
                                                    <th align="left">'.DESCRIPTION.'</th>
                                                    <th align="left">'.CODE.'</th>
                                                    <th align="left">'.SUBGROUPNAME.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkGroupId = array();
            $chkAccId   = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                if(!in_array($row->aid,$chkGroupId)):
                    $pdfBody .= '
                            <tr>
                                <td>'.$row->aid.'</td>
                                <td>'.$row->ahead.'</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>';
                endif;
                if(!in_array($row->nAId,$chkAccId)):
                    $pdfBody .= '
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>'.$row->vHCode.'</td>
                                <td>'.$row->vAcname.'</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>';
                endif;
                $pdfBody .= '
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>'.$row->vACode.'</td>
                                <td>'.$row->vTitle.'</td>
                            </tr>';
                $chkGroupId[] = $row->aid;
                $chkAccId[]   = $row->nAId;
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ACCOUNTHEADLIST, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'chart_account_head';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function account_head_group()
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
                                    <th align="left">'.CODE.'</th>
                                    <th align="left">'.GROUPNAME.'</th>
                                    <th align="left">'.GROUP.'</th>
                                    <th align="left">'.DESCRIPTION.'</th>
                                    <th align="left">'.CODE.'</th>
                                    <th align="left">'.SUBGROUPNAME.'</th>
                                </tr>
                            </thead>
                            <tbody>';
            $chkMainCode = array();
            $chkMainGroup   = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                switch($row->vTitle)
                {
                    case "1" :
                        $mainGroup = 'Assets';
                        $mainCode  = '1';
                        break;

                    case "2" :
                        $mainGroup = 'Liability';
                        $mainCode  = '2';
                        break;

                    case "3" :
                        $mainGroup = 'Income';
                        $mainCode  = '3';
                        break;

                    case "4" :
                        $mainGroup = 'Expense';
                        $mainCode  = '4';
                        break;
                }//end switch

                if(!in_array($mainCode,$chkMainCode)):
                    $pdfBody .= '<tr>
                        <td><strong>'.$mainCode.'</strong></td>
                        <td><strong>'.$mainGroup.'</strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>';
                endif;
                $pdfBody .='
                    <tr>
                        <td><strong>'.$this->common_model->get_field_name('FinanceModule','vCode'," WHERE nParent='".$row->nId."'").'</strong></td>
                        <td><strong>'.$this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nParent='".$row->nId."'").'</strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>';
                $accountVal = $this->chart_report_model->getAccountGroupVal($this->common_model->get_field_name('FinanceModule','nId'," WHERE nParent='".$row->nId."'"));
                if(!empty($accountVal)):
                    foreach($accountVal as $key1=>$accItem):
                        $pdfBody .='<tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>'.$accItem->vHCode.'</td>
                            <td>'.$accItem->vAcname.'</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>';

                        $accountVal2 = $this->chart_report_model->getAccountHeadVal($accItem->nAId);
                        if(!empty($accountVal2)):
                            foreach($accountVal2 as $key2=>$accItem2):
                                $pdfBody .='<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>'.$accItem2->vACode.'</td>
                                    <td>'.$accItem2->vTitle.'</td>
                                </tr>';
                            endforeach;
                        endif;
                    endforeach;
                endif;
                $chkMainCode[] = $mainCode;
                $chkMainGroup[] = $mainGroup;
            }//end for each

            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), ACCOUNTHEADLIST, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'account_head_group';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function sub_account_list()
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
                                                    <th align="left">&nbsp;</th>
                                                    <th align="left">'.ACCCODENAME.'</th>
                                                    <th align="left">&nbsp;</th>
                                                    <th align="left" colspan="3">&nbsp;</th>
                                                </tr>
                                                <tr>
                                                    <th align="left">'.SRNO.'</th>
                                                    <th align="left">'.CODE.'</th>
                                                    <th align="left">'.NAME.'</th>
                                                    <th align="left">'.CONTACTPHONE.'</th>
                                                    <th align="left">'.CREDITDAYS.'</th>
                                                    <th align="left">'.CREDITAMOUNT.'</th>
                                                    <th align="left">'.CURRNAME.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkEmployee = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '<tr>
                                <td>'.$srNo.'</td>
                                <td>'.$row->vCode.'</td>
                                <td>'.$row->vName.'</td>
                                <td>'.$row->vPhone.'</td>
                                <td>'.$row->vCreditDays.'</td>
                                <td>'.$row->vAmount.'</td>
                                <td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'").'</td>
                            </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), SUBACCOUNTLIST, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'sub_account_list';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function airline_account_report()
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
                                                    <th align="left">'.SRNO.'</th>
                                                    <th align="left">'.NUMCODE.'</th>
                                                    <th align="left">'.ALPCODE.'</th>
                                                    <th align="left">'.AIRLINENAME.'</th>
                                                    <th align="left">'.TKTCOMMISSION.'</th>
                                                    <th align="left">'.XOCOMMISSION.'</th>
                                                    <th align="left">'.CONTACTPHONE.'</th>
                                                    <th align="left">'.EMAIL.'</th>
                                                    <th align="left">'.PAYABLEACCOUNT.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkEmployee = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '<tr>
                                <td>'.$srNo.'</td>
                                <td>'.$row->vAirlineNo.'</td>
                                <td>'.$row->vAlpha.'</td>
                                <td>'.$row->vTitle.'</td>
                                <td>'.$row->nTkt.'</td>
                                <td>'.$row->nXo.'</td>
                                <td>'.$row->vPhone.'</td>
                                <td>'.$row->vEmail.'</td>
                                <td>&nbsp;</td>
                            </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), AIRLINEREPORT, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'airline_account_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function hotel_account_report()
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
                                                    <th align="left">'.SRNO.'</th>
                                                    <th align="left">'.COUNTRYNAME.'</th>
                                                    <th align="left">'.CITYNAME.'</th>
                                                    <th align="left">'.HOTELNAME.'</th>
                                                    <th align="left">'.CONTACTPHONE.'</th>
                                                    <th align="left">'.EMAIL.'</th>
                                                    <th align="left">'.SERVICES.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            $chkEmployee = array();
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '<tr>
                                <td>'.$srNo.'</td>
                                <td>'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCountry."'").'</td>
                                <td>'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$row->nCity."'").'</td>
                                <td>'.$row->vName.'</td>
                                <td>'.$row->vPhone.'</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>';
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), HOTELLIST, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'hotel_account_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function country_account_report()
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
                                                    <th align="left">'.SRNO.'</th>
                                                    <th align="left">'.COUNTRYNAME.'</th>
                                                    <th align="left">'.CITYNAME.'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '<tr>
                                <td>'.$srNo.'</td>
                                <td>'.$row->vName.'</td>
                                <td>&nbsp;</td>
                            </tr>';
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId='".$row->nId."'");
                if($viewAccessList->num_rows() > 0)
                {
                    foreach ($viewAccessList->result() as $key1=>$city):
                        $pdfBody .= '<tr>
                            <td colspan="2">&nbsp;</td>
                            <td>'.$city->vName.'</td>
                        </tr>';
                    endforeach;
                }
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), COUNTRYCITYLIST, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'country_account_report';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');
    }//end function

    function sales_reprint()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody        = array();

        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                    <th>'.SRNO.'</th>
                                                    <th>'.CUSTDATE.'</th>
                                                    <th>'.DOCNUMBER.'</th>
                                                    <th colspan="16">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '<tr>
                                <td>'.$srNo.'</td>
                                <td>'.stripslashes(date('d-m-Y',strtotime($row->dDate))).'</td>
                                <td>'.stripslashes($row->vInvoiceNumber).'</td>
                                <td colspan="16">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                                <td>'.TYPE.'</td>
                                <td>'.AIRLINE.'</td>
                                <td>'.TICKETNAME.'</td>
                                <td>'.EMPNAME.'</td>
                                <td>'.FACEFARE.'</td>
                                <td>'.TAX.'</td>
                                <td>'.COMMPERCENT.'</td>
                                <td>'.COMMISSIONAMT.'</td>
                                <td>'.NETAMT.'</td>
                                <td>'.SER.'</td>
                                <td>'.SERAMT.'</td>
                                <td>'.SLSAMT.'</td>
                                <td>'.DISCAMT.'</td>
                                <td>'.COLECTED.'</td>
                                <td>'.AGENTNAME.'</td>
                                <td>'.LPO.'</td>
                            </tr>';
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nSalesCloseId='".$row->nSCId."' ORDER BY nId DESC");
                if($viewAccessList->num_rows() > 0)
                {
                    foreach ($viewAccessList->result() as $key1=>$tkt):
                        switch($tkt->vType)
                        {
                            case "Tkt":
                                $type      = 'Ticket';
                                $tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual', " WHERE nTId = '".$tkt->vTktNumber."'");
                                break;

                            case "Xo":
                                $type      = 'Xo';
                                $tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual', " WHERE nXId = '".$tkt->vTktNumber."'");
                                break;

                            case "Htl":
                                $type      = 'Hotel';
                                $tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual', " WHERE nHVId = '".$tkt->vTktNumber."'");
                                break;
                        }
                        $pdfBody .= '<tr>
                            <td colspan="3">&nbsp;</td>
                            <td align="left">'.$type.'</td>
                            <td align="left">'.stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$tkt->nAirline."'")).'</td>
                            <td align="left">'.stripslashes($tktNumber).'</td>
                            <td align="left">'.stripslashes($tkt->vEmployee).'</td>
                            <td align="left">'.stripslashes($tkt->vFare).'</td>
                            <td align="left">'.stripslashes($tkt->vTax).'</td>
                            <td align="left">'.stripslashes($tkt->vComm).'</td>
                            <td align="left">'.stripslashes($tkt->vCommAmt).'</td>
                            <td align="left">'.stripslashes($tkt->vNetAmt).'</td>
                            <td align="left">'.stripslashes($tkt->vServPercent).'</td>
                            <td align="left">'.stripslashes($tkt->vServAmt).'</td>
                            <td align="left">'.stripslashes($tkt->vSlsAmt).'</td>
                            <td align="left">'.stripslashes($tkt->vDisAmt).'</td>
                            <td align="left">'.stripslashes($tkt->vCollected).'</td>
                            <td align="left">'.stripslashes($tkt->vAgentAmt).'</td>
                            <td align="left">'.stripslashes($tkt->vDLPO).'</td>
                        </tr>';
                        $totalFare      += $tkt->vFare;
                        $totalTax       += $tkt->vTax;
                        $totalComm      += $tkt->vComm;
                        $totalCommAmt   += $tkt->vCommAmt;
                        $totalNet       += $tkt->vNetAmt;
                        $totalSerP      += $tkt->vServPercent;
                        $totalSer       += $tkt->vServAmt;
                        $totalSales     += $tkt->vSlsAmt;
                        $totalDisc      += $tkt->vDisAmt;
                        $totalCollected += $tkt->vCollected;
                        $totalAgent     += $tkt->vAgentAmt;
                    endforeach;
                    $pdfBody .= '<tr>
                        <td align="right" colspan="7"><strong>'.TOTALAMT.'</strong></td>
                        <td align="left">'.stripslashes($totalFare).'</td>
                        <td align="left">'.stripslashes($totalTax).'</td>
                        <td align="left">'.stripslashes($totalComm).'</td>
                        <td align="left">'.stripslashes($totalCommAmt).'</td>
                        <td align="left">'.stripslashes($totalNet).'</td>
                        <td align="left">'.stripslashes($totalSerP).'</td>
                        <td align="left">'.stripslashes($totalSer).'</td>
                        <td align="left">'.stripslashes($totalSales).'</td>
                        <td align="left">'.stripslashes($totalDisc).'</td>
                        <td align="left">'.stripslashes($totalCollected).'</td>
                        <td align="left">'.stripslashes($totalAgent).'</td>
                        <td align="left">&nbsp;</td>
                    </tr>';
                }//end if
                $pdfBody .= '<tr>
                        <td colspan="3">&nbsp;</td>
                        <td colspan="2">'.DRACCNAME.'</td>
                        <td colspan="2">'.ACHEAD.'</td>
                        <td colspan="2">'.CARDNAME.'</td>
                        <td colspan="2">'.COMMPERCENT.'</td>
                        <td colspan="2">'.COMMISSIONAMT.'</td>
                        <td colspan="2">'.AMT.'</td>
                        <td colspan="2">'.CRV.'</td>
                        <td colspan="2">'.REFTKT1.'</td>
                    </tr>';
                $viewAccessList2  = $this->db->query("SELECT * FROM {PRE}SalesAccountDetails WHERE nSalesCloseId='".$row->nSCId."' ORDER BY nId DESC");
                if($viewAccessList2->num_rows() > 0)
                {
                    foreach ($viewAccessList2->result() as $key2=>$acc):
                        switch($acc->vTable)
                        {
                            case "C":
                                $AccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$acc->nDrAccount."'");
                                break;

                            case "V":
                                $AccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$acc->nDrAccount."'");
                                break;

                            case "A":
                                $AccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc->nDrAccount."'");
                                break;

                            case "O":
                                $AccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$acc->nDrAccount."'");
                                break;

                            case "AG":
                                $AccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$acc->nDrAccount."'");
                                break;
                        }
                        $subAcc = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc->nAcHead."'");
                        $card   = $this->common_model->get_field_name('Card','vName'," WHERE nCId = '".$acc->nCard."'");

                        $pdfBody .= '<tr>
                                <td colspan="3">&nbsp;</td>
                                <td align="left" colspan="2">'.$AccountName.'</td>
                                <td align="left" colspan="2">'.$subAcc.'</td>
                                <td align="left" colspan="2">'.$card.'</td>
                                <td align="left" colspan="2">'.stripslashes($acc->vCardComm).'</td>
                                <td align="left" colspan="2">'.stripslashes($acc->vCommAmt).'</td>
                                <td align="left" colspan="2">'.stripslashes($acc->vAmt).'</td>
                                <td align="left" colspan="2">'.stripslashes($acc->vCRV).'</td>
                                <td align="left" colspan="2">'.stripslashes($acc->vRefTkt).'</td>
                            </tr>';
                    endforeach;
                }//end if
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), NETINVOICE, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'sales_reprint';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'L','A3');
    }//end function

    function refund_reprint()
    {
        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody        = array();

        $sqlArray = $this->db->query($_SESSION['sess_query']);

        if ($sqlArray->num_rows() > 0)
        {
            $pdfBody = '<table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
                                            <thead>
                                                <tr>
                                                    <th>'.SRNO.'</th>
                                                    <th>'.CUSTDATE.'</th>
                                                    <th>'.DOCNUMBER.'</th>
                                                    <th colspan="11">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            foreach ($sqlArray->result() as $key => $row)
            {
                $srNo = $key+1;
                $pdfBody .= '<tr>
                                <td>'.$srNo.'</td>
                                <td>'.stripslashes(date('d-m-Y',strtotime($row->dDate))).'</td>
                                <td>'.stripslashes($row->vDocNumber).'</td>
                                <td colspan="11">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                                <th>'.TYPE.'</th>
                                <th>'.AIRLINE.'</th>
                                <th>'.TICKETNAME.'</th>
                                <th>'.ORIGINALTKT.'</th>
                                <th>'.NETAMT.'</th>
                                <th>'.SER.'</th>
                                <th>'.SERAMT.'</th>
                                <th>'.AGENTAMT.'</th>
                                <th>'.PAIDAMT.'</th>
                                <th>'.DRACCNAME.'</th>
                                <th>'.LPO.'</th>
                            </tr>';
                $viewAccessList  = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId='".$row->nRCId."' ORDER BY nId DESC");
                if($viewAccessList->num_rows() > 0)
                {
                    foreach ($viewAccessList->result() as $key1=>$tkt):
                        switch($tkt->vType)
                        {
                            case "Tkt":
                                $type      = 'Ticket';
                                $tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual', " WHERE nTId = '".$tkt->nTicketNumber."'");
                                break;

                            case "Xo":
                                $type      = 'Xo';
                                $tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual', " WHERE nXId = '".$tkt->nTicketNumber."'");
                                break;

                            case "Htl":
                                $type      = 'Hotel';
                                $tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual', " WHERE nHVId = '".$tkt->nTicketNumber."'");
                                break;
                        }

                        switch($tkt->vTable)
                        {
                            case "C":
                                $AccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$tkt->nDrAcc."'");
                                break;

                            case "V":
                                $AccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$tkt->nDrAcc."'");
                                break;

                            case "A":
                                $AccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$tkt->nDrAcc."'");
                                break;

                            case "O":
                                $AccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$tkt->nDrAcc."'");
                                break;

                            case "AG":
                                $AccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$tkt->nDrAcc."'");
                                break;
                        }
                        $Airline = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$tkt->nAirline."'");

                        $pdfBody .= '<tr>
                            <td colspan="3">&nbsp;</td>
                            <td align="left">'.$type.'</td>
                            <td align="left">'.$Airline.'</td>
                            <td align="left">'.stripslashes($tktNumber).'</td>
                            <td align="left">'.stripslashes($tkt->vORG).'</td>
                            <td align="left">'.stripslashes($tkt->vNetAmt).'</td>
                            <td align="left">'.stripslashes($tkt->vServPercent).'</td>
                            <td align="left">'.stripslashes($tkt->vServAmt).'</td>
                            <td align="left">'.stripslashes($tkt->vAgentAmt).'</td>
                            <td align="left">'.stripslashes($tkt->vPaidAmt).'</td>
                            <td align="left">'.$AccountName.'</td>
                            <td align="left">'.stripslashes($tkt->vLPO).'</td>
                        </tr>';
                        $totalNet       += $tkt->vNetAmt;
                        $totalSerP      += $tkt->vServPercent;
                        $totalSer       += $tkt->vServAmt;
                        $totalAgent     += $tkt->vAgentAmt;
                        $totalPaid      += $tkt->vPaidAmt;
                    endforeach;
                    $pdfBody .= '<tr>
                        <td align="right" colspan="7"><strong>'.TOTALAMT.'</strong></td>
                        <td align="left">'.stripslashes($totalNet).'</td>
                        <td align="left">'.stripslashes($totalSerP).'</td>
                        <td align="left">'.stripslashes($totalSer).'</td>
                        <td align="left">'.stripslashes($totalAgent).'</td>
                        <td align="left">'.stripslashes($totalPaid).'</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                    </tr>';
                }//end if
            }//end for each
            $pdfBody .= '</tbody></table>';
        }//end if

		if(empty($pdfBody))
			$pdfBody = 'Sorry, no records found';

        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(), NETINVOICE, $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);

        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time().'refund_reprint';
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A3');
    }//end function

    function generate_pdf_P(){

        $this->common_model->check_admin_authorization();
        session_start();

        $pdfBody  = array();

        $sess_data_name = $this->uri->segment(4);
        $pdfBody = $_SESSION[$sess_data_name];
//echo $pdfBody;
        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(),"", $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time(). $sess_data_name;
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,'P','A4');


    }//end function


    function generate_pdf($email = 'N'){
        session_start();
        $this->common_model->check_admin_authorization();

        $pdfBody  = array();

        $sess_data_name = $this->uri->segment(4);

        $pdfBody = $_SESSION[$sess_data_name];
        if($_SESSION[$sess_data_name.'_pgSz'] == 'A4')
          $pageSize ='A4';
        else
          $pageSize = 'A3';

        if($_SESSION[$sess_data_name.'_lp'] == 'P')
          $landscape ='P';
        else
          $landscape = 'L';
$landscape ='P';
//echo $pdfBody;
        $pdfFormat  = read_file('./static/pdf/pdf.html');
        $arrSearch	= array("{SITE_TITLE}", "{PDF_TITLE}","{PDF_CONTENT}");
        $arrReplace	= array($this->common_model->get_site_title(),"", $pdfBody);
        $pdfBody	= str_replace($arrSearch, $arrReplace,$pdfFormat);
        //write content to file
        write_file('./static/pdf/pdf1.html', $pdfBody);
        $download_fileName = date('Y-m-d').'_'.time(). $sess_data_name;
        $this->common_model->exportAsReqPdf($pdfBody,$download_fileName,$landscape,$pageSize,$email);

    }//end function

    function email_pdf(){

      $this->common_model->check_admin_authorization();

      $this->load->helper(array('form', 'url'));
      $this->load->library('form_validation');
      $this->form_validation->set_rules('txtFrom', 'From', 'required');
//      $this->form_validation->set_rules('txtFrom', 'From', 'email');
      $this->form_validation->set_rules('txtTo', 'To', 'required');
//      $this->form_validation->set_rules('txtTo', 'To', 'email');

      if ($this->form_validation->run() == FALSE){
        redirect($this->input->post('link').'/U'); 
      }
      else{
        $this->generate_pdf('Y');
        redirect($this->input->post('link').'/S'); 
      }

    }//end function

}//end class


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
