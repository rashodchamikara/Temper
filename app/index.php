<?PHP

use App\Controllers\ReadController;
//use League\Csv\Reader;

require_once realpath('../vendor/autoload.php');
require_once realpath('config.php');


$out_put = array();

if(isset($_GET['action'])){

    if(isset($_GET['duration']) && ($_GET['duration']=='DAY' || $_GET['duration']=='WEEK' || $_GET['duration']=='MONTH')){
        $duration = $_GET['duration'];
        $export = new ReadController();
        $export->setDuration($duration);

        if($_GET['action']=='GetSlots'){
            $slots = $export->getLineOutputs();
            $out_put['data'] = $slots;
        }elseif($_GET['action']=='GetData'){
            $data_out = $export->getFInalDataArray();
            $out_put['data'] = $data_out;
        }else{
            $out_put['status']  = 402;
            $out_put['error_msg']= 'Invalid Action Provided'; 
        }

    }else{
        $duration = 'WEEK';
    }

}else{
    $out_put['status']  = 404;
    $out_put['error_msg']= 'No Action Provided'; 
}

$return_value = json_encode($out_put);
echo $return_value;
exit();




?>