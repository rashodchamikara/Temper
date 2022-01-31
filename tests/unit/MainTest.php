<?PHP  
 define('CONNECTION_TYPE','csv');
 define('CSV_PATH', __DIR__. '/data/export.csv');
class MainTest extends \PHPUnit\Framework\TestCase
{
   public function testIsConnectionReturnsTrue(){

     
      $conn = new \App\Controllers\ReadController;

      

      $this->assertTrue($conn->isValidConnection());
       
   } 

  

   public function testDailyArraySet(){

      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('DAY');
      $data = $model->buildSlotsArray();
      
      $test_against1 = $data['2016-07-21']['start_date'];
      $test_count = count($data);

      $test_data = '2016-07-21|23';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testWeeklYArraySet(){
      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('WEEK');
      $data = $model->buildSlotsArray();

      $test_against1 = $data['29']['start_date'];
      $test_count = count($data);

      $test_data = '2016-07-18|4';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testMonthlyArraySet(){
      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('MONTH');
      $data = $model->buildSlotsArray();

      $test_against1 = $data['07']['end_date'];
      $test_count = count($data);

      $test_data = '2016-07-31|2';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testControllerLinsOutPutDays(){

      $con = new \App\Controllers\ReadController;
      $con->setDuration('DAY');
      $data = $con->getLineOutputs();

      $test_against1 = $data[3];
      $test_count = count($data);

      $test_data = '2016-07-22|23';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testControllerLinsOutPutWeeks(){

      $con = new \App\Controllers\ReadController;
      $con->setDuration('WEEK');
      $data = $con->getLineOutputs();

      $test_against1 = $data[3];
      $test_count = count($data);

      $test_data = '2016-08-08 - 2016-08-14|4';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testControllerLinsOutPutMonth(){

      $con = new \App\Controllers\ReadController;
      $con->setDuration('MONTH');
      $data = $con->getLineOutputs();

      $test_against1 = $data[0];
      $test_count = count($data);

      $test_data = '2016-07-01 - 2016-07-31|2';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testTotalOutPutEachSlotDaily(){

      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('DAY');
      $data = $model->getTotalCountForEachSlot();
      
      $test_against1 = $data['2016-07-19'][40];
      $test_count = count($data);

      $test_data = '3|23';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testTotalOutPutEachSlotWeekly(){

      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('WEEK');
      $data = $model->getTotalCountForEachSlot();
      
      $test_against1 = $data['32'][100];
      $test_count = count($data);

      $test_data = '11|4';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }
   public function testTotalOutPutEachSlotMonthly(){

      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('MONTH');
      $data = $model->getTotalCountForEachSlot();
      
      $test_against1 = $data['07'][99];
      $test_count = $data['08'][100];

      $test_data = '27|23';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testTotalCountDaily(){
      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('DAY');
      $data = $model->dataFormatForFinalOutput();

      $test_against1 = $data['2016-07-20']['70'];
      $test_count = count($data);

      $test_data = '2|23';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testTotalCountWeekly(){
      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('WEEK');
      $data = $model->dataFormatForFinalOutput();

      $test_against1 = $data['31']['70'];
      $test_count = count($data);

      $test_data = '26|4';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testTotalCountMonthly(){
      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('MONTH');
      $data = $model->dataFormatForFinalOutput();

      $test_against1 = $data['08']['50'];
      $test_count = count($data);

      $test_data = '56|2';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testFinalOutPutDaily(){
      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('DAY');
      $data = $model->exportDataToOutput();

      $test_against1 = $data['2016-07-19']['data']['40'];
      $test_count = count($data);

      $test_data = '30.77|23';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }

   public function testFinalOutPutWeekly(){
      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('WEEK');
      $data = $model->exportDataToOutput();

      $test_against1 = $data['31']['data']['90'];
      $test_count = count($data);

      $test_data = '12.32|4';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }
   public function testFinalOutPutMonthly(){
      $model = new \App\Models\CsvModel;
      $model->setTimeDuration('MONTH');
      $data = $model->exportDataToOutput();

      $test_against1 = $data['07']['data']['50'];
      $test_count = count($data);

      $test_data = '10.85|2';
      $this->assertEquals($test_against1.'|'.$test_count, $test_data);
   }
}
?>