<?php
require_once('incl.php');
?>
<html>
<head>
<title><?php echo $businessname;?> - Job List</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div id="main">
<h3>Add/Edit Booking</h3>
<p>Please select the repair booking job from the list below to edit, or click 'Add New' to create a new job.</p>
<p align="center"><a href="addbooking.php">Create New</a><br />
<form method="GET" action="editbooking.php">
<select name="book" onChange="form.submit(); return true">
<option value=""/>------------
<?php
// get booking info from database
// we want: bid, cid, startdate, firstname, lastname

$r = $db->select("SELECT customer.cid, customer.firstname, customer.lastname, booking.bid, booking.date, booking.problem FROM booking JOIN customer ON booking.cid = customer.cid ORDER BY booking.date ASC");
while ($row=$db->get_row($r, 'MYSQL_ASSOC')) {
	$PROB = $row['problem'];
	$PROBlen = strlen($PROB);
	if($PROBlen<40){$cutprob = $PROB;} else {
		$cutprob = substr($PROB,0,39)."...";
	}

   echo "<option value=\"".$row['bid']."\"/>[".$row['bid']."] ".$row['lastname'].", ".$row['firstname']." - ".$row['date']." - ".$cutprob."\n";
} 

?>
</select></form></p>
<p align="center"><a href="javascript:history.go(-1)">&lt; Back</a> | <a href="./">Back to Home</a></p>
<p>&nbsp;</p>
</div>
</body>
</html>
