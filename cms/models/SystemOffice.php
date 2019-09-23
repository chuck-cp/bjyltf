<?php

namespace cms\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "yl_system_office".
 *
 * @property string $id
 * @property string $office_name 办事处名称
 * @property string $storehouse 仓库名称(多个仓库以逗号分割)
 */
class SystemOffice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_system_office';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['office_name'], 'required'],
            [['office_name'], 'string', 'max' => 30],
            [['storehouse'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'office_name' => 'Office Name',
            'storehouse' => 'Storehouse',
        ];
    }

    /**
     * 添加办事处
     */
    public static function addOffice($data){
        $model=new SystemOffice();
        $model->office_name=$data['SystemOffice']['office_name'];
        $model->storehouse=implode(',',$data['SystemOffice']['storehouse']);
        if($model->save())
            return json_encode(['code'=>1,'msg'=>'添加成功']);
        return json_encode(['code'=>2,'msg'=>'添加失败']);
    }

    /**
     * 编辑办事处
     */
    public static function editOffice($data,$id){
        $model=SystemOffice::findOne($id);
        $model->office_name=$data['SystemOffice']['office_name'];
        $model->storehouse=implode(',',$data['SystemOffice']['storehouse']);
        if($model->save()!==false)
            return json_encode(['code'=>1,'msg'=>'编辑成功']);
        return json_encode(['code'=>2,'msg'=>'编辑失败']);
    }

    /**
     * 搜索获取办事处名称
     */
    public static function officeSearch(){
        $officeModel = self::find()->select('id,office_name')->asArray()->all();
        $officeModel = ArrayHelper::map($officeModel,'id','office_name');
        return $officeModel;
    }
    /**
     * 搜索获取办事处名称
     */
    public static function storehouse($id){
        $storehouse = self::find()->where(['id'=>$id])->select('id,storehouse')->asArray()->one();
        if(empty($storehouse)){
            return[];
        }
        return explode(',',$storehouse['storehouse']);
    }

}
