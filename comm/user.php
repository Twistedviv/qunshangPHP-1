<?php

/**用户信息操作文件**/
require_once 'common.php';


function addUser($utel,$upass,$preidentity){
	$link = get_connect();
	$utel=mysql_dataCheck($utel);
	$upass=mysql_dataCheck($upass);
	$uname="用户_".$utel;
	$gender=1;
	$headimage="//hbimg.huabanimg.com/6d28cfdb0f69acaa5c21651ebfb924a5b796dee646f30-JP2KuL_fw658/format/webp";
	$sql="insert into `tbl_user` (`utel`,`upass`,`uname`,`headimage`,`gender`,`preidentity`,`identity`)  values  ('$utel','$upass','$uname','$headimage',$gender,'$preidentity',1)";
	$rs=execUpdate($sql,$link);
	$getId=mysql_insert_id($link);
	mysql_close($link);
	return $getId;
}
/*
function getId(){
	$link=get_connect();
	$getId=mysql_insert_id($link);
	return $getId;
}
*/
function createInviteCode($userId)
{
    $sourceString = array('0','1','2','3','4','5','6','7','8','9','10','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
    $num = $userId;
    $code = '';
    while($num)
    {
        $mod = $num % 36;
        $num = (int)($num / 36);
        $code = "{$sourceString[$mod]}{$code}";
    }
    //判断code的长度
    if( empty($code[4]))
        str_pad($code,5,"0",STR_PAD_LEFT);
    return $code;
}
function updateInviteCode($invitecode,$userId){
	$link=get_connect();
	$sql="update `tbl_user` set `invitecode`='$invitecode' where `uid`=$userId";
	$rs=execUpdate($sql,$link);
	mysql_close($link);
	return $rs;
}
//输入过滤
function test_input($data) {
   $data = trim($data); //去除左右两端的空白字符
   $data = stripslashes($data); //去除输入中的反斜杠
   $data = htmlspecialchars($data); //将特殊字符转换为实体引用
   return $data;
}
function findUserByPhone($utel){
	$link = get_connect();
	$sql="select * from tbl_user where `utel`='$utel'";
	$rs=execQuery($sql,$link);
	mysql_close($link);
	if(count($rs)>0){
		return $rs[0];
	}
	return $rs;
}
function findUserByUid($uid){
	$link = get_connect();
	$sql="select * from tbl_user where `utel`=$uid";
	$rs=execQuery($sql,$link);
	mysql_close($link);
	if(count($rs)>0){
		return $rs[0];
	}
	return $rs;
}
function updateIdentityToMember($uid){
	$link = get_connect();
	$sql="update `tbl_user` set `identity`=2 where `uid`=$uid";
	$rs=execUpdate($sql,$link);
	mysql_close($link);
	return $rs;
}
function findIdentityById($uid){
	$link = get_connect();
	$sql="select * from tbl_user where `uid`=$uid";
	$rs=execQuery($sql,$link);
	mysql_close($link);
	if(count($rs)>0){
		$result=$rs[0]['identity'];
		return $result;
	}
}
function findPreinviteid($uid){
	$rs=findUserByUid($uid);
	$inviteid=$rs['inviteid'];
	return $inviteid;
}
function updateUserCredit($uid,$credit){
	$link = get_connect();
	$sql="update `tbl_user` set `credit`=$credit where `uid`=$uid";
	$rs=execUpdate($sql,$link);
	mysql_close($link);
	return $rs;
}
?>