@extends('layouts.front')
@section('styles')
<style>
.more {
  cursor: pointer;
  border: none;
  background: transparent;
}

.more span {
  display: block;
  width: .25rem;
  height: .25rem;
  background: #363636;
  border-radius: 50%;
  pointer-events: none;
}

.more span:not(:last-child) {
  margin-bottom: .125rem;
}

.dropout {
  z-index: 9001;
  width: fit-content;
  position: relative;
  margin-left: auto;
  margin-right: auto;
}

.dropout ul {
  position: absolute;
  top: -1.1rem;
  right: 1.5rem;
  transform: scaleX(0);
  transform-origin: right;
  transition: transform 0.12s ease;
}

.dropout.active ul {
  transform: scaleX(1);
}
</style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                      @if(isset($folder))
                          <a href="javascript:history.back()" class="back"><i class="fas fa-arrow-left"></i></a>
                      @endif
                        Shared Data
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif



                        <div class="row " id="folder_body">
                            @if(count($shared_folders) <=0 && count($shared_files) <=0 )
                              No Shared data Found
                            @endif
                            @foreach ($shared_folders as $folder_row)
                                <div class="col-lg-3 col-md-3 col-sm-4 mb-3">
                                    <div class="card card-item"  ondblclick="href_to_link('{{ route('shared.show', $folder_row) }}')">
                                      <div class="card-title">

                                        <div class="menu-nav">
                                          <div class="menu-item"></div>
                                          <button type="button" class="btn  dropdown-toggle  dropdown-card-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div class="three-dots"></div>
                                          </button>
                                          <div class="dropdown-menu">
                                            <!-- <a class="dropdown-item rename-btn" href="javascript:void(0)" onclick="display_rename_model('folder',{{$folder_row->id}},'{{$folder_row->name}}')" data-toggle="modal" data-target="#RenameModal">Rename</a>
                                            <a class="dropdown-item invite-btn" href="javascript:void(0)" onclick="display_invite_model('folder',{{$folder_row->id}})" data-toggle="modal" data-target="#InviteModal">invite</a> -->
                                            <a class="dropdown-item" target="_blank"  href="{{route('folders.download_zip',$folder_row->id)}}" >Download</a>
                                            <!-- <a class="dropdown-item" href="javascript:void(0)">Copy</a> -->
                                            <!-- <a class="dropdown-item" href="javascript:void(0)" onclick="display_move_model('folder',{{$folder_row->parent_id}}, {{$folder_row->id}})" data-toggle="modal" data-target="#MoveModal">Move</a>
                                            <a class="dropdown-item" href="javascript:void(0) " onclick="delete_item('folder', {{$folder_row->id}})">Delete</a> -->
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
                            @foreach ($shared_files as $file)
                                <div class="col-lg-3 col-md-3 col-sm-4 mb-3">
                                    <div class="card card-item" ondblclick="href_to_link('{{ $file->getUrl() }}' ,'file')" >
                                        <div class="card-title">
                                          <div class="menu-nav">
                                            <div class="menu-item"></div>
                                            <button type="button" class="btn  dropdown-toggle dropdown-card-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <div class="three-dots"></div>
                                            </button>
                                            <div class="dropdown-menu">
                                              <!-- <a class="dropdown-item rename-btn" href="javascript:void(0)" onclick="display_rename_model('file',{{$file->id}},'{{$file->name}}')" data-toggle="modal" data-target="#RenameModal">Rename</a> -->
                                              <!-- <a class="dropdown-item invite-btn" href="javascript:void(0)" onclick="display_invite_model('file',{{$file->id}})" data-toggle="modal" data-target="#InviteModal">invite</a> -->
                                              <a class="dropdown-item" target="_blank"  href="{{route('file.download',$file->id)}}" >Download</a>
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
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('scripts')
  <script>


  </script>
@endsection
