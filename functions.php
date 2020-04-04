<?php
//Please don't load functions system-wide if you don't need them system-wide.
// To make your plugin more efficient on resources, consider only loading resources that need to be loaded when they need to be loaded.
// For instance, you can do
// $currentPage = currentPage();
// if($currentPage == 'admin.php'){ //The administrative dashboard
//   bold("<br>See! I am only loading this when I need it!");
// }
// // Also, please wrap your functions in if(!function_exists())
// if(!function_exists('membershipFunction')) {
//   function membershipFunction(){ }
// }

if(!function_exists('ap_getPerm')) {
  function ap_getPerm($permid,$user){
    $db=DB::getInstance();
    $permexist = 0;
    $settings = $db->query("SELECT * FROM settings")->first();
    if(is_numeric($user)){
      $perms = fetchUserPermissions($user);
      $sql = "";
      if(is_numeric($permid)){
       $permexist = $db->query("SELECT * FROM plg_advanced_perm WHERE id = ?",array($permid))->count();
      }else{
        $permexist = $db->query("SELECT * FROM plg_advanced_perm WHERE name = ?",array($permid))->count();
      }
      if($permexist == 0){
         return false;
      }else{
        $permid = $db->query("SELECT * FROM plg_advanced_perm WHERE name = ?",array($permid))->first()->id;
        $sql .="SELECT * FROM plg_advanced_perm_matches WHERE perm_id = ? AND ( userid = ? OR ";
        $i=0;
        if( $settings->plg_advperm_groups == 1){
          foreach($perms as $perm){
            if(!$i==0){
              $sql .=" OR ";
            }

            $sql .="user_group_id = '".$perm->permission_id."' ";
            $i++;
          }
        }else{
          $userd = $db->query("SELECT * FROM users WHERE id = ?",array($user))->first();
          $sql .="user_group_id = '".$userd->plg_advperm_group."' ";
        }
        $sql .=") AND general = '1'";

         $hasperm = $db->query($sql,array($permid,$user))->count();        
      }
      if($hasperm == 0){
       return false;
      }else{
       return $db->query($sql,array($permid,$user))->first();
     }
   }else{
     return false;
   }
  }
}


if(!function_exists('ap_hasPerm')) {
  function ap_hasPerm($permid,$user){
    $db=DB::getInstance();
    $permexist = 0;
    $settings = $db->query("SELECT * FROM settings")->first();
    if(is_numeric($user)){
      $perms = fetchUserPermissions($user);
      $sql = "";
      if(is_numeric($permid)){
       $permexist = $db->query("SELECT * FROM plg_advanced_perm WHERE id = ?",array($permid))->count();
      }else{
        $permexist = $db->query("SELECT * FROM plg_advanced_perm WHERE name = ?",array($permid))->count();
      }
      if($permexist == 0){
         return false;
      }else{
        $permid = $db->query("SELECT * FROM plg_advanced_perm WHERE name = ?",array($permid))->first()->id;
        $sql .="SELECT * FROM plg_advanced_perm_matches WHERE perm_id = ? AND ( userid = ? OR ";
        $i=0;
        if( $settings->plg_advperm_groups == 1){
          foreach($perms as $perm){
            if(!$i==0){
              $sql .=" OR ";
            }

            $sql .="user_group_id = '".$perm->permission_id."' ";
            $i++;
          }
        }else{
          $userd = $db->query("SELECT * FROM users WHERE id = ?",array($user))->first();
          $sql .="user_group_id = '".$userd->plg_advperm_group."' ";
        }
        $sql .=") AND general = '1'";

         $hasperm = $db->query($sql,array($permid,$user))->count();        
      }
      if($hasperm == 0){
       return false;
      }else{
       return true;
     }
   }else{
     return false;
   }
  }
}


