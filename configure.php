
<?php 

if(!in_array($user->data()->id,$master_account)){ Redirect::to($us_url_root.'users/admin.php');} //only allow master accounts to manage plugins! ?>

<?php
include "plugin_info.php";
pluginActive($plugin_name);
$view = Input::get('v');
$groups = getAdvPermGroups();
$editgroup = Input::get('editgroup');
$editperm = Input::get('editperm');
$delperm = Input::get('delperm');
$delgroup = Input::get('delgroup');
$openacc = Input::get('o');

$aid = "accordion";
$tid = "paginate";

$us_permsQ = $db->query("SELECT * FROM permissions");
$us_permsR = $us_permsQ->results();

$LoadTestPerms = false;
if($LoadTestPerms){
foreach(range(0,10) as $p){
	$fields = array(
		'name'=>"Test Group ".$p
	);
	//$db->insert('plg_advanced_perm_groups',$fields);
}

$ga = $db->query("SELECT * FROM plg_advanced_perm_groups");
$gr = $ga->results();
foreach($gr as $g){
	foreach(range(0,10) as $p){
		$fields = array(
			'name'=>"Test Permission ".$p,
			'description'=>"Test Description ".$p." ".$g->id,
			'group_id'=>$g->id
		
		);
		$db->insert('plg_advanced_perm',$fields);
	}
}
}

if($editperm != ''){
	if(is_numeric($editperm)){
		
		$permEditQ = $db->query("SELECT * FROM plg_advanced_perm WHERE id = ?",array($editperm));
		$permEditC = $permEditQ->count();
		if($permEditC > 0){
			$Pitem = $permEditQ->first();
			$openacc = $Pitem->group_id;
		}else{
			Redirect::to('admin.php?view=plugins_config&plugin=AdvancedPermissions&err=Permission+not+found!');
		}
	}
}
if($delperm != ''){
	if(is_numeric($delperm)){
		$permdelQ = $db->query("DELETE FROM plg_advanced_perm WHERE id = ?",array($delperm));
		$permdelR = $permdelQ->results();
		Redirect::to('admin.php?view=plugins_config&plugin=AdvancedPermissions&err=Permission+deleted!&o='.$openacc);
		
	}
}
if($delgroup != ''){
	if(is_numeric($delgroup)){
		if(isset($_POST['editgroup'])){
			$editgroup=$delgroup;
			$openacc=$delgroup;
		}else{
		$groupdelQ = $db->query("DELETE FROM plg_advanced_perm_groups WHERE id = ?",array($delgroup));
		$groupdelR = $groupdelQ->results();
		$permdelQ = $db->query("SELECT * FROM plg_advanced_perm WHERE group_id = ?",array($delgroup));
		$permdelR = $permdelQ->results();
		foreach($permdelR as $perm){
	
			$permdelQa = $db->query("DELETE FROM plg_advanced_perm_matches WHERE perm_id = ?",array($perm->id));
			$permdelRa = $permdelQa->results();

			$permdelQb = $db->query("DELETE FROM plg_advanced_perm WHERE id = ?",array($perm->id));
			$permdelRb = $permdelQb->results();
		}

		Redirect::to('admin.php?view=plugins_config&plugin=AdvancedPermissions&err=Group+deleted!');
	}
	}
}
if($editgroup != ''){
	if(is_numeric($editgroup)){
		$groupEditQ = $db->query("SELECT * FROM plg_advanced_perm_groups WHERE id = ?",array($editgroup));
		$groupEditC = $groupEditQ->count();
		if($groupEditC > 0){
			$Gitem = $groupEditQ->first();
		}else{
			Redirect::to('configure.php?err=Group+not+found');
		}
	}
}
if(!empty($_POST)){
	
	if(!empty($_POST['groupform'])){
		$fields = array(
			'name'=>Input::get('groupname')
		);

		if(is_numeric($editgroup)){
			$db->update('plg_advanced_perm_groups',$editgroup,$fields);
			Redirect::to('admin.php?view=plugins_config&plugin=AdvancedPermissions&err=Group+updated!&o='.$editgroup);
		}else{
			$db->insert('plg_advanced_perm_groups',$fields);
			Redirect::to('admin.php?view=plugins_config&plugin=AdvancedPermissions&err=Group+added!');
		}
	}
	if(!empty($_POST['permform'])){
		$fields = array(
			'name'=>Input::get('permname'),
			'description'=>Input::get('desc'),
			'group_id'=>Input::get('groupid')
			
		);
		if(is_numeric($editperm)){
			$db->update('plg_advanced_perm',$editperm,$fields);
			Redirect::to('admin.php?view=plugins_config&plugin=AdvancedPermissions&err=Permission+updated!&o='.Input::get('groupid'));

		}else{
			$db->insert('plg_advanced_perm',$fields);
			Redirect::to('admin.php?view=plugins_config&plugin=AdvancedPermissions&err=Permission+added!&o='.Input::get('groupid'));
		}
	}	
}
?>

