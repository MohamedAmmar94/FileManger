<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Validator;
use InviteController;
class AjaxController extends Controller
{
    public function check_exist_email(Request $request){
      $validation = Validator::make($request->input(), [
        'email' =>'required|exists:users,email'
        ]);
        if ($validation->fails()) {
            $errors = json_decode($validation->errors());
            $errors_string = '';
            foreach ($errors as $error) {
                $errors_string .= $error[0] . "\n<br>";
            }
            return ['status' => 0, 'message' => $errors_string, 'data' => null];
        }
          $this->sent_invetation($request->email);
          //============>sent Invettion email < ======================//
          return ['status' => 1, 'message' => "invetation sent successfully", 'data' => null];

    }
}
