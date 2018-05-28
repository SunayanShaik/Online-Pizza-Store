# Online-Pizza-Store

A web-application that enables students to order and have pizza delivered 
at the college dormitory using REST web services and MVC architecture.

# README for pizza2 setup  

This project is meant to be installed at /cs637/username/proj2/pizza2 
relative to the document root, where username is your topcat username.  

Under Windows XAMPP, at \xampp\htdocs\cs637\username\proj2\pizza2 
Under Mac OSX XAMPP, at /Applications/XAMPP/xampfiles/cs637/username/proj2/pizza2 
Under Linux XAMPP, at /opt/lampp/cs637/username/proj2/pizza2  

To be ready to run this project on topcat, you need to edit in 
your topcat username in one place in these sources, as explained in comments in these files, 
in model/database.php  

Once this edit is done, the project is set up to be deployed at c:\xampp\htdocs\cs637\username\proj2\pizza2   
for XAMPP on Windows at /Application/XAMPP/htdocs/cs637/username/proj2/pizza2   
for XAMPP on Mac at /opt/lampp/htdocs/cs637/username/proj2/pizza2   
for XAMPP on Linux at /var/www/html/cs637/username/proj2/pizza2  
for apache on topcat where username is your topcat username.  

Note that the error handling has been simplified by combining database_error.php into error.php, 
where the database case is discovered by instanceof on the  error object. 

The catches are now on Exception, so more kinds can be caught, including the Guzzle exceptions needed for the web services.  

For better security, an .htaccess file has been added to directories database and model, both of which contain database credentials. 
The .htaccess file (with contents "deny from all" ensures that no files in the  directory may be accessed from the web, 
assuming an Apache web server.
