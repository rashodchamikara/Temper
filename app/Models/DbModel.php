<?PHP 
namespace App\Models;
use League\Csv\Reader;


class DbModel {

    public function viewData($test){
        $csv = Reader::createFromPath(__DIR__.'/data/export.csv', 'r');
        $header = $csv->getHeader(); //returns the CSV header record
        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object

        //echo $csv->toString(); //returns the CSV document as a string
        return $csv->toString();

    }

}


?>