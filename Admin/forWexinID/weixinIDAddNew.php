<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新追加会员初始化设置</title>
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');
    
    $nowTime  = date("Y-m-d H:i:s",time());
    $action=addslashes($_GET["action"]);
    $weixinID=addslashes($_GET["weixinID"]);
    if($action == "delete"){
        $deleteSql = "update AdminToWeiID
                      set weixinEditTime = '$nowTime',
                          weixinStatus = 0
                      where id=$weixinID";
        $errono = SaeRunSql($deleteSql);
        if($errono == 0){
            $msg = "删除成功！"; 
        }else{
            $msg = "删除失败！";
        }
        echoInfo($msg);
        exit;
    }
    $weixinID=addslashes($_GET["weixinID"]);
    $sql = "select * from AdminToWeiID
            where id = '$weixinID'
            AND  weixinStatus = 1";
    $weixinInfo = getLineBySql($sql);
    if($weixinID){
    ?>	
	<script type="text/javascript">
        //用于显示select的选中事件
		$(document).ready(function(){
			$("#weixinType ").val(<?php echo $weixinInfo['weixinType'];?>);
		});
	</script>
	
    <?php 	
    }
?>
<form action="weixinIDAddNewData.php" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
	<fieldset>
    <div id = "mainInfo">
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-7">
            <p><h2><span class="label label-info">设&nbsp &nbsp置&nbsp &nbsp公&nbsp &nbsp众&nbsp &nbsp号</span></h2></p></br>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="weixinName">公众号名称：</label>
            <div class="col-sm-5">
                <input class="form-control" type="text" id = "weixinName" name = "weixinName" value =<?php echo $weixinInfo['weixinName'];?> >
                <span class="help-block">您可以给此公众号起一个名字, 方便下次修改和查看.</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="weixinType">公众号类型：</label>
            <div class="col-sm-5">
                <select class="form-control" id = "weixinType" name = "weixinType">
                    <option value="0">服务号</option>
                    <option value="1">订阅号</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="weixinUrl"><font color="red">接口地址：</font></label>
            <div class="col-sm-5">
                <input readonly type="text" class="form-control" id="weixinUrl" name="weixinUrl" value = <?php echo $weixinInfo['weixinUrl']?>>
                <span class="help-block">设置“微信公众平台接口”配置信息中的接口地址.</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="weixinToken"><font color="red">微信Token：</font></label><a href="javascript:void(0);" onclick="create_token()">生成新的</a>
            <div class="col-sm-5">
                <input readonly type="text" class="form-control" id="weixinToken" name="weixinToken" value = <?php echo $weixinInfo['weixinToken']?>>
                <span class="help-block">与微信公众平台接入设置值一致，必须为英文或者数字，长度为3到32个字符. 请妥善保管, Token 泄露将可能被窃取或篡改微信平台的操作数据.(如未生成请点击【生成新的】)</span>
                <div id="tokenMsg" class="alert alert-warning" style = "display:none"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="weixinAppId">公众号AppId：</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="weixinAppId" name="weixinAppId" value = <?php echo $weixinInfo['weixinAppId']?>>
                <span class="help-block">请填写微信公众平台后台的AppId.</span>
            </div>
        </div>    
        <div class="form-group">
            <label class="col-sm-2 control-label" for="weixinAppSecret">公众号AppSecret：</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="weixinAppSecret" name="weixinAppSecret" value = <?php echo $weixinInfo['weixinAppSecret']?>>
                <span class="help-block">请填写微信公众平台后台的AppSecret, 只有填写这两项才能管理自定义菜单.</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="weixinCode">微信号：</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="weixinCode" name = "weixinCode" value = <?php echo $weixinInfo['weixinCode']?>>
                <span class="help-block">您的微信帐号，本平台支持管理多个微信公众号.</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="weixinOldID">原始账号：</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="weixinOldID" name = "weixinOldID" value = <?php echo $weixinInfo['weixinOldID']?>>
                <span class="help-block">微信公众帐号的原ID串.</span>
            </div> 
        </div>        
        <div class="form-group" id = "newImg">
            <label class="col-sm-2 control-label" for="up_img">二维码：</label>
            <input type="file" id="up_img" name="up_img" style = "display:none" accept="image/*"/>
            <div id="imgdiv"  class="col-sm-5">
                <img id="imgShow" src=<?php echo $weixinInfo['weixinQRCodeUrl'];?> class="img-rounded" width="150"/>
            </div>
        </div>
        <div class="form-group" id = "newImgMin">
            <label class="col-sm-2 control-label" for="up_imgMin">头像：</label>
            <input type="file" id="up_imgMin" name="up_imgMin" style = "display:none" accept="image/*"/>
            <div id="imgdivMin"  class="col-sm-5">
                <img id="imgShowMin" src=<?php echo $weixinInfo['weixinHeadUrl'];?> class="img-rounded" width="85"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-5">
                <input type="hidden" id="weixin_id" name = "weixin_id" value=<?php echo $weixinInfo['id']?>>
                <button type="submit" class="btn btn-primary btn-block "  id = "OKBtn" onclick="return formCheck();">提交</button>
            </div>
        </div>
    </div>    
    <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-4">
            <div id="myMsg" class="alert alert-warning" style = "display:none"></div>
            <div id="myOKMsg" class="alert alert-success" style = "display:none"></div>
        </div>
	</div>
	
	</fieldset>
</form>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script src="../../Static/JS/uploadPreview.js" type="text/javascript"></script>
<script>
    window.onload = function () {
        new uploadPreview({ UpBtn: "up_img", DivShow: "imgdiv", ImgShow: "imgShow" });
        new uploadPreview({ UpBtn: "up_imgMin", DivShow: "imgdivMin", ImgShow: "imgShowMin" });
    }

    //根据是修改还是新增来显示图片
    $(document).ready(function(){

        if($('#weixin_id').val() == ""){
            $('#imgShow').hide();
            $("#imgShow").attr("src", "img/default_QR.png");
            $('#imgShow').show();

            $('#imgShowMin').hide();
            $("#imgShowMin").attr("src", "img/default_head.png");
            $('#imgShowMin').show();

            //初始化设置Url，token
            $('#weixinUrl').val("http://<?php echo $_SERVER['HTTP_HOST'];?>/?weixinID="); //将app名称用常量替换
            create_token();
        }
    });
    $(function(){
        
        $('#imgShow').click(function(){
            $('#up_img').click();
        });
        $('#imgShowMin').click(function(){
            $('#up_imgMin').click();
        });
    });
    function formCheck(){
        if(isNull($('#weixinUrl').val())){
            alert("【公众号名称】不能为空");
            return false;
        }
        /*
        if($('#weixinType').val() == "1"){
             if(isNull($('#weixinUrl').val())){
                alert("【公众号名称】不能为空");
                return false;
            }
        }*/
        return true;
    }
    function create_token(){
        $.ajax({
                url:"weixinIDAddNewData.php?action=getToken"//改为你的动态页
                ,type:"POST"
                ,data:{}//调用json.js类库将json对象转换为对应的JSON结构字符串
                ,dataType: "json"
                ,success:function(data){
                    if(data.success == "OK"){
                        $('#weixinToken').val(data.msg);
                    }else{
                        $('#tokenMsg').html(data.msg);
                        $('#tokenMsg').show();
                        setTimeout("$('#tokenMsg').hide()",2000);
                    }
                    
                    
                } 
                //,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
    }
</script>
</body>
</html>