<?php
// nothing to require, we are just going to run the backup and show the path to file name if successful.
// variables are hardcoded in since this isn't going to be portable without a major rework.

$timez = time();
$randz = rand(1000,9999);
$path = "c:\\xampp\\htdocs\\cust\\backup_".$timez."_".$randz.".sql";
$gorun = "c:\\xampp\\mysql\\bin\\mysqldump -u SQLUSER SQLPASS > $path";
system($gorun);
echo "If all went well, the backup file will be saved to the following location:<pre>$path</pre>";
echo "Use Windows Explorer to navigate there and save the file elsewhere for backup.";
?>
