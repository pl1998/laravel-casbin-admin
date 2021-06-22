### Rbac角色访问控制 

 > 这是一种常见的后台内容管理权限策略，通常它是有五张表组成

   * `users` 用户表
   * `roles` 角色表
   * `permissions` 权限表
   * `users_roles` 用户角色表
   * `roles_permissions` 角色权限表

#### 它们之间的逻辑
 
> 用户表和角色表以及权限表 关联关系分别存储在 用户角色表  角色权限表 权限表中
> 一般情况下 通过登录用户拿到角色 通过角色拿到具体的权限节点 然后判断用户是否有访问的权限

### 让我们看看在laravel中它有那些集成的解决方案 它们都提供了简洁的方法 让我们通过调用方法的形式实现对角色权限的管理控制 简单易用

   * [Laravel-permission](https://spatie.be/docs/laravel-permission/v4/introduction)
> 
  #### 使用实例
  
```php
// 添加权限到用户
$user->givePermissionTo('edit articles');

//添加权限到角色
$user->assignRole('writer');

```

#### 跨平台的解决方案 `casbin` 

> casbin是一个强大、高效的访问控制库。支持常用的多种访问控制模型，如ACL/RBAC/ABAC等。可以实现灵活的访问权限控制。同时，它支持多种主流的后端语言 java php node go python .net c++ rust

  * [laravel-authz](https://github.com/php-casbin/laravel-authz)
#### 使用实例
```php
use Enforcer;

// 添加权限到用户
Enforcer::addPermissionForUser('eve', 'articles', 'read');
// 添加角色到用户
Enforcer::addRoleForUser('eve', 'writer');
// 添加权限到角色
Enforcer::addPolicy('writer', 'articles','edit');
```

