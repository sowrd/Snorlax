<?php

$ROOT_DIR = '../';
include_once "{$ROOT_DIR}header.php";
include_once "{$ROOT_DIR}inc/config.inc.php";
include_once "{$ROOT_DIR}inc/mysql.inc.php";
include_once "{$ROOT_DIR}inc/function.inc.php";

$link = connect();
$html = "";

?>

<!-- 修改内容 -->
<div class="col-xl-8">
    <article class="lyear-arc">
        <div class="arc-header">
            <h2 class="arc-title"><a href="vul.php">Vulnerabilities Introduce</a></h2>
            <ul class="arc-meta">
                <li><i class="mdi mdi-calendar"></i> <?php echo date('Y-m-d G:i:s');?></li>
                <li><i class="mdi mdi-tag-text-outline"></i> <a href="#">漏洞, 靶场</a></li>
            </ul>
        </div>
        <div class="arc-synopsis">
            <div>
                <div style="float: left">
                    <?php
                    if(isset($_SESSION['vul']) && $_SESSION['vul'] = 'vulkey'){
                        $html=<<<str
<div style="float: left">
    <a href="?look=burtforce"><h6 style="color: black">Burt Force (暴力破解)</h6></a>
    <a href="?look=xss"><h6 style="color: black">Cross Site Script (跨站脚本)</h6></a>
    <a href="?look=sql"><h6 style="color: black">SQL Injection (SQL 注入)</h6></a>
    <a href="?look=rce"><h6 style="color: black">RCE (远程命令/代码执行)</h6></a>
    <a href="?look=fileinclude"><h6 style="color: black">File Include (文件包含)</h6></a>
    <a href="?look=fileupload"><h6 style="color: black">File Upload (文件上传)</h6></a>
</div>
str;
                    }else{
                        $html=<<<str
<h6 style="color: black">Please Enter Your Information</h6>
                    <form action="#" method="post">
                        <table>
                            <tr>
                                <th>
                                    <!-- default user: snorlax/snorlax -->
                                    <label for="username">账&nbsp;&nbsp;&nbsp;户：</label>
                                    <input type="text" name="username" placeholder="Username" id="username"/>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <label for="password">密&nbsp;&nbsp;&nbsp;码：</label>
                                    <input type="password" name="password" placeholder="Password" id="password"/>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <input type="submit" name="change" value="Login" />
                                </th>
                            </tr>
                        </table>
                    </form>
str;
                        if(isset($_POST['change'])){
                            $username = $_POST['username'];
                            $password = $_POST['password'];
                            $sql_login = "SELECT username,password FROM users WHERE username = ? and password = md5(?)";
                            $login = $link->prepare($sql_login);
                            $login->bind_param("ss",$username,$password);
                            $login->execute();
                            if($login->fetch()){
                                $_SESSION['vul'] = 'vulkey';
                                $html=<<<str
<div style="float: left">
    <a href="?look=burtforce"><h6 style="color: black">Burt Force (暴力破解)</h6></a>
    <a href="?look=xss"><h6 style="color: black">Cross Site Script (跨站脚本)</h6></a>
    <a href="?look=sql"><h6 style="color: black">SQL Injection (SQL 注入)</h6></a>
    <a href="?look=rce"><h6 style="color: black">RCE (远程命令/代码执行)</h6></a>
    <a href="?look=fileinclude"><h6 style="color: black">File Include (文件包含)</h6></a>
    <a href="?look=fileupload"><h6 style="color: black">File Upload (文件上传)</h6></a>
</div>
str;
                            } else {
                                $html .= "<br />登录失败~";
                            }
                        }
                    }
                    echo "<br />" . $html;

                    if(isset($_GET['look']) && !empty($_GET['look'])) {
                        $type = $_GET['look'];
                        $sql_query = "SELECT typename,path FROM vultype WHERE vulname = ?";
                        $line = $link->prepare($sql_query);
                        $line->bind_param('s', $type);
                        $line->execute();
                        $line->bind_result($typename,$path);
                        echo<<<str
<div style="float: left"><ul>
str;
                        while($line->fetch()){
                            echo<<<str
<li><a href="{$path}">{$typename}</a></li>
str;
                        }
                        echo "</ul></div>";
                    }
                    ?>
            </div>
        </div>
    </article>

</div>

<?php include_once "{$ROOT_DIR}footer.php" ?>

