@if ($crud->hasAccess('show'))
	@if ($crud->route == 'admin/hijackercheck')
		<a href="https://www.amazon.com/gp/offer-listing/{{ $entry->asin }}/condition=new" target="_blank" class="btn btn-xs btn-default">
			<i class="fa fa-eye"></i> {{ trans('backpack::crud.preview') }}
		</a>
	@elseif ($crud->route == 'admin/product')
		<a href="https://www.amazon.com/dp/{{ $entry->asin }}/" target="_blank" class="btn btn-xs btn-default">
			<i class="fa fa-eye"></i> {{ trans('backpack::crud.preview') }}
		</a>
	@else
		<a href="{{ url($crud->route.'/'.$entry->getKey()) }}" class="btn btn-xs btn-default">
			<i class="fa fa-eye"></i> {{ trans('backpack::crud.preview') }}
		</a>
	@endif
@endif