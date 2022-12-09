<!-- Rename Modal -->
<div class="modal fade" id="RenameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" id="modal_content">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Rename </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control rename-input" id="rename_input" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="rename('folder')">Save</button>
      </div>
    </div>
  </div>
</div>
    <!--  Invite Modal -->
<div class="modal fade" id="InviteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" id="modal_content">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Invite </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="email" class="form-control rename-input" id="invaite_email" >
        <div id="allowed_list">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="check_exist_email()">invite</button>
      </div>
    </div>
  </div>
</div>
    <!-- Move Modal -->
<div class="modal fade" id="MoveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" id="modal_content">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Move </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="move_body">
        <!-- <input type="text" class="form-control rename-input" id="rename_input" > -->
      </div>
      <div class="modal-footer">
        <input type="hidden" name="current" >
        <input type="hidden" name="remote" >
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="move('folder')">move Here</button>
      </div>
    </div>
  </div>
</div>
  <div class="hidden" id="rename_copied">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Rename </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
      <input type="text" class="form-control rename-input" id="rename_input_{id}" value="{value}">
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary" onclick="{rename}">Save</button>
    </div>
  </div>
</div>
@section('scripts')
  <script>

  function display_rename_model(type,id,value){
    var html =   document.getElementById("rename_copied").innerHTML;
    var html = html.replaceAll("{id}", id);
    var html = html.replaceAll("{value}", value);
    var html = html.replaceAll("{rename}", "rename('"+type+"',"+id+")");
    document.getElementById("modal_content").innerHTML = html;
  }
  function rename(type,id){
    var myInput = document.getElementById("rename_input_"+id);
    if (!myInput || myInput == null || myInput.value =="" || myInput.value.trim() =="") {
      new Noty({
             text:  type+" name cannot be empty",
             type: 'error',
             timeout: 6000,
         }).show();
      return false;
    }
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    if(type=='folder'){
      var url="{{route('folders.rename',':id')}}";
      url = url.replace(':id', id);
    }else{
      var url="{{route('file.rename',':id')}}";
      url = url.replace(':id', id);
    }
    $.ajax({
        url: url,
        method: "POST",
        data: {_token: CSRF_TOKEN,id:id,name:myInput.value}
    }).done(
        function(data) {
            if(data.status==1){
              reload_screen();
              $("#RenameModal .close").click();
              new Noty({
                     text:  type+" renamed successfully",
                     type: 'success',
                     timeout: 6000,
                 }).show();

            }else{
              new Noty({
                     text:  data.message,
                     type: 'error',
                     timeout: 6000,
                 }).show();
            }
        });
  }
  function display_move_model(type,id,current=""){
      if(current != ""){
        sessionStorage.setItem("move_current",parseInt(current));
        sessionStorage.setItem("move_type",type)
      }

      var url="{{route('folders.display_in_modal',':id')}}";
      url = url.replace(':id', id);
      // url = url.replace(':current', sessionStorage.getItem("move_current"));
    $.ajax({
        url: url,
        method: "POST",
        data: {_token:"{{ csrf_token() }}",current:sessionStorage.getItem("move_current")}
    }).done(
        function(data) {
          if(data['status'] ==1){
            sessionStorage.setItem("move_destination",parseInt(id));

               $('#move_body').html(data['html']);
             }
        });

  }
  function move(type){
    type=sessionStorage.getItem("move_type");//sessionStorage.setItem("move_type",type);
    if(type=="folder"){
      var url="{{route('folders.move_folder',':id')}}";
      url = url.replace(':id', sessionStorage.getItem("move_current"));
    }else{
      var url="{{route('file.move',':id')}}";
      url = url.replace(':id', sessionStorage.getItem("move_current"));
    }
    $.ajax({
        url: url,
        method: "POST",
        data: {_token:"{{ csrf_token() }}",current:sessionStorage.getItem("move_current"),destination:sessionStorage.getItem("move_destination")}
    }).done(
        function(data) {
          if(data['status'] ==1){
            new Noty({
                   text:  type+" moved successfully",
                   type: 'success',
                   timeout: 6000,
               }).show();
               reload_screen();
               $("#MoveModal .close").click();

             }else{
               new Noty({
                      text:  data['message'],
                      type: 'error',
                      timeout: 6000,
                  }).show();
             }
        });
  }

  function delete_item(type,id){
    swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this imaginary file!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      // console.log(willDelete);
      if (willDelete) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        if(type=='folder'){
          var url="{{route('folders.remove',':id')}}";
          url = url.replace(':id', id);
        }else{
          var url="{{route('file.remove',':id')}}";
          url = url.replace(':id', id);

        }
        $.ajax({
            url: url,
            method: "POST",
            data: {_token: CSRF_TOKEN,id:id}
        }).done(
            function(data) {
                if(data.status==1){
                  reload_screen();
                  swal("Poof! Your imaginary file has been deleted!", {
                    icon: "success",
                  });

                }else{

                  new Noty({
                         text:  data.message,
                         type: 'error',
                         timeout: 6000,
                     }).show();
                }
            });

      } else {
        swal("Your imaginary file is safe!");
      }
    });
  }
  function display_invite_model(type,id){
    // console.log(type);
    sessionStorage.setItem("invite_type",type);
    sessionStorage.setItem("invite_id",parseInt(id));
    var url="{{route('invite.get_allawed_users')}}";
    // url = url.replace(':id', id);
    $.ajax({
        url: url,
        method: "POST",
        data: {_token:"{{ csrf_token() }}",id:id,type:type},

    }).done(
        function(data) {
          if(data.status==1){
          $('#allowed_list').html(data['html']);
          }
        });
  }
  function delete_allowed(type,id,user_id){
    swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this imaginary file!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      // console.log(willDelete);
      if (willDelete) {
        var url="{{route('invite.delete_allowed')}}";
        $.ajax({
            url: url,
            method: "POST",
            data: {_token:"{{ csrf_token() }}",id:id,type:type,user_id:user_id},

        }).done(
            function(data) {
              if(data.status==1){
                $('#allowed_row_'+user_id).hide();
                new Noty({
                       text:  data.message,
                       type: 'success',
                       timeout: 6000,
                   }).show();

              }else{
                new Noty({
                       text:  data.message,
                       type: 'error',
                       timeout: 6000,
                   }).show();
              }
            });
          } else {
            swal("Your imaginary file is safe!");
          }
        });
  }
  function check_exist_email(){
    var myInput = document.getElementById("invaite_email");
    if (!myInput || myInput == null || myInput.value =="" || myInput.value.trim() =="") {
      new Noty({
             text:  " email cannot be empty",
             type: 'error',
             timeout: 6000,
         }).show();
      return false;
    }

    var url="{{route('invite.sent')}}";
    $.ajax({
        url: url,
        method: "POST",
        data: {_token:"{{ csrf_token() }}",email:myInput.value,type:sessionStorage.getItem("invite_type"),id:sessionStorage.getItem("invite_id")},

    }).done(
        function(data) {
          if(data.status==1){
            new Noty({
                   text:  data.message,
                   type: 'success',
                   timeout: 6000,
               }).show();
               $("#InviteModal .close").click();
               $("#invaite_email").val("");

               return true;
          }else{
            new Noty({
                   text:  data.message,
                   type: 'error',
                   timeout: 6000,
               }).show();
               return false;
          }
        // console.log(res);
        });
  }
  function reload_screen(){
    var url="{{route('folders.reload_screen',$folder->id)}}";
    $.ajax({
        url: url,
        method: "POST",
        data: {_token:"{{ csrf_token() }}"}
    }).done(
        function(data) {
          if(data['status'] ==1){
               // $('#get-invoice').modal('show');
               $('#folder_body').html(data['html']);
             }
        });
  }
  </script>
@endsection
