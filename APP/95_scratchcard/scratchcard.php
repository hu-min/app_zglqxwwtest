<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<head>
<title>刮刮卡</title>
<link href="css/activity-style.css" rel="stylesheet" type="text/css">
</head>
<body data-role="page" class="activity-scratch-card-winning">
<?php
//判断是否取得openid 和 是否为会员判定
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);  //weixinID

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

isVipByOpenid($openid,$weixinID,"95_scratchcard/scratchcard.php");

$config =  getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

//是会员的情况下，进行奖品主信息查询

//取得当前时间有效的刮刮卡Main信息
$nowDate = date("Y-m-d",time());
$sql = "SELECT * FROM  scratchcard_main
        WHERE scratchcard_beginDate <= '$nowDate'
        AND scratchcard_endDate >= '$nowDate'
        AND scratchcard_isDeleted = 0
        AND WEIXIN_ID = $weixinID
        ORDER BY scratchcard_id DESC";
$scratchcardMainInfo = getlineBySql($sql);
if(!$scratchcardMainInfo){
?>
    <div class="main">
        <div class="content">
            <div class="boxcontent boxwhite">
                <div class="box">
                    <div class="title-brown">活动说明：
                    </div>
                    <div class="Detail">
                        <p>因后台系统升级中，<?php echo $weixinName;?>抽奖活动将暂停几天，原先中奖者的奖品我们会在陆续进行兑现，希望大家继续关注路桥发布！</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 	
    exit;
}else{
    
    //取得各个字段的内容
    $scratchcard_id = $scratchcardMainInfo['scratchcard_id'];
    $freeTimes = $scratchcardMainInfo['scratchcard_times'];
    $beginDate = $scratchcardMainInfo['scratchcard_beginDate'];
    $endDate = $scratchcardMainInfo['scratchcard_endDate'];
    $integral = $scratchcardMainInfo['scratchcard_Integral'];

    //取得各个字段内容（数组）
    $scratchcard_detail_name = json_decode($scratchcardMainInfo['scratchcard_detail_name']);
    $scratchcard_detail_description = json_decode($scratchcardMainInfo['scratchcard_detail_description']);
    $scratchcard_detail_count = json_decode($scratchcardMainInfo['scratchcard_detail_count']);
}
?>
<div class="main">
    <div class="cover" id = "cover">
        <img src='./img/activity-scratch-card-bannerbg.png'/>
        <div id="prize"><p><span id="prizeNew"></span></p></div>
        <div id="scratchpad">
            <div style="position: absolute; width: 150px; height: 40px; cursor: default;">
                <canvas width="150" height="40" style="cursor: default;"></canvas>
            </div>
        </div>
    </div>
    <div class="content">
        <div id="winprize" style="display:none" class="boxcontent boxwhite">
            <div class="box">
                <div class="title-red"><span>恭喜你中奖了</span></div>
                <div class="Detail">
                    <p>您中了：<span class="red" id="prizelevel"></span></p>
                    <p>奖品为：<span class="red" id="prizename"></span></p>
                    <p>兑换SN码(可在会员中心画面查询)： <span class="red" id="prizeSN"></span></p>
                    <p>领奖地址： <span class="red" id="prizeAdress"></span></p>
                    <p>过期日期： <span class="red" id="prizeexPirationDate"></span></p>
                </div>
            </div>
        </div>
        <div id="maxTimesInfo" style="display:none" class="boxcontent boxwhite">
            <div class="box">
                <div class="title-red"><span>不能继续啦</span></div>
                    <p>亲，您没有抽奖机会啦！<span class="red"></span></p>
            </div>
        </div>
        <div id="noDataInfo" style="display:none" class="boxcontent boxwhite">
            <div class="box">
                <div class="title-red"><span>注意啦</span></div>
                    <p>取得数据失败，请重新进入！<span class="red"></span></p>
            </div>
        </div>
        <div id="winnedInfo" style="display:none" class="boxcontent boxwhite">
            <div class="box">
                <div class="title-red"><span>已中过奖咯</span></div>
                <div class="Detail">
                    <p>[中奖日期]：<span class="red" id="winneddateTime"></span></p>
                    <p>[当时您中了]：<span class="red" id="winnedprizelevel"></span></p>
                    <p>[奖品]：<span class="red" id="winnedprizename"></span></p>
                    <p>[兑换SN码](可在会员中心画面查询)： <span class="red" id="winnedprizeSN"></span></p>
                    <p>[领奖地址]： <span class="red" id="winnedprizeAdress"></span></p>
                    <p>[过期日期]： <span class="red" id="winnedprizeexPirationDate"></span></p>
                </div>
            </div>
        </div>
        <div id="NowIntegralCount" style="display:none" class="boxcontent boxwhite">
            <div class="box">
                <div class="title-red"><span>亲爱的会员</span></div>
                    <p>今日免费次数已经用完，您将使用<?php echo $weixinName;?>进行<span class="red"></span></p>
                    <p>目前的<?php echo $weixinName;?>为：<span class="red"></span><span class="red" id = "thisVipIntegral">分</span></p>
            </div>
        </div>
        <div class="boxcontent boxwhite" id = "defaultInfo1">
            <div class="box">
                <div class="title-brown">活动说明：</div>
                    <div class="Detail">
                        <p><font color="red">[活动开始时间]：</font><?php echo $beginDate;?></p>
                        <p><font color="red">[活动结束时间]：</font><?php echo $endDate;?></p>
                        <?php if ($freeTimes > 0){
                        ?>
                            <p><font color="blue">免费次数：<?php echo $freeTimes;?>次
                            <p><font color="blue">免费结束后，每次需要<?php echo $integral;?><?php echo $weixinName;?>
                        <?php
                        }else{
                        ?>
                            <!--<p><font color="blue">每次需要--><?php //echo $integral;?><!----><?php //echo $weixinName;?>
                            <p><font color="blue">每一次成功"建言献策"，将赢得一次抽奖机会
                        <?php
                        }
                        ?>
                        </font></p>
                    </div>
            </div>
        </div> 
        <div class="boxcontent boxwhite" id = "defaultInfo2">
            <div class="box">
                <div class="title-brown">奖品设置：</div>
                    <div class="Detail">
                        <?php
                            $detailCount = count($scratchcard_detail_name);
                            for($i = 0;$i<$detailCount;$i++){
                        ?>
                            <p><font color="red"><?php echo $scratchcard_detail_name[$i]?>：</font>
                            <?php echo $scratchcard_detail_description[$i]?> &nbsp数量：
                            <?php echo $scratchcard_detail_count[$i]?> </p>
                        <?php
                        }
                        ?>
                    </div>
                    
            </div>
        </div>
    </div>    
    <div style="clear:both;"></div>
