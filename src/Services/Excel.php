<?php

namespace Otomaties\SageHtmlFormsExportSubmissions\Services;

class Excel extends Abstracts\ExportService
{
    public function label() : string
    {
        return __('Excel', 'html-forms-export-submissions');
    }

    public function export() : void
    {
        $fileName = $this->fileName() . '.xlsx';

        $sheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet->setActiveSheetIndex(0);

        $sheet->getActiveSheet()->fromArray($this->headers(), null, 'A1');
        $sheet->getActiveSheet()->fromArray($this->data(), null, 'A2');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        $writer->save('php://output');
        exit;
    }
}
