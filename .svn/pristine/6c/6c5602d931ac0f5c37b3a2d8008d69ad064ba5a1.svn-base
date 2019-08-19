var com_export = {


    /**
     * 公共大数据导出
     * @param form_id   条件表单
     * @param route     请求文件 （ 文件名/方法名 ）
     * @param exportIndex     导出索引
     * @example  window.Global.download( 'search-form' , 'goods/store' , 1000 )
     */
    download:function( form_id, route, exportIndex ){

        if ( E.isEmpty( route ) ) {
            layer.alert('缺少路由参数' , {icon:2,offset:'50px'});
            return false;
        }

        if ( E.isEmpty( exportIndex ) || !E.isInt( exportIndex ) ) {
            layer.alert('缺少导出索引参数' , {icon:2,offset:'50px'});
            return false;
        }

        var dt = {};
        if ( !E.isEmpty( form_id ) ) {
            dt = E.getFormValues(form_id);
        }

        dt.export_index = exportIndex;

        var load = layer.load();

        E.ajax({
            type:'post',
            url: route ,
            data: dt,
            success:function( obj ) {
                if ( obj.code == 200 ) {
                    com_export.openDownLoadPage(exportIndex);
                } else {
                    layer.close( load );
                    layer.alert( obj.message , {icon:2, offset:'50px'});
                }
            }
        })

    },


    //打开下载页面
    openDownLoadPage:function( exportIndex ){

        if ( E.isEmpty( exportIndex ) ) {
            layer.alert('缺少导出索引参数',{icon:2,offset:'50px'})
        } else {
            layer.open({
                title: '下载页' ,
                type: 2,
                scrollbar: false,
                area: [ '1050px', '500px' ],
                content:'/plugin/export/' + exportIndex
            });
        }

    }

};