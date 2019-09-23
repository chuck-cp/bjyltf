<?php
namespace cms\core;
use yii\base\Action;

class CmsAction extends Action
{
    public function checkSaveResult($result,$url=''){
        if($result !== true && $result !== false && !is_numeric($result)){
            echo $this->showMessage($result,'error');
        }else{
            if($result){
                echo $this->showMessage('操作成功!','success',['url'=>$url]);
            }else{
                echo $this->showMessage('操作失败!','error',['url'=>$url]);
            }
        }

    }
    public function showMessage($message = null, $title = '提示',$params=[])
    {
        if ($message === null)
        {
            $message = '权限不足，无法进行此项操作';
        }
        if($title == 'error' || $title == 'success'){
            $params['close_time'] = 3;
        }
        if(is_array($message)){
            $message = Json::encode($message);
        }
        $params=array_merge(['title'=>$title,'message'=>$message],$params);
        return $this->controller->render('//site/message',$params);
    }
}
