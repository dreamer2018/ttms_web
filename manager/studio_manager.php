<?php
require_once "conf/conf.php";
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
            <ul class="navbar-list clearfix">
                <li style="font-size:20px; font-weight:bold;">光影人生</li>
                <li style="font-size: 16px;font-style: italic">-影院票务管理系统</li>
            </ul>
        </div>
        <div class="top-info-wrap">
            <ul class="top-info-list clearfix">
                <li><a href="logout.php"><i class="icon-font">&#xe059;</i></a></li>
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
                        <li><a href="studio_manager.php"><i class="icon-font">&#xe044;</i>影厅管理</a></li>
                        <li><a href="movie_manager.php"><i class="icon-font">&#xe034;</i>影片管理</a></li>
                        <li><a href="seat_manager.php"><i class="icon-font">&#xe063;</i>座位管理</a></li>
                        <li><a href="schedule_manager.php"><i class="icon-font">&#xe014;</i>演出计划管理</a></li>
                        <li><a href="managerStatistic.php"><i class="icon-font">&#xe065;</i>财务管理</a></li>
                        <li><a href="movieStatistics.php"><i class="icon-font">&#xe042;</i>票房统计</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="icon-font">&#xe018;</i>系统管理</a>
                    <ul class="sub-menu">
                        <li><a href="employee_manager.php"><i class="icon-font">&#xe017;</i>人事管理</a></li>
                        <li><a href="change_passwd.php"><i class="icon-font">&#xe017;</i>密码重置</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- 菜单栏结束-->

    
    <div class="main-wrap">

        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="index.php">首页</a><span
                    class="crumb-step">&gt;</span><span class="crumb-name">演出厅管理</span></div>
        </div>
        <div class="result-wrap">

            <div class="result-title">
                <div class="result-list">
                    <a href="add_studio.php"><i class="icon-font"></i>添加</a>
                </div>
            </div>
            <div class="result-content" id="fid">
                <table class="result-tab" width="100%" id="tableid" cellpadding="0" cellspacing="0">
                    <tr>
                        <th>演出厅ID</th>
                        <th>演出厅名</th>
                        <th>行数</th>
                        <th>列数</th>
                        <th>操作</th>
                        <?php
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
                        $count = 0;
                        $select = $connect->select_db($DB_NAME);
                        if (isset($_POST['studio_id'])) {
                            $query = "delete from schedule where play_id = " . $_POST['play_id'] . ";";
                            $connect->query($query);
                            $query = "delete from studio where id = " . $_POST['studio_id'] . ";";
                            $connect->query($query);
                            $query = "delete from seat where studio_id =".$_POST['studio_id'].";";
                            $connect->query($query);
                        }

                        $query = "select theater_id from manager where emp_no =\"" . $_SESSION['username'] . "\";";
                        $result = $connect->query($query);
                        $row = $result->fetch_array();
                        $query = "select id,name,row,col from studio where theater_id =" . $row['theater_id'] . ";";
                        $result2 = $connect->query($query);
                        while ($row2 = $result2->fetch_array()) {

                            echo "<tr>";
                            echo "<td>" . $row2['id'] . "</td>";
                            echo "<td>" . $row2['name'] . "</td>";
                            echo "<td>" . $row2['row'] . "</td>";
                            echo "<td>" . $row2['col'] . "</td>";
                            echo "<td>";
                            echo "<form name=\"myform\" method=\"post\" action=\"studio_manager.php\">";
                            echo "<input type = 'hidden' value ='" . $row2['id'] . "'  name = 'studio_id'>";
                            echo "<input  type='submit'  class=\"btn btn-primary btn2\" value='删除' >";
                            echo "</form>";
                            echo "</td>";
                            echo "<tr>";
                            $count++;
                        }
                        ?>
                </table>
                <div class="list-page" style="margin-left: 85%"> 共<?php echo $count ?>条</div>
            </div>

        </div>
    </div>
</div>
</body>
</html>