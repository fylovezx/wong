<?php
session_start();
/**
 * 插入或更新数据库中指定htmlpage ，
 * 1.防止误入的措施
 *  暂未做
 * 2.运行流程
 * 
 */
print_r($_POST);

if(isset($_POST['subhtmlpage'])){
    $connname = $_SESSION['userinfo']['connname'];
    include_once $_SERVER['DOCUMENT_ROOT'].'/tools/conn.php';setconnparm($conne,$connname);
    $db=$conne->getconneinfo('dBase');
    $contents = $_POST['htmlpage'];
    $link = $_POST['link'];
    $str = $contents;
    $arr=explode("\n",$str);
    $str1=nl2br($str);//回车换成换行
    $find2 = array("<",">","\"","\r\n");
    $replace = array("&lt","&gt","&quot;","\\\\n");
    $textstr = str_replace($find2,$replace,$contents);

    $sql = "SELECT link FROM $db.htmlpage WHERE link='$link'";
    $rs = $conne->getRowsRst($sql);
    if(!isset($rs['link'])){
        $sql = "INSERT INTO $db.htmlpage VALUES('$link','$textstr'); ";
    }else{
        $sql = "UPDATE $db.htmlpage SET htmlpage = '$textstr' WHERE link='$link'; ";
    }    
    $num =$conne->uidRst($sql);
    echo $sql."<br>";
    echo $num."<br>";

    if($num==0){
        echo "<script>alert('修改".$sql."失败".$conne->msg_error()."'); window.location.href='wtr-comp-link.php?link=$link';</script>";
    }else{
        echo "<script>alert('修改成功'); window.location.href='wtr-comp-link.php?link=$link';</script>";
    }
}



?>