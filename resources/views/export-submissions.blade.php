<h2><?php echo __( 'Export submissions', 'html-forms-export-submissions' ); ?></h2>

@foreach($services as $serviceKey => $service)
  <a href="{{ $service->exportLink() }}" class="button">{!! sprintf(__('Export to %s file', 'html-forms-export-submissions' ), $service->label()) !!}</a>
@endforeach
