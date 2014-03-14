<?php
require_once('incl.php');

// show list of bookings, sorted by date (most recent first)

?>
<html>
<head>
<title><?php echo $businessname;?> - Job List</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div id="main">
<h3>Bookings List</h3>
<?php
if(!ISSET($_GET['sts'])) { $sitestatus='i'; } else { $sitestatus=$_GET['sts'];}
switch ($sitestatus) {
	case "i":
		$qry = "SELECT booking.bid, booking.cid, booking.problem, booking.date, customer.firstname, customer.lastname FROM booking JOIN customer ON booking.cid = customer.cid JOIN checklist ON booking.bid = checklist.bid WHERE booking.status='i' ORDER BY booking.date DESC  ";
		$type = "View current jobs (status: in progress)";
	break;
	case "c":
		$qry = "SELECT booking.bid, booking.cid, booking.problem, booking.date, customer.firstname, customer.lastname FROM booking JOIN customer ON booking.cid = customer.cid JOIN checklist ON booking.bid = checklist.bid WHERE booking.status='c' ORDER BY booking.date DESC  ";
		$type = "View finished jobs (status: completed)";	
	break;
	case "n":
		$qry = "SELECT booking.bid, booking.cid, booking.problem, booking.date, customer.firstname, customer.lastname FROM booking JOIN customer ON booking.cid = customer.cid JOIN checklist ON booking.bid = checklist.bid WHERE booking.status='i' AND checklist.step0='n' ORDER BY booking.date DESC  ";
		$type = "View jobs not yet started (Yellow Bay)";
	break;
	case "p":
		$qry = "SELECT booking.bid, booking.cid, booking.problem, booking.date, customer.firstname, customer.lastname FROM booking JOIN customer ON booking.cid = customer.cid JOIN checklist ON booking.bid = checklist.bid WHERE booking.status='i' AND checklist.step22='n' AND checklist.step21='y' ORDER BY booking.date DESC  ";
		$type = "View jobs ready for pickup (Green Bay)";
	break;
	default:
		$qry = "SELECT booking.bid, booking.cid, booking.problem, booking.date, customer.firstname, customer.lastname FROM booking JOIN customer ON booking.cid = customer.cid JOIN checklist ON booking.bid = checklist.bid WHERE booking.status='i' ORDER BY booking.date DESC  ";
		$type = "View current jobs (status: in progress)";
}

?>
<p><?php echo $type;?> in the system. Click the 'view' link to see that bookings information, or 'work' to work on that job..</p>
<table>
<tr class="nor"><th>Booking ID</th><th>Customer Name</th><th>Problem</th><th>Date Started</th><th>Action</th></tr>
<?php
$r = $db->select($qry);
$i=0;
while ($row = $db->get_row($r)) {
	echo "<tr class=\"";
	if($i%2){echo "nor";} else {echo "alt";}
	echo "\"><td>".$row['bid']."</td>";
	echo '<td><a href="editcustomer.php?cust='.$row['cid'].'">'.$row['firstname'].' '.$row['lastname'].'</a></td>';
	if(strlen($row['problem'])<60) {
		echo "<td>".$row['problem']."</td>";
	} else {
		$cut = substr($row['problem'],0,60)."...";
		echo "<td>$cut</td>";	
	}
	echo "<td>".$row['date']."</td>";
	echo "<td>[<a href=\"editbooking.php?book=".$row['bid']."\">view</a>]<br />[<a href=\"workchecklist.php?book=".$row['bid']."\">work</a>]</td>";


}



?>
<tr><td colspan="5" class="none">&nbsp;</td></tr>
<tr><td colspan="5" class="center none"> <a href="viewbookings.php?sts=i">View In-Progress Bookings</a> | <a href="viewbookings.php?sts=n">View Jobs Not Started (Yellow Bay)</a> <br /> <a href="viewbookings.php?sts=p">View Jobs Ready for Pickup</a> | <a href="viewbookings.php?sts=c" title="Warning: will show ALL completed bookings!">View Completed Bookings</a><br /><a href="./">Back to Home</a> 
</td></tr>
</table>
</div>
</body>
</html>
