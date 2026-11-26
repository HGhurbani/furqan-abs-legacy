<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:             */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2010 Qbicblack  Technologies                      |
// +----------------------------------------------------------------------+
// | This source file is a part of ABS Account Balancing Solution         |
// +----------------------------------------------------------------------+
// | Authors: programmers                                                 |
// |                                                                      |
// +----------------------------------------------------------------------+                                                     
?>
<script language="javascript1.1" type="text/javascript">
$.validator.setDefaults({
	submitHandler: function() {}
});

$(document).ready(function()
{
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			txtDes: {
						required : true
					}
		},
		messages: {
			txtDes: {
				required : "<?php echo CONTENTCANTBEBLANK;?>"
			}
		},
	 success: function(label2) { 
            // set   as text for IE 
            label2.html("").addClass("checked"); 
        } ,
		submitHandler: function(form) {
                $(form).ajaxSubmit();
             } 
		
	});
});
</script>
<!-- Load jQuery -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
        google.load("jquery", "1.3");
</script>

<!-- Load jQuery build -->
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/tinymce/jquery.tinymce.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
                $('textarea.tinymce').tinymce({
                        // Location of TinyMCE script
                        script_url : '<?php echo base_url();?>static/plugins/tinymce/tiny_mce.js',

                        // General options
                        theme : "advanced",
						plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
				
						// Theme options
						theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect",
						theme_advanced_buttons2 :"fontselect,fontsizeselect,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent",
						theme_advanced_buttons3 : "indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
						theme_advanced_buttons4 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup",
						theme_advanced_buttons5 : ",|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr",
						theme_advanced_buttons6 : "acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
                        theme_advanced_toolbar_location : "top",
                        theme_advanced_toolbar_align : "left",
                        theme_advanced_statusbar_location : "bottom",
                        theme_advanced_resizing : true,

                        // Example content CSS (should be your site CSS)
                        content_css : "css/content.css",

                        // Drop lists for link/image/media/template dialogs
                        template_external_list_url : "lists/template_list.js",
                        external_link_list_url : "lists/link_list.js",
                        external_image_list_url : "lists/image_list.js",
                        media_external_list_url : "lists/media_list.js",

                        // Replace values for the template plugin
                        template_replace_values : {
                                username : "Some User",
                                staffid : "991234"
                        }
                });
       
		$('#ddlContent').change(function()
		{
			var nVal = $('#ddlContent').val();
			
			$.ajax(
			  {
				 type:'POST',
				 data:{ddlContent:nVal},
				 url:'<?php echo base_url().'index.php/cms/filterCms/'.$this->uri->segment(3);?>',
				 dataType:'html',
				 beforeSend: function() {
						$('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
					},
					success: function(response) {
						$('#result').html(response);
					}
			  });
		});

});
</script>
<?php
echo validation_errors('<p class="errorClass">','</p>');
echo $this->common_model->successMsg($this->uri->segment(4));
$attributes = array('name' => 'frmCategory', 'id' => 'frmValidate');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<?php $this->load->view('general/admin_menu');?>

<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
	<?php echo form_open('cms/editCms/'.$this->uri->segment(3).'/e',$attributes); ?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
				<tbody>
				<tr>
				  <td width="14%" align="left"><?php echo SELECTPAGE;?></td>
				  <td width="86%" align="left"><?php echo form_dropdown('ddlContent', $this->common_model->cmsArray(), $this->ddlContent,' id="ddlContent"');?></td>
				</tr>
				
				<tr>
					  <td valign="top"><?php echo CONTENT;?> <span class="warning">*</span></td>
					  <td valign="top"><label>
					  <span id="result">
					  <textarea name="txtDes" rows="40" cols="45" id="elim1" class="tinymce"><?php echo stripslashes($this->txtDes);?></textarea>
					  </span>
					  </label></td>
				  </tr>
				  
				  <tr class="formButs">
						<td>&nbsp;</td>
						<td><label>
						  <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal; ?>" />
						  <input type="reset" value="<?php echo RESET;?>" />
						</label></td>
				  </tr>
				</tbody>
	  </table>
	  </form>
	  </div>
	</div>
	
</div>

