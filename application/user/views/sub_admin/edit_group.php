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

    function checkAll()
    {
        if(document.getElementById('chkAll').checked==false)
        {
            uncheckAll();
        }//end if
        else
        {
            for (var i = 0; i < document.frmGroup.elements.length; i++)
            {
                var e = document.frmGroup.elements[i];
                if (e.name == "ddlModules[]")
                {
                    e.checked = true;
                }//end if
            }//end for loop
        }//end else
    }//end function

    function uncheckAll()
    {
        for (var i = 0; i < document.frmGroup.elements.length; i++)
        {
            var e = document.frmGroup.elements[i];
            if (e.name == "ddlModules[]")
            {
                e.checked = false;
            }//end if
        }//end for loop
    }//end function

    $(document).ready(function()
    {
        $('#btnCancel').click(function()
        {
            window.location.replace('<?php echo base_url().'index.php/admin_group/groupList/'.$this->uri->segment(3);?>');
            return false;
        });

        jQuery.validator.addMethod(
            "selectNone",
            function(value, element) {
                if (element.value == "0")
                {
                    return false;
                }
                else return true;
            },
            "<?php echo PLEASESELECTANOPTION;?>"
        );

        // validate signup form on keyup and submit
        $("#frmValidate").validate({
            rules: {
                txtTitle: {
                    required : true,
                    <?php
                    if($this->uri->segment(5) !='')
                    {
                    ?>
                    remote   : {url:'<?php echo base_url().'index.php/admin_group/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
                    <?php
                    }//end if
                    else
                    {
                    ?>
                    remote   : {url:'<?php echo base_url().'index.php/admin_group/checkTitle'?>',type: "post"}
                    <?php
                    }//end else
                    ?>
                }/*,
                ddlUserAccess:{
                    selectNone : true
                }*/
            },
            messages: {
                txtTitle: {
                    required : "<?php echo GROUPNAME;?><?php echo CANTBEBLANK;?>",
                    remote   : "<?php echo GROUPALREADYTAKEN;?>"
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

        $('#ddlUserAccess').change(function(e) {
            $('.showUserAccess').val($('#ddlUserAccess').val());
        });

        $('.checkSub1').change(function(){
            var idVal = $(this).attr('data-group');
            if($(this).is(':checked')) {
                $('.checkSub2'+'.'+idVal).each(function(){
                       $(this).attr('checked','checked');
                })
            } else {
                $('.checkSub2'+'.'+idVal).each(function(){
                    $(this).attr('checked','');
                })
            }
        });

        $('.checkSub2').change(function(){
            var cClass = $(this).attr('class');
            cClass = cClass.split('checkSub2 ');
            $('.checkSub2'+'.'+cClass[1]).each(function(){
                if($(this).is(':checked')) {
                    $(".checkSub1"+"[data-group='"+cClass[1]+"']").attr('checked','checked');
                    return false;
                } else {
                    $(".checkSub1"+"[data-group='"+cClass[1]+"']").attr('checked','');
                    return true;
                }
            })
        });
    });

</script>

<?php
echo $this->common_model->successMsg($this->uri->segment(5));

echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');

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
<?php echo form_open('admin_group/editAdminGroup/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes);?>
<?php echo form_hidden('nGId', $this->GroupId);?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo GROUPNAME;?></td>
            <td width="86%" align="left"><label>
             <input name="txtTitle" id="txtTitle" class="textfield" type="text" value="<?php echo $this->txtTitle;?>" size="40">
            </label></td>
        </tr>

        <tr>
            <td><?php echo USERROLEACCESS;?> <span class="warning">*</span></td>
            <td><label>
                <?php echo form_dropdown('ddlUserAccess', $list_access, $this->ddlUserAccess, 'id="ddlUserAccess"');?>
            </label></td>
        </tr>

        <tr>
            <td valign="top"><?php echo MODULEACCESS;?></td>
            <td valign="top">
              <input type="checkbox" name="chkAll"  id="chkAll" onclick="checkAll();"/>
              <strong><?php echo SELECTALL;?> </strong><br />
                <?php echo $this->general_model->get_list_of_admin_modules($this->GroupId);?>
          	</td>
        </tr>
        
        <tr>
            <td><?php echo ACTIVE;?></td>
            <td><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?>/>&nbsp;<?php echo YES;?> <input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>
        
        <tr class="formButs">
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
			  <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOGROUP;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
