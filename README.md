# HMorm
弘梦orm，一款php orm
### 简介
HMorm是一款单例模式的php语言数据库操作orm，使用方式类似于Thinkphp中的orm操作。
### 安装
1. 安装
```php
composer require vanstranger/hmorm
```
2. 引入
```php
use HMorm\DB as DB;
```
3. 配置数据库
```php
DB::init(
  [
    "db"=>[
        "type"=>"mysql",
        "host"=>"127.0.0.1",
        "username"=>"root",
        "password"=>"root",
        "database"=>"test",
        "hostport"=>3306,
        "charset"=>"utf8",
        "prefix"=>"",
    ],
    "db1"=>[
        "type"=>"mysql",
        "host"=>"127.0.0.1",
        "username"=>"root",
        "password"=>"root1",
        "database"=>"test1",
        "hostport"=>3306,
        "charset"=>"utf8",
        "prefix"=>"test",
    ],
]
  );
```
* type 默认为mysql，如果是oracle请设置为oci
* host 数据库地址
* username 数据库用户名
* password 数据库密码
* database 数据库名
* hostport 数据库端口
* charset 数据库编码
* prefix 数据表前缀


3. 配置log文件路径
```php
DB::setLogPath("./logs");
```
### 方法文档
* 静态方法 init

    初始化数据库配置
```php
DB::init(array $dbconfig);
```
    参数$dbconfigy是一个数组，可以配置多个数据库，DB默认会连接key为db的数据库配置。
    例：

    DB::init(
      [
        "db"=>[
            "type"=>"mysql",
            "host"=>"127.0.0.1",
            "username"=>"root",
            "password"=>"root",
            "database"=>"test",
            "hostport"=>3306,
            "charset"=>"utf8",
            "prefix"=>"",
        ],
        "db1"=>[
            "type"=>"mysql",
            "host"=>"127.0.0.1",
            "username"=>"root",
            "password"=>"root1",
            "database"=>"test1",
            "hostport"=>3306,
            "charset"=>"utf8",
            "prefix"=>"test",
        ],
    ]
      );
    会连接到名为test的数据库。
* 静态方法 connect

    init中配置多个数据库，切换数据库时使用。返回DB的实例。
```php
DB::connect(string $dbname);
```
    改变数据库链接到init方法中配置的数组中key为$dbname的数据库。
    例：DB::connect("db1");会连接到名为test1的数据库。
* 静态方法 getDatatype

    返回数据库类型（mysql | oci ）。
```php
DB::getDatatype();
```
* 静态方法 getDbconfig

    返回init方法配置的变量。
```php
DB::getDbconfig();
```
* 静态方法 getPDO

    返回PDO连接对象。
```php
DB::getPDO();
```
* 静态方法 getConn

    返回从dbconfig中获取数据库配置的键名。
```php
DB::getConn();
```
* 静态方法 lastInsertId

    返回上次插入的id。
```php
DB::lastInsertId();
```
* 静态方法 beginTrans

    开启事务,常与静态方法commit或静态方法rollback一起使用。
```php
DB::beginTrans();
```
* 静态方法 commit

    提交事务,常与静态方法beginTrans一起使用。
```php
DB::commit();
```
* 静态方法 rollback

    提交事务,常与静态方法beginTrans一起使用。
```php
DB::rollback();
```
* 静态方法 closeConn

    关闭连接。
```php
DB::closeConn();
```
* 静态方法 table
    

    设置sql查询的数据表,返回DB的实例。
    ```php
    DB::table(string $table);
    DB::table(array $table);
    ```
    1. $table为字符串时，选择以$table为名的数据表。
  
            例：
            DB::table("user")->select();
            会转化为
            select * from user;
    2. $table为数组时：

        1. 若$table的长度为1，会选择以$table的key为名的数据表，并用$value作为数据表的别名。

                例：
                DB::table(["user"=>"u"])->select();
                会转化为 
                select * from user u;

        2. 若$table的长度为2，则$table第0项应该是一个长度为2的数组（并且此数组的第0项应该是字符串，第1项应该是数组），第1项应该是一个字符串。会选择以第0项代表的数据表，并用第一项作为数据表的别名。

                例：
                DB::table([["select id from user where id>?",[1]],"u"])->select();
                会转化为 
                select * from ( select id from user where id>1) u;
