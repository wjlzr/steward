@extends('admin.layoutEdit')
@section('css')
    <link  rel="stylesheet" href="/css/admin/web/goods.css?v=2018011509">
@endsection

@section('title')
    <li class="cur bill-detail"><span>添加商品</span></li>
@endsection

@section('go-back-btn')
    <button class="btn btn-default layer-go-back" type="button" onclick="back()">返回</button>
@endsection

@section('content')
    <div class="main">

        <!--基本信息-->
        <div class="setting-box">
            <p class="setting-title">基本信息</p>
            <div class="setting-inner">
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <label class="control-label"><strong><span class="red">*&nbsp;</span></strong>商品名称：</label>
                    </div>
                    <div class="form-group col-sm-5">
                        <input type="text" class="form-control" placeholder="格式：伊利安慕希酸牛奶250克/瓶" value="">
                    </div>
                    <div class="col-sm-1 question">?</div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <label class="control-label"><strong><span class="red">*&nbsp;</span></strong>商品分类：</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <select class="form-control" name="" id="">
                            <option value="">请选择分类</option>
                            <option value="">分类1</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <label class="control-label"><strong><span class="red">*&nbsp;</span></strong>商品单位：</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type="text" class="form-control" placeholder="件" value="">
                    </div>
                </div>
            </div>
        </div>

        <!--商品规格-->
        <div class="setting-box">
            <p class="setting-title">商品规格</p>
            <div class="setting-inner">
                <div class="col-md-12">
                    <div class="col-sm-3" style="line-height: 20px;">
                        <label class="control-label"><strong><span class="red">*&nbsp;</span></strong>是否多规格：</label>
                    </div>
                    <div class="form-group col-sm-5">
                        <input type="radio" class="square-radio" value="0"><label class="control-label mr10">&nbsp;是</label>
                        <input type="radio" class="square-radio" value="1"><label class="control-label mr10">&nbsp;否</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <label class="control-label">商家编码：</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type="text" class="form-control" placeholder="请输入商家编码">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <label class="control-label">商品条码：</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type="text" class="form-control" placeholder="请输入商品条码" value="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <label class="control-label"><strong><span class="red">*&nbsp;</span></strong>销售价：</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type="text" class="form-control" placeholder="请输入销售价">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <label class="control-label">包装费：</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type="text" class="form-control" placeholder="" value="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <label class="control-label">重量：</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control">
                            <span class="input-group-addon">克</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--商品图片-->
        <div class="setting-box">
            <p class="setting-title">商品图片</p>
            <div class="setting-inner">
                <div class="col-md-12">
                    <div class="col-sm-3" style="line-height: 80px;">
                        <label class="control-label"><strong><span class="red">*&nbsp;</span></strong>商品图片：</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <ul class="img-list">
                            <li><img src="/images/admin/steward-error.png" alt=""></li>
                            <li><img src="/images/admin/steward-error.png" alt=""></li>
                        </ul>
                        <div class="add-img">
                            <a href="javascript:;" class="blue">
                                +<br>添加图片
                            </a>
                        </div>
                        <p class="tips">建议尺寸：不小于640×640px 像素，可以拖拽图片调整图片顺序，可点击图片放大</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <label class="control-label">商品描述：</label>
                    </div>
                    <div class="col-sm-7">
                        <textarea class="form-control" cols="30" rows="10"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!--底部按钮-->
        <div class="btn-box">
            <button class="btn btn-blue mr10">保存</button>
            <button class="btn btn-blue mr10">保存且上架</button>
            <button class="btn btn-default">取消</button>
        </div>
    </div>
@endsection

@section('js')
    <script>
        //icheck插件
        $('.square-radio').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    </script>
@endsection