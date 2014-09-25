<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
    <title></title>
</head>
<body>
    <div style="text-align:center;">
        <p>优倍快（UBNT）</p>
        <img src="hardware.jpg">
    </div>
    <script type="text/javascript">
        <?php
            include_once ('../config.php');
            $url = DEFAULT_URL . '?v=1&' . $_SERVER['QUERY_STRING'];
            echo "var url = '" . $url . "';";
        ?>
        var random = +new Date();
        window.location.href = url + "&" + random;
    </script>
</body>
</html>