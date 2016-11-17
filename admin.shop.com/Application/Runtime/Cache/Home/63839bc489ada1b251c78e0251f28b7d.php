<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ECSHOP 管理中心 - 商品列表 </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="http://admin.shop.com/Public/Home/Styles/general.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Home/Styles/main.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Home/Styles/page.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('add');?>">添加新商品</a></span>
    <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 商品列表 </span>
    <div style="clear:both"></div>
</h1>
<div class="form-div">
    <form action="<?php echo U('index');?>" name="searchForm">
        <img src="http://admin.shop.com/Public/Home/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
        <!-- 分类 -->
        <select name="category">
            <option value="0">所有分类</option>
            <?php if(is_array($rows['categorys'])): foreach($rows['categorys'] as $key=>$category): ?><option value="<?php echo ($category["id"]); ?>"><?php echo str_repeat('&nbsp;',($category['level']-1)*5); echo ($category["name"]); ?></option><?php endforeach; endif; ?>
        </select>
        <!-- 品牌 -->
        <select name="brand">
            <option value="0">所有品牌</option>
            <?php if(is_array($rows['brands'])): foreach($rows['brands'] as $key=>$brand): ?><option value="<?php echo ($brand["id"]); ?>"><?php echo ($brand["brandname"]); ?></option><?php endforeach; endif; ?>
        </select>
        <!-- 推荐 -->
        <select name="intro_type">
            <option value="0">全部</option>
            <option value="is_best">精品</option>
            <option value="is_new">新品</option>
            <option value="is_hot">热销</option>
        </select>
        <!-- 上架 -->
        <select name="is_on_sale">
            <option value=''>全部</option>
            <option value="1">上架</option>
            <option value="0">下架</option>
        </select>
        <!-- 关键字 -->
        关键字 <input type="text" name="keyword" size="15" />
        <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>

<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>商品名称</th>
                <th>货号</th>
                <th>图片</th>
                <th>商品分类</th>
                <th>商品品牌</th>
                <th>商品价格</th>
                <th>总数</th>
                <th>是否上架</th>
                <th>精品</th>
                <th>状态</th>
                <th>推荐排序</th>
                <th>录入时间</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($cont['rows'])): foreach($cont['rows'] as $key=>$row): ?><tr>
                    <td align="center" class="first-cell"><span><?php echo ($row["name"]); ?></span></td>
                    <td align="center"><span onclick=""><?php echo ($row["sn"]); ?></span></td>
                    <td align="center"><img src="<?php echo ($row["logo"]); ?>"/></td>
                    <td align="center"><span><?php echo ($row["categoryname"]); ?></span></td>
                    <td align="center"><span><?php echo ($row["brandname"]); ?></span></td>
                    <td align="center"><span><?php echo ($row["shop_price"]); ?></span></td>
                    <td align="center"><span><?php echo ($row["stock"]); ?></span></td>
                    <td align="center"><span><img src="http://admin.shop.com/Public/Home/Images/<?php echo ($row["is_on_sale"]); ?>.gif"/></span></td>
                    <td align="center"><span><?php echo ($row["goods_status"]); ?></span></td>
                    <td align="center"><span><img src="http://admin.shop.com/Public/Home/Images/<?php echo ($row["status"]); ?>.gif"/></span></td>
                    <td align="center"><span><?php echo ($row["sort"]); ?></span></td>
                    <td align="center"><span><?php echo date('Y-m-d H:i:s',$row['inputtime']);?></span></td>
                    <td align="center">
                        <a href="<?php echo U('content',['id'=>$row['id']]);?>" target="_blank" title="查看"><img src="http://admin.shop.com/Public/Home/Images/icon_view.gif" width="16" height="16" border="0" /></a>
                        <a href="<?php echo U('edit',['id'=>$row['id']]);?>" title="编辑"><img src="http://admin.shop.com/Public/Home/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
                        <a href="<?php echo U('remove',['id'=>$row['id']]);?>" onclick="" title="回收站"><img src="http://admin.shop.com/Public/Home/Images/icon_trash.gif" width="16" height="16" border="0" /></a></td>
                </tr><?php endforeach; endif; ?>
        </table>

        <!-- 分页开始 -->
        <div class="page">

                    <?php echo ($cont["pageBer"]); ?>

        </div>
        <!-- 分页结束 -->
    </div>
</form>

<div id="footer">
    共执行 7 个查询，用时 0.028849 秒，Gzip 已禁用，内存占用 3.219 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>