if(!function_exists('ap_canView')) {
  function ap_canView($permid,$user){
    $db=DB::getInstance();
    $permexist = 0;
    $settings = $db->query("SELECT * FROM settings")->first();
    if(is_numeric($user)){
      $perms = fetchUserPermissions($user);
      $sql = "";
      if(is_numeric($permid)){
       $permexist = $db->query("SELECT * FROM plg_advanced_perm WHERE id = ?",array($permid))->count();
      }else{
        $permexist = $db->query("SELECT * FROM plg_advanced_perm WHERE name = ?",array($permid))->count();
      }
      if($permexist == 0){
         return false;
      }else{
        $permid = $db->query("SELECT * FROM plg_advanced_perm WHERE name = ?",array($permid))->first()->id;
        $sql .="SELECT * FROM plg_advanced_perm_matches WHERE perm_id = ? AND ( userid = ? OR ";
        $i=0;
        if( $settings->plg_advperm_groups == 1){
          foreach($perms as $perm){
            if(!$i==0){
              $sql .=" OR ";
            }

            $sql .="user_group_id = '".$perm->permission_id."' ";
            $i++;
          }
        }else{
          $userd = $db->query("SELECT * FROM users WHERE id = ?",array($user))->first();
          $sql .="user_group_id = '".$userd->plg_advperm_group."' ";
        }
        $sql .=") AND can_view = '1'";

         $hasperm = $db->query($sql,array($permid,$user))->count();        
      }
      if($hasperm == 0){
       return false;
      }else{
       return true;
     }
   }else{
     return false;
   }
  }
}


if(!function_exists('ap_canEdit')) {
  function ap_canEdit($permid,$user){
    $db=DB::getInstance();
    $permexist = 0;
    $settings = $db->query("SELECT * FROM settings")->first();
    if(is_numeric($user)){
      $perms = fetchUserPermissions($user);
      $sql = "";
      if(is_numeric($permid)){
       $permexist = $db->query("SELECT * FROM plg_advanced_perm WHERE id = ?",array($permid))->count();
      }else{
        $permexist = $db->query("SELECT * FROM plg_advanced_perm WHERE name = ?",array($permid))->count();
      }
      if($permexist == 0){
         return false;
      }else{
        $permid = $db->query("SELECT * FROM plg_advanced_perm WHERE name = ?",array($permid))->first()->id;
        $sql .="SELECT * FROM plg_advanced_perm_matches WHERE perm_id = ? AND ( userid = ? OR ";
        $i=0;
        if( $settings->plg_advperm_groups == 1){
          foreach($perms as $perm){
            if(!$i==0){
              $sql .=" OR ";
            }

            $sql .="user_group_id = '".$perm->permission_id."' ";
            $i++;
          }
        }else{
          $userd = $db->query("SELECT * FROM users WHERE id = ?",array($user))->first();
          $sql .="user_group_id = '".$userd->plg_advperm_group."' ";
        }
        $sql .=") AND can_edit = '1'";

         $hasperm = $db->query($sql,array($permid,$user))->count();        
      }
      if($hasperm == 0){
       return false;
      }else{
       return true;
     }
   }else{
     return false;
   }
  }
}


if(!function_exists('ap_canDelete')) {
  function ap_canDelete($permid,$user){
    $db=DB::getInstance();
    $permexist = 0;
    $settings = $db->query("SELECT * FROM settings")->first();
    if(is_numeric($user)){
      $permid = $db->query("SELECT * FROM plg_advanced_perm WHERE name = ?",array($permid))->first()->id;
      $perms = fetchUserPermissions($user);
      $sql = "";
      if(is_numeric($permid)){
       $permexist = $db->query("SELECT * FROM plg_advanced_perm WHERE id = ?",array($permid))->count();
      }else{
        $permexist = $db->query("SELECT * FROM plg_advanced_perm WHERE name = ?",array($permid))->count();
      }
      if($permexist == 0){
         return false;
      }else{
        $sql .="SELECT * FROM plg_advanced_perm_matches WHERE perm_id = ? AND ( userid = ? OR ";
        $i=0;
        if( $settings->plg_advperm_groups == 1){
          foreach($perms as $perm){
            if(!$i==0){
              $sql .=" OR ";
            }

            $sql .="user_group_id = '".$perm->permission_id."' ";
            $i++;
          }
        }else{
          $userd = $db->query("SELECT * FROM users WHERE id = ?",array($user))->first();
          $sql .="user_group_id = '".$userd->plg_advperm_group."' ";
        }
        $sql .=") AND can_delete = '1'";

         $hasperm = $db->query($sql,array($permid,$user))->count();        
      }
      if($hasperm == 0){
       return false;
      }else{
       return true;
     }
   }else{
     return false;
   }
  }
}


