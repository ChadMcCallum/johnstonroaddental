<?php

// if the from is loaded from WordPress form loader plugin, 
// the phpfmg_display_form() will be called by the loader 
if( !defined('FormmailMakerFormLoader') ){
    # This block must be placed at the very top of page.
    # --------------------------------------------------
	require_once( dirname(__FILE__).'/form.lib.php' );
    phpfmg_display_form();
    # --------------------------------------------------
};


function phpfmg_form( $sErr = false ){
		$style=" class='form_text' ";

?>




<div id='frmFormMailContainer'>

<form name="frmFormMail" id="frmFormMail" target="submitToFrame" action='<?php echo PHPFMG_ADMIN_URL . '' ; ?>' method='post' enctype='multipart/form-data' onsubmit='return fmgHandler.onSubmit(this);'>

<input type='hidden' name='formmail_submit' value='Y'>
<input type='hidden' name='mod' value='ajax'>
<input type='hidden' name='func' value='submit'>
            
            
<ol class='phpfmg_form' >

<li class='field_block' id='field_0_div'><div class='col_label'>
	<label class='form_field'>Patient Name:</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_0"  id="field_0" value="<?php  phpfmg_hsc("field_0", ""); ?>" class='text_box'>
	<div id='field_0_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_1_div'><div class='col_label'>
	<label class='form_field'>Home Address:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<input type="text" name="field_1"  id="field_1" value="<?php  phpfmg_hsc("field_1", ""); ?>" class='text_box'>
	<div id='field_1_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_2_div'><div class='col_label'>
	<label class='form_field'>City:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<input type="text" name="field_2"  id="field_2" value="<?php  phpfmg_hsc("field_2", ""); ?>" class='text_box'>
	<div id='field_2_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_3_div'><div class='col_label'>
	<label class='form_field'>Postal Code:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<input type="text" name="field_3"  id="field_3" value="<?php  phpfmg_hsc("field_3", ""); ?>" class='text_box'>
	<div id='field_3_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_4_div'><div class='col_label'>
	<label class='form_field'>How would you like to be contacted?</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_dropdown( 'field_4', "Choose One|Telephone - List Time &amp; Number Below|Email - List Email Address Below", true );?>
	<div id='field_4_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_5_div'><div class='col_label'>
	<label class='form_field'>If telephone selected above, specify best time to call:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<input type="text" name="field_5"  id="field_5" value="<?php  phpfmg_hsc("field_5", ""); ?>" class='text_box'>
	<div id='field_5_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_6_div'><div class='col_label'>
	<label class='form_field'>Phone Number:</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_6"  id="field_6" value="<?php  phpfmg_hsc("field_6", ""); ?>" class='text_box'>
	<div id='field_6_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_7_div'><div class='col_label'>
	<label class='form_field'>E-mail Address:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<input type="text" name="field_7"  id="field_7" value="<?php  phpfmg_hsc("field_7", ""); ?>" class='text_box'>
	<div id='field_7_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_8_div'><div class='col_label'>
	<label class='form_field'>Date of Birth</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<input type="text" name="field_8"  id="field_8" value="<?php  phpfmg_hsc("field_8", "") ?>" class='text_box'>
	<div id='field_8_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_9_div'><div class='col_label'>
	<label class='form_field'>Gender:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_9', "Male|Female" );?>
	<div id='field_9_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_10_div'><div class='col_label'>
	<label class='form_field'>Dental Procedure Required:</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_10"  id="field_10" value="<?php  phpfmg_hsc("field_10", ""); ?>" class='text_box'>
	<div id='field_10_tip' class='instruction'></div>
	</div>
</li>

<div align="center">
<center>
<table border="0" width="300">
	<tr>
		<td >
			<ul>
				<div align="center">
						<dl>
							<div align="left">
							<dt style="margin-left: 3; margin-right: 0"><font color="0B4790" size="2"><b>Dr. Len's office hours are as follows:</b></font></dt>
                        	</div>
                    	</dl>
                          	<ul>
								<li><p style="margin-left: 3; margin-right: 0" align="left"><font size="1">Monday &amp; Tuesday:&nbsp; 8 am to 6 pm</font></li>
                            	<li><p style="margin-left: 3; margin-right: 0" align="left"><font size="1">Wednesday &amp; Thursday:&nbsp; 8 am to 3 pm</font></li>
                            	<li><p style="margin-left: 3; margin-right: 0" align="left"><font size="1">Friday:&nbsp; 9 am to 1 pm</font></li>
                          	</ul>
                            
                     	<dl>
                       	 	<div align="left">
                            <dt style="margin-left: 3; margin-right: 0">&nbsp;</dt>
                        	</div>
							<div align="left">
                            <dt style="margin-left: 3; margin-right: 0"><font color="0B4790" size="2"><b>Dr. Barker's office hours are as follows:</b></font></dt>
                          	</div>
                      	</dl>
                          <ul>
                             <li><p style="margin-left: 3; margin-right: 0" align="left"><font size="1">Monday &amp; Tuesday:&nbsp; 8 am to 3 pm</font></li>
                             <li><p style="margin-left: 3; margin-right: 0" align="left"><font size="1">Wednesday:&nbsp; 8 am to 5 pm</font></li>
                             <li><p style="margin-left: 3; margin-right: 0" align="left"><font size="1">Thursday:&nbsp; 8 am to 3 pm</font></li>
							 <li><p style="margin-left: 3; margin-right: 0" align="left"><font size="1">Friday</font><font size="1">:&nbsp;8 am to 1 pm</font></li>
                           </ul>
				</div>
			</ul>
		</td>
	</tr>
    </table>
<div>
		<dt><b><font size="2">Please specify appointment preference:</font></b></dt>
</div>
</div>
                                                
<li class='field_block' id='field_11_div'><div class='col_label'>
	<label class='form_field'>Which doctor would you like to see?</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_dropdown( 'field_11', "Choose One|No Preference|Dr. Len|Dr Barker", true );?>
	<div id='field_11_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_12_div'><div class='col_label'>
	<label class='form_field'>I would like the first available appointment:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_checkboxes( 'field_12', "Yes" );?>
	<div id='field_12_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_13_div'><div class='col_label'>
	<label class='form_field'>Day of the Week:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_dropdown( 'field_13', "Choose One|Any|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday", true );?>
	<div id='field_13_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_14_div'><div class='col_label'>
	<label class='form_field'>Time of Day:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_dropdown( 'field_14', "Choose One|8:00 - 8:30 am|8:30 - 9:00 am|9:00 - 9:30 am|9:30 - 10:00 am|10:00 - 10:30 am|10:30 - 11:00 am|11:00 - 11:30 am|11:30 - 12:00 pm|12:00 - 12:30 pm|12:30 - 1:00 pm|1:00 - 1:30 pm|1:30 - 2:00 pm|2:00 - 2:30 pm|2:30 - 3:00 pm|3:00 - 3:30 pm|3:30 - 4:00 pm|4:00 - 4:30 pm|4:30 - 5:00 pm|5:00 - 5:30 pm|5:30 - 6:00 pm|6:00 - 6:30 pm|6:30 - 7:00 pm|7:00 - 7:30 pm|7:30 - 8:00 pm", true );?>
	<div id='field_14_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_15_div'><div class='col_label'>
	<label class='form_field'>How soon do you need the appointment?</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_dropdown( 'field_15', "Choose One|First Available|1 Week|2 Weeks|3 Weeks|Other, please specify", true );?>
	<div id='field_15_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_16_div'><div class='col_label'>
	<label class='form_field'>Additional Information:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<textarea name="field_16" id="field_16" rows=4 cols=25 class='text_area'><?php  phpfmg_hsc("field_16"); ?></textarea>

	<div id='field_16_tip' class='instruction'></div>
	</div>
</li>


<li class='field_block' id='phpfmg_captcha_div'>
	<div class='col_label'><label class='form_field'>Security Code:</label> <label class='form_required' >*</label> </div><div class='col_field'>
	<?php phpfmg_show_captcha(); ?>
	</div>
</li>


            <li>
            <div class='col_label'>&nbsp;</div>
            <div class='form_submit_block col_field'>
	
				
                <input type='submit' value='Submit' class='form_button'>

				<div id='err_required' class="form_error" style='display:none;'>
				    <label class='form_error_title'>Please check the required fields</label>
				</div>
				


                <span id='phpfmg_processing' style='display:none;'>
                    <img id='phpfmg_processing_gif' src='<?php echo PHPFMG_ADMIN_URL . '?mod=image&amp;func=processing' ;?>' border=0 alt='Processing...'> <label id='phpfmg_processing_dots'></label>
                </span>
            </div>
            </li>
            
</ol>
</form>

<iframe name="submitToFrame" id="submitToFrame" src="javascript:false" style="position:absolute;top:-10000px;left:-10000px;" /></iframe>

</div> 
<!-- end of form container -->


<!-- [Your confirmation message goes here] -->
<div id='thank_you_msg' style='display:none;'>
Your form has been sent. Thank you!
</div>

            
            






<?php
			
    phpfmg_javascript($sErr);

} 
# end of form




