<?php if (!defined('THINK_PATH')) exit();?><div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <?php if(is_array($menuList)): foreach($menuList as $menu_key=>$menu): ?><ul class="nav nav-sidebar">
                    <li class="active"><a href="javascript:;"><?php echo ($menu["name"]); ?></a></li>
                    <?php if(is_array($menu["submenu_list"])): foreach($menu["submenu_list"] as $submenu_key=>$submenu): ?><li>
                            <a href="<?php echo ($submenu["url"]); ?>">
                                <?php echo ($submenu["name"]); ?>
                                <?php if(strtoupper($currMenu) == strtoupper($submenu['url'])): ?>&nbsp;&nbsp;<span aria-hidden="true" class="glyphicon glyphicon-hand-left"></span><?php endif; ?>                        
                            </a>
                        </li><?php endforeach; endif; ?>
                </ul><?php endforeach; endif; ?>            
        </div>