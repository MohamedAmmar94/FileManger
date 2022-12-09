<div class="col-md-12 col-xs-12" style="margin-top:20px;">
  <div class="table-wrapper-scroll-y my-custom-scrollbar">
    <table class="table table-bordered table-hover table-striped table-foldersystem">
      <tbody>
        @foreach ($folder->children as $folder_row)
          @if($folder_row->id != $current)
            <tr ondblclick="display_move_model('folder',{{$folder_row->id}})">
              <td class="text-center">
                <div class="file-item-icon far fa-folder text-secondary font-1"></div>
              </td>
              <td>{{$folder_row->name}}</td>
            </tr>
          @endif
        @endforeach
        @foreach ($folder->files as $file)
        <tr>
          <td>
            @if($file->type=="pdf")
              <div class="file-item-icon far fa-file-pdf text-secondary font-1"></div>
            @elseif($file->type=="image")
              <img class="w-100px" src="{{ $file->getUrl()  }}" alt="{{ $file->name }}">
            @else
              <div class="file-item-icon far fa-file text-secondary "></div>
            @endif
          </td>
          <td>{{$file->file_name}}</td>
        </tr>
        @endforeach
        @if(!empty($folder->parent))
          <a href="javascript:void(0)" onclick="display_move_model('folder',{{$folder->parent->id}})" class="btn btn-primary">
              Back to folder {{ $folder->parent->name }}
          </a>
        @endif
      </tbody>
    </table>
  </div>
</div>
