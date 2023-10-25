<?php

namespace Otomaties\SageHtmlFormsExportSubmissions\Services\Contracts;

interface Exportable
{
    public function key() : string;

    public function label() : string;

    public function exportLink() : string;

    public function export() : void;

    public function fileName() : string;

    public function data() : array;
}
