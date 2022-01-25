<?PHP
namespace App\Controllers;


class ReadController{

    private $db_class;

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

    //final exporting function to build the string for API call 
    public function getFInalDataArray(){
        $data_set = $this->db_class->exportDataToOutput();
        $return_str = '';
        $main_count = count($data_set);
        $y=1;
        foreach($data_set as $data){
            $return_str .= "{";
                $return_str .= "name: '".$data['start_date']."-".$data['end_date']."',";
                $return_str .= "data: ";
                $return_str .= "[";
                $mini_count = count($data['data']);
                $x=1;
                
                foreach($data['data'] as $key => $value){
                    if($key==0){
                        $return_str .= 100;
                    }else{
                        $return_str .= $value;
                    }
                    
                    if($x!=$mini_count){
                        $return_str .= ",";
                    }
                    $x++;
                }
            $return_str .= "]";
            $return_str .= "}";
            if($y!=$main_count){
                $return_str .= ",";
            }
        $y++;
        }

        return $return_str;
    }
}


?>