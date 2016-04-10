<?php
/**
 * 公共函数
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016-04-10
 */

/**
 * 获取当前访问的uri(只解析两级)
 */
function curr_uri() {
    $request_uri = $_SERVER['REQUEST_URI'];
    !$request_uri && $request_uri = 'XD*LSf&';    
    
    if ($request_uri == '/' || $request_uri == '/index' || $request_uri == '/index/') {
        $request_uri = '/index/index';
    }
        
    //解析uri
    $uri_arr = explode("?", $request_uri);
    $path_arr = explode("/", $uri_arr[0]);
    $path = '/' . $path_arr[1] . '/' . $path_arr[2];
    
    return $path;
}

/**
 * 返回数据
 * @param type $ResponseCode    响应码
 * @param type $ResponseMsg     响应消息
 * @param type $ResponseData    响应数据
 */
function ReturnMsg($ResponseCode = 999,$ResponseMsg = '调用成功',$ResponseData = array()){
    if(!is_numeric($ResponseCode)) {
        return false;
    }
    $result = array(
        'Code'=>$ResponseCode,
        'Msg'=>$ResponseMsg,
        'Data'=>$ResponseData
    );
    return $result;
}

/**
 * 接口数据响应
 * @param type $ResponseCode    响应码
 * @param type $ResponseMsg     响应消息
 * @param type $ResponseData    响应数据
 * @param type $ResponseType    响应数据类型
 */
function Response($ResponseCode = 999,$ResponseMsg = '接口请求成功',$ResponseData = array(),$ResponseType = 'json'){
    if(!is_numeric($ResponseCode)) {
        return '';
    }
    
    $ResponseType = isset($_GET['format']) ? $_GET['format'] : $ResponseType;
    
    $result = array(
        'Code'=>$ResponseCode,
        'Msg'=>$ResponseMsg,
        'Data'=>$ResponseData
    );
    
    if($ResponseType == 'json') {
        json($ResponseCode, $ResponseMsg, $ResponseData);
        exit();
    } else if($ResponseType == 'xml') {
        xmlencode($ResponseCode, $ResponseMsg, $ResponseData);
        exit();
    } else if($ResponseType == 'array') {
        var_dump($result);
        exit();
    } else {
        json($ResponseCode, $ResponseMsg, $ResponseData);
        exit();
    }
}

/**
 * 响应Json格式数据
 * @param type $ResponseCode    响应码
 * @param type $ResponseMsg     响应消息
 * @param type $ResponseData    响应数据
 */
function json($ResponseCode = 999,$ResponseMsg = '接口请求成功',$ResponseData = array()){
    if(!is_numeric($ResponseCode)) {
        return '';
    }
    
    $result = array(
        'Code'=>$ResponseCode,
        'Msg'=>$ResponseMsg,
        'Data'=>$ResponseData,
        'Type'=>'json'
    );
    header("Content-type: text/html; charset=utf-8");
    echo json_encode($result);
    exit();
}

/**
 * 响应xml格式数据
 * @param type $ResponseCode    响应码
 * @param type $ResponseMsg     响应消息
 * @param type $ResponseData    响应数据
 */
function xmlencode($ResponseCode = 999,$ResponseMsg = '接口请求成功',$ResponseData = array()) {
    if (!is_numeric($ResponseCode)) {
        return '';
    }

    $result = array(
        'Code'=>$ResponseCode,
        'Msg'=>$ResponseMsg,
        'Data'=>$ResponseData,
        'Type'=>'xml'
    );

    header("Content-Type:text/xml");
    $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
    $xml .= "<root>\n";

    $xml .= xml_to_encode($result);

    $xml .= "</root>";
    echo $xml;
}

/**
 * 将数据编码成xml格式
 * @param type $data
 * @return type
 */
function xml_to_encode($data) {
    $xml = $attr = "";
    foreach($data as $key => $value) {
        if(is_numeric($key)) {
            $attr = " id='{$key}'";
            $key = "item";
        }
        $xml .= "<{$key}{$attr}>";
        $xml .= is_array($value) ? xml_to_encode($value) : $value;
        $xml .= "</{$key}>\n";
    }
    return $xml;
}