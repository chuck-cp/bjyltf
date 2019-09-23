<?php

namespace cms\modules\sign\models;

use cms\modules\member\models\Member;
use Yii;
use \PHPExcel;
use PHPExcel_Worksheet_Drawing;
/**
 * This is the model class for table "yl_sign".
 *
 * @property string $id
 * @property string $team_id 团队ID
 * @property int $team_type 团队类型(1、业务 2、维护)
 * @property string $member_id 签到人的用户ID
 * @property int $team_member_type 成员类型(1、普通成员 2、负责人 3、管理人)
 * @property string $team_name 团队名称
 * @property string $member_name 签到人员的姓名
 * @property string $member_avatar 用户头像
 * @property string $shop_name 店铺名称
 * @property string $shop_address 店铺位置
 * @property int $frist_sign 是否是当天首次签到(1、是)
 * @property int $late_sign 是否超时签到(1、是)
 * @property int $late_time 超时时间(分钟)
 * @property string $create_at 签到时间
 */
class Sign extends \yii\db\ActiveRecord
{
    public $totalmongo_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'team_name', 'member_name', 'member_avatar', 'shop_name', 'shop_address'], 'required'],
            [['team_id', 'team_type', 'member_id', 'team_member_type', 'frist_sign', 'late_sign', 'late_time'], 'integer'],
            [['create_at'], 'safe'],
            [['team_name', 'member_name'], 'string', 'max' => 50],
            [['member_avatar'], 'string', 'max' => 255],
            [['shop_name'], 'string', 'max' => 100],
            [['shop_address'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_id' => 'Team ID',
            'team_type' => 'Team Type',
            'member_id' => 'Member ID',
            'team_member_type' => 'Team Member Type',
            'team_name' => 'Team Name',
            'member_name' => 'Member Name',
            'member_avatar' => 'Member Avatar',
            'shop_name' => 'Shop Name',
            'shop_address' => 'Shop Address',
            'frist_sign' => 'Frist Sign',
            'late_sign' => 'Late Sign',
            'late_time' => 'Late Time',
            'create_at' => 'Create At',
        ];
    }

    //关联业务员签到信息表
    public function getSignBusiness(){
        return $this->hasOne(SignBusiness::className(),['sign_id'=>'id']);
    }
    //关联维护员签到信息表
    public function getSignMaintain(){
        return $this->hasOne(SignMaintain::className(),['sign_id'=>'id']);
    }
    //关联维护员签到信息表
    public function getMemberMobile(){
        return $this->hasOne(Member::className(),['id'=>'member_id'])->select('id,mobile');
    }
    //获取职务
    public function getMemberType(){
        return $this->hasOne(SignTeamMember::className(),['member_id'=>'member_id'])->select('id,member_id,member_type');
    }

    /**
     * @param $data
     * @param $type
     * 数据处理
     */
    public static function exportData($data=[],$type){
        if($type==1){
            $filename="业务签到信息";
            $headArr=['所属团队','姓名','日期','签到时间','签到地点','备注','超时时间','是否为首次签到','店铺名称','有无其他公司设备','图片1','图片2'];
            foreach($data as $k=>$v){
                $excelArr[$k]['team_name']=$v['team_name'];
                $excelArr[$k]['member_name']=$v['member_name'];
                $excelArr[$k]['date']=date('Y-m-d',strtotime($v['create_at']));
                $excelArr[$k]['create_at']=$v['create_at'];
                $excelArr[$k]['shop_address']=$v['shop_address'];
                $excelArr[$k]['description']=$v['signBusiness']['description'];
                if($v['late_time']==0){
                    $excelArr[$k]['late_time']='';
                }else{
                    $excelArr[$k]['late_time']=$v['late_time']<=60?$v['late_time'].'分钟':self::Timechange($v['late_time']);
                }
                $excelArr[$k]['first_sign']=$v['first_sign']==1?'是':'否';
                $excelArr[$k]['shop_name']=$v['shop_name'];
                $excelArr[$k]['screen_number']=$v['signBusiness']['screen_number']==0?'无':'有';
                $imgs = SignImage::signImg($v['id'],1);
                if(count($imgs)>1){
                    $excelArr[$k]['imgs']=$imgs[0];
                    $excelArr[$k]['imgs1']=$imgs[1];
                }else{
                    $excelArr[$k]['imgs']=$imgs[0];
                    $excelArr[$k]['imgs1']='';
                }
            }
        }else if($type==2){
            $filename="维护签到信息";
            $headArr=['所属团队','姓名','日期','签到时间','签到地点','备注','超时时间','是否为首次签到','店铺名称','有无其他公司设备','图片1','图片2'];
            foreach($data as $k=>$v){
                $excelArr[$k]['team_name']=$v['team_name'];
                $excelArr[$k]['member_name']=$v['member_name'];
                $excelArr[$k]['date']=date('Y-m-d',strtotime($v['create_at']));
                $excelArr[$k]['create_at']=$v['create_at'];
                $excelArr[$k]['shop_address']=$v['shop_address'];
                $excelArr[$k]['description']=$v['signMaintain']['description'];
                if($v['late_time']==0){
                    $excelArr[$k]['late_time']='';
                }else{
                    $excelArr[$k]['late_time']=$v['late_time']<=60?$v['late_time'].'分钟':self::Timechange($v['late_time']);
                }
                $excelArr[$k]['first_sign']=$v['first_sign']==1?'是':'否';
                $excelArr[$k]['shop_name']=$v['shop_name'];
                $excelArr[$k]['screen_number']='有';
                $imgs = SignImage::signImg($v['id'],2);
                if(count($imgs)>1){
                    $excelArr[$k]['imgs']=$imgs[0];
                    $excelArr[$k]['imgs1']=$imgs[1];
                }else{
                    $excelArr[$k]['imgs']=$imgs[0];
                    $excelArr[$k]['imgs1']='';
                }
            }
        }
        self::exportExcel($excelArr,$headArr,$filename);

    }

    //导出excel
    public static function exportExcel($data,$headArr,$filename){
        try{

            $date = date('Ymd-His');
            $filename .= "-{$date}.xls";
            //创建PHPExcel对象
            $objPHPExcel = new \PHPExcel();
            //设置单元格的宽度
           /* $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8);*/

            //设置表头
            $key = ord("A");
            foreach($headArr as $v){
                $colum = chr($key);
                $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
                $key += 1;
            }
            $column = 2;
            $objActSheet = $objPHPExcel->getActiveSheet();
            foreach($data as $key => $rows){ //行写入
                $span = ord("A");
                foreach($rows as $keyName=>$value){// 列写入
                    $j = chr($span);
                    if(strpos($keyName,'imgs') !== false){
                        if($value){
                            $objActSheet->setCellValue($j.$column, '店面图')->getCell($j.$column)->getHyperlink()->setUrl($value);
                        }else{
                            $objActSheet->setCellValue($j.$column, '暂无图片');
                        }
                    }else{
                        $objActSheet->setCellValue($j.$column, $value);
                    }
                    $span++;
                }
                $column++;
            }
            $filename = iconv("utf-8", "gb2312", $filename);
            //重命名表
            // $objPHPExcel->getActiveSheet()->setTitle('test');
            //设置活动单指数到第一个表,所以Excel打开这是第一个表
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output'); //文件通过浏览器下载
            exit;
        }catch (Exception $e){
            Yii::error("[Excel]".$e->getMessage());
        }

    }

    //将超时签到时间转化为小时分钟
    public static function Timechange($time){
        $time = $time*60;
        $d = floor($time / (3600*24));
        $h = floor(($time % (3600*24)) / 3600);
        $m = floor((($time % (3600*24)) % 3600) / 60);
        if($d>'0'){
            $datetime = $d.'天'.$h.'小时'.$m.'分钟';
        }else{
            if($h!='0'){
                $datetime =  $h.'小时'.$m.'分钟';
            }else{
                $datetime =  $m.'分钟';
            }
        }
        return $datetime;
    }
}
