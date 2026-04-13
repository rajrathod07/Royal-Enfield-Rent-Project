<?php
session_start();
session_destroy();
header("Location: auth.php?logged_out=1");
exit;
