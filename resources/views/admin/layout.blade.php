<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" >
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/css/admin/common.css?v=201709333334444">
    <title>商管云</title>
    @yield('css')
</head>

<body>

@yield('content')

<script src="/libs/jquery/jquery-1.9.1.min.js"></script>
<script src="/libs/layer-v3.0.3/layer.js"></script>
<script src="/js/admin/global.js?v=20170104000"></script>
<script>
    $(document).click(function(){
        $('.mall-list', window.parent.document).hide();
    })
</script>
@yield('js')

</body>
</html>