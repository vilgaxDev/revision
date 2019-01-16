<script src="<?php echo base_url();?>public/new/plugins/jquery/jquery.min.js"></script>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                  <h2>File Browser</h2>
            </div>
            <div class="action_bar pull-right">
              <a href="#" class="btn btn-default inline" data-toggle="modal" data-target="#createFolder">
                <i class="material-icons">create_new_folder</i>
                Create Folder
              </a>
              <a href="#" class="btn btn-default inline" data-toggle="modal" data-target="#uploadFile">
                <i class="material-icons">file_upload</i>
                Upload
              </a>
            </div>
            <ol class="breadcrumb folder-menu">
              <?php
              switch ($mode) {
                case 'default':
                  ?><li><a id="path-0home" href="<?php echo site_url('dashboard'); ?>"><i class="material-icons">home</i> Home</a></li><?php
                  break;
                case 'marked':
                ?><li><a href="<?php echo site_url('marked'); ?>"><i class="material-icons">star</i> Marked Files</a></li><?php
                  break;
                case 'trashcan':
                ?><li><a href="<?php echo site_url('trashcan'); ?>"><i class="material-icons">delete</i> Trashcan</a></li><?php
                  break;
                case 'shared':
                ?><li><a href="<?php echo site_url('shared'); ?>"><i class="material-icons">person</i> Shared Files</a></li><?php
                  break;
              }
              foreach($full_path as $path){
                ?>
                <li><a id="path-<?php echo $path['public_key']; ?>" href="<?php echo site_url('folders/'.$path['public_key']); ?>"><?php echo $path['folder_name']; ?></a></li>
                <?php
              }
