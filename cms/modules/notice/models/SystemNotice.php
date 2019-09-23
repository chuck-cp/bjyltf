<?php

namespace cms\modules\notice\models;


use Yii;
use cms\models\MemberMessage;
use cms\modules\notice\models\SystemBanner;
use yii\base\Exception;

/**
 * This is the model class for table "{{%system_notice}}".
 *
 * @property int $id
 * @property string $title 公告标题
 * @property string $image_url 图片地址
 * @property string $content 公告内容
 * @property int $top 推送到首页
 * @property string $create_at 创建时间
 */
class SystemNotice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['top', 'create_user_id', 'status'], 'integer'],
            [['create_at'], 'safe'],
            [['title'], 'string', 'max' => 200],
            [['create_user'], 'string', 'max' => 20],
            [['image_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'image_url' => '图片',
            'content' => '内容',
            'top' => 'Top',
            'create_at' => '发布日期',
            'create_user' => '发布者',
            'create_user_id' => 'Create User Id',
            'status' => '状态',
        ];
    }
    /**
     * 写入用户推送消息表
     */
    public function saveNotice($type=[]){
        try{
            $this->create_user_id = Yii::$app->user->identity->getId();
            $this->create_user = Yii::$app->user->identity->username;
            $this->save();
            $notice_id = Yii::$app->db->getLastInsertID();
            //是否推送至banner
            //ToolsClass::p($type);die;
            if($this->image_url && !empty($type)){
                foreach ($type as $v){
                    $BannerModel = new SystemBanner();
                    $BannerModel->image_url = $this->image_url;
                    //url
                    $host = YII_ENV == 'dev' ? 'tap' : 'wap';
                    $BannerModel->link_url = $host.'.bjyltf.com/message/'.$notice_id.'?type=notice';
                    $BannerModel->sort = 0;
                    $BannerModel->type = $v;
                    $result = $BannerModel->save(false);
                }
            }

            //存入member_massage
            $MemberMessageModel = new MemberMessage();
            $MemberMessageModel->member_id = 0;
            $MemberMessageModel->notice_id = $notice_id;
            $MemberMessageModel->title = $this->title;
            $MemberMessageModel->content = $this->content;
            $MemberMessageModel->message_type = 1;
            $MemberMessageModel->status = 0;
            $MemberMessageModel->save();
            return $MemberMessageModel->id;
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            return false;
        }
    }
    /**
     * 管理员删除notice
     */
    public function deleteNotice($model){
        $msgModel = MemberMessage::findOne(['notice_id'=>$model->id]);
        if(!$model || !$msgModel){
            return false;
        }else{
            try{
                $model->status = 0;
                $model->save();
                $msgModel->delete();
                return true;
            }catch (Exception $e){
                Yii::error($e->getMessage(),'error');
                return false;
            }



        }
    }
}
