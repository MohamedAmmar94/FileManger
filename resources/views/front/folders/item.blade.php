
  @foreach ($folder->children as $folder_row)
  <div class="col-lg-3 col-md-3 col-sm-4 mb-3">
      <div class="card card-item"  ondblclick="href_to_link('{{ route('folders.show', $folder_row) }}')">
        <div class="card-title">

          <div class="menu-nav">
            <div class="menu-item"></div>
            <button type="button" class="btn  dropdown-toggle  dropdown-card-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="three-dots"></div>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item rename-btn" href="javascript:void(0)" onclick="display_rename_model('folder',{{$folder_row->id}},'{{$folder_row->name}}')" data-toggle="modal" data-target="#RenameModal">Rename</a>
              <a class="dropdown-item invite-btn" href="javascript:void(0)" onclick="display_invite_model('folder',{{$folder_row->id}})" data-toggle="modal" data-target="#InviteModal">invite</a>
              <a class="dropdown-item" target="_blank"  href="{{route('folders.download_zip',$folder_row->id)}}" >Download</a>
              <!-- <a class="dropdown-item" href="javascript:void(0)">Copy</a> -->
              <a class="dropdown-item" href="javascript:void(0)" onclick="display_move_model('folder',{{$folder_row->parent_id}}, {{$folder_row->id}})" data-toggle="modal" data-target="#MoveModal">Move</a>
              <a class="dropdown-item" href="javascript:void(0) " onclick="delete_item('folder', {{$folder_row->id}})">Delete</a>
            </div>

          </div>
          <!-- <a href="{{ route('folders.show', [$folder_row]) }}" target="_blank"> -->
            <div class="file-item-icon far fa-folder text-secondary vertical-center"></div>
          <!-- </a> -->
        </div>
          <div class="card-footer text-center"  >
              <p class="vertical-center file-name" id="folder_name_{{$folder_row->id}}">
                     {{ $folder_row->name }}

              </p>

          </div>

      </div>
  </div>
  @endforeach
  @foreach ($folder->files as $file)
  <div class="col-lg-3 col-md-3 col-sm-4 mb-3">
      <div class="card card-item" ondblclick="href_to_link('{{ $file->getUrl() }}' ,'file')" >
          <div class="card-title">
            <div class="menu-nav">
              <div class="menu-item"></div>
              <button type="button" class="btn  dropdown-toggle dropdown-card-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="three-dots"></div>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item rename-btn" href="javascript:void(0)" onclick="display_rename_model('file',{{$file->id}},'{{$file->name}}')" data-toggle="modal" data-target="#RenameModal">Rename</a>
                <a class="dropdown-item invite-btn" href="javascript:void(0)" onclick="display_invite_model('file',{{$file->id}})" data-toggle="modal" data-target="#InviteModal">invite</a>
                <a class="dropdown-item" target="_blank"  href="{{route('file.download',$file->id)}}" >Download</a>
                <!-- <a class="dropdown-item" href="javascript:void(0)">Copy</a> -->
                <a class="dropdown-item" href="javascript:void(0)" onclick="display_move_model('file',{{$folder->id}}, {{$file->id}})" data-toggle="modal" data-target="#MoveModal">Move</a>
                <a class="dropdown-item" href="javascript:void(0) " onclick="delete_item('file', {{$file->id}})">Delete</a>
              </div>
            </div>
            <!-- <a href="{{ $file->getUrl() }}" target="_blank"> -->
              <div class="card-image" >
                @if($file->type=="pdf")
                  <div class="file-item-icon far fa-file-pdf text-secondary vertical-center"></div>
                @elseif($file->type=="image")
                  <img class="card-img-top vertical-center" src="{{ $file->getUrl()  }}" alt="{{ $file->name }}">
                @else
                  <div class="file-item-icon far fa-file text-secondary vertical-center"></div>
                @endif
              </div>
            <!-- </a> -->
          </div>

          <div class="card-footer text-center" onclick="href_to_link('{{ $file->getUrl() }}' ,'file')">
              <p class="vertical-center file-name">
                  {{ $file->name }}
              </p>

          </div>
      </div>
  </div>
  @endforeach
