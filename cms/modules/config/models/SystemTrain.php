<?php

namespace cms\modules\config\models;

use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "yl_system_train".
 *
 * @property string $id
 * @property string $number 素材编号
 * @property string $name 素材名称
 * @property int $type 素材类型(1、图片 2、视频)
 * @property int $status 状态(1、使用中 2、停用)
 * @property int $sort 排序(大的在前)
 * @property string $thumbnail 缩略图地址
 * @property string $create_at 创建日期
 * @property string $create_user_name 上传人名称
 * @property string $create_user_id 上传人ID
 */
class SystemTrain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_system_train';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'name','thumbnail', 'create_user_name', 'create_user_id'], 'required'],
            [['type', 'status', 'sort', 'create_user_id'], 'integer'],
            [['create_at','video_id','content'], 'safe'],
            [['name'], 'string', 'max' => 60],
            [[ 'thumbnail'], 'string', 'max' => 255],
            [['create_user_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '资料名称',
            'type' => '资料类型',
            'status' => '状态',
            'sort' => '排序',
            'thumbnail' => '缩略图',
            'create_at' => '创建日期',
            'create_user_name' => '上传人',
            'create_user_id' => 'Create User ID',
            'content' => '内容',
        ];
    }
    public function add($data){
        $this->create_at=date('Y-m-d H:i:s');
        $this->create_user_name=Yii::$app->user->identity->username;
        $this->create_user_id=Yii::$app->user->identity->getId();
        $this->name=$data['SystemTrain']['name'];
        $this->thumbnail=$data['SystemTrain']['thumbnail'];
        if($data['type']==1){
            $this->content=$data['SystemTrain']['content'];
        }else{
            $this->content=$data['content'];
        }
        $this->type=$data['type'];
        if($this->save())
            return true;
        return false;
    }

    public function edit($data){
        $dataRes['name']=$data['SystemTrain']['name'];
        $dataRes['thumbnail']=$data['SystemTrain']['thumbnail'];
        if($data['type']==1){
            if($data['SystemTrain']['content']!==''){
                $dataRes['content']=$data['SystemTrain']['content'];
            }
        }else{
            if($data['content']!==''){
                $dataRes['content']=$data['content'];
            }
        }
        $id=$data['SystemTrain']['id'];
        if($this::UpdateAll($dataRes,['id'=>$id])!==false){
            return true;
        }else{
            return false;
        }


    }
    /**
     * 获取类型或状态
     */
    public static function getTypeStatus($type,$number){
        $srr = [];
        switch ($type){
            case 'type':
                $srr = [
                    '1' => '图片',
                    '2' => '视频',
                ];
                break;
            case 'status':
                $srr = [
                    '1' => '使用中',
                    '2' => '停用',
                ];
                break;
        }
        return array_key_exists($number,$srr) ? $srr[$number] : '未设置';
    }
}
