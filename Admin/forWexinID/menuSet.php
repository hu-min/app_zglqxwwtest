<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>自定义菜单设置</title>
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

<script>
var text = "",lastChar = "";
function setMenuAction(thisText) {
    text = thisText;

    //超过9以后截取后两位，没超过则截取最后一位
    if( (text.length == 4) || (text.length == 7)){
        lastChar = text.substr(-1,1);
    }else{
        lastChar = text.substr(-2,2);
    }
    //启动模态
    $('#myModal').modal();

    //取得原先设置的数据
    if(text.length == 4){
        $("#ipt-click").val($("#clickName"+lastChar).val());
        $("#ipt-url").val($("#linkName"+lastChar).val());
    }else{
        $("#ipt-click").val($("#subClickName"+lastChar).val());
        $("#ipt-url").val($("#subLinkName"+lastChar).val());
    }
}
$(function(){
    $('#myModal').on('hidden.bs.modal', function () {
        $("#ipt-url").attr("disabled","disabled");
        //只能选择一种作为该菜单一级的目标（链接或者图文形式）
        if(($.trim($("#ipt-click").val()) != "") && ($.trim($("#ipt-url").val()) != "")){
            alert("只能选择其中一种类型");
            return;
        }
        if(($.trim($("#ipt-click").val()) == "") && ($.trim($("#ipt-url").val()) != "")) {
            if(text.length == 4){
                $("#linkName"+lastChar).val($("#ipt-url").val());
                $("#menutype"+lastChar).val("view");
            }else{
                $("#subLinkName"+lastChar).val($("#ipt-url").val());
                $("#subMenutype"+lastChar).val("view");
            }
        }
        if(($.trim($("#ipt-click").val()) != "") && ($.trim($("#ipt-url").val()) == "")){
            if(text.length == 4){
                $("#clickName"+lastChar).val($("#ipt-click").val());
                $("#menutype"+lastChar).val("click");
            }else{
                $("#subClickName"+lastChar).val($("#ipt-click").val());
                $("#subMenutype"+lastChar).val("click");
            }
        }
        if(($.trim($("#ipt-click").val()) == "") && ($.trim($("#ipt-url").val()) == "")){
            if(text.length == 4){
                $("#linkName"+lastChar).attr("value",'');
                $("#clickName"+lastChar).attr("value",'');
                $("#menutype"+lastChar).attr("value",'');
            }else{
                $("#subLinkName"+lastChar).attr("value",'');
                $("#subClickName"+lastChar).attr("value",'');
                $("#subMenutype"+lastChar).attr("value",'');
            }
        }
        //设置完毕后将模态中的数据清空
        $("#ipt-click").attr("value",'');
        $("#ipt-url").attr("value",'');
    });
});
</script>
</head>
<body>
<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');
    $weixinID=addslashes($_GET["weixinID"]);

    $sql = "select * from replyInfo where WEIXIN_ID = $weixinID";
    $replyInfo = getDataBySql($sql);
    $replyInfoCount = count($replyInfo);

