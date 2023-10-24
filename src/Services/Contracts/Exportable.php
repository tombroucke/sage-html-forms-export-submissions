<?php

namespace Otomaties\SageHtmlFormsExportSubmissions\Services\Contracts;

interface Exportable
{
    public function label() : string;
    public function export() : void;
    public function fileName() : string;
    public function data() : array;
}
