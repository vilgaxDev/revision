<script src="<?php echo base_url();?>public/new/plugins/jquery/jquery.min.js"></script>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                  <h2>File Browser</h2>
            </div>
            <ol class="breadcrumb">
              <?php
              switch ($mode) {
                case 'default':
                  ?><li><a href="<?php echo site_url('dashboard'); ?>"><i class="material-icons">home</i> Home</a></li><?php
                  break;
                case 'marked':
                ?><li><a href="<?php echo site_url('marked'); ?>"><i class="material-icons">star</i> Marked Files</a></li><?php
                  break;
                case 'trashcan':
                ?><li><a href="<?php echo site_url('trashcan'); ?>"><i class="material-icons">delete</i> Trashcan</a></li><?php
                  break;
              }
              foreach($full_path as $path){
                ?>
                <li><a href="<?php echo site_url('folders/'.$path['public_key']); ?>"><?php echo $path['folder_name']; ?></a></li>
                <?php
              }
               ?>
            </ol>
            <div class="folders">

              <?php foreach($folder_content['folders'] as $folder ){
                ?>
                  <a id="folder-<?php echo $folder['public_key']; ?>" class="btn btn-lg btn-default waves-effect inline folder">
                      <i class="material-icons">folder</i>
                    <?php echo $folder['folder_name']; ?></a>
                    <script>

                            $('document').ready(function(){

                              $.contextMenu({
                                  selector: '#folder-<?php echo $folder['public_key']; ?>',
                                  items: {
                                      "restore": {name: "Restore Folder", icon: "edit", callback: restoreFolder},
                                      "delete": {name: "Delete Folder", icon: "delete", callback: delelteFolder}
                                  }
                              });
                              function restoreFolder(){
                                window.location.replace('<?php echo site_url('file/restoreFolder/'.$folder['public_key']); ?>');
                              };
                              function delelteFolder(){
                                window.location.replace('<?php echo site_url('file/deleteFolderPerm/'.$folder['public_key']); ?>');
                              };

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
                <a id="file-<?php echo $file['storage_name']; ?>" title="<?php echo $file['real_name']; ?>" class="file card">
                  <?php
                  if(!$file['thumbnail'] == false){
                    ?><img class="file-thumbnail" src="data:<?php echo 'image/png;base64,'.$file['thumbnail_source']; ?>"/><?php
                  }elseif(isset($file['thumbnail_default'])){
                    ?><img class="file-thumbnail-default" src="<?php echo $file['thumbnail_source']; ?>"/><?php
                  }else{
                    ?><img class="file-thumbnail" src="<?php echo base_url(); ?>public/new/images/thumbnail.jpg"/><?php
                  }
                   ?>
                  <p class="file-name"><?php echo mb_strimwidth($file['real_name'], 0, 20, "..."); ?></p>
                </a>
                <script>

                        $('document').ready(function(){

                          $.contextMenu({
                              selector: '#file-<?php echo $file['storage_name']; ?>',
                              items: {
                                  "restore": {name: "Restore File", icon: "fa-window-restore", callback: restoreFile},
                                  "edit": {name: "Delete File", icon: "delete", callback: deleteFile}
                              }
                          });
                          function deleteFile(){
                            window.location.replace('<?php echo site_url('file/deleteFilePerm/'.$file['storage_name']); ?>');
                          }
                          function restoreFile(){
                            window.location.replace('<?php echo site_url('file/restoreFile/'.$file['storage_name']); ?>');
                          }

                        });

                </script>
                <?php
              }
               ?>
            </div>
        </div>
    </section>


<?php
//We dont want these models if we're in the trashcan
if($mode !== 'trashcan'){
  ?>

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
<?php
} ?>
