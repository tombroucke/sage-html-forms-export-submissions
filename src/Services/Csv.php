<?php

namespace Otomaties\SageHtmlFormsExportSubmissions\Services;

class Csv extends Abstracts\ExportService
{
    public function key() : string
    {
        return 'csv';
    }

    public function label() : string
    {
        return __('CSV', 'html-forms-export-submissions');
    }

    public function export() : void
    {
        $fileName = $this->fileName() . '.csv';

        $sheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet->setActiveSheetIndex(0);

        $sheet->getActiveSheet()->fromArray($this->headers(), null, 'A1');
        $sheet->getActiveSheet()->fromArray($this->data(), null, 'A2');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($sheet);

        header("Content-Type: text/csv");
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        $writer->save('php://output');
        exit;
    }
}
