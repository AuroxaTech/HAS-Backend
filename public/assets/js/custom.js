///////////////////// CHANGE PASSWORD //////////////
// ///////////////  SAVE PEO PROGRAM ////////
function changePassword(siteurl,loginurl){
    jQuery.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });     
    const   new_password = $('#new_password').val(),
            confirm_new_password    = $('#confirm_new_password').val();
    if(new_password === confirm_new_password){
        $('#span_change_password').text('');
        $('#change-password-btn').prop('disabled',true);
        $.ajax({
            type: "POST",
            url: siteurl,
            data: {
                new_password: new_password,
                confirm_new_password: confirm_new_password,
            },
            success: function(response){
                window.location.href = loginurl;
            }
        });
    }else{
        $('#span_change_password').text('Please Enter Valid & Same Password');
    }
} 
///////////////////// validators////////////////////
            
			$('#inter_obt').keyup(function(){
				var obt_mark_int = $('#inter_obt').val();
				if(obt_mark_int >= 1100){
					$('#span_inter_obt').text('Please Enter Valid Number');
				}else{
					$('#span_inter_obt').text('');
				}
			})

			$('#matric_obt').keyup(function(){
				var obt_mark_int = $('#matric_obt').val();
				if(obt_mark_int >= 1100){
					$('#span_matric_obt').text('Please Enter Valid Number');
				}else{
					$('#span_matric_obt').text('');
				}
			})
			$('#cnic').keyup(function(){
				var cnic_no = $("#cnic").val();
				var cnic_no_regex = /^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/;
	
				if(cnic_no_regex.test(cnic_no)) 
				{
					$('#span_cnic').text('');
				}
				else 
				{
					$('#span_cnic').text('Please Enter Valid Number');
				}
			})
			$('#guardian_cnic').keyup(function(){
				var guardian_cnic_no = $("#guardian_cnic").val();
				var guardian_cnic_no_regex = /^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/;
	
				if(guardian_cnic_no_regex.test(guardian_cnic_no)) 
				{
					$('#span_guardian_cnic').text('');
				}
				else 
				{
					$('#span_guardian_cnic').text('Please Enter Valid Number');
				}
			})
//////////////////////   //// Menu Template


function menus(url){
   
    var menutitle =$("#name").val();
    var menutitlearray= menutitle.split(' ');
    var menutitlestring= menutitlearray.join('-');
    // alert(menutitlearray[0].toLowerCase());
    if(menutitlearray[0].toLowerCase() === 'manage'){
        $("#menudescription").val(url+'/'+menutitlestring.charAt(0).toLowerCase()+ menutitlestring.toLowerCase().slice(1));
    }else{
        $("#menudescription").val(url+'/manage-'+menutitlestring.charAt(0).toLowerCase()+ menutitlestring.slice(1));
    }

}

//// DELETE DATA FROM LISTING //////

function deleteData(siteurl){

    jQuery.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    const id = $("#delete_id").val();
    $.ajax({
        type: "GET",
        url: siteurl,
        data: {
            id: id,
        },
        // method: 'POST', //Post method,
        // dataType: 'json',
        success: function(response){
            location.reload();
        }
    });
}

/////// OPEN MODEL FOR DELETION ///////////

function openDeleteModel(id){
    $('#delete_approve').modal('toggle');
    $('#delete_approve').modal('show');
    $("#delete_id").val(id);
}

/////// OPEN MODEL FOR Permission ///////////

function openPermissionModel(siteurl , id){
    $('#manage_permission').modal('toggle');
    $('#manage_permission').modal('show');
    $("#role_id").val(id);
    roleHasPermission(siteurl ,id);
}

////////////////////// CHANGE & SAVE PERMISSION ///////////////////
function roleHasPermission(siteurl, id){
    jQuery.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });     
    $.ajax({
        type: "POST",
        url: siteurl,
        data: {
            id: id,
        },
         // method: 'POST', //Post method,
        // dataType: 'json',
        success: function(response){
            console.log(response)
            $('#permissiontable').html(response);
        }
    });
}
////////////////////// CHANGE & SAVE PERMISSION ///////////////////
function changePermission(siteurl,module_id,permission_name,unique_id){
    jQuery.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    const id = $("#role_id").val();
    if($("#checkbox"+unique_id).is(":checked")){
        var checked = 1;      
    } else if($("#checkbox"+unique_id).is(":not(:checked)")){            
        var checked = 0;      
    }         
    $.ajax({
        type: "POST",
        url: siteurl,
        data: {
            id: id,
            module_id: module_id,
            permission_name: permission_name,
            checked: checked,
        }
    });
}

/////// CHANGE STATUS FROM LISTING


function changeStatus(siteurl,id,status){

    jQuery.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "GET",
        url: siteurl,
        data: {
            id: id,
            status: status,
        },
        success: function(response){
            location.reload();
        }
    });
}



// /////////// Permalink ////////////

function creat_link() {
    var res = $("#name").val();
    res = res.toLowerCase();
    res = res.replace(/ /gi, '-');
    $("#permalink").val(res);
}

