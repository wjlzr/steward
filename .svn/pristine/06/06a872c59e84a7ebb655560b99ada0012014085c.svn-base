/**
 * 调取pc客户端通用配置
 */
function client_common_config_get() {
    var return_data =  window.external.common_config_get();
    E.setCookie('client_common_config' , return_data , 300 );
    return $.parseJSON(return_data);
}

/**
 * 调取pc客户端打印机设置
 */
function client_print_config_get(){
    var return_data =  window.external.print_config_get();
    E.setCookie( 'client_print_config' , return_data , 300 );
    return $.parseJSON(return_data);
}

/**
 * 设置通用配置信息
 * @param data  更新后的所有客户端配置
 */
function client_common_config_set(data){
    var data = stringifyJson(data);
    window.external.common_config_set(data);
}

/**
 * 设置打印机连接
 * @param name
 * @param connect
 * @param paper
 */
function client_print_config_set (name, connect ,paper){
    window.external.print_config_set(name, connect ,paper);
}

/**
 * 打印机测试
 * @param name
 */
function client_print_test( name){

    window.external.print_test(name);
}

/**
 * 试听提示音
 * @param type
 */
function client_notice_audition(type){
    window.external.notice_audition(type);
}

/**
 * 轮循客户端订单提醒数量
 * @param new_number
 * @param remind_number
 * @param refund_number
 */
function client_notice_func(new_number, remind_number ,refund_number){
    window.external.notice_func(new_number, remind_number ,refund_number);
}

/**
 * 客户端批量自动打印
 * @param data
 *
 */
function client_print_auto_func(data){
    window.external.print_auto_func(stringifyJson(data));
}

var gap;
var indent;
var meta;
var rep;

function stringifyJson(value, replacer, space) {

    var i;
    gap = "";
    indent = "";

    if (typeof space === "number") {
        for (i = 0; i < space; i += 1) {
            indent += " ";
        }
    } else if (typeof space === "string") {
        indent = space;
    }
    rep = replacer;
    if (replacer && typeof replacer !== "function" && (typeof replacer !== "object" || typeof replacer.length !== "number")) {
        throw new Error("JSON.stringify");
    }

    return str("", {"": value});
}

function quote(string) {

    var rx_escapable = /[\\"\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
    rx_escapable.lastIndex = 0;
    return rx_escapable.test(string) ? "\"" + string.replace(rx_escapable, function (a) {
        var c = meta[a];
        return typeof c === "string" ? c : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4);
    }) + "\"" : "\"" + string + "\"";

}

function str(key, holder) {

    var i;          // The loop counter.
    var k;          // The member key.
    var v;          // The member value.
    var length;
    var mind = gap;
    var partial;
    var value = holder[key];

    if (value && typeof value === "object" && typeof value.toJSON === "function") {
        value = value.toJSON(key);
    }

    if (typeof rep === "function") {
        value = rep.call(holder, key, value);
    }

    switch (typeof value) {
        case "string":
            return quote(value);
        case "number":
            return isFinite(value) ? String(value) : "null";
        case "boolean":
        case "null":
            return String(value);
        case "object":

            if (!value) {
                return "null";
            }

            gap += indent;
            partial = [];

            if (Object.prototype.toString.apply(value) === "[object Array]") {
                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || "null";
                }
                v = partial.length === 0 ? "[]" : gap ? "[\n" + gap + partial.join(",\n" + gap) + "\n" + mind + "]" : "[" + partial.join(",") + "]";
                gap = mind;
                return v;
            }

            if (rep && typeof rep === "object") {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === "string") {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ": " : ":") + v);
                        }
                    }
                }
            } else {
                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ": " : ":") + v);
                        }
                    }
                }
            }

            v = partial.length === 0
                ? "{}"
                : gap
                ? "{\n" + gap + partial.join(",\n" + gap) + "\n" + mind + "}"
                : "{" + partial.join(",") + "}";
            gap = mind;
            return v;
    }
}

