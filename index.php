<?php
require_once('incl.php');
?>
<html>
<head>
<title><?php echo $businessname;?></title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div id="main">
<img src="logo.jpg" width="170" height="156" border="0" />
<h1><?php echo $businessname;?></h1>
<p>&nbsp;</p>
<h2>Service Portal</h2>
<p>&nbsp;</p>
<ul>
<li/><a href="customer.php">Customers</a>
<li/><a href="viewbookings.php">View Jobs</a>
<ul>
<?php
$qry = "SELECT booking.bid FROM booking WHERE booking.status='i'";
$inpro = mysql_num_rows($db->select($qry));
$qry = "SELECT booking.bid FROM booking JOIN checklist ON booking.bid = checklist.bid WHERE booking.status='i' AND checklist.step0='n'";
$notyet = mysql_num_rows($db->select($qry));
$qry = "SELECT booking.bid FROM booking JOIN checklist ON booking.bid = checklist.bid WHERE booking.status='i' AND checklist.step22='n' AND checklist.step21='y'";
$ready = mysql_num_rows($db->select($qry));
$qry = "SELECT booking.bid FROM booking WHERE booking.status='c'";
$completed = mysql_num_rows($db->select($qry));
?>
<li/><a href="viewbookings.php?sts=i">View In-Progress Bookings [<strong><?php echo $inpro;?></strong>]</a>
<li/><a href="viewbookings.php?sts=n">View Jobs Not Started (Yellow Bay) [<strong><?php echo $notyet;?></strong>]</a>
<li/><a href="viewbookings.php?sts=p">View Jobs Ready for Pickup [<strong><?php echo $ready;?></strong>]</a>
<li/><a href="viewbookings.php?sts=c" title="Warning: will show ALL completed bookings!">View Completed Bookings [<strong><?php echo $completed;?></strong>]</a>
</ul>
</ul>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<h6>Version <?php echo $version." ($lastdate)";?></h6>
<h6>Copyright &copy; 2009 LG Coding.</h6>
</div>
</body>
</html>
