<?php
session_start();								//初始化SESSION变量
if(isset($_GET['page'])){
    $page = $_GET['page'];
}else{
    //这种情况属于非法进入，应当直接予以退出处理
    echo "<script>alert('非法访问！'); window.location.href='main_login.php';</script>";
}

switch ($page){
    case "index":
    $_SESSION['pageinfo']['CtLoc']='main-visit';//这里后面要根据权限修改为主页
    echo "<script>window.location.href='../index.php';</script>";
    break;
    case "dbm-index":
        $_SESSION['pageinfo']['CtLoc']='dbm-index';
        if(!isset($_SESSION['pageinfo']['dbm-index'])){
            $_SESSION['pageinfo']['dbm-index']='opdb';//不能删，否则无法通过main-div-nav切换
        }
    echo "<script>window.location.href='../index.php';</script>";
    break;
    case "wtr-index":
        $_SESSION['pageinfo']['CtLoc']='wtr-index';
        if(!isset($_SESSION['pageinfo']['wtr-index'])){
            $_SESSION['pageinfo']['wtr-index']='all-0';//不能删，否则无法通过main-div-nav切换
        }
    echo "<script>window.location.href='../index.php';</script>";
    break;

}

?>