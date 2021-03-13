## vue-element-admin + laravel + jwt + casbin 前后端分离 rbac鉴权权限 管理系统
  
  * [线上demo](http://system.pltrue.top)
  
  #### 使用了一下技术

   * [vue-element-admin-是一个后台前端解决方案，它基于 vue 和 element-ui实现](https://panjiachen.github.io/vue-element-admin-site/zh/)
   * [laravel-是一套简洁、优雅的PHP Web开发框架](https://laravel.com/)
   * [casbin-跨平台的访问控制框架](https://github.com/php-casbin/laravel-authz)

   #### 后台界面登录


![alt 属性文本](img/login.png)


![alt 属性文本](img/home.png)


![alt 属性文本](img/per.png)


![alt 属性文本](img/pro_u.png)


![alt 属性文本](img/user.png)


![alt 属性文本](img/user_update.png)


![alt 属性文本](img/log.png)


#### 安装使用
```shell script
git clone https://github.com/pl1998/laravel-casbin-admin.git
```
#### 后端环境配置 
····省略
```shell script
 cd /laravel-casbin-admin/web/vue-element-admin/
```
  * 调整前端域名 ``.env.development,.env.production``
  * 启动项目
```
 npm run dev
```
  * 打包
```shell script
  npm run build:prod
```

  * nignx 配置 以及 数据看文件都在项目根目录下