</div>
<div style="height:60px;"></div>

<script src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script src="js/wScratchPad.js" type="text/javascript"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script src="../js/alert.js" type="text/javascript"></script>
<script type="text/javascript">
    //隐藏右上角分享按钮
    NoShowRightBtn();

    $(function(){
        var display = false;
        var num = 0;
        var win = false;
        var prizeName = "";
        var prizedescription = "";
        var SN = "";
        var adress = "";
        var pirationDate = ""

        //取得当前的年月日，以便于数据库中的最近更新日比较
        var today = new Date();
        var year = today.getFullYear();
        var month = today.getMonth() + 1;
        //人为将月份改为两位数
        if(month<10){
            month = "0" + month;
        }else{
            month = month;
        }
        var day = today.getDate();
        var thisDate = year + "-" + month + "-" + day;

        //【免费次数用完画面】初始化
        var freeIsOverFlag = 0;
        $.ajax({
            url: "scratchcardData.php?action=isfreeOrNot&openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>",
            type: "POST",
            dataType: "json",
            data: {"thisDate":thisDate},
            success: function(data) {
                //进入画面时，先判断是否已经使用完免费次数
                if(data.status == "sameDateAndFlag"){
                    //隐藏本来的活动说明框
                    $("#thisVipIntegral").text(data.integral);
                    $("#NowIntegralCount").show();
                    return;
                }
            },
            //未获取json返回时
            error: function() {
                alert(data.message+"error");
            }
        })
        //刮刮卡进行时触发
        $("#scratchpad").wScratchPad({
            width : 150,
            height : 40,
            color : "#a9a9a7",

            scratchMove : function(e, percent){
                num++;
                //80%时自动清除
                if(percent > 80){
                    this.clear();
                }
                //开始时请求中奖结果
                if (num == 1) {

                    $.ajax({
                        url: "scratchcardData.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>",
                        type: "POST",
                        dataType: "json",
                        data: {"scratchcard_id":<?php echo $scratchcard_id;?>},
                        success: function(data) {

                            //alert(data.status);
                            if (data.status == "noData"){
                                $("#cover").slideUp(1000);
                                $("#defaultInfo1").slideUp(500);
                                $("#defaultInfo2").slideUp(500);

                                $("#noDataInfo").slideToggle(500);

                                return;

                            }
                            if (data.status == "ok") {
                                win = true;

                                prizeName = data.prizelevel;
                                prizedescription = data.prizedescription;
                                SN = data.SN;
                                adress = data.adress;
                                pirationDate = data.expirationDate;

                                $("#prize").text(prizeName);
                                $("#prizelevel").text(prizeName);
                                $("#prizename").text(prizedescription);
                                $("#prizeSN").text(SN);
                                $("#prizeAdress").text(adress);
                                $("#prizeexPirationDate").text(pirationDate);


                                $("#defaultInfo1").slideUp(10);
                                $("#defaultInfo2").slideUp(10);
                                $("#NowIntegralCount").hide();
                                $("#winprize").slideToggle(1000);

                                return

                            }
                            //<?php echo $weixinName;?>不够一次刮刮卡时
                            if(data.status == "NoEnoughIntegral"){

                                $("#cover").hide();

                                $("#defaultInfo1").hide();
                                $("#defaultInfo2").hide();
                                $("#maxTimesInfo").slideToggle(1000);

                                //$("#thisVipIntegral").text(data.nowIntegralData);
                                //$("#NowIntegralCount").show();

                                return;
                            }

                            //已经中过奖了
                            if (data.status == "isWinning") {

                                $("#cover").hide();
                                $("#NowIntegralCount").slideUp(10);
                                $("#defaultInfo1").slideUp(10);
                                $("#defaultInfo2").slideUp(10);

                                $("#winneddateTime").text(data.winnedDateTime);
                                $("#winnedprizelevel").text(data.prizelevel);
                                $("#winnedprizename").text(data.prizedescription);
                                $("#winnedprizeSN").text(data.SN);
                                $("#winnedprizeAdress").text(data.adress);
                                $("#winnedprizeexPirationDate").text(data.expirationDate);

                                $("#winnedInfo").slideToggle(1000);

                                return
                            }
                        },
                        //未获取json返回时
                        error: function() {
                            alert(data.message+"error");
                        }
                    })
                }
                if (num > 10){
                    if (!display){
                        //根据概率显示
                        if (!win){
                            $("#prize").text("谢谢参与");
                        }
                    }
                    display = true;
                }
            }
        });
    });
</script>
</body>
</html>