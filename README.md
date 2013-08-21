php_mobile_accounting
=====================

A simple Accounting Application for mobile devices and classical computers.
Based on minimalistic coding in PHP and Javascript to enable simple hosting
at a huge amount of shared hosting providers or other LAMP-Servers.
It's written during watching TV, mostly on weekends but I think this fakt
should not be seen to much at reviewing its code. But I invite everybody to
send me comments on identified deficites.

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

First download this repository from GitHub (you will find it at https://github.com/wolfgang-wiedermann/php_mobile_accounting). I assume, you have loaded it as an ZIP-File. Unzip this file to your local computer and go to the directory you have unzipped it to.   

At next please go to the ./sql-Folder, it contains a file named create-tables-and-views.sql. Execute this file on a Mysql-Server within a database you want to use for "php mobile accounting". In case of this manual I will assume that you have called it "fibu" but it can have every possible other name.

Next step ist to configure your database connection. Therefore the file "Database.php" in the folder "lib" can be used. To prepare this please copy "Database.php.template" to "Database.php" and open it with your favorite Texteditor. Replace the placeholders for databasehost, database, username and password to enable access to the database you created one step before.

At next please copy the whole content of the expanded ZIP-File to your target webspace and go to its ./html/index.php file with your browser. If everything is done right "php mobile accounting" should open.
