<?PHP 
namespace App\Models;

interface DataSource{

    public function isValidConnection();

    public function buildSlotsArray();

    public function exportDataToOutput();

}

?>