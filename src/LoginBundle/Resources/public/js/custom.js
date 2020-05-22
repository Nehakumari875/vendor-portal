$(document).ready(function() { 

  var sortBy = getUrlVars()["sortby"];
  $("#assetFilter").val(sortBy);

  // Read a page's GET URL variables and return them as an associative array.
  function getUrlVars()
  {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  }


  $('#imageList').hide();

  var $el = $(this);
  var option = '';
  $.ajax({
    type: "GET",
    url: "/user-list?query="+$el.val(),    
    success: function(data){
      var parsedData = jQuery.parseJSON(data);
      $.each(parsedData, function(i, item) {
        $("#browsers").append('<option value=' + item.value + '>' + item.label + '</option>');
      });
    }
  });


  $(document).on("click", "#list", function(event){event.preventDefault();$('#folderList .item').addClass('list-group-item').removeClass('grid-group-item');});
  $(document).on("click", "#list", function(event){event.preventDefault();$('#imageList .item').addClass('list-group-item').removeClass('grid-group-item');});

  $(document).on("click", "#grid", function(event){event.preventDefault();$('#folderList .item').removeClass('list-group-item');})
  $(document).on("click", "#grid", function(event){event.preventDefault();$('#imageList .item').removeClass('list-group-item');})

  $('#folderList .item').addClass('grid-group-item');

//Share Asset
  $(document).on("click", "#shareAsset", function(){
    var $el = $(this);
    var assetType = $el.data('type');
    var folderId = $el.data('id');
    if(assetType == 'folder'){
      $.ajax({  
       type:"GET",
       url:"/create-zip?assetId="+folderId+"&type=asset",
       success:function(data){ 
        var parseData = jQuery.parseJSON(data);
        console.log(parseData);
        window.location = '/download-as-zip?jobId='+parseData.jobId+'&id='+folderId;
      }
    });
    }

  });


//Breadcrumb
  $(document).on("click", ".breadCrumbLevel" , function() {
    
    $('#folderList').show();
    $('#imageList').hide();
  }); 

  //Create Zip
  $(document).on("click", "div.download", function(){
   
    var $el = $(this);
    var assetType = $el.data('type');
    var folderId = $el.data('id');
    if(assetType == 'folder'){
      $.ajax({  
       type:"GET",
       url:"/create-zip?assetId="+folderId,
       success:function(data){ 
        var parseData = jQuery.parseJSON(data);
        console.log(parseData);
        window.location = '/download-as-zip?jobId='+parseData.jobId+'&id='+folderId;
      }
    });
    }

  });

  var arr = [
  { "id" : "1", "foldername" : "Home"}        
  ];
  $(document).on("click", "div.folder" , function() {

    $('#folderList').hide();
    $('#imageList').show();
    var $el = $(this);
    var parentFolderId = $el.data('id');
    var parentFolderName = $el.data('name');
    var resultObject = breadcrumbArray(parentFolderId, parentFolderName);

    if(parentFolderName == 'Home'){
      $('.items .breadcrumb').html('');
      $('.breadcrumb').append('<li class="breadCrumbLevel" data-name="'+parentFolderName+'" data-id="'+parentFolderId+'"><a href="#">'+parentFolderName+'</a></li>');
    }else{

      $('.items .breadcrumb').html('');
      $.each(resultObject , function( key, value ) {
        console.log(resultObject);
        $('.breadcrumb').append('<li class="breadCrumbLevel" data-name="'+value.folderName+'" data-id="'+value.id+'"><a href="#">'+value.folderName+'</a></li>'); 
      });


    }
    $.ajax({  
     type:"GET",
     url:"/asset/list?parentId="+parentFolderId,
     success:function(data){ 
       $('#imageList').html(''); 
       var parseData = jQuery.parseJSON(data);
       var dataItems = "";
       var getUrl = window.location;
       var baseUrl = getUrl .protocol + "//" + getUrl.host;
       var html = '';
       $.each(parseData, function(index, item) {

        dataItems += index + ": " + item + "\n";
        var url = baseUrl+item.path;
        if(item.type == 'folder'){
          var dclass = 'folder';
          html = '<div class="item col-xs-4 col-lg-4 grid-group-item"><div class="thumbnail card"><div class="'+dclass+'" data-id="'+item.id+'" id="parentFolder" data-name="'+item.filename+'">   <a href="#" data-type="'+item.type+'"> <i class="fa fa-folder-open fa-5x group list-group-image img-fluid" style="font-size:100px;"></i></a></div><div class="caption card-body"><h4 class="group card-title inner list-group-item-heading"> '+item.filename+' </h4><p class="group inner list-group-item-text">description... Lorem ipsum dolor sit amet, consectetuer adipiscing elit,sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p></div><div class="download" data-type="'+item.type+'" data-id="'+item.id+'"><i class="fa fa-download  fa-3x" style="font-size:20px;"></i></div></div></div>';

        }else if(item.type == 'image'){
          var dclass = 'image';
          html = '<div class="item col-xs-4 col-lg-4 grid-group-item thumb"><div class="thumbnail card"><div class="'+dclass+'" data-id="'+item.id+'" id="parentFolder" data-name="'+item.filename+'"><a href="/asset-detail?id='+item.id+'" class="fancybox"><img  src="'+item.thumbnail+'" class="group list-group-image img-fluid rootImage img-fluid"  alt="" height="300px" width="200px"></a></div><div class="caption card-body"><h4 class="group card-title inner list-group-item-heading">'+item.filename+'</h4><p class="group inner list-group-item-text">description... Lorem ipsum dolor sit amet, consectetuer adipiscing elit,sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p></div><div class="downloadImg" data-type=""'+item.type+'""><a href="'+item.path+'" data-id="'+item.id+'" download><i class="fa fa-download  fa-3x" style="font-size:20px;" ></i></a></div></div></div>';
        }
        $("#imageList").append(html);
      });
     },
     error: function(XMLHttpRequest, textStatus, errorThrown) {
       console.log("some error");
     }
   });
  })

  var arr = [{ "id" : "1", "folderName" : "Home"}];

  function breadcrumbArray(folderId, folderName){
    var added=false;
    $.map(arr, function(elementOfArray, indexInArray) {
      if (elementOfArray.id == folderId) {
        added = true;
        arr.splice(indexInArray + 1, arr.length - (indexInArray + 1) );
        /*console.log(arr);*/
      }
    })
    if (!added) {
      arr.push({'id': folderId, 'folderName': folderName});
    }
    return arr;
  }






/*
Code by Sapna
*/
    //Login Section
    $('#login_form_submit').click(function(e) {
      var username = $.trim($('#login_form_username').val());
      var password = $.trim($('#login_form_password').val());
      if (username.length === 0 && password.length == 0) { 
        $('.error-msg.up-error').text("Please enter username and password.");
      }
      else if(username.length === 0){
        $('.error-msg.up-error').text("Please enter your username.");
      }
      else if(password.length === 0){
      $('.error-msg.up-error').text("Please enter your password.");
    }
    });

    //Navigation Part
    $(".nav .nav-item").on("click", function() {
      $(".nav .nav-item").removeClass("active");
      $(this).addClass("active");
    });
    if(window.location.pathname == '/login'){
      $("#navigation").css("display","none");
    }

    //Change Password
    $('#change_password_form').parsley();
    $('#change_password_form').on('submit', function(){
      var oldpassword = $("#change_password_form_oldpassword").val();
      var newpassword = $("#change_password_form_newpassword").val();
      var confirmpassword = $("#change_password_form_confirmpassword").val();
      if(oldpassword == newpassword){
        $(".error").html("Old password and new passsword should not be same.");
        return false;
      }
      if(newpassword!=confirmpassword){
        $(".error").html("New passsword and confirm new password should be same.");
        return false;
      }
          
          
    });

    //Edit user profile
    $('.success').hide();

    $('#validate_form').parsley();
    $('#validate_form').on('submit', function(event){

      event.preventDefault();

      var username = $("#user_form_username").val();
      var firstname = $("#user_form_firstname").val();
      var lastname = $("#user_form_lastname").val();
      var email = $("#user_form_email").val();
      var dataid = $("#user_form_username").data('id');
      var key = $("#user_form_username").data('key');
      var parentId = $("#user_form_username").data('parentid');
      if(dataid){
        var data = {id:dataid,username:username,firstname:firstname,lastname:lastname,email:email,parentId:parentId,key:key};
        var url = "/user/update";
      }

      $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(dataResult){

          var dataResult = JSON.parse(dataResult);
          if(dataResult["errormessage"]){
            $('.erroremail').append(dataResult["errormessage"]);
          }else{
            $('.success').show();
            $('.erroremail').hide();
          } 
        },
        error: function(){
          $('.error').append("Invalid Data.Please try again..!!");
          return false;
        }
      });
    });





    //Edit user profile - NEERAJ KUMAR GUPTA
    $('.success').hide();

    $('#editUser').parsley();
    $('#editUser').on('submit', function(event){

        event.preventDefault();

        var dataid = $("#edit_username").data('id');

        if(dataid){
            var url = "/user/editUser";
        }

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                if(dataResult["errormessage"]){
                    $('.erroremail').append(dataResult["errormessage"]);
                    // return false;
                }else{
                    $('.success').show();
                    $('.erroremail').hide();
                }
            },
            error: function(){
                // $('.error').text("This is a Error..!!");
                // console.log("Random text");

                $('.error').append("Invalid Data.Please try again..!!");
                return false;
            }
        });
    });







    //Collection add,update
    $(document).on("click", "#collection_form_save", function(e){
      e.preventDefault();
      var collectionId = $(this).data('id');
      var collectionName = $("#collection_form_collectionName").val(); 
      var collectionDesc = $("#collection_form_collectionDesc").val(); 
      if(collectionId){
        var data = "id="+ collectionId + "&collectionName=" + collectionName+ "&collectionDesc=" + collectionDesc;   
      }else{
        var data = "collectionName=" + collectionName+ "&collectionDesc=" + collectionDesc;         
      }
      var url = "/collection/add";
      $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(result){
        $('#messages').text('Collection Added/Updated successfully.').show(10).delay(3000).hide(400);
          
          document.getElementById("addcollection_form").reset();
          $('#addCollectionclose').click();
           var id = $("#global_id").val();
           var page = $("#global_page").val();
            $('#folderList').load("/collection/content?id="+id+"&page="+page);
        },
        error: function(){
          bootbox.alert('Collection not Added/Updated.');
        }
      });

    });

    $(document).on("click", ".updatebutton", function(){ 
      $("#modaltitle").html("Edit Collection");
      var collectionId = $(this).data('id');
      var name = $(this).data('name');
      var description = $(this).data('description');
      $('#collection_form_save').data('id', collectionId);
      $('.collectionName').val(name);
      $('.collectionDesc').val(description);
    });

    $(document).on("click", "#addcollection", function(){ 
      $("#modaltitle").html("Add Collection");
    });

    //Delete Collection
    $(document).on("click", ".deletebutton", function(){
        var deleteId = $(this).data('id');
        $('#confirmButton').data('id', deleteId);
    });

    $(document).on("click", "#confirmButton", function(){
        var deleteId = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/collection/delete?id=" + deleteId,
            data: {id:deleteId},
            success: function(result){
              $('#messages').text('Collection deleted successfully.').show(10).delay(3000).hide(400);
                $("#deleteModalclose").click();
                var id = $("#global_id").val();
                var page = $("#global_page").val();
                $('#folderList').load("/collection/content?id="+id+"&page="+page);
            },
            error: function(){
                bootbox.alert('Collection not deleted.');
            }
        });
    });

    //Assign Collection
    $(document).on("click", ".assignCollection", function(){
      var $el = $(this);
      var option = '';
      $.ajax({
        type: "GET",
        url: "/collection-list?query="+$el.val(),    
        success: function(item){
          var parsedData = jQuery.parseJSON(item);
          $.each(parsedData, function(i, item) {
            $("#collectionlist").append('<option value=' + item.value + '>' + item.label + '</option>');
          });
        }
      });
    });

    //Download Collection
    $(document).on("click", "#downloadCollection", function(){
        var $el = $(this);
        var folderId = $el.data('id');
  
        $.ajax({  
         type:"GET",
         url:"/create-zip?assetId="+folderId+"&type=collection",
         success:function(data){ 
          var parseData = jQuery.parseJSON(data);
          console.log(parseData);
          window.location = '/download-as-zip?jobId='+parseData.jobId+'&id='+folderId+'&type=collection';
      
          }
        });
  
    });

    //Add asset to Collection
    $("#add_to_collection_form_save").click(function(){
      
      var assetId = $(this).data('id');
      var collectionId = $("#collectionlist").val(); 
      
        var data = "collectionId="+ collectionId + "&assetId=" + assetId;   
     
      var url = "/asset/add-to-collection";
      $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(result){
          $('#messages').text('Asset added to collection successfully.').show(10).delay(3000).hide(400);
          
           document.getElementById("addtocollection_form").reset();
          $('#assignCollection .close').click();
           var id = $("#global_id").val();
           var page = $("#global_page").val();
           $('#folderList').load("/asset/content?id="+id+"&page="+page);
        },
        error: function(){
          bootbox.alert('Asset is not added to collection');
        }
      });

    });

    $(document).on('click', '.assignCollection', function(){
      var assetId = $(this).data('id');
          $('#add_to_collection_form_save').data('id', assetId);
    });
    

    //Share collection
    $("#collection_share_form_userList").hide();
    $(document).on('click', '#collection_share_form_save', function(e){
          e.preventDefault();
          var shareditemId = $(this).data('id');
          var sharedType = $(this).data('type');
          var sharedWith = $("#browsers").val();

          $.ajax({
            type: "POST",
            url: "/collection/share",
            data: "shareditemId=" + shareditemId+ "&sharedType=" + sharedType+ "&sharedWith=" + sharedWith,
            success: function(result){
             var result = JSON.parse(result);
              if(result.type=="Collection"){
                $('#messages').text('Collection shared successfully.').show(10).delay(3000).hide(400);
                
                document.getElementById("share_form").reset();
                $('#share_formclose').click();
                 var id = $("#global_id").val();
                 var page = $("#global_page").val();
                 $('#folderList').load("/collection/content?id="+id+"&page="+page);
              }else{
                $('#messages').text('Asset shared successfully.').show(10).delay(3000).hide(400);
                
                document.getElementById("share_form").reset();
                $('#collection_share_form_saveclose').click();
                 var id = $("#global_id").val();
                 var page = $("#global_page").val();
                 $('#folderList').load("/asset/content?id="+id+"&page="+page);
              }
             
            },
            error: function(){
              bootbox.alert('Data not shared.');
            }
          });
    
    });
    
    //Share Collection
    $(document).on('click', '.sharebutton', function(){
        var collectionId = $(this).data('id');
        var sharedType = $(this).data('type');
        $('#collection_share_form_save').data('id', collectionId);
        $('#collection_share_form_save').data('type', sharedType);
    });

    //Share Asset
    $(document).on('click', '.shareAssetButton', function(){
        var AssetId = $(this).data('id');
        var sharedType = $(this).data('type'); 
        $('#collection_share_form_save').data('id', AssetId);
        $('#collection_share_form_save').data('type', sharedType);
    });

   
    //Remove asset from collection
    $(document).on('click', '.removeAsset', function(){
      var assetId = $(this).data('id');
          $('#removeAssetConfirmButton').data('id', assetId);
    });
    
    $(document).on('click', '#removeAssetConfirmButton', function(){
        var idstring = $(this).data('id');
        var res = idstring.split(",");
        var assetId = res[0];
        var collectionId = res[1];
        $.ajax({
            type: "POST",
            url: "/collection/remove-asset",
            data: {assetId:assetId,collectionId:collectionId},
            success: function(result){
                $('#messages').text('Asset removed successfully.').show(10).delay(3000).hide(400);
                
                $("#removeAssetConfirmButtonclose").click();
                $('#folderList').load("/collection/detail?id="+collectionId);
            },
            error: function(){
                bootbox.alert('Asset is not removed.');
            }
        });
    });

    //Create Folder
    $('#create_folder_form_save').on('click',(function() {
        
        var name = $("#create_folder_form_name").val();
        var parentid = $("#parentid").val();
        $.ajax({
            type:'POST',
            url: "/asset/createfolder",
            data: {name:name,id:parentid},
            success:function(data){
                console.log(data);
                $('#messages').text('Folder created successfully.').show(10).delay(3000).hide(400);
                document.getElementById("add_asset_form").reset();
                    $('#createFolderModal .close').click();
                    var id = $("#global_id").val();
                    var page = $("#global_page").val();
                      $('#folderList').load("/asset/content?id="+id+"&page="+page);
              },
            error: function(data){
              if(data){
                bootbox.alert(data);
              }else{
                bootbox.alert('Folder not created');
              }
              
            }

      });
  }));

 $(document).on("keyup", "#tags", function(){
      var keyword=$(this).val();
      var id = $("#global_id").val();
      var page = $("#global_page").val();
      $('#folderList').load("/"+$(this).attr('data')+"/content?id="+id+"&page="+page+"&keyword="+keyword);

});

  // add asset
    $('#add_asset_form').parsley();
    $('#add_asset_form').on('submit',(function(e) {
          e.preventDefault();
          
          var formData = new FormData(this);
          $.ajax({
              type:'POST',
              url: '/asset/add',
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){
                  console.log(data);
                  
                  $('#messages').text('Asset added successfully.').show(10).delay(3000).hide(400);
                  document.getElementById("add_asset_form").reset();
                  $('#addAssetModal .close').click();
                   var id = $("#global_id").val();
                   var page = $("#global_page").val();
                   $('#folderList').load("/asset/content?id="+id+"&page="+page);
              },
              error: function(data){
                if(data){
                  bootbox.alert(data);
                }else{
                  bootbox.alert('Asset not added.');
                }
                
              }
          });
    }));

    // delete asset
    $(document).on("click", "a.deleteAsset", function(){
              var $el = $(this);
              
              var assetId = $el.data('id');
              var assetType = $el.data('type');
              var assetName = $el.data('name');
              // Confirm box
              bootbox.confirm("Do you really want to delete <b>"+assetName+" "+assetType+"</b> ?", function(result) {  
                if(result){  
                  $.ajax({  
                   type:"GET",
                   url:"/delete?assetId="+assetId,
                   success:function(data){ 
                    var parseData = jQuery.parseJSON(data);

                    if(parseData.success == true){
                      $('#messages').text('Asset deleted successfully.').show(10).delay(3000).hide(400);
                       var id = $("#global_id").val();
                       var page = $("#global_page").val();
                       $('#folderList').load("/asset/content?id="+id+"&page="+page);
            
                     }else{
                      bootbox.alert('Asset not deleted.');
                    }
                  }
                });
                }
         });

    });

   //Asset sort by filter
   
    $("#assetFilter").change(function() {
        var $el = $(this);
        var sortValue = $el.find(":selected").val();
        var id = $("#global_id").val();
        var page = $("#global_page").val();
        var dataString = 'sortby='+ sortValue+'&id='+id+'&page='+page;
        console.log(sortValue);
        $('#folderList').load("/asset/content?"+dataString);
    });   

    //Collection sort by filter
    $(document).on("click", "#collectionFilter", function(){ 
        var $el = $(this);
        var sortValue = $el.find(":selected").val();
        
        var id = $("#global_id").val();
        var page = $("#global_page").val();
        var dataString = 'sortby='+ sortValue+'&id='+id+'&page='+page;
        console.log(sortValue);
        $('#folderList').load("/collection/content?"+dataString);
    });     


    //  view collection
    
    $(document).on("click", ".viewcollection", function(){ 
          var id = $(this).attr("id");
          $('#folderList').load("/collection/detail?id="+id);
    });

    // pagination

    $(document).on("click",".page-link",function(){
          var id = $(this).attr("data-id");
          $('#folderList').load(id);
    });

  });



