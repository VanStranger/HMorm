# HMorm
弘梦orm，一款php orm
### 简介
HMorm是一款单例模式的php语言数据库操作orm，使用方式类似于Thinkphp中的orm操作。
### 安装
1. 引入
```php
use HMorm\DB as DB;
```
2. 配置数据库
```php
use HMorm\DB as DB;
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
3. 配置log文件路径
```php
DB::setLogPath("./logs");
```
### 方法文档
稍后补充...

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

添加多个条件

```php
DB::table("user")
->where([
  "username"=>"hongmeng",
  "createType"=>"独立自主"
  ])
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

