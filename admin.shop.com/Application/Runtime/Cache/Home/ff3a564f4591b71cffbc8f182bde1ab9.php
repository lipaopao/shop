<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ECSHOP 管理中心 - 添加品牌 </title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="http://admin.shop.com/Public/Home/Styles/general.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Home/Styles/main.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Home/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="http://admin.shop.com/Public/Home/Js/jquery.min.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Home/uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Home/layer/layer.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('index');?>">商品品牌</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加品牌 </span>
    <div style="clear:both"></div>
</h1>
<div class="main-div">
    <form method="post" action="<?php echo U();?>"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">品牌名称</td>
                <td>
                    <input type="text" name="brandName" maxlength="60" value="<?php echo ($row["brandname"]); ?>" />
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">品牌网址</td>
                <td>
                    <input type="text"  name="brandUrl" maxlength="60" size="40" value="<?php echo ($row["brandurl"]); ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">品牌LOGO</td>
                <td>
                    <input type="file" id="logo" size="45"><br/>
                    <input type="hidden" name='brandlogo' value="<?php echo ($row["brandlogo"]); ?>" id='logourl'/>
                    <img src='<?php echo ($row["brandlogo"]); ?>' id='logopreview' style='max-width: 80px;max-height: 60px;margin-top:10px'/>
                </td>
            </tr>
            <tr>
                <td class="label">品牌描述</td>
                <td>
                    <textarea   name="brandDs" cols="60" rows="4"  ><?php echo ($row["brandds"]); ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="label">排序</td>
                <td>
                    <input type="text" name="brandPx" maxlength="40" size="15" value="<?php echo ((isset($row["brandpx"]) && ($row["brandpx"] !== ""))?($row["brandpx"]):20); ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">是否显示</td>
                <td>
                    <input type="radio" name="brandShow" value="1" class='status'/> 是
                    <input type="radio" name="brandShow" value="0"  class='status'/> 否(当品牌下还没有商品的时候，首页及分类页的品牌区将不会显示该品牌。)
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><br />
                    <input type="hidden" value="<?php echo ($row["id"]); ?>" name='id'/>
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="footer">
    共执行 1 个查询，用时 0.018952 秒，Gzip 已禁用，内存占用 2.197 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>

<script type="text/javascript">
    $(function(){
        $('.status').val([<?php echo ((isset($row["brandshow "]) && ($row["brandshow "] !== ""))?($row["brandshow "]): 1); ?>]);
        $('#logo').uploadify({
                'swf'      : 'http://admin.shop.com/Public/Home/uploadify/uploadify.swf',
                'uploader' : "<?php echo U('Upload/upload');?>",
            onUploadSuccess:function(file,data) {
                //将响应数据转换为json对象
                data = $.parseJSON(data);
                $('#logourl').val(data.url);
                $('#logopreview').attr('src', data.url);
            }
         })
    })
</script>
</body>

</html>