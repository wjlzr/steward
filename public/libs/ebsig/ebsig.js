(function() {

    var W = window,
        D = document,
        N = navigator;

    var E = {

        ajax_timeout: 30000,

        /**
         * 检查浏览器类型
         * @returns {string}
         */
        browser: function() {
            var browserName = N.userAgent.toLowerCase();
            if (/MicroMessenger/i.test(browserName)) {
                return "MicroMessenger";
            } else if (/AlipayClient/i.test(browserName)) {
                return "AlipayClient";
            } else if (/EbsigClient/i.test(browserName)) {
                return "EbsigClient";
            } else if (/msie/i.test(browserName) && !/opera/.test(browserName)) {
                return "IE" + N.appVersion.substring(22, 23);
            } else if (/firefox/i.test(browserName)){
                return "Firefox";
            } else if (/chrome/i.test(browserName) && /webkit/i.test(browserName) && /mozilla/i.test(browserName)) {
                return "Chrome";
            } else if (/opera/i.test(browserName)) {
                return "Opera";
            } else if (/webkit/i.test(browserName) &&!(/chrome/i.test(browserName) && /webkit/i.test(browserName) && /mozilla/i.test(browserName))) {
                return "Safari";
            } else {
                return "unKnow";
            }
        },

        /**
         * 设置COOKIE
         * @param cookieName
         * @param cookieValue
         * @param cookieTime
         * @param cookieDomain
         */
        setCookie: function(cookieName, cookieValue ,cookieTime, cookieDomain) {
            var exp = new Date();
            exp.setTime(exp.getTime() + cookieTime * 1000);
            if (cookieTime == 0)
                document.cookie = cookieName + "=" + encodeURI(cookieValue) + ";path=/;domain=" + cookieDomain + ";";
            else
                document.cookie = cookieName + "=" + encodeURI(cookieValue) + ";expires=" + exp.toGMTString() + ";path=/;domain=" + cookieDomain + ";";
            return true;
        },

        /**
         * 获得cookie
         * @param cookieName
         * @returns {null}
         */
        getCookie: function(cookieName) {
            var strCookie = D.cookie;
            var arrCookie = strCookie.split("; ");
            var arrCookieCount = arrCookie.length;
            var arr,identifyContent = null;
            for(var i = 0; i < arrCookie.length ; i++){
                arr = arrCookie[i].split("=");
                if(cookieName == arr[0]){
                    var arrStr = D.cookie.split("; ");
                    identifyContent = decodeURIComponent(decodeURIComponent(arr[1]));
                    break;
                }
            }
            arrCookie = null;
            if (identifyContent == null)
                return null;
            else
                return identifyContent;
        },

        /**
         * 生成uuid
         * @returns {string}
         */
        createGuid: function() {
            var guid = '';
            for (var i = 1; i <= 32; i++){
                guid += Math.floor(Math.random()*16.0).toString(16);
            }
            return guid;
        },

        /**
         *冒泡排序
         * @param sort_data  排序对象
         * @param n 数量
         * @param sort_val 排序字段
         * @returns {*}
         */
        bubbleSort:function(sort_data,n,sort_val){

            var i, j;

            for(i = 0; i < n; i++){

                for(j = 0; i + j < n - 1; j++){
                    if(sort_data[j][sort_val] > sort_data[j + 1][sort_val] ){
                        var temp = sort_data[j];
                        sort_data[j] = sort_data[j + 1];
                        sort_data[j + 1] = temp;
                    }
                }

            }
            return sort_data;

        },

        /**
         * 合并两个对象数组
         * @param arr1
         * @param arr2
         * @returns {{}}
         */
        concat: function(arr1, arr2) {
            var newArr = arr1;
            for (var k2 in arr2){
                newArr[k2] = arr2[k2];
            }
            return newArr;
        },

        /**
         * 关闭浏览器窗口
         */
        closeWindows: function() {
            parent.window.opener = null;
            parent.window.open("", "_self");
            parent.window.close();
        },

        /*
         *
         *   判断在数组中是否含有给定的一个变量值
         *   参数：
         *   needle：需要查询的值
         *   haystack：被查询的数组
         *   在haystack中查询needle是否存在，如果找到返回true，否则返回false。
         *   此函数只能对字符和数字有效
         *
         */
        inArray: function(needle, haystack) {
            var t = false;
            $.each(haystack, function(k, v) {
                if (v == needle) {
                    t = true;
                    return false;
                }
            });
            return t;
        },

        /**
         * 检查参数是否为空
         * @param val
         * @returns {boolean}
         */
        empty: function( val ) {
            switch (typeof(val)){
                case "string":
                    return this.trim(val).length == 0 ? true : false;
                    break;
                case "number":
                    return val == 0;
                    break;
                case "object":
                    return val == null;
                    break;
                case "array":
                    return val.length == 0;
                    break;
                default:
                    return true;
            }
        },
        isEmpty: function( val ) {
            return this.empty( val );
        },

        /**
         * 检查日期获取日期+时间或时间格式
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isDate: function(s) {
            var   reg=  /^\d{4}-\d{2}-\d{2}$|^\d{4}-\d{2}-\d{2} \d{1,2}:\d{1,2}:\d{1,2}$|\d{1,2}:\d{1,2}:\d{1,2}$/;
            return reg.exec(s);
        },

        /**
         * 匹配email
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isEmail: function(s) {
            var   reg=  /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;
            return reg.exec(s);
        },

        /**
         * 匹配数字（整数）
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isDigital: function(s) {
            var reg = /^\d+$/;
            return reg.exec(s);
        },

        /**
         * 匹配数字（整数或小数）
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isNum: function(s) {
            var reg = /^\d+$|^\d+\.\d+$/;
            return reg.exec(s);
        },

        /**
         * 匹配非负整数（正整数+0）
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isInt: function(s) {
            var reg = /^[0-9]\d*$/;
            return reg.exec(s);
        },

        /**
         * 匹配小数
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isFloat: function(s) {
            var reg = /^(\d+)(\.(\d{1,2}))$/;
            return reg.exec(s);
        },

        /**
         * 匹配金额
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isMoney: function(s) {
            var reg = /^(([1-9]\d*(,\d{3})*)|([0-9]\d*))(\.(\d{1,2}))?$/;
            return reg.exec(s);
        },

        /**
         * 从字符串的两端删除空白字符和其他预定义字符
         * @param s
         * @returns {*}
         */
        trim: function(s) {
            return s.replace(/(^\s*)|(\s*$)/g, "");
        },

        /**
         * 匹配手机号码
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isMobile: function(s) {
            var reg = /(^13\d{9}$)|(^14)[5,7]\d{8}$|(^15[0,1,2,3,5,6,7,8,9]\d{8}$)|(^17)[0,1,2,3,5,6,7,8,9]\d{8}$|(^18\d{9}$)/g;
            return reg.exec(s);
        },

        /**
         * 匹配电话号码
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isPhone: function(s) {
            var reg = /^(0[0-9]{2,3}-)?([2-9][0-9]{6,7})+(-[0-9]{1,6})?$/;
            return reg.exec(s);
        },

        /**
         * 匹配汉字
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isChinese: function( s ) {
            var reg = /^[\u4e00-\u9fa5]+$/;
            return reg.exec(s) ;
        },

        /**
         * 匹配身份证号
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        isIdentity: function( s ) {
            var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
            return reg.exec(s);
        },


        /**
         * 检查变量是否定义
         * @param variable
         * @returns {boolean}
         */
        isDefined: function( variable ) {
            if (typeof(variable) == 'undefined'){
                return false;
            } else {
                return true;
            }
        },

        /**
         * 匹配密码
         */
        isPwd: function( s ) {
            var reg = /^[A-Za-z0-9_-]{6,30}$/;
            return reg.exec(s) ;
        },

        /**
         * 比较日期先后
         * @param sDate     开始日期
         * @param eDate       结束日期
         * @returns {boolean}
         */
        dateCompare : function(sDate, eDate){
            var s = sDate.replace(/-/g,"/");
            var e = eDate.replace(/-/g,"/");
            if (Date.parse(s) - Date.parse(e) > 0){
                return false;
            }
            return true;
        },

        /**
         * 编码 URL 字符串
         * @param str
         * @returns {string}
         */
        urlencode: function( str ) {
            str = str.replace(/\r\n/g,"\n");
            var utftext = "";
            for (var n = 0; n < str.length; n++) {
                var c = str.charCodeAt(n);
                if (c < 128) {
                    utftext += String.fromCharCode(c);
                } else if((c > 127) && (c < 2048)) {
                    utftext += String.fromCharCode((c >> 6) | 192);
                    utftext += String.fromCharCode((c & 63) | 128);
                } else {
                    utftext += String.fromCharCode((c >> 12) | 224);
                    utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                    utftext += String.fromCharCode((c & 63) | 128);
                }
            }
            return utftext;
        },

        /**
         * 解码已编码的 URL 字符串
         * @param utftext
         * @returns {string}
         */
        urldecode: function(utftext) {
            var str = "";
            var i = 0;
            var c = c1 = c2 = 0;
            while ( i < utftext.length ) {
                c = utftext.charCodeAt(i);
                if (c < 128) {
                    str += String.fromCharCode(c);
                    i++;
                } else if((c > 191) && (c < 224)) {
                    c2 = utftext.charCodeAt(i+1);
                    str += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                    i += 2;
                } else {
                    c2 = utftext.charCodeAt(i+1);
                    c3 = utftext.charCodeAt(i+2);
                    str += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                    i += 3;
                }
            }
            return str;
        },

        /**
         * 检查参数长度
         * @param val
         * @returns {number}
         */
        len: function( val ) {
            var l = 0;
            var a = val.split("");
            for (var i=0;i<a.length;i++) {
                if (a[i].charCodeAt(0)<299) {
                    l++;
                } else {
                    l+=2;
                }
            }
            return l;
        },

        /**
         * 检查字符串
         * @param str
         * @param startp
         * @param endp
         * @returns {*}
         */
        sb_substr: function(str, startp, endp) {
            var i= 0, c = 0, unicode = 0, rstr = '';
            var len = str.length;
            var sblen = this.len(str);
            if (startp < 0) {
                startp = sblen + startp;
            }
            if (endp < 1) {
                endp = sblen + endp;// - ((str.charCodeAt(len-1) < 127) ? 1 : 2);
            }
            for(i = 0; i < len; i++) {
                if (c >= startp) {
                    break;
                }
                var unicode = str.charCodeAt(i);
                if (unicode < 127) {
                    c += 1;
                } else {
                    c += 2;
                }
            }
            for(i = i; i < len; i++) {
                var unicode = str.charCodeAt(i);
                if (unicode < 127) {
                    c += 1;
                } else {
                    c += 2;
                }
                rstr += str.charAt(i);

                if (c >= endp) {
                    break;
                }
            }
            return rstr;
        },

        /**
         * 解析url
         * @param url
         * @returns {{source: *, protocol: string, host: (string|.parseUrl.hostname|*), port: (Function|E.parseURL.port|*|string|tc.port|.parseUrl.port), query: *, params, file: (string|*), hash: (*|XML|string|void), path: string, relative: *, segments: Array}}
         */
        parseURL: function( url ) {
            var a = document.createElement('a');
            a.href = url;
            return {
                source: url,
                protocol: a.protocol.replace(':', ''),
                host: a.hostname,
                port: a.port,
                query: a.search,
                params: (function () {
                    var ret = {},
                        seg = a.search.replace(/^\?/, '').split('&'),
                        len = seg.length, i = 0, s;
                    for (; i < len; i++) {
                        if (!seg[i]) { continue; }
                        s = seg[i].split('=');
                        ret[s[0]] = s[1];
                    }
                    return ret;

                })(),
                file: (a.pathname.match(/\/([^\/?#]+)$/i) || [, ''])[1],
                hash: a.hash.replace('#', ''),
                path: a.pathname.replace(/^([^\/])/, '/$1'),
                relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [, ''])[1],
                segments: a.pathname.replace(/^\//, '').split('/')
            };
        },

        /**
         *
         * @param url
         * @param againstParams  排斥参数   字符串型（ 例如：'uid,pcustID' ）
         * @param addParams      追加参数   对象（ 例如：{'id':123,'name':'try'} ）
         * @returns {string}
         */
        replaceUrlParams: function( url, againstParams , addParams ) {

            var url_obj = this.parseURL( url );

            var new_url = url_obj.protocol + '://' + url_obj.host;
            if (url_obj.path) {
                new_url += url_obj.path;
            }

            var i = 0;

            if (url_obj.params) {

                //将排斥参数打散成数组
                var againstParams_array = againstParams.split(',');

                $.each(url_obj.params, function(k, v) {
                    if( E.inArray( k , againstParams_array ) ){
                        return true;
                    }
                    if ( i == 0 ) {
                        new_url += '?' + k + '=' + v;
                    } else {
                        new_url += '&' + k + '=' + v;
                    }
                    i++;
                });
            }

            if( addParams ){

                $.each( addParams , function(k1,v1){

                    if ( i == 0 ) {
                        new_url += '?' + k1 + '=' + v1;
                    } else {
                        new_url += '&' + k1 + '=' + v1;
                    }

                    i++;

                } );

            }

            return new_url;

        },

        /**
         * 抓取表单数据
         * @param id
         * @returns {{}}
         */
        getFormValues: function(id) {
            var x = document.getElementById(id);
            var form_obj = {};
            var checkbox_name_array = {};
            for (var i = 0; i < x.length; i++) {
                var ele = x.elements[i];
                if (ele.name == undefined || ele.name == "undefined")
                    continue;
                if (ele.value == undefined || ele.value == "undefined")
                    continue;
                ele.name = this.trim(ele.name);
                ele.value = this.trim(ele.value);
                if (ele.name == "")
                    continue;
                if (ele.value == "")
                    continue;
                if (ele.type == "radio" && ele.checked == false)
                    continue;
                if (ele.type == "checkbox") {
                    if (!checkbox_name_array[ele.name]) {
                        checkbox_name_array[ele.name] = 0;
                        form_obj[ele.name] = new Array();
                    }
                    if (ele.checked == false)
                        continue;
                    else {
                        var index = checkbox_name_array[ele.name];
                        form_obj[ele.name][index] = ele.value;
                        checkbox_name_array[ele.name] += 1;
                    }
                } else {
                    ele.name_array = ele.name.split('[');
                    if (ele.name_array[1] != undefined && ele.name_array[1] == ']') {
                        var ele_name = ele.name_array[0];
                        if (!checkbox_name_array[ele_name]) {
                            checkbox_name_array[ele_name] = 0;
                            form_obj[ele_name] = new Array();
                        }
                        var index = checkbox_name_array[ele_name];
                        form_obj[ele_name][index] = ele.value;
                        checkbox_name_array[ele_name] += 1;
                    } else {
                        form_obj[ele.name] = ele.value;
                    }

                }

            }
            return form_obj;
        },

        /**
         * 获得页面高度和宽度
         */
        scroll: function() {
            var width = document.body.scrollWidth;
            var height = document.body.scrollHeight;
            if (document.documentElement) {
                width = Math.max(width, document.documentElement.scrollWidth);
                height = Math.max(height, document.documentElement.scrollHeight);
            }
            return {height: height, width: width};
        },

        /**
         * 刷新页面
         */
        refresh: function() {
            window.location.reload();
        },

        /**
         * ajax的post请求
         * @param args
         */
        ajax_post: function( args ) {
            if (args.url)
                var request_url = args.url;
            else
                var request_url = "/ajax-shop/default/" + args.action + ".ajax?operFlg=" + args.operFlg;
            $.ajax({
                type: "POST",
                url: request_url,
                dataType: "JSON",
                data: args.data,
                timeout: this.ajax_timeout,
                success: function( o ) {
                    if (typeof(args.call) == 'string') {
                        eval(args.call + "(o)");
                    } else {
                        args.call( o );
                    }
                },
                error: function( XMLHttpRequest, textStatus, errorThrown ) {

                    E.loadding.close();

                    switch (textStatus) {

                        case 'timeout':
                            E.alert('网络爆棚，请重新尝试！');
                            break;

                        default:
                            break;

                    }

                }
            });
        },

        /**
         * ajax的get请求
         * @param args
         */
        ajax_get: function( args ) {
            if (args.url)
                var request_url = args.url;
            else
                var request_url = "/ajax-shop/default/" + args.action + ".ajax?operFlg=" + args.operFlg;
            if (args.data) {
                $.each(args.data, function(k, v) {
                    request_url += "&" + k + "=" + v;
                });
            }
            $.ajax({
                type: "GET",
                url: request_url,
                dataType: "JSON",
                timeout: this.ajax_timeout,
                success: function( o ) {
                    if (typeof(args.call) == 'string') {
                        eval(args.call + "(o)");
                    } else {
                        args.call( o );
                    }
                },
                error: function( XMLHttpRequest, textStatus, errorThrown ) {

                    E.loadding.close();

                    switch (textStatus) {

                        case 'timeout':
                            E.alert('网络爆棚，请重新尝试！');
                            break;

                        default:
                            break;

                    }

                }
            });
        },

        /**
         * 生成验证码
         * @param id img标签的id
         * @param len 验证码长度 2或4，默认为4
         */
        captcha: function(id, len) {
            if (!len)
                len = 4;
            var img = "/core/yzm.html?len=" + len + "&code=" + Math.ceil(Math.random() * 10000);
            $("#" + id).attr("src", img);
        },

        /**
         * 获得当前日期
         * @returns {string}
         */
        date: function() {
            var dt = new Date(),
                y = dt.getFullYear(),
                m = parseInt(dt.getMonth()) + 1 > 9 ? parseInt(dt.getMonth()) + 1 : '0' + (parseInt(dt.getMonth()) + 1),
                d = dt.getDate() > 9 ? dt.getDate() : '0' + dt.getDate();
            return y + '-' + m + '-' + d;
        },

        /**
         * 获得当前日期+时间
         * @returns {string}
         */
        datetime: function() {
            var dt = new Date(),
                y = dt.getFullYear(),
                m = parseInt(dt.getMonth()) + 1 > 9 ? parseInt(dt.getMonth()) + 1 : '0' + (parseInt(dt.getMonth()) + 1),
                d = dt.getDate() > 9 ? dt.getDate() : '0' + dt.getDate(),
                h = dt.getHours() > 9 ? dt.getHours() : '0' + dt.getHours(),
                i = dt.getMinutes() > 9 ? dt.getMinutes() : '0' + dt.getMinutes(),
                s = dt.getSeconds() > 9 ? dt.getSeconds() : '0' + dt.getSeconds();

            return y + '-' + m + '-' + d + ' ' + h + ':' + i + ':' + s;
        },

        /**
         * 获取当前时间
         * @returns {string}
         */
        time: function() {
            var dt = new Date(),
                h = dt.getHours() > 9 ? dt.getHours() : '0' + dt.getHours(),
                i = dt.getMinutes() > 9 ? dt.getMinutes() : '0' + dt.getMinutes(),
                s = dt.getSeconds() > 9 ? dt.getSeconds() : '0' + dt.getSeconds();

            return h + ':' + i + ':' + s;
        },

        /**
         * 复制对象
         * @param obj
         * @returns {*}
         */
        clone: function( obj ) {

            var buf;
            if (obj instanceof Array) {
                buf = [];
                var i = obj.length;
                while (i--) {
                    buf[i] = E.clone(obj[i]);
                }
                return buf;
            } else if (obj instanceof Object) {
                buf = {};
                for ( var k in obj ) {
                    buf[k] = E.clone(obj[k]);
                }
                return buf;
            } else {
                return obj;
            }

        }

    };

    W.E = W.ebsig = E;

})();

// 图片延迟加载
(function(){
    var timer  = null;
    var height = (window.innerHeight||document.documentElement.clientHeight) + 40;
    var images = [];
    function detect(){
        var scrollTop = (window.pageYOffset||document.documentElement.scrollTop) - 20;
        for( var i=0,l=images.length; i<l; i++ ){
            var img = images[i];
            var offsetTop = img.el.offsetTop;
            if( !img.show && scrollTop < offsetTop+img.height && scrollTop+height > offsetTop ){
                img.el.setAttribute('src', img.src);
                img.show = true;
            }
        }
    }
    function onScroll(){
        clearTimeout(timer);
        timer = setTimeout(detect, 100);
    }
    function onLoad(){
        var imageEls = document.getElementsByTagName('img');
        for( var i=0,l=imageEls.length; i<l; i++ ){
            var img = imageEls.item(i);
            if(!img.getAttribute('data-src') ) continue;
            images.push({
                el     : img,
                src    : img.getAttribute('data-src'),
                height : img.offsetHeight,
                show   : false
            });
        }
        detect();
    }

    if( window.addEventListener ){
        window.addEventListener('scroll', onScroll, false);
        window.addEventListener('load', onLoad, false);
        document.addEventListener('touchmove', onScroll, false);
    }
    else {
        window.attachEvent('onscroll', onScroll);
        window.attachEvent('onload', onLoad);
    }

})();

function count_down(et, g, f) {

    var timer = window.setInterval(function() {
        var nt = new Date().getTime();
        var l = et - nt;
        if (l <= 0) {
            f();
            clearInterval(timer);
            return false;
        }
        var d = Math.floor(l / 86400000);
        var h = Math.floor((l - d * 86400000) / 3600000);
        var m = Math.floor((l - d * 86400000 - h * 3600000) / 60000);
        var s = Math.ceil((l - d * 86400000 - h * 3600000 - m * 60000) / 1000);

        if (h < 10) {
            h = '0' + h;
        }
        if (m < 10) {
            m = '0' + m;
        }
        if (s < 10) {
            s = '0' + s;
        }
        g(d, h, m, s);
    }, 1000);

}

/**
 * 获取地址位置
 * @param success
 */
function get_location(success) {
    this.success_func = success;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(get_location.success, get_location.error, {
            // 指示浏览器获取高精度的位置，默认为false
            enableHighAcuracy: true,
            // 指定获取地理位置的超时时间，默认不限时，单位为毫秒
            timeout: 5000,
            // 最长有效期，在重复获取地理位置时，此参数指定多久再次获取位置。
            maximumAge: 3000
        });
    } else {
        alert('获取地址位置失败');
    }
}
get_location.success = function(position) {
    this.success_func(position.coords);
};
get_location.error = function(error) {
    alert('获取地址位置失败');
};