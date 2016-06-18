<?php
require_once "../conf/conf.php"
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>光影人生-影院票务管理系统</title>
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <script type="text/javascript" src="js/houtai.js"></script>
</head>
<body>

<!-- 网页头部 -->
<div class="topbar-wrap white">
    <div class="topbar-inner clearfix">
        <div class="topbar-logo-wrap clearfix">
            <h1 class="topbar-logo none"><a href="index.html" class="navbar-brand">后台管理</a></h1>
            <ul class="navbar-list clearfix">
                <li style="font-size:20px; font-weight:bold;">光影人生</li>
                <li style="font-size: 16px;font-style: italic">-影院票务管理系统</li>
            </ul>
        </div>
        <div class="top-info-wrap">
            <ul class="top-info-list clearfix">
                <li><a href="#"><i class="icon-font">&#xe014;</i></a></li>
                <li><a href="#"><i class="icon-font">&#xe059;</i></a></li>
            </ul>
        </div>
    </div>
</div>
<!-- 头部结束-->

<div class="container clearfix">

    <!-- 网页菜单栏-->
    <div class="sidebar-wrap">
        <div class="sidebar-title">
            <h1>菜单</h1>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-list">
                <li>
                    <a href="#"><i class="icon-font">&#xe003;</i>常用操作</a>
                    <ul class="sub-menu">
                        <li><a href="design.html"><i class="icon-font">&#xe044;</i>影厅管理</a></li>
                        <li><a href="design.html"><i class="icon-font">&#xe034;</i>影片管理</a></li>
                        <li><a href="design.html"><i class="icon-font">&#xe063;</i>座位管理</a></li>
                        <li><a href="design.html"><i class="icon-font">&#xe014;</i>演出计划管理</a></li>
                        <li><a href="design.html"><i class="icon-font">&#xe065;</i>财务管理</a></li>
                        <li><a href="design.html"><i class="icon-font">&#xe042;</i>票房统计</a></li>
                        <li><a href="managerStatistic.html"><i class="icon-font">&#xe033;</i>查询</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="icon-font">&#xe018;</i>系统管理</a>
                    <ul class="sub-menu">
                        <li><a href="system.html"><i class="icon-font">&#xe017;</i>人事管理</a></li>
                        <li><a href="system.html"><i class="icon-font">&#xe017;</i>密码重置</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- 菜单栏结束-->

    <div class="main-wrap">

        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="/jscss/admin/design/">首页</a><span
                    class="crumb-step">&gt;</span><a class="crumb-name" href="studio_manager.php">影厅管理</a><span
                    class="crumb-step">&gt;</span><span>添加影厅</span></div>
        </div>
        <div class="result-wrap">
            <div class="result-content">
                <form action="add_studio.php" method="post" id="myform" name="myform">
                    <table class="insert-tab" width="100%" id="fid" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <th><i class="require-red">*</i>演出厅名：</th>
                            <td>
                                <input class="common-text required" id="title" name="studio_name" size="20" value=""
                                       type="text">
                            </td>
                        </tr>
                        <tr>
                            <th><i class="require-red">*</i>行数：</th>
                            <td>
                                <input class="common-text required" id="title" name="row_num" size="20" value=""
                                       type="text">
                            </td>
                        </tr>
                        <tr>
                            <th><i class="require-red">*</i>列数：</th>
                            <td>
                                <input class="common-text required" id="time" name="col_num" size="20" value=""
                                       type="text">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <br/>
                    <tr>
                        <th></th>
                        <td>
                            <input id="subid" class="btn btn-primary btn6 mr10" value="提交" type="submit"
                                   style="margin-left: 29%">
                            <input class="btn btn6" onclick="self.location='studio_manager.php'" value="返回"
                                   type="button">
                        </td>
                    </tr>
                </form>
            </div>
        </div>
        <div class="result-wrap">
            <div class="result-content" id="fid">
                <?php
                if (isset($_POST['studio_name'])) {

                    $studio_name = $_POST['studio_name'];
                    $row_num = $_POST['row_num'];
                    $col_num = $_POST['col_num'];


                    $sign = 1;  //信息正确性标志

                    /*
                     * 对输入信息进行验证
                     */

                    if (strlen($studio_name) > 40 || strlen($studio_name) <=0 ) {
                        echo "<p>演出厅名过长或为0</p><br/>";
                        $sign = 0;
                    }

                    if ($row_num <= 0) {
                        echo "<p>行数输入错误！</p><br/>";
                        $sign = 0;
                    }

                    if ($col_num <= 0) {
                        echo "<p>列数输入错误！</p><br/>";
                        $sign = 0;
                    }

                    if ($sign) {

                        /*
                        * 连接数据库
                        */
                        $connect = new mysqli($DB_HOST, $DB_USER, $DB_PASSWD);
                        /*
                        * 如果连接失败，则直接结束
                        */
                        if (!$connect) {
                            die("Connect DataBase Error!<br/>");
                        }

                        /*
                        * 选择数据库
                        */

                        $select = $connect->select_db($DB_NAME);
                        $query = "select theater_id from manager where emp_no = \"" . $_SESSION['username'] . "\";";
                        $result1 = $connect->query($query);
                        $row1 = $result1->fetch_array();

                        $query = "insert into studio(theater_id,name,row,col) values (".$row1['theater_id'].",\"".$studio_name."\",".$row_num.",".$col_num.")";

                        $result2 = $connect->query($query);
                        $row2 = $result2->fetch_array();

                        if ($row2['count(emp_no)'] == 0) {

                            $query = "insert into employee (emp_no,theater_id,name,passwd,tel) VALUES (\"" . $emp_no . "\"," . $row1['theater_id'] . ",\"" . $name . "\",\"" . $passwd . "\"," . $tel . ");";
                            $result3 = $connect->query($query);
                            if ($result3) {
                                echo "<table class=\"result-tab\" width=\"100%\" id=\"tableid\" cellpadding=\"0\" cellspacing=\"0\">";
                                echo "<tr>";
                                echo "<th>工号</th>";
                                echo "<th>影院号</th>";
                                echo "<th>姓名</th>";
                                echo "<th>密码</th>";
                                echo "<th>电话号</th>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td>" . $emp_no . "</td>";
                                echo "<td>" . $row['theater_id'] . "</td>";
                                echo "<td>" . $name . "</td>";
                                echo "<td>" . $passwd . "</td>";
                                echo "<td>" . $tel . "</td>";
                                echo "</tr>";
                                echo "</table>";
                            }
                        } else {
                            echo "<p>工号已存在！</p><br/>";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <!--/main-->
</div>
</body>
</html>