function phpfmg_form_css(){
    $formOnly = isset($GLOBALS['formOnly']) && true === $GLOBALS['formOnly'];
?>
<style type='text/css'>
<?php 
if( !$formOnly ){
    echo"
body{
    margin-left: 18px;
    margin-top: 18px;
}

body{
    font-family : Arial, Helvetica, sans-serif;
    font-size : 11px;
    color : #474747;
    background-color: transparent;
}

select, option{
    font-size:11px;
}
";
}; // if
?>

ol.phpfmg_form{
    list-style-type:none;
    padding:0px;
    margin:0px;
}

ol.phpfmg_form input, ol.phpfmg_form textarea, ol.phpfmg_form select{
    border: 1px solid #ccc;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
}

ol.phpfmg_form li{
    margin-bottom:5px;
    clear:both;
    display:block;
    overflow:hidden;
	width: 100%
}


.form_field, .form_required{
    font-weight : normal;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
}

.form_required{
    color:red;
    margin-right:8px;
}

.field_block_over{
}

.form_submit_block{
    padding-top: 3px;
}

.text_box, .text_area, .text_select {
    width:300px;
}

.text_area{
    height:80px;
}

.form_error_title{
    font-weight: bold;
    color: red;
}

.form_error{
    background-color: #F4F6E5;
    border: 1px dashed #ff0000;
    padding: 10px;
    margin-bottom: 10px;
}

.form_error_highlight{
    background-color: #F4F6E5;
    border-bottom: 1px dashed #ff0000;
}

div.instruction_error{
    color: red;
    font-weight:bold;
}

hr.sectionbreak{
    height:1px;
    color: #ccc;
}

#one_entry_msg{
    background-color: #F4F6E5;
    border: 1px dashed #ff0000;
    padding: 10px;
    margin-bottom: 10px;
}


#frmFormMailContainer input[type="submit"]{
    padding: 10px 25px; 
    font-weight: bold;
    margin-bottom: 10px;
    background-color: #FAFBFC;
}

#frmFormMailContainer input[type="submit"]:hover{
    background-color: #E4F0F8;
}

<?php phpfmg_text_align();?>    



</style>

<?php
}
# end of css
 
# By: formmail-maker.com
?>