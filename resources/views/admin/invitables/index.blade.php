@extends('layouts.admin')
@section('content')
@can('invitable_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.invitables.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.invitable.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.invitable.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Invitable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.invitable.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.invitable.fields.invitable') }}
                        </th>
                        <th>
                            {{ trans('cruds.invitable.fields.invitable_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.invitable.fields.user') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.invitable.fields.invited_by') }}
                        </th>
                        <th>
                            {{ trans('cruds.invitable.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invitables as $key => $invitable)
                        <tr data-entry-id="{{ $invitable->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $invitable->id ?? '' }}
                            </td>
                            <td>
                                {{ $invitable->invitable ?? '' }}
                            </td>
                            <td>
                                {{ $invitable->invitable_type ?? '' }}
                            </td>
                            <td>
                                {{ $invitable->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $invitable->user->email ?? '' }}
                            </td>
                            <td>
                                {{ $invitable->invited_by->name ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Invitable::STATUS_SELECT[$invitable->status] ?? '' }}
                            </td>
                            <td>
                                @can('invitable_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.invitables.show', $invitable->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('invitable_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.invitables.edit', $invitable->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('invitable_delete')
                                    <form action="{{ route('admin.invitables.destroy', $invitable->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('invitable_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.invitables.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Invitable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection