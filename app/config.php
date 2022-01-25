<?PHP 
///this file holds all the required configurations for this applications 

define('CONNECTION_TYPE','csv'); // this holds the data source type csv or mysql (should be able to extend to other data Sources using the interface)

define('CSV_PATH', __DIR__.'/models/data/export.csv'); //this path should be relative to config file location

//mysql Db variables
define('DB_DBNAME','');
define('DB_USER','');
define('DB_PASSWORD','');
define('DB_HOST','');
?>