@echo off  
echo server starting...
php start_channel.php start
php start_websocket.php start
php start_http.php start