* 方法 join
    
    跨表查询时,设置需要连接的表、连接条件、连接方式。返回$this。

    ```php
    join(string $table,string $condition,string $jointype);
    join(array $table,string $condition,string $jointype);
    ``` 
    $table 为要连接的表。

    $condition 为连接条件,如 tableA.id=tableB.userid。

    $jointype 为连接方式，只有三个选项：left ，right ，inner。默认为inner。

    1. $table为字符串时，选择以$table为名的数据表。

            例：
            DB::table("user")->join("work","user.id=work.userid")->select();
            会转化为
            select * from user inner join work on user.id=work.userid;

            DB::table("user")->join("work","user.id=work.userid","left")->select();
            会转化为
            select * from user left join work on user.id=work.userid;
    
    2. $table为数组时：

        1. 若$table的长度为1，会连接以$table的key为名的数据表，并用$value作为数据表的别名。

                例：
                DB::table("user")->join(["work"=>"w"],"user.id=w.userid")->select();
                会转化为 
                select * from user inner join work w on user.id=w.userid;

        2. 若$table的长度为2，则$table第0项应该是一个长度为2的数组（并且此数组的第0项应该是字符串，第1项应该是数组），第1项应该是一个字符串。会连接以第0项代表的数据表，并用第一项作为数据表的别名。
          
                例：
                DB::table("user")->join([["select * from work where id>?",[1]],"w"],"user.id=w.userid")->select();
                会转化为 
                select * from user inner join ( select id from work where id>1) w on user.id=w.userid;
* 方法 field
    
    设置要查询的字段。返回$this。
    ```php
    field(string $field);
    field(array $field);
    ``` 
    1. $field为字符串时，将$field作为要查询的字段。

            例：
            DB::table("user")->field("id,user.username")->select();
            会转化为
            select id,user.username from user ;

    2. $field为数组时,会将$field的所有项作为字段查询。当key为数字时，会将value作为字段；当key为字符串时，会将key作为字段，value作为别名。

            例：
            DB::table("user")->field(["id","username"=>"u"])->select();
            会转化为 
            select id,username as u from user;
* 方法 fields
    
    设置要查询的字段。返回$this。同field方法。     
* 方法 where
    

    设置sql查询的查询条件，可以多次执行，多次执行的条件用 and 连接。返回$this。
    ```php
    where(string $sql);
    where(string $sql,array $params);
    where(string $key,string $value);
    where(string $key,string $value1,string $value2);
    where(array $array)
    where(callable $function)
    ```
    1. 第一个参数为字符串时：

        1. 如果只有一个参数，且第一个参数为字符串，则会将第一个参数作为条件。

                例：
                DB::table("user")->where("id>1")->select();
                会转化为
                select * from user where ( id >1 );
        2. 如果只有两个参数，且第一个参数为字符串，第二个参数为数组，则会将第一个参数作为条件，第二个参数为预处理参数。
        
                例：
                DB::table("user")->where("id>?",[1])->select();
                会转化为
                sql:select * from user where ( `id` > ? );
                params:[1]
        3. 如果只有两个参数，且第一个参数为字符串，第二个参数为字符串、数字或null，则会将第一个参数作为条件，第二个参数为预处理参数，预处理为第一个参数=第二个参数。
        
                例：
                DB::table("user")->where("id",1)->select();
                会转化为
                sql:select * from user where ( `id` = ? );
                params:[1]
        4. 如果有三个参数，且第一个参数为字符串，第二个参数为数字或字符串，第三个参数为null、数字或字符串，则会将第一个参数与第二个参数拼接为条件，第三个参数为预处理参数。
        
            例：
            DB::table("user")->where("id",">",1)->select();
            会转化为
            sql:select * from user where ( `id` > ? );
            params:[1]


    2. 第一个参数为数组时，会将数组的每一项都所谓条件用and连接起来作为条件。如果某一项的value为数字或字符串会拼接进预处理参数数组，如果value为null会将条件设置为 key is null，如果value是数组，会将数组的value依次拼接进sql语句。

            例：
            DB::table(["user"=>"u"])->where([
              "id"=>[">",1],
              "username"=>"phporm",
              "xingfuma"=>null,
            ])->select();
            会转化为 
            sql:select * from user u where ( `id` > 1 and `username` = ? and `xingfuma` is null);
            params:["phporm"];
    3. 第一个参数为函数时，会将当前实例作为函数的第一个参数执行此函数。
     
            例：
            DB::table(["user"=>"u"])->where(function($q)use($search){
              if($search){
                $q->where("username like ?",["%".$search."%"]);
              }
            })->select();
            $search判断为真时，会转化为 
            sql:select * from user u where (  `username` like ? );
            params:[$search];
            $search判断为假时，会转化为 
            sql:select * from user u ;
