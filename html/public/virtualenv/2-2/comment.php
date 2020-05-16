<h4>以下为一些评论</h4>
<ul>
<?php
header("X-XSS-Protection:0");
$conn = new mysqli('127.0.0.1', 'root_sql', '<mysql_password>', 'xss-platform');
// Check connection
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
$result = $conn->query("SELECT comment FROM 2_2_".$_COOKIE['username']);
if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        echo "<li>" . $row["comment"]. "</li>";
    }
} else {
    echo "<li>0 结果</li>";
}
$conn->close();
?>
</ul>
<form action="addComment.php" method="POST">
    <label>添加评论:</label>
    <input type="text" name="comment"/>
    <input type="submit"/>
</form>