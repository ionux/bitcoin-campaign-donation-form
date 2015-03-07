<?php

/**
 * ©2014-2015 BITPAY, INC.
 *
 * The MIT License (MIT)
 *
 * Permission is hereby granted to any person obtaining a copy of this software
 * and associated documentation for use and/or modification in association with
 * the bitpay.com service.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * This is a simple drop-in form to allow a political campaign to accept bitcoin
 * donations using the BitPay payment service. If you don't have a BitPay merchant
 * account, you must sign up before you can use this form: https://bitpay.com
 *
 * Version 1.1, Rich Morgan <rich@bitpay.com>
 */

ob_start();
session_start();

// Edit this array to include any donation amount
// choices you want to display to potential donors.
$donation_amount_choices = array('5','10','20','30','40','50','60','70','80','90','100',);

// Page title displayed in the browser title bar area.
$page_title = 'Bitcoin Campaign Donation Form';

// Header message displayed at the top of the page.
// You can edit this as needed.
$header_message = 'Thank you for supporting our campaign with Bitcoin! Your support matters!';

// Certification checkbox statement.
$certification_statement = 'I certify that I am a citizen of the United States and my donation is neither a corporate contribution or a contribution by a foreign national.';

// Legal disclaimer statement. Edit as needed.
$legal_disclaimer = 'Contributions to this campaign are not tax deductible for tax purposes. Federal Law requires political committees to report the name, mailing address, occupation and name of employer for each individual whose contributions aggregate in excess of $200 per election cycle.  Individuals may contribute up to a maximum of $7,800 to the campaign, and a couple may contribute up to $15,600 - $2,600 per individual toward the 2014 primary, runoff and general elections. Corporate contributions and contributions by foreign nationals are prohibited.';

// Includes territories.
$states = array(
	'AL'=>'ALABAMA',
	'AK'=>'ALASKA',
	'AS'=>'AMERICAN SAMOA',
	'AZ'=>'ARIZONA',
	'AR'=>'ARKANSAS',
	'CA'=>'CALIFORNIA',
	'CO'=>'COLORADO',
	'CT'=>'CONNECTICUT',
	'DE'=>'DELAWARE',
	'DC'=>'DISTRICT OF COLUMBIA',
	'FM'=>'FEDERATED STATES OF MICRONESIA',
	'FL'=>'FLORIDA',
	'GA'=>'GEORGIA',
	'GU'=>'GUAM GU',
	'HI'=>'HAWAII',
	'ID'=>'IDAHO',
	'IL'=>'ILLINOIS',
	'IN'=>'INDIANA',
	'IA'=>'IOWA',
	'KS'=>'KANSAS',
	'KY'=>'KENTUCKY',
	'LA'=>'LOUISIANA',
	'ME'=>'MAINE',
	'MH'=>'MARSHALL ISLANDS',
	'MD'=>'MARYLAND',
	'MA'=>'MASSACHUSETTS',
	'MI'=>'MICHIGAN',
	'MN'=>'MINNESOTA',
	'MS'=>'MISSISSIPPI',
	'MO'=>'MISSOURI',
	'MT'=>'MONTANA',
	'NE'=>'NEBRASKA',
	'NV'=>'NEVADA',
	'NH'=>'NEW HAMPSHIRE',
	'NJ'=>'NEW JERSEY',
	'NM'=>'NEW MEXICO',
	'NY'=>'NEW YORK',
	'NC'=>'NORTH CAROLINA',
	'ND'=>'NORTH DAKOTA',
	'MP'=>'NORTHERN MARIANA ISLANDS',
	'OH'=>'OHIO',
	'OK'=>'OKLAHOMA',
	'OR'=>'OREGON',
	'PW'=>'PALAU',
	'PA'=>'PENNSYLVANIA',
	'PR'=>'PUERTO RICO',
	'RI'=>'RHODE ISLAND',
	'SC'=>'SOUTH CAROLINA',
	'SD'=>'SOUTH DAKOTA',
	'TN'=>'TENNESSEE',
	'TX'=>'TEXAS',
	'UT'=>'UTAH',
	'VT'=>'VERMONT',
	'VI'=>'VIRGIN ISLANDS',
	'VA'=>'VIRGINIA',
	'WA'=>'WASHINGTON',
	'WV'=>'WEST VIRGINIA',
	'WI'=>'WISCONSIN',
	'WY'=>'WYOMING',
	'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
	'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
	'AP'=>'ARMED FORCES PACIFIC'
);

