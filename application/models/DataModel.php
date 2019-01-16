<?php defined('BASEPATH') OR exit('No direct script access allowed');

class DataModel extends CI_Model{
  public $sharedPermission = 0;
  public function __construct(){
    parent::__construct();
  }

  public function validateFileType($type){

    $this->db->where('name', 'whitelist_enabled');
    $this->db->where('content', 1);
    $whitelist_enabled = $this->db->count_all_results('setting_items');

    if($whitelist_enabled == 1){
      $this->db->where('name', 'contentTypeFilter_whitelist');
      $this->db->where('content', $type);
      if($this->db->count_all_results('setting_items') == 1){
        return true;
      }
      return false;
    }

    $this->db->where('name', 'blacklist_enabled');
    $this->db->where('content', 1);
    $blacklist_enabled = $this->db->count_all_results('setting_items');

    if($blacklist_enabled == 1){
      $this->db->where('name', 'contentTypeFilter_blacklist');
      $this->db->where('content', $type);
      if($this->db->count_all_results('setting_items') == 1){
        return false;
      }
      return true;
    }

    return true;
  }

  public function generateStorageName(){
    $string = $this->generateRandomString(25);
    //Check if the name is unique
    $this->db->where('storage_name', $string);
    if($this->db->count_all_results('filestorage') == 0){
      return $string;
    }else{
      $this->generateStorageName();
    }
  }

  public function storageEngine($id=null){
    if($id == NULL){
      $this->db->where('active', 1);
    }else{
      $this->db->where('id', $id);
    }
    return $this->db->get('storage_types')->row_array();
  }

  public function allStorageEngines(){
    return $this->db->get('storage_types')->result_array();
  }

  //Save the data of an uploaded file to the database
  public function save($file, $thumbnail, $user_id, $parent){
    $insert_data = array(
      'storage_name' => $file['storage_name'],
      'real_name' => $file['name'],
      'storage_engine' => $file['storage_engine'],
      'created' => time(),
      'uploader_ip' => $this->input->ip_address(),
      'mime' => $file['type'],
      'filesize' => $file['size'],
      'user_id' => $user_id,
      'parent' => $parent,
      'thumbnail' => $thumbnail
    );


    //Insert the data into the database
    $this->db->insert('filestorage', $insert_data);
    return true;
  }

  public function fileExists($data){
    if(is_array($data)){
      $this->db->where($data);
    }else{
      $this->db->where('storage_name', $data);
    }
    if($this->db->count_all_results('filestorage') == 1){
      return true;
    }
    return false;
  }

  public function fileInformation($filename){
    $this->db->where('storage_name', $filename);
    $this->db->select('id, user_id, storage_engine, real_name, storage_name, created, mime, filesize, DATE_FORMAT(FROM_UNIXTIME(filestorage.created), "%e %b %Y %h:%m%p") AS uploaded_date, thumbnail, parent, marked, trash');
    return $this->db->get('filestorage')->row_array();
  }



