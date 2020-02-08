<?php
namespace App\Datatable;
use App\Datatable\SSP;
use DB;
use PDO;

class DBI {
    public function db()
    {
        return $this->db = array(
            'host' => DB::connection()->getConfig()['host'],
            'user' => DB::connection()->getConfig()['username'],
            'pass' => DB::connection()->getConfig()['password'],
            'db'   => DB::connection()->getConfig()['database']
        );
    }

    public function columns()
    {
       return array(
                array( 'db' => 'name',                    'dt' => 0 ),
                array( 'db' => 'email',                   'dt' => 1 ),
                array( 'db' => 'phone',                   'dt' => 2 ),
                array( 'db' => 'blood_group',             'dt' => 3 ),
                array(
                    'db'        => 'latest_donotion_date',
                    'dt'        => 4,
                    'formatter' => function( $d, $row ) {
                        return date( 'jS M Y', strtotime($d));
                    }
                )
            );
    }
    public function columnsForAdmin()
    {
       return array(
                array( 'db' => 'name',                    'dt' => 0 ),
                array( 'db' => 'phone',                    'dt' => 1 ),
                array( 'db' => 'blood_group',             'dt' => 2 ),
               
                array(
                    'db'        => 'id',
                    'dt'        => 3,
                    'formatter' => function( $d, $row ) {
                        return '
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" onclick="show('.$d.')" data-toggle="modal" data-target="#profile_Modal" href="#">Profile</a>
                                        <a class="dropdown-item" onclick="edit('.$d.')" data-toggle="modal" data-target="#edit_Modal" href="#">Edit </a>
                                        <a class="dropdown-item" onclick="changepass('.$d.')" data-toggle="modal" data-target="#password_Modal" href="#">Password</a>
                                    </div>
                                </div>
                                
                            ';
                    }
                )
            );
    }
}