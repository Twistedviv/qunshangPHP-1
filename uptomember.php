<?PHP
$uid=$_GET['uid'];
//身份为1是会员
$identity=1;
require_once 'comm/user.php';
require_once 'comm/membercase.dao.php';
require_once 'comm/memberprofit.dao.php';
//升级用户表中的身份
updateIdentityToMember($uid);
//插入会员用户情况表
addMemberCase($uid,$identity);
//通过循环找到池主upgradeid
$memberid=$uid;
$upgradeid=0000000000;
while(findIdentityById($memberid)<2){
	$memberid=findPreinviteid($memberid);
}
$upgradeid=$memberid;
//记录会员支付以及上级领主会员费收益
if(!findProfitByUid($uid)){
	//在会员收益表内没有记录，在会员费收益表中加入，缴费次数设为1
	addMembeProfit($uid,$upgradeid);
}else{
	// 在会员收益表中有记录，在会员表中更新，缴费次数加1
	$result=findProfitByUid($uid);
	$payfeenum=$result['payfeenum'];
	$payfeenum+=1;
	updateMemberProfit($uid,$payfeenum);
}
//消费行为积分收益
$bili=array(1,1,1);
$suid=$uid;
for($i=1;$i<count($bili);$i++){
	if(findIdentityById($suid)>=1){
		$rs=findUserByUid($suid);
		$credit=$rs['credit'];
		$credit+=200*bili[$i];
		updateUserCredit($suid,$credit);
	}
	if(!findPreinviteid($suid)){
		$suid=findPreinviteid($suid);
	}else{
		break;
	}
}
?>
