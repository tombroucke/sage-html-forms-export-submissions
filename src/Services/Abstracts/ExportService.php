<?php

namespace Otomaties\SageHtmlFormsExportSubmissions\Services\Abstracts;

use HTML_Forms\Form;
use Illuminate\Support\Str;
use Otomaties\SageHtmlFormsExportSubmissions\Services\Contracts\Exportable;

abstract class ExportService implements Exportable
{
    public function __construct(private Form $form)
    {
    }

    public function fileName() : string
    {
        return Str::slug($this->form->title);
    }

    public function data() : array
    {
        $submissions = collect(hf_get_form_submissions($this->form->id));
        $data = $submissions->pluck('data');

        $unexportableData = [
            'g-recaptcha-response',
            'h-captcha-response'
        ];
        $data = $data->map(function ($item) use ($unexportableData) {
            foreach ($unexportableData as $key) {
                if (isset($item[$key])) {
                    unset($item[$key]);
                }
            }
            return $item;
        });

        return $data->toArray();
    }
}
