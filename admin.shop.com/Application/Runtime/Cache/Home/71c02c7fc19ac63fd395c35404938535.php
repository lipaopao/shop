<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ECSHOP 管理中心 - 商品品牌 </title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="http://admin.shop.com/Public/Home/Styles/general.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Home/Styles/main.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Home/Styles/page.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Home/Styles/page.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('add');?>">添加品牌</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 商品品牌 </span>
    <div style="clear:both"></div>
</h1>
<div class="form-div">
    <form action="<?php echo U();?>" name="searchForm">
        <img src="http://admin.shop.com/Public/Home/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
        <input type="text" name="brandName"  value="<?php echo I('get.brandName');?>" size="15" />
        <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>
<form method="post" action="" name="listForm">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>品牌名称</th>
                <th>品牌网址</th>
                <th>品牌图片</th>
                <th>品牌描述</th>
                <th>排序</th>
                <th>是否显示</th>
                <th>操作</th>
            </tr>

            <?php if(is_array($rows['rows'])): foreach($rows['rows'] as $key=>$row): ?><tr>
                <td class="first-cell">
                    <span style="float:right;text-align: center;"><a href="" target="_brank"><?php echo ($row["brandname"]); ?><img src="" width="16" height="16" border="0" alt="品牌LOGO" /></a></span>
                    <span></span>
                </td>
                <td align="center">
                    <a href="<<?php echo ($val["site_url"]); ?>>" target="_brank"><?php echo $row['brandurl'];?></a>
                </td>
                <td align="center">
                    <?php echo $row['brandlogo'];?>
                </td>
                <td align="center"><?php echo $row['brandds'];?></td>
                <td align="center">排序：<?php echo $row['brandpx'];?></td>
                <td align="center"><img src="http://admin.shop.com/Public/Home/Images/<?php echo $row['brandshow'];?>.gif"/></td>
                <td align="center">
                    <a href="<?php echo U('edit',['id'=>$row['id']]);?>" title="编辑">编辑</a> |
                    <a href="<?php echo U('remove',['id'=>$row['id']]);?>" title="移出">移除</a>
                </td>
            </tr><?php endforeach; endif; ?>
        </table>
    </div>
</form>
<div class="page"><?php echo ($rows["pageBer"]); ?></div>

<div id="footer">
    共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>