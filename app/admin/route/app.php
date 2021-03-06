<?php
use think\facade\Route;

 Route::get('test/hello', 'Test/hello');

//有种东西叫虚拟分组
//不需要鉴权的常规路由
Route::group(function () {
    //用户登录
    Route::post('User/login', 'User/login');

});

//需要鉴权的路由分组
Route::group(function () {
    //获取用户信息
    Route::get('User/getInfo', 'User/getInfo');
    //用户退出登录
    Route::put('User/logout', 'User/logout');
    //获取用户路由权限
    Route::get('User/getPermissionRouter', 'User/getPermissionRouter');

    //路由资源添加
    Route::post('RouteResource/routeResourceAdd', 'RouteResource/routeResourceAdd');
    //路由资源配置
    Route::get('RouteResource/routeResourceOptions', 'RouteResource/routeResourceOptions');
    //路由资源列表
    Route::get('RouteResource/routeResourceList', 'RouteResource/routeResourceList');
    //路由资源详情
    Route::get('RouteResource/routeResourceDetail', 'RouteResource/routeResourceDetail');
    //路由资源编辑
    Route::put('RouteResource/routeResourceEdit', 'RouteResource/routeResourceEdit');
    //路由资源删除
    Route::delete('RouteResource/routeResourceDelete', 'RouteResource/routeResourceDelete');
    //路由资源节点树
    Route::get('RouteResource/routeResourceNodes', 'RouteResource/routeResourceNodes');

    //角色添加
    Route::post('Role/roleAdd', 'Role/roleAdd');
    //角色列表
    Route::get('Role/roleList', 'Role/roleList');
    //角色详情
    Route::get('Role/roleDetail', 'Role/roleDetail');
    //角色编辑
    Route::put('Role/roleEdit', 'Role/roleEdit');
    //角色删除
    Route::delete('Role/roleDelete', 'Role/roleDelete');
    //所有角色选项
    Route::get('Role/roleAllList', 'Role/roleAllList');

    //管理员添加
    Route::post('Admin/adminAdd', 'Admin/adminAdd');
    //管理员列表
    Route::get('Admin/adminList', 'Admin/adminList');
    //管理员详情
    Route::get('Admin/adminDetail', 'Admin/adminDetail');
    //管理员编辑
    Route::put('Admin/adminEdit', 'Admin/adminEdit');
    //管理员删除
    Route::delete('Admin/adminDelete', 'Admin/adminDelete');

    //腾讯云-实例列表
    Route::get('Txy/hostList', 'Txy/hostList');
    //可重装的系统列表
    Route::get('Txy/osList', 'Txy/osList');
    //开机
    Route::get('Txy/osOpen', 'Txy/osOpen');
    //关机
    Route::get('Txy/osClose', 'Txy/osClose');
    //防火墙列表
    Route::get('Txy/firewallList', 'Txy/firewallList');
    //删除防火墙规则
    Route::post('Txy/firewallDel', 'Txy/firewallDel');
    //添加防火墙规则
    Route::post('Txy/createFirewall', 'Txy/createFirewall');
})->middleware(\app\admin\middleware\Auth::class);