* 方法 whereOr
    

    设置sql查询的查询条件，可以多次执行，返回$this，同where方法。唯一不同为：多次执行的条件用 or 连接。        
* 方法 whereIn
    
    设置sql查询的查询条件，可以多次执行，多次执行的条件用 and 连接。返回$this。
    ```php

    where(string $key,array $value);
    where(callable $function)
    ```
    1. 第一个参数为字符串时，第二个参数为数组时，设置为名称为第一个参数的字段 in 第二个参数的数组中的所有项的条件。
    
            例：
            DB::table("user")->whereIn("id",[1,2,3])->select();
            会转化为
            select * from user where ( `id` in (1,2,3) );
    2. 第一个参数为函数时，会将当前实例作为函数的第一个参数执行此函数。
        
            例：
            DB::table(["user"=>"u"])->whereIn(function($q)use($condition){
              if($condition){
                $q->whereIn("id",[1,2,3]);
              }
            })->select();
            $condition判断为真时，会转化为 
            sql:select * from user u where ( id in (1,2,3) );
            params:[$condition];
            $condition判断为假时，会转化为 
            sql:select * from user u ;    
* 方法 whereLikeEntity
    
    设置sql查询的查询条件，可以多次执行，多次执行的条件用 and 连接。返回$this。
    ```php

    whereLikeEntity(array $entity,bool $condition = true);
    whereLikeEntity(callable $function);
    ```
    1. 查询当前表的所有字段，对数组entity中的key列表中存在的字段用like模糊查询并用and（第二个参数为false时用or）连接。
    
            例：
            DB::table("user")->whereLikeEntity(['username"=>"李","address"=>"平山县"])->select();
            如果user表有username字段，没有address字段会转化为
            sql:select * from user where ( `username` like ? );
            params:["%李%"];
            如果user表有username字段和address字段会转化为
            sql:select * from user where ( `username` like ? and `address` like ? );
            params:["%李%","%平山县%"];
    2. 第一个参数为函数时，会将当前实例作为函数的第一个参数执行此函数。
    
            例：
            DB::table("user")->whereLikeEntity(funcion($q){
              $q->whereLikeEntity(['username"=>"李","address"=>"平山县"]);
            })->select();
              如果user表有username字段，没有address字段会转化为
              sql:select * from user where ( `username` like ? );
              params:["%李%"];
              如果user表有username字段和address字段会转化为
              sql:select * from user where ( `username` like ? and `address` like ? );
              params:["%李%","%平山县%"];
* 方法 whereLeftLikeEntity
    
    设置sql查询的查询条件，可以多次执行，多次执行的条件用 and 连接。返回$this。
    ```php

    whereLeftLikeEntity(array $entity,bool $condition = true);
    whereLeftLikeEntity(callable $function);
    ```
    1. 查询当前表的所有字段，对数组entity中的key列表中存在的字段用like左匹配模糊查询并用and（第二个参数为false时用or）连接。
      
            例：
            DB::table("user")->whereLeftLikeEntity(['username"=>"李","address"=>"平山县"])->select();
            如果user表有username字段，没有address字段会转化为
            sql:select * from user where ( `username` like ? );
            params:["李%"];
            如果user表有username字段和address字段会转化为
            sql:select * from user where ( `username` like ? and `address` like ? );
            params:["李%","平山县%"];
    2. 第一个参数为函数时，会将当前实例作为函数的第一个参数执行此函数。
    
            例：
            DB::table("user")->whereLeftLikeEntity(funcion($q){
              $q->whereLeftLikeEntity(['username"=>"李","address"=>"平山县"]);
            })->select();
              如果user表有username字段，没有address字段会转化为
              sql:select * from user where ( `username` like ? );
              params:["李%"];
              如果user表有username字段和address字段会转化为
              sql:select * from user where ( `username` like ? and `address` like ? );
              params:["李%","平山县%"];
* 方法 whereRightLikeEntity
    
    设置sql查询的查询条件，可以多次执行，多次执行的条件用 and 连接。返回$this。
    ```php

    whereRightLikeEntity(array $entity,bool $condition = true);
    whereRightLikeEntity(callable $function);
    ```
    1. 查询当前表的所有字段，对数组entity中的key列表中存在的字段用like右匹配模糊查询并用and（第二个参数为false时用or）连接。
      
              例：
              DB::table("user")->whereRightLikeEntity(['username"=>"李","address"=>"平山县"])->select();
              如果user表有username字段，没有address字段会转化为
              sql:select * from user where ( `username` like ? );
              params:["%李"];
              如果user表有username字段和address字段会转化为
              sql:select * from user where ( `username` like ? and `address` like ? );
              params:["%李","%平山县"];
    2. 第一个参数为函数时，会将当前实例作为函数的第一个参数执行此函数。
    
            例：
            DB::table("user")->whereRightLikeEntity(funcion($q){
              $q->whereRightLikeEntity(['username"=>"李","address"=>"平山县"]);
            })->select();
              如果user表有username字段，没有address字段会转化为
              sql:select * from user where ( `username` like ? );
              params:["%李"];
              如果user表有username字段和address字段会转化为
              sql:select * from user where ( `username` like ? and `address` like ? );
              params:["%李","%平山县"];
      ~~~
      还有约三分之一的方法，待续。
      ~~~
### 常用操作
0. 直接执行
```php
DB::query("select * from user");
```
1. 新增
```php
DB::table("user")->insert([
  "id"=>1,
  "name"=>"hm"
]);
$user=[
  "id"=>1,
  "name"=>"hm"
];
DB::table("user")->insertEntity($user);//自动查询user表的列，然后从$user中获取相应数据
```
2. 删除
```php
DB::table("user")->where("id",1)->delete();
```
3. 修改
```php
DB::table("user")
->where("id",1)
->update([
  "id"=>2,
  "name"=>"hm"
]);
$user=[
  "id"=>2,
  "name"=>"hm"
];
DB::table("user")
->where("id",1)
->updateEntity($user);//自动查询user表的列，然后从$user中更新相应数据
```
4. 查询

查询数据表
```php
DB::table("user")->select();//多行数据
DB::table("user")->find();//一行数据
```
添加条件

```php
DB::table("user")
->where("id=1")
->select();

DB::table("user")
->where("id=?",[1])
->select();

DB::table("user")
->where("id",1)
->select();
```
添加多个条件

```php
DB::table("user")
->where([
  "username"=>"hongmeng",
  "createType"=>"独立自主"
  ])
->select();

DB::table("user")
->where([
  "username"=>"hongmeng",
  "createType"=>"独立自主"
  ])
->where("id",2)
->select();
```
添加非prepare条件

```php
DB::table("user")
->where([
  "age"=>"oldage+1"
  ])
->select();
```
添加函数条件

```php
$search="hm";
DB::table("user")
->where(function($q)use($search){
  if($search){
    $q->where("username like %".$search."%");
  }
})
->select();
```
指定字段查询

```php
DB::table(["user"=>"u"])
->field("id,username,password as p")
->select();

DB::table(["user"=>"u"])
->field([
  "id","username","password"=>"p"
])
->select();
```
order查询

```php
DB::table("user")
->order("id desc")
->select();
```
分页查询

```php
$page=1;
$size=20;
DB::table("user")
->limit(($page-1)*$size,$size)
->order("id desc")
->select();
```

group查询

```php
DB::table("user")
->field("sex,count(1) as num")
->group("sex")
->select();
```
having查询

```php
DB::table("user")
->field("sex,count(1) as num")
->group("sex")
->having("num>1")
->select();
```

跨表查询

```php
DB::table(["user"=>"u"])
->join(["city"=>"c"],"u.cid=c.id","left")
->field("u.id,c.cityname")
->select();
```

