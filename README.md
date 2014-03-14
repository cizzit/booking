A Job Booking and Management System
===================================

In '09 I as asked to write some software that a local company might use to manage their jobs (a local IT support company) and customers easier. Over the course of a weekend this application was born.

It was built on a Windows machine but by no means requires it. If you do port it to Linux please check teh files carefully for any directory read/writes that may have a slash in the wrong format (I believe only *backup.php* writes to file)

This software allows you to set up customers and create jobs under the customer record. You can edit customer information and view previous jobs for said customer.

Each job has a worklist depending ont eh type of job it is. The job then displays a list of tasks int he worklist that the technicians works through, marking off each one when they are done with it.

The system has the ability to send out SMSs to the customer at a certain point in the workflow. This is based off the Australian ISP Exetel's API and can easily be customised (in *fnctn.php* to fit any other provider).

Requirements
------------
Apache 2.x
PHP 5.x
MySQL (or other SQL database, see *db.class.php* for information)

Disclaimer
----------

This software was written in 2009 and has not been visited much since. This is the 4th version of the software and the last that I will work with - it's only here for prosperity. If you want to use it, you are more than welcome to - credit would be nice but honestly it's not required.

Some of the code in here will make you want to vomit - I know it did to me when I was uploading it here. Bear in mind it is old code that hasn't been looked at for a long time. If I were writing it again, it would be better. Having said that, if you take it and rewrite it, I'd love to see what you come up with! - cizzit (cizzit@localghost.com.au)
