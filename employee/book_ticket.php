<?php
require_once "conf/conf.php";
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
                <li><a href="logout.php"><i class="icon-font">&#xe059;</i></a></li>
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
                        <li><a href="book_ticket.php"><i class="icon-font">&#xe044;</i>售票</a></li>
                        <li><a href="return_ticket.php"><i class="icon-font">&#xe034;</i>退票</a></li>
                        <li><a href="select_action.php"><i class="icon-font">&#xe063;</i>影片查询</a></li>
                        <li><a href="schedule_select.php"><i class="icon-font">&#xe014;</i>演出计划查询</a></li>
                        <li><a href="employeeStatistic.php"><i class="icon-font">&#xe065;</i>统计</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="icon-font">&#xe018;</i>系统管理</a>
                    <ul class="sub-menu">
                        <li><a href="change_passwd.php"><i class="icon-font">&#xe017;</i>更改密码</a></li>
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
                <a href="index.php">首页</a>
                <span class="crumb-step">&gt;</span>
                <span class="crumb-name">售票</span>
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
                            <th class="tc">演出厅ID</th>
                            <th class="tc">剧目</th>
                            <th class="tc">放映时间</th>
                            <th class="tc">票价</th>
                            <th class="tc">折扣</th>
                            <th class="tc">折后价</th>
                        </tr>
                        <?php
                        $count = 0;
                        if (isset($_POST['movie_name'])) {
                            $movie_name = $_POST['movie_name'];
                            $date = $_POST['date'];

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
                            $query = "select theater_id from employee where emp_no = \"" . $_SESSION['username'] . "\";";
                            $res = $connect->query($query);
                            $r = $res->fetch_array();

                            if ($movie_name == 0 && $date == -1) {
                                $query = "select id,studio_id,play_id,time,discount,price from schedule where status = 1 and studio_id in (select id from studio where theater_id = " . $r['theater_id'] . " );";
                            } elseif ($movie_name == 0 && $date != -1) {
                                $query = "select id,studio_id,play_id,time,discount,price from schedule where status = 1 and  time=\"" . $date . "\" and studio_id in (select id from studio where theater_id = " . $r['theater_id'] . ");";
                            } elseif ($movie_name != 0 && $date == -1) {
                                $query = "select id,studio_id,play_id,time,discount,price from schedule where status = 1 and play_id =" . $movie_name . " and  studio_id in (select id from studio where theater_id = " . $r['theater_id'] . ");";
                            } else {
                                $query = "select id,studio_id,play_id,sale_time,discount,price from schedule where status = 1 and play_id =" . $movie_name . " and time=\"" . $date . "\" and studio_id in ( select id from studio where theater_id = " . $r['theater_id'] . " ) ;";
                            }
                            $result = $connect->query($query);

                            while ($row = $result->fetch_array()) {

                                $query = "select name from play where id = " . $row['play_id'] . ";";
                                $result2 = $connect->query($query);
                                $row2 = $result2->fetch_array();
                                $movie_name = $row2['name'];
                                $orig_price = $row['price'] / $row['discount'];
                                echo "<tr>";
                                echo "<td class=\"tc\">" . $row['id'] . "</td >";
                                echo "<td class=\"tc\">" . $row['studio_id'] . "</td >";
                                echo "<td class=\"tc\">" . $movie_name . "</td >";
                                echo "<td class=\"tc\">" . substr($row["time"], 0, 4) . "." . substr($row["time"], 4, 2) . "." . substr($row["time"], 6, 2) . "&nbsp;" . substr($row["time"], 8, 2) . ":" . substr($row["time"], 10, 2) . "</td >";
                                echo "<td class=\"tc\">" . $orig_price . "</td >";
                                echo "<td class=\"tc\">" . $row['discount'] . "</td >";
                                echo "<td class=\"tc\">" . $row['price'] . "</td >";
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

                                    $query = "select theater_id from employee where emp_no = \"" . $_SESSION['username'] . "\";";
                                    $res = $connect->query($query);
                                    $r = $res->fetch_array();
                                    $query = "select id from schedule  where studio_id in (select id from studio where theater_id = " . $r['theater_id'] . ");";
                                    $result = $connect->query($query);
                                    while ($row = $result->fetch_array()) {
                                        echo "<option value=" . $row["id"] . ">" . $row["id"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <th width="120">行:</th>
                            <td>
                                <input type="text" name="row" size="7">
                            </td>
                            <th width="120">列:</th>
                            <td>
                                <input type="text" name="col" size="7">
                            </td>
                            <th width="120">手机号:</th>
                            <td>
                                <input type="text" name="tel" size="20">
                            </td>
                            <th width="120"></th>
                            <td>
                                <input class="btn btn-primary btn2" name="sub" value="订购" type="submit"
                                       style="margin-right: 70px;">
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
                    $tel = $_POST['tel'];

                    if (strlen($seat_col) && strlen($seat_row)) {


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

                        $query = "select discount,price from schedule where id = " . $schedule_id . ";";
                        $result3 = $connect->query($query);
                        $dicount = 0;
                        $price = 0;
                        while ($row3 = $result3->fetch_array()) {
                            $dicount = $row3['discount'];
                            $price = $row3['price'];
                        }
                        /*
                         * 先通过schedule_id获取到一系列seat_id
                         */

                        $sign = 1; //座位存在标志
                        $query = "select id,seat_id,play_id,status from ticket where schedule_id = " . $schedule_id . ";";

                        $result = $connect->query($query);

                        while ($row = $result->fetch_array()) {

                            $seat_id = $row['seat_id'];
                            $ticket_id = $row['id'];
                            $query = "select id,row,col,status,studio_id from seat where id = " . $seat_id . ";";

                            $result2 = $connect->query($query);
                            while ($row2 = $result2->fetch_array()) {
                                $seat_status = $row2['status'];
                                if ($row2['row'] == $seat_row and $row2['col'] == $seat_col) {
                                    $sign = 0;

                                    if ($row2['status'] == 1) {
                                        if ($row['status']) {
                                            echo "此座位已被订购！";
                                        } else {
                                            $query = "select count(id),id from customer where tel =".$tel.";";
                                            $result4 = $connect->query($query);
                                            $row4 = $result4->fetch_array();
                                            if(!$row4['count(id)']){
                                                $query = "insert into customer(tel)  VALUES (" . $tel . ");";
                                                $connect->query($query);
                                                $customer_id = mysqli_insert_id($connect);
                                            }else{
                                                $customer_id = $row4['id'];
                                            }
                                            $query = "select id from employee where emp_no = \"".$_SESSION['username']."\";";
                                            $res=$connect->query($query);
                                            $r=$res->fetch_array();
                                            $emp_id = $r['id'];
                                            $time = date("YmdHis");
                                            $query = "insert into bill(customer_id,ticket_id,emp_id,play_id,price,sale_time) VALUE (" . $customer_id . "," . $row['id'] . "," . $emp_id . "," . $row['play_id'] . "," . $price . ",\"" . $time . "\");";
                                            $connect->query($query);
                                            $query = "update ticket set status = 2 where id = " . $row['id'] . ";";
                                            $connect->query($query);


                                            echo "<div class=\"result-content\" id=\"fid\">";
                                            echo "<table class= \"result-tab \"width=\"100%\" id=\"tableid\" cellpadding=\"0\" cellspacing=\"0\">";
                                            echo "<tr>";
                                            echo "<th class=\"tc\">票ID</th>";
                                            echo "<th class=\"tc\">影厅ID</th>";
                                            echo "<th class=\"tc\">剧目ID</th>";
                                            echo "<th class=\"tc\">票价</th>";
                                            echo "<th class=\"tc\">位置</th>";
                                            echo "<th class=\"tc\">折扣</th>";
                                            echo "<th class=\"tc\">折后价<br />";
                                            echo "<th class=\"tc\">时间</th>";
                                            echo "</tr>";
                                            $orig_price = $price / $dicount;
                                            echo "<tr>";
                                            echo "<td class=\"tc\">" . $row['id'] . "</td >";
                                            echo "<td class=\"tc\">" . $row2['studio_id'] . "</td >";
                                            echo "<td class=\"tc\">" . $row['play_id'] . "</td >";
                                            echo "<td class=\"tc\">" . $seat_row."行".$seat_col."列" . "</td >";
                                            echo "<td class=\"tc\">" . $orig_price . "</td >";
                                            echo "<td class=\"tc\">" . $dicount . "</td >";
                                            echo "<td class=\"tc\">" . $price . "</td >";
                                            echo "<td class=\"tc\">" . substr($time, 0, 4) . "." . substr($time, 4, 2) . "." . substr($time, 6, 2) . "&nbsp;" . substr($time, 8, 2) . ":" . substr($time, 10, 2) . ":" . substr($time, 12, 2) . "</td >";
                                            echo "</tr>";
                                            echo "</table>";
                                            break;
                                        }
                                    } else {
                                        echo "此座位不可用！";
                                    }
                                }
                            }

                        }
                        if ($sign) {
                            echo "座位不存在";
                        }
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