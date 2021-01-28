<template>
    <div class="app-container">
        <div class="filter-container">
            <el-form>
                <el-form-item>
                    <el-button type="success" @click="addRole" icon="el-icon-plus"></el-button>
                </el-form-item>
            </el-form>
        </div>

        <div class="content-container" v-loading="listLoading">
            <el-table :data="list" border style="width: 100%">
                <el-table-column prop="name" label="角色名称"></el-table-column>
                <el-table-column prop="status_text" label="角色状态">
                    <template slot-scope="{row}">
                        <el-tag>{{ row.status_text }}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="description" label="角色描述"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="{row}">
                        <el-button @click="edit(row)" type="primary" icon="el-icon-edit-outline"></el-button>
                        <el-button @click="del(row)"  type="danger" icon="el-icon-delete"></el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <el-dialog :title="title" :visible.sync="dialogVisible" :before-close="handleClose">
            <el-form ref="roleForm" :model="form" label-width="100px" v-loading="formLoadding">
                <el-form-item label="角色名称: " :required="true" prop="name">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="角色状态: " :required="true" prop="status">
                    <el-radio v-model="form.status" label="1">启用</el-radio>
                    <el-radio v-model="form.status" label="2">禁用</el-radio>
                </el-form-item>
                <el-form-item label="角色描述: " :required="true" prop="description">
                    <el-input type="textarea" v-model="form.description"></el-input>
                </el-form-item>
                <el-form-item label="权限选择: ">
                    <el-tree ref="permissionTree" :data="form.permissions" show-checkbox :default-expanded-keys="form.ownPermissionsIds" :default-checked-keys="form.ownPermissionsIds" node-key="id" :props="defaultProps" @check-change="nodeChange" />
                </el-form-item>
                <el-form-item>
                    <el-button @click="submit">提交</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>

    </div>
</template>
<script>
import {
    getRoleList,
    getRolePermission,
    setRolePermission,
    delRole
} from "@/api/auth";
import layoutMix from "@/components/mixins/layout";
import { param } from "../../utils";

export default {
    mixins: [layoutMix],
    data () {
        return {
            listLoading: true,
            list: [],
            title: "",
            dialogVisible: false,
            form: {
                id: undefined,
                name: undefined,
                description: undefined,
                status: "1",
                node_ids: [],
                ownPermissionsIds: []
            },
            formLoadding: true,
            defaultProps: {
                children: "children",
                label: "name"
            }
        };
    },
    methods: {
        /**
         * 添加角色
         */
        async addRole () {
            await this.setFormPermissionTree(null);
            this.formLoadding = false;
            this.title = "新增角色";
            this.dialogVisible = true;
        },

        /**
         * 获取table列表
         */
        getTableList (params) {
            this.listLoading = true;
            getRoleList(params).then(response => {
                const { data } = response;
                this.list = data.list;
                this.listLoading = false;
            });
        },

        /**
         * 编辑角色
         */
        async edit (item) {
            this.form = Object.assign(this.form, {
                name: item.name,
                description: item.description,
                status: item.status.toString(),
                id: item.id
            });
            await this.setFormPermissionTree(item.id);
            this.title = "编辑角色 | " + item.name;
            this.dialogVisible = true;
            this.formLoadding = false;
        },

        /**
         * 设置权限节点树
         */
        setFormPermissionTree (id) {
            new Promise((resolve, reject) => {
                getRolePermission({ id: id }).then(r => {
                    const { data } = r;
                    // 强制dom渲染
                    this.$set(
                        this.form,
                        "permissions",
                        data.allPermissionsNode
                    );
                    this.$set(
                        this.form,
                        "ownPermissionsIds",
                        data.ownPermissionsIds
                    );
                    resolve(true);
                });
            });
        },

        /**
         * 节点发生改变
         */
        nodeChange () {
            let keys = this.$refs.permissionTree.getCheckedKeys();
            this.form.node_ids = keys;

            console.log(keys);
        },

        /**
         * 删除角色
         */
        async del (item) {
            await this.confirmOk("是否删除");
            delRole({ id: item.id }).then(r => {
                if (r.code == 200) {
                    this.$message({
                        type: "success",
                        message: "成功!",
                        duration: 5 * 1000
                    });
                    this.getTableList();
                }
            });
        },

        /**
         * 创建或者更新角色
         */
        updateOrCreate () {
            let params = this.form;
            return new Promise((resolve, reject) => {
                setRolePermission(params).then(r => {
                    if (r.code == 200) {
                        this.message();
                        this.dialogVisible = false;
                        this.getTableList();
                        this.form = this.$options.data().form;
                        resolve();
                    } else {
                        this.$message(r.message);
                        reject();
                    }
                });
            });
        },

        /**
         * 提交
         * 新增或者更新角色
         */
        async submit () {
            await this.validateForm("roleForm");
            await this.updateOrCreate();
        }
    },
    mounted () {
        this.getTableList();
    }
};
</script>