$error = false;
$msg   = '';

if (isset($_POST['qqka']) && $_POST['qqka'] != '') {

    if (!isset($_POST['uscitizen']) || $_POST['uscitizen'] != 'Yes') {
        $error = true;
        $msg = 'You must check the box indicating you are a US Citizen and this is not a corporate donation or donation from a foreign national.';
    }

    if (trim($_POST['fullname']) == '' || trim($_POST['addr1']) == '' || trim($_POST['city']) == '' || trim($_POST['state']) == '' || trim($_POST['zip']) == '' || trim($_POST['email']) == '' || trim($_POST['phone']) == '' || trim($_POST['employer']) == '' || trim($_POST['occupation']) == '') {
        $error = true;
        $msg = 'You must fill in your complete personal information.';
    }

    if (base64_decode(trim($_POST['qqka'])) != 'donation_form') {
        $error = true;
        $msg = 'Unknown form posting.';
    } else if (base64_decode(trim($_POST['qqka'])) == 'donation_form' && $error == false) {
        require_once('bp_lib.php');
        $options = array(
                        'itemDesc'      => 'Bitcoin Campaign Donation',
                        'itemCode'      => 'bitcoindonation',
                        'posData'       => trim(htmlspecialchars($_POST['employer'])) . ', ' . trim(htmlspecialchars($_POST['occupation'])),
                        'buyerName'     => trim(htmlspecialchars($_POST['fullname'])),
                        'buyerAddress1' => trim(htmlspecialchars($_POST['addr1'])),
                        'buyerAddress2' => trim(htmlspecialchars($_POST['addr2'])),
                        'buyerCity'     => trim(htmlspecialchars($_POST['city'])),
                        'buyerState'    => trim(htmlspecialchars($_POST['state'])),
                        'buyerZip'      => trim(htmlspecialchars($_POST['zip'])),
                        'buyerEmail'    => trim(htmlspecialchars($_POST['email'])),
                        'buyerPhone'    => trim(htmlspecialchars($_POST['phone'])),
                        );
        $orderid = bin2hex(openssl_random_pseudo_bytes(8));
        $amount  = trim(htmlspecialchars($_POST['amt']));

        if (strlen($options['posData']) > 100) {
               $options['posData'] = substr($options['posData'],0,99);
        }

        $invoice = bpCreateInvoice($orderId, $amount, $posData, $options);
        
        if (isset($invoice['url']) && trim($invoice['url']) != '') {
            header('Location: ' . $invoice['url']);
        } else {
            $error = true;
            $msg = 'There was an error processing your request.  Try submitting the form again and contact support if this error continues.';
        }
        
    }    
}