?></ol><?php
              foreach($full_path as $path){
                ?>
                <script>

                        $('document').ready(function(){
                          $("#path-<?php echo $path['public_key']; ?>").droppable({
                            tolerance: "touch",
                            hoverClass: 'hover-path-drop',
                            activeClass: 'active-path-drop',
                            over: function(event, ui){
                              $('.folder-menu').find('a[id*=path-]').droppable( "option", "disabled", true );
                              $(this).droppable( "option", "disabled", false );
                            },
                            out: function(event,ui){
                              $('.folder-menu').find('a[id*=path-]').droppable( "option", "disabled", false );
                            },
                            drop: function(event, ui){
                              if(ui.draggable.hasClass("folder")){
                                window.location.replace('<?php echo site_url('file/moveFolder/'); ?>' + ui.draggable.attr('id') + '/' + $(this).attr('id'));
                              }else{
                                window.location.replace('<?php echo site_url('file/moveFile/'); ?>' + ui.draggable.attr('id') + '/' + $(this).attr('id'))
                              }
                            }

                          });
                        })
                        </script>
                <?php
              }
               ?>
               <script>

                       $('document').ready(function(){
                         $("#path-0home").droppable({
                           tolerance: "touch",
                           hoverClass: 'hover-path-drop',
                           activeClass: 'active-path-drop',
                           over: function(event, ui){
                             $('.folder-menu').find('a[id*=path-]').droppable( "option", "disabled", true );
                             $(this).droppable( "option", "disabled", false );
                           },
                           out: function(event,ui){
                             $('.folder-menu').find('a[id*=path-]').droppable( "option", "disabled", false );
                           },
                           drop: function(event, ui){
                             if(ui.draggable.hasClass("folder")){
                               window.location.replace('<?php echo site_url('file/moveFolder/'); ?>' + ui.draggable.attr('id') + '/' + $(this).attr('id'));
                             }else{
                               window.location.replace('<?php echo site_url('file/moveFile/'); ?>' + ui.draggable.attr('id') + '/' + $(this).attr('id'))
                             }
                           }

                         });
                       })
                       </script>

            <div class="folders">

              <?php foreach($folder_content['folders'] as $folder ){
                ?>
                  <a <?php if($folder['marked']){ echo "style='color: #ff9600;'";} ?> id="folder-<?php echo $folder['public_key']; ?>" href="<?php echo site_url('folders/'.$folder['public_key']); ?>" class="btn btn-lg btn-default waves-effect inline folder">
                      <i class="material-icons">folder</i>
                    <?php echo $folder['folder_name']; ?></a>
                    <script>
                            $('document').ready(function(){
                              $("#folder-<?php echo $folder['public_key']; ?>").droppable({
                                tolerance: "touch",
                                hoverClass: 'active-folder-drop',
                                over: function(event, ui){
                                  $('.folders').find('a[id*=folder-]').droppable( "option", "disabled", true );
                                  $(this).droppable( "option", "disabled", false );
                                },
                                out: function(event,ui){
                                  $('.folders').find('a[id*=folder-]').droppable( "option", "disabled", false );
                                },
                                drop: function(event, ui){
                                  if(ui.draggable.hasClass("folder")){
                                    window.location.replace('<?php echo site_url('file/moveFolder/'); ?>' + ui.draggable.attr('id') + '/' + $(this).attr('id'));
                                  }else{
                                    window.location.replace('<?php echo site_url('file/moveFile/'); ?>' + ui.draggable.attr('id') + '/' + $(this).attr('id'))
                                  }
                                }

                              });
                              $("#folder-<?php echo $folder['public_key']; ?>").draggable({
                                revert: true,
                                helper: 'clone',
                                cursorAt: { left: 20, top: 5 },
                                start: function( event, ui ) {
                                  $(ui.helper).addClass('active-file-drop');
                                  $("#folder-<?php echo $folder['public_key']; ?>").click(function(e) {
                                    e.preventDefault();
                                  });
                                },
                                stop: function(event, ui){
                                  $("#folder-<?php echo $folder['public_key']; ?>").unbind('click');
                                }

                              });
                              $.contextMenu({
                                  selector: '#folder-<?php echo $folder['public_key']; ?>',
                                  items: {
                                      "rename": {name: "Rename Folder", icon: "edit", callback: renameFolder},
                                      "star": {name: "<?php if($folder['marked']){echo "Unmark";}else{echo "Mark";} ?>", icon: "fa-star", callback: markFolder},
                                      "share": {name: "Share Folder", icon: "fa-users", callback: shareFolder},
                                      "edit": {name: "Delete Folder", icon: "delete", callback: deleteFolder}
                                  }
                              });
                              function shareFolder(){
                                var folder_id = $(this).attr('id');
                                shareModel(folder_id);
                              }
                              function deleteFolder(){
                                window.location.replace('<?php echo site_url('file/deleteFolder/'.$folder['public_key']); ?>');
                              };
                              function renameFolder(){
                               var public_key = "<?php echo $folder['public_key']; ?>";
                               var folder_name = "<?php echo $folder['folder_name']; ?>";

                               $('#rename_NewName').val(folder_name);
                               $('#rename_Identifier').val(public_key);
                               $('#renameForm').attr('action', "<?php echo site_url('file/renameFolder/'.$folder['public_key']) ?>");
                               $('#rename').modal('show');
                              }

                              function markFolder(){
                                window.location.replace("<?php echo site_url('file/markFolder/'.$folder['public_key']); ?>");
                              }

                            });

                    </script>
                <?php
              }
              ?>
            </div>

            <div class="files" style="margin-top: 20px;">
              <?php
              foreach($folder_content['files'] as $file){
                ?>
                <a id="file-<?php echo $file['storage_name']; ?>" data-storage="<?php echo $file['storage_name']; ?>" data-mime="<?php echo $file['mime']; ?>" title="<?php echo $file['real_name']; ?>" href="<?php echo site_url('download/start/'.$file['storage_name']); ?>" class="file card">
                  <?php
                  if($file['marked']){
                    ?>
                    <span class="label label-warning" style="position: absolute; margin-top: 8px; margin-left: 5px; padding-top: 5px;"><i class="fa fa-star" aria-hidden="true"></i></span>
                    <?php
                  }
                   ?>
                  <?php
                  if(!$file['thumbnail'] == false){
                    ?><img class="file-thumbnail" src="data:<?php echo 'image/png;base64,'.$file['thumbnail_source']; ?>"/><?php
                  }elseif(isset($file['thumbnail_default'])){
                    ?><img class="file-thumbnail-default" src="<?php echo $file['thumbnail_source']; ?>"/><?php
                  }else{
                    ?><img class="file-thumbnail" src="<?php echo base_url(); ?>public/new/images/thumbnail.jpg"/><?php
                  }
                   ?>
                  <p class="file-name"><?php echo mb_strimwidth($file['real_name'], 0, 25, "..."); ?></p>
                </a>
                <script>
                  $('document').ready(function(){
                    $.contextMenu({
                        selector: '#file-<?php echo $file['storage_name']; ?>',
                        items: {
                            "download": {name: "Download", icon: "fa-download", callback: download},
                            "rename": {name: "Rename File", icon: "edit", callback: renameFile},
                            "star": {name: "Mark", icon: "fa-star", callback: markFile},
                            "share": {name: "Share File", icon: "fa-users", callback: shareFile},
                            "edit": {name: "Delete File", icon: "delete", callback: deleteFile}
                        }
                    });
                    function download(){
                      window.location.replace('<?php echo site_url('download/start/'.$file['storage_name']); ?>');
                    }
                    function deleteFile(){
                      window.location.replace('<?php echo site_url('file/deleteFile/'.$file['storage_name']); ?>');
                    }
                    function renameFile(){
                     var storage_key = "<?php echo $file['storage_name']; ?>";
                     var file_name = "<?php echo $file['real_name']; ?>";

                     $('#rename_NewName').val(file_name);
                     $('#rename_Identifier').val(storage_key);
                     $('#renameForm').attr('action', "<?php echo site_url('file/renameFile/'.$file['storage_name']) ?>");
                     $('#rename').modal('show');
                    }

                    function markFile(){
                      window.location.replace("<?php echo site_url('file/markFile/'.$file['storage_name']); ?>");
                    }

                    //File sharing preview ...
                    function shareFile(){
                      shareModel("<?php echo $file['storage_name']; ?>");
                    }
                        });

                </script>
                <?php
              }
               ?>
            </div>
        </div>
    </section>


  <script>
  function shareModel(share_id){
    $('#shareModel').modal('show');
    $('#share-id').val(share_id);
    $('.share_link span').html(share_id);
    refreshShareUsers(share_id)
  }

  function refreshShareUsers(share_id){
    //Check if the object is already shared with some one
    $.get("<?php echo site_url('file/getShares/'); ?>" + share_id, function(result){
      $('#shareUsers').html('');

      var data = $.parseJSON(result);
      if("shares" in data){
        for(var i = 0; i < data['shares'].length; i++){
          $('#shareUsers').append("<tr> \
          <td>"+ data['shares'][i]['email'] +" <i>("+data['shares'][i]['firstname'] +" "+data['shares'][i]['lastname']+")</i></td> \
          <td><a data-id='"+$('#share-id').val()+"' data-share='"+data['shares'][i]['share_id']+"' class='btn btn-danger btn-xs pull-right delete-share'>Remove</a></td> \
          </tr>");
        }
      }
    });
  }

  $('document').ready(function(){
    $('#shareUsers').on('click', '.delete-share', function(){
      var share_id = $(this).attr('data-share');
      var obj_id = $(this).attr('data-id');
      //Try to delete the share
      $.get("<?php echo site_url('file/deleteShare/'); ?>" + obj_id + "/" + share_id, function(result){
        var data = $.parseJSON(result);

        if('error' in data){
          $('#shareError').show();
          $('#shareError').html(data.error);
          setTimeout(function(){
            $('#shareError').fadeOut();
          }, 4000);
        }else if ('success' in data) {
          $('#shareSuccess').show();
          $('#shareSuccess').html(data.success);
          refreshShareUsers($('#share-id').val());
          setTimeout(function(){
            $('#shareSuccess').fadeOut();
          }, 4000);
        }

      });
    });
  })

  $('document').ready(function(){
    $('#preview_share').click(function(e){
      shareModel('file-'+$('#preview_share').attr('data-file'));
    });

    $('#shareForm').submit(function(e){
      // process the form
        $.ajax({
            type        : 'POST',
            url         : '<?php echo site_url('file/share'); ?>',
            data        : $('#shareForm').serialize(),
            dataType    : 'json',
                        encode          : true
        })
      // using the done promise callback
      .done(function(data) {

          // log data to the console so we can see
          if('error' in data){
            $('#shareError').show();
            $('#shareError').html(data.error);
            setTimeout(function(){
              $('#shareError').fadeOut();
            }, 4000);
          }else if ('success' in data) {
            $('#shareSuccess').show();
            $('#shareSuccess').html(data.success);
            refreshShareUsers($('#share-id').val());
            setTimeout(function(){
              $('#shareSuccess').fadeOut();
            }, 4000);
          }

          // here we will handle errors and validation messages
      });

      // stop the form from submitting the normal way and refreshing the page
    e.preventDefault();
    })
  })

  </script>
  <div class="modal fade" id="shareModel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Sharing settings</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <p>
                <strong>Direct Link</strong>
              </p>
              <p class="share_link">
                <?php echo site_url('file/directDownload/'); ?><span></span>
              </p>
            </div>
            <div class="col-md-12">
              <p class="alert alert-success" id="shareSuccess" style="display: none;">

              </p>
            </div>
            <div class="col-md-12">
              <p class="alert alert-danger" id="shareError" style="display: none;">

              </p>
            </div>
            <div class="col-md-12">
              <table class="table">
                <thead class="row">
                  <th class="col-md-8">
                    User
                  </th>
                  <th class="col-md-4">

                  </th>
                </thead>
                <tbody id="shareUsers">

                </tbody>
              </table>
            </div>
          </div>
          <?php echo form_open('file/share/', array('id' => 'shareForm')); ?>
        <div class="row" style="padding-right: 20px; padding-left: 10px;">
          <div class="col-md-8">
            <input type="email" name="user_email" class="form-control" placeholder="Email address of user"/>
          </div>
          <div class="col-md-2">
            <select class="form-control" name="permission">
              <option value="view">
                View</option>
              <option value="edit">
                Edit</option>
            </select>
          </div>
          <div class="col-md-2">
            <input type="submit" class="btn btn-success" value="Add User" />
          </div>
          <input type="text" id="share-id" name="share-id" style="display: none;" hidden />
        </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


    <!-- Modal -->
    <div class="modal fade" id="createFolder" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Create a New Folder</h4>
          </div>
          <div class="modal-body">
          <?php echo form_open('file/createFolder'); ?>
            <input type="text" hidden="hidden" value="<?php echo $parent_public_key; ?>" name="parent_public_key" id="parent_public_key" />
            <label>Folder Name</label>
            <input type="text" class="form-control" placeholder="Folder Name" name="folder_name" />
            <br/>
            <input type="submit" class="btn btn-primary" value="Create Folder" />
          </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="uploadFile" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Upload a File</h4>
          </div>
          <div class="modal-body">
          <form id="fileUploadForm">
            <input type="text" hidden="hidden" value="<?php echo $parent_public_key; ?>" name="parent_public_key" />
            <label>Choose your file</label>
            <input id="fileUpload" type="file" class="form-control" placeholder="Folder Name" name="files" />
            <br/>
            <input type="submit" class="btn btn-primary" value="Upload File" />
          </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="rename" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Rename</h4>
          </div>
          <div class="modal-body">
            <form method="post" id="renameForm">
              <div>
                <label>New Name</label>
                <input type="text" class="form-control" name="newName" id="rename_NewName"  />
              </div>
                <input type="text" hidden="hidden" style="display: none; visibility: hidden;" id="rename_Identifier" name="rename_Identifier" />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
          </form>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<div class="preview_overlay">
  <div class="preview_menu" id="preview_menu">
    <div class="left">
      <img src="" class="file_type_icon" />
      <p class="file_title"></p>
    </div>
    <div class="right">
      <a href="#" data-file="" class="preview_menu_option" id="preview_share"><i class="fa fa-share-alt" aria-hidden="true"></i> Share</a>
      <a href="#" class="preview_menu_option" id="preview_download"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
    </div>
  </div>
  <p class="no_preview" style="
      color: #fff;
      font-size: 20px;
      text-align: center;
      margin-top: 100px;
  ">There is no preview available for the selected file</p>
  <div class="preview_area">
  </div>