?>
<form id = "menuform" action = "menuSetData.php?weixinID=<?php echo $weixinID;?>" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
<fieldset>
<div id = "mainInfo" class=" form-group col-sm-12">
<h4>菜单设计器 <small>编辑和设置微信公众号码, 必须是服务号或者是已认证的订阅号才能编辑自定义菜单。</small></h4>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="col-md-4"> 
                <div>
                    <label >第一个按钮设置（最多五个分按钮）：</label></br></br>
                    <input type="text" id = "titleName1" name = "titleName1" class="form-control " value="" maxLength="5">
                    <input type="hidden" id = "menutype1" name = "menutype1" value="" />
                    <input type="hidden" id = "linkName1"name = "linkName1"/>
                    <input type="hidden" id = "clickName1" name = "clickName1"/>
                    <a href="javascript:;" onclick="setMenuAction('btn1');" class="glyphicon glyphicon-edit parent" title="设置第一个主按钮菜单动作"></a>
                </div>
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName1" name = "subTitleName1" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype1" name = "subMenutype1"  />
                    <input type="hidden" id = "subLinkName1" name = "subLinkName1"/>
                    <input type="hidden" id = "subClickName1" name = "subClickName1"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn1');" class="glyphicon glyphicon-edit" title="设置第一个分按钮菜单动作"></a> &nbsp;
                </div>
            
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName2" name = "subTitleName2"  class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input id = "subMenutype2" name = "subMenutype2" type="hidden" />
                    <input id = "subLinkName2" name = "subLinkName2" type="hidden"/> 
                    <input id = "subClickName2" name = "subClickName2" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn2');" class="glyphicon glyphicon-edit" title="设置第二个分按钮菜单动作"></a> &nbsp;
                </div>
            
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName3" name = "subTitleName3" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype3" name = "subMenutype3" />
                    <input id = "subLinkName3" name = "subLinkName3" type="hidden"/>
                    <input id = "subClickName3" name = "subClickName3" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn3');" class="glyphicon glyphicon-edit" title="设置第三个分按钮菜单动作"></a> &nbsp;
                </div>
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName4" name = "subTitleName4" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype4" name = "subMenutype4"/>
                    <input id = "subLinkName4" name = "subLinkName4" type="hidden"/>
                    <input id = "subClickName4" name = "subClickName4" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn4');" class="glyphicon glyphicon-edit" title="设置第四个分按钮菜单动作"></a> &nbsp;
                </div>
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName5" name = "subTitleName5" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype5" name = "subMenutype5"/>
                    <input id = "subLinkName5" name = "subLinkName5" type="hidden"/>
                    <input id = "subClickName5" name = "subClickName5" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn5');" class="glyphicon glyphicon-edit" title="设置第五个分按钮菜单动作"></a> &nbsp;
                </div> 
            </div>
            <div class="col-md-4">
                <div>
                    <label >第二个按钮设置（最多五个分按钮）：</label></br></br>
                    <input type="text" id = "titleName2" name = "titleName2"  class="form-control " value="" maxLength="5">
                    <input type="hidden" id = "menutype2"  name = "menutype2"  value="" />
                    <input id = "linkName2" name = "linkName2" type="hidden"/>
                    <input id = "clickName2" name = "clickName2" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('btn2');" class="glyphicon glyphicon-edit parent" title="设置第二个主按钮菜单动作"></a>
                </div>
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName6" name = "subTitleName6" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype6" name = "subMenutype6" />
                    <input  id = "subLinkName6" name = "subLinkName6" type="hidden"/>
                    <input id = "subClickName6" name = "subClickName6" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn6');" class="glyphicon glyphicon-edit" title="设置第六个分按钮菜单动作"></a> &nbsp;
                </div>
            
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName7" name = "subTitleName7" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype7" name = "subMenutype7" />
                    <input id = "subLinkName7" name = "subLinkName7" type="hidden"/>
                    <input id = "subClickName7" name = "subClickName7" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn7');" class="glyphicon glyphicon-edit" title="设置第七个分按钮菜单动作"></a> &nbsp;
                </div>
            
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName8" name = "subTitleName8" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype8" name = "subMenutype8"  />
                    <input id = "subLinkName8" name = "subLinkName8" type="hidden"/>
                    <input id = "subClickName8" name = "subClickName8" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn8');" class="glyphicon glyphicon-edit" title="设置第八个分按钮菜单动作"></a> &nbsp;
                </div>
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName9" name = "subTitleName9" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype9" name = "subMenutype9" />
                    <input   id = "subLinkName9" name = "subLinkName9" type="hidden"/>
                    <input id = "subClickName9" name = "subClickName9" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn9');" class="glyphicon glyphicon-edit" title="设置第九个分按钮菜单动作"></a> &nbsp;
                </div>
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName10" name = "subTitleName10"  class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype10" name = "subMenutype10" />
                    <input  id = "subLinkName10" name = "subLinkName10" type="hidden"/>
                    <input id = "subClickName10" name = "subClickName10" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn10');" class="glyphicon glyphicon-edit" title="设置第十个分按钮菜单动作"></a> &nbsp;
                </div>
            </div>
            <div class="col-md-4">
                <div>
                    <label >第三个按钮设置（最多五个分按钮）：</label></br></br>
                    <input type="text" id = "titleName3" name = "titleName3" class="form-control " value="" maxLength="5">
                    <input type="hidden" id = "menutype3" name = "menutype3" value="" />
                    <input id = "linkName3" name = "linkName3" type="hidden"/>
                    <input id = "clickName3" name = "clickName3" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('btn3');" class="glyphicon glyphicon-edit parent" title="设置第三个主按钮菜单动作"></a>
                </div>
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName11" name = "subTitleName11" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype11" name = "subMenutype11" />
                    <input id = "subLinkName11" name = "subLinkName11" type="hidden"/>
                    <input id = "subClickName11" name = "subClickName11" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn11');" class="glyphicon glyphicon-edit" title="设置第十一个分按钮菜单动作"></a> &nbsp;
                </div>
            
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName12" name = "subTitleName12" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype12" name = "subMenutype12"  />
                    <input id = "subLinkName12" name = "subLinkName12" type="hidden"/>
                    <input id = "subClickName12" name = "subClickName12" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn12');" class="glyphicon glyphicon-edit" title="设置第十二个分按钮菜单动作"></a> &nbsp;
                </div>
            
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName13" name = "subTitleName13" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype13" name = "subMenutype13" />
                    <input id = "subLinkName13" name = "subLinkName13" type="hidden"/>
                    <input id = "subClickName13" name = "subClickName13" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn13');" class="glyphicon glyphicon-edit" title="设置第十三个分按钮菜单动作"></a> &nbsp;
                </div>
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName14" name = "subTitleName14" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype14" name = "subMenutype14"/>
                    <input id = "subLinkName14" name = "subLinkName14" type="hidden"/>
                    <input id = "subClickName14" name = "subClickName14" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn14');" class="glyphicon glyphicon-edit" title="设置第十四个分按钮菜单动作"></a> &nbsp;
                </div>
                <div style=" padding-left:40px;">
                    <input type="text" id = "subTitleName15" name = "subTitleName15" class="form-control " value="" maxLength="13"> &nbsp; &nbsp; 
                    <input type="hidden" id = "subMenutype15" name = "subMenutype15"/>
                    <input id = "subLinkName15" name = "subLinkName15" type="hidden"/>
                    <input id = "subClickName15" name = "subClickName15" type="hidden"/>
                    <a href="javascript:;" onclick="setMenuAction('subBtn15');" class="glyphicon glyphicon-edit" title="设置第十五个分按钮菜单动作"></a> &nbsp;
                </div> 
            </div>
        </div>
        <div class="form-group" style="display:none">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-8">
                <input type="hidden" name="ccfs" class="ccfs">
                <button type="button" class="btn btn-primary btn-block" onclick="return tijiaobiaodan('bf')">保存</button>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-8">
                <input type="hidden" name="ccfs" class="ccfs">
                <button type="button" class="btn btn-primary btn-block" onclick="return tijiaobiaodan('tj')">保存并部署到公众号</button>
            </div>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" style="width: 100%;">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">
               只能选择一种作为该菜单一级的目标（链接或者图文形式）
            </h4>
            <hr/>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#url" data-toggle="tab">设定链接</a></li>
                <li class=""><a href="#rules" data-toggle="tab">指向到指定规则</a></li>
            </ul>
         </div>
         <div class="modal-body">
            <div class="tab-content" style="padding:10px 0;">
                <div class="tab-pane active" id="url">
                    <div class="well">
                        <input class="form-control col-md-8" id="ipt-url" type="text" disabled>
                        <span class="help-block">指定点击此菜单时要跳转的链接（注：链接需加http://）</span>
                        <div class="alert-block" style="padding:3px 0;"><strong class="text-error">使用微站链接:</strong>
                    <?php
                        for($i = 0;$i<$replyInfoCount;$i++){
                    ?>
                            <a href="javascript:;" class="icon-external-link preview" onclick="$('#ipt-url').attr('disabled','disabled');$('#ipt-url').val('<?php echo $replyInfo[$i]['reply_url'];?>')"><?php echo $replyInfo[$i]['event_Text']?></a>&nbsp;
                    <?php
                        }
                    ?>
                       
                        <a href="javascript:;" id ="setUrl">&nbsp;&nbsp;&nbsp;【自己填写】</a>&nbsp;
                        <a href="javascript:;"  onclick="$('#ipt-url').attr('value','')">&nbsp;&nbsp;&nbsp;【清除记录】</a>&nbsp;
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane " id="rules">
            <div id="rules-container">
                <table class="tb table table-bordered" style="margin-bottom:8px;">
                <tbody>
                    <input class="form-control col-md-8" id="ipt-click" type="text" disabled>
                    <span class="help-block">选择下面的固有规则作为当前菜单的规则，没有请新增规则</span>
                    <tr class="control-group">
                        <td class="rule-content">
                            <?php 
                                for($i = 0;$i<$replyInfoCount;$i++){
                            ?>
                            <legend>
                                <small style="font-size:12px;">
                                <?php echo $replyInfo[$i]['event_Text']?> &nbsp;&nbsp; 
                                <a href="javascript:;" onclick="$('#ipt-click').val('<?php echo $replyInfo[$i]['event_Text'];?>')" class="pull-right"><i class="glyphicon glyphicon-star"></i> 设定为当前菜单消息</a></small>
                            </legend>
                            <?php
                            }
                            ?>
                            <small style="font-size:14px;">
                                <a href="javascript:;" onclick="$('#ipt-click').attr('value','')" class="pull-right">【清除记录】&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                            </small>
                            <legend></legend>
                            <legend>
                                <small style="font-size:12px;">
                                在【选择功能及回复设置】→【回复设置】中设定新的规则，以便选择 &nbsp;&nbsp; 
                                </i> 设定为当前菜单消息</small>
                            </legend>
                        </td>
                    </tr>
                    
                </tbody>
                </table>
                
                </div></div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary"
               data-dismiss="modal">提交设置
            </button>
         </div>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script src="../../Static/JS/uploadPreview.js" type="text/javascript"></script>
<script type="text/javascript">
    function tijiaobiaodan(cs) {
		if (cs == 'tj') {
			$(".ccfs").val("tj");
		}
		if (cs == 'bf') {
			$(".ccfs").val("bf");
		}
        $(".form-horizontal").submit();
	};
    $(function(){

        $('#setUrl').click(function(){
            $("#ipt-url").val("");
            $("#ipt-url").removeAttr("disabled");
        });
    });
</script>
</body>
</html>