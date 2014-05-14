<?php

/**
 * ©2014 BITPAY, INC.
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
 * This is a simple drop-in form to allow a political campaign to accept bitcoin donations using the BitPay service.
 *
 * Version 1.0, rich@bitpay.com
 * 
 */

ob_start();
session_start();

$error=false;
$msg='';

if(isset($_POST['qqka']) && $_POST['qqka'] != '') {

    if(!isset($_POST['uscitizen']) || $_POST['uscitizen'] != 'Yes') {
        $error = true;
        $msg = 'You must check the box indicating you are a US Citizen and this is not a corporate donation or donation from a foreign national.';
    }

    if(trim($_POST['fullname']) == '' || trim($_POST['addr1']) == '' || trim($_POST['city']) == '' || trim($_POST['state']) == '' || trim($_POST['zip']) == '' || trim($_POST['email']) == '' || trim($_POST['phone']) == '' || trim($_POST['employer']) == '' || trim($_POST['occupation']) == '') {
        $error = true;
        $msg = 'You must fill in your complete personal information.';
    }

    if(base64_decode(trim($_POST['qqka'])) != 'donation_form') {
        $error = true;
        $msg = 'Unknown form posting.';
    } elseif(base64_decode(trim($_POST['qqka'])) == 'donation_form' && $error == false) {
        require_once('bp_lib.php');
        $options = array(
                        'itemDesc' => 'Bitcoin Campaign Donation',
                        'itemCode' => 'bitcoindonation',
                        'posData' => trim(htmlspecialchars($_POST['employer'])) . ', ' . trim(htmlspecialchars($_POST['occupation'])),
                        'buyerName' => trim(htmlspecialchars($_POST['fullname'])),
                        'buyerAddress1' => trim(htmlspecialchars($_POST['addr1'])),
                        'buyerAddress2' => trim(htmlspecialchars($_POST['addr2'])),
                        'buyerCity' => trim(htmlspecialchars($_POST['city'])),
                        'buyerState' => trim(htmlspecialchars($_POST['state'])),
                        'buyerZip' => trim(htmlspecialchars($_POST['zip'])),
                        'buyerEmail' => trim(htmlspecialchars($_POST['email'])),
                        'buyerPhone' => trim(htmlspecialchars($_POST['phone'])),
                        );
        $orderid = bin2hex(openssl_random_pseudo_bytes(8));
        $amount = trim(htmlspecialchars($_POST['amt']));

        if(strlen($options['posData']) > 100)
               $options['posData'] = substr($options['posData'],0,99);
        
        $invoice = bpCreateInvoice($orderId, $amount, $posData, $options);
        
        if(isset($invoice['url']) && trim($invoice['url']) != '')
            header('Location: ' . $invoice['url']);
        else {
            $error = true;
            $msg = 'There was an error processing your request.  Try submitting the form again and contact support if this error continues.';
        }
        
    }    
}

if(!$error)
    $_SESSION['salt'] = bin2hex(openssl_random_pseudo_bytes(16));

