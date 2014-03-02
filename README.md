HTML5-Haushaltsbuch
===================

A simple Accounting Application for mobile devices and classical computers.
Based on minimalistic coding in PHP and Javascript to enable simple hosting
at a huge amount of shared hosting providers or other LAMP-Servers.
It's written during watching TV, mostly on weekends but I think this fakt
should not be seen to much at reviewing its code. But I invite everybody to
send me comments on identified deficites.

Caution: This application is based on a strongly simplified view of double-entry 
bookkeeping which should only be used for private purposes. It has deficites
for example in causes of value added tax and is not able to split booking entries.
It is strongly adjusted to my personal needs.

License Information
-------------------

The software is contributed under GPL (see license.txt) in this Folder.

It contains JQuery, JQuery-Mobile and Knockout.js as clientside framework.
On the serverside its code relies just on plain PHP5 (using mysqli for 
database connectivity) with no additional frameworks.

Installation Information
------------------------

"php mobile accounting" does not come with an own installer yet, so installation must be done by hand.

The installation can be handled within tree steps.

First download this repository from GitHub (you will find it at http://download.html5-haushaltsbuch.de). I assume, you have loaded it as an ZIP-File. Unzip this file to your local computer and go to the directory you have unzipped it to.   

At next please go to the ./sql-Folder, it contains a file named create-tables-and-views.sql. Execute this file on a Mysql-Server within a database you want to use for "php mobile accounting". In case of this manual I will assume that you have called it "fibu" but it can have every possible other name.

Next step ist to configure your database connection. Therefore the file "Database.php" in the folder "lib" can be used. To prepare this please copy "Database.php.template" to "Database.php" and open it with your favorite Texteditor. Replace the placeholders for databasehost, database, username and password to enable access to the database you created one step before.

At next you have to create a .htpasswd-File in the root-Directory of php_mobile_accounting. The best way to do this ist to use the htpasswd commandline tool which is automatically
installed if you have a running apache2 server on a Linux machine. Go to the target directory and type htpasswd -c .htpasswd username and the file will be created automatically.

At next please copy the whole content of the expanded ZIP-File to your target webspace and go to its ./html/index.php file with your browser. If everything is done right "php mobile accounting" should open.


Additional Information
----------------------

For additional information please go to the projects homepage under http://www.html5-haushaltsbuch.de.
