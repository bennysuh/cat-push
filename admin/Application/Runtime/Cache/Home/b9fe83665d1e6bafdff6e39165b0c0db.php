<?php if (!defined('THINK_PATH')) exit();?><div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h1 class="page-header">WEB消息推送</h1>

    <div class="row">
		<div class="panel panel-default">
			<div class="panel-body">
				<form>
					<div class="form-group">
						<label for="push-type">推送类型</label> 
						<div id="push-type">
							<label class="radio-inline input-lg"> 
								<input type="radio" checked name="type" value="all">
								所有用户
							</label>
							<label class="radio-inline input-lg"> 
								<input type="radio" name="type" value="group">
								用户组
							</label>
							<label class="radio-inline input-lg"> 
								<input type="radio" name="type" value="part">
								指定用户
							</label>
						</div>						 
					</div>
					<div class="form-group" id="part-form" style="display:none;">
						<label for="add-user-div">添加用户</label>
						<div class="input-group" id="add-user-div">
							<input type="text" class="form-control" id="uid" placeholder="输入用户ID">
							<span class="input-group-btn">
								<button class="btn btn-info add-user-btn" type="button">
									<span aria-hidden="true" class="glyphicon glyphicon-plus"></span>&nbsp;添加
								</button>
							</span>
						</div>
						<ol class="add-user-list">
						</ol>
					</div>
					<div class="form-group" id="group-form" style="display:none;">
						<label for="add-usergroup-div">添加用户组</label> 
						<div class="input-group" id="add-usergroup-div">
							<input type="text" class="form-control" id="group" placeholder="输入用户组名">
							<span class="input-group-btn">
								<button class="btn btn-info add-usergroup-btn" type="button">
									<span aria-hidden="true" class="glyphicon glyphicon-plus"></span>&nbsp;添加
								</button>
							</span>
						</div>
						<ol class="add-usergroup-list">
						</ol>
					</div>
					<div class="form-group">
						<label for="content">内容</label>
						<textarea class="form-control" rows="3" id="content"></textarea>						
					</div>
					<button type="button" class="btn btn-success push-btn">推送</button>
					<span class="err-msg"></span>
				</form>
			</div>
		</div>
	</div>   
</div>
<script src="/Public/js/web/push.js"></script>