?>
<html>
    <head>
        <title>Bitcoin Campaign Donation Form</title>
        <meta name="description" content="This is a simple drop-in form to allow a political campaign to accept bitcoin donations using the BitPay service.." />
        <meta name="keywords" content="bitcoin, campaign, donation, form, political, support" />
        <link href='//fonts.googleapis.com/css?family=Ubuntu+Mono:700|Ubuntu:300,400,400italic,500' rel='stylesheet' type='text/css'>
        <style type="text/css">
    /* <!-- */
    body {
        margin:0;
        padding:0;
        border:0;
        width:100%;
        background:#002855;
        font-family: 'Ubuntu';
        min-width:600px;
		font-size:90%;
    }
	a {
    	color:#369;
	}
	a:hover {
		color:#fff;
		background:#369;
		text-decoration:none;
	}
    h1, h2, h3 {
        margin:.8em 0 .2em 0;
        padding:0;
    }
    p {
        margin:.4em 0 .8em 0;
        padding:0;
    }
	img {
		margin:10px 0 5px;
	}
    input {
        border-radius:4px;
        height: 30px;
        width:  300px;
        font-size: 14pt;
        font-family: 'Ubuntu';
    }
    select {
        width: 240px;
        height: 34px;
        overflow: hidden;
        border: 1px solid #ccc;
    }
    #header {
        clear:both;
        float:left;
        width:100%;
    }
	#header {
		border-bottom:0px solid #000;
        background:#FFCC00;
        padding-top: 5px;
        padding-bottom: 10px;
	}
	#header p,
	#header h1,
	#header h2 {
	    padding:.4em 15px 0 15px;
        margin:0;
	}
	#header ul {
	    clear:left;
	    float:left;
	    width:100%;
	    list-style:none;
	    margin:10px 0 0 0;
	    padding:0;
	}
	#header ul li {
	    display:inline;
	    list-style:none;
	    margin:0;
	    padding:0;
	}
	#header ul li a {
	    display:block;
	    float:left;
	    margin:0 0 0 1px;
	    padding:3px 10px;
	    text-align:center;
	    background:#ffffff;
	    color:#000;
	    text-decoration:none;
	    position:relative;
	    left:15px;
		line-height:1.3em;
	}
	#header ul li a:hover {
	    background:#369;
		color:#fff;
	}
	#header ul li a.active,
	#header ul li a.active:hover {
	    color:#fff;
	    background:#000;
	    font-weight:bold;
	}
	#header ul li a span {
	    display:block;
	}
	#layoutdims {
		clear:both;
		background:#f3f3f3;
		border-top:0px solid #000;
		margin:0;
		padding:6px 15px !important;
		text-align:right;
	}
	.colmask {
		position:relative;
	    clear:both;
	    float:left;
        width:100%;
		overflow:hidden;
	}
	.holygrail {
	    background:#002855;
	}
    .holygrail .colmid {
        float:left;
        width:200%;
        margin-left:-200px;
        position:relative;
        right:100%;
        background:#ffffff;
    }
    .holygrail .colleft {
        float:left;
        width:100%;
        margin-left:-50%;
        position:relative;
        left:400px;
        background:#002855;
    }
    .holygrail .col1wrap {
        float:left;
	    width:50%;
	    position:relative;
	    right:200px;
	    padding-bottom:1em;
	}
	.holygrail .col1 {
        margin:0 215px;
        position:relative;
	    left:200%;
	    overflow:hidden;
	}
    .holygrail .col2 {
        float:left;
        float:right;
        width:200px;
        position:relative;
        right:15px;
    }
    .holygrail .col3 {
        float:left;
        float:right;
        width:200px;
        margin-right:45px;
        position:relative;
        left:50%;
    }
	#footer {
        clear:both;
        float:left;
        width:100%;
		border-top:50px solid #002855;
        background:#002855;
        font-size: 10pt;
    }
    #footer p {
        padding:10px;
        margin:0;
    }
    /* --> */
    </style>
    <!--[if lt IE 7]>
    <style media="screen" type="text/css">
    .col1 {
	    width:100%;
	}
    </style>
    <![endif]-->
    </head>
    <body>
    <?php if($error) echo '<div id="header"><center><img src="error.png" height="24" width="24" alt="Error Icon" /><br><b>Error: ' . $msg . '</b></center></div>'; $error=false; $msg=''; ?>
    <div class="colmask holygrail">
    <div class="colmid">
        <div class="colleft">
            <div class="col1wrap">
                <div class="col1">
					<h2>Thank you for supporting our campaign with Bitcoin! Your support matters!</h2>
                    <form method="post" action="index.php">
                    <table border="0">
                    <tr style="padding:15px;"><td style="padding:15px;">Contribution Amount:</td><td style="padding:15px;"><select name="amt" style="font-size: 14pt;">
                                                            <option value="10"<?php if($_POST['amt'] == '10') echo ' selected'; ?>>$10</option>
                                                            <option value="20"<?php if($_POST['amt'] == '20') echo ' selected'; ?>>$20</option>
                                                            <option value="30"<?php if($_POST['amt'] == '30') echo ' selected'; ?>>$30</option>
                                                            <option value="40"<?php if($_POST['amt'] == '40') echo ' selected'; ?>>$40</option>
                                                            <option value="50"<?php if($_POST['amt'] == '50') echo ' selected'; ?>>$50</option>
                                                            <option value="60"<?php if($_POST['amt'] == '60') echo ' selected'; ?>>$60</option>
                                                            <option value="70"<?php if($_POST['amt'] == '70') echo ' selected'; ?>>$70</option>
                                                            <option value="80"<?php if($_POST['amt'] == '80') echo ' selected'; ?>>$80</option>
                                                            <option value="90"<?php if($_POST['amt'] == '90') echo ' selected'; ?>>$90</option>
                                                            <option value="100"<?php if($_POST['amt'] == '100') echo ' selected'; ?>>$100</option>
                                                         </select></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Full Name:</td><td style="padding:15px;"><input type="text" name="fullname" value="<?php echo $_POST['fullname']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Address 1:</td><td style="padding:15px;"><input type="text" name="addr1" value="<?php echo $_POST['addr1']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Address 2:</td><td style="padding:15px;"><input type="text" name="addr2" value="<?php echo $_POST['addr2']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">City:</td><td style="padding:15px;"><input type="text" name="city" value="<?php echo $_POST['city']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">State:</td><td style="padding:15px;">
                    <select name="state" style="font-size: 14pt;">
    <option value="AL"<?php if($_POST['state'] == 'AL') echo ' selected'; ?>>Alabama</option>
	<option value="AK"<?php if($_POST['state'] == 'AK') echo ' selected'; ?>>Alaska</option>
	<option value="AZ"<?php if($_POST['state'] == 'AZ') echo ' selected'; ?>>Arizona</option>
	<option value="AR"<?php if($_POST['state'] == 'AR') echo ' selected'; ?>>Arkansas</option>
	<option value="CA"<?php if($_POST['state'] == 'CA') echo ' selected'; ?>>California</option>
	<option value="CO"<?php if($_POST['state'] == 'CO') echo ' selected'; ?>>Colorado</option>
	<option value="CT"<?php if($_POST['state'] == 'CT') echo ' selected'; ?>>Connecticut</option>
	<option value="DE"<?php if($_POST['state'] == 'DE') echo ' selected'; ?>>Delaware</option>
	<option value="DC"<?php if($_POST['state'] == 'DC') echo ' selected'; ?>>District Of Columbia</option>
	<option value="FL"<?php if($_POST['state'] == 'FL') echo ' selected'; ?>>Florida</option>
	<option value="GA"<?php if($_POST['state'] == 'GA') echo ' selected'; ?>>Georgia</option>
	<option value="HI"<?php if($_POST['state'] == 'HI') echo ' selected'; ?>>Hawaii</option>
	<option value="ID"<?php if($_POST['state'] == 'ID') echo ' selected'; ?>>Idaho</option>
	<option value="IL"<?php if($_POST['state'] == 'IL') echo ' selected'; ?>>Illinois</option>
	<option value="IN"<?php if($_POST['state'] == 'IN') echo ' selected'; ?>>Indiana</option>
	<option value="IA"<?php if($_POST['state'] == 'IA') echo ' selected'; ?>>Iowa</option>
	<option value="KS"<?php if($_POST['state'] == 'KS') echo ' selected'; ?>>Kansas</option>
	<option value="KY"<?php if($_POST['state'] == 'KY') echo ' selected'; ?>>Kentucky</option>
	<option value="LA"<?php if($_POST['state'] == 'LA') echo ' selected'; ?>>Louisiana</option>
	<option value="ME"<?php if($_POST['state'] == 'ME') echo ' selected'; ?>>Maine</option>
	<option value="MD"<?php if($_POST['state'] == 'MD') echo ' selected'; ?>>Maryland</option>
	<option value="MA"<?php if($_POST['state'] == 'MA') echo ' selected'; ?>>Massachusetts</option>
	<option value="MI"<?php if($_POST['state'] == 'MI') echo ' selected'; ?>>Michigan</option>
	<option value="MN"<?php if($_POST['state'] == 'MN') echo ' selected'; ?>>Minnesota</option>
	<option value="MS"<?php if($_POST['state'] == 'MS') echo ' selected'; ?>>Mississippi</option>
	<option value="MO"<?php if($_POST['state'] == 'MO') echo ' selected'; ?>>Missouri</option>
	<option value="MT"<?php if($_POST['state'] == 'MT') echo ' selected'; ?>>Montana</option>
	<option value="NE"<?php if($_POST['state'] == 'NE') echo ' selected'; ?>>Nebraska</option>
	<option value="NV"<?php if($_POST['state'] == 'NV') echo ' selected'; ?>>Nevada</option>
	<option value="NH"<?php if($_POST['state'] == 'NH') echo ' selected'; ?>>New Hampshire</option>
	<option value="NJ"<?php if($_POST['state'] == 'NJ') echo ' selected'; ?>>New Jersey</option>
	<option value="NM"<?php if($_POST['state'] == 'NM') echo ' selected'; ?>>New Mexico</option>
	<option value="NY"<?php if($_POST['state'] == 'NY') echo ' selected'; ?>>New York</option>
	<option value="NC"<?php if($_POST['state'] == 'NC') echo ' selected'; ?>>North Carolina</option>
	<option value="ND"<?php if($_POST['state'] == 'ND') echo ' selected'; ?>>North Dakota</option>
	<option value="OH"<?php if($_POST['state'] == 'OH') echo ' selected'; ?>>Ohio</option>
	<option value="OK"<?php if($_POST['state'] == 'OK') echo ' selected'; ?>>Oklahoma</option>
	<option value="OR"<?php if($_POST['state'] == 'OR') echo ' selected'; ?>>Oregon</option>
	<option value="PA"<?php if($_POST['state'] == 'PA') echo ' selected'; ?>>Pennsylvania</option>
	<option value="RI"<?php if($_POST['state'] == 'RI') echo ' selected'; ?>>Rhode Island</option>
	<option value="SC"<?php if($_POST['state'] == 'SC') echo ' selected'; ?>>South Carolina</option>
	<option value="SD"<?php if($_POST['state'] == 'SD') echo ' selected'; ?>>South Dakota</option>
	<option value="TN"<?php if($_POST['state'] == 'TN') echo ' selected'; ?>>Tennessee</option>
	<option value="TX"<?php if($_POST['state'] == 'TX') echo ' selected'; ?>>Texas</option>
	<option value="UT"<?php if($_POST['state'] == 'UT') echo ' selected'; ?>>Utah</option>
	<option value="VT"<?php if($_POST['state'] == 'VT') echo ' selected'; ?>>Vermont</option>
	<option value="VA"<?php if($_POST['state'] == 'VA') echo ' selected'; ?>>Virginia</option>
	<option value="WA"<?php if($_POST['state'] == 'WA') echo ' selected'; ?>>Washington</option>
	<option value="WV"<?php if($_POST['state'] == 'WV') echo ' selected'; ?>>West Virginia</option>
	<option value="WI"<?php if($_POST['state'] == 'WI') echo ' selected'; ?>>Wisconsin</option>
	<option value="WY"<?php if($_POST['state'] == 'WY') echo ' selected'; ?>>Wyoming</option>
    <option value="AS"<?php if($_POST['state'] == 'AS') echo ' selected'; ?>>American Samoa</option>
    <option value="GU"<?php if($_POST['state'] == 'GU') echo ' selected'; ?>>Guam</option>
    <option value="MP"<?php if($_POST['state'] == 'MP') echo ' selected'; ?>>Northern Mariana Islands</option>
    <option value="PR"<?php if($_POST['state'] == 'PR') echo ' selected'; ?>>Puerto Rico</option>
    <option value="UM"<?php if($_POST['state'] == 'UM') echo ' selected'; ?>>United States Minor Outlying Islands</option>
    <option value="VI"<?php if($_POST['state'] == 'VI') echo ' selected'; ?>>Virgin Islands</option>
    <option value="AA"<?php if($_POST['state'] == 'AA') echo ' selected'; ?>>Armed Forces Americas</option>
    <option value="AP"<?php if($_POST['state'] == 'AP') echo ' selected'; ?>>Armed Forces Pacific</option>
    <option value="AE"<?php if($_POST['state'] == 'AE') echo ' selected'; ?>>Armed Forces Others</option>
