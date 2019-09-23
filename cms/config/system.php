<?php
namespace cms\config;

class system{
    /*
     * 系统菜单列表
     * 一维数组的key为model名称
     *      @title是顶部菜单的标题。
     *      @href不填写默认跳转到child下的第一个菜单
     * child为左侧菜单的列表
     *      @title是菜单标题
     *      @href是链接地址
     *      @key是链接地址的控制器名称
     * */
    public static function systemMenu(){
        return [
            'member'=>[
                'title'=>'业务查询',
                'child'=>[
                    [
                        'title'=>'人员查询',
                        'href'=>'/member/member/index',
                        'key'=>'member_index',
                    ],
                    [
                        'title'=>'广告查询',
                        'href'=>'/member/order/index',
                        'key'=>'order_index',
                    ],
                    [
                        'title'=>'合同申请',
                        'href'=>'/member/order/contract',
                        'key'=>'order_contract',
                    ],
                    [
                        'title'=>'发票申请',
                        'href'=>'/member/order/invoice',
                        'key'=>'order_invoice',
                    ],
                    [
                        'title'=>'安装人补贴',
                        'href'=>'/member/member-install-subsidy/index',
                        'key'=>'member-install-subsidy_index',
                    ],
                    [
                        'title'=>'安装团队查询',
                        'href'=>'/member/member-team/index',
                        'key'=>'member-team_index',
                    ],
                    [
                        'title'=>'业绩排行',
                        'href'=>'/member/member/ranking',
                        'key'=>'member_ranking',
                    ],
                    [
                        'title'=>'每月维护费用支出',
                        'href'=>'/member/member/maintain-price',
                        'key'=>'member_maintain-price',
                    ],
                ]
            ],
            'shop'=>[
                'title'=>'商家信息',
                'child'=>[
                    [
                        'title'=>'商家信息',
                        'href'=>'/shop/shop/index',
                        'key'=>'shop_index',
                    ],
                    [
                        'title'=>'总部信息',
                        'href'=>'/shop/shop-headquarters/index',
                        'key'=>'shop-headquarters_index',
                    ],
                    [
                        'title'=>'修改法人',
                        'href'=>'/shop/shop-update-record/index',
                        'key'=>'shop-update-record_index',
                    ],
                    [
                        'title'=>'店铺屏幕信息',
                        'href'=>'/shop/shop-abnormal/index',
                        'key'=>'shop-abnormal_index',
                    ],
                    [
                        'title'=>'店铺数据统计',
                        'href'=>'/shop/shop/statistics',
                        'key'=>'shop_statistics',
                    ],
                ]
            ],
            'screen'=>[
                'title'=>'屏幕管理',
                'child'=>[
                    [
                        'title'=>'屏幕管理',
                        'href'=>'/screen/screen/index',
                        'key'=>'screen_index',
                    ],
                    [
                        'title'=>'广告维护指派',
                        'href'=>'/screen/shop-screen-advert-maintain/index',
                        'key'=>'shop-screen-advert-maintain_index',
                    ],
                ]
            ],
            'notice'=>[
                'title'=>'公告管理',
                'child'=>[
                    [
                        'title'=>'公告列表',
                        'href'=>'/notice/notice/index',
                        'key'=>'notice_index'
                    ],
                    [
                        'title'=>'banner管理',
                        'href'=>'/notice/banner/index',
                        'key'=>'banner_index',
                    ],
                ]
            ],
            'sysfunc'=>[
                'title'=>'模块管理',
                'child'=>[
                    [
                        'title'=>'模块列表',
                        'href'=>'/sysfunc/sysfunc/index',
                        'key'=>'sysfunc_index'
                    ],
                ]
            ],
            'feedback'=>[
                'title'=>'意见栏',
                'child'=>[
                    [
                        'title'=>'意见栏',
                        'href'=>'/feedback/feedback/index',
                        'key'=>'feedback_index',
                    ],
                    [
                        'title'=>'业务合作人投诉查询',
                        'href'=>'/feedback/order-complain/business',
                        'key'=>'order-complain_business',
                    ],
                    [
                        'title'=>'广告对接人投诉查询',
                        'href'=>'/feedback/order-complain/advertisement',
                        'key'=>'order-complain_advertisement',
                    ],
                ]
            ],
            'config'=>[
                'title'=>'配置中心',
                'child'=>[
                    [
                        'title' => '业务合作人员配置',
                        'href' => '/config/config/index',
                        'key' => 'config_index',
                    ],
                    [
                        'title' => '广告配置',
                        'href' => '/config/advert-config/place',
                        'key' => 'advert-config_place',
                    ],
                    [
                        'title' => '客服电话',
                        'href' => '/config/config/phone',
                        'key' => 'config_phone',
                    ],
                    [
                        'title' => '版本管理',
                        'href' => '/config/system-version/version',
                        'key' => 'system-version_version',
                    ],
                    [
                        'title' => '提现验证配置',
                        'href' => '/config/config/money',
                        'key' => 'config_money',
                    ],
                    [
                        'title' => '区域等级设置',
                        'href' => '/config/config/area',
                        'key' => 'config_area',
                    ],
                    [
                        'title' => '区域价格设置',
                        'href' => '/config/zone-price/zone',
                        'key' => 'zone-price_zone',
                    ],
                    [
                        'title' => '安装价格配置',
                        'href' => '/config/config/install-price',
                        'key' => 'config_install-price',
                    ],
                    [
                        'title' => '业务提成管理',
                        'href' => '/config/config/bonus',
                        'key' => 'config_bonus',
                    ],
                    [
                        'title' => '线下汇款配置',
                        'href' => '/config/config/remittance',
                        'key' => 'config_remittance',
                    ],
                    [
                        'title' => '提现留存配置',
                        'href' => '/config/config/retained',
                        'key' => 'config_retained',
                    ],
                    [
                        'title' => '屏幕配置',
                        'href' => '/config/config/screen',
                        'key' => 'config_screen',
                    ],
                    [
                        'title' => 'Led办事处配置',
                        'href' => '/config/config/system-office',
                        'key' => 'config_system-office',
                    ],
                    [
                        'title' => '培训资料配置',
                        'href' => '/config/system-train/index',
                        'key' => 'system-train_index',
                    ],
                    [
                        'title' => '错误提醒电话',
                        'href' => '/config/config/telephone',
                        'key' => 'config_telephone',
                    ],
                    [
                        'title' => '付款配置',
                        'href' => '/config/config/configpay',
                        'key' => 'config_configpay',
                    ],
                    [
                        'title' => '底部菜单配置',
                        'href' => '/config/config/bottom-menu-advert',
                        'key' => 'config_bottom-menu-advert',
                    ],
                    [
                        'title' => '黑名单次数设置',
                        'href' => '/config/config/blacklist',
                        'key' => 'config_blacklist',
                    ],
                    [
                        'title' => '广告配置',
                        'href' => '/config/config/advert-set',
                        'key' => 'config_advert-set',
                    ],
                    [
                        'title' => '银行管理',
                        'href' => '/config/system-bank/index',
                        'key' => 'system-bank_index',
                    ],
//                    [
//                       'title' => 'sql/redis查询',
//                       'href' => '/config/config/querys',
//                       'key' => 'config_querys',
//                    ],
//                    [
//                        'title' => '广告推送',
//                        'href' => '/config/config/alladvice',
//                        'key' => 'config_alladvice',
//                    ],
//                    [
//                        'title' => '系统扣款',
//                        'href' => '/config/config/upprice',
//                        'key' => 'config_upprice',
//                    ],
//                    [
//                        'title' => '图片上传',
//                        'href' => '/config/config/upload-img',
//                        'key' => 'config_upload-img',
//                    ],
                ]
            ],
            'systemstartup'=>[
                'title'=>'启动页管理',
                'child'=>[
                    [
                        'title' => '启动页列表',
                        'href' => '/systemstartup/system-startup/index',
                        'key' => 'system-startup_index',
                    ],
                ]
            ],
            'withdraw'=>[
                'title'=>'提现管理',
                'child'=>[
                    [
                        'title' => '等待财务',
                        'href' => '/withdraw/member-withdraw/index',
                        'key' => 'member-withdraw_index',
                    ],
                    [
                        'title' => '等待审计',
                        'href' => '/withdraw/member-withdraw/audit',
                        'key' => 'member-withdraw_audit',
                    ],
                    [
                        'title' => '等待出纳',
                        'href' => '/withdraw/member-withdraw/cashier',
                        'key' => 'member-withdraw_cashier',
                    ],
                    [
                        'title' => '提现记录',
                        'href' => '/withdraw/member-withdraw/withdraw',
                        'key' => 'member-withdraw_withdraw',
                    ],
                ]
            ],
            'examine'=>[
                'title'=>'审核管理',
                'child'=>[
                    [
                        'title' => '人员审核',
                        'href' => '/examine/chef/index',
                        'key' => 'chef_index',
                    ],
                    [
                        'title' => '安装人员审核',
                        'href' => '/examine/chef/installer',
                        'key' => 'chef_installer',
                    ],
                    [
                        'title' => '总部审核',
                        'href' => '/examine/shop-head/index',
                        'key' => 'shop-head_index',
                    ],
                    [
                        'title' => '指派安装人',
                        'href' => '/examine/examine/installer-assign',
                        'key' => 'examine_installer-assign',
                    ],
                    [
                        'title' => '商家认领',
                        'href' => '/examine/examine/claim',
                        'key' => 'examine_claim',

                    ],
                    [
                        'title' => '商家审核',
                        'href' => '/examine/examine/index',
                        'key' => 'examine_index',

                    ],
                    [
                        'title' => '配发货',
                        'href' => '/examine/install/allocate',
                        'key' => 'install_allocate',

                    ],
                    [
                        'title' => '安装反馈审核',
                        'href' => '/examine/install/index',
                        'key' => 'install_index',
                    ],
                    [
                        'title' => '商家审核(内部)',
                        'href' => '/examine/examine/offline-shop',
                        'key' => 'examine_offline-shop',

                    ],
                    [
                        'title' => '安装反馈审核(内部)',
                        'href' => '/examine/install/offline-an',
                        'key' => 'install_offline-an',
                    ],
                    [
                        'title' => '广告审核',
                        'href' => '/examine/order/index',
                        'key' => 'order_index',
                    ],
                    [
                        'title' => '维护屏幕指派',
                        'href' => '/examine/shop-screen-replace/index',
                        'key' => 'shop-screen-replace_index',
                    ],
                    [
                        'title' => '维护屏幕审核',
                        'href' => '/examine/shop-screen-replace/res-examine',
                        'key' => 'shop-screen-replace_res-examine',
                    ],
                    [
                        'title' => '店铺自定义广告',
                        'href' => '/examine/order/shop-order',
                        'key' => 'order_shop-order',
                    ],
                    [
                        'title' => '店铺推荐信息',
                        'href' => '/examine/activity-detail/index',
                        'key' => 'activity-detail_index',
                    ],
                    [
                        'title' => '合同审核',
                        'href' => '/examine/shop-contract/index',
                        'key' => 'shop-contract_index',
                    ],
                    [
                        'title' => '店铺变更审核',
                        'href' => '/examine/shop-choose/index',
                        'key' => 'shop-choose_index',
                    ],
                ]
            ],
            'ledmanage' => [
                'title' => '设备库存管理',
                'child' => [
                    [
                        'title' => 'LED设备总列表',
                        'href' => '/ledmanage/led-manage/index',
                        'key' => 'led-manage_index',
                    ],
                    [
                        'title' => 'LED办事处列表',
                        'href' => '/ledmanage/led-manage/offices',
                        'key' => 'led-manage_offices',
                    ],
                    [
                        'title' => '画框设备总列表',
                        'href' => '/ledmanage/frame/index',
                        'key' => 'frame_index',
                    ],
                    [
                        'title' => '画框办事处列表',
                        'href' => '/ledmanage/frame/offices',
                        'key' => 'frame_offices',
                    ],
                ],
            ],
            'account' => [
                'title' => '结算中心',
                'child' => [
                    [
                        'title' => '收款',
                        'href' => '/account/settle-center/collection',
                        'key' => 'settle-center_collection',
                    ],
                    [
                        'title' => '业务合作人支出',
                        'href' => '/account/settle-center/salesmanpay',
                        'key' => 'settle-center_salesmanpay',
                    ],
                    [
                        'title' => '安装费用支出',
                        'href' => '/account/settle-center/install',
                        'key' => 'settle-center_install',
                    ],
                    [
                        'title' => '线下收款',
                        'href' => '/account/settle-center/offline',
                        'key' => 'settle-center_offline',
                    ],
                    [
                        'title' => '安装费用补贴',
                        'href' => '/account/settle-center/install-subsidy',
                        'key' => 'settle-center_install-subsidy',
                    ],
                    [
                        'title' => '拆装屏幕费用支出',
                        'href' => '/account/settle-center/replace-screen',
                        'key' => 'settle-center_replace-screen',
                    ],
                    [
                        'title' =>'每月维护费用支出',
                        'href'  =>'/account/screen-run-time-shop-subsidy/index',
                        'key' => 'screen-run-time-shop-subsidy_index',
                    ],
                    [
                        'title' =>'广告销售奖励支出',
                        'href'  =>'/account/member-reward-detail/index',
                        'key' => 'member-reward-detail_index',
                    ],
                    [
                        'title' =>'每月买断费用支出',
                        'href'  =>'/account/screen-run-time-shop-subsidy/shop-apply-brokerage',
                        'key' => 'screen-run-time-shop-subsidy_shop-apply-brokerage',
                    ],
                ],
            ],
            'authority' => [
                'title' => '权限管理',
                'child' => [
                    [
                        'title' => '后台角色管理',
                        'href' => '/authority/auth-item/index',
                        'key' => 'authitem-authitem_index',
                    ],
                    [
                        'title' => '后台权限管理',
                        'href' => '/authority/auth-rule/index',
                        'key' => 'authrule-authrule_index',
                    ],
                    [
                        'title' => '后台用户管理',
                        'href' => '/authority/user/index',
                        'key' => 'user-user_index',
                    ],
                    [
                        'title' => '客服用户管理',
                        'href' => '/authority/custom-user/index',
                        'key' => 'custom-user_index',
                    ],
                ],
            ],
            'schedules' => [
                'title' => '广告排期管理',
                'child' => [
                    [
                        'title' => 'A屏区域排期',
                        'href' => '/schedules/order-throw-program/ascreen',
                        'key' => 'order-throw-program_ascreen',
                    ],
                    [
                        'title' => 'B屏区域排期',
                        'href' => '/schedules/order-throw-program/bscreen',
                        'key' => 'order-throw-program_bscreen',
                    ],
                    [
                        'title' => 'C屏区域排期',
                        'href' => '/schedules/order-throw-program/cscreen',
                        'key' => 'order-throw-program_cscreen',
                    ],
                    [
                        'title' => 'D屏区域排期',
                        'href' => '/schedules/order-throw-program/dscreen',
                        'key' => 'order-throw-program_dscreen',
                    ],
                    [
                        'title' => '历史排期表',
                        'href' => '/schedules/order-throw-program/history',
                        'key' => 'order-throw-program_history',
                    ],
                    [
                        'title' => '等待日广告管理',
                        'href' => '/schedules/system-advert/index',
                        'key' => 'system-advert_index',
                    ],
                    [
                        'title' => '测试推送',
                        'href' => '/schedules/system-advert/propel',
                        'key' => 'system-advert_propel',
                    ],
                    [
                        'title' => '等待日广告审核',
                        'href' => '/schedules/system-advert/advert-examine-list',
                        'key' => 'system-advert_advert-examine-list',
                    ],
                ],
            ],
            'report' => [
                'title' => '监播管理',
                'child' => [
                    [
                        'title' => '播放列表',
                        'href' => '/report/report/index',
                        'key' => 'report_index',
                    ],
                ],
            ],
            'sign' => [
                'title' => '签到管理',
                'child' => [
                    [
                        'title' => '签到团队管理',
                        'href' => '/sign/sign/signteam',
                        'key' => 'sign_signteam',
                    ],
                    [
                        'title' => '团队管理日志',
                        'href' => '/sign/sign/sign-log',
                        'key' => 'sign_sign-log',
                    ],
                    [
                        'title' => '业务签到管理',
                        'href' => '/sign/sign/sign-business',
                        'key' => 'sign_sign-business',
                    ],
                    [
                        'title' => '维护签到管理',
                        'href' => '/sign/sign/sign-maintain',
                        'key' => 'sign_sign-maintain',
                    ],
                    [
                        'title' => '按时间业务签到统计',
                        'href' => '/sign/sign/business-time',
                        'key' => 'sign_business-time',
                    ],
                    [
                        'title' => '按团队业务签到统计',
                        'href' => '/sign/sign/business-team',
                        'key' => 'sign_business-team',
                    ],
                    [
                        'title' => '按时间维护签到统计',
                        'href' => '/sign/sign/maintain-time',
                        'key' => 'sign_maintain-time',
                    ],
                    [
                        'title' => '按团队维护签到统计',
                        'href' => '/sign/sign/maintain-team',
                        'key' => 'sign_maintain-team',
                    ],
                    [
                        'title' => '业务签到基础设置',
                        'href' => '/sign/sign/salesman-sign-set',
                        'key' => 'sign_salesman-sign-set',
                    ],
                    [
                        'title' => '维护签到基础设置',
                        'href' => '/sign/sign/maintain-sign-set',
                        'key' => 'sign_maintain-sign-set',
                    ],
                ],
            ],
            'guest'=>[
                'title'=>'客服系统',
                'child'=>[
                    [
                        'title'=>'人员查询',
                        'href'=>'/guest/guest/check-member',
                        'key'=>'guest_check-member',
                    ],
                    [
                        'title'=>'商家查询',
                        'href'=>'/guest/guest/index',
                        'key'=>'guest_index',
                    ],
                    [
                        'title'=>'提现查询',
                        'href'=>'/guest/guest/cash',
                        'key'=>'guest_cash',
                    ],
                    [
                        'title'=>'推荐信息',
                        'href'=>'/guest/guest/activity',
                        'key'=>'guest_activity',
                    ],
                    [
                        'title'=>'广告查询',
                        'href'=>'/guest/guest/advice',
                        'key'=>'guest_advice',
                    ],
                ]
            ],
        ];
    }
}