$(document).on('click', '#js-pic-list .glyphicon-remove', function () {
    /**
     * 删除商品图片
     */
    $(this).parent().remove();

    var len = $('#js-pic-list').find('li').length;
    if( len <= 5 ){
        $('#js-pic-list').find('li.no-sort').show();
    }
}).on('mouseenter', '#js-pic-list li.sort', function () {
    /**
     * 显示图片删除按钮
     */
    $(this).find('.glyphicon-remove').show();
}).on('mouseleave', '#js-pic-list li.sort', function () {
    /**
     * 隐藏图片删除按钮
     */
    $(this).find('.glyphicon-remove').hide();
}).on('click', '#js-pic-list li.sort', function (e) {
    /**
     * 放大图片
     */
    var glyphicon  = $(this).find('.glyphicon')[0];

    if( e.target!= glyphicon &&!$.contains(glyphicon, e.target)){

        var img = $(this).find('img').attr('data-target');
        if ( !img ) {
            return true;
        }
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            area: ['500px', '500px'],
            skin: 'layui-layer-nobg', //没有背景色
            shadeClose: true,
            content:  '<img src="' + img + '" width="100%" height="100%">'
        });
    }
});