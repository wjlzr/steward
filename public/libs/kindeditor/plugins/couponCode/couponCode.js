/**
 * Created by wangqingqing on 14-10-13.
 */

var self = '';

KindEditor.plugin('couponCode', function(K) {

    self = this, name = 'couponCode';
    // 点击图标时执行
    self.clickToolbar(name, function() {

        layer.open({
            title: '卡券列表',
            type: 2,
            offset: '20px',
            area: ['800px', '510px'],
            content: '/postsystem/app/plugin/cardCouponPlugin.pscript?operFlg=1'
        });

    });
});

var plugin = {

    cardCoupon: function ( data ) {
        data.limit_money = parseFloat(data.limit_money) / 1;
        data.salePrice = parseFloat(data.salePrice) / 1;
        data.amount = parseFloat(data.amount) / 1;
        var replaceFlagArr =['{\\$ebsig_couponGet}',
                                            '{\\$ebsig_couponNname}',
                                            '{\\$ebsig_couponAmount}',
                                            '{\\$ebsig_couponAiscount}',
                                            '{\\$ebsig_couponGoodsName}',
                                            '{\\$ebsig_couponGoodsSalePrice}',
                                            '{\\$ebsig_couponPreDes}',
                                            '{\\$ebsig_couponDes}',
                                            '{\\$ebsig_couponLimit}',
                                            '{\\$ebsig_couponDate}'];

        if (E.empty(data.description)) {
            data.description = '';
        }

        if (E.empty(data.privilege_description)) {
            data.privilege_description = '';
        }

        if (!E.empty(data.discount)) {
            data.discount = parseFloat((data.discount * 10).toFixed(1));
        }

        var replaceValueArr =['getOffLineCoupon(' + data.card_id + ');',data.card_name, data.amount,data.discount,data.goods_name,data.salePrice,data.privilege_description,data.description];

        var coupon_limit = '任意金额使用';
        if (E.inArray(data.card_type, [1,2,5]) && data.limit_money > 0) {
            coupon_limit = '满 ' + data.limit_money + '元 使用';
        }
        replaceValueArr.push(coupon_limit);

        var couponDate = '';
        if (data.time_type == 1) {
            couponDate = data.start_date + ' - ' + data.end_date;
        } else {
            couponDate = data.valid_day + '天';
        }
        replaceValueArr.push(couponDate);

        var coupon = addHtmlTags( data.template_code, replaceFlagArr, replaceValueArr);

        self.insertHtml(coupon);
    }
}

function addHtmlTags ( str, replaceFlagArr, replaceValueArr ){
    var return_str = str;
    $.each(replaceFlagArr, function(k,v){
        var regExp = new RegExp( v , "g");
        return_str = return_str.replace(regExp, replaceValueArr[k]);
    });
    return return_str;
}





