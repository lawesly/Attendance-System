<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
	var money;
	$(function() {
		$("#server_name1").text(function(i,origText){
		    return number+"人"; 	    
		  });
	});
	function win(){
		money=$("#money").val();
		if(money=="")  return ;
		if(money<1){
			$('#x').window('open');
		}else{
			$("#money1").text(function(i,origText){
			    return money+"元"; 	    
			});
			$("#shu").text(function(i,origText){
			    return "增加用户"+money*10+"人"; 	    
			});
			$('#w').window('open');
			//
		}
	}
	function Form(){
		 $('#alipayment').submit();
		 $('#w').window('close');
	}
</script>
<h2 style="margin-left: 10px">订购服务</h2>
<p style="margin-left: 10px;font-size:16px">充值须知：增加管理用户数量，按每元增加10人计费</p>

<div style="width:400px;margin-left: 100px;padding:10px 60px">
	<div style="margin-bottom:20px">      
   		<h3>已有用户:&nbsp<span id="server_name1" style="color:blue"></span></h3>
	</div>
<form id=alipayment action=/22/Alipay/alipayapi.php method=post target="_blank">
	<div style="margin-bottom:20px">      
   		<h3>充值金额:&nbsp<input  class="easyui-numberbox" id="money" name="WIDtotal_fee"  data-options="prompt:''" style="width:36%;height:32px" required="required"/><span> 元（1元 = 10人）</span></h3>
	</div>
</form>
	<div>
   		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="win()" style="width:20%;height:32px;margin-left: 86px">确认</a>
  	</div>
</div>
<div id="w" class="easyui-window"  closed="true" title="订单确认" data-options="iconCls:'icon-save'" style="width:360px;height:204px;padding:5px;">
      <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'center'" style="padding:10px;">
              <h3>&nbsp&nbsp&nbsp&nbsp消费信息：&nbsp<span id="shu" style="font-size:14px;font-weight:normal;"></span></h3> 
              <h3>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp说明：&nbsp<span style="font-size:14px;font-weight:normal;">充值不支持退款</span></h3>
              <h3>&nbsp&nbsp&nbsp&nbsp支付金额：&nbsp<span id="money1" style="font-size:14px;font-weight:normal;"></span></h3>
          </div>
          <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">       
              <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="Form()" style="width:100px">确认并购买</a>            
              <a class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" href="javascript:void(0)" onclick="$('#w').window('close')" style="width:80px">关闭</a>
          </div>
      </div>
</div>
<div id="x" class="easyui-window" title="消息提示" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:250px;height:150px;padding:40px;">
      &nbsp&nbsp 充值金额不得小于1
</div>