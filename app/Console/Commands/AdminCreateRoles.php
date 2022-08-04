<?php

namespace App\Console\Commands;

use App\Models\Permissions;
use App\Models\Roles;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Console\Command;

class AdminCreateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:roles:to:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建角色';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected $permissionService;
    protected $roleService;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle( PermissionService $permissionService,RoleService $roleService)
    {
        $this->permissionService = $permissionService;
        $this->roleService = $roleService;

        $name = 'admin';
        $node = Permissions::query()
            ->where('status',Permissions::STATUS_OK)
            ->pluck('id')->toArray();

        $this->createAdmin($name,$node);

        $name = 'demo';

        $node = Permissions::query()
            ->where('status',Permissions::STATUS_OK)
            ->where(function ($query){
                $query->where('method',Permissions::HTTP_REQUEST_GET)
                    ->orWhere('is_menu',Permissions::IS_MENU_YES);
            })
            ->pluck('id')->toArray();

        $this->createAdmin($name,$node);
    }

    public function createAdmin($name,$node)
    {
        $roles = Roles::query()->where('name',$name)->first();

        if(!$roles){
            $status = Roles::STATUS_OK;
            $description = "超级管理员!";
            $roleId =  Roles::query()->insertGetId(compact('name', 'description', 'status'));
        } else{
            $roleId = $roles->id;
        }
        if (!empty($node)) {
            // 添加角色
            $this->permissionService->setPermissions($node, $roleId);
        }
        $userId = User::query()->where('name',$name)->value('id');
        $this->roleService->setRoles([$roleId],$userId);
        $this->info("给demo用户赋予角色: $name");
    }
}