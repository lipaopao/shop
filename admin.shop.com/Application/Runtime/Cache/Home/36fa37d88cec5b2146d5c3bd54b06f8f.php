<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ECSHOP 管理中心 - 添加新商品 </title>
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
    <span class="action-span"><a href="__GROUP__/Goods/goodsList">商品列表</a>
    </span>
    <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加新商品 </span>
    <div style="clear:both"></div>
</h1>

<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front" id="general-tab">通用信息</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="<?php echo U('edit');?>" method="post">
            <table width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="name" value="<?php echo ($goodsOne["name"]); ?>"size="30" />
                        <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">商品货号： </td>
                    <td>
                        <input type="text" name="sn" value="<?php echo ($goodsOne["sn"]); ?>" size="20"/>
                        <span id="goods_sn_notice"></span><br />
                        <span class="notice-span"id="noticeGoodsSN">如果您不输入商品货号，系统将自动生成一个唯一的货号。</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">品牌LOGO</td>
                    <td>
                        <input type="file" id="logo" size="45"><br/>
                        <input type="hidden" name='logo' value="<?php echo ($goodsOne["logo"]); ?>" id='logourl'/>
                        <img src='<?php echo ($goodsOne["logo"]); ?>' id='logo-preview' style='max-width: 80px;max-height: 60px;margin-top:10px'/>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品分类：</td>
                    <td>
                        <select name="goods_category_id" id="goods_category_id">
                            <option value="0">顶级分类...</option>
                            <?php if(is_array($rows["categorys"])): foreach($rows["categorys"] as $key=>$category): ?><option value="<?=$category['id']?>"><?php echo str_repeat('&nbsp;',($category['level']-1)*5); echo ($category["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品品牌：</td>
                    <td>
                        <select name="brand_id" id="brand_id">
                            <option value="0">品牌选择...</option>
                            <?php if(is_array($rows["brands"])): foreach($rows["brands"] as $key=>$brand): ?><option value="<?php echo ($brand["id"]); ?>"><?php echo ($brand["brandname"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <input type="text" name="market_price" value="<?php echo ($goodsOne["market_price"]); ?>" size="20" />
                    </td>
                </tr>
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <input type="text" name="shop_price" value="<?php echo ($goodsOne["market_price"]); ?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">库存：</td>
                    <td>
                        <input type="text" name="stock" size="8" value="<?php echo ($goodsOne["stock"]); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品状态：</td>
                    <td>
                        <input type="checkbox" name="goods_status[]" value="1" class="goods_status"/> 精品
                        <input type="checkbox" name="goods_status[]" value="2" class="goods_status"/> 新品
                        <input type="checkbox" name="goods_status[]" value="4" class="goods_status"/> 热销
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <input type="radio" name="is_on_sale" value="1" class="is_on_sale"/> 是
                        <input type="radio" name="is_on_sale" value="0" class="is_on_sale"/> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">状态：</td>
                    <td>
                        <input type="radio" name="status" value="1" class="status"/> 是
                        <input type="radio" name="status" value="0" class="status"/> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">排序：</td>
                    <td>
                        <input type="text" name="sort" size="5" value="<?php echo ($goodsOne["sort"]); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品图片：</td>
                    <td>
                        <input type="file" id="logos" size="45"><br/>
                        <!--图片回显-->
                        <div id="goods_logos">
                            <?php if(is_array($rows["categorys"])): foreach($rows["categorys"] as $key=>$category): ?><img src="" alt=""/><input type="hidden" value="{}" name="path[]"/><?php endforeach; endif; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品内容：</td>
                    <td>
                        <textarea name="content" rows="10" cols="70"><?php echo ($goodsIntroOne["content"]); ?></textarea>
                    </td>
                </tr>
                <tr><td class="label">添加时间：</td><td><?php echo date('Y-m-d H:i:s',time());?></td></tr>
            </table>
            <div class="button-div">
                <input type="hidden" value="<?php echo ($goodsOne["id"]); ?>" name="id">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>
</div>

<div id="footer">
    共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
<script type="text/javascript">
    $(function(){
        $('#goods_category_id').val([<?php echo ($goodsOne["goods_category_id"]); ?>]);
        $('#brand_id').val([<?php echo ($goodsOne["brand_id"]); ?>]);
        $('.is_on_sale').val([<?php echo ($goodsOne["is_on_sale"]); ?>]);
        $('.status').val([<?php echo ($goodsOne["status"]); ?>]);
    $('.goods_status').val(<?php echo ($goodsOne["goods_status"]); ?>);
    $('#logo').uploadify({
        'swf'      : 'http://admin.shop.com/Public/Home/uploadify/uploadify.swf',
        'uploader' : "<?php echo U('Upload/upload');?>",
        onUploadSuccess:function(file,data) {
            //将响应数据转换为json对象
            data = $.parseJSON(data);
            $('#logourl').val(data.url);
            $('#logo-preview').attr('src', data.url);
        }
    })
    //商品图片
    $('#logos').uploadify({
        'swf'      : 'http://admin.shop.com/Public/Home/uploadify/uploadify.swf',
        'uploader' : "<?php echo U('Upload/upload');?>",
        buttonText:' 选择文件 ',
        onUploadSuccess:function(file,data) {
            //将响应数据转换为json对象
            data = $.parseJSON(data);

            var html = '';
            html += '<img src="'+data.url+'"/><input type="hidden" value="'+data.url+'" name="path[]"/>';

            $(html).appendTo($('#goods_logos'));
        }
    })
    })
</script>
</body>
</html>