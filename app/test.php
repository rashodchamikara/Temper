<?PHP

use App\Controllers\ReadController;
use App\Models\CsvModel;
//use League\Csv\Reader;

require_once realpath('../vendor/autoload.php');
require_once realpath('config.php');


$export = new CsvModel();
$export->setTimeDuration('MONTH');

$data = $export->exportDataToOutput();

var_dump($data);

// $con = new ReadController();
// $con->setDuration('WEEK');

// $data = $con->getLineOutputs();

// var_dump($data);

?>