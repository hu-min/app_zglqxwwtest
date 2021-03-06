<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

</HEAD>
<body>
    
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//获取当前页码
$page=intval(addslashes($_GET["page"]));

//追加可以选择显示条数的功能，默认的情况是显示5条 20151030
$showCount = intval(addslashes($_GET['showCount']));
if($showCount == 0){
	$showCount = 5; //默认为5条
}

//获取操作标识传入
$action = addslashes($_GET["action"]);
//取得公众号ID session值
$weixinID = $_SESSION['weixinID'];

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}
//是否删除
if($action=="del"){
	
    //获取问题ID号传入
    $thisScratchcardID=intval(addslashes($_GET["scratchcardID"]));
    //获取当前时间
    $nowtime=date("Y/m/d H:i:s",time());
	$sql = "update scratchcard_main
			set scratchcard_isDeleted=1,
				scratchcard_editTime = '$nowtime'
			where scratchcard_id=$thisScratchcardID
			AND WEIXIN_ID = $weixinID";
	$errno = SaeRunSql($sql);
	if( $errno != 0 ){
		echo "<script>alert('删除刮刮卡主表数据失败！');history.back();</Script>";
		exit;
	}
	echo "<script>alert('操作成功！');location='scratchcardManger.php?page=$page';</Script>";
    exit;    
}    
//列表数据获取、分页

//计算总数
$sql="select COUNT(*) from scratchcard_main
	  where scratchcard_isDeleted = 0
	  AND WEIXIN_ID = $weixinID";
$count = getVarBySql($sql);
//如果数据表里有数据
if($count){
    //每页显示记录数
    $page_num = $showCount;
    //如果无页码参数则为第一页
    if ($page == 0) $page = 1;
    //计算开始的记录序号
    $from_record = ($page - 1) * $page_num;
    //获取符合条件的数据
	$sql = "select * from scratchcard_main
			where scratchcard_isDeleted = 0
			AND WEIXIN_ID = $weixinID
			order by scratchcard_id asc
			limit $from_record,$page_num";
    $class_list = getDataBySql($sql);
}
$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
	echo "取得配置信息失败，请确认！";
	exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];
?>
<!--页面名称-->
<h3>刮刮卡信息管理<a href="scratchcardSet.php?page=<?php echo $page;?>">新增信息>></a></h3>
<!--列表开始-->

<table class="table table-bordered">
	<thead><tr>
		<th>序号</th>
		<th>刮刮卡标题</th>
		<th>详细信息描述</th>
		<th>可免费刮奖次数(每天)</th>
		<th>每次需<?php echo $weixinName;?>数</th>
		<th>活动开始日期</th>
		<th>活动结束日期</th>
		<th>领奖过期日期</th>
		<th>领奖地址</th>
		<th>奖项个数</th>
		<th>操作</th>
	</tr></thead>
<?php
	if($class_list){
		foreach($class_list as $value){
		echo "<tbody><tr>
				<td>$value[scratchcard_id]</td>
				<td>$value[scratchcard_title]</td>
				<td><textarea>$value[scratchcard_description]</textarea></td>
				<td>$value[scratchcard_times]</td>
				<td>$value[scratchcard_Integral]</td>
				<td>$value[scratchcard_beginDate]</td>
				<td>$value[scratchcard_endDate]</td>
				<td>$value[scratchcard_expirationDate]</td>
				<td><textarea>$value[scratchcard_address]</textarea></td>
				<td>$value[scratchcard_count]</td>
				<td>
				<a href='javascript:isDelete($value[scratchcard_id]);'>删除</a>&nbsp
				<a href='scratchcardSet.php?action=edit&scratchcardID=$value[scratchcard_id]'>修改</a>
				</td>
			<tr></tbody>";
		}
	}else{
		echo "<tbody><tr><td colspan=11>无记录</td></tr></tbody>";
	}
?>
</table>
<ul class="pagination" id="pagination"></ul>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../../Static/JS/multi.js"></script>
<script>
	$(document).ready(function() {
		var pagecount = <?php echo $count;?>;
		var pagesize = <?php echo $page_num;?>;
		var currentpage = <?php echo $page;?>;
		var showCount = <?php echo $showCount?>;
		multi(pagecount,pagesize,currentpage,showCount,"scratchcardManger");
		$("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
	});
	function isDelete(id){
		if(confirm("确认删除吗？")){
			location.href='scratchcardManger.php?action=del&scratchcardID='+id;
		}else{
			return false;
		}
	}
</script>
</body>
</html>
