<?php
require_once './vendor/autoload.php';

use App\Database\DB;

class Bootstrap {
 

    public function __construct() {


        $querySQL = DB::table('users')
                      ->select('id', 'name', 'age')
                      ->where('gender', 'm')
                      ->orderBy('age')
                      ->toSql();

        echo $querySQL . "\n";


        $querySQL = DB::table('posts')
                      ->where('is_hidden', 1)
                      ->where('count', 1)
                      ->orWhere(function ($query) {
                          $query->where('author', null)
                                ->where('read_count', '<', 100);
                      })
                      ->toSql();

         echo $querySQL . "\n";


    }
}


new Bootstrap();