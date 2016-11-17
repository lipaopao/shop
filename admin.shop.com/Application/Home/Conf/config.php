<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'thinkphp',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'URL_MODEL'             =>  1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：

    'TMPL_PARSE_STRING'     =>  array(
        '__CSS__'   => SRC_URL.'Public/'.MODULE_NAME.'/Styles',
        '__JS__'   => SRC_URL.'Public/'.MODULE_NAME.'/Js',
        '__IMG__'   => SRC_URL.'Public/'.MODULE_NAME.'/Images',
        '__UPLOADIFY__'   => SRC_URL.'Public/'.MODULE_NAME.'/uploadify',
        '__LAYER__'   => SRC_URL.'Public/'.MODULE_NAME.'/layer',
        '__ZTREE__'=>SRC_URL.'Public/'.MODULE_NAME.'/ztree',
        '__UEDITER__'=>SRC_URL.'Public/'.MODULE_NAME.'/uediter',
    ),

    'PATHINFO'     => 'http://admin.shop.com/',

    'RABC' =>[
      'IGONRE' =>[
          'Home/Admin/login',
          'Home/Admin/show',
          'Home/Admin/add',
          'Home/Goods/add',
          'Home/Goods/index',
      ]  ,
        'USER_IGONRE' =>[
            'Home/Index/index',
            'Home/Index/top',
            'Home/Index/menu',
            'Home/Index/main',
            'Home/Admin/index',
            'Home/Admin/login',
            'Home/Admin/add',
            'Home/Captcha/show',
            'Home/Upload/upload',
        ],
    ],

);