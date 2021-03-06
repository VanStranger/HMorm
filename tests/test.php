<?php
include __DIR__."/../vendor/autoload.php";
use HMorm\DB as DB;
DB::setLogPath("./logs");
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
  $data=DB::table("users")
  ->field("sex,count(1) as num")
  ->group("sex")
  ->having("num>1")
  ->select();
  // $sql=DB::table("users")->where("id",">",3)->buildSql();
  $data1=DB::table("visitor")
  ->join([DB::table("users")->where("id",">",3)->buildSql(),"user"],"user.id=visitor.userid","left")
  ->fields("user.*")
  ->select();
  echo DB::getDatatype()."</br>";
  echo DB::getSql()."</br>";
  echo json_encode(DB::getParams())."</br>";
  echo json_encode($data);
  echo json_encode($data1);