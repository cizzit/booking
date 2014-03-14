<?php
require_once('incl.php');
$soup = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
?>
<html>
<head>
<title><?php echo $businessname;?> - Customer List</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div id="main">
<h3>Add/Edit Customer Information</h3>
<p>Use the list below to find the customer you require, or click 'Add New' to create a new customer.</p>
<p align="center"><a href="addcustomer.php">Add New</a><br />
<br />&middot; 
<?php
for($o=0;$o<count($soup);$o++) {
	echo "<a href=\"customer.php?cust=".$soup[$o]."\";>";
	if($_GET['cust']==$soup[$o]) {echo "<strong class=\"rage\">".strtoupper($soup[$o])."</strong>";} else { echo strtoupper($soup[$o]); }
	echo "</a> &middot; ";
}
?>
<br /><br />
<form method="GET">
<select name="cust" onChange="form.submit(); return true">
<option value=""/>------------
<?php
/* new code */
for($u=0;$u<count($soup);$u++){
	echo "<option value=\"$soup[$u]\"";
	if($_GET['cust']==$soup[$u]) echo "selected=\"selected\"";
	echo "/>".strtoupper($soup[$u])."\n";
}

/* old code
// get customer info from database
// we want: cid, firstname, lastname, primnum

$r = $db->select("SELECT cid, firstname, lastname, primnum FROM customer ORDER BY lastname ASC");
while ($row=$db->get_row($r, 'MYSQL_ASSOC')) {
   echo "<option value=\"".$row['cid']."\"/>".$row['lastname'].", ".$row['firstname']." - ".$row['primnum']." [".$row['cid']."]\n";
} 
*/
?>
</select></form></p>
<?php
if(ISSET($_GET['cust'])){
	$selected = $_GET['cust'];
	if(strlen($selected)>1){
		header("Location: customer.php");
	}
?>
<table>
<tr><th>Cust ID</th><th>Name</th><th>Primary Contact No.</th><th>Company</th><th>Email</th><th>Customer Action</th></tr>
<?php
$r = $db->select("SELECT cid, firstname, lastname, primnum, company, email FROM customer ORDER BY lastname ASC");
$i=0;
while($row=$db->get_row($r, 'MYSQL_ASSOC')) {
	$custid = $row['cid'];
	$first = $row['firstname'];
	$last = $row['lastname'];
	$primary = $row['primnum'];
	$company = $row['company'];
	$email = $row['email'];

	$chkval = strtolower(substr($last,0,1));
	
	if($chkval == $selected) {
		// their last name starts with the letter we are looking for!
		// use it
		echo "<tr class=\"";
		if($i%2){echo "nor";}else{echo"alt";}
		echo "\" ><td>$custid</td>";
		echo "<td class=\"left\">$last, $first</td>";
		echo "<td class=\"left\">$primary</td>";
		echo "<td class=\"left\">$company&nbsp;</td>";
		echo "<td class=\"left\">$email&nbsp;</td>";
		echo "<td>[<a href=\"editcustomer.php?cust=$custid\">edit</a>]<br />
[<a href=\"addbooking.php?gocust=$custid\">new job</a>]</td></tr>\n";
	}
	$i++;
}
}
?>
</table>
<p align="center"><a href="javascript:history.go(-1)">&lt; Back</a> | <a href="./">Back to Home</a></p>
<p>&nbsp;</p>
</div>
</body>
</html>