<?php 
if($view == ''){
?>
<div id="page-wrapper"><!-- .wrapper -->
	<div class="container-fluid"><!-- .container -->	
		<div class="row">
			<div class="col-sm-12"><!-- .col1 -->
				<a href="<?=$us_url_root?>users/admin.php?view=plugins">Return to the Plugin Manager</a>
				<h1 class="text-center">Manage Advanced Permissions</h1>
				<h1 class="text-center"><a href="admin.php?view=plugins_config&plugin=AdvancedPermissions&v=docs">Documentation</a></h1>
			</div><!-- /.col1 -->

			<?php  if(ap_canView('Test Section',$user->data()->id)){ ?>
			<div class="col-sm-12"><!-- .col1 -->	

			<?php if(ap_hasPerm('Test Perm',$user->data()->id)){?>
				<h6 style="color:#008000;" class="text-center">We have general permission for Test Perm</h6>
			<?php }else{ ?>
				<h6 style="color:#FF0000;" class="text-center">We dont have general permission for Test Perm</h6>
			<?php }	?>

			<?php if(ap_canView('Test Perm',$user->data()->id)){?>
				<h6 style="color:#008000;" class="text-center">We have view permission for Test Perm</h6>
			<?php }else{ ?>
				<h6 style="color:#FF0000;" class="text-center">We dont have view permission for Test Perm</h6>
			<?php }	?>

			<?php if(ap_canEdit('Test Perm',$user->data()->id)){?>
				<h6 style="color:#008000;" class="text-center">We have edit permission for Test Perm</h6>
			<?php }else{ ?>
				<h6 style="color:#FF0000;" class="text-center">We dont have edit permission for Test Perm</h6>
			<?php }	?>

			<?php if(ap_canDelete('Test Perm',$user->data()->id)){?>
				<h6 style="color:#008000;" class="text-center">We have delete permission for Test Perm</h6>
			<?php }else{ ?>
				<h6 style="color:#FF0000;" class="text-center">We dont have delete permission for Test Perm</h6>
			<?php }	?>

			<?php ap_protectedLabel('Test Label', $user->data()->id, 'for="test"', 'Test Label', true); ?>
			<?php ap_protectedInput('Test Input', $user->data()->id, ' type="text" name="test" class="form-control" value="Test"', true); ?>
			<?php ap_protectedFE('Test FE', $user->data()->id, 'for="test2"', ' type="text" name="test2" class="form-control" value="Test"', 'Test Label 2', true, true); ?>
			</div><!-- /.col1 -->

			<?php  } ?>
			
			<div class="col-sm-6">
				<h3><?php
				if($editgroup != ''){
					echo "Edit Group";
				}else{
					echo "New Group";
				}?></h3>
				<form class="" action="admin.php?view=plugins_config&plugin=AdvancedPermissions&editgroup=<?=$editgroup?>&o=<?=$editgroup?>" method="post">
					<input type="hidden" name="groupform" value="1">
					<label for="">Group Name*</label>
					<input type="text" name="groupname" class="form-control" value="<?php if($editgroup != ''){ echo $Gitem->name;}?>">
					<br>
					<?php if($editgroup == ''){ ?>
					<input type="submit" name="submit" value="Add New Group" class="btn btn-primary">
					<?php }else{ ?>
					<input type="submit" name="submit" value="Edit Group" class="btn btn-danger">

					<?php } ?>
					
 				</form>
				 <div class="form-group">
                	<label for="site_offline">Permission Inheritance <a href="#!" tabindex="-1" title="Note" data-trigger="focus" class="nounderline" data-toggle="popover" data-content="Enabling this option will allow users with multiple permission level's to inherit advanced permission's for all those level's. By default user's must be assinged one level. IMPORTANT! this will not revoke permissions from lower levels, only grant additional permissions from higher levels"><i class="fa fa-question-circle"></i></a></label>
                	<span style="float:right;">
                  	<label class="switch switch-text switch-success">
                    <input id="site_offline" type="checkbox" class="switch-input set_toggle" data-type="plg_advperm_groups" data-desc="Permission Inheritance" <?php if($settings->plg_advperm_groups==1) echo 'checked="true"'; ?>>
                    <span data-on="Yes" data-off="No" class="switch-label"></span>
                    <span class="switch-handle"></span>
                  	</label>
               		</span>
              	</div>
				
				<div class="form-group">
                	<label for="force_user_pr">Set user groups <a href="#" tabindex="-1" title="Note" class="nounderline"></a></label>
                	<span style="float:right;">
						<form class="" action="admin.php?view=plugins_config&plugin=AdvancedPermissions&v=ManageUsers" method="post">
                  			<button type="submit" name="manage_users" id="manage_users" class="btn btn-primary input-group-addon">Manage User's</button>
				 	 	</form>
                  	<span>
              	</div>
			</div> <!-- /.col -->

			<div class="col-sm-6">
				<h3><?php
				if($editperm != ''){
					echo "Edit Permission";
				}else{
					echo "New Permission";
				}?></h3>
				<form class="" action="admin.php?view=plugins_config&plugin=AdvancedPermissions&editperm=<?=$editperm?>&o=<?=$openacc?>" method="post">
					<input type="hidden" name="permform" value="1">
					<label for="">Group*</label>
					<select class="form-control" name="groupid">
						<option value="">--Choose Group--</option>
						<?php foreach($groups as $g){?>
							<option <?php if($editperm != '' && ($Pitem->group_id == $g->id)){ echo "selected";}?> value="<?=$g->id?>"><?=$g->name?></option>
						<?php } ?>
					</select>
					
					<label for="">Permission Name*</label>
					<input type="text" name="permname" class="form-control" value="<?php if($editperm != ''){ echo $Pitem->name;}?>">
					
					<label for="">Permission Description*</label>
					<input type="text" name="desc" class="form-control" value="<?php if($editperm != ''){ echo $Pitem->description;}?>">
					<br>
					<?php if($editperm == ''){ ?>
					<input type="submit" name="submit" value="Add New Permission" class="btn btn-primary">
					<?php }else{ ?>
					
					<input type="submit" name="submit" value="Edit Permission" class="btn btn-danger">
					<?php } ?>
				</form>
			</div> <!-- /.col -->
		</div> <!-- /.row -->


		<div class="row"><!-- .row -->

			<div class="col-md-12"><!-- .col2 -->

				<ul id="tabs" class="nav nav-tabs">
					<?php
					$i = 0;
					$gi = 0;
					foreach($us_permsR as $us_perm){?>
						<li class="nav-item"><a href="" data-target="#tab<?=$us_perm->id?>" data-toggle="tab" class="nav-link small text-white bg-dark text-uppercase"><?=$us_perm->name?></a></li>
					<?php } ?>
				</ul>
				<br>
				<div id="tabsContent" class="tab-content"><!-- .tabsContent -->
				<?php
					foreach($us_permsR as $us_perm){
						$accid = $aid . $gi;
						?>
						<div id="tab<?=$us_perm->id?>" class="tab-pane fade"><!-- .tabpanel -->
							<div class="col-sm-12">	<!-- .col3 -->
								<div id="<?=$accid?>" class="accordion">
                           			<div class="card card-default panel">
										<?php 

									   	foreach($groups as $group){
											$tpid = $tid . $i; $i++; ?>

											<a href="" data-toggle="collapse" data-target="#group<?=$group->id?>_<?=$us_perm->id?>" class="card-header small text-white bg-dark collapsed" >
											<span class="card-title">
												<?=$group->name;?> [ID: <?=$group->id;?>]
												
											</span><i class="float-right fa fa-ellipsis-v"></i>
											</a>

											
											<?php
											ap_initGroupPerms($group->id,$us_perm->id);
											$perms = getAdvGroupPerms($group->id);
											if($openacc == $group->id){$show='show';}else{$show='';}?>
											<div id="group<?=$group->id?>_<?=$us_perm->id?>" class="card-body collapse <?=$show?>" data-parent="#<?=$accid?>"><!-- card body-->
												<h3 class="text-center"><?=$group->name;?> Permsissions for <?=$us_perm->name;?></h3>
												<span class="float-right" >
													<form class="" action="admin.php?view=plugins_config&plugin=AdvancedPermissions&delgroup=<?=$group->id?>" id="del<?=$group->id?>" method="post">
														<input type="submit" name="editgroup" value="Edit Group" class="btn btn-primary">
														<input type="submit" name="submit" value="Delete Group" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this group and all associated permission\'s?');">
													</form>
												</span>
												<table  id="<?=$tpid?>" class='table table-hover table-list-search'>
													<thead>
														<th style="width:3%;">ID</th>
														<th style="width:29%;">Name</th>
														<th style="width:50%;">Description</th>

														<th style="width:3%;" align="center" >Can View</th>
														<th style="width:3%;" align="center" >Can Edit</th>
														<th style="width:3%;" align="center" >Can Delete</th>
														<th style="width:3%;" align="center" >Other</th>

														<th style="width:3%;" align="center" >Edit</th>
														<th style="width:3%;" align="center" >Delete</th>
														
													</thead>
													<tbody>	
													<?php				
													 foreach($perms as $perm){
														 
														 $m = getAdvPermMatches($perm->id,$us_perm->id);?>
														<tr>
															<td style="width:3%;"><?=$perm->id?></td>
															<td style="width:32%;"><?=$perm->name?></td>
															<td style="width:50%;"><?=$perm->description?></td>
															<td style="width:3%;">
															<label class="switch switch-text switch-success">
                   											 	<input id="switch_<?=$perm->id?>_<?=$us_perm->id?>_view" type="checkbox" data-type="can_view" data-perm-id="<?=$m->id?>" class="switch-input ap_toggle" data-desc="View <?=$perm->name?> updated for group <?=$us_perm->name?>," <?php if($m->can_view==1) echo 'checked="true"'; ?>>
                    											<span data-on="Yes" data-off="No" class="switch-label"></span>
                    											<span class="switch-handle"></span>
                 											 </label>
															</td>
															<td style="width:3%;">
															<label class="switch switch-text switch-success">
                   											 	<input id="switch_<?=$perm->id?>_<?=$us_perm->id?>_edit" type="checkbox" data-type="can_edit" data-perm-id="<?=$m->id?>" class="switch-input ap_toggle" data-desc="Edit <?=$perm->name?> updated for group <?=$us_perm->name?>," <?php if($m->can_edit==1) echo 'checked="true"'; ?>>
                    											<span data-on="Yes" data-off="No" class="switch-label"></span>
                    											<span class="switch-handle"></span>
                 											 </label>
															</td>
															<td style="width:3%;">
															<label class="switch switch-text switch-success">
                   											 	<input id="switch_<?=$perm->id?>_<?=$us_perm->id?>_del" type="checkbox" data-type="can_delete" data-perm-id="<?=$m->id?>" class="switch-input ap_toggle" data-desc="Delete <?=$perm->name?> updated for group <?=$us_perm->name?>," <?php if($m->can_delete==1) echo 'checked="true"'; ?>>
                    											<span data-on="Yes" data-off="No" class="switch-label"></span>
                    											<span class="switch-handle"></span>
                 											 </label>
															</td>
															<td style="width:3%;">
															<label class="switch switch-text switch-success">
                   											 	<input id="switch_<?=$perm->id?>_<?=$us_perm->id?>_general" type="checkbox" data-type="general" data-perm-id="<?=$m->id?>" class="switch-input ap_toggle" data-desc="Other <?=$perm->name?> updated for group <?=$us_perm->name?>," <?php if($m->general==1) echo 'checked="true"'; ?>>
                    											<span data-on="Yes" data-off="No" class="switch-label"></span>
                    											<span class="switch-handle"></span>
                 											 </label>
															</td>
															<td style="width:3%;" align="center" ><a href="admin.php?view=plugins_config&plugin=AdvancedPermissions&editperm=<?=$perm->id?>"><i style="color:#000000; " class="fa fas fa-edit fa-lg"></i></a></td>
															<td style="width:3%;" align="center" ><a onclick="return confirm('Are you sure you want to delete this permission?');" href="admin.php?view=plugins_config&plugin=AdvancedPermissions&delperm=<?=$perm->id?>&o=<?=$group->id?>"><i style="color:#FF0000;" class="fa fas fa-trash fa-lg"></i></a></td>
														</tr>	
													<?php } ?>
													</tbody>
												</table>
											</div>
										<?php
									    } ?>

									</div><!-- /.col3 -->
								</div><!-- /.tabpanel-->
							</div><!-- /.col3 -->
						</div><!-- /.tabpanel-->
						<?php
						 $gi++;
					} ?>
				</div><!-- /.tabsContent -->
			</div><!-- /.col2 -->	
		</div><!-- /.row -->	
	</div> <!-- /.container -->	
