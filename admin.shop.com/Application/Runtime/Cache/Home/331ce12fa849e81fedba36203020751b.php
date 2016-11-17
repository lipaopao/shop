<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: category_info.htm 16752 2009-10-20 09:59:38Z wangleisvn $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ECSHOP 管理中心 - 添加菜单 </title>
        <meta name="robots" content="noindex, nofollow"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="http://admin.shop.com/Public/Home/Styles/general.css" rel="stylesheet" type="text/css" />
        <link href="http://admin.shop.com/Public/Home/Styles/main.css" rel="stylesheet" type="text/css" />
        <link href="http://admin.shop.com/Public/Home/ztree/zTreeStyle.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h1>
            <span class="action-span"><a href="<?php echo U('index');?>">菜单列表</a></span>
            <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
            <span id="search_id" class="action-span1"> - 添加菜单 </span>
        </h1>
        <div style="clear:both"></div>
        <div class="main-div">
            <form action="<?php echo U();?>" method="post" name="theForm" enctype="multipart/form-data">
                <table width="100%" id="general-table">
                    <tr>
                        <td class="label">菜单名称:</td>
                        <td>
                            <input type='text' name='name' value='<?php echo ($row["name"]); ?>' size='27' /> <font color="red">*</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">菜单路径:</td>
                        <td>
                            <input type='text' name='path' value='<?php echo ($row["path"]); ?>' size='27' />
                        </td>
                    </tr>
                    <tr>
                        <td class="label">上级菜单:</td>
                        <td>
                            <input type="hidden" name="parent_id" id="parent_id" value="<?php echo ($row["parent_id"]); ?>"/>
                            <ul id="parent_nodes" class="ztree"></ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">关联权限:</td>
                        <td>
                            <div id='permission_ids'></div>
                            <ul id="permission_nodes" class="ztree"></ul>
                        </td>
                    </tr>
                </table>
                <div class="button-div">
                    <input type="hidden" name="id" value='<?php echo ($row["id"]); ?>'/>
                    <input type="submit" value=" 确定 " />
                    <input type="reset" value=" 重置 " />
                </div>
            </form>
        </div>

        <div id="footer">
            共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
            版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
        </div>

        <script type="text/javascript" src="http://admin.shop.com/Public/Home/Js/jquery.min.js"></script>
        <script type="text/javascript" src="http://admin.shop.com/Public/Home/ztree/jquery.ztree.core.min.js"></script>
        <script type="text/javascript" src="http://admin.shop.com/Public/Home/ztree/jquery.ztree.excheck.min.js"></script>

        <script type="text/javascript">
            $(function() {
                $('#parent_id').val([<?php echo ($row["parent_id"]); ?>]);
            });


            var setting = {
                data: {
                    simpleData: {
                        enable: true,
                        pIdKey:'parent_id',
                    }
                },
                callback:{
                    onClick:function(event,ele_id,node){
                        $('#parent_id').val(node.id);
                    },
                },
            };

            var zNodes = <?php echo ($menus); ?>;

            $(document).ready(function() {
                var ztree_obj = $.fn.zTree.init($("#parent_nodes"), setting, zNodes);
                ztree_obj.expandAll(true);
                <?php if(isset($row)): ?>//回显数据，选中父级节点
                    //找到节点
                    var parent_node = ztree_obj.getNodeByParam('id',<?php echo ($row["parent_id"]); ?>);
                    ztree_obj.selectNode(parent_node)<?php endif; ?>
            });

            //展示权限列表
            var perm_setting = {
                data: {
                    simpleData: {
                        enable: true,
                        pIdKey:'parent_id',
                    }
                },
                check:{
                    enable:true,
                },
                callback:{
                    onCheck:function(){
                        //获取所有的被勾选权限
                        var nodes = perm_ztree_obj.getCheckedNodes(true);
                        var box = $('#permission_ids');
                        box.empty();
                        $(nodes).each(function(i,v){
                            var html = '<input type="hidden" name="permission_id[]" value="'+v.id+'"/>';
                            $(html).appendTo(box);
                        });
                    },
                },
            };
            var perm_nodes = <?php echo ($permissions); ?>;
            var perm_ztree_obj;
            $(function(){
                perm_ztree_obj = $.fn.zTree.init($("#permission_nodes"), perm_setting, perm_nodes);
                perm_ztree_obj.expandAll(true);

                //回显权限
                <?php if(isset($row)): ?>var permission_ids = <?php echo ($row["permission_ids"]); ?>;
                $(permission_ids).each(function(i,v){
                    //找到节点
                    var node = perm_ztree_obj.getNodeByParam('id',v);
                    //选中节点
                    perm_ztree_obj.checkNode(node,true,false,true);
                });<?php endif; ?>
            });
            
        </script>
    </body>
</html>