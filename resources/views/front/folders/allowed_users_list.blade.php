<div class="container m-3">
  <div class="row col-12">
    People with access
  </div>
  <hr>
  @if(count($users)> 0 )
    @foreach($users as $user)
      <div class="row col-12" id="allowed_row_{{$user->id}}">
        <div class="col-2">
          <img class="allowed-img" src="{{asset('images/user.png')}}">
        </div>
        <div class="col-8">
          <h6>{{$user->name}}</h6>
          <span>{{$user->email}}</span>
        </div>
        <div class="col-2">
          <button class="btn btn-danger" onclick="delete_allowed('{{$type}}',{{$id}},{{$user->id}})">Delete</button>
        </div>
      </div>
    <hr>
    @endforeach
  @else
    no people having access to this repository
  @endif
</div>
