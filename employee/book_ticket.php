<?php
session_start();
if (!isset($_SESSION["username"]) || !isset($_SESSION["identity"])) {
    die("<h1>非法访问</h1>");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>光影人生-影院票务管理系统</title>
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <script type="text/javascript" src="js/houtai.js"></script>
</head>
<body>

<!--网页头部-->
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
                <li><a href="#"><i class="icon-font">&#xe014;</i></a></li>
                <li><a href="#"><i class="icon-font">&#xe059;</i></a></li>
            </ul>
        </div>
    </div>
</div>
<!--头部结束-->


<div class="container clearfix">

    <!--网页菜单栏-->
    <div class="sidebar-wrap">
        <div class="sidebar-title">
            <h1>菜单</h1>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-list">
                <li>
                    <a href="#"><i class="icon-font">&#xe003;</i>常用操作</a>
                    <ul class="sub-menu">
                        <li><a href="booking.html"><i class="icon-font">&#xe044;</i>售票</a></li>
                        <li><a href="return.html"><i class="icon-font">&#xe034;</i>退票</a></li>
                        <li><a href="php/select_action.php"><i class="icon-font">&#xe063;</i>影片查询</a></li>
                        <li><a href="schedule_select.php"><i class="icon-font">&#xe014;</i>演出计划查询</a></li>
                        <li><a href="employeeStatistic.html"><i class="icon-font">&#xe065;</i>统计</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="icon-font">&#xe018;</i>系统管理</a>
                    <ul class="sub-menu">
                        <li><a href="change_passwd.html"><i class="icon-font">&#xe017;</i>更改密码</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!--菜单栏结束-->

    <!-- 演出计划选择开始 -->
    <div class="main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list">
                <i class="icon-font"></i>
                <a href="index.html">首页</a>
                <span class="crumb-step">&gt;</span>
                <span class="crumb-name">订票</span>
            </div>
        </div>
        <div class="search-wrap">
            <div class="search-content">
                <form action="book_ticket.php" method="post">
                    <table class="search-tab">
                        <tr>
                            <th width="120">影片名称:</th>
                            <td>
                                <select name="movie_name">
                                    <option value="0">全部</option>
                                    <?php
                                    require_once "../conf/DB_login.php";
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
                                    $query = "select id,name from play;";
                                    $result = $connect->query($query);
                                    while ($row = $result->fetch_array()) {
                                        echo "<option value=" . $row["id"] . ">" . $row["name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <th width="120">日期:</th>
                            <td>
                                <select name="date">
                                    <option value="-1">全部</option>
                                    <?php
                                    require_once "../conf/DB_login.php";
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

                                    $query = "select distinct time from schedule;";

                                    $result = $connect->query($query);
                                    while ($row = $result->fetch_array()) {
                                        echo "<option value=" . $row["time"] . ">" . substr($row["time"], 0, 4) . "." . substr($row["time"], 4, 2) . "." . substr($row["time"], 6, 2) . "&nbsp;" . substr($row["time"], 8, 2) . ":" . substr($row["time"], 10, 2) . "</option>";
                                    }
                                    ?>
                                </select>
                            <th width="120"></th>
                            <td>
                                <input class="btn btn-primary btn2" name="sub" value="查询" type="submit">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="result-wrap">
            <form name="myform" id="myform" method="post">
                <div class="result-content" id="fid">
                    <table class="result-tab" width="100%" id="tableid" cellpadding="0" cellspacing="0">
                        <tr>
                            <th class="tc">演出计划ID</th>
                            <th>演出厅id</th>
                            <th>剧目</th>
                            <th>放映时间</th>
                            <th>折扣</th>
                            <th>票价</th>
                        </tr>
                        <?php

                        if (isset($_POST["movie_name"])) {
                            $movie_name = $_POST['movie_name'];
                            $date = $_POST['date'];

                            require_once "../conf/DB_login.php";
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
                            if ($movie_name == 0 && $date == -1) {
                                $query = "select id,studio_id,play_id,time,discount,price from schedule where status = 1 ;";
                            } elseif ($movie_name == 0 && $date != -1) {
                                $query = "select id,studio_id,play_id,time,discount,price from schedule where status = 1 and  time=\"" . $date . "\";";
                            } elseif ($movie_name != 0 && $date == -1) {
                                $query = "select id,studio_id,play_id,time,discount,price from schedule where status = 1 and play_id =" . $movie_name . ";";
                            } else {
                                $query = "select id,studio_id,play_id,time,discount,price from schedule where status = 1 and play_id =" . $movie_name . " and time=\"" . $date . "\";";
                            }
                            //echo $query;

                            $result = $connect->query($query);
                            $count = 0;
                            while ($row = $result->fetch_array()) {

                                $query = "select name from play where id = " . $row['id'] . ";";
                                $result2 = $connect->query($query);
                                $row2 = $result2->fetch_array();
                                $movie_name = $row2['name'];
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td >";
                                echo "<td>" . $row['studio_id'] . "</td >";
                                echo "<td>" . $movie_name . "</td >";
                                echo "<td>" . substr($row["time"], 0, 4) . "." . substr($row["time"], 4, 2) . "." . substr($row["time"], 6, 2) . "&nbsp;" . substr($row["time"], 8, 2) . ":" . substr($row["time"], 10, 2) . "</td >";
                                echo "<td>" . $row['discount'] . "</td >";
                                echo "<td>" . $row['price'] . "</td >";
                                echo "</td>";
                                $count++;
                            }
                        }
                        ?>
                    </table>
                    <div class="list-page" style="margin-left: 85%">共<?php echo $count ?>条</div>
                </div>
            </form>
        </div>
    </div>

    <!-- 演出计划选择结束 -->

    <!--订票选择开始 -->
    <div class="main-wrap">
        <div class="search-wrap">
            <div class="search-content">
                <form action="book_ticket.php" method="post">
                    <table class="search-tab">
                        <tr>
                            <th width="120">演出计划ID:</th>
                            <td>
                                <select name="schedule_id">
                                    <?php
                                    require_once "../conf/DB_login.php";
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
                                    $query = "select id from schedule ;";
                                    $result = $connect->query($query);
                                    while ($row = $result->fetch_array()) {
                                        echo "<option value=" . $row["id"] . ">" . $row["id"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <th width="120">行:</th>
                            <td>
                                <input type="text" name="row">
                            </td>
                            <th width="120">列:</th>
                            <td>
                                <input type="text" name="col">
                            </td>
                            <th width="120"></th>
                            <td>
                                <input class="btn btn-primary btn2" name="sub" value="订购" type="submit">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="result-wrap">

            <!-- 订票信息 -->
            <form name="myform" id="myform" method="post">
                <?php
                if (isset($_POST["schedule_id"])) {


                    $schedule_id = $_POST['schedule_id'];
                    $seat_row = $_POST['row'];
                    $seat_col = $_POST['col'];
                    if (strlen($seat_col) && strlen($seat_row)) {

                        echo "<div class=\"result-content\" id=\"fid\">";
                        echo "<table class= \"result-tab \"width=\"100%\" id=\"tableid\" cellpadding=\"0\" cellspacing=\"0\">";
                        echo "<tr>";
                        echo "<th class=\"tc\">演出计划ID</th>";
                        echo "<th>演出厅ID</th>";
                        echo "<th>剧目</th>";
                        echo "<th>放映时间</th>";
                        echo "<th>折扣</th>";
                        echo "<th>票价</th>";
                        echo "</tr>";
                        require_once "../conf/DB_login.php";
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

                        /*
                         * 先通过schedule_id获取到一系列seat_id
                         */
                        $query = "select id,seat_id,play_id from ticket where schedule_id =" . $schedule_id . ";";

                        $result = $connect->query($query);

                        while ($row = $result->fetch_array()) {

                            $seat_id = $row['seat_id'];
                            $ticket_id = $row['ticket_id'];

                            $query = "select id,row,col,status from seat where id = " . $seat_id . ";";

                            $result2 = $connect->query($query);

                            while ($row2 = $result2->fetch_array()) {

                                $seat_status = $row2['status'];
                                if ($row2['status'] == 1 && $row2['row'] == $seat_row && $row2['col'] == $seat_col) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td >";
                                    echo "<td>" . $row['studio_id'] . "</td >";
                                    echo "<td>" . $row['play_id'] . "</td >";
                                    echo "<td>" . substr($row["time"], 0, 4) . "." . substr($row["time"], 4, 2) . "." . substr($row["time"], 6, 2) . "&nbsp;" . substr($row["time"], 8, 2) . ":" . substr($row["time"], 10, 2) . "</td >";
                                    echo "<td>" . $row['discount'] . "</td >";
                                    echo "<td>" . $row['price'] . "</td >";
                                    echo "</tr>";
                                }
                            }

                            /*
                            $count = 0;
                            while ($row = $result->fetch_array()) {

                                $query = "select name from play where id = " . $row['id'] . ";";
                                $result2 = $connect->query($query);
                                $row2 = $result2->fetch_array();
                                $movie_name = $row2['name'];
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td >";
                                echo "<td>" . $row['studio_id'] . "</td >";
                                echo "<td>" . $movie_name . "</td >";
                                echo "<td>" . substr($row["time"], 0, 4) . "." . substr($row["time"], 4, 2) . "." . substr($row["time"], 6, 2) . "&nbsp;" . substr($row["time"], 8, 2) . ":" . substr($row["time"], 10, 2) . "</td >";
                                echo "<td>" . $row['discount'] . "</td >";
                                echo "<td>" . $row['price'] . "</td >";
                                echo "</tr>";
                                $count++;
                            }*/
                        }
                        echo "</table>";
                        echo " <div class=\"list-page\" style=\"margin-left: 85%\">共" . $count . "条</div>";
                    } else {
                        echo "<p>位置不能为空</p>";
                    }
                }
                ?>
            </form>
            <!-- 订票信息 -->
        </div>
    </div>
    <!--订票选择结束 -->
</div>
</body>
</html>