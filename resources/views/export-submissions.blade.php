<h2><?php echo __( 'Export submissions', 'html-forms' ); ?></h2>

@foreach($services as $serviceKey => $service)
  <a href="{{ admin_url( 'admin.php?page=html-forms&view=edit&form_id=' . $form->ID . '&export_to=' . $serviceKey ) }}" class="button">{!! sprintf(__('Export to %s file', 'html-forms-export-submissions' ), $service->label()) !!}</a>
@endforeach
