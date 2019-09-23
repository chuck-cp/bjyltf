<?php

/**
 *  Csv导出，数据处理类
 */

namespace common\libs;
use cms\modules\examine\models\ShopScreenReplace;
use cms\modules\shop\models\Shop;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\ShopLable;
use cms\modules\member\models\MemberInfo;
use cms\models\SystemAddress;
class CsvClass
{
    /**
     * [CsvDataWriting 公共导出csv]
     * @param array  $data      [数据集]
     * @param array  $title     [表头]
     * @param string $fileName  [文件名]
     * @param        $is_header [真 假 是否取表头 解决循环写入问题]
     */
    public static function CsvDataWriting($data=array(), $title=array(), $fileName='' ,$is_header=true){
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        // buffer计数器
        $cnt = 0;
        // 逐行取出数据，不浪费内存
        $fp = fopen('../runtime/'.$fileName, 'a+'); //没有则生成临时文件，已有文件在文件中继续追加数据
        /*        chmod($fileName,777);//修改可执行权限*/
        if($is_header){
            // 将数据通过fputcsv写到文件句柄
            foreach ($title as $v){
                $tit[]=iconv('UTF-8', 'GBK//IGNORE',$v);
            }
            fputcsv($fp,$tit);
        }
        foreach ($data as $v){
            $cnt++;
            if($limit==$cnt){
                ob_flush();
                flush();
                $cnt=0;
            }
            foreach ($v as $t){
                $tarr[]=iconv('UTF-8', 'GBK//IGNORE',$t);
            }
            fputcsv($fp,$tarr);
            unset($tarr);
        }
        unset($data);
        //fclose($fp);  //每生成一个文件关闭
    }

