<?php
defined('BASEPATH') or exit('No direct script access allowed!');

class Communication
{

	public function __construct()
	{
		$this->ci =& get_instance();
    $this->ci->load->library('email');
    $this->ci->load->model('SettingsModel');

    $this->settings = $this->ci->SettingsModel->getSetting(array('email_display_name', 'email_hostname', 'email_username', 'email_address', 'email_password', 'email_notifications'));
		if($this->settings['email_notifications'] == true){
			$this->connect();
		}
	}

  public function connect(){
    $config['protocol'] = 'smtp';
    $config['mailtype'] = 'html';
    $config['smtp_host'] = $this->settings['email_hostname'];
    $config['smtp_user'] = trim($this->settings['email_username']);
    $config['smtp_pass'] = trim($this->settings['email_password']);
    //$config['smtp_crypto'] = 'tls';
    $this->ci->email->initialize($config);
  }

  public function send($message, $data){
		if($this->settings['email_notifications'] == false){
			return true;
		}

    $this->ci->email->from($this->settings['email_address'], $this->settings['email_display_name']);
    $this->ci->email->to($data['receiver']);

    //Get the message from the database
    $subject = $this->ci->SettingsModel->getSetting('emailmsg_'.$message.'_subject');
    $content = $this->ci->SettingsModel->getSetting('emailmsg_'.$message.'_message');


    //Replace variables
    $template['message'] = $content;
    foreach($data as $key => $item){
      $template['message'] = str_replace('{'.$key.'}', $item, $template['message']);
    }
    $email_content = '';
    $email_content .= $this->ci->parser->parse('email/header.php', $template, true);
    $email_content .= $this->ci->parser->parse('email/content.php', $template, true);
    $email_content .= $this->ci->parser->parse('email/footer.php', $template, true);

    $this->ci->email->subject($subject);
    $this->ci->email->message($email_content);

    $this->ci->email->send();
    return true;
  }

}
