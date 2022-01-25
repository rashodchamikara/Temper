<?PHP

use App\Controllers\ReadController;
use App\Models\CsvModel;
//use League\Csv\Reader;

require_once realpath('../vendor/autoload.php');
require_once realpath('config.php');


$export = new CsvModel();

$data = $export->buildWeeksArray();

var_dump($data);

?>