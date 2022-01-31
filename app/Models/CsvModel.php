<?PHP 
namespace App\Models;


use App\Models\DataSource;
use League\Csv\Reader;
use \DateTime;



class CsvModel implements DataSource{

    private $dataLink;
    private $csv;
    public $slots;
    public $xaxis_values;
    public $duration;

    function __construct()
    {
        $this->dataLink = CSV_PATH;
        $this->csv =  Reader::createFromPath($this->dataLink, 'r');
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

    //using a getter and setter for future scalability 
    public function setTimeDuration($duration){
        $this->duration = $duration;
    }

    public function getTimeDuration(){
        $duration = $this->duration;
        return $duration;
    }
    
    //this function create a array with duration slot number as key and start date and end date as values to use add data later
    public function buildSlotsArray(){
        $duration = $this->getTimeDuration();
        $local_timeSlots = array();
        $func = function ($row) {
            return $row;
        };

        $results = $this->csv->fetch($func);
        $x =1;
        foreach ($results as $row) {
            
            if($x == 1){//skipping the header
                $x++; 
                continue;
            }
            $date_value = $row;
            $date_value = explode(';',$date_value[0]);
            if($duration=='DAY'){
                //duration - daily 
                $main_array_key = $date_value[1];
            }elseif($duration=='WEEK'){
                //duration - weekly
                $main_array_key = $this->getWeekNumberFromDate($date_value[1]);
            }elseif($duration=='MONTH'){
                //duration - monthly
                //as date format can change in future we can convert it to timestamp and get the month from timestamp 
                $timeStamp = strtotime($date_value[1]);
                $main_array_key = date('m',$timeStamp);
            }else{
                //we have unexpected time slot we exit from here 
                return false;
            }

            

            if(!in_array($main_array_key,$local_timeSlots)){
                $local_timeSlots[$main_array_key] = array();
                if($duration=='DAY'){
                    $start_date = $main_array_key;
                    $end_date  = "";
                }elseif($duration=='WEEK'){
                    $duedt = explode("-", $date_value[1]);
                    $return_date = $this->getStartAndEndDate($main_array_key, $duedt[0]);

                    $start_date = $return_date['week_start'];
                    $end_date  = $return_date['week_end'];

                }elseif($duration=='MONTH'){
                    // we use same timestamp created earlier 
                    $start_date = date('Y-m-01',$timeStamp);
                    $end_date  = date("Y-m-t",$timeStamp);
                }

                //we can set start date and end date as values just here 
                if(!isset($local_timeSlots[$main_array_key]['start_date'])){
                    $local_timeSlots[$main_array_key] = array();
                    $local_timeSlots[$main_array_key]['start_date'] = $start_date;
                    $local_timeSlots[$main_array_key]['end_date'] = $end_date;
                }
            }

            
         
        }
        //unsetting irrelevant array key
        unset($local_timeSlots[0]);
        $this->weeks = $local_timeSlots;
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
    public function getTotalCountForEachSlot(){
        $local_week_array = array();
        $duration = $this->getTimeDuration();
        $this->slots = $this->buildSlotsArray();

        
        foreach($this->slots as $x => $val){
            //var_dump($x);
            $local_week_array[$x] = array();
             foreach($this->xaxis_values as $y=>$value){
                $local_week_array[$x][$value] = 0;
             }
             
        }
        //var_dump($local_week_array);
        //unset($local_week_array[0]);
        
        $func = function ($row) {
            return $row;
        };
        $results = $this->csv->fetch($func);
        $x =1;
        foreach ($results as $row) {
            if($x==1){//skipping the header
                $x++; 
                continue;
            }
            $date_value = $row;
            $date_value = explode(';',$date_value[0]);

            
            if($duration=='DAY'){
                $slot_key = $date_value[1];
            }elseif($duration=='WEEK'){
                $slot_key = $this->getWeekNumberFromDate($date_value[1]);
            }elseif($duration=='MONTH'){
                $timeStamp = strtotime($date_value[1]);
                $slot_key = date('m',$timeStamp);
            }

            
            $progress = $date_value[2];
            //echo $progress.'<br/>';
            //we now add 1 to the specific progress slab
           
            if(in_array($progress,$this->xaxis_values) && $progress!=NULL){
                
                    
                $local_week_array[$slot_key][$progress] =  $local_week_array[$slot_key][$progress]+1;
            }
                
            $x++;
        }


        return $local_week_array;
    }

    public function  dataFormatForFinalOutput(){
        $data_array = $this->getTotalCountForEachSlot();
        $temp_hold_array = $data_array;
        //var_dump($data_array);
        foreach($data_array as $data => $data_key){
            //looping again to add higher totals to lover steps counts
            //var_dump($data_array[$data]);
            foreach($data_array[$data] as $key => $val){
                if($key==0){
                    continue;
                }
                $cur_count = $val;
                //var_dump($cur_count);
                foreach($this->xaxis_values as $steps){
                    if($steps>$key){
                        $cur_count = $cur_count + $data_array[$data][$steps];
                    }
                    
                }
                //var_dump($cur_count);
                $temp_hold_array[$data][$key] = $cur_count;

                unset($cur_count);
                 
            }
            
        }

        return $temp_hold_array;
    }

    //final export function to controller with formatted data 
    public function exportDataToOutput(){
        $divided_data = $this->dataFormatForFinalOutput();

        

        foreach($this->slots as $x => $val){
            //first of all get total number of entries for week
            
            $temp_total = 0;
            foreach($divided_data[$x] as $entry){
                $temp_total = $temp_total + $entry;
                
            }
            $this->slots[$x]['total'] = $temp_total;
            //im looping this same divided data to make final data array
            $this->slots[$x]['data'] = array();
            foreach($divided_data[$x] as $key => $entry){
                $percentage = 100/$temp_total * $entry;
                //var_dump($key.'-'.$entry);
                //echo number_format($percentage,2)."<hr/>";
                //var_dump();
                $this->slots[$x]['data'][$key] = number_format($percentage,2);
            }
            unset($temp_total);
        }

        return $this->slots;
    }

    
}

?>