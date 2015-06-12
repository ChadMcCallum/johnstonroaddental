<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "jodi@rogerswebdesigns.ca" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "1b214e" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'FFEA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAU0lEQVR4nGNYhQEaGAYTpIn7QkNFQ11DHVqRxQIaRBpYGximOmCKBQRgiDE6iCC5LzRqatjS0JVZ05Dch6YOWSw0BLd5+MVCHVHEBir8qAixuA8AT03MIETt5BMAAAAASUVORK5CYII=',
			'D96E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUMDkMQCprC2Mjo6OiCrC2gVaXRtwCbGCBMDOylq6dKlqVNXhmYhuS+glTHQFcM8BqDeQDQxFkwxLG7B5uaBCj8qQizuAwDdDcvsbpWk8QAAAABJRU5ErkJggg==',
			'5A3A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkMYAhhDGVqRxQIaGENYGx2mOqCIsQLVBAQEIIkFBog0OjQ6OogguS9s2rSVWVNXZk1Ddl8rijqomGioQ0NgaAiyHSB1DYEo6kSmiDS6oullBdrrGMqIat4AhR8VIRb3AQCqo81dBtrq7AAAAABJRU5ErkJggg==',
			'FAED' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QkMZAlhDHUMdkMQCGhhDWBsYHQJQxFhbQWIiKGIija4IMbCTQqOmrUwNXZk1Dcl9aOqgYqKhmGLY1EHEAtDF0Nw8UOFHRYjFfQBFwcyWbqiNEwAAAABJRU5ErkJggg==',
			'33A4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7RANYQximMDQEIIkFTBFpZQhlaEQWY2hlaHR0dGhFEZvC0MoKVB2A5L6VUavClq6KiopCdh9YXaADunmuoYGhIehiQJegu4UVTQzkZnSxgQo/KkIs7gMAoFvOaCN+FsYAAAAASUVORK5CYII=',
			'4B06' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37poiGMExhmOqALBYi0soQyhAQgCTGGCLS6Ojo6CCAJMY6RaSVtSHQAdl906ZNDVu6KjI1C8l9ARB1KOaFhoo0ugL1iqC4BWIHmhiGW7C6eaDCj3oQi/sAef/LyscbacgAAAAASUVORK5CYII=',
			'641B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYWhmmMIY6IImJTGGYyhDC6BCAJBbQwhDKCBQTQRZrYHQF6oWpAzspMmrp0lXTVoZmIbkvZIpIK5I6iN5W0VCHKWjmtYLdgiIGdAuGXpCbGUMdUdw8UOFHRYjFfQBGhcrNLBKOlwAAAABJRU5ErkJggg==',
			'83EB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7WANYQ1hDHUMdkMREpoi0sjYwOgQgiQW0MjS6AsVEUNQxIKsDO2lp1KqwpaErQ7OQ3IemDqd52O3AdAs2Nw9U+FERYnEfAJGnyuzlxP3uAAAAAElFTkSuQmCC',
			'F491' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkMZWhlAGEksoIFhKqOjw1Q0sVDWhoBQVDFGV6AYTC/YSaFRS5euzIxaiuy+gAaRVoaQADQ7REMdGtDFGFoZsYk5OmCIAd0cGjAIwo+KEIv7AP1LzPosdnywAAAAAElFTkSuQmCC',
			'7CC0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkMZQxlCHVpRRFtZGx0dAqY6oIiJNLg2CAQEIItNEWlgbWB0EEF2X9S0VUtXrcyahuQ+kAokdWDI2oApJtKAaUdAA6ZbAhqwuHmAwo+KEIv7AEs6zF3BhOZUAAAAAElFTkSuQmCC',
			'4149' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpI37pjAEMDQ6THVAFgthDGBodQgIQBJjDGENYJjq6CCCJMYK0hsIFwM7adq0VVErM7OiwpDcFwBUxwq0A1lvaChQLDSgQQTTLQ5YxFDcwjCFNRTDzQMVftSDWNwHAMHnymvkUUrLAAAAAElFTkSuQmCC',
			'704F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZAhgaHUNDkEVbGUMYWh0dUFS2srYyTEUTmyLS6BAIF4O4KWrayszMzNAsJPcxOog0ujai6mVtAIqFBqKIiTQA7UBTF9AAdAuGGNjNqG4ZoPCjIsTiPgBrpcn4Ic08QAAAAABJRU5ErkJggg==',
			'25FF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WANEQ1lDA0NDkMREpog0sDYwOiCrC2jFFGNoFQlBEoO4adrUpUtDV4ZmIbsvgKHRFU0vkIchxtoggiEGtLUV3d7QUMYQDLcMUPhREWJxHwDIy8iGQezWnwAAAABJRU5ErkJggg==',
			'A1EA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHVqRxVgDGANYGximOiCJiUxhBYkFBCCJBbQC9QJNEEFyX9RSIApdmTUNyX1o6sAwNBQsFhqC2zw8YqyhrKGOKGIDFX5UhFjcBwAX48kG3igUjQAAAABJRU5ErkJggg==',
			'E01E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkMYAhimMIYGIIkFNDCGMIQwOjCgiLG2MmKIiTQ6TIGLgZ0UGjVtZda0laFZSO5DU4dHjLWVAUMM6BY0MZCbGUMdUdw8UOFHRYjFfQC0lMpK1vs15QAAAABJRU5ErkJggg==',
			'B6D3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDGUIdkMQCprC2sjY6OgQgi7WKNLI2BDSIoKgTaQCJBSC5LzRqWtjSVVFLs5DcFzBFtBVJHdw8V3TzsIlhcQs2Nw9U+FERYnEfANkuzzPs6DgEAAAAAElFTkSuQmCC',
			'495C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QsRGAIAxFkzuyAQPFwh7uYAQLnQIKNohuQOOUahfUUk/zu9e8d4H1cgn+tHf6BANFnp1mgQolcFYxDDb3CdkoRrKzGVn3LUutwzhOus8Jek6etTdGyGcGYnaHbxwgVLDjpuVohght81f/e243fRuyWcsVey+USQAAAABJRU5ErkJggg==',
			'FFC4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkNFQx1CHRoCkMQCGkQaGB0CGtHFWBsEWjHFGKYEILkvNGpq2NJVq6KikNwHUQc0EUMvY2gIph3Y3IIhxoDm5oEKPypCLO4DAL8TzxCOvoXuAAAAAElFTkSuQmCC',
			'0697' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUNDkMRYA1hbGR0dGkSQxESmiDSyNgSgiAW0ijSAxAKQ3Be1dFrYysyolVlI7gtoFW1lCAloZUDV2+jQEDCFAc0Ox4aAAAYMtzg6YHEzithAhR8VIRb3AQDrv8sFDXWFOQAAAABJRU5ErkJggg==',
			'3F60' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7RANEQx1CGVqRxQKmiDQwOjpMdUBW2SrSwNrgEBCALDYFJMboIILkvpVRU8OWTl2ZNQ3ZfSB1jo4wdUjmBWIRC0CxA5tbRAOAutDcPFDhR0WIxX0Aq7jLyrrKhLMAAAAASUVORK5CYII=',
			'A03F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB0YAhhDGUNDkMRYAxhDWBsdHZDViUxhbWVoCEQRC2gVaXRAqAM7KWrptJVZU1eGZiG5D00dGIaGAsUwzMNmB6ZbAlrBbkYRG6jwoyLE4j4AJEXKusVZcs8AAAAASUVORK5CYII=',
			'16F8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA6Y6IImxOrC2sjYwBAQgiYk6iDSyAlWLoOgVaUBSB3bSyqxpYUtDV03NQnIfo4MohnlAvY2umOZhEcPilhCgmxsYUNw8UOFHRYjFfQCjgcicDtLbrAAAAABJRU5ErkJggg==',
			'D46B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgMYWhlCGUMdkMQCpjBMZXR0dAhAFgOqYm1wdBBBEWN0ZW1ghKkDOylqKRBMXRmaheS+gFaRVlYM80RDXRsC0cxjaGVFF5vC0IruFmxuHqjwoyLE4j4Ak1bMj2G5bKkAAAAASUVORK5CYII=',
			'ADDF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDGUNDkMRYA0RaWRsdHZDViUwRaXRtCEQRC2hFEQM7KWrptJWpqyJDs5Dch6YODENDCZoHE8NwS0Ar2M0oYgMVflSEWNwHAMcZzAm1SECgAAAAAElFTkSuQmCC',
			'9999' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaY6IImJTGFtZXR0CAhAEgtoFWl0bQh0EMEtBnbStKlLl2ZmRkWFIbmP1ZUx0CEkYCqyXoZWhkaHhoAGZDGBVpZGx4YAFDuwuQWbmwcq/KgIsbgPAN/0y+Z4z0YWAAAAAElFTkSuQmCC',
			'893A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYQxhDGVqRxUSmsLayNjpMdUASC2gVaXRoCAgIQFEHFGt0dBBBct/SqKVLs6auzJqG5D6RKYyBSOqg5jEAzQsMDUERYwGJoaiDuAVVL8TNjChiAxV+VIRY3AcAZFfM/OtZMPkAAAAASUVORK5CYII=',
			'124C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YQxgaHaYGIImxOrC2MrQ6BIggiYk6iABVOTqwoOgF6gx0dEB238qsVUtXZmZmIbsPqG4KayNcHUwsgDU0EE0MZCK6HawNQFtQ3RIiGuqA5uaBCj8qQizuAwBCuMkMix+uRwAAAABJRU5ErkJggg==',
			'4A55' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjAEsIY6hgYgi4UwhrA2MDogqwOKtKKLsU4RaXSdyujqgOS+adOmrUzNzIyKQnJfAFCdQ0NAgwiS3tBQ0VB0MQaQeQ2BDuhijo4OAQFoYg6hDFMdBkP4UQ9icR8AJIvL7dtkweYAAAAASUVORK5CYII=',
			'9FD3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7WANEQ11DGUIdkMREpog0sDY6OgQgiQW0AsUaAhpEsIgFILlv2tSpYUtXRS3NQnIfqyuKOgjEYp4AFjFsbmENAIqhuXmgwo+KEIv7AOjGzXwyx0coAAAAAElFTkSuQmCC',
			'A378' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB1YQ1hDA6Y6IImxBoi0MjQEBAQgiYlMYWh0aAh0EEESC2hlaAWKwtSBnRS1dFXYqqWrpmYhuQ+sbgoDinmhoSCdjOjmNTo6oIuJtLI2oOoNaAW6uYEBxc0DFX5UhFjcBwACPsznsRw3kwAAAABJRU5ErkJggg==',
			'E95C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDHaYGIIkFNLC2sjYwBIigiIk0ujYwOrCgi01ldEB2X2jU0qWpmZlZyO4LaGAMdGgIdGBA0cvQiCnGArQjEM0O1lZGRwcUt4DczBDKgOLmgQo/KkIs7gMAkobMeuQUx9kAAAAASUVORK5CYII=',
			'3C79' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7RAMYQ1lDA6Y6IIkFTGFtdGgICAhAVtkq0uDQEOgggiw2BchrdISJgZ20MmraqlVLV0WFIbsPpG4Kw1QRNPMYAhga0MUcHRhQ7AC5xRWoEtktYDc3MKC4eaDCj4oQi/sAztrMe0WVoBsAAAAASUVORK5CYII=',
			'7915' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7QkMZQximMIYGIIu2srYyhDA6oKhsFWl0RBebItLoMIXR1QHZfVFLl2ZNWxkVheQ+RgfGQIcpDA0iSHpZGxga0cVEGlhA5jkgiwU0AN0yhSEgAEWMMYQx1GGqwyAIPypCLO4DAFrOyw8ee96TAAAAAElFTkSuQmCC',
			'AF0F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB1EQx2mMIaGIImxBog0MIQyOiCrE5ki0sDo6IgiFtAq0sDaEAgTAzspaunUsKWrIkOzkNyHpg4MQ0MxxUDqsNmB7haw2BRUsYEKPypCLO4DAEQYyf6Xy7niAAAAAElFTkSuQmCC',
			'AB6B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB1EQxhCGUMdkMRYA0RaGR0dHQKQxESmiDS6Njg6iCCJBbSKtLICTQhAcl/U0qlhS6euDM1Cch9YHZp5oaEg8wLRzcMmhuGWgFZMNw9U+FERYnEfAMkrzDRHayNKAAAAAElFTkSuQmCC',
			'113B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhDGUMdkMRYHRgDWBsdHQKQxEQdWAMYGgIdRND0MiDUgZ20MmtV1KqpK0OzkNyHpg4hhs08LGIYbglhDUV380CFHxUhFvcBAK9Jxwv2pnJYAAAAAElFTkSuQmCC',
			'C785' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QsQ3AMAgEccEGzj5QuHck03gaXHiEZId4ylBiJWUimZcoXv/iBIzHKKykX/iwbEISJDsvdmjMTD6XG7Sk++wp9MCcyPHVMc4hV62Oz3I5MGmcuoHQ9uQ1VLQbcWKJat3s+bBYQuCgBf73oV74bmlQy6FeV4J5AAAAAElFTkSuQmCC',
			'659C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQxlCGaYGIImJTBFpYHR0CBBBEgtoEWlgbQh0YEEWaxAJAYkhuy8yaurSlZmRWcjuC5nC0OgQAlcH0dsKFGtAFxNpdESzQ2QKayu6W1gDGEPQ3TxQ4UdFiMV9AFldy5Mh0ESoAAAAAElFTkSuQmCC',
			'090B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB0YQximMIY6IImxBrC2MoQyOgQgiYlMEWl0dHR0EEESC2gVaXRtCISpAzspaunSpamrIkOzkNwX0MoYiKQOKsYA1iuCYgcLhh3Y3ILNzQMVflSEWNwHAKwgyvpXdQm8AAAAAElFTkSuQmCC',
			'7A74' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkMZAlhDAxoCkEVbGUMYGgIaUcVYW4FirShiU0QaHRodpgQguy9q2sqspauiopDcx+gAVDeF0QFZL2uDaKhDAGNoCJKYSINIo6MDA4pbAoBirg2ExQYq/KgIsbgPANo+zn5y3S2rAAAAAElFTkSuQmCC',
			'6334' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WANYQxhDGRoCkMREpoi0sjY6NCKLBbQwNDo0BLSiiDUwtAJFpwQguS8yalXYqqmroqKQ3BcyBaTO0QFFL0hnQ2BoCIZYADa3oIhhc/NAhR8VIRb3AQDT288rGOcmKgAAAABJRU5ErkJggg==',
			'DCA8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QgMYQxmmMEx1QBILmMLa6BDKEBCALNYq0uDo6OgggibG2hAAUwd2UtTSaauWroqamoXkPjR1CLHQQAzzXBvQxIBucUXTC3Iz0DwUNw9U+FERYnEfAP+az3DaUrw3AAAAAElFTkSuQmCC',
			'66F1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA1qRxUSmsLayNjBMRRYLaBFpBIqFoog1iDQAxWB6wU6KjJoWtjR01VJk94VMEW1FUgfR2yrS6EqEGNQtKGJgNwPdEjAIwo+KEIv7AHumy7c7fETeAAAAAElFTkSuQmCC',
			'F529' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkNFQxlCGaY6IIkFNIg0MDo6BASgibE2BDqIoIqFMCDEwE4KjZq6dNXKrKgwJPcBzWl0aGWYiqoXKDaFoQHNvEaHAAY0O1hbGR0Y0NzCGMIaGoDi5oEKPypCLO4DAAznzPAbYaJeAAAAAElFTkSuQmCC',
			'7D57' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QkNFQ1hDHUNDkEVbRVpZgbQIqlijK7rYFKDYVIaGAGT3RU1bmZqZtTILyX2MDiKNDg0Brcj2sjaAxaYgi4k0gOwICEAWC2gQaWV0dHRAFRMNYQhlRBEbqPCjIsTiPgCU9sxf5Od80AAAAABJRU5ErkJggg==',
			'A636' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YQxhDGaY6IImxBrC2sjY6BAQgiYlMEWlkaAh0EEASC2gVaWBodHRAdl/U0mlhq6auTM1Ccl9Aq2grUB2KeaGhIo0OQPNEUM3DIobploBWTDcPVPhREWJxHwDXSM0afuECwAAAAABJRU5ErkJggg==',
			'8347' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WANYQxgaHUNDkMREpoi0MrQ6NIggiQW0MjQ6TEUVE5nC0MoQ6NAQgOS+pVGrwlZmZq3MQnIfSB1ro0MrA5p5rqEBU9DFHBodAhjQ3dLo6IDFzShiAxV+VIRY3AcAcL3M/2vg5XsAAAAASUVORK5CYII=',
			'113E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhDGUMDkMRYHRgDWBsdHZDViTqwBjA0BDqg62VAqAM7aWXWqqhVU1eGZiG5D00dQgybeVjEMNwSwhqK7uaBCj8qQizuAwD9s8Wv97AWAQAAAABJRU5ErkJggg==',
			'379E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7RANEQx1CGUMDkMQCpjA0Ojo6OqCobGVodG0IRBWbwtDKihADO2ll1KppKzMjQ7OQ3TeFIYAhBE1vKyOQjy7G2sCIJhYwRaSBEc0togEiDQxobh6o8KMixOI+ACmZyaCj7r9oAAAAAElFTkSuQmCC',
			'2682' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaY6IImJTGFtZXR0CAhAEgtoFWlkbQh0EEHW3SrSAFTXIILsvmnTwlaFrloVhey+AFGQeY3IdjA6iDS6Ak1FcUsDWGwKsphIA8QtyGKhoSA3M4aGDILwoyLE4j4AHpjLXX4zjssAAAAASUVORK5CYII=',
			'E2D6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDGaY6IIkFNLC2sjY6BASgiIk0ujYEOgigiDGAxZDdFxq1aunSVZGpWUjuA6qbwtoQiGYeQwBQzEEERYzRAVOMtQHdLaEhoqGuaG4eqPCjIsTiPgAfc827YKMCLgAAAABJRU5ErkJggg==',
			'43F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpI37prCGsIYGTHVAFgsRaWVtYAgIQBJjDGFodG1gdBBBEmOdwgBUBxcDO2natFVhS0NXRYUhuS8ArI5hKrLe0FCQeQwNIihuAYs5oIphugXsZqB5KG4eqPCjHsTiPgChOssThsm5OQAAAABJRU5ErkJggg==',
			'AE7B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDA0MdkMRYA0SAZKBDAJKYyBSImAiSWEArkNfoCFMHdlLU0qlhq5auDM1Cch9Y3RRGFPNCQ4FiAYwY5jE6YIqxNqDqDWgFurmBEcXNAxV+VIRY3AcATonLhqoWhzEAAAAASUVORK5CYII=',
			'1A1F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIaGIImxOjCGMIQAZZDERB1YWxnRxBgdRBodpsDFwE5amTUNhEKzkNyHpg4qJhqKKYZNHaaYaIhIo2OoI4rYQIUfFSEW9wEATI3G+fkt4C0AAAAASUVORK5CYII=',
			'2F80' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WANEQx1CGVqRxUSmiDQwOjpMdUASC2gVaWBtCAgIQNbdClLn6CCC7L5pU8NWha7MmobsvgAUdWDI6AAyLxBFjLUB0w6RBky3hIYCdaG5eaDCj4oQi/sA9crLN/KnjDEAAAAASUVORK5CYII=',
			'AAD6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGaY6IImxBjCGsDY6BAQgiYlMYW1lbQh0EEASC2gVaXQFiiG7L2rptJWpqyJTs5DcB1WHYl5oqGgoSK8IFvMwxNDcAhZDc/NAhR8VIRb3AQCwxs4Dt49iRQAAAABJRU5ErkJggg==',
			'40F4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpI37pjAEsIYGNAQgi4UwhrA2MDQiiwFFWoFirchirFNEGl2BJgQguW/atGkrU0NXRUUhuS8ArI7RAVlvaChYLDQExS1gO1DdMgXsFjQxoJvRxQYq/KgHsbgPAKE3zKJ+ZPFVAAAAAElFTkSuQmCC',
			'54FE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkMYWllDA0MDkMSA7KmsDYwODKhioehigQGMrkhiYCeFTVu6dGnoytAsZPe1irSi62VoFQ11RbejlQFDncgUTDHWALAYipsHKvyoCLG4DwB8h8kVzs++oAAAAABJRU5ErkJggg==',
			'1224' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGRoCkMRYHVhbGR0dGpHFRB1EGl0bAloDUPQyNDo0BEwJQHLfyqxVS4FEVBSS+4DqpjC0Mjqg6Q1gmMIYGoLqFpBoA6o6VpBaFDHRENFQ19AAFLGBCj8qQizuAwDby8pFDK5lrgAAAABJRU5ErkJggg==',
			'A7B8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB1EQ11DGaY6IImxBjA0ujY6BAQgiYlMAYo1BDqIIIkFtDK0siLUgZ0UtXTVtKWhq6ZmIbkPqC6AFc280FBGB1YM81gbMMVEGtD1gsXQ3DxQ4UdFiMV9AMsFzaEkO/RRAAAAAElFTkSuQmCC',
			'0032' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhDGaY6IImxBjCGsDY6BAQgiYlMYW1laAh0EEESC2gVaXRodGgQQXJf1NJpK7OmAmkk90HVNTqg6wWSDBh2BExhwOIWTDczhoYMgvCjIsTiPgCBEsxqFvYGLQAAAABJRU5ErkJggg==',
			'7778' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkNFQ11DA6Y6IIu2MjQ6NAQEBGCIBTqIIItNAYvC1EHcFLVq2qqlq6ZmIbmP0YEhAKgWxTxWsCgjinkiYFFUsQCwKKpeqBiqmwco/KgIsbgPAHFMzBvO0BVuAAAAAElFTkSuQmCC',
			'AEE0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDHVqRxVgDRBpYGximOiCJiUwBiwUEIIkFtILEGB1EkNwXtXRq2NLQlVnTkNyHpg4MQ0MxxSDqsNmB6paAVkw3D1T4URFicR8ADtbLk+x3iWoAAAAASUVORK5CYII=',
			'554C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkNEQxkaHaYGIIkFNIg0MLQ6BIigi011dGBBEgsMEAlhCHR0QHZf2LSpS1dmZmahuK+VodG1Ea4OIRYaiCIW0CrS6NCIaofIFFagSlS3sAYwhqC7eaDCj4oQi/sAokDMdH560nMAAAAASUVORK5CYII=',
			'BBB5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDGUMDkMQCpoi0sjY6OiCrC2gVaXRtCEQVg6hzdUByX2jU1LCloSujopDcB1Hn0CCCYV4AFrFABxEMOxwCkN0HcTPDVIdBEH5UhFjcBwB0Yc41t1r+nwAAAABJRU5ErkJggg==',
			'97C8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQx1CHaY6IImJTGFodHQICAhAEgtoZWh0bRB0EEEVa2VtYICpAztp2tRV05auWjU1C8l9rK4MAUjqILCV0YG1gRHFPAGgaaxodohMEQGqQnULawBQBZqbByr8qAixuA8ABv7L09QpjvAAAAAASUVORK5CYII=',
			'9B35' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANEQxhDGUMDkMREpoi0sjY6OiCrC2gVaXRoCEQXa2VodHR1QHLftKlTw1ZNXRkVheQ+VleQOocGEWSbweYFoIgJQO0QwXCLQwCy+yBuZpjqMAjCj4oQi/sAcXDMhEB5JOkAAAAASUVORK5CYII=',
			'6A7A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDA1qRxUSmMIYwNARMdUASC2hhBaoJCAhAFmsQaXRodHQQQXJfZNS0lVlLV2ZNQ3JfyBSguimMMHUQva2ioQ4BjKEhKGIiQNNQ1YkA9bo2oIqxBmCKDVT4URFicR8A71PMrh4rA74AAAAASUVORK5CYII=',
			'92A1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYQximMLQii4lMYW1lCGWYiiwW0CrS6OjoEIoqxtDoCiKR3Ddt6qqlS1dFLUV2H6srwxRWhDoIbGUIYA1FFRNoZXRAVwd0SwO6GGuAaCjQ3tCAQRB+VIRY3AcAWoXMedQXtfwAAAAASUVORK5CYII=',
			'0D7E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDA0MDkMRYA0RaGRoCHZDViUwRaXRAEwtoBYo1OsLEwE6KWjptZdbSlaFZSO4Dq5vCiKk3gBHDDkcHVDGQW1gbUMXAbm5gRHHzQIUfFSEW9wEAUA3KdUzetvUAAAAASUVORK5CYII=',
			'4503' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpI37poiGMkxhCHVAFgsRaWAIZXQIQBJjBIoxOjo0iCCJsU4RCWFtCGgIQHLftGlTly5dFbU0C8l9AVMYGl0R6sAwNBQiJoLiFpFGRzQ7GKawtqK7hWEKYwiGmwcq/KgHsbgPAKM5zLZKZnfuAAAAAElFTkSuQmCC',
			'F6B6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDGaY6IIkFNLC2sjY6BASgiIk0sjYEOgigijWwNjo6ILsvNGpa2NLQlalZSO4LaBAFmueIYZ4r0DwRgmLY3ILp5oEKPypCLO4DAPCdzb8kW8fbAAAAAElFTkSuQmCC',
			'39A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7RAMYQximMIQ6IIkFTGFtZQhldAhAVtkq0ujo6NAggiw2RaTRtSGgIQDJfSujli5NXRW1NAvZfVMYA5HUQc1jaHQNDUA1r5UFbJ4ImltYGwJR3AJyM2tDAIqbByr8qAixuA8A3WvNv1IyiNsAAAAASUVORK5CYII=',
			'5D3F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkNEQxhDGUNDkMQCGkRaWRsdHRhQxRodGgJRxAIDgGIIdWAnhU2btjJr6srQLGT3taKoQ4ihmReARUxkCqZbWAPAbkY1b4DCj4oQi/sA2pTLrEQcLKQAAAAASUVORK5CYII=',
			'0C8E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7GB0YQxlCGUMDkMRYA1gbHR0dHZDViUwRaXBtCEQRC2gVaWBEqAM7KWrptFWrQleGZiG5D00dXIwVzTxsdmBzCzY3D1T4URFicR8AKgLJzC7p3wsAAAAASUVORK5CYII=',
			'6136' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WAMYAhhDGaY6IImJTGEMYG10CAhAEgtoAapsCHQQQBZrYAhgaHR0QHZfZNSqqFVTV6ZmIbkvZApYHap5rQxg80QIiIkA9aK7hTWANRTdzQMVflSEWNwHAEhcyrqw8vFXAAAAAElFTkSuQmCC',
			'E66D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGUMdkMQCGlhbGR0dHQJQxEQaWRscHURQxRpYGxhhYmAnhUZNC1s6dWXWNCT3BTSItrI6YuhtdG0IJEIM0y3Y3DxQ4UdFiMV9AO4mzBXI6RQ2AAAAAElFTkSuQmCC',
			'92F0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA1qRxUSmsLayNjBMdUASC2gVaXRtYAgIQBFjAIoxOogguW/a1FVLl4auzJqG5D5WV4YprAh1ENjKEIAuJtDK6MCKZgfQLQ3obmENEA11BZkwCMKPihCL+wDmxMr+7RhIxgAAAABJRU5ErkJggg==',
			'9472' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QsQ2AMAwEP0V6irCPm/RGIg3TmMIbJGyQJlMSOkdQgoS/O730J6PdTvCnfOLnGeoTFzIsZBQIMxvGigRZKAzMRewkwfgdpdZWW9uMn49Bka+mWdY5Ud+2LpNCHfXm6KJewDdncWn9wf9ezIPfCQVuy7jMi4KOAAAAAElFTkSuQmCC',
			'AB83' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB1EQxhCGUIdkMRYA0RaGR0dHQKQxESmiDS6NgQ0iCCJBbSC1Dk0BCC5L2rp1LBVoauWZiG5D00dGIaGYjUPhx2obgloxXTzQIUfFSEW9wEAc9jNjLJbS0IAAAAASUVORK5CYII=',
			'5056' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7QkMYAlhDHaY6IIkFNDCGsDYwBASgiLG2sjYwOgggiQUGiDS6TmV0QHZf2LRpK1MzM1OzkN3XKtLoAFSNbB5UzEEE2Y5WkB2oYiJTGEMYHR1Q9LIGMAQwhDKguHmgwo+KEIv7AF0hy3D7ydamAAAAAElFTkSuQmCC',
			'B725' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM2QsQ2AMAwEnYINwj5OQf8UbrIBW5giGwR2IFMSOgcoQYrdnU7WyVQeo9TT/tInGIXFCQxDpjWEwNZDonXSuWWZEuk8semTWPZyLDGavurhMn1zzzHlOxuU4Lhh2Ws1YfsEXgfBxh3878N96TsBfRTMYkMRSREAAAAASUVORK5CYII=',
			'182E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUMDkMRYHVhbGR0dHZDViTqINLo2BDqg6mVtZUCIgZ20Mmtl2KqVmaFZSO4Dq2tlRNMr0ugwBYtYALoY0C0OqGKiIYwhrKGBKG4eqPCjIsTiPgAwFcaVdRH2FwAAAABJRU5ErkJggg==',
			'A3EB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB1YQ1hDHUMdkMRYA0RaWYEyAUhiIlMYGl2BYiJIYgGtDMjqwE6KWroqbGnoytAsJPehqQPD0FCs5mERw3RLQCummwcq/KgIsbgPAJWlyzSSsOrYAAAAAElFTkSuQmCC',
			'A8BC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGaYGIImxBrC2sjY6BIggiYlMEWl0bQh0YEESC2gFqXN0QHZf1NKVYUtDV2Yhuw9NHRiGhkLMY0AxD5cdqG4JaMV080CFHxUhFvcBADvWzIyF1257AAAAAElFTkSuQmCC',
			'A38D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB1YQxhCGUMdkMRYA0RaGR0dHQKQxESmMDS6NgQ6iCCJBbQygNWJILkvaumqsFWhK7OmIbkPTR0YhoZiNQ+LGKZbAlox3TxQ4UdFiMV9ALsiy1xUCoMsAAAAAElFTkSuQmCC',
			'1BC1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB1EQxhCHVqRxVgdRFoZHQKmIouJOog0ujYIhKLqFWllbWCA6QU7aWXW1LClq1YtRXYfmjqYGNA8bGIC6GIgt6CIiYaA3RwaMAjCj4oQi/sAsUrJgbKTREUAAAAASUVORK5CYII=',
			'91E1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHVqRxUSmMAawNjBMRRYLaGUFiYWiijGAxGB6wU6aNnVV1NLQVUuR3cfqiqIOAlsxxQSwiIlMwRQDuiQU6ObQgEEQflSEWNwHAAs9yNnN+WxpAAAAAElFTkSuQmCC',
			'D26C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGaYGIIkFTGFtZXR0CBBBFmsVaXRtcHRgQRFjAIoxOiC7L2rpqqVLp67MQnYfUN0UVkdHBwZUvQGsDYFoYowOIDEUO6awNqC7JTRANNQBzc0DFX5UhFjcBwDB7sybX8JcvQAAAABJRU5ErkJggg==',
			'6D34' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WANEQxhDGRoCkMREpoi0sjY6NCKLBbSINDo0BLSiiDUAxRodpgQguS8yatrKrKmroqKQ3BcyBaTO0QFFbyvIvMDQEAyxAGxuQRHD5uaBCj8qQizuAwASwtAmKaubVAAAAABJRU5ErkJggg==',
			'3FF1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVUlEQVR4nGNYhQEaGAYTpIn7RANEQ11DA1qRxQKmiDSwNjBMRVHZChYLRRGDqIPpBTtpZdTUsKWhq5aiuA9VHbJ5BMUCsOgVDYC4JWAQhB8VIRb3AQAHlMs+8btqdAAAAABJRU5ErkJggg==',
			'024A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB0YQxgaHVqRxVgDWFsZWh2mOiCJiUwRaQSKBAQgiQW0AnUGOjqIILkvaumqpSszM7OmIbkPqG4KayNcHUwsgDU0MDQExQ5GBwY0dUC3NKCLMTqIhjqgiQ1U+FERYnEfAOiuy8QWM8OlAAAAAElFTkSuQmCC',
			'6724' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7WANEQx1CGRoCkMREpjA0Ojo6NCKLBbQwNLo2BLSiiDUwtALJKQFI7ouMWjVt1cqsqCgk94VMYQhgaGV0QNEL5DNMYQwNQRFjbQCqRHOLSANQJYoYa4BIA2toAIrYQIUfFSEW9wEANv3No7J1GWMAAAAASUVORK5CYII=',
			'1421' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YWhlCgRhJjNWBYSqjo8NUZDFRB4ZQ1oaAUFS9jK4MDQEwvWAnrcxauhREILuP0UGklaEV1Q5GB9FQhynoYkB+AKYYWBzZLSEMrayhAaEBgyD8qAixuA8ALbvIQTDd9qIAAAAASUVORK5CYII=',
			'4CC3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpI37pjCGMoQ6hDogi4WwNjo6BDoEIIkxhog0uDYINIggibFOEWlgBdIBSO6bNm3aqqWrVi3NQnJfAKo6MAwNhYghm8cwBdMOhimYbsHq5oEKP+pBLO4DAEcqzShI+tjRAAAAAElFTkSuQmCC',
			'CE3D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7WENEQxmB0AFJTKRVpIG10dEhAEksoFEESAY6iCCLNQB5QHUiSO6LWjU1bNXUlVnTkNyHpg4hhm4eFjuwuQWbmwcq/KgIsbgPAA9YzBnAa15aAAAAAElFTkSuQmCC',
			'2604' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM3QMQrAIAxA0Th4g/Q+OnSPoB08TTLkBuINunjKQqdIO7a0yfYh8AiMyzD8aV/xeXIZGjCZhs0rFBDbSFFcDGobKLJnamR9vW/7qLVaHy3qOQV76wLKyqlka2GUGMNs4dMytVKu5q/+9+De+A4c4M0E3D6uigAAAABJRU5ErkJggg==',
			'8ED5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WANEQ1lDGUMDkMREpog0sDY6OiCrC2gFijUEooiB1TUEujoguW9p1NSwpasio6KQ3AdRF9AggmEeNrFABxEMtzgEILsP4maGqQ6DIPyoCLG4DwAHw8xDzmLlZgAAAABJRU5ErkJggg==',
			'E7F3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkNEQ11DA0IdkMQCGhgaXRsYHQIwxBgaRFDFWlkhNNx9oVGrpi0NXbU0C8l9QPkAJHVQMUYHVgzzWBswxUSAYqhuCQ0BiTGguHmgwo+KEIv7ANoPzVjOfHy5AAAAAElFTkSuQmCC',
			'7C00' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkMZQxmmMLSiiLayNjqEMkx1QBETaXB0dAgIQBabItLA2hDoIILsvqhpq5auisyahuQ+RgcUdWDI2oApJtKAaUdAA6ZbAhqwuHmAwo+KEIv7AEUQzF1JoAjUAAAAAElFTkSuQmCC'        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>