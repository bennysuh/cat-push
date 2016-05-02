/**
 * cat-web-push客户端
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016-04-02
 */

$(function(){
    //websocket
    var ws;
    var empty_html = '<div class="msg-empty">消息列表空空哒~~</div>';
    
    //连接服务器
    $(".close-btn").click(function(){
        // 假设服务端ip为127.0.0.1
        ws = new WebSocket("ws://192.168.245.128:8383");
        ws.onopen = function() {
            //链接成功
            $('.conn-btn').show();
            $('.close-btn').hide();
        };
        ws.onmessage = function(e) {
            var data = e.data;
            var dataObj = JSON.parse(data);
            if(dataObj.Code == 999) {
                //收到服务器的消息
                //隐藏空消息提示
                $(".msg-empty").hide();

                //追加消息
                var html = [];
                html.push('<div class="alert alert-info alert-dismissible" role="alert">');
                html.push('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                html.push(dataObj.Msg);
                html.push('</div>');
                $('.msg-list').append(html.join(''));
            } else if(dataObj.Code == 1001) {
                //收到服务器推送的在线人数统计
                var num = dataObj.Data.num;
                $(".online-num-view").text(num);
            }
        };
        ws.onclose = function(e) {
            ws.close(); //关闭TCP连接
            $('.conn-btn').hide();
            $('.close-btn').show();
            
            //清空消息列表
            $(".msg-list").empty().html(empty_html);
        };
    });
    
    //断开链接
    $(".conn-btn").click(function(){
        ws.close(); //关闭连接
        $('.conn-btn').hide();
        $('.close-btn').show();

        //清空消息列表
        $(".msg-list").empty().html(empty_html);
    });    
});