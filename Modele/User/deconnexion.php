<?php
session_start();
session_destroy();
header("Location: ../../Vue/Login/login.html");

