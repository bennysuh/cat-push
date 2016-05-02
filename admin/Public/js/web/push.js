/**
 * web推送
 */

$(function(){
	/**
	 * 选择推送类型
	 */
	$("input[name='type']").change(function(){
		var type = $("input[name='type']:checked").val();
		
		if(type == 'all') {
			$("#part-form").hide();
			$("#group-form").hide();
		} else if(type == 'group') {
			$("#part-form").hide();
			$("#group-form").show();
		} else if(type == 'part') {
			$("#part-form").show();
			$("#group-form").hide();
		}
	});
	
	/**
	 * 添加用户id
	 */
	$(".add-user-btn").click(function(){
		var uid = $.trim($("#uid").val());
		if(!!!uid) {
			$("#uid").focus();
			return;
		}
		
		$(".add-user-list").append("<li data-uid='"+uid+"'>"+uid+"&nbsp;<a href='javascript:;' onclick='$(this).parent().remove();'><span aria-hidden='true' class='glyphicon glyphicon-remove'></span></a></li>");
		$("#uid").val("");
	});
	
	/**
	 * 添加用户组
	 */
	$(".add-usergroup-btn").click(function(){
		var group = $.trim($("#group").val());
		if(!!!group) {
			$("#group").focus();
			return;
		}
		
		$(".add-usergroup-list").append("<li data-group='"+group+"'>"+group+"&nbsp;<a href='javascript:;' onclick='$(this).parent().remove();'><span aria-hidden='true' class='glyphicon glyphicon-remove'></span></a></li>");
		$("#group").val("");
	});
	
	/**
	 * 提交推送
	 */
	$(".push-btn").click(function(){
		$(".err-msg").text("");
		
		var type = $("input[name='type']:checked").val();
		var content = $.trim($("#content").val());
		
		if(!!!content) {
			$(".err-msg").text("请输入推送内容");
			$("#content").focus();
			return;
		}
		
		var postData = new Object();
		postData.type = type;
		postData.content = content;
		
		if(type == 'part') {
			//获取要推送的用户列表
			var uidList = [];
			$(".add-user-list").find('li').each(function(){
				uidList.push($(this).attr("data-uid"));
			});
			
			if(uidList.length == 0) {
				$(".err-msg").text("请输入添加要推送的用户");
				return;
			}
			postData.uidList = uidList.join(",");
		} else if(type == 'group') {
			//获取要推送的用户组列表
			var groupList = [];
			$(".add-usergroup-list").find('li').each(function(){
				groupList.push($(this).attr("data-group"));
			});
			
			if(!!!groupList) {
				$(".err-msg").text("请输入添加要推送的用户组");
				return;
			}
			postData.groupList = groupList.join(",");
		}
		
		$.post("/web/pushCommit",postData,function(data){
			$(".err-msg").text(data.Msg);
			if(data.Code == 999) {
				$("#content").val("");
			}
		},'json');
	});
});