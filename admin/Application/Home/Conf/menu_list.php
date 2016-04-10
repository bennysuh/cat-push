<?php
/**
 * 菜单列表
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016-04-10
 */
$config = array(
    'MENU_LIST' => array(
        array(
            'name' => 'WEB消息推送',
            'submenu_list' => array(
                array('name'=>'在线用户列表','url'=>'/web/userList'),
                array('name'=>'推送消息','url'=>'/web/pushMsg'),
            ),
        ),
        array(
            'name' => 'APP消息推送',
            'submenu_list' => array(
                array('name'=>'在线用户列表','url'=>'/app/userList'),
                array('name'=>'推送消息','url'=>'/app/pushMsg'),
            ),
        ),
    ),
);
return $config;