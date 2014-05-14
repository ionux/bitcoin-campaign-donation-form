<strong>(c)2014 BITPAY, INC.</strong>

Permission is hereby granted to any person obtaining a copy of this software and associated documentation for use and/or modification in association with the bitpay.com service.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

This is a simple drop-in form to allow a political campaign to accept bitcoin donations using the BitPay service.


Installation
------------
Download the zip file comprising index.php, error.png and the two library files. Create a direcory on your webserver and upload these files to that directory.  Since this package contains an index file, DO NOT upload it to the root folder for your website or it will overwrite your existing index page.  You must create a subfolder and upload these form files there!

For example, you could create a /bitcoin folder and upload the files there so the web address for the donation form would be:

<pre>http://your_web_site_address/bitcoin/</pre>

Next, on your main campaign website create a new post telling your supporters you now support Bitcoin donations and link to the donation form.  That's all there is to it! :D


Configuration
-------------
0. If you are not signed up to use the BitPay service, you must do that first at https://bitpay.com<br />
1. Create an API key by logging into your Merchant Dashboard and clicking My Account > API Access Keys > Add New API Key.<br />
2. Open the bp_options.php file and add your API key to the $bpOptions['apiKey'] variable.<br />
a. Be sure the API key string is inside the single quote marks.<br />
3. Scroll down a few lines and your website address to the $bpOptions['redirectURL'] variable.<br />
a. Be sure the API key string is inside the single quote marks.<br />
4. Save the file.
5. If you have edited this file outside of your webserver (like on your desktop or laptop), upload the new file to the form donation directory you created upon installation.

<i><strong>Note:</strong> The other options in this file are pre-set to values that make the most sense for this usage scenario. Unless you absolutely know what you are doing, you should keep these values the same.</i>


Usage
-----
When a supporter chooses the Bitcoin donation method on your campaign website, they will be shown the form which will collect their personal information and allow them to select a donation amount.  All fields except for "Address 2" are required and the person wishing to donate must check the box certifying they are not a foreign national or making a corporate contribution.  If any personal information fields are blank or the last statement is not agreed to, the donation form will not process their donation request.  Any errors will appear in a yellow banner at the top of the page letting the person know what the problem was and how to correct it.

Once the form is completely filled out and they check the statement box, an invoice will be created for this donation amount and they will be shown a summary of all the payment details.  Since this is for Bitcoin donations, the contributor will be given a Bitcoin address to which they will send their payment.

After the payment is made successfully, the contributor will be redirect back to your campaign website that you specified in the options file.


Troubleshooting
----------------
If you are a web developer implementing this donation form for a campaign, the official BitPay API documentation should always be your first reference for development, errors and troubleshooting:
https://bitpay.com/downloads/bitpayApi.pdf

Some web servers have outdated root CA certificates and will cause this curl error: "SSL certificate problem, verify that the CA cert is OK. Details: error:14090086:SSL routines:SSL3_GET_SERVER_CERTIFICATE:certificate verify failed'".  The fix is to contact your hosting provider or server administrator and request a root CA cert update.

If you have turned on logging in the options file, the log file is named 'bplog.txt' and can be found in the same directory as the donation form files.  Checking this log file will give you exact responses from the BitPay network, in case of failures.

Check the version of this donation form against the official repository to ensure you are using the latest version. Your issue might have been addressed in a newer version.  You can always find the latest version here: https://github.com/ionux/bitcoin-campaign-donation-form/

If all else fails, send an email describing your issue *in detail* to support@bitpay.com and attach the bplog.txt file (if present).


Version
-------
Version 1.0
- Initial release 5/14/2014 -rich@bitpay.com


Important Disclaimer
--------------------
1. First of all, this is form is for accepting donations for political campaigns operating inside the United States.
2. While I have attempted to ensure this form collects the correct information to abide by FEC donation guidelines, I AM NOT A LAWYER and make no representation that this form is accurate.  The legal burden to be compliant with all election laws and guidelines is upon the campaign.
3. If you have legal questions about using this form or accepting certain types of donations for a political campaign, consult a lawyer qualified to answer such questions.  See: http://www.americanbar.org/groups/public_education/public-information/how-do-i-find-a-lawyer-.html
4. THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
5. By downloading and using this software, you agree to these terms.  IF you do not agree to these terms, do not use the software and delete all copies from your server.
