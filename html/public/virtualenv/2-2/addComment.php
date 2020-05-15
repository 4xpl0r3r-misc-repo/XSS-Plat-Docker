<?php
header("X-XSS-Protection:0");
$conn = new mysqli('127.0.0.1', 'root_sql', '5431', 'xss-virtualenv');
// Check connection
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
$result = $conn->query("INSERT INTO `2_2_".$_COOKIE['username']."` (`comment`) VALUES ('".$_POST['comment']."');");
echo(htmlspecialchars("INSERT INTO `2_2_".$_COOKIE['username']."` (`comment`) VALUES ('".$_POST['comment']."');"));
?>
<h3>评论添加完成</h3>
<a href="comment.php">查看所有评论</a>