</div>


<div class="uploadProgress_overlay">
  <i id="closeUploadProgress" class="fa fa-times" aria-hidden="true" style="float:right; cursor: pointer;"></i>
  <div class="uploadFile">
    <p class="uploadName" id="uploadFileName"></p>
    <p class="uploadProgress"><span id="uploadProgressPercent"></span>% (<span id="uploadBytesDone"></span>Mb/<span id="uploadBytesTotal"></span>Mb)</p>
    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
        <span id="uploadProgressPercent"></span>
      </div>
    </div>
  </div>
</div>




<script>
$('document').ready(function(){

$('#closeUploadProgress').click(function(){
  $('.uploadProgress_overlay').hide();
})

var ajax = new XMLHttpRequest();
$('#fileUploadForm').submit(function(e){
  e.preventDefault();
  uploadFile();
  $('#uploadFile').modal('hide');
})

function uploadFile() {
  var file = document.getElementById('fileUpload').files[0];
  $('#uploadFileName').html(file.name.substr(0, 20) + "...");
  $('.uploadProgress_overlay').removeClass("done");
  $('.uploadProgress_overlay').removeClass("error");
  $('.uploadProgress_overlay p:last-child').remove();
  $('.uploadProgress_overlay').show();
  var formdata = new FormData(document.getElementById('fileUploadForm'));
  ajax.upload.addEventListener("progress", progressHandler, false);
  ajax.addEventListener("load", completeHandler, false);
  ajax.addEventListener("error", errorHandler, false);
  ajax.addEventListener("abort", abortHandler, false);
  ajax.open("POST", "<?php echo site_url('upload/'); ?>");
  ajax.send(formdata);
}

function progressHandler(event) {
  var percent = (event.loaded / event.total) * 100;
  $('#uploadBytesDone').html(Math.ceil(event.loaded/1024/1024));
  $('#uploadBytesTotal').html(Math.ceil(event.total/1024/1024));
  $('#uploadProgressPercent').html(Math.ceil(percent));
  $('.uploadFile .progress-bar').attr("aria-valuenow", Math.ceil(percent));
  $('.uploadFile .progress-bar').css("width", Math.ceil(percent) + "%");
  $('.uploadProgressPercent').html(Math.ceil(percent));
}

function completeHandler(event) {
  var json = JSON.parse(ajax.responseText);

  if(json['success'] !== undefined){
    $('.uploadProgress_overlay').addClass("done");
    var image_source = "";
    if(json['thumbnail'] == true){
      image_source = "data:image/png;base64,"+json['thumbnail_data'];
    }else{
      image_source = "<?php echo base_url(); ?>public/new/images/thumbnail.jpg";
    }
    $('.files').append('\
    <a id="file-'+json['storage']+'" data-storage="'+json['storage']+'" data-mime="'+json['mime']+'" title="'+json['name']+'" href="<?php echo site_url('download/start/'); ?>'+json['storage']+'" class="file card">\
    <img class="file-thumbnail" src="'+image_source+'"/>\
    <p class="file-name">'+jQuery.trim(json['name']).substring(0, 22)+'</p>\
    </a>\
    ');
    $(".file").draggable({
      revert: true,
      helper: 'clone',
      cursorAt: { left: 20, top: 5 },
      start: function( event, ui ) {
        $(ui.helper).addClass('active-file-drop');
        $(".file").click(function(e) {
          e.preventDefault();
        });
      },
      stop: function(event, ui){
        $(".file").unbind('click');
      }

    });
    $.contextMenu({
        selector: '#file-'+json['storage'],
        items: {
            "download": {name: "Download", icon: "fa-download", callback: download},
            "rename": {name: "Rename File", icon: "edit", callback: renameFile},
            "star": {name: "Mark", icon: "fa-star", callback: markFile},
            "share": {name: "Share File", icon: "fa-users", callback: shareFile},
            "edit": {name: "Delete File", icon: "delete", callback: deleteFile}
        }
    });
    function download(){
      window.location.replace('<?php echo site_url('download/start/'); ?>'+json['storage']);
    }
    function deleteFile(){
      window.location.replace('<?php echo site_url('file/deleteFile/'); ?>'+json['storage']);
    }
    function renameFile(){
     var storage_key = json['storage'];
     var file_name = json['name'];

     $('#rename_NewName').val(file_name);
     $('#rename_Identifier').val(storage_key);
     $('#renameForm').attr('action', "<?php echo site_url('file/renameFile/') ?>"+json['storage']);
     $('#rename').modal('show');
    }

    function markFile(){
      window.location.replace("<?php echo site_url('file/markFile/'); ?>"+json['storage']);
    }

    //File sharing preview ...
    function shareFile(){
      shareModel(json['storage']);
    }

  }else{
    //File not uploaded
    $('.uploadProgress_overlay').addClass("error");
    //Get the error message
    $('.uploadProgress_overlay').append("<p>"+json['error']+"</p>")
  }
}

function errorHandler(event) {
  $('.uploadProgress_overlay').addClass("error");
}
function abortHandler(event) {
  $('.uploadProgress_overlay').addClass("error");
}


$(".file").draggable({
  revert: true,
  helper: 'clone',
  cursorAt: { left: 20, top: 5 },
  start: function( event, ui ) {
    $(ui.helper).addClass('active-file-drop');
    $(".file").click(function(e) {
      e.preventDefault();
    });
  },
  stop: function(event, ui){
    $(".file").unbind('click');
  }

});

  $('.preview_overlay').click(function(e){
    if(e.target.id !== "preview_menu" && e.target.id !== "preview_download" && e.target.id !== "preview_share"){
      $('.preview_overlay').hide();
    }
  });

  function previewPDF(name){
    $('.preview_area').html('<embed src="<?php echo site_url('directPreview'); ?>/'+name+'" width="800px" height="100%" />');
  }

  function previewSound(name){
      $('.preview_area').html('<audio controls>\
        <source src="<?php echo site_url('directPreview'); ?>/'+name+'" type="audio/mpeg">\
      Your browser does not support the audio element.\
      </audio>');
  }

  function previewVideo(name){
    $('.preview_area').html('<video controls>\
      <source src="<?php echo site_url('directPreview'); ?>/'+name+'" type="video/mp4">\
    Your browser does not support the audio element.\
    </video>');
  }

  function previewImage(name){
    $('.preview_area').html('<img style="display: block; margin:0 auto; max-height: 100%; width: auto; max-width: 100%;" src="<?php echo site_url('directPreview'); ?>/'+name+'"/>');
  }

  $(document).on('click', '.file', function(){
    //Preview the file if possible
    $('.preview_overlay').toggle();
    $('.preview_area').html('');
    $('.no_preview').show();
    name = $(this).attr('data-storage');
    $('.preview_menu .file_title').html($('#file-'+name).attr('title'));
    $('#preview_download').attr('href', '<?php echo site_url('download/start/'.$file['storage_name']); ?>')
    $('#preview_share').attr('data-file', '<?php echo $file['storage_name']; ?>');
    switch($('#file-'+name).attr('data-mime')){
      case "application/pdf":{
        $('.no_preview').hide();
        //We can display pdf documents directly
        previewPDF(name);
        //File type icon available for pdf
        $('.file_type_icon').attr('src', "<?php echo base_url().'/public/file_previews/pdf.png'; ?>");
        $('.file_type_icon').show();
        break;
      }
      case "audio/mp3": {
        $('.no_preview').hide();
        //We can display pdf documents directly
        previewSound(name);
        //File type icon available for pdf
        $('.file_type_icon').attr('src', "<?php echo base_url().'/public/file_previews/mp3.png'; ?>");
        $('.file_type_icon').show();
        break;
      }
      case "image/png": {
        $('.no_preview').hide();
        //We can display pdf documents directly
        previewImage(name);
        //File type icon available for pdf
        $('.file_type_icon').attr('src', "<?php echo base_url().'/public/file_previews/png.png'; ?>");
        $('.file_type_icon').show();
        break;
      }
      case "image/jpeg": {
        $('.no_preview').hide();
        //We can display pdf documents directly
        previewImage(name);
        //File type icon available for pdf
        $('.file_type_icon').attr('src', "<?php echo base_url().'/public/file_previews/jpg.png'; ?>");
        $('.file_type_icon').show();
        break;
      }
      case "video/mp4": {
        $('.no_preview').hide();
        //We can display pdf documents directly
        previewVideo(name);
        //File type icon available for pdf
        $('.file_type_icon').attr('src', "<?php echo base_url().'/public/file_previews/mp4.png'; ?>");
        $('.file_type_icon').show();
        break;
      }
      default: {
        $('.file_type_icon').hide();
      }
    }

    return false;
  }).dblclick(function() {

  });
});
</script>
