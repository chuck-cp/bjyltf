<?php
/**
 * 操作excel类
 */
namespace common\libs;
use yii\phpexcel\Yiiexcel;
use yii\phpexcel\YiiexcelIOFactory;
use yii\phpexcel\YiiexcelPHPExcelWriterExcel2007;
use yii\phpexcel\YiiexcelPHPExcelWriterExcel5;
class ExcelClass
{
    private $columnExcle;
    private $formModel;
    /**
     * 构造函数
     */
    public function __construct($formModel)
    {
        //excl列转换数组
        $columnExcle = array();
        for($i='A';$i<='Z';$i++)
        {
            $columnExcle[] = $i;
            if(count($columnExcle)>=52)
            {
                break;
            }
        }
        $this->columnExcle = $columnExcle;
        $this->formModel = $formModel;
    }
    /**
     * 导出excel
     * $title 标题数组
     * $models 数据数组
     * $outputFileName  输出的文件名
     */
    public function Exportexcel($title,$models,$outputFileName = 'statistics',$exportType='2003')
    {
        $columnExcle = $this->columnExcle;
        $phpExcel = new Yiiexcel();
        $phpExcel->setActiveSheetIndex(0);
        $flag = 1;
        $j = 0;
        foreach ($title as $a=>$b)
        {
            $phpExcel->getActiveSheet()->setCellValue($columnExcle[$j].$flag,$b);
            $j++;
        }
        $flag++;
        foreach ($models as $key=>$val)
        {
            $j = 0;
            foreach ($val as $k=>$v)
            {
                //$phpExcel->getActiveSheet()->setCellValue($columnExcle[$j].$flag,$v);
                $phpExcel->getActiveSheet()->setCellValueExplicit($columnExcle[$j].$flag,$v,\PHPExcel_Cell_DataType::TYPE_STRING);
                $j++;
            }
            $flag++;

        }
        if($exportType == '2003'){
            $xlsWriter = new YiiexcelPHPExcelWriterExcel5($phpExcel);
            $outputFileName .= '.xls';
        }else{
            $xlsWriter = new YiiexcelPHPExcelWriterExcel2007($phpExcel);
            $outputFileName .= '.xlsx';
        }
        ob_start();
        ob_flush();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $xlsWriter->save( "php://output" );
        exit;
    }

    // 读取excel
    public function ReadExcel($filename){        
        $phpExcel = new Yiiexcel();
        $objPHPExcel = YiiexcelIOFactory::load($filename);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        return $sheetData;
    }
}