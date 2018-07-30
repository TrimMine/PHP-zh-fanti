<?php


if (!function_exists('msg')) {
    function msg($status = 200, $message = '操作成功!', $data = '')
    {
        if (!$data) {
            //单个翻译
            $message = (new \translate\Lang($message,'fanTi'))->langType();
            return json(['status' => $status, 'message' => $message]);
        } else {
            //有数据的情况下 处理data,data可以是数组
            list($message, $data) = (new \translate\Lang([$message, $data], 'fanTi'))->langType();
            return json(['status' => $status, 'message' => $message, 'data' => $data]);
        }
    }
}