  public function getFiles($offset, $limit, $order_by = NULL, $search_term){
    //Order by
    switch ($order_by) {
      case 'udnf':
        $this->db->order_by('filestorage.created', 'DESC');
        break;
      case 'udof':
        $this->db->order_by('filestorage.created', 'ASC');
        break;
      case 'fssf':
        $this->db->order_by('filestorage.filesize', 'ASC');
        break;
      case 'fsbf':
        $this->db->order_by('filestorage.filesize', 'DESC');
        break;

      default:
        $this->db->order_by('filestorage.created', 'DESC');
        break;
    }

    //Search
    if(!$search_term == ''){
      $this->db->or_like('filestorage.real_name', $search_term);
    }

    $this->db->select('filestorage.real_name, filestorage.storage_engine, filestorage.id, filestorage.real_name, filestorage.storage_name,
    storage_types.display_name as storage_engine_name, DATE_FORMAT(FROM_UNIXTIME(filestorage.created), "%e %b %Y %h:%m%p") AS uploaded_date, filestorage.filesize');
    $this->db->join('storage_types', 'storage_types.id = filestorage.storage_engine');
    return $this->db->get('filestorage')->result_array();
  }

  public function getUserFiles($user_id){
    $this->db->where('user_id', $user_id);
    return $this->db->get('filestorage')->result_array();
  }

  public function getUserFolders($user_id){
    $this->db->where('user_id', $user_id);
    return $this->db->get('folders')->result_array();
  }

  public function delete($storage_name){
    $this->db->where('storage_name', $storage_name);
    $this->db->delete('filestorage');
    return True;
  }

  public function deleteFolder($public_key){
    $this->db->where('public_key', $public_key);
    $this->db->delete('folders');
    return true;
  }

  public function deleteFolderSearch($search){
    $this->db->where($search);
    $this->db->delete('folders');
    return true;
  }


  // User //

  public function createFolder($parent_id, $name, $user_id){
    $folder_id = $this->getUniqueKey('folders', 'public_key');
    $this->db->insert('folders', array('parent' => $parent_id, 'folder_name' => $name, 'user_id' => $user_id, 'public_key' => $folder_id));
    return true;
  }

  public function getContent($parent, $user_id=null){
    if($user_id !== null){
      $this->db->where('user_id', $user_id);
    }
    $this->db->where('parent', $parent);
    $this->db->where('trash', 0);
    $data['folders'] = $this->db->get('folders')->result_array();

    if($user_id !== null){
      $this->db->where('user_id', $user_id);
    }
    $this->db->where('parent', $parent);
    $this->db->where('trash', 0);
    $data['files'] = $this->db->get('filestorage')->result_array();

    return $data;
  }

  public function getPremiumContent($user_id){
    $this->db->where('user_id', $user_id);
    $this->db->where('marked', 1);
    $data['folders'] = $this->db->get('folders')->result_array();

    $this->db->where('user_id', $user_id);
    $this->db->where('marked', 1);
    $data['files'] = $this->db->get('filestorage')->result_array();

    return $data;
  }

  public function getTrashCanContent($user_id){
    $this->db->where('user_id', $user_id);
    $this->db->where('trash', 1);
    $data['folders'] = $this->db->get('folders')->result_array();

    $this->db->where('user_id', $user_id);
    $this->db->where('trash', 1);
    $data['files'] = $this->db->get('filestorage')->result_array();

    return $data;
  }

  public function getFolderInfo($search, $user_id = null){
    $this->db->select('folders.*');
    $this->db->where($search);
    if(!$user_id == ''){
      $this->db->where('user_id', $user_id);
    }
    $result = $this->db->get('folders')->row_array();
    return $result;
  }

  public function folderExists($search, $user_id = null){
    $this->db->where($search);
    if(!$user_id == ''){
      $this->db->where('user_id', $user_id);
    }
    if($this->db->count_all_results('folders') == 1){
      return true;
    }
  }

  public function updatefolder($search, $update){
    $this->db->where($search);
    $this->db->update('folders', $update);
    return true;
  }

  public function updateFile($search, $update){
    $this->db->where($search);
    $this->db->update('filestorage', $update);
    return true;
  }

  public function fileIntoTrash($file_id){
    $this->db->where('id', $file_id);
    $this->db->update('filestorage', array('trash' => 1));
    return true;
  }

  public function getFullFolderPath($folder_id){
    //Break if we're in the main folder
    if($folder_id == 0){
      return array();
    }
    $current_id = $folder_id;
    $paths = array();
    $x=1;
    do {
      $x++;
      $this->db->where('id', $current_id);
      $this->db->select('id, public_key, folder_name, parent');
      $parent = $this->db->get('folders')->row_array();
      $paths[$x] = $parent;
      $current_id = $parent['parent'];
    } while ($current_id > 0);
    asort($paths);
    return $paths;
  }

  public function userPermission($access_type, $file_name, $user_id){

    $this->db->where('storage_name', $file_name);
    $this->db->where('user_id', $user_id);
    if($this->db->count_all_results('filestorage') == 1){
      return true;
    }

    //Check if the file is shared with the user
    //Check if the file is in a folder the user has access to
    if($this->hasSharedAccessFile($file_name, $user_id)){
      switch ($access_type) {
        case 'edit':
          if($this->sharedPermission == 1){ return true; }
          break;
        case 'view':
          if($this->sharedPermission == 1 || $this->sharedPermission == 0){ return true; }
          break;
      }
    }

    return false;
  }

  public function userPermissionFolder($access_type, $public_key, $user_id){

    $this->db->where('public_key', $public_key);
    $this->db->where('user_id', $user_id);
    if($this->db->count_all_results('folders') == 1){
      return true;
    }

    //Check if the file is shared with the user
    //Check if the file is in a folder the user has access to
    if($this->hasSharedAccessFolder($public_key, $user_id)){
      switch ($access_type) {
        case 'edit':
          if($this->sharedPermission == 1){ return true; }
          break;
        case 'view':
          if($this->sharedPermission == 1 || $this->sharedPermission == 0){ return true; }
          break;
      }
    }

    return false;
  }

  public function hasSharedAccessFolder($public_key, $user_id){
    //Get some details about that folder
    $this->db->where('public_key', $public_key);
    $this->db->where('trash !=', 1);
    $this->db->select('id, parent');
    $folder = $this->db->get('folders')->row_array();
    $parent = $folder['parent'];

    //Check if the folder is directly shared with the user
    $this->db->where('folder_id', $folder['id']);
    $this->db->where('user_id', $user_id);
    if($this->db->count_all_results('shared_folders') == 1){
      $this->getPermissionLevel($folder['id'], $user_id);
      return true;
    }

    //Check if a parent folder is shared with the user
    while($parent > 0){
      $this->db->where('folder_id', $parent);
      $this->db->where('user_id', $user_id);
      if($this->db->count_all_results('shared_folders') == 1){
        $this->getPermissionLevel($parent, $user_id);
        return true;
      }
      //Get a new parent
      $this->db->where('id', $parent);
      $this->db->where('trash !=', 1);
      $this->db->select('id, parent');
      $tmp_folder = $this->db->get('folders')->row_array();
      if(empty($tmp_folder)){
        $tmp_folder['parent'] = 0;
      }
      $parent = $tmp_folder['parent'];
    }
    return false;

  }

  public function hasSharedAccessFile($storage_name, $user_id){
    //Get some details about that folder
    $this->db->where('storage_name', $storage_name);
    $this->db->where('trash !=', 1);
    $this->db->select('id, parent');
    $file = $this->db->get('filestorage')->row_array();
    $parent = $file['parent'];

    //Check if the file is directly shared with the user
    $this->db->where('file_id', $file['id']);
    $this->db->where('user_id', $user_id);
    if($this->db->count_all_results('shared_files') == 1){
      $this->sharedPermission = 0;
      return true;
    }

    //Check if a parent folder is shared with the user
    while($parent > 0){
      $this->db->where('folder_id', $parent);
      $this->db->where('user_id', $user_id);
      $this->db->select('folder_id');
      $result = $this->db->get('shared_folders')->row_array();

      if(!empty($result)){
        $this->getPermissionLevel($result['folder_id'], $user_id);
        return true;
      }
      //Get a new parent
      $this->db->where('id', $parent);
      $this->db->select('id, parent');
      $this->db->where('trash !=', 1);
      $tmp_folder = $this->db->get('folders')->row_array();
      if(empty($tmp_folder)){
        $tmp_folder['parent'] = 0;
      }
      $parent = $tmp_folder['parent'];
    }
    return false;

  }

  // End User //

  public function getUniqueKey($table, $column){
    while(true){
      $key = $this->generateRandomString();
      $this->db->where($column, $key);
      if ($this->db->count_all_results($table) == 0){
        return $key;
      }
    }
  }

  public function createShareFile($share_id, $user_id){
    $data = array(
      'file_id' => $share_id,
      'user_id' => $user_id
    );
    $this->db->insert('shared_files', $data);
    return true;
  }

  public function fileShareExists($where){
    $this->db->where($where);
    if($this->db->count_all_results('shared_files') == 1){
      return true;
    }
    return false;
  }

  public function deleteFileShare($where){
    $this->db->where($where);
    $this->db->delete('shared_files');
    return true;
  }

  public function createShareFolder($share_id, $user_id, $permission){
    $data = array(
      'folder_id' => $share_id,
      'user_id' => $user_id,
      'permission' => $permission
    );
    $this->db->insert('shared_folders', $data);
    return true;
  }

  public function folderShareExists($where){
    $this->db->where($where);
    if($this->db->count_all_results('shared_folders') == 1){
      return true;
    }
    return false;
  }

  public function deletefolderShare($where){
    $this->db->where($where);
    $this->db->delete('shared_folders');
    return true;
  }

  public function fileShares($file_id){
    $this->db->where('file_id', $file_id);
    $this->db->select('users.email, users.firstname, users.lastname, shared_files.id as share_id');
    $this->db->join('users', 'users.id=shared_files.user_id');
    return $this->db->get('shared_files')->result_array();
  }

  public function folderShares($folder_id){
    $this->db->where('folder_id', $folder_id);
    $this->db->select('users.email, users.firstname, users.lastname, shared_folders.id as share_id');
    $this->db->join('users', 'users.id=shared_folders.user_id');
    return $this->db->get('shared_folders')->result_array();
  }

  public function sharedAccessFiles($user_id){
    $this->db->where('shared_files.user_id', $user_id);
    $this->db->join('filestorage', 'shared_files.file_id=filestorage.id');
    return $this->db->get('shared_files')->result_array();
  }

  public function sharedAccessFolders($user_id){
    $this->db->where('shared_folders.user_id', $user_id);
    $this->db->where('folders.marked !=', '1');
    $this->db->where('folders.trash !=', '1');
    $this->db->where('shared_folders.user_id', $user_id);
    $this->db->join('folders', 'shared_folders.folder_id=folders.id');
    return $this->db->get('shared_folders')->result_array();
  }

  private function generateRandomString($length = 20) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }

  private function getPermissionLevel($parent, $user_id){
    $this->db->select('permission');
    $this->db->where('folder_id', $parent);
    $this->db->where('user_id', $user_id);
    $result = $this->db->get('shared_folders')->row_array();

    $this->sharedPermission = $result['permission'];
  }
}
