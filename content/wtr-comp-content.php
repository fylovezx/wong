<?php
@session_start();
/**
 * 这个页面承担了两个功能。。。。
 * 两个功能通过struid 是book还是link区分，要改成两个页面
 * 
 */

if(isset($_SESSION['ajax']) ){
    echo "session访问".$_SESSION['ajax'][1]."，include注意地址的问题";
    if($_SESSION['ajax'][0]=='wtr-comp-content'){
        $struid = $_SESSION['ajax'][1];
        unset($_SESSION['ajax']);
    }else{
        unset($_SESSION['ajax']);
        echo "<script>alert('非法session访问wtr-comp-content.php！'); window.location.href='main-login.php';</script>";
    }
    
}else{
    echo "get访问".$_GET['struid'];
    if(isset($_GET['struid'])){
        $struid = $_GET['struid'];
    }else{
        //这种情况属于非法进入，应当直接予以退出处理
        echo "<script>alert('非法get访问wtr-index-comp.php！'); window.location.href='main-login.php';</script>";
    }
}
$struarray = explode("-",$struid);
$stru = $struarray[0];
$id = $struarray[1];

$connname = $_SESSION['userinfo']['connname'];
include_once $_SERVER['DOCUMENT_ROOT'].'/tools/conn.php';setconnparm($conne,$connname);
$db=$conne->getconneinfo('dBase');
switch ($stru)
{
case "book":
    /* ajax AjaxWtrNew 调用
    代表是要在本书之中插入章节
    给一个章列表，是插入章还是插入节 
    这里插入章，节在各章之中体现
    */
    $idbk = $id;
    //查询章的cpsnum
    $sql = "SELECT Max(cpsnum) as snum  FROM $db.chapter where idbk =$idbk ";
    $rssnum = $conne->getRowsArray($sql);
    if(count($rssnum)){
        $cpsum = $rssnum[0]["snum"]+1;
    }else{
        $cpsum = 1;
    }
    $sql = "SELECT link FROM $db.book where idbk =$idbk ";
    $rslink = $conne->getRowsRst($sql);
    $bklink =$rslink["link"];
    $linkpre = explode("-",$bklink);
    $link = $linkpre[0]."-firstcp";
    
    
    echo <<<THE
    <form action="" method="post" onsubmit="return chkcpin(this)">
    <input type="text" name="stru" value="chapter" style="display:none"><input type="text" name="idbk" value="$idbk" style="display:none">
    第<input type="text" name="cpsnum" value="$cpsum">章 名称: <input type="text" name="cpname"><br>
    分配指向链接:<input type="text" name="link" value="$link">
    <input type="submit" name="insertdata" value="新建章信息">
    </form> 
THE;
break;
case "chapter":
    /* ajax AjaxWtrNew 调用
    代表是要在本书之中插入章节
    给一个章列表，是插入章还是插入节 
    这里插入章，节在各章之中体现
    */
    $idcp = $id;
    //查询章的scsnum
    $sql = "SELECT Max(scsnum) as snum  FROM $db.section where idcp =$idcp ";
    $rssnum = $conne->getRowsArray($sql);
    if(count($rssnum)){
        $scsnum = $rssnum[0]["snum"]+1;
    }else{
        $scsnum = 1;
    }
    $sql = "SELECT link FROM $db.chapter where idcp =$idcp ";
    $rslink = $conne->getRowsRst($sql);
    $bklink =$rslink["link"];
    $linkpre = explode("-",$bklink);
    $link = $linkpre[0]."-".$linkpre[1]."-firstsc";

    echo <<<THE
    <form action="" method="post" onsubmit="return chkscin(this)">
    <input type="text" name="stru" value="section" style="display:none"><input type="text" name="idcp" value="$idcp" style="display:none">
    第<input type="text" name="scsnum" value="$scsnum">节 名称: <input type="text" name="scname"><br>
    分配指向链接:<input type="text" name="link" value="$link">
    <input type="submit" name="insertdata" value="新建节信息">
    </form> 
THE;
break;
case "link":
/**
 * AjaxWtrComp中获取数据库中的文本
 */
    $link = substr($struid,5);
    echo "<a href=\"content/wtr-comp-link.php?link=$link\" target=\"_Blank\">编辑请点击此处</a><br>";
    // 读取link-指向的的文本
    //建立实现浏览div
    echo '<div id="htmlarea" style="background-color:#e7e7e7;width:700px;float:left;">';
    $sql = "SELECT htmlpage FROM $db.htmlpage WHERE link='$link'";
    $rs = $conne->getRowsRst($sql);    
    $find = array("<",">","\\\"");
    $replace = array("&lt","&gt","&quot;");
    $htmlpage =str_replace($replace,$find,$rs["htmlpage"]);  
    echo $htmlpage;
    echo '</div>';
break;
}
?>