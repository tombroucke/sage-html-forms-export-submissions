<?php

namespace Otomaties\SageHtmlFormsExportSubmissions\Providers;

use Roots\Acorn\ServiceProvider;
use HTML_Forms\Form;

class SageHtmlFormsExportSubmissionsServiceProvider extends ServiceProvider
{
    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/html-forms-export-submissions.php',
            'html-forms-export-submissions'
        );

        $this->app->bind('SageHtmlFormsExportSubmissionsServices', function () {
            $formId = filter_input(INPUT_GET, 'form_id', FILTER_SANITIZE_NUMBER_INT);
            $form = hf_get_form($formId);
            return collect([
                'excel' => new \Otomaties\SageHtmlFormsExportSubmissions\Services\Excel($form),
            ]);
        });
    }

    /**
    * Bootstrap any application services.
    *
    * @return void
    */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/html-forms-export-submissions.php' => $this->app->configPath('html-forms-export-submissions.php'),
        ], 'config');

        $this->loadViewsFrom(
            __DIR__.'/../../resources/views',
            'SageHtmlFormsExportSubmissions',
        );

        add_filter('hf_admin_tabs', function ($tabs, $form) {
            $tabs['export'] = __('Export submissions', 'html-forms-export-submissions');
            return $tabs;
        }, 10, 2);

        add_action('admin_init', function () {
            $isHtmlFormsPage = isset($_GET['page']) && strpos($_GET['page'], 'html-forms') !== false;
            $isExportTab = isset($_GET['export_to']);

            if ($isHtmlFormsPage && $isExportTab) {
                $form = hf_get_form($_GET['form_id']);
                $service = sanitize_text_field($_GET['export_to']);

                $excel = $this->app->make('SageHtmlFormsExportSubmissionsServices')->get($service);

                $excel->export($form);
            }
        });

        add_action('hf_admin_output_form_tab_export', function (Form $form) {

            echo view('SageHtmlFormsExportSubmissions::export-submissions', [
                'form' => $form,
                'services' => $this->app->make('SageHtmlFormsExportSubmissionsServices'),
            ]);
        });
    }
}
