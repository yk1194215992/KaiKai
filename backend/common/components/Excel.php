<?php
/**
 * Created by PhpStorm.
 * User: zhanglongji
 * Date: 2018/4/19
 * Time: 15:11
 */

namespace backend\common\components;


class Excel
{
    /**
     *  @DESC 数据导
     *  @notice 解决了上面导出列数过多的问题
     *  @example
     *  $data = [1, "小明", "25"];
     *  $header = ["id", "姓名", "年龄"];
     *  Myhelpers::exportData($data, $header);
     *  @return void, Browser direct output
     */
    public static function exportData ($data, $header, $title = "simple", $filename = "data")
    {
        if (!is_array ($data) || !is_array ($header)) return false;
        $objPHPExcel = new \PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
        $objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        //添加头部
        $hk = 0;
        foreach ($header as $k => $v)
        {
            $colum = \PHPExcel_Cell::stringFromColumnIndex($hk);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum."1", $v);
            $hk += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach($data as $key => $rows)  //行写入
        {
            $span = 0;
            foreach($rows as $keyName => $value) // 列写入
            {
                $j = \PHPExcel_Cell::stringFromColumnIndex($span);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }
        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle($title);
        // Save Excel 2007 file
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        header("Pragma:public");
        header("Content-Type:application/x-msexecl;name=\"{$filename}.xlsx\"");
        header("Content-Disposition:inline;filename=\"{$filename}.xlsx\"");
        $objWriter->save("php://output");
    }
}