    /**
     * [CsvDownload 网页下载CSV文件并将临时文件删除]
     * @param $filename [文件名]
     */
    public static function CsvDownload($filename){
        header("Cache-Control: max-age=0");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename('../runtime/'.$filename)); // 文件名
        header('Content-Type: application/vnd.ms-excel' );
        header("Content-Transfer-Encoding: binary"); //
        header('Content-Length: ' . filesize('../runtime/'.$filename)); //
        @readfile('../runtime/'.$filename);//输出文件;
        unlink('../runtime/'.$filename); //删除临时文件
        die;
    }

    /**
     * [SettleCenterInstllDate  结算中心-线下收款数据处理]
     * @param array $data [需要做处理的数据集]
     * @return array
     */
    public static function SettleCenterInstllData($data=[]){
        foreach ($data as $k=>$v){
            $csv[$k]['id']=$v['id'];
            $csv[$k]['name']=$v['name'];
            $csv[$k]['area_name_address']=$v['area_name'].$v['address'];
            $csv[$k]['shop_member_id']=$v['shopreplace']['shop_member_id'];
            $csv[$k]['apply_name']=$v['shopreplace']['apply_name'];
            $csv[$k]['apply_mobile']=$v['shopreplace']['apply_mobile'];
            $csv[$k]['create_at']=$v['create_at'];
            $csv[$k]['install_finish_at']=$v['install_finish_at'];
            $csv[$k]['apply_brokerage']=ToolsClass::priceConvert($v['apply']['apply_brokerage']);
            $csv[$k]['member_id']=$v['member_id'];
            $csv[$k]['member_name']=$v['member_name'];
            $csv[$k]['member_mobile']=$v['member_mobile'];
            $csv[$k]['member_price']=ToolsClass::priceConvert($v['member_price']);
            $csv[$k]['member_reward_price']=ToolsClass::priceConvert($v['member_reward_price']);
            $csv[$k]['member_price_tatol']=ToolsClass::priceConvert($v['member_price']+$v['member_reward_price']);
            $csv[$k]['introducer_member_id']=$v['introducer_member_id'];
            $csv[$k]['introducer_member_name']=$v['introducer_member_name'];
            $csv[$k]['introducer_member_mobile']=$v['introducer_member_mobile'];
            $csv[$k]['introducer_member_price']=$v['introducer_member_price']==0?'---':ToolsClass::priceConvert($v['introducer_member_price']);
            $csv[$k]['parent_id']=$v['parentMember']['id'];
            $csv[$k]['parent_name']=$v['parentMember']['name'];
            $csv[$k]['parent_mobile']=$v['parentMember']['mobile'];
            $csv[$k]['parent_member_price']=ToolsClass::priceConvert($v['parent_member_price']);
            $csv[$k]['install_member_id']=$v['install_member_id'];
            $csv[$k]['install_member_name']=$v['install_member_name'];
            $csv[$k]['install_mobile']=$v['install_mobile'];
            if($v['shopreplace']['replace_screen_number']){
                $csv[$k]['install_price']=ToolsClass::priceConvert($v['install_price']/$v['shopreplace']['replace_screen_number']);
            }else{
                $csv[$k]['install_price']=0;
            }
            $screennum = ShopScreenReplace::find()->where(['shop_id'=>$v['id'],'maintain_type'=>1])->select('replace_screen_number')->asArray()->one();
            $csv[$k]['screen_number']=$screennum['replace_screen_number'];
            $csv[$k]['tolprice']=ToolsClass::priceConvert($v['install_price']);
            $csv[$k]['sum']=ToolsClass::priceConvert($v['apply']['apply_brokerage']+$v['member_price']+$v['member_reward_price']+$v['install_price']+$v['parent_member_price']+$v['introducer_member_price']);
        }
        return $csv;
    }

    /**
     * [ShopIndexData 商家信息数据处理]
     * @param $data [数据集]
     * @return mixed
     */
    public static function ShopIndexData($data){
        foreach($data as $k=>$v){
            $csv[$k]['id']=$v['id'];//商家编号
            $csv[$k]['member_id']=$v['member_id'];//用户ID
            $csv[$k]['member_name']=$v['member_name'];//业务合作人
            $csv[$k]['member_mobile']=$v['member_mobile'];//业务合作人手机号
            $csv[$k]['admin_member_id']=$v['admin_member_id'];//管理人ID
            $csv[$k]['shop_image']=$v['shop_image'];//店铺门脸
            $csv[$k]['name']=$v['name'];//店铺名称
            $csv[$k]['province']=$v['shop_province'];//省
            $csv[$k]['city']=$v['shop_city'];//市
            $csv[$k]['area']=$v['shop_area'];//区
            $csv[$k]['Street']=$v['shop_street'];//街道
            $csv[$k]['area_name']=$v['area_name'];//店铺所在地区
            $csv[$k]['address']=$v['address'];//详细地址
            $csv[$k]['apply_screen_number']=$v['apply_screen_number'];//申请数量
            $csv[$k]['screen_number']=$v['screen_number'];//实际屏幕数量
            $csv[$k]['error_screen_number']=$v['error_screen_number'];//故障数量
            $csv[$k]['status']=Shop::getStatusByNum($v['status']);//申请状态
            $csv[$k]['screen_status']=Screen::getScreenStatus($v['screen_status']);
            $csv[$k]['acreage']=$v['acreage'];//店铺面积
            $csv[$k]['apply_client']=$v['apply_client'];//申请客户端
            $csv[$k]['mirror_account']=$v['mirror_account'];//镜面数量
            $csv[$k]['shop_type']=$v['shop_type'];//入驻方式//店铺类型
            $csv[$k]['apply_name']=$v['apply']['apply_name'];//申请人姓名
            $csv[$k]['apply_mobile']=$v['apply']['apply_mobile'];//申请人手机号
            $csv[$k]['create_at']=$v['create_at'];//申请时间
            $csv[$k]['shop_examine_at']=$v['shop_examine_at'];//店铺审核通过时间
            $csv[$k]['install_member_name']=$v['install_member_name'];//安装人
            $csv[$k]['install_mobile']=$v['install_mobile'];//安装人电话
            $csv[$k]['install_finish_at']=$v['install_finish_at'];//店铺安装完成时间
            $csv[$k]['lable']=ShopLable::listlable($v['lable_id']);//标签
        }
        return $csv;
    }

    /**
     * [MemberWithdrawWithdrawData 提现管理-提现记录数据处理]
     * @param $data [数据集]
     * @return mixed
     */
    public static function MemberWithdrawWithdrawData($data){
        foreach($data as $k=>$v){
            $csv[$k]['id']=$v['id'];
            $csv[$k]['serial_number']=$v['serial_number']."\t";
            $csv[$k]['create_at']=$v['create_at'];
            $csv[$k]['member_id']=$v['member_id'];
            $csv[$k]['member_name']=$v['member_name'];
            $csv[$k]['mobile']=$v['mobile']."\t";
            $csv[$k]['bank_name']=$v['bank_name'];
            $csv[$k]['payee_name']=$v['payee_name'];
            $csv[$k]['id_number']=$v['memberinfo']['id_number']."\t"?$v['memberinfo']['id_number']."\t":'---';
            $csv[$k]['account_type']=$v['account_type']==1?'个人':'公司';
            $csv[$k]['bank_account']=$v['bank_account'].",";
            $csv[$k]['bank_mobile']=$v['bank_mobile']."\t";
            $csv[$k]['status']=$v['examine_status'] == 3 && $v['examine_result'] == 1 ? '提现失败' : '提现成功';
            $csv[$k]['price']=number_format($v['price']/100,2);
            $csv[$k]['poundage']=number_format($v['poundage']/100,2);
            $csv[$k]['account_balance']=number_format($v['account_balance']/100,2);
        }
        return $csv;
    }

    /**
     * [getMemberIndexData 业务查询-人员查询导出]
     * @param $data [数据集]
     * @return mixed
     */
    public static function getMemberIndexData($data){
        foreach ($data as $k=>$v){
            $Csv[$k]['id']=$v['id'];//序号
            $Csv[$k]['name']=$v['name'];//姓名
            $Csv[$k]['id_number']=$v['memIdcardInfo']['id_number']."\t";//身份证号
            $Csv[$k]['mobile']=$v['mobile'];//手机
            $Csv[$k]['area']=SystemAddress::getAreaNameById($v['area']);//所属地区
            $Csv[$k]['admin_area']=SystemAddress::getAreaByIdLen($v['admin_area'],9);//业务区域
            $Csv[$k]['count_price']=ToolsClass::priceConvert($v['memberAccount']['count_price']);//收益总额
            $Csv[$k]['shop_number']=$v['memberAccount']['shop_number']?$v['memberAccount']['shop_number']:0;//联系店家数量
            $Csv[$k]['screen_number']=$v['memberAccount']['screen_number']?$v['memberAccount']['screen_number']:0;//联系LED数量
            $Csv[$k]['install_shop_number']=$v['memberAccount']['install_shop_number']?$v['memberAccount']['install_shop_number']:0;//安装商家数量
            $Csv[$k]['install_screen_number']=$v['memberAccount']['install_screen_number']?$v['memberAccount']['install_screen_number']:0;//安装LED数量
            $Csv[$k]['inside']=$v['inside']==1?'是':'否';//是否为内部人员
            $Csv[$k]['electrician_examine_status']=$v['memIdcardInfo']['electrician_examine_status']==1?'是':'否';//是否为电工
            $Csv[$k]['company_electrician']=$v['memIdcardInfo']['company_electrician']==1?'是':'否';//是否为内部电工
            //是否为合作推广人
            if($v['inside']==1){
                $Csv[$k]['inviter'] = '否';
            }else{
                if($v['parent_id']>0){
                    $Csv[$k]['inviter'] = '是';
                }else{
                    $Csv[$k]['inviter'] = '否';
                }
            }
        }
        return $Csv;
    }


    /**
     * [getMaintainPrice 业务查询-每月维护费用支出]
     * @param $data [数据集]
     * @return mixed
     */
    public static function getMaintainPrice($data){
        foreach($data as $k=>$v){
            $Csv[$k]['id']=$v['id'];
            $Csv[$k]['shop_id']=$v['shop_id'];
            $Csv[$k]['shop_name']=$v['shop_name'];
            $Csv[$k]['area_name']=$v['area_name'];
            $Csv[$k]['apply_id']=$v['apply_id'];
            $Csv[$k]['apply_name']=$v['apply_name'];
            $Csv[$k]['apply_mobile']=$v['apply_mobile'];
            $Csv[$k]['date']=substr($v['date'],0,4).'年'.substr($v['date'],-2).'月';
            $Csv[$k]['screen_number']=$v['screen_number'];
            $Csv[$k]['reduce_price']=ToolsClass::priceConvert($v['reduce_price']);
            $Csv[$k]['price']=ToolsClass::priceConvert($v['price']);
            $Csv[$k]['status']=$v['status']==1?'发放':'不发放';
        }
        return $Csv;
    }

}