if(!function_exists('ap_protectedLabel')) {
  function ap_protectedLabel($perm, $userid, $att, $text, $break = false){
    $db=DB::getInstance();
    if(ap_canView($perm,$userid)){
      echo  '<label ' . $att. '>' . $text . '</label>';
      if($break){
        echo '<br>';
      }
    }
  }
}

if(!function_exists('ap_protectedInput')) {
  function ap_protectedInput($perm, $userid, $att, $break = false){
    $db=DB::getInstance();
    if(ap_canEdit($perm,$userid) && ap_canView($perm,$userid)){
     echo '<input ' . $att . '>';
     if($break){
       echo '<br>';
      }

      
    }elseif(ap_canView($perm,$userid)){

      echo '<input ' . $att . ' disabled>';
      if($break){
        echo '<br>';

      }
    }else{
      
    }

  }
}


if(!function_exists('ap_protectedFE')) {
  function ap_protectedFE($perm, $userid, $latt, $iatt, $ltext, $lbreak = false, $ibreak = false){
    $db=DB::getInstance();
		ap_protectedLabel($perm, $userid, $latt, $ltext, $lbreak);
		ap_protectedInput($perm, $userid, $iatt, $ibreak);
  }
}


if(!function_exists('getAdvPermGroups')) {
  function getAdvPermGroups(){
    $db=DB::getInstance();
    return $db->query("SELECT * FROM plg_advanced_perm_groups")->results();
  }
}

if(!function_exists('getAdvGroupPerms')) {
  function getAdvGroupPerms($gid){
    $db=DB::getInstance();
    return $db->query("SELECT * FROM plg_advanced_perm WHERE group_id = ?", array($gid))->results();
  }
}
if(!function_exists('getAdvPermMatches')) {
  function getAdvPermMatches($pid, $ugid){
    $db=DB::getInstance();
   // $permQ = $db->query("SELECT * FROM plg_advanced_perm_matches WHERE perm_id = ? AND user_group_id = ?", array($perm->id, $ugid));
   // $permC = $permQ->count();
   // $perms = $permQ->results();
    return $db->query("SELECT * FROM plg_advanced_perm_matches WHERE userid = ? AND perm_id = ? AND user_group_id = ?", array(-1,$pid, $ugid))->first();
  }
}

if(!function_exists('echoAdvPermGroup')) {
  function echoAdvPermGroup($id){
    $db=DB::getInstance();
    $name = $db->query("SELECT * FROM plg_advanced_perm_groups WHERE id = ?", array($id))->first();
    echo $name->name;
  }
}

if(!function_exists('ap_initGroupPerms')) {
  function ap_initGroupPerms($gid, $ugid){
    $db=DB::getInstance();
    $permG = $db->query("SELECT * FROM plg_advanced_perm WHERE group_id = ?", array($gid));
    $permGC = $permG->count();
    $permGR = $permG->results();
    if($permGC > 0){
      foreach($permGR as $perm){
        $permQ = $db->query("SELECT * FROM plg_advanced_perm_matches WHERE perm_id = ? AND user_group_id = ?", array($perm->id, $ugid));
        $permC = $permQ->count();
        $perms = $permQ->results();
        if($permC == 0){
            $fields = array(
               'perm_id'=>$perm->id,
               'user_group_id'=>$ugid,
               'can_view'=>0,
               'can_edit'=>0,
               'can_delete'=>0,
               'general'=>0        
             );
            $db->insert('plg_advanced_perm_matches',$fields);
       
        }

      }
    }
  }
}
