<?PHP 
namespace App\Models;


use App\Models\DataSource;
use League\Csv\Reader;
use \DateTime;



class CsvModel implements DataSource{

    private $dataLink;
    private $csv;
    public $weeks;
    public $xaxis_values;

    function __construct()
    {
        $this->dataLink = CSV_PATH;
        $this->csv =  Reader::createFromPath($this->dataLink, 'r');
        $this->weeks = array();
        $this->weeks = $this->buildWeeksArray();
        $this->xaxis_values = array(0,20,40,50,70,90,99,100);
    }

    public function isValidConnection(){
        //this function validate whether file exists or not
        
        $nbRows = $this->csv->each(function ($row) {
            return true;
        });


        if($nbRows>0){
            return true;
        }else{
            return false;
        }
    }
    
    //this function create a array with week number as key and start date and end date as values to use add data later
    public function buildWeeksArray(){
        
        $local_weeks = array();
        $func = function ($row) {
            return $row;
        };

        $results = $this->csv->fetch($func);
        $x =1;
        foreach ($results as $row) {
            if($x<>1){//skipping the header 
                $date_value = $row;
                $date_value = explode(';',$date_value[0]);

                $week = $this->getWeekNumberFromDate($date_value[1]);

                if(!in_array($week,$local_weeks)){
                    $local_weeks[$week] = array();
                    $duedt = explode("-", $date_value[1]);
                    $return_date = $this->getStartAndEndDate($week, $duedt[0]);
                    //we can set start date and end date as values just here 
                    if(!isset($local_weeks[$week]['start_date'])){
                        $local_weeks[$week] = array();
                        $local_weeks[$week]['start_date'] = $return_date['week_start'];
                        $local_weeks[$week]['end_date'] = $return_date['week_end'];
                    }
                }
               
            }
            
         $x++;
        }
        //unsetting irrelevant array key
        unset($local_weeks[0]);
        $this->weeks = $local_weeks;
        return $this->weeks;
        
    }

    //helper function to get week number from a date
    public function getWeekNumberFromDate($date){
        $duedt = explode("-", $date);
        $date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
        $week  = (int)date('W', $date);

        return $week;
    }
    //helper function to get week start date and date
    public function getStartAndEndDate($week, $year){
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
    }

    //adding new array to existing week array with progress segments  
    public function getTotalCountForEachWeek(){
        $local_week_array = array();

        foreach($this->weeks as $x => $val){
            $local_week_array[] = $x;
            $local_week_array[$x] = array();
             foreach($this->xaxis_values as $y=>$value){
                $local_week_array[$x][$value] = 0;
             }
             
        }
        unset($local_week_array[0]);
        
        $func = function ($row) {
            return $row;
        };
        $results = $this->csv->fetch($func);
        $x =1;
        foreach ($results as $row) {
            if($x<>1){
                $date_value = $row;
                $date_value = explode(';',$date_value[0]);

                $week = $this->getWeekNumberFromDate($date_value[1]);
                $progress = $date_value[2];
                //we now add 1 to the specific progress slab
                if(in_array($progress,$this->xaxis_values) && $progress!=NULL){
                    
                    $local_week_array[$week][$progress] =  $local_week_array[$week][$progress]+1;
                }
                
                
            }
            $x++;
        }

        return $local_week_array;
    }


    //final export function to controller with formatted data 
    public function exportDataToOutput(){
        $divided_data = $this->getTotalCountForEachWeek();

        foreach($this->weeks as $x => $val){
            //first of all get total number of entries for week
            
            $temp_total = 0;
            foreach($divided_data[$x] as $entry){
                $temp_total = $temp_total + $entry;
                
            }
            $this->weeks[$x]['total'] = $temp_total;
            //im looping this same divided data to make final data array
            $this->weeks[$x]['data'] = array();
            foreach($divided_data[$x] as $key => $entry){
                $percentage = 100/$temp_total * $entry;
                //var_dump($key.'-'.$entry);
                //echo number_format($percentage,2)."<hr/>";
                $this->weeks[$x]['data'][$key] = number_format($percentage,2);
            }
            unset($temp_total);
        }

        return $this->weeks;
    }

    
}

?>