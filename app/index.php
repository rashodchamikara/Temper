<?PHP

use App\Controllers\ReadController;
//use League\Csv\Reader;

require_once realpath('../vendor/autoload.php');
require_once realpath('config.php');


$export = new ReadController();

$data = $export->getFInalDataArray();

echo $data;

?>