if (!$error) {
    $_SESSION['salt'] = bin2hex(openssl_random_pseudo_bytes(16));
}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
  <head>
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="This is a simple drop-in form to allow a political campaign or nonprofit organization to accept bitcoin donations using the BitPay payment service." />
    <meta name="keywords" content="bitcoin, campaign, donation, form, political, support, bitpay, nonprofit" />
    <link href="//fonts.googleapis.com/css?family=Ubuntu+Mono:700|Ubuntu:300,400,400italic,500" rel="stylesheet" type="text/css">
    <link href="donation_page.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 7]>
    <style media="screen" type="text/css">
    .col1 {width:100%;}
    </style>
    <![endif]-->
    </head>
    <body>
    <?php if ($error) { echo '<div id="header"><center><b>Error: ' . $msg . '</b></center></div>'; $error=false; $msg=''; } ?>
    <div class="colmask holygrail">
    <div class="colmid">
        <div class="colleft">
            <div class="col1wrap">
                <div class="col1">
		    <h2><?php echo $header_message; ?></h2>
                    <form method="post" action="index.php">
                    <table border="0">
                    <tr style="padding:15px;"><td style="padding:15px;">Contribution Amount:</td><td style="padding:15px;">
                    	<select name="amt" style="font-size: 14pt;">
                    	<?php
                    		foreach ($donation_amount_choices as $key => $value) {
                    			echo '			<option value="' . $value . '"';
                    			if (isset($_POST['amt']) && $_POST['amt'] == $value) {
                    				echo ' selected';
                    			}
                    			echo '>$' . $value . '</option>';
                    		}
			?>
                        </select></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Full Name:</td><td style="padding:15px;"><input type="text" name="fullname" value="<?php if(isset($_POST['fullname'])) echo $_POST['fullname']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Address 1:</td><td style="padding:15px;"><input type="text" name="addr1" value="<?php if(isset($_POST['addr1'])) echo $_POST['addr1']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Address 2:</td><td style="padding:15px;"><input type="text" name="addr2" value="<?php if(isset($_POST['addr2'])) echo $_POST['addr2']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">City:</td><td style="padding:15px;"><input type="text" name="city" value="<?php if(isset($_POST['city'])) echo $_POST['city']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">State:</td><td style="padding:15px;">
                    	<select name="state" style="font-size: 14pt;">
                    	<?php
                    		foreach ($states as $key => $value) {
                    			echo '			<option value="' . $key . '"';
                    			if (isset($_POST['state']) && $_POST['state'] == $key) {
                    				echo ' selected';
                    			}
                    			echo '>$' . ucwords(strtolower($value)) . '</option>';
                    		}
			?>
		    	</select>
                    </td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Zip Code:</td><td style="padding:15px;"><input type="text" name="zip" value="<?php if(isset($_POST['zip'])) echo $_POST['zip']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Email Address:</td><td style="padding:15px;"><input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Phone Number:</td><td style="padding:15px;"><input type="text" name="phone" value="<?php if(isset($_POST['phone'])) echo $_POST['phone']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Employer:</td><td style="padding:15px;"><input type="text" name="employer" value="<?php if(isset($_POST['employer'])) echo $_POST['employer']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Occupation:</td><td style="padding:15px;"><input type="text" name="occupation" value="<?php if(isset($_POST['occupation'])) echo $_POST['occupation']; ?>"></td></tr>
                    </table>
                    <p><br></p>
                    <table border="0">
                    <tr><td><input type="checkbox" name="uscitizen" value="Yes" style="height: 12px; width: 12px;"> <?php echo $certification_statement; ?></td></tr>
                    </table>
                    <p><br></p>
                    <table border="0">
                    <tr><td><input type="submit" name="submit" value="Submit" style="height: 44px; width: 104px; border-radius:4px;"></td></tr>
                    </table>
                    <input type="hidden" name="qqka" value="<?php echo base64_encode('donation_form'); ?>">
                    <input type="hidden" name="fjqq" value="<?php if (isset($_SESSION['salt'])) { echo $_SESSION['salt'];} else {$_SESSION['salt']=bin2hex(openssl_random_pseudo_bytes(16));echo $_SESSION['salt']; } ?>">
                    </form>
                    <p><br></p>
                    <table border="0">
                    <tr><td><p style="font-size:10pt;"><?php echo $legal_disclaimer; ?></p></td></tr>
                    </table>
                </div>
            </div>
            <div class="col2">
		<h2></h2>
                <p></p>
            </div>
            <div class="col3">
                <h2></h2>
		<p></p>
		<h3></h3>
	    </div>
	</div>
    </div>
</div>
    </div>
    <div id="footer">
    </div>
    </body>
</html>