</div> <!-- /.wrapper -->
<?php
}elseif($view == 'ManageUsers'){
	$userData = fetchAllUsers("permissions DESC,id",[],false); //Fetch information for all users

	?>
<div id="page-wrapper"><!-- .wrapper -->
	<div class="container-fluid"><!-- .container -->	
		<div class="row">
			<div class="col-sm-12"><!-- .col1 -->
				<h1 class="text-center">Manage Advanced Permissions</h1>
			</div><!-- /.col1 -->


			<div class="col-sm-12"><!-- .col1 -->




  <div class="content mt-3">
    <h2>Manage Users</h2>
 
    <hr />

   
    <div class="alluinfo">&nbsp;</div>
    <div class="allutable">
      <table id="paginate_users" class='table table-hover table-list-search'>
        <thead>
          <tr>
            <th>ID</th>
			<th>Username</th>
			<th>Name</th>
			<th>Email</th>
			<th>Group</th>
          </tr>
        </thead>
        <tbody>
          <?php
          //Cycle through users
          foreach ($userData as $v1) {
            ?>
            <tr>
              <td><?=$v1->id?></td>
              <td><?=$v1->username?></td>
              <td><?=$v1->fname?> <?=$v1->lname?></td>
              <td><?=$v1->email?></td>
			  <td>
			  <select class="form-control ap_select" name="groupid" data-user-id="<?=$v1->id?>">
				<option value="">--Choose Group--</option>
					<?php foreach($us_permsR as $g){?>
						<option <?php if($v1->plg_advperm_group == $g->id){ echo "selected";}?> value="<?=$g->id?>"><?=$g->name?></option>
					<?php } ?>
				</select>
			  </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php if($showAllUsers!=1) {?><a href="?view=users&showAllUsers=1" class="btn btn-primary nounderline pull-right">Show All Users</a><?php } ?>
      <?php if($showAllUsers==1) {?><a href="?view=users" class="btn btn-primary nounderline pull-right">Show Active Users Only</a><?php } ?>
    </div>
  </div>


			</div><!-- /.col1 -->
		</div><!-- /.row -->	
	</div> <!-- /.container -->	
</div> <!-- /.wrapper -->
<?php
}elseif($view == 'docs'){

	        $xmlDoc = new DOMDocument();
            $xmlDoc->load($abs_us_root.$us_url_root.'usersc/plugins/AdvancedPermissions/assets/docs.xml');
            $items=$xmlDoc->getElementsByTagName('function');
            ?>
			<div id="page-wrapper"><!-- .wrapper -->
	<div class="container-fluid"><!-- .container -->	
		<div class="row">
			<div class="col-sm-12"><!-- .col1 -->
            <br>
            <h3>Advanced Permissions Documentation (<a href="admin.php?view=plugins_config&plugin=AdvancedPermissions">Return to Plugin</a>)</h3><br>
          Functions.<br><br>
          <?php for ($i=0; $i < $items->length ; $i++) { ?>
            <strong><a href="#<?=$items->item($i)->getElementsByTagName('name')->item(0)->childNodes->item(0)->nodeValue;?>">
              <?=$items->item($i)->getElementsByTagName('name')->item(0)->childNodes->item(0)->nodeValue;?>
                    </a>
            </strong><br>
          <?php } ?><br>
          <?php for ($i=0; $i < $items->length ; $i++) { ?>
            <div class="card" id="<?=$items->item($i)->getElementsByTagName('name')->item(0)->childNodes->item(0)->nodeValue;?>">
              <div class="card-header"><strong>function <?=$items->item($i)->getElementsByTagName('name')->item(0)->childNodes->item(0)->nodeValue;?></strong></div>
              <div class="card-body"><strong>Description:</strong> <br><?=$items->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;?></strong></div>
				<?php for ($ii=0; $ii < $items->item($i)->getElementsByTagName('example')->length ; $ii++){ ?>
			  
			  		<div class="card-footer">Example <?=$ii+1?>: <font color="blue"><?=$items->item($i)->getElementsByTagName('example')->item($ii)->childNodes->item(0)->nodeValue;?></font></div>


	 			<?php } ?>
        </div>
          		<?php } ?>
				</div><!-- /.col1 -->
			</div><!-- /.row -->	
		</div> <!-- /.container -->	
	</div> <!-- /.wrapper -->
<?php
}
?>


<script type="text/javascript" src="../../../../users/js/pagination/datatables.min.js"></script>
<script src="../../../../users/js/jwerty.js"></script>

<script>
    $(document).ready(function() {

	function ap_messages(data) {
    console.log("messages found");
    $('#messages').removeClass();
    $('#message').text("");
    $('#messages').show();
    if(data.success == "true"){
      $('#messages').addClass("sufee-alert alert with-close alert-success alert-dismissible fade show");
    }else{
      $('#messages').addClass("sufee-alert alert with-close alert-success alert-dismissible fade show");
    }
    $('#message').text(data.msg);
    $('#messages').delay(4000).fadeOut('slow');

  	}

	$( ".ap_toggle" ).change(function() { //use event delegation
    var value = $(this).prop("checked");
    $(this).prop("checked",value);

    var field = $(this).attr("id"); //the id in the input tells which field to update
    var desc = $(this).attr("data-desc"); //For messages
	var groupid = $(this).attr("data-group-id");
	var permid = $(this).attr("data-perm-id");
	var datatype = $(this).attr("data-type");

    var formData = {
	  'value' 					: value,
	  'id' 						: permid,
      'field'					: datatype,
	  'desc'					: desc,
      'type'          			: 'perm',
    };

    $.ajax({
      type 		: 'POST',
      url 		: '../usersc/plugins/AdvancedPermissions/parsers/set_perm.php',
      data 		: formData,
      dataType 	: 'json',
    })

    .done(function(data) {
      ap_messages(data);
    })
  });

	$( ".set_toggle" ).change(function() { //use event delegation
    var value = $(this).prop("checked");
    $(this).prop("checked",value);

    var field = $(this).attr("id"); //the id in the input tells which field to update
    var desc = $(this).attr("data-desc"); //For messages
	var groupid = $(this).attr("data-group-id");
	var permid = $(this).attr("data-perm-id");
	var datatype = $(this).attr("data-type");
	
    var formData = {
	  'value' 					: value,
	  'id' 						: permid,
      'field'					: datatype,
	  'desc'					: desc,
      'type'          			: 'setting',
    };

    $.ajax({
      type 		: 'POST',
      url 		: '../usersc/plugins/AdvancedPermissions/parsers/set_perm.php',
      data 		: formData,
      dataType 	: 'json',
    })

    .done(function(data) {
      ap_messages(data);
    })
  });

  $( ".ap_select" ).change(function() { //use event delegation
    var value = $(this).prop("value");
    $(this).prop("value",value);

    var field = $(this).attr("id"); //the id in the input tells which field to update
    var desc = $(this).attr("data-desc"); //For messages
	var userid = $(this).attr("data-user-id");
	
    var formData = {
	  'value' 					: value,
	  'id' 						: userid,
      'field'					: 'plg_advperm_group',
	  'desc'					: desc,
      'type'          			: 'set_user',
    };

    $.ajax({
      type 		: 'POST',
      url 		: '../usersc/plugins/AdvancedPermissions/parsers/set_perm.php',
      data 		: formData,
      dataType 	: 'json',
    })

    .done(function(data) {
      ap_messages(data);
    })
  });


  $('#paginate_users').DataTable({"pageLength": 25,"stateSave": true,"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]], "aaSorting": []}); 
		<?php
		
			foreach(range(0,$i) as $p){
				echo '$(\'#'.$tid.$p.'\').DataTable({"pageLength": 10,"stateSave": false,"aLengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]], "aaSorting": []}); ';
			}
		?>

    });
</script>