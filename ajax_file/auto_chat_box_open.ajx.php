<?php
session_start();
if (isset($_SESSION['chat']) && !empty($_SESSION['chat']))
{
    foreach ($_SESSION['chat'] as $k=>$v)
    {
        echo $k.'|'.$v.'|';
    }
}