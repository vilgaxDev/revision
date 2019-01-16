<script src="<?php echo base_url();?>public/new/plugins/jquery/jquery.min.js"></script>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                  <h2>File Browser</h2>
            </div>
            <?php
            if($permission == 1){
              ?>
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
              <?php
            }
             ?>
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
                <li><a id="path-<?php echo $path['public_key']; ?>" href="<?php echo site_url('sharedFolder/'.$path['public_key']); ?>"><?php echo $path['folder_name']; ?></a></li>
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

            <div class="folders">

              <?php foreach($folder_content['folders'] as $folder ){
                ?>
                  <a id="folder-<?php echo $folder['public_key']; ?>" href="<?php echo site_url('sharedFolder/'.$folder['public_key']); ?>" class="btn btn-lg btn-default waves-effect inline folder">
                      <i class="material-icons">folder</i>
                    <?php echo $folder['folder_name']; ?></a>
                    <script>
                    <?php
                    if($permission == 1){
                      ?>
                      $('document').ready(function(){
                        $.contextMenu({
                            selector: '#folder-<?php echo $folder['public_key']; ?>',
                            items: {
                                "rename": {name: "Rename Folder", icon: "edit", callback: renameFolder},
                                "edit": {name: "Delete Folder", icon: "delete", callback: deleteFolder}
                            }
                        });
                      });
                      <?php
                    }
                     ?>
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
                    </script>
                <?php
              }
              ?>
            </div>
            <div class="files" style="margin-top: 20px;">
              <?php
              foreach($folder_content['files'] as $file){
                ?>
                <a id="file-<?php echo $file['storage_name']; ?>" title="<?php echo $file['real_name']; ?>" href="<?php echo site_url('download/start/'.$file['storage_name']); ?>" class="file card">
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
                  }else{
                    ?><img class="file-thumbnail" src="http://xpenology.org/wp-content/themes/qaengine/img/default-thumbnail.jpg"/><?php
                  }
                   ?>
                  <p class="file-name"><?php echo mb_strimwidth($file['real_name'], 0, 25, "..."); ?></p>
                </a>
                <script>

                        $('document').ready(function(){
                          $('#file-<?php echo $file['storage_name']; ?>').click(function() {
                              return false;
                          }).dblclick(function() {
                              window.location = this.href;
                              return false;
                          });

                          $.contextMenu({
                              selector: '#file-<?php echo $file['storage_name']; ?>',
                              items: {
                                  "download": {name: "Download", icon: "fa-download", callback: download}
                                  <?php if($permission == 1){
                                    ?>
                                    ,"rename": {name: "Rename File", icon: "edit", callback: renameFile},
                                    "delete": {name: "Delete File", icon: "delete", callback: deleteFile}
                                    <?php
                                  }
                                  ?>
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

                        });

                </script>
                <?php
              }
               ?>
            </div>
        </div>
    </section>


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
            <input type="text" hidden="hidden" value="<?php echo $parent_public_key; ?>" name="parent_public_key" />
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
          <?php echo form_open_multipart('upload'); ?>
            <input type="text" hidden="hidden" value="<?php echo $parent_public_key; ?>" name="parent_public_key" />
            <label>Choose your file</label>
            <input type="file" class="form-control" placeholder="Folder Name" name="files" />
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
