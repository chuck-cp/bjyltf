<?php

namespace cms\modules\config\models;

use Yii;

/**
 * This is the model class for table "{{%system_version}}".
 *
 * @property string $id
 * @property int $app_type 应用类型(1、安卓 2、IOS)
 * @property int $upgrade_type 升级类型(1、强制升级 2、不强制)
 * @property string $version 版本号
 * @property string $url 下载地址
 * @property string $desc 版本描述
 * @property string $create_at 发布日期
 */
class SystemVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_version}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
       /* return [
            [['app_type', 'upgrade_type', 'version'], 'required'],
            [['app_type', 'upgrade_type','version_type'], 'integer'],
            [['create_at','status'], 'safe'],
            [['version'], 'string', 'max' => 10],
            [['create_user'], 'string', 'max' => 50],
            [['url', 'desc'], 'string', 'max' => 255],
            [['app_type', 'version'], 'unique', 'targetAttribute' => ['app_type', 'version']],
        ];*/
        return [
            [['app_type', 'upgrade_type', 'version'], 'required'],
            [['app_type', 'upgrade_type', 'version_type', 'status'], 'integer'],
            [['create_at'], 'safe'],
            [['version'], 'string', 'max' => 10],
            [['url', 'desc'], 'string', 'max' => 255],
            [['create_user'], 'string', 'max' => 50],
            [['app_type', 'version'], 'unique', 'targetAttribute' => ['app_type', 'version']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_type' => '应用类型',
            'upgrade_type' => '升级类型',
            'version' => '版本号',
            'url' => '应用地址',
            'desc' => '版本描述',
            'create_at' => '更新日期',
            'create_user' => '发布者',
            'status' => '状态',
            'version_type' => '版本类型',
        ];
    }

    public static function getcount($app_type,$version){
        $query=SystemVersion::find();
        $query->andFilterWhere([
            'app_type' => $app_type,
            'version'=>$version
        ]);
        return $count=$query->count();
        if($count==0){

            return true;
        }else{
            return false;
        }
    }
}
