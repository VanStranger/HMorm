<?php
include __DIR__."/../vendor/autoload.php";
use HMorm\DB as DB;
DB::setLogPath("./logs123");
DB::init(
  [
    "db"=>[
        "type"=>"mysql",
        "host"=>"127.0.0.1",
        "username"=>"root",
        "password"=>"br13jHhrh7-1",
        "database"=>"zhihu",
        "hostport"=>3306,
        "charset"=>"utf8",
        "prefix"=>"",
    ],
]
  );
  $data=DB::table("use")->select();
  var_dump(json_encode($data)) ;