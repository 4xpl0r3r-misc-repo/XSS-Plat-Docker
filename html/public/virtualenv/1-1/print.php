<?php
header("X-XSS-Protection:0");
echo('您的用户名是:'.$_POST['username']);