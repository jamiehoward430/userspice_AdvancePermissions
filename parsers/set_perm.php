<?php
//NOTE: This also serves as the reference file for how to do One Click Edit with UserSpice. See comments below.
require_once '../../../../users/init.php';
  $db = DB::getInstance();
  $settings = $db->query("SELECT * FROM settings")->first();
  if(!hasPerm([2],$user->data()->id)){
  die("You do not have permission to be here.");
  }
$msg = [];

$type = Input::get('type');
$id = Input::get('id');
$field = Input::get('field');
$value = Input::get('value');
$desc = Input::get('desc');
$token = Input::get('token');

$msg = [];

if($type == 'perm'){
    if(is_numeric($id)){
        if($value == 'true'){
          $msg['msg']=$desc." Granted";
          $value = 1;
        }else{
          $msg['msg']=$desc." Revoked";
          $value = 0;
        }
        $db->update('plg_advanced_perm_matches',$id,[$field=>$value]);
        $msg['success'] = "true";
        $msg['msg'] =$msg['msg']." OK!";
      }else{
        $msg['success'] = "false";
        $msg['msg'] = $msg['msg']." Failed!";
      }
    
}

if($type == 'setting'){
  if($value == 'true' || $value == 'false'){
    if($value == 'true'){
      $msg['msg']=$desc." Enabled";
      $value = 1;
    }else{
      $msg['msg']=$desc." Disabled";
      $value = 0;
    }
    $db->update('settings',1,[$field=>$value]);
    $msg['success'] = "true";
    $msg['msg'] =$msg['msg']." OK!";
  }else{
    $msg['success'] = "false";
    $msg['msg'] = $msg['msg']." Failed!";
  }
}

if($type == 'set_user'){
  if(is_numeric($id)){
    $db->update('users',$id,[$field=>$value]);
    $msg['success'] = "true";
    $msg['msg'] =$msg['msg']." OK!";
  }else{
    $msg['success'] = "false";
    $msg['msg'] = $msg['msg']." Failed!";
  }

}

echo json_encode($msg);