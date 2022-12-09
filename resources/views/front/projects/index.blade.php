@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">My Assigned Project</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-4 mb-3 cursor-pointer">
                                    <div class="card card-item "  onclick="href_to_link('{{ route('folders.show', $project->id) }}')">
                                        <div class="card-title">
                                              <img class="card-img-top vertical-center" src="{{  url('images/project.png') }}" alt="{{ $project->name }}">
                                        </div>
                                        <div class="card-footer text-center">
                                          <p class="vertical-center">
                                                {{ $project->name }}
                                          </p>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
