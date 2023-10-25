<?php

namespace Otomaties\SageHtmlFormsExportSubmissions\Services\Abstracts;

use HTML_Forms\Form;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Otomaties\SageHtmlFormsExportSubmissions\Services\Contracts\Exportable;

abstract class ExportService implements Exportable
{

    const EXCLUDE_FIELDS = [
        'g-recaptcha-response',
        'h-captcha-response'
    ];

    public function __construct(private Form $form)
    {
    }

    public function fileName() : string
    {
        return Str::slug($this->form->title);
    }

    public function exportLink() : string
    {
        $exportLink = admin_url('admin.php?page=html-forms&view=edit');
        $exportLink = add_query_arg([
            'form_id' => $this->form->ID,
            'export_to' => $this->key(),
        ], $exportLink);
        $exportLink = wp_nonce_url( $exportLink, 'export-submissions' );
        return html_entity_decode($exportLink);
    }

    public function headers() {
        $columns = [];
		foreach ( $this->submissions() as $submission ) {
			if ( ! is_array( $submission->data ) ) {
				continue;
			}

			foreach ( $submission->data as $field => $value ) {
				if ( ! isset( $columns[ $field ] ) && !in_array($field, self::EXCLUDE_FIELDS) ) {
					$columns[ $field ] = esc_html(Str::title(str_replace('-', ' ', $field)));
				}
			}
		}
        return $columns;
    }

    private function submissions() : Collection
    {
        return collect(hf_get_form_submissions($this->form->id));
    }

    public function data() : array
    {
        $headers = $this->headers();
        $submissions = $this->submissions()->pluck('data')->map(function ($data) use ($headers) {
            $submission = [];
            foreach ($headers as $key => $value) {
                $submission[$value] = $data[$key] ?? '';
            }
            return $submission;
        });
        return $submissions->toArray();
    }
}
