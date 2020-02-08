<?php
namespace App\Datatable;
use App\Datatable\SSP;
use App\Datatable\DBI;
use DB;
use PDO;
use Carbon\Carbon;

class FatchData extends DBI{

    protected $datetime;

    public function __construct()
    {
        $this->datetime = Carbon::now()->subDays(90)->toDateTimeString();
    }
   
    public function getData($table,$primaryKey)
    {
        return SSP::simple( $_GET, $this->db(), $table, $primaryKey, $this->columnsForAdmin() );
    }
    public function getDonors()
    {
        $date = $this->datetime;
        $query = "`latest_donotion_date` <= '$date'";
        return SSP::complex( null, $this->db(), "users", "id", $this->columns(), $whereResult= $query, null);
    }
    
    public function searchByBloodGroup($query)
    {
        $date = $this->datetime;
        $query = "`blood_group` = '$query' AND `latest_donotion_date` <= '$date'";
        return SSP::complex ( null, $this->db(), "users", "id", $this->columns(), $whereResult= $query, null);
    }
    
}