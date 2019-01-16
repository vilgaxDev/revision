<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('aws-autoloader.php');

class AwsS3{

  public function __construct(){
    $this->ci =& get_instance();
    $this->ci->load->model('SettingsModel');
  }

  //Connct to the storage server
  public function connect(){
    //Authenticate
    $this->key = $this->ci->SettingsModel->getSetting('awsS3_key');
    $this->secret = $this->ci->SettingsModel->getSetting('awsS3_secret');
    $this->region = $this->ci->SettingsModel->getSetting('awsS3_region');
    $this->bucket = $this->ci->SettingsModel->getSetting('awsS3_bucket');

    $config = array(
        'credentials' => array(
          'key'    => $this->key,
          'secret' => $this->secret
        ),
        'version' => 'latest',
        'region' => $this->region

    );

    $this->s3Client = Aws\S3\S3Client::factory($config);
  }

  //Saves a file to the storage
  public function upload($file){
    $result = $this->s3Client->putObject(array(
      'Bucket' => $this->bucket,
      'Key' => $file['storage_name'],
      'SourceFile' => $file['tmp_name'],
      'ContentType' => $file['type']
    ));
    $this->s3Client->waitUntil('ObjectExists', array(
      'Bucket' => $this->bucket,
      'Key'    => $file['storage_name']
    ));
    return true;
  }

  //Downloads a file from the server
  public function download($storage_name){
    $cmd = $this->s3Client->getCommand('GetObject', [
      'Bucket' => $this->bucket,
      'Key'    => $storage_name
    ]);
    $request = $this->s3Client->createPresignedRequest($cmd, '+20 minutes');
    $presignedUrl = (string) $request->getUri();
    return file_get_contents($presignedUrl);
  }

  //Deletes a file
  public function delete($storage_name){
    $this->s3Client->deleteObject(array(
      'Bucket' => $this->bucket,
      'Key'    => $storage_name
    ));
    return true;
  }

  public function allSettings(){
    return array(
      array(
        'name' => 'key',
        'label' => 'AWS Account key',
        'type' => 'text'
      ),
      array(
        'name' => 'secret',
        'label' => 'AWS Account secrect',
        'type' => 'text'
      ),
      array(
        'name' => 'bucket',
        'label' => 'AWS Bucket',
        'type' => 'text'
      ),
      array(
        'name' => 'region',
        'label' => "AWS Region",
        'type' => "text"
      )
    );
  }
}
