(function ($, layer) {

    'use strict';

    window.E = {

        layerIndex: 0,

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
         * 从字符串的两端删除空白字符和其他预定义字符
         * @param s
         * @returns {*}
         */
        trim: function(s) {
            return s.replace(/(^\s*)|(\s*$)/g, '');
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
                ele.name = this.trim(ele.name);
                if (ele.value == undefined || ele.value == 'undefined') {
                    continue;
                }
                ele.value = this.trim(ele.value);
                if (ele.name == "" || ele.name == undefined || ele.name == "undefined")
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

        ajax: function (args) {
            if (!args.data) {
                args.data = [];
            }

            this.lastIndex = layer.load();

            $.ajax({
                type: args.type,
                url: args.url,
                dataType: 'JSON',
                data: args.data,
                timeout: 180000,
                success: function( o ) {
                    layer.close(E.lastIndex);
                    args.success( o );
                },
                error: function( XMLHttpRequest, textStatus, errorThrown ) {

                    layer.close(E.lastIndex);

                    switch (textStatus) {
                        case 'timeout':
                            layer.alert('网络爆棚，请重新尝试', {icon: 2, offset: '70px'});
                            break;

                        default:
                            layer.alert('系统出错，请重试', {icon: 2, offset: '70px'});
                            break;
                    }

                }
            });

        },

        layerClose: function () {
            var index = parent.layer.getFrameIndex(window.name); // 先得到当前iframe层的索引
            parent.layer.close(index);   //  再执行关闭
        }

    };


    $(document).on('change', '#search-form select', function () {
        $('#table').bootstrapTable('refresh',{url:bootstrap_table_ajax_url} );
    }).on('click', '#search-form button[name="search"]', function () {
        $('#table').bootstrapTable('refresh',{url:bootstrap_table_ajax_url});
    }).on('click', '#search-form button[name="to-reset"]', function () {
        $('#search-form')[0].reset();
        $('.reset-bootstrap-select').val('').selectpicker('refresh');
        $('#table').bootstrapTable('refresh',{url:bootstrap_table_ajax_url});
    }).on('click', '.layer-go-back', function () { //layer的iframe层关闭
        E.layerClose();
    });



})(jQuery, layer);
