@extends('develop.layoutList')

@section('title')
    <li class="cur">
        <span>代码发布</span>
    </li>
@endsection

@section('btn')
    <div class="input-group">
        <select id="version-number" class="form-control">
            <option value="0">请选择</option>
        </select>
        <span class="input-group-btn">
            <button class="btn btn-success" id="release" type="button">发布</button>
            <button class="btn btn-default" id="refresh" type="button">刷新</button>
        </span>
    </div>
@endsection

@section('search')
    <input type="text" class="form-control" name="version_number" placeholder="请输入SVN版本号">
@endsection

@section('js')

    <script>

        var bootstrap_table_ajax_url  = '/ajax/release/search';
        bootstrap_table({
            sortName: 'release_id',
            sortOrder: 'DESC',
            columns: [
                { title: '操作', field: 'operation', align: 'left', width: '150px' },
                { title: '发布号', field: 'release_id', align: 'left', width: '150px' },
                { title: 'SVN版本号', field: 'version_number', align: 'left', width: '150px' },
                { title: '发布时间', field: 'release_time', align: 'left' },
                { title: '发布人', field: 'user_id', align: 'left', width: '150px' },
                { title: '发布状态', field: 'release_status', align: 'left', width: '150px' }
            ]
        });

        var Release = {

            layerIndex: 0,

            refresh: function () {
                E.ajax({
                    type: 'get',
                    url: '/ajax/release/revision/list',
                    success: function (res) {
                        if (res.code == 200) {
                            var html = '<option value="0">请选择</option>';
                            $.each(res.revision, function (k, v) {
                                html += '<option value="' + v + '">' + v + '</option>';
                            });
                            $('#version-number').html(html);
                        }
                    }
                });
            },

            releasedFiles: function (id) {

                E.ajax({
                    type: 'get',
                    url: '/ajax/released/files/' + id,
                    success: function (res) {
                        if (res.code == 200) {

                            var html = '<ul class="list-group" style="margin: 10px;">';
                            $.each(res.data, function (k, v) {
                                html += '<li class="list-group-item">' + v + '</li>';
                            });
                            html += '</ul>';

                            Release.layerIndex = layer.open({
                                type: 1,
                                titel: '已发布文件列表',
                                area: ['500px', '300px'], //宽高
                                offset: '50px',
                                content: html,
                                btn: ['关闭']
                            });

                        } else {
                            layer.alert(res.message, {icon: 2, offset: '70px'});
                        }
                    }
                });

            },

            files: function (id) {

                E.ajax({
                    type: 'get',
                    url: '/ajax/release/files/' + id,
                    data: {},
                    success: function (res) {
                        if (res.code == 200) {

                            var html = '<ul class="list-group" style="margin: 10px;">';
                            $.each(res.data, function (k, v) {
                                html += '<li class="list-group-item">' + v + '</li>';
                            });
                            html += '</ul>';

                            Release.layerIndex = layer.open({
                                type: 1,
                                titel: '发布文件列表',
                                area: ['500px', '300px'], //宽高
                                offset: '50px',
                                content: html,
                                btn: ['确定', '取消'],
                                yes: function () {
                                    Release.release(id);
                                }
                            });

                        } else {
                            layer.alert(res.message, {icon: 2, offset: '70px'});
                        }
                    }
                });

            },

            release: function (id) {

                E.ajax({
                    type: 'get',
                    url: '/ajax/release/do/' + id,
                    data: {},
                    success: function (res) {
                        if (res.code == 200) {
                            layer.alert('发布成功', {icon: 1, offset: '70px', time: 1500});
                            $('#version-number').val(0);
                            Release.refresh();
                            bootstrap_table_init();
                            layer.close(Release.layerIndex);
                        } else {
                            layer.alert(res.message, {icon: 2, offset: '70px'});
                        }
                    }
                });

            },

            del: function (id) {

                layer.confirm('确定删除该发布吗？', {icon: 3}, function (index) {
                    layer.close(index);
                    var load_index = layer.load();
                    E.ajax({
                        type: 'get',
                        url: '/ajax/release/delete/' + id,
                        success: function (res) {
                            layer.close(load_index);
                            if (res.code == 200) {
                                layer.alert('删除成功', {icon: 1, offset: '70px', time: 1500});
                                Release.refresh();
                                bootstrap_table_init();
                            } else {
                                layer.alert(res.message, {icon: 2, offset: '70px'});
                            }
                        }
                    });
                });

            }

        };

        $(function () {

            Release.refresh();

            //点击刷新svn
            $('#refresh').click(function () {
                Release.refresh();
            });

            //点击打开svn内容弹出层
            $('#release').click(function () {
                var id = $('#version-number').val();
                if (id == 0) {
                    return false;
                }
                Release.files(id);
            });

        });


    </script>

@endsection