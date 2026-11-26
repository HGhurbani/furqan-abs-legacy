<?php
require_once 'static/classes/paginationClass.php';

class Common_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();	
		$this->load->library('session');
		$this->langFiles();
    }//end function
    
	function langFiles()
	{
		$langFolder = $this->langFolder();
		$phpFile    = $this->langFolderFiles();
		
		foreach($phpFile as $langval)
		{
			include_once('static/languages/'.$langFolder.'/'.$langval);
		}//end foreach
	}//end function

	//function to get php file from lang folder
	function langFolderFiles()
	{
		$langFolder = $this->langFolder();
		
		//creating page array
		$Rootpage=array();
		$dirpath="static/languages/".$langFolder;
		
		if($handledir=opendir($dirpath)) 
		{
			while (false!==($file=readdir($handledir))) 
			{
				if ($file!= "." && $file!= "..") 
				{
					$splitbydotarray=explode(".",$file);
					switch($splitbydotarray[1])
					{
						case "php":
							$Rootpage[$file]=$file;
						break;
					}//end switch
				}//end if
			}//end while
		}//end if
		closedir($handledir);
		//sort array
		return ($Rootpage);
	}//end function
	
	//function to get lang folder
	function langFolder($langFolder = '')
	{
		if(trim($langFolder) == '')
		{
			if($this->session->userdata('lang') == '')
			{
				$langFolder = 'eng';
			}//end if
			else
			{
				$langFolder = $this->session->userdata('lang');
			}//end else
		}//end if
		
		return ($langFolder);
	}//end function
	
    function get_set_sessions()
    {
		$this->load->library('session');
		return $this->session->userdata('AbsAdmin');
    }//end function
	
	function get_site_title()
    {
		//site Name
		$siteName = $this->db->query("SELECT vDes FROM {PRE}LookUp WHERE vName = 'CompanyName'");
		$rowName  = $siteName->row();
		return $rowName->vDes;
    }//end function
	
	function get_admin_lastlogin()
    {
        $this->load->library('session');
        return $this->session->userdata('lastLogin');
    }//end function
	
	function check_admin_authorization()
    {
		$this->load->library('session');
		$this->session->userdata('AbsAdmin');
		if(!$this->session->userdata('AbsAdmin') && $this->session->userdata('logged_in') == FALSE && current_url() != base_url().'index.php/login')
		{
			$this->session->sess_destroy();
			redirect(site_url("login"));
		}//end if
    }//end function
	
	//function for given table total records
	function get_total_num_rows($tableName,$tblId,$extraCond)
	{
		$tabCount = $this->db->query("SELECT ".$tblId." FROM {PRE}".$tableName.$extraCond);
		return ($tabCount->num_rows());
	}//end function
	
	function get_total_Student($tableName,$tblId,$extraCond)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT ".$tblId." FROM {PRE}".$tableName.$extraCond ." LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
		
		if ($adminGroupList->num_rows() > 0)
			{
				foreach ($adminGroupList->result() as $key=>$row)
				{
					$adminGroupRow[] = $row;
				}//end for each
			}//end if
		return ($adminGroupRow);
	}//end function
	
	function get_list_of_admin_modules($Group = 0)
	{
		// Colors
		$color = '#F6F6F6';
		$moduleArr = array();
		
		if (intval($Group) != 0)
		{
			$adAccess = $this->db->query("SELECT nModule FROM {PRE}Admin_Module_Access WHERE nGroup = '".$Group."'");
			if ($adAccess->num_rows() > 0)
			{
				foreach ($adAccess->result() as $key=>$row)
				{
					$moduleArr[] = $row->nModule;
				}//end for each
			}//end if
		}//end if
		
		//checking lang
		switch($this->session->userdata('lang'))
		{
			case "eng":
				$sTitle  = 'amc.vTitle, am.vModuleTitle';
				$sTitle1 = 'vTitle';
				$sTitle2 = 'vModuleTitle';
			break;
			
			case "ar":
				$sTitle  = 'amc.vTitle2, am.vModuleTitle2';
				$sTitle1 = 'vTitle2';
				$sTitle2 = 'vModuleTitle2';
			break;
		}//end switch
		
		$adModuleList = $this->db->query("SELECT amc.*, am.* FROM {PRE}Admin_Module_Category amc,
											{PRE}Admin_Modules am WHERE am.nCategory = amc.nCId
										  ORDER BY ".$sTitle);
		
		if ($adModuleList->num_rows() > 0)
		{
			echo '<table width="100%" cellspacing="1" cellpadding="5" bgcolor="#000000">';
			foreach ($adModuleList->result() as $key=>$row)
			{
				if ($row->nCId != $categoryId)
				{
					$bgcolor = $color;
					echo '<tr bgcolor="#CC9933"><td>'.$row->$sTitle1.'</td></tr>';
				}//end if
				
				if(in_array($row->nMId,$moduleArr)) 
				{ 
					$checked = 'checked'; 
				}//end if
				else 
				{
					$checked = ''; 
				}//end else
				
				if ($row->nMId=='2' || $row->nMId=='3')
						$bgcolor="#FFAD8A"; else $bgcolor="#f0f0f0";
				?>
				<tr bgcolor="<?php echo $bgcolor;?>">
					<td>
						<input type="checkbox" value="<?php echo $row->nMId;?>" name="ddlModules[]" <?php echo $checked;?>>&nbsp;<?php echo $row->$sTitle2; ?>
					</td>
				</tr>
				<?php
					$categoryId = $row->nCId;	 	
				}//end foreach
			echo '</table>';
		}//end if
	}//end function
	
	function get_admin_menu($adminId)
	{
		$adminMenuList = array();
		
		//checking lang
		switch($this->session->userdata('lang'))
		{
			case "eng":
				$sTitle  = 'amc.vTitle, am.vModuleTitle';
			break;
			
			case "ar":
				$sTitle  = 'amc.vTitle2, am.vModuleTitle2';
			break;
		}//end switch

		// amc = admin_module_category
		// am = admin_modules
		// ama = admin_module_access
		// au = admin_users
		$adminMenu = $this->db->query("SELECT amc.*, am.* FROM {PRE}Admin_Module_Category amc,
												{PRE}Admin_Modules am,
											{PRE}Admin a, {PRE}Admin_Module_Access ama
												 WHERE a.nGroupId = ama.nGroup
											AND am.nCategory = amc.nCId
												 AND ama.nModule = am.nMId
											AND a.nId = '".$adminId."' AND 
												am.vActive ='1' AND am.nSubParent = 0
											GROUP BY amc.nCId ORDER BY amc.nPosition, ".$sTitle);
		
		/*$adminMenu = $this->db->query("SELECT amc.*, am.* FROM {PRE}Admin_Module_Category amc,
												{PRE}Admin_Modules am,
											{PRE}Admin a, {PRE}Admin_Module_Access ama
												 WHERE a.nGroupId = ama.nGroup
											AND am.nCategory = amc.nCId
												 AND ama.nModule = am.nMId
											AND a.nId = '".$adminId."' AND 
												am.vActive ='1'
											GROUP BY amc.nCId ORDER BY amc.nPosition, ".$sTitle);*/

		if ($adminMenu->num_rows() > 0)
		{
			foreach ($adminMenu->result() as $key=>$row)
			{
				$adminMenuList[] = $row;
			}//end for each
		} //end if
		return ($adminMenuList);
	}//end function
	
	function get_admin_submenu($adminId,$catId)
	{
		$userMenuList = array();
		// amc = admin_module_category
		// am = admin_modules
		// ama = admin_module_access
		// au = admin_users
	
		$adminMenu = $this->db->query("SELECT am.* FROM {PRE}Admin_Modules am
											LEFT JOIN {PRE}Admin_Module_Access ama ON am.nMId = ama.nModule
												 LEFT JOIN {PRE}AdminGroups ag ON ama.nGroup = ag.nGId
											LEFT JOIN {PRE}Admin a ON a.nGroupId = ag.nGId
												 WHERE a.nId = '".$adminId."' AND am.vActive = '1' 
												AND am.nSubParent = 0 AND am.nCategory = '".$catId."' 
											ORDER BY am.nPosition,am.vModuleTitle");
		/*$adminMenu = $this->db->query("SELECT am.* FROM {PRE}Admin_Modules am
											LEFT JOIN {PRE}Admin_Module_Access ama ON am.nMId = ama.nModule
												 LEFT JOIN {PRE}AdminGroups ag ON ama.nGroup = ag.nGId
											LEFT JOIN {PRE}Admin a ON a.nGroupId = ag.nGId
												 WHERE a.nId = '".$adminId."' AND am.vActive = '1' 
											AND am.nCategory = '".$catId."'  AND ama.vAccess != 'N' 
												ORDER BY am.nPosition,am.vModuleTitle");*/
		
		if ($adminMenu->num_rows() > 0)
		{
			foreach ($adminMenu->result() as $key=>$row)
			{
				$adminMenuList[] = $row;
			}//end for each
		} //end if
		return ($adminMenuList);
	}//end function

	function get_admin_submenu2($adminId,$catId,$subCatId)
	{
		$userMenuList = array();
		// amc = admin_module_category
		// am = admin_modules
		// ama = admin_module_access
		// au = admin_users
	
		$adminMenu = $this->db->query("SELECT am.* FROM {PRE}Admin_Modules am
											LEFT JOIN {PRE}Admin_Module_Access ama ON am.nMId = ama.nModule
												 LEFT JOIN {PRE}AdminGroups ag ON ama.nGroup = ag.nGId
											LEFT JOIN {PRE}Admin a ON a.nGroupId = ag.nGId
												 WHERE a.nId = '".$adminId."' AND am.vActive = '1' AND am.nSubParent = '".$subCatId."' AND ama.vAccess != 'N'
											AND am.nCategory = '".$catId."' ORDER BY am.nPosition,am.vModuleTitle");
		
		if ($adminMenu->num_rows() > 0)
		{
			foreach ($adminMenu->result() as $key=>$row)
			{
				$adminMenuList[] = $row;
			}//end for each
		} //end if
		return ($adminMenuList);
	}//end function


	function get_admin_access_list($adminId,$catId,$fileName = "")
	{
    if($fileName != "")
      $extrachk =  " AND am.vFileName = '".$fileName ."' ";

		$adminMenu = $this->db->query("SELECT am.*,ama.vAccess FROM {PRE}Admin_Modules am
											LEFT JOIN {PRE}Admin_Module_Access ama ON am.nMId = ama.nModule
												 LEFT JOIN {PRE}AdminGroups ag ON ama.nGroup = ag.nGId
											LEFT JOIN {PRE}Admin a ON a.nGroupId = ag.nGId
												 WHERE a.nId = '".$adminId."' AND am.vActive = '1' 
											AND am.nCategory = '".$catId."' AND ama.vAccess != 'N' ". $extrachk ." 
												ORDER BY am.nSubParent DESC,am.nPosition,am.vModuleTitle");
/*
	function get_admin_access_list($adminId,$catId,$cntrl)
	{
		$adminMenu = $this->db->query("SELECT am.*,ama.vAccess FROM {PRE}Admin_Modules am
											LEFT JOIN {PRE}Admin_Module_Access ama ON am.nMId = ama.nModule
												 LEFT JOIN {PRE}AdminGroups ag ON ama.nGroup = ag.nGId
											LEFT JOIN {PRE}Admin a ON a.nGroupId = ag.nGId
												 WHERE a.nId = '".$adminId."' AND am.vActive = '1' 
											AND am.nCategory = '".$catId."' AND ama.vAccess != 'N'
													AND am.vFileName = '".$cntrl."'
												ORDER BY am.nPosition,am.vModuleTitle");
	*/	
		if ($adminMenu->num_rows() > 0)
		{
			$adminMenuList = $adminMenu->row();
		} //end if
		
		return ($adminMenuList);
	}//end function
	
	function get_admin_access_list2($adminId,$catId,$cntrl)
	{
		$adminMenu = $this->db->query("SELECT am.*,ama.vAccess FROM {PRE}Admin_Modules am
											LEFT JOIN {PRE}Admin_Module_Access ama ON am.nMId = ama.nModule
												 LEFT JOIN {PRE}AdminGroups ag ON ama.nGroup = ag.nGId
											LEFT JOIN {PRE}Admin a ON a.nGroupId = ag.nGId
												 WHERE a.nId = '".$adminId."' AND am.vActive = '1' 
											AND am.nCategory = '".$catId."' AND ama.vAccess != 'N'
												AND am.vFileName = '".$cntrl."'
												ORDER BY am.nPosition,am.vModuleTitle");
	
		if ($adminMenu->num_rows() > 0)
		{
			$adminMenuList = $adminMenu->row();
		} //end if
		
		return ($adminMenuList);
	}//end function



  function get_account_period_date($dDate)
  {
    $adminMenu = $this->db->query("SELECT * FROM {PRE}AccountPeriodDetails WHERE '". $dDate ."' BETWEEN dSDate AND dEDate AND vActive='O'");
    if ($adminMenu->num_rows() > 0)
    {
      $adminMenuList = 1;
    } //end if
    else{
      $adminMenuList = 0;
    }
    return ($adminMenuList);
  }//end function



  function get_account_period()
  {
    $adminMenu = $this->db->query("SELECT * FROM {PRE}AccountPeriod WHERE NOW() BETWEEN dFDate AND dTDate AND vStatus='O'");
    if ($adminMenu->num_rows() > 0)
    {
      $adminMenuList = '1';
    } //end if
    return ($adminMenuList);
  }//end function

	//fuction to show corresponding messages
	function successMsg($val)
	{
		$successadd     = SUCCESSADD;
		$successupdate  = SUCCESSUPDATE;
		$successdel     = SUCCESSDELETE;
		$mailsent       = SUCCESSMAIL;
		$successapprove = SUCCESSAPPROVE;
		$forgotsucc		= FORGOTSUCCESS;
		switch ($val)
		{
			case "add_suc":
				$showMsg = '<code2>'.$successadd.'</code2>';
			break;
			
			case "edit_suc":
				$showMsg = '<code2>'.$successupdate.'</code2>';
			break;
			
			case "del_suc":
				$showMsg = '<code2>'.$successdel.'</code2>';
			break;
			
			case "mail_suc":
				$showMsg = '<code2>'.$mailsent.'</code2>';
			break;

			case "forgotSucess":
				$showMsg = '<code2>'.$forgotsucc.'</code2>';
			break;
			
			case "approve_suc":
				$showMsg = '<code2>'.$successapprov.'</code2>';
			break;
			
			case "error_msg":
				$showMsg = '<code2>Count less.</code2>';
			break;
			
			default:
				$showMsg = '';
			break;
		}//end switch
		return $showMsg;
	}//end function
	
	function getThumbName($picName) 
	{
		@list($name,$ext) = @split("[.]",$picName);
		$thumbName = preg_replace("/\.([a-zA-Z]{3,4})$/","_thumb.".$ext,$picName);
		return $thumbName;
	}//end function
	
	function getThumbName2($picName) 
	{
		@list($name,$ext) = @split("[.]",$picName);
		$thumbName = preg_replace("/\.([a-zA-Z]{3,4})$/","_thumb2.".$ext,$picName);
		return $thumbName;
	}//end function
	
	function getThumbName3($picName) 
	{
		@list($name,$ext) = @split("[.]",$picName);
		$thumbName = preg_replace("/\.([a-zA-Z]{3,4})$/","_thumb3".$ext,$picName);
		return $thumbName;
	}//end function
	
	//fucntion for display file path nmae
	function ClientFilePathName($PhpSelf)
	{
		$fpath=@split("/",$PhpSelf);
		$fpath=$fpath[count($fpath)-3].'/'.$fpath[count($fpath)-2];
		return ($fpath);
	}//end function

    function ClientFilePathName1($PhpSelf)
    {
        $fpath = @split("/",$PhpSelf);
        $fpath = $fpath[count($fpath)-4].'/'.$fpath[count($fpath)-3];
        return ($fpath);
    }//end function

    function ClientFilePathName2($PhpSelf)
    {
        $fpath = @split("/",$PhpSelf);
        $fpath = $fpath[count($fpath)-5].'/'.$fpath[count($fpath)-4];
        return ($fpath);
    }//end function

	//function for date format
	function dateFormat($input_date, $input_format, $output_format)
	{
		preg_match("/^([\w]*)/i", $input_date, $regs);
		$sep = substr($input_date, strlen($regs[0]), 1);
		$label = explode($sep, $input_format);
		$value = explode($sep, $input_date);
		$array_date = array_combine($label, $value);
		if (in_array('Y', $label)) {
			$year = $array_date['Y'];
		} elseif (in_array('y', $label)) {
			$year = $year = $array_date['y'];
		} else {
			return false;
		}
	 
		$output_date = date($output_format, mktime(0, 0, 0, $array_date['m'], $array_date['d'], $year));
		return $output_date;
	}//end function
	
	//function for fetch value from given table
	function get_field_name($tableName,$tblId,$extraCond)
	{
	
		$tabCount = $this->db->query("SELECT ".$tblId." FROM {PRE}".$tableName.$extraCond);
		$rowValue = $tabCount->row_array();
		return ($rowValue[$tblId]);
	}//end function

 	//Pagination
    function _paging($count, $target,$queryString)
    {
		$this->p = new pagination();
        $this->_limit = $this->LookUpDisplay('PerPage');
        $this->_page = 1;
		$_GET['p']   = $this->uri->segment(4);
		
        if(isset($_GET['p']))
        {
            $this->_page = intval($_GET['p']);

        }
        if((!$this->_page)  || (is_numeric($this->_page) == false) || $this->_page<=0 || $this->_page > ceil($count/$this->_limit) )
        {
            $this->_page = 1; //default
        }
        // counting the offset
        $this->_offset = ($this->_page - 1) * $this->_limit;
        $this->p->items($count);
        $this->p->limit($this->_limit);
        $this->p->parameterName(base_url().'index.php/'.$queryString);
        //$this->p->adjacents(1);
        $this->p->currentPage($this->_page);
        $this->p->nextIcon('&#9658;');
        $this->p->prevIcon('&#9668;');
        $this->p->target($target);
    }//end pagination function
	
	//function for export as xl
	function exportAsXl($userArray,$userValueArray,$fileName)
	{
		require_once 'static/classes/mid_excel.class.php';
		$mid_excel = new MID_SQLPARAExel;
		$mid_excel->mid_sqlparaexcel($userArray,$userValueArray,$fileName);
	}//end function
	

	//function for export as word
	function exportAsExcel_H($valueArray,$fileName)
	{
		header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT");
		header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		header ( "Pragma: no-cache" );
		header ( "Content-type: application/octet-stream; name=".$fileName.'.xls');
		header ( "Content-Disposition: attachment; filename=".$fileName.'.xls'); 
		header ( "Content-Description: MID Gera word" );
		echo($valueArray);
	}//end function
	

	//function for export as word
	function exportAsWord($valueArray,$fileName)
	{
		header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT");
		header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		header ( "Pragma: no-cache" );
		header ( "Content-type: application/octet-stream; name=".$fileName.'.doc');
		header ( "Content-Disposition: attachment; filename=".$fileName.'.doc'); 
		header ( "Content-Description: MID Gera word" );
		echo($valueArray);
	}//end function
	
	//function for export as pdf
	function exportAsPdf($valueArray,$fileName,$pdfTitle)
	{
		// don't want any warnings turning up in the pdf code if the server is set to 'anal' mode.
		error_reporting(7);
		require_once 'static/classes/class.ezpdf.php';
		
		$pdf = new Cezpdf('a4','portrait');

		$pdf -> ezSetMargins(50,70,50,50);
		
		// put a border round all the pages
		$all = $pdf->openObject();
		$pdf->rectangle(20,40,558,782);
		$pdf->closeObject();
		// note that object can be told to appear on just odd or even pages by changing 'all' to 'odd'
		// or 'even'.
		$pdf->addObject($all,'all');
		
		$pdf->ezSetDy(-100);

		// select a font
		$pdf->selectFont('static/classes/fonts/Helvetica');
		
		$pdf->ezText($this->get_site_title()."\n",30,array('justification'=>'centre'));
		$pdf->ezText($pdfTitle."\n",20,array('justification'=>'centre'));
		$pdf->ezText("Developed by Qbicblack Technologies \nhttp://www.qbicblack.com/",18,array('justification'=>'centre',
							'url'=>'http://www.qbicblack.com/'));

		$pdf->ezSetDy(-150);
		// modified to use the local file if it can
		
		/*if (file_exists('static/images/logo.png'))
		{
		  $pdf->addJpegFromFile('static/images/logo.png',199,$pdf->y-100,200,0);
		}//end if*/
		
		//-----------------------------------------------------------
		// load up the document content	
		$data = $valueArray;
		
		$pdf->ezNewPage();
		
		$pdf->ezStartPageNumbers(500,30,10,'','',1);
		
		$size=12;
		$height = $pdf->getFontHeight($size);
		
		$pdf->ezTable($data);
		
		if ($d)
		{
		  $pdfcode = $pdf->ezOutput(1);
		}//end if
		else 
		{
		  $pdf->ezStream();
		}//end else
	}//end function

	function LookUpDisplay($vName)
	{
		$siteName = $this->db->query("SELECT vDes FROM {PRE}LookUp WHERE vName='".$vName."'");
		$rowName = $siteName->row();
		return $rowName->vDes;
	}//end function
	
	function cmsArray()
	{
		$cmsArrayList = array("Home" => "Home", "About" => "About Us","Contact" => "Contact Us","Privacy" => "Privacy Policy","Terms" => "Terms of Service");
		return ($cmsArrayList);
	}//end function
	
	function send_mail($to,$toName,$from,$fromName,$subject,$mailBody,$html=true)
	{
		//send mail 
		$config = array (
						 'mailtype' => 'html',
						 'charset'  => 'utf-8',
						 'priority' => '1'
					  );
		
		$this->load->library('email', $config);
		
		$config['protocol'] = 'sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		
		$this->email->initialize($config);

		$this->email->set_newline("\r\n");
		$this->email->from($from, $fromName);
		$this->email->to($to);
					
		$this->email->subject($subject);
		$this->email->message($mailBody);
					
       /* if($this->email->send())
        {
            echo 'Your email was sent, successfully.';
        }//end if
 
        else
        {
            show_error($this->email->print_debugger());
        }//end else*/
		
	}//end funciton
	
	function get_list_of_Doc($docId=0)
	{
		$color = '#F6F6F6';
		$moduleArr = array();
		$adModuleList = $this->db->query("SELECT * from {PRE}Documents where vActive='1'");
			if ($adModuleList->num_rows() > 0)
			{
				echo '<table width="100%" cellspacing="1" cellpadding="5" bgcolor="#000000">';
				foreach ($adModuleList->result() as $key=>$row)
				{
								if(in_array($row->nId,$this->ddlDoc)) 
								{ 
									$checked = 'checked'; 
								}//end if
								else 
								{
									$checked = ''; 
								}//end else
					?>
					<tr bgcolor="<?php echo $bgcolor;?>">
                    	<td>
							<input type="checkbox" value="<?php echo $row->nId; ?>" name="ddlDoc[]" <?php echo $checked; ?> >&nbsp;<? echo $row->vTitle; ?>
						</td>
                    </tr>
	<?php
						$docId = $row->nId;	 	
					}//end foreach
				echo '</table>';
			}//end if
	}//end function
	
	function get_admin_topmenu($adminId)
	{
		$adminMenuList = array();
		// amc = admin_module_category
		// am = admin_modules
		// ama = admin_module_access
		// au = admin_users
		$adminMenu = $this->db->query("SELECT amc.*, am.* FROM {PRE}Admin_Module_Category amc,
												{PRE}Admin_Modules am,
											{PRE}Admin a, {PRE}Admin_Module_Access ama
												 WHERE a.nGroupId = ama.nGroup
											AND am.nCategory = amc.nCId
												 AND ama.nModule = am.nMId
											AND a.nId = '".$adminId."' AND am.vActive ='1'
												GROUP BY amc.nCId ORDER BY amc.vTitle, am.vModuleTitle");
		
		if ($adminMenu->num_rows() > 0)
		{
			foreach ($adminMenu->result() as $key=>$row)
			{
				$adminMenuList[] = $row;
			}//end for each
		} //end if
		return ($adminMenuList);
	}//end function
	
	function maxPos($tableName)
	{
		$sql1 = $this->db->query("SELECT MAX(nPosition) as maxNo from {PRE}".$tableName);
		$rw1 = $sql1->row();
		$maxorder1 = $rw1->maxNo+1;
		
		return ($maxorder1);
	}//end function
	
	function OrderUp($table,$oldId,$oldPosition,$PositionfieldName,$IdfieldName)
	{
		$where = $PositionfieldName.' > '.$oldPosition;
   		$this->db->where($where);
		$this->db->select_min($PositionfieldName,minVal);
		$this->db->from($table);
		$query = $this->db->get();
		$row = $query->row(); 
		$newPosition= $row->minVal;
		
		
		$where = $PositionfieldName.' = '.$newPosition;
   		$this->db->where($where);
		$this->db->select($IdfieldName);
		$this->db->from($table);
		$query = $this->db->get();
		$row = $query->row(); 
		$newId= $row->$IdfieldName;
		
		$data = array(
			$PositionfieldName => $newPosition
		);
		
		$this->db->where($IdfieldName, $oldId);
		$this->db->update($table, $data);
		
		
		$data = array(
			$PositionfieldName => $oldPosition
		);
		
		$this->db->where($IdfieldName,$newId);
		$this->db->update($table, $data);
	}//end function
	
	function OrderDown($table,$oldId,$oldPosition,$PositionfieldName,$IdfieldName)
	{
		$where = $PositionfieldName.' < '.$oldPosition;
   		$this->db->where($where);
		$this->db->select_max($PositionfieldName,maximum);
		$this->db->from($table);
		$query = $this->db->get();
		$row = $query->row(); 
		$newPosition= $row->maximum;
		
		$where = $PositionfieldName.' = '.$newPosition;
   		$this->db->where($where);
		$this->db->select($IdfieldName);
		$this->db->from($table);
		$query = $this->db->get();
		$row = $query->row(); 
		$newId= $row->$IdfieldName;
		
		$data = array(
			$PositionfieldName => $newPosition
		);
		
		$this->db->where($IdfieldName, $oldId);
		$this->db->update($table, $data);
		
		
		$data = array(
			$PositionfieldName => $oldPosition
		);
		
		$this->db->where($IdfieldName,$newId);
		$this->db->update($table, $data);
	}//end function 
	
	function searchKeyword($varName)
	{
	    // echo $varName;die();
		// for creating session
		$this->load->library('session');
		
		if($this->input->post($varName) != '')
		{
			$txtSearchVal = trim($this->input->post($varName));
			$this->session->set_userdata($varName, $txtSearchVal);
		}//end if
		else
		{
			$txtSearchVal = $this->session->userdata($varName);
		}//end else
		
		$txtSearchVal           = htmlentities(mysql_real_escape_string($txtSearchVal));
		return ($txtSearchVal);
	}//end function
	
	function searchKeyword3($varName)
	{
	    // echo $varName;die();
		// for creating session
		$this->load->library('session');
		
		if($this->input->post($varName) != '')
		{
			$txtSearchVal = trim($this->input->post($varName));
			$this->session->set_userdata($varName, $txtSearchVal);
		}//end if
		else
		{
			$txtSearchVal = $this->session->userdata($varName);
		}//end else
		
		$txtSearchVal           = htmlentities(mysql_real_escape_string($txtSearchVal));
		return ($txtSearchVal);
	}//end function
	 
	function _paging1($count, $target,$queryString)
    {
		$this->p = new pagination();
        $this->_limit = $this->LookUpDisplay('PerPage');
        $this->_page = 1;
		$_GET['p']   = $this->uri->segment(6);
		
        if(isset($_GET['p']))
        {
            $this->_page = intval($_GET['p']);

        }
        if((!$this->_page)  || (is_numeric($this->_page) == false) || $this->_page<=0 || $this->_page > ceil($count/$this->_limit) )
        {
            $this->_page = 1; //default
        }
        // counting the offset
        $this->_offset = ($this->_page - 1) * $this->_limit;
        $this->p->items($count);
        $this->p->limit($this->_limit);
        $this->p->parameterName(base_url().'index.php/'.$queryString);
        //$this->p->adjacents(1);
        $this->p->currentPage($this->_page);
        $this->p->nextIcon('&#9658;');
        $this->p->prevIcon('&#9668;');
        $this->p->target($target);
    }//end pagination function
	
	function _createThumbnail($fileName,$width,$height,$folder) 
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = './upload/' .$folder.'/'.$fileName;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
		$this->load->library('image_lib', $config);
		$this->image_lib->initialize($config); 
		if ( ! $this->image_lib->resize())
        {
            echo $this->image_lib->display_errors();
        }
		return ($image['file_name']);
	}//end function
	
	function resizeImg($imgPath, $maxWidth, $maxHeight, $directOutput = true, $quality = 90, $verbose,$imageType)
	{
	   // get image size infos (0 width and 1 height,
	   //     2 is (1 = GIF, 2 = JPG, 3 = PNG)
	  
		 $size = getimagesize($imgPath);
	
	   // break and return false if failed to read image infos
		 if(!$size){
		   if($verbose && !$directOutput)echo "<br />Not able to read image infos.<br />";
		   return false;
		 }//end if
	
	   // relation: width/height
		 $relation = $size[0]/$size[1];
	   // maximal size (if parameter == false, no resizing will be made)
		 $maxSize = array($maxWidth?$maxWidth:$size[0],$maxHeight?$maxHeight:$size[1]);
	   // declaring array for new size (initial value = original size)
		 $newSize = $size;
	   // width/height relation
		 $relation = array($size[1]/$size[0], $size[0]/$size[1]);
	
	
		if(($newSize[0] > $maxWidth))
		{
			$newSize[0]=$maxSize[0];
			$newSize[1]=$newSize[0]*$relation[0];
		}//end if
		
		
		if(($newSize[1] > $maxHeight))
		{
			$newSize[1]=$maxSize[1];
			$newSize[0]=$newSize[1]*$relation[1];
		}//end if
		
		
		 // create image
	
		   switch($size[2])
		   {
			 case 1:
			   if(function_exists("imagecreatefromgif"))
			   {
				 $originalImage = imagecreatefromgif($imgPath);
			   }else{
				 if($verbose && !$directOutput)echo "<br />No GIF support in this php installation, sorry.<br />";
				 return false;
			   }
			   break;
			 case 2: $originalImage = imagecreatefromjpeg($imgPath); break;
			 case 3: $originalImage = imagecreatefrompng($imgPath); break;
			 default:
			   if($verbose && !$directOutput)echo "<br />No valid image type.<br />";
			   return false;
		   }//end switch
	
		 // create new image
		   $resizedImage = imagecreatetruecolor($newSize[0], $newSize[1]); 

	 		 //checking transparency start here
     		 if ( ($size[2] == 1) || ($size[2] == 3) )
			 {
				 $trnprt_indx = imagecolortransparent($originalImage);
				 // If we have a specific transparent color
				 if ($trnprt_indx >= 0)
				 {
					// Get the original image's transparent color's RGB values
					$trnprt_color    = @imagecolorsforindex($originalImage, $trnprt_indx);
					// Allocate the same color in the new image resource
					$trnprt_indx    = @imagecolorallocate($resizedImage, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
					// Completely fill the background of the new image with allocated color.
					@imagefill($resizedImage, 0, 0, $trnprt_indx);
					// Set the background color for new image to transparent
					imagecolortransparent($resizedImage, $trnprt_indx);
				 }//end if
				 // Always make a transparent background color for PNGs that don't have one allocated already
				 else if ($size[2] == 3)
				  {
					 // Turn off transparency blending (temporarily)
					 imagealphablending($resizedImage, false);
					 
					 // Create a new transparent color for image
					 $color = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
					 
					 // Completely fill the background of the new image with allocated color.
					 imagefill($resizedImage, 0, 0, $color);
					 
					 // Restore transparency blending
					 imagesavealpha($resizedImage, true);
				 }//end else if
			 }//end if transparency check
            //checking transparency end here

		   imagecopyresampled($resizedImage, $originalImage,0, 0, 0, 0,$newSize[0], $newSize[1], $size[0], $size[1]);
	
		$rz=$imgPath;
	
		 // output or save
		   if($directOutput)
			{
			 imagejpeg($resizedImage);
			 }
			 else
			{
				
				 $rz=preg_replace("/\.([a-zA-Z]{3,4})$/","".$imageType.".jpg",$imgPath);
			 imagejpeg($resizedImage, $rz, $quality);
			 }
		 // return true if successfull
		   return $rz;
	}//end function
		
	function getAdminId()
	{
		$siteName = $this->db->query("SELECT nId FROM {PRE}Admin WHERE nId='1'");
		$rowName = $siteName->row();
		return $rowName->nId;
	}//end function 
	
	//remove \n from descrition
   function removeNewline($content)
   {
	  // $findArray    = array("\\n","n","r","{","}");
	   $findArray    = array("\\r", "\\n");
	   $replaceArray = array('');
	   
	   return(str_replace($findArray, $replaceArray, $content));
   }//end function

	//function to check admin module access in all pages
	function pageAccess()
	{
		$catArray         = array();
		$adminId 		  = $this->get_set_sessions(); 
		$check_menu_value = $this->get_admin_menu($adminId);
		
		foreach($check_menu_value as $val)
		{
			$catArray[] = $val->nCId;
		}//end foreach

		if($this->ClientFilePathName($_SERVER['PHP_SELF']) != 'work/index.php')
		{
			if(!in_array($this->uri->segment(3),$catArray))
			{
				//$this->session->sess_destroy();
				//redirect(site_url("login"));
			}//end if
		}//end if
	}//end fucntion
	
	function getLang()
	{
		switch($this->session->userdata('lang'))
		{
			 case 'E':    
			 	$this->lang = 'E';
			 break;
			 
			 case 'A':    
			 	$this->lang = 'A';
			 break;
			 
			 default:    
			 	$this->lang = 'E';
			break;			   			   
		}//end switch
		return ($this->lang);
	}//end function

	function searchKeyword33($varName)
	{
	    // echo $varName;die();
		// for creating session
		$this->load->library('session');
		
		if($this->uri->segment($varName) != '')
		{
			$txtSearchVal = trim($this->uri->segment($varName));
			$this->session->set_userdata($varName, $txtSearchVal);
		}//end if
		else
		{
			$txtSearchVal = $this->session->userdata($varName);
		}//end else
		
		$txtSearchVal           = htmlentities(mysql_real_escape_string($txtSearchVal));
		
		return ($txtSearchVal);
	}//end function
	
	function getCompanylogo()
	{
		$siteName = $this->db->query("SELECT vImg FROM {PRE}Company");
		$rowName = $siteName->row();
		return $rowName->vImg;
	}//end function
	
	function getCompanyName()
	{
		$siteName = $this->db->query("SELECT vTitle FROM {PRE}Company");
		$rowName = $siteName->row();
		return $rowName->vTitle;
	}//end function

    function getCompanyAddress()
	{
		$siteName = $this->db->query("SELECT vAddress FROM {PRE}Company");
		$rowName = $siteName->row();
		return $rowName->vAddress;
	}//end function

    function getCompanyLocation()
	{
		$siteName = $this->db->query("SELECT nLocation FROM {PRE}Company");
		$rowName = $siteName->row();
		return $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$rowName->nLocation."'");
	}//end function
	
 	function getCompanyCurr()
	{
		$fetchTitle 	  = $this->db->query("SELECT nCurrency FROM {PRE}Company");
		$showVal	      = $fetchTitle->row();
		return $showVal->nCurrency;
	}//end function

  function getCompanyCurrDecimal()
  {
    $siteName = $this->db->query("SELECT vDecimal FROM {PRE}Currency WHERE nId = (SELECT nCurrency FROM {PRE}Company)");
    $rowName = $siteName->row();
    return $rowName->vDecimal;
  }//end function


	//fucntion to get currency decimal digits
	function currencyDecimal($id)
	{
		$siteName = $this->db->query("SELECT vDecimal FROM {PRE}Currency WHERE nId = '".$id."'");
		$rowName = $siteName->row();
		return $rowName->vDecimal;
	}//end fucntion
	
	//fucntion to get currency coin
	function currencyCoin($id)
	{
		$siteName = $this->db->query("SELECT vCoin FROM {PRE}Currency WHERE nId = '".$id."'");
		$rowName = $siteName->row();
		return $rowName->vCoin;
	}//end fucntion
	
	//fucntion to get currency code
	function currencyCode($id)
	{
		$siteName = $this->db->query("SELECT vCurrcode FROM {PRE}Currency WHERE nId = '".$id."'");
		$rowName = $siteName->row();
		return $rowName->vCurrcode;
	}//end fucntion
	
   	//get location name
	function getLocationName($locArray)
	{
		$locName         = array();
		//fetch location name
		foreach($locArray as $val)
		{
			$locName[]   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$val."'");	
		}//end foreach
		
		return(@join(",",$locName));
	}//end function
	
		//function for export as pdf single page
	function exportAsReqPdf($valueArray,$fileName,$type,$pagesize,$email='N')
	{
    ob_clean(); //angela 06/24/14 code added to remove error:
    // TCPDF ERROR: Some data has already been output, can't send PDF file 
		 // get the HTML
		ob_start();
		require_once 'static/pdf/pdf1.html';
		$content = ob_get_clean();
	
		// convert in PDF
		require_once 'static/plugins/pdf/html2pdf.class.php';
		try
		{
//			$html2pdf = new HTML2PDF($type, $pagesize, 'fr');
			$html2pdf = new HTML2PDF($type, $pagesize, 'fr');
      //$html2pdf->pdf->SetMargins(20,18);
			//$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
      if($email =='Y')
        $pdfdoc = $html2pdf->Output($fileName.'.pdf','S');
      else
        $html2pdf->Output($fileName.'.pdf','D');
    }
    catch(HTML2PDF_exception $e) {
      echo $e;
      exit;
    }

    if($email == 'Y'){

      $location = $this->input->post('ddlLocation');

      $to = $this->input->post("txtTo");//"alvaresangela@gmail.com";
      $subject = $this->input->post("txtSubject");;
      $message = $this->input->post("txtMessage");

      $attachment = chunk_split(base64_encode($pdfdoc));
      $eol = PHP_EOL; //' \r\n';


      $headers = "From:" . trim($this->input->post("txtFrom")) ." \r\n";
      $headers .= "Bcc:" . trim($this->input->post("txtBcc")) ." \r\n";

      $headers .= "MIME-Version: 1.0".$eol;
      $separator = md5(time());
      $headers .= "Content-Type: multipart/mixed;boundary=\"".$separator."\"".$eol.$eol;
      $headers .= "Content-Transfer-Encoding: 7bit".$eol;
      $headers .= "This is a MIME encoded message.".$eol.$eol;

      $headers .= "--".$separator.$eol;
      $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
      $headers .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
      $headers .= $message.$eol.$eol;

      $headers .= "--".$separator.$eol;
      $headers .= "Content-Type: application/octet-stream; name=\"".$fileName.".pdf\"".$eol;
      $headers .= "Content-Transfer-Encoding: base64".$eol;
      $headers .= "Content-Disposition: attachment".$eol.$eol;
      $headers .= $attachment.$eol.$eol;
      $headers .= "--".$separator."--";

      $retval = mail ($to,$subject,'',$headers);

      return;
    }//endif 

  }//end function

	function downloads($fileName)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->helper('download');

		$data 		= file_get_contents("static/pdf/".$fileName.'.html'); // Read the file's contents
		
		force_download($fileName.'.html', $data);
		@unlink("static/pdf/".$fileName.'.html');
		//redirect(site_url("events/details/".$Id));	
	}//end function
	
	function get_user_menu($adminId)
	{
		$userMenuList = array();
		
		//checking lang
		switch($this->session->userdata('lang'))
		{
			case "eng":
				$sTitle  = 'amc.vTitle, am.vModuleTitle';
			break;
			
			case "ar":
				$sTitle  = 'amc.vTitle2, am.vModuleTitle2';
			break;
		}//end switch

		// amc = admin_module_category
		// am = admin_modules
		// ama = admin_module_access
		// au = admin_users
		$userMenu = $this->db->query("SELECT amc.*, am.* FROM {PRE}Admin_Module_Category amc,
												{PRE}Admin_Modules am,
											{PRE}UserProfile a, {PRE}User_Module_Access ama
												 WHERE a.nId = ama.nUserProfile
											AND am.nCategory = amc.nCId
												 AND ama.nModule = am.nMId
											AND a.nId = '".$adminId."' AND am.vActive ='1' AND am.nSubParent = 0
												GROUP BY amc.nCId ORDER BY amc.nPosition, ".$sTitle);
		
		if ($userMenu->num_rows() > 0)
		{
			foreach ($userMenu->result() as $key=>$row)
			{
				$userMenuList[] = $row;
			}//end for each
		} //end if
		return ($userMenuList);
	}//end function
	
	//get url
	function getPHPSelf($val)
	{
		$url = @split("index.php",$val);
		$url = substr($url[1],1,strlen($url[1]));
		$this->session->set_userdata('backURL',$url);
	}//end function

    function getPHPSelf2($val)
    {
        $url = @split("index.php",$val);
        $url = substr($url[1],1,strlen($url[1]));
        $this->session->set_userdata('backURL2',$url);
    }//end function

	//locaiton for logged user
	function getLocLogged()
	{
		$userLoc  = $this->db->query("SELECT nLocation FROM {PRE}Admin WHERE nId = 
											'".$this->session->userdata('AbsAdmin')."'");
		if($userLoc->num_rows() > 0)
		{
			$locId = $userLoc->row();
			$locId = $locId->nLocation;
		}//end if
		else
		{
			$locId = 0;
		}//end else
		
		return ($locId);
	}//end function

    function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'Zero',
            1                   => 'One',
            2                   => 'Two',
            3                   => 'Three',
            4                   => 'Four',
            5                   => 'Five',
            6                   => 'Six',
            7                   => 'Seven',
            8                   => 'Eight',
            9                   => 'Nine',
            10                  => 'Ten',
            11                  => 'Eleven',
            12                  => 'Twelve',
            13                  => 'Thirteen',
            14                  => 'Fourteen',
            15                  => 'Fifteen',
            16                  => 'Sixteen',
            17                  => 'Seventeen',
            18                  => 'Eighteen',
            19                  => 'Nineteen',
            20                  => 'Twenty',
            30                  => 'Thirty',
            40                  => 'Fourty',
            50                  => 'Fifty',
            60                  => 'Sixty',
            70                  => 'Seventy',
            80                  => 'Eighty',
            90                  => 'Ninety',
            100                 => 'Hundred',
            1000                => 'Thousand',
            1000000             => 'Million',
            1000000000          => 'Billion',
            1000000000000       => 'Trillion',
            1000000000000000    => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,E_USER_WARNING);
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

	//check location
	function getUserLoc()
	{
		if($this->session->userdata('AbsAdmin') != 1)
			$locCheck = " AND nId = '".$this->session->userdata('AUserLoc')."'";
		else
			$locCheck = '';
			
		return ($locCheck);
	}//end function
	
	//get selected location div id
	function getUserLocDiv($cond)
	{
		if($this->session->userdata('AbsAdmin') != 1):
			$divId    = $this->get_field_name('Location','nDivision'," 
										WHERE nId = '".$this->session->userdata('AUserLoc')."'");
			$divCheck = " ".$cond." = '".$divId."'";
		else:
			$locCheck = '';
		endif;
			
		return ($divCheck);
	}//end function

	/*
	//get selected location div id
	function getUserLocCond($cond)
	{
		if($this->session->userdata('AbsAdmin') != 1):
			$divCheck = " ".$cond." = '".$this->session->userdata('AUserLoc')."'";
		else:
			$locCheck = '';
		endif;
			
		return ($divCheck);
	}//end function
*/
    function convert_decimal_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'Zero',
            1                   => 'One',
            2                   => 'Two',
            3                   => 'Three',
            4                   => 'Four',
            5                   => 'Five',
            6                   => 'Six',
            7                   => 'Seven',
            8                   => 'Eight',
            9                   => 'Nine',
            10                  => 'Ten',
            11                  => 'Eleven',
            12                  => 'Twelve',
            13                  => 'Thirteen',
            14                  => 'Fourteen',
            15                  => 'Fifteen',
            16                  => 'Sixteen',
            17                  => 'Seventeen',
            18                  => 'Eighteen',
            19                  => 'Nineteen',
            20                  => 'Twenty',
            30                  => 'Thirty',
            40                  => 'Fourty',
            50                  => 'Fifty',
            60                  => 'Sixty',
            70                  => 'Seventy',
            80                  => 'Eighty',
            90                  => 'Ninety',
            100                 => 'Hundred',
            1000                => 'Thousand',
            1000000             => 'Million',
            1000000000          => 'Billion',
            1000000000000       => 'Trillion',
            1000000000000000    => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,E_USER_WARNING);
            return false;
        }
/*
        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }
*/
        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
/*
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }
*/
        if (null !== $fraction && is_numeric($fraction)) {
           //$string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    function insertPageBreak($str,$n=10){
      $length = strlen($str);
      $brk = $n;
      $k = $n;
      $newlen = 0;
      for($k = 0; $k<$length; $k=$k+$n){

        $newstr = substr($str,$brk);
        $str = substr_replace($str ,"<br />",$brk);
        $str .= $newstr;
        $brk = $brk + $n + 6;
        //  $newlen = $newlen + 4;
      }
      return $str;
    }//end function


////////////////////////////////////////////////////////////////////////////////////////////
//////////////////       Common functions for Change Of Location      //////////////////////
////////////////////////////////////////////////////////////////////////////////////////////

// used in sales closing drAcc
    function getAllAccounts()//$division = '', $location = '')
    {

      $location = $this->input->post('ddlLocation');
      $division = $this->input->post('ddlDivision');
      if($division == ''){
        $division =  $this->get_field_name('Location','nDivision'," WHERE nId='".$location."'");
      }
      $typeGroupRow    = array();
      $vendorGroupRow  = array();
      $AgentGroupRow   = array();
      $OtherGroupRow   = array();
      $accheadGroupRow = array();

      $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY vName ASC");
      if ($typeGroup->num_rows() > 0)
      {
        foreach ($typeGroup->result() as $key=>$row)
        {
          $locArray = explode(',',$row->vLocation);
          if(in_array($location, $locArray))
            $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;
        }//end for each
      }//end if
      $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY vName ASC");
      if ($vendorGroup->num_rows() > 0)
      {
        foreach ($vendorGroup->result() as $key=>$row)
        {
          $locArray = explode(',',$row->vLocation);
          if(in_array($location, $locArray))
            $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;
        }//end for each
      }//end if
      $resultArray = array_merge($typeGroupRow,$vendorGroupRow);

      $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY vName ASC");
      if ($AgentGroup->num_rows() > 0)
      {
        foreach ($AgentGroup->result() as $key=>$row)
        {
          $locArray = explode(',',$row->vLocations);
          if(in_array($location, $locArray))
            $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;
        }//end for each
      }//end if
      $resultArray = array_merge($AgentGroupRow,$resultArray);

      $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other ORDER BY vName ASC");
      if ($OtherGroup->num_rows() > 0)
      {
        foreach ($OtherGroup->result() as $key=>$row)
        {
          $locArray = explode(',',$row->vLocation);
          if(in_array($location, $locArray))
            $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;
        }//end for each
      }//end if
      $resultArray = array_merge($OtherGroupRow,$resultArray);
      $accheadGroup = $this->db->query("SELECT aha.nId,aha.vTitle,aha.vACode, ahc.vDivision, ahc.vLocation FROM {PRE}AccountHeadAccounts aha
          INNER JOIN {PRE}AccountHeadControls ahc ON aha.nId = ahc.nActHId
          WHERE ahc.vSubLedgerType NOT IN ( 'V','C','E','A') ORDER BY aha.vTitle ");

      if ($accheadGroup->num_rows() > 0)
      {
        $accheadGroupRow[0] = SELECT;
        foreach ($accheadGroup->result() as $key=>$row)
        {
          if(($row->vDivision == 0 && $row->vLocation == 0) || ($row->vDivision == 0 && $row->vLocation == $location) || ($row->vDivision == $division &&  $row->vLocation == 0 ) ||  ($row->vDivision == $division &&  $row->vLocation == $location))
            $accheadGroupRow[$row->nId.'-'.'A'] = $row->vTitle.' - '.$row->vACode;
        }//end for each
      }//end if

      else
      {
        $accheadGroupRow[0] = SELECT;
      }
      $resultArray = array_merge($accheadGroupRow,$resultArray);
      if(empty($resultArray)) $resultArray[0] = NOVALUE;
      natcasesort($resultArray);
      return ($resultArray);

    }//end function



    function getVendorMasterAccounts()
    {
      $division = $this->input->post('ddlDivision');
      $location = $this->input->post('ddlLocation');

      $accGroupRow = array();
      $accGroup    = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY vName ASC");
      if ($accGroup->num_rows() > 0)
      {
        foreach ($accGroup->result() as $key => $row)
        {
          $accGroupRow[0] = SELECT;
          $locArray = explode(',',$row->vLocation);
          if(in_array($location, $locArray))
            $accGroupRow[$row->nVId] = $row->vName. ' (' . $row->vCode . ' )';
        }//end for each
      }//end if
      else
      {
        $accGroupRow[0] = NOVALUE;
      }//end else
      if(empty($accGroupRow)) $accGroupRow[0] = NOVALUE;
//      else  $accGroupRow[0] = SELECT;

      return ($accGroupRow);
    }//end function

    function getCustomerMasterAccounts()
    {
      $division = $this->input->post('ddlDivision');
      $location = $this->input->post('ddlLocation');

      $typeGroupRow = array();
      $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY vName ASC");
      if ($typeGroup->num_rows() > 0)
      {
        $typeGroupRow[0] = SELECT;
        foreach ($typeGroup->result() as $key=>$row)
        {
          $locArray = explode(',',$row->vLocation);
          if(in_array($location, $locArray))
            $typeGroupRow[$row->nCId] = $row->vName.' - '.$row->vCode;
        }//end for each
      }//end if
      if(empty($typeGroupRow)) $typeGroupRow[0] = NOVALUE;
      return ($typeGroupRow);
    }//end function


    function getAgentAccounts()
    {
      $division = $this->input->post('ddlDivision');
      $location = $this->input->post('ddlLocation');

      $agentGroup    = $this->db->query("SELECT * FROM {PRE}Agent WHERE vActive = '1' ORDER BY nAId DESC");
      $agentGroupRow = array();

      if ($agentGroup->num_rows() > 0)
      {
        $agentGroupRow[0] = SELECT;
        foreach ($agentGroup->result() as $key => $row)
        {
          $locArray = explode(',',$row->vLocations);
          if(in_array($location, $locArray))
            $agentGroupRow[$row->nAId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');
        }//end for each
      } //end if
      else
      {
        $agentGroupRow[0] = NOVALUE;
      }//end else
      if(empty($agentGroupRow)) $agentGroupRow[0] = NOVALUE;
//      else $agentGroupRow[0] = SELECT;
      return ($agentGroupRow);
    }//end function

    function getIssuedBy()
    {
      $division = $this->input->post('ddlDivision');
      $location = $this->input->post('ddlLocation');

      $empGroup    = $this->db->query("SELECT nEId,vCode,vName, vLocations FROM {PRE}EmployeeMaster WHERE vActive = '1'
          ORDER BY vName ASC");
      $empGroupRow = array();

      if ($empGroup->num_rows() > 0)
      {
        $empGroupRow[0] = SELECT;
        foreach ($empGroup->result() as $key => $row)
        {
          $locArray = explode(',',$row->vLocations);
          if(in_array($location, $locArray))
          $empGroupRow[$row->nEId] = (stripslashes($row->vName).' ('.stripslashes($row->vCode).') ');
        }//end for each
      }//end if
      else
      {
        $empGroupRow[0] = NOVALUE;
      }//end else
      if(empty($empGroupRow)) $empGroupRow[0] = NOVALUE;
//      else $empGroupRow[0] = SELECT;
      return ($empGroupRow);
    }//end function

    function getIssuedByMultiLoc()
    {
      $division = $this->input->post('ddlDivision');
      $location = $this->input->post('ddlLocation');
      $location2 = $this->input->post('ddlLocation2');
      $location =  $this->get_field_name("Location","vCode"," WHERE nId =  " . $location . " ");
      $location2 =  $this->get_field_name("Location","vCode"," WHERE nId =  " . $location2 . " ");

      // get greater location id
      if($location > $location2)
      {
        $temp = $location;
        $location = $location2;
        $location2 = $temp;
      }

      //generate location array
      $multiLoc = array();
      $multiLocGroup = $this->db->query("SELECT nId from {PRE}Location 
                          WHERE vCode BETWEEN " . $location . " AND  " . $location2 . " AND nDivision = " . $division . " 
                          AND nId IN ".$this->session->userdata('accessLoc') ." ");
      if($multiLocGroup->num_rows() > 0)
      {
        foreach ($multiLocGroup->result() as $key => $row){
          $multiLoc[] =  $row->nId;
        } //en foreach
      }// end if

      $empGroup    = $this->db->query("SELECT nEId,vCode,vName, vLocations FROM {PRE}EmployeeMaster WHERE vActive = '1'
          ORDER BY vName ASC");
      $empGroupRow = array();

      if ($empGroup->num_rows() > 0)
      {
        $empGroupRow[0] = SELECT;
        foreach ($empGroup->result() as $key => $row)
        {
          $locArray = explode(',',$row->vLocations);
          if(count(array_intersect($multiLoc,$locArray)) > 0)
            $empGroupRow[$row->nEId] = (stripslashes($row->vName).' ('.stripslashes($row->vCode).') ');
        }//end for each
      }//end if
      else
      {
        $empGroupRow[0] = NOVALUE;
      }//end else
      if(empty($empGroupRow)) $empGroupRow[0] = NOVALUE;
//      else $empGroupRow[0] = SELECT;
      return ($empGroupRow);

    }//end function


  function getCashAccounts()
  {
    $division = $this->input->post('ddlDivision');
    $location = $this->input->post('ddlLocation');

    $typeGroupRow = array();
    $typeGroup    = $this->db->query("SELECT aca.vTitle,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType, ahc.vDivision, ahc.vLocation 
                      FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                      ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                      WHERE ah.vActive = '1' AND ahc.vSubSale = 'C' ORDER BY vTitle ASC");
    if ($typeGroup->num_rows() > 0)
    {
//      $typeGroupRow[0] = SELECT;
      foreach ($typeGroup->result() as $key=>$row)
      {
        if(($row->vDivision == 0 && $row->vLocation == 0) || ($row->vDivision == 0 && $row->vLocation == $location) || ($row->vDivision == $division &&  $row->vLocation == 0 ) ||  ($row->vDivision == $division &&  $row->vLocation == $location))
          $typeGroupRow[$row->ahaId] = $row->vTitle;
      }//end for each
    }//end if
    else
    {
      $typeGroupRow[0] = NOVALUE;
    }//end else
    if(empty($typeGroupRow)) $typeGroupRow[0] = NOVALUE;
    return ($typeGroupRow);
  }//end function

  function getBankAccounts()
  {
    $division = $this->input->post('ddlDivision');
    $location = $this->input->post('ddlLocation');

    $typeGroupRow = array();
    $typeGroup    = $this->db->query("SELECT aca.vTitle,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType, ahc.vDivision, ahc.vLocation 
                      FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                      ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                      WHERE ah.vActive = '1' AND ahc.vSubSale = 'B' ORDER BY vTitle ASC");
    if ($typeGroup->num_rows() > 0)
    {
//      $typeGroupRow[0] = SELECT;
      foreach ($typeGroup->result() as $key=>$row)
      {
        if(($row->vDivision == 0 && $row->vLocation == 0) || ($row->vDivision == 0 && $row->vLocation == $location) || ($row->vDivision == $division &&  $row->vLocation == 0 ) ||  ($row->vDivision == $division &&  $row->vLocation == $location))
          $typeGroupRow[$row->ahaId] = $row->vTitle;
      }//end for each
    }//end if
    else
    {
      $typeGroupRow[0] = NOVALUE;
    }//end else
    if(empty($typeGroupRow))  $typeGroupRow[0] = NOVALUE;
    return ($typeGroupRow);
  }//end function



  function getBankAccountsMultiLoc()
  {
    $division = $this->input->post('ddlDivision');
    $location = $this->input->post('ddlLocation');
    $location2 = $this->input->post('ddlLocation2');
    $location =  $this->get_field_name("Location","vCode"," WHERE nId =  " . $location . " ");
    $location2 =  $this->get_field_name("Location","vCode"," WHERE nId =  " . $location2 . " ");


      // get greater location id
      if($location > $location2)
      {
        $temp = $location;
        $location = $location2;
        $location2 = $temp;
      }

      //generate location array
      $multiLoc = array();
      $multiLocGroup = $this->db->query("SELECT nId from {PRE}Location 
                          WHERE vCode BETWEEN " . $location . " AND  " . $location2 . " AND nDivision = " . $division . " 
                          AND nId IN ".$this->session->userdata('accessLoc') ." ");
      if($multiLocGroup->num_rows() > 0)
      {
        foreach ($multiLocGroup->result() as $key => $row){
          $multiLoc[] =  $row->nId;
        } //en foreach
      }// end if

/*$multiLoc1[0] = "l1 -- " .$location;
$multiLoc1[1] = "l2 -- " .$location2;
return $multiLoc1;
*/
    $typeGroupRow = array();
    $typeGroup    = $this->db->query("SELECT aca.vTitle,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType, ahc.vDivision, ahc.vLocation 
                      FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                      ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                      WHERE ah.vActive = '1' AND ahc.vSubSale = 'B' ORDER BY vTitle ASC");
    if ($typeGroup->num_rows() > 0)
    {
//      $typeGroupRow[0] = SELECT;
      foreach ($typeGroup->result() as $key=>$row)
      {
        if(($row->vDivision == 0 && $row->vLocation == 0) || ($row->vDivision == 0 &&  in_array($row->vLocation,$multiLoc)) || ($row->vDivision == $division &&  $row->vLocation == 0 ) ||  ($row->vDivision == $division &&  in_array($row->vLocation,$multiLoc)))
          $typeGroupRow[$row->ahaId] = $row->vTitle;
      }//end for each
    }//end if
    else
    {
      $typeGroupRow[0] = NOVALUE;
    }//end else
    if(empty($typeGroupRow))  $typeGroupRow[0] = NOVALUE;
    return ($typeGroupRow);
  }//end function


    function getBSPAccounts()
    {
      $division = $this->input->post('ddlDivision');
      $location = $this->input->post('ddlLocation');

        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType, ahc.vDivision, ahc.vLocation
                      FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                      ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                      WHERE ah.vActive = '1' AND ahc.vSubSale = '1' AND ahc.vPrefix = 'BSP' ORDER BY vTitle ASC");
        if ($typeGroup->num_rows() > 0)
        {
//            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key=>$row)
            {
              if(($row->vDivision == 0 && $row->vLocation == 0) || ($row->vDivision == 0 && $row->vLocation == $location) || ($row->vDivision == $division &&  $row->vLocation == 0 ) ||  ($row->vDivision == $division &&  $row->vLocation == $location))
                $typeGroupRow[$row->ahaId] = $row->vTitle;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end else
        if(empty($typeGroupRow))  $typeGroupRow[0] = NOVALUE;
        return ($typeGroupRow);
    }//end function

////////////////////////////////////////////////////////////////////////////////////////////
//////////////////       End of common functions for Change Of Location  ///////////////////
////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////
//////////////////       Common functions for allocation              //////////////////////
////////////////////////////////////////////////////////////////////////////////////////////

    function getAllocatedDetails($type,$receiptId,$drAccount,$vTable)
    {
/*      $salesCloseTypeList =  $this->db->query(" SELECT IF(nSalesCloseDetId = 0,'ADM',vSalesCloseType) AS vSalesCloseType,nSalesCloseDetId 
          FROM {PRE}CashAllocation ca
          WHERE ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."' AND ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."' GROUP BY vSalesCloseType, IF(nSalesCloseDetId = 0 , 0 , 1)");
*/
 $salesCloseTypeList =  $this->db->query(" SELECT vSalesCloseType,nSalesCloseDetId
          FROM {PRE}CashAllocation ca
          WHERE ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."' AND ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."' GROUP BY vSalesCloseType, IF(nSalesCloseDetId = 0 , 0 , 1)");
      if ($salesCloseTypeList->num_rows() > 0)
      {
        foreach ($salesCloseTypeList->result() as $key=>$row)
        {
          $finance_columns = " '". $row->vSalesCloseType ."' as salesCloseType, tb.dDate, 
                                          tb.vDocNumber as vTktNumber,
                                          tbd.nAmount as vCollected, ca.vDebitAmt,ca.vAllAmt,ca.vBalAmt,ca.nCAId, ca.nSalesCloseId as idVal , ca.nSalesCloseDetId as detIdVal ";
          switch($row->vSalesCloseType)
          {
            case 'CR':
              /*echo "SELECT " . $finance_columns ."
                FROM {PRE}CashReceipt tb LEFT JOIN {PRE}CashReceiptDetails tbd ON tbd.nCashId = tb.nCRId
                LEFT JOIN {PRE}CashAllocation ca ON
                (ca.nSalesCloseId = tb.nCRId  AND tbd.nId = ca.nSalesCloseDetId
                AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                GROUP BY ca.nCAId ORDER BY ca.nCAId ASC";
               */
              $adminGroupQuery   = $this->db->query("SELECT " . $finance_columns ." , 'CR' as type
                  FROM {PRE}CashReceipt tb LEFT JOIN {PRE}CashReceiptDetails tbd ON tbd.nCashId = tb.nCRId 
                  LEFT JOIN {PRE}CashAllocation ca ON
                  (ca.nSalesCloseId = tb.nCRId  AND tbd.nId = ca.nSalesCloseDetId 
                   AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."' 
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");
              break;
              /////////////////
            case 'CP':
              $adminGroupQuery   = $this->db->query("SELECT " . $finance_columns .", 'CP'  as type
                  FROM {PRE}CashPayment tb LEFT JOIN {PRE}CashPaymentDetails tdd ON tdd.nCashPId = td.nCPId 
                  LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = tb.  AND tbd.  = ca.nSalesCloseDetId
                    AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");

              break;
              ////////////////
            case 'BR':
              $adminGroupQuery   = $this->db->query("SELECT " . $finance_columns ." ,'BR' as type
                  FROM {PRE}BankBook tb LEFT JOIN {PRE}BankBookDetails tbd ON tbd.nBankbookId = tb.nBBId 
                  LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = tb.  AND tbd.  = ca.nSalesCloseDetId
                    AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");

              break;
              /////////////////
            case 'BP':
              $adminGroupQuery   = $this->db->query("SELECT " . $finance_columns ." , 'BP' as type 
                  FROM {PRE}BankBookPayment tb LEFT JOIN {PRE}BankBookPaymentDetails tbd ON tbd.nBankPaymentId = tb.nBPId 
                  LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = tb.  AND tbd.  = ca.nSalesCloseDetId
                    AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");

              break;
              /////////////////
            case 'CV':
              $adminGroupQuery   = $this->db->query("SELECT " . $finance_columns ."  , 'CV' as type
                  FROM {PRE}CreditVoucher tb LEFT JOIN {PRE}CreditVoucherDetails tbd ON tbd.nCreditId = tb.nCVId 
                  LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = tb.  AND tbd.  = ca.nSalesCloseDetId
                    AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");

              break;

              /////////////////

            case 'DV':
              $adminGroupQuery   = $this->db->query("SELECT " . $finance_columns ."  , 'DV' as type
                  FROM {PRE}Jv tb LEFT JOIN {PRE}JvDetails tbd ON tbd.nJournalId = tb.nJVId 
                  LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = tb.  AND tbd.  = ca.nSalesCloseDetId
                    AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");

              break;
              /////////////////
            case 'AD':
              $adminGroupQuery   = $this->db->query("SELECT " . $finance_columns ." , 'AD' as type
                  FROM {PRE}AdmEntry tb LEFT JOIN {PRE}AdmEntryDetails tbd ON tbd.nAdId = tb.nADId
                  LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = tb.  AND tbd.  = ca.nSalesCloseDetId
                    AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");

              break;
              /////////////////
            case 'ADM':
              $adminGroupQuery   = $this->db->query("SELECT
                  '". $row->vSalesCloseType ."' as salesCloseType,
                  tb.dDate, tbd.vDocNumber as vTktNumber,  tbd.vAmountDC as vCollected, ca.vDebitAmt,ca.vAllAmt,ca.vBalAmt,ca.nCAId, ca.nSalesCloseId as idVal , ca.nSalesCloseDetId as detIdVal 
                  , 'ADM' as type
                  FROM {PRE}AdmEntry tb
                  LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = tb.  AND tbd.  = ca.nSalesCloseDetId
                    AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");

              break;
              /////////////////
            case 'AC':
                  // only the bottom accounts will be stored in the db
              $adminGroupQuery   = $this->db->query("SELECT " . $finance_columns ."  , 'AC' as type
                  FROM {PRE}AcmEntry tb LEFT JOIN {PRE}AcmEntryDetails tbd ON tbd.nAcId = tb.nACId 
                  LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = tb.  AND tbd.  = ca.nSalesCloseDetId
                    AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");

              break;
              /////////////////
			  // add sc.vInvoiceNumber
            case 'SC':
              $adminGroupQuery = $this->db->query("SELECT 
                  '". $row->vSalesCloseType ."' as salesCloseType,
                  sc.dDate,sc.vInvoiceNumber,sc.vTotalCollected ,scd.vTktNumber,scd.vType as tktType, scd.nAirline, scd.vCollected,
                  ca.vDebitAmt,ca.vAllAmt,ca.vBalAmt,ca.nCAId, ca.nSalesCloseId as idVal , ca.nSalesCloseDetId as detIdVal,  'SC' as type
                  FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
                  scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                  sad ON sc.nSCId = sad.nSalesCloseId LEFT JOIN {PRE}CashAllocation ca ON
                  (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");

              break;

              /////////////////
            case 'RC':
            case 'RCR':

              if( $type == 'RCR')
                $column = ' rcd.vNetAmt ';
              else
                $column = ' rcd.vPaidAmt ';
              $adminGroupQuery = $this->db->query("SELECT
                  '". $row->vSalesCloseType ."' as salesCloseType,
                  rc.dDate, rcd.nTicketNumber, rcd.vType as tktType, rcd.nAirline, " . $column . " ,
                  ca.vDebitAmt,ca.vAllAmt,ca.vBalAmt,ca.nCAId, ca.nSalesCloseId as idVal , ca.nSalesCloseDetId as detIdVal,  'RC' as type
                  FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId 
                  LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = rc.nRCId  AND rcd.nId  = ca.nSalesCloseDetId
                  AND ca.nDrAccount = '".$drAccount."' AND ca.vTable = '".$vTable."')
                  WHERE ca.nReceiptDId =  '".$receiptId."' AND vAllType = '". $type ."'
                  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");

              break;
              /////////////////

          }//end switch
//          if(!empty($adminGroupList)){
            if ($adminGroupQuery->num_rows() > 0)
            {
              foreach ($adminGroupQuery->result() as $key=>$row)
              {
                $adminGroupRow[] = $row;
              }//end for each
            }//end if
//          }//end if

        }//end for each
      }//end if
      return ($adminGroupRow);

    } // end function
//add GROUP BY sc.vInvoiceNumber 

function getPendingDetails($drAccount,$vTable,$account){
  switch($vTable){
    case 'C':
           $adminGroupList   = $this->db->query("SELECT 'SC' as type,sc.*,scd.*, sad.vTable,sad.nDrAccount,sc.nSCId as idVal,scd.nId as detIdVal,ca.vAllTktNumber , scd.nAirline,
                                                    CASE WHEN ca.vBalAmt > 0 THEN (sc.vTotalAllocation - (CASE WHEN
                              (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                AND nSalesCloseDetId = scd.nId
                                      AND nDrAccount = '".$drAccount."' AND
                              vTable = '".$vTable."') is null THEN 0
                                  ELSE
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                  AND nSalesCloseDetId = scd.nId
                                        AND nDrAccount = '".$drAccount."' AND
                             vTable = '".$vTable."') END)) ELSE sc.vTotalAllocation END AS allocAmt ,
                                                    CASE WHEN sc.vTotalAllocation - (CASE WHEN
                              (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                AND nSalesCloseDetId = scd.nId
                                      AND nDrAccount = '".$drAccount."' AND
                              vTable = '".$vTable."') is null THEN 0
                                  ELSE
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                  AND nSalesCloseDetId = scd.nId
                                        AND nDrAccount = '".$drAccount."' AND
                             vTable = '".$vTable."') END) > 0 THEN 1 ELSE 0 END AS balCheck
                                                        FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
                                                        LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                                                    LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                                        WHERE sad.nDrAccount = '".$drAccount."' AND sad.nAcHead = '".$account."'
                                                        AND scd.vAllocation > 0
                                                    GROUP BY sc.vInvoiceNumber HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                                        {PRE}CashAllocation)) ORDER BY sc.dDate DESC");



      break;
/////////////////
    case 'V':
            if($account == 32):
                $adminGroupList   = $this->db->query("SELECT  'SC' as type,  sc.nSCId as idVal,sc.dDate,sc.vLocation,scd.nId as detIdVal,sjd.vNumber as vTktNumber,sjd.vType,sjd.nCredit as vCollected, scd.vType,scd.nAirline,
                                                    CASE WHEN ca.vBalAmt > 0 THEN (sjd.nCredit - (CASE WHEN
                              (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                AND nSalesCloseDetId = scd.nId
                                      AND nDrAccount = '".$drAccount."' AND
                              vTable = '".$vTable."') is null THEN 0
                                  ELSE
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                  AND nSalesCloseDetId = scd.nId
                                        AND nDrAccount = '".$drAccount."' AND
                             vTable = '".$vTable."') END))
                            ELSE sjd.nCredit END AS allocAmt ,
                                                    CASE WHEN sjd.nCredit - (CASE WHEN
                              (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                AND nSalesCloseDetId = scd.nId
                                      AND nDrAccount = '".$drAccount."' AND
                              vTable = '".$vTable."') is null THEN 0
                                  ELSE
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                  AND nSalesCloseDetId = scd.nId
                                        AND nDrAccount = '".$drAccount."' AND
                             vTable = '".$vTable."') END) > 0 THEN 1 ELSE 0 END AS balCheck
                                                        FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
                                                        LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                                                        LEFT JOIN {PRE}SystemJvDetails sjd ON (sjd.vType=scd.vType AND sjd.vNumber=scd.vTktNumber)
                                                        LEFT JOIN {PRE}TicketFareDetails tfd ON sjd.vNumber = tfd.nTicketId
                                                    LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                                        WHERE sjd.nAccHeadId = '".$account."' AND sjd.nAGId = '18' AND tfd.nAcc='".$drAccount."'
                                                        AND sjd.nCredit != 0
                                                    GROUP BY sc.vInvoiceNumber HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                                        {PRE}CashAllocation)) ORDER BY sc.dDate DESC");
            elseif($account== 34):
                $adminGroupList   = $this->db->query("SELECT  'SC' as type, sc.nSCId as idVal,sc.dDate,sc.vLocation,scd.nId as detIdVal,sjd.vNumber as vTktNumber,sjd.vType,sjd.nCredit as vCollected,scd.vType,scd.nAirline,
                                                    CASE WHEN ca.vBalAmt > 0 THEN (sjd.nCredit - (CASE WHEN
                              (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                AND nSalesCloseDetId = scd.nId
                                      AND nDrAccount = '".$drAccount."' AND
                              vTable = '".$vTable."') is null THEN 0
                                  ELSE
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                  AND nSalesCloseDetId = scd.nId
                                        AND nDrAccount = '".$drAccount."' AND
                             vTable = '".$vTable."') END)) ELSE sjd.nCredit END AS allocAmt ,
                                                    CASE WHEN sjd.nCredit - (CASE WHEN
                              (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                AND nSalesCloseDetId = scd.nId
                                      AND nDrAccount = '".$drAccount."' AND
                              vTable = '".$vTable."') is null THEN 0
                                  ELSE
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                  AND nSalesCloseDetId = scd.nId
                                        AND nDrAccount = '".$drAccount."' AND
                             vTable = '".$vTable."') END) > 0 THEN 1 ELSE 0 END AS balCheck
                                                        FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
                                                        LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                                                        LEFT JOIN {PRE}SystemJvDetails sjd ON (sjd.vType=scd.vType AND sjd.vNumber=scd.vTktNumber)
                                                        LEFT JOIN {PRE}HotelVoucher hv ON sjd.vNumber = hv.nHVId
                                                    LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                                        WHERE sjd.nAccHeadId = '".$account."' AND sjd.nAGId = '18' AND hv.nAcCode='".$drAccount."'
                                                        AND sjd.nCredit != 0
                                                    GROUP BY sc.vInvoiceNumber HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                                        {PRE}CashAllocation)) ORDER BY sc.dDate DESC");
            else:

                $adminGroupList   = $this->db->query("SELECT sc.nSCId,sc.dDate,sc.vLocation,scd.nId,sjd.vNumber as vTktNumber,sjd.vType,sjd.nCredit,
                                                    CASE WHEN ca.vBalAmt > 0 THEN (sjd.nCredit - (CASE WHEN
        											(SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId 
																AND nSalesCloseDetId = scd.nId 
                    									AND nDrAccount = '".$drAccount."' AND
														 	vTable = '".$vTable."') is null THEN 0
            											ELSE 
             												(SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId 
																	AND nSalesCloseDetId = scd.nId 
                    										AND nDrAccount = '".$drAccount."' AND
														 vTable = '".$vTable."') END)) ELSE sjd.nCredit END AS allocAmt ,
                                                    CASE WHEN sjd.nCredit - (CASE WHEN
        											(SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId 
																AND nSalesCloseDetId = scd.nId 
                    									AND nDrAccount = '".$drAccount."' AND
														 	vTable = '".$vTable."') is null THEN 0
            											ELSE 
             												(SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId 
																	AND nSalesCloseDetId = scd.nId 
                    										AND nDrAccount = '".$drAccount."' AND
														 vTable = '".$vTable."') END) > 0 THEN 1 ELSE 0 END AS balCheck
                                                        FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
                                                        LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                                                        LEFT JOIN {PRE}SystemJvDetails sjd ON (sjd.vType=scd.vType AND sjd.vNumber=scd.vTktNumber)
                                                        LEFT JOIN {PRE}XoBooking xo ON sjd.vNumber = xo.nXId
                                                    LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                                        WHERE sjd.nAccHeadId = '".$account."' AND sjd.nAGId = '18' AND xo.nPassengerAC='".$drAccount."'
                                                        AND sjd.nCredit != 0
                                                    GROUP BY sc.vInvoiceNumber HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                                        {PRE}CashAllocation)) ORDER BY sc.dDate DESC");



/*            $adminGroupList   = $this->db->query("SELECT  'SC' as type,  sc.nSCId as idVal,sc.dDate,sc.vLocation,scd.nId as detIdVal,sjd.vNumber as vTktNumber,sjd.vType,sjd.nCredit as vCollected,scd.vType,scd.nAirline,
                                                    CASE WHEN ca.vBalAmt > 0 THEN (sjd.nCredit - (CASE WHEN
                              (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                AND nSalesCloseDetId = scd.nId
                                      AND nDrAccount = '".$drAccount."' AND
                              vTable = '".$vTable."') is null THEN 0
                                  ELSE
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                  AND nSalesCloseDetId = scd.nId
                                        AND nDrAccount = '".$drAccount."' AND
                             vTable = '".$vTable."') END)) ELSE sjd.nCredit END AS allocAmt ,
                                                    CASE WHEN sjd.nCredit - (CASE WHEN
                              (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                AND nSalesCloseDetId = scd.nId
                                      AND nDrAccount = '".$drAccount."'  AND
														 vTable = '".$drAccount."') > 0 THEN 1 ELSE 0 END AS balCheck
                                                        FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
                                                        LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                                                        LEFT JOIN {PRE}SystemJvDetails sjd ON (sjd.vType=scd.vType AND sjd.vNumber=scd.vTktNumber)
                                                        LEFT JOIN {PRE}XoBooking xo ON sjd.vNumber = xo.nXId
                                                    LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                                        WHERE sjd.nAccHeadId = '".$account."' AND sjd.nAGId = '18' AND xo.nPassengerAC='".$drAccount."'
                                                        AND sjd.nCredit != 0
                                                    GROUP BY sjd.nSjDId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                                        {PRE}CashAllocation)) ORDER BY sc.dDate DESC");*/
            endif;

      break;
/////////////////
    default:

      break;
/////////////////
  } //end switch
  if(!empty($adminGroupList)){
    if ($adminGroupList->num_rows() > 0)
    {
      foreach ($adminGroupList->result() as $key=>$row)
      {
        $adminGroupRow[] = $row;
      }//end for each
    }//end if
  }
  else{
    $adminGroupRow = array();
  }
  return ($adminGroupRow);

}

function setnAcAllocatedAmt($type,$detlReceiptId,$allAmt,$operation){
  if($operation == '-') $allAmt = $allAmt * -1;
  switch($type){
    case 'CR': $tableName = 'CashReceiptDetails'; break;
    case 'CP': $tableName = 'CashPaymentDetails'; break;
    case 'BR': $tableName = 'BankBookDetails'; break;
    case 'BP': $tableName = 'BankBookPaymentDetails'; break;
    case 'CV': $tableName = 'CreditVoucherDetails'; break;
    case 'DV': $tableName = 'DebitVoucherDetails'; break;
    case 'AD': $tableName = 'ADMEntryDetails'; break;
    case 'AC': $tableName = 'ACMEntryDetails'; break;
    case 'JV': $tableName = 'JvDetails'; break;
    case 'RC': $tableName = 'RefundClosingDetails'; break;
  }
  switch($type){
    case 'ADM':
      $allocAmt_db = $this->get_field_name('ADMEntry','nAcAllocatedAmt'," WHERE nADId = ".  $detlReceiptId ." ");
      $data       = array('nAcAllocatedAmt' => $allocAmt_db + $allAmt);
      $this->db->update('{PRE}ADMEntry',$data);
      $allocAmt_db = $this->get_field_name('ADMEntry','nAcAllocatedAmt'," WHERE nADId = ".  $detlReceiptId ." ");
      break;
    case 'ACM':
      $allocAmt_db = $this->get_field_name('ACMEntry','nAcAllocatedAmt'," WHERE nACId = ".  $detlReceiptId ." ");
      $data       = array('nAcAllocatedAmt' => $allocAmt_db + $allAmt);
      $this->db->update('{PRE}ADMEntry',$data);
      $allocAmt_db = $this->get_field_name('ACMEntry','nAcAllocatedAmt'," WHERE nACId = ".  $detlReceiptId ." ");
      break;
    case 'RCR':
/*      $allocAmt_db = $this->get_field_name('RefundClosingDetails','nAcAllocatedAmt_rr'," WHERE nId = ".  $detlReceiptId ." ");
      $data       = array('nAcAllocatedAmt_rr' => $allocAmt_db + $allAmt);
      $this->db->update('{PRE}RefundClosingDetails',$data);
      $allocAmt_db = $this->get_field_name('RefundClosingDetails','nAcAllocatedAmt_rr'," WHERE nId = '".  $detlReceiptId ."' ");
      break;*/
      $tableName = 'RefundClosingDetails'; 
      $allocAmt_db = $this->get_field_name($tableName,'nAcAllocatedAmt_rr'," WHERE nId = ". $detlReceiptId ." ");
      $data       = array('nAcAllocatedAmt_rr' => $allocAmt_db + $allAmt);
      $this->db->where('nId',$detlReceiptId);
      $this->db->update('{PRE}' .$tableName,$data);
      $allocAmt_db = $this->get_field_name($tableName,'nAcAllocatedAmt_rr'," WHERE nId = ". $detlReceiptId ." ");
      break;
    default:
      $allocAmt_db = $this->get_field_name($tableName,'nAcAllocatedAmt'," WHERE nId = ". $detlReceiptId ." ");
      $data       = array('nAcAllocatedAmt' => $allocAmt_db + $allAmt);
      $this->db->where('nId',$detlReceiptId);
      $this->db->update('{PRE}' .$tableName,$data);
      $allocAmt_db = $this->get_field_name($tableName,'nAcAllocatedAmt'," WHERE nId = ". $detlReceiptId ." ");
  }
  
  return $allocAmt_db;

}//end function

function editAllocation()
{
  $cashAllocId =  $this->input->post('cashAllocId');
  $allAmt = $this->input->post('allAmt');  
  $allBalAmt = $this->input->post('allBalAmt');

  $detlReceiptId = $this->session->userdata('DetReceiptId');
  $allType = $this->session->userdata('ReceiptType');
  $receiptAmt =  $this->input->post('receiptAmt');

  $salesDetId =  $this->input->post('salesDetId');
  $type =   $this->input->post('type');

  $allAmt_old = $this->get_field_name('CashAllocation','vAllAmt', " WHERE nCAId = " . $cashAllocId . " ");
   
  $detlReceiptId = $this->session->userdata('DetReceiptId');

//minus the old first
  if($type != 'SC')
    $this->setnAcAllocatedAmt($type,$salesDetId,$allAmt_old,'-');
  if($allType != 'SC')
    $allocatedReceiptAmt = $this->setnAcAllocatedAmt($allType,$detlReceiptId,$allAmt_old,'-');

  if ( $allocatedReceiptAmt + $allAmt > $receiptAmt){
    return -1;
  }

  if($type != 'SC')
    $this->setnAcAllocatedAmt($type,$salesDetId,$allAmt,'+');
  if($allType != 'SC')
    $allocatedReceiptAmt = $this->setnAcAllocatedAmt($allType,$detlReceiptId,$allAmt,'+');


  $data       = array('vAllAmt' => $allAmt,
    'vBalAmt' => $allBalAmt);
  $this->db->where('nCAId',$cashAllocId);
  $this->db->update('{PRE}CashAllocation', $data);
  return $allocatedReceiptAmt;

}//end function

function delAllocation()
{
  $cashAllocId =  $this->input->post('cashAllocId');

  $detlReceiptId = $this->session->userdata('DetReceiptId');
  $allType = $this->session->userdata('ReceiptType');

  $salesDetId =  $this->input->post('salesDetId');
  $type =   $this->input->post('type');

  $allAmt = $this->get_field_name('CashAllocation','vAllAmt', " WHERE nCAId = " . $cashAllocId . " ");
  $detlReceiptId = $this->session->userdata('DetReceiptId');
  //reduce total alloc
  if($type != 'SC')
    $this->setnAcAllocatedAmt($type,$salesDetId,$allAmt,'-');
  if($allType != 'SC')
    $this->setnAcAllocatedAmt($allType,$detlReceiptId,$allAmt,'-');

  $this->db->delete('{PRE}CashAllocation',array('nCAId' => $cashAllocId));
  return $allAmt;
}//end function


function addAllocation()//$salesId,$salesDetId, $type, $dDate,$drAcc,$vTable,$tktNum,)
{
  $salesId =  $this->input->post('salesId'); 
  $salesDetId =  $this->input->post('salesDetId'); 
  $type =   $this->input->post('type');
  $dDate = $this->input->post('dDate');
  $tktNum = $this->input->post('tktNum'); 
  $allDebitAmt = $this->input->post('allDebitAmt');
  $allAmt = $this->input->post('allAmt');  
  $allBalAmt = $this->input->post('allBalAmt');  
  $drAcc = $this->session->userdata('AccVal');
  $vTable = $this->session->userdata('TableVal'); 
  $location =  $this->session->userdata('ddlLocation');
  $allType = $this->session->userdata('ReceiptType');
  $receiptId = $this->session->userdata('ReceiptId');
  $detlReceiptId = $this->session->userdata('DetReceiptId');

  $location = $this->get_field_name('Location','vCode'," WHERE nId='". $location. "'");
  //update allocated values
  if($type != 'SC')
    $this->setnAcAllocatedAmt($type,$salesDetId,$allAmt,'+');
  if($allType != 'SC')
    $this->setnAcAllocatedAmt($allType,$detlReceiptId,$allAmt,'+');
/*
  $dupRecord = $this->get_field_name('CashAllocation','nCAId'," WHERE 
      vSalesCloseType = '". $type . "' AND nSalesCloseId = " . $salesId . " AND nSalesCloseDetId = " . $salesDetId . " 
      AND vAllType = '" . $allType . "' AND nReceiptDId = " . $receiptId . " 
      AND nDrAccount = " . $drAcc . " AND vTable = '". $vTable . "' 
      AND vAllTktNumber = " . $tktNum . " AND vDebitAmt = " . $allDebitAmt . " AND vAllAmt = ". $allAmt . "
      AND vBalAmt = ". $allBalAmt . " ");
  if($dupRecord == '') return -1;
*/
  $data       = array('nUserId' => $this->session->userdata('AbsAdmin'),
      'vSalesCloseType' =>$type , 'nSalesCloseId' => $salesId,  'nSalesCloseDetId' => $salesDetId,
      'vAllType' => $allType,'nReceiptDId' => $receiptId,
      'dDate' => $dDate,'vStatus' => '1',
      'nDrAccount' => $drAcc,'vTable' => $vTable,
    'vAllTktNumber' => $tktNum,'vDebitAmt' => $allDebitAmt,'vAllAmt' => $allAmt,
    'vBalAmt' => $allBalAmt,'vLocation' => $location);
  $this->db->insert('{PRE}CashAllocation', $data);
  return $receiptId;
}//end function

////////////////////////////////////////////////////////////////////////////////////////////
//////////////       End of Common functions for allocation              ///////////////////
////////////////////////////////////////////////////////////////////////////////////////////




}//end class
?>
