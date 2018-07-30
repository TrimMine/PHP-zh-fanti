<?php
/**
 * Date: 2018/7/11
 * Time: 16:17
 */

namespace translate;

use translate\Translate;

//TODO 翻译  没有放到项目中
class Lang
{
    protected $zh = "zh-CHS"; //中文
    protected $en = "EN"; //英文
    protected $fan = "fanTi"; //英文
    protected $lang = ''; //英文
    protected $msg = ''; //修改的信息
    protected $conf = ''; //繁体配置文件
    protected $fail_msg = 'no message!'; //没有查询到对应翻译
    protected $file_path = ''; //文件路径
    protected $write_data = [];//写入数据

    public function __construct($msg, $lang)
    {
        $this->msg = $msg;
        $this->lang = $lang;

    }

    public function langType()
    {
        if ($this->lang == $this->fan) {
            $this->conf = \opencc_open("s2t.json"); //传入配置文件名
            return $this->fanTi();
        }
        if ($this->lang == $this->en) {
            return $this->translate();
        }
    }

    //简体转繁体
    public function fanTi()
    {
        if (!is_array($this->msg)) {
            $message = \opencc_convert($this->msg, $this->conf);
            return $message;
        } else {
            $message = \opencc_convert($this->msg[0], $this->conf);
            if (!is_array($this->msg[1])) {
                $data = $this->isArr([$this->msg[1]]);
            } else {
                $data = $this->isArr($this->msg[1]);
            }
            return [$message, $data];
        }

    }

    //数组处理
    private function isArr($msg)
    {
        foreach ($msg as $k => $v) {
            if (is_array($v)) {
                $msg[$k] = $this->isArr($v);
            } else {
                //是汉字
                if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $v)) {
                    $msg[$k] = \opencc_convert($v, $this->conf);
                }
            }
        }
        return $msg;
    }

    //英文翻译处理
    public function translate()
    {
        $path = substr($_SERVER['PATH_INFO'], 1);
        $len = strpos($path, '/');
        $file_path = substr_replace($path, 'lang/translate/', $len + 1, 0) . '.json';
        $dir_name = dirname($file_path);
        $dir_path = APP_PATH . $dir_name;
        $this->file_path = APP_PATH . $file_path;
        //目录是否存在
        if (!file_exists($dir_path) && !is_dir($dir_path)) {
            if (!mkdir($dir_path, 0777, true)) return '创建失败!';
        }
        //文件是否存在
        if (!file_exists($this->file_path)) {
            file_put_contents($this->file_path, '');
        }
        return $this->translation();
    }

    //翻译写入
    public function translation()
    {
        $obj_translate = new Translate();
        //翻译内容
        $this->msg = $this->doTranslate($obj_translate, $this->msg);
        if (count($this->write_data) > 0) {
            //写入文件
            $this->writeFile($this->write_data);
        }
        if (is_array($this->msg) && (count($this->msg) == 2)) {
            return [$this->msg[0], $this->msg[1]];
        } else {
            return [$this->msg];
        }

    }

    //来时翻译判断是否是多层数组
    private function doTranslate($obj_translate, $msg)
    {
        if (is_array($msg)) {
            $msg = $this->doTranslateArr($obj_translate, $msg);
        } else {

            $return_data = $obj_translate->translate($msg, $this->zh, $this->en);

            $msg = $this->makeData($return_data, $msg);
        }
        return $msg;
    }

    //数组处理
    private function doTranslateArr($obj_translate, $msg)
    {
        foreach ($msg as $k => $v) {
            if (is_array($v)) {
                $msg[$k] = $this->doTranslateArr($obj_translate, $v);
            } else {
                //是汉字且不在文件中存在
                if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $v)) {
                    if ($this->isInFile($v) === false) {
                        $return_msg = $obj_translate->translate($v, $this->zh, $this->en);
                        $msg[$k] = $this->makeData($return_msg, $v);
                    } else {
                        $msg[$k] = $this->readFile($v);
                    }
                }
            }
        }
        return $msg;
    }

    //写入文件 追加写入
    protected function writeFile($data)
    {
        $json_data = json_decode(file_get_contents($this->file_path));
        if ($json_data == '') {
            $json_data = [];
        }
        if (is_object($json_data)) {
            $json_data = objToArray($json_data);
        }
        $arr = json_encode(array_merge($json_data, $data));
        file_put_contents($this->file_path, $arr);
    }

    //读取文件
    protected function readFile($v)
    {
        $json_data = json_decode(file_get_contents($this->file_path), true);
        if ($key = array_search($v, $json_data)) {
            return $key;
        } else {
            return $this->fail_msg;
        }
    }

    //是否在文件中已经存在
    protected function isInFile($val)
    {
        $json_data = json_decode(file_get_contents($this->file_path), true);
        if ($json_data == '') {
            $json_data = [];
        }
        if (in_array($val, $json_data)) {
            return true;
        } else {
            return false;
        }

    }

    //组装翻译后的返回信息
    protected function makeData($return_msg, $v)
    {
        if (isset($return_msg['translation']) && isset($return_msg['translation'][0])) {
            $this->write_data[$return_msg['translation'][0]] = $v;
            $msg = $return_msg['translation'][0];
        } else {
            $msg = $this->fail_msg;
        }
        return $msg;
    }
}