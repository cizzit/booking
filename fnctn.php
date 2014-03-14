<?php

/**********************************************************
NAME: send_SMS()
PURPOSE: sends an SMS to a customer using Exetel's API
ARGUEMENTS: mobile number, total of job, customer id number, job id number
RETURNS: string, message from Exetel API
**********************************************************/
function send_SMS($mobnum, $total, $custno, $jobno) {
	if(!$mobnum || !$total || !$custno || !$jobno) {return "0||||Invalid Input";}

	// if $total has a '$' in front, strip that out.
	if(substr($total,0)=='$'){ $total = substr($total,1); }

	$mobnum = str_replace(" ","",$mobnum); // remove spaces (if any) from mobile number

	$msg = htmlentities("Your system is ready to pickup.Total $".$total." inc GST. Q? Call xxxxxxxx. Thx :)");
	$msg = str_replace(" ","%20",$msg);

	// set ourref value
	$ourref = "c".$custno."j".$jobno; //maximum of 50 characters, will allow for a 24 character customer number and a 24 character job number
                                        // ie: customer or job number from 1 up to 999,999,999,999,999,999,999,999 (not including commas)

	$gourl = "https://smsgw.exetel.com.au/sendsms/api_sms.php"; 		// base URL
	$gourl .= "?username=EXETELUSERNAME&password=EXETELUSERPASSWORD";   // company username/password (NOT ACCOUNT, BUT SPECIAL SMS ONES)
	$gourl .= "&mobilenumber=".$mobnum;                         		// home numbers dont work
	$gourl .= "&message=".$msg;                                 		// the message
	$gourl .= "&sender=04xxxxxxxx";                             		// this is the company authorised sending mobile number
	$gourl .= "&messagetype=Text";                              		// type is ALWAYS Text (capital T is required)
	$gourl .= "&referencenumber=".$ourref;                      		// our reference which _may_ appear on the bill. This gets recorded in the SMS Client step comment

	// going to use cURL
	$ch = curl_init($gourl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);             // return as string
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);            // since we are accessing a https page
	$rawr = curl_exec($ch);                                     // get the output into a string
	curl_close($ch);                                            // close the connection

	// $rawr holds the raw output. We will pass this output back to the requesting page for processing
	return $rawr;	
}

?>
