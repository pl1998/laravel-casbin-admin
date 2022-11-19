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

    protected $permissionService;
    protected $roleService;

    protected const ADMIN_NAME='admin';
    protected const DEMO_NAME='demo';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PermissionService $permissionService, RoleService $roleService)
    {
        $this->permissionService = $permissionService;
        $this->roleService = $roleService;


        $node = Permissions::query()
            ->where('status', Permissions::STATUS_OK)
            ->pluck('id')->toArray();

        $this->createAdmin(self::ADMIN_NAME, $node);

        $node = Permissions::query()
            ->where('status',Permissions::STATUS_OK)
            ->where('is_menu', Permissions::IS_MENU_YES)
            ->orWhere(function ($query){
                $query
                    ->where('is_menu', Permissions::IS_MENU_NO)
                    ->where('method',Permissions::HTTP_REQUEST_GET);
            })
            ->pluck('id')->toArray();

        $this->createAdmin(self::DEMO_NAME, $node);
    }

    public function createAdmin($name, $node): void
    {
        $roles = Roles::query()->where('name', $name)->first();
        $description = 'admin' === $name ? '超级管理员!' : 'demo角色';
        if (!$roles) {

            $roleId = Roles::query()->insertGetId([
                'name' => $name,
                'description' => $description,
                'status' => Roles::STATUS_OK,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ]);
        } else {
            $roleId = $roles->id;
        }
        if (!empty($node)) {
            // 添加角色
            $this->permissionService->setPermissions($node, $roleId);
        }
        $userId = User::query()->where('name', $name)->value('id');
        $this->roleService->setRoles([$roleId], $userId);
        $this->info("给{$description}用户赋予角色: {$name}");
    }
}
