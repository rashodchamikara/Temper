<?PHP
namespace App\Controllers;


class ReadController{

    private $db_class;
    public $duration;

    function __construct()
    {
        if(CONNECTION_TYPE=='csv'){
            $this->db_class = new \App\Models\CsvModel;
        }else{
            //include the db class
        }
        
        
    }
    // this function validate for valid file link or db connection 
    public function isValidConnection()
    {
        $con_status = $this->db_class->isValidConnection();
        return $con_status;
    }

    //using a getter and setter for future scalability 
    public function setDuration($duration){
        $this->duration = $duration;
    }

    public function getDuration(){
        $duration = $this->duration;
        return $duration;
    }
    public function getLineOutputs(){
        $durations = $this->getDuration();
        $this->db_class->setTimeDuration($durations);
        $lines_array = $this->db_class->buildSlotsArray();
        if(is_array($lines_array)){
            $local__line_array = array();
            foreach($lines_array as $data){
                $plug_in_string = $data['start_date'];
                if($data['end_date']!=''){
                    $plug_in_string .= " - ".$data['end_date'];
                }
                $local__line_array[]  = $plug_in_string;
                unset($plug_in_string);
            }
            //return  $lines_array['2016-07-19']['start_date'];
            return $local__line_array;
        }else{
            //somethings wrong with the duration or csv file we use this return to expose error 
            return false;
        }
        
    }

    //final exporting function to build the string for API call 
    public function getFInalDataArray(){
        $durations = $this->getDuration();
        $this->db_class->setTimeDuration($durations);
        $data_set = $this->db_class->exportDataToOutput();
        $output_data = array();
        $x=1;
        //var_dump($data_set);
        foreach($data_set as $data){
            $output_data[$x] = array();
            $build_title = $data['start_date'];
            if($data['end_date']!=''){
                $build_title .= "-".$data['end_date'];
            }
            $output_data[$x]['title'] = $build_title;
            $output_data[$x]['data'] = $data['data'];

            $x++;
        }

        return $output_data;
    }
}


?>