</select>
                    </td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Zip Code:</td><td style="padding:15px;"><input type="text" name="zip" value="<?php echo $_POST['zip']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Email Address:</td><td style="padding:15px;"><input type="text" name="email" value="<?php echo $_POST['email']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Phone Number:</td><td style="padding:15px;"><input type="text" name="phone" value="<?php echo $_POST['phone']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Employer:</td><td style="padding:15px;"><input type="text" name="employer" value="<?php echo $_POST['employer']; ?>"></td></tr>
                    <tr style="padding:15px;"><td style="padding:15px;">Occupation:</td><td style="padding:15px;"><input type="text" name="occupation" value="<?php echo $_POST['occupation']; ?>"></td></tr>
                    </table>
                    <p><br></p>
                    <table border="0">
                    <tr><td><input type="checkbox" name="uscitizen" value="Yes" style="height: 12px; width: 12px;"> I certify that I am a citizen of the United States and my donation is neither a corporate contribution or a contribution by a foreign national.</td></tr>
                    </table>
                    <p><br></p>
                    <table border="0">
                    <tr><td><input type="submit" name="submit" value="Submit" style="height: 44px; width: 104px; border-radius:4px;"></td></tr>
                    </table>
                    <input type="hidden" name="qqka" value="<?php echo base64_encode('donation_form'); ?>">
                    <input type="hidden" name="fjqq" value="<?php echo $_SESSION['salt']; ?>">
                    </form>
                    <p><br></p>
                    <table border="0">
                    <tr><td><p style="font-size:10pt;">Contributions to this campaign are not tax deductible for tax purposes. Federal Law requires political committees to report the name, mailing address, occupation and name of employer for each individual whose contributions aggregate in excess of $200 per election cycle.  Individuals may contribute up to a maximum of $7,800 to the campaign, and a couple may contribute up to $15,600 - $2,600 per individual toward the 2014 primary, runoff and general elections. Corporate contributions and contributions by foreign nationals are prohibited.</p></td></tr>
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
