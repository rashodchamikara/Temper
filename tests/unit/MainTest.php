<?PHP  
 define('CONNECTION_TYPE','csv');
 define('CSV_PATH', __DIR__. '/data/export.csv');
class MainTest extends \PHPUnit\Framework\TestCase
{
   public function testIsConnectionReturnsTrue(){

     
      $conn = new \App\Controllers\ReadController;

      

      $this->assertTrue($conn->isValidConnection());
       
   } 

   public function testValidateFinalOutput(){

     
      $conn = new \App\Controllers\ReadController;

      $data = $conn->getFInalDataArray();
      $test_data = "{name: '2016-07-18-2016-07-24',data: [100,0.00,54.93,2.82,0.00,0.00,11.27,30.99]},{name: '2016-07-25-2016-07-31',data: [100,0.00,58.70,1.45,0.00,0.00,13.77,26.09]},{name: '2016-08-01-2016-08-07',data: [100,0.00,42.55,2.13,0.00,0.00,29.79,25.53]},{name: '2016-08-08-2016-08-14',data: [100,0.00,25.64,0.00,0.00,0.00,46.15,28.21]}";
      

      $this->assertEquals($data, $test_data);
       
   } 

   public function testStartDateAndDateArray(){

      $model = new \App\Models\CsvModel;

      $data = $model->getStartAndEndDates();
      $data_string = $data['start_date']."|".$data['end_date'];

      $test_data = '2016-07-19|2016-08-10';
      $this->assertEquals($test_data, $data_string);
   }

   public function testBuildWeeksArray(){
      $model = new \App\Models\CsvModel;

      $data = $model->buildWeeksArray();
      $target_slot = $data[29]['start_date'];

      $test_data = '2016-07-18';
      $this->assertEquals($test_data, $target_slot);

   }
}
?>