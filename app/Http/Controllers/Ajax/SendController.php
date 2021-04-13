<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

use DB;
use Mail;
use Validator;

use App\Models\Feedback;
use App\Models\JobApps;

use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

class SendController extends Controller {
    
    public function __construct(){
        parent::__construct();
    }
    
    public $_allow_files = [
		'jpg'	=> 'image/jpeg',
		'jpeg'	=> 'image/jpeg',
		'jpe'	=> 'image/jpeg',
		'png'	=> 'image/png',
		'doc'	=> [
			'application/octet-stream',
			'application/wps-office.doc',
			'application/wps-office.pdf',
			'application/wps-office.docx',
			'application/msword'
		],
		'docx'	=> [
			'application/octet-stream',
			'application/wps-office.doc',
			'application/wps-office.docx',
			'application/msword'
		],
		'pdf'	=> [
			'application/octet-stream',
			'application/wps-office.pdf',
			'application/pdf'
		],
		'djvu'	=> [
			'application/octet-stream',
			'application/wps-office.djvu',
			'application/djvu'
		],
    ];
    
    public $_check_mimes = false;
    public $_max_size = 5242880; // 5Mb
    
    function getExtension($filename){
		$path_info = pathinfo($filename);
		
		return $path_info['extension'];
	}
    
    private function sendEmail($key, $to = null, $data){
		$settings = [
			'send_type'				=> '',
			'smtp_host'				=> '',
			'smtp_port'				=> '',
			'smtp_username' 		=> '',
			'smtp_password' 		=> '',
			'google_app_password'	=> '',
			'email'					=> '',
			'email_for_letters'		=> [],
		];
		
		$tmp = DB::table('admin_config')->whereIn('name', array_keys($settings))->select('name', 'value')->get();
		
		if(count($tmp)){
			foreach($tmp as $item){
				$item->value = trim($item->value);
				
				if($item->name == 'email_for_letters'){
					$values = explode(',', $item->value);
					
					$item->value = [];
					
					foreach($values as $v){
						$v = trim($v);
						
						if($v){
							$item->value[] = $v;
						}
					}
				}
				
				$settings[$item->name] = $item->value;
			}
		}
		
		$tmp = null;
		
		if($settings['send_type'] == 'smtp'){
			if(!$settings['smtp_host'] || !$settings['smtp_port'] || !$settings['smtp_username'] || !$settings['smtp_password'] || !$settings['email_for_letters']){
				return false;
			}
		}
		
		$template = DB::table('email_templates')->where('slug', $key)->select('content', 'subject', 'from_name', 'from_email')->first();
		
		if($template && $template->content){
			if($settings['send_type'] == 'smtp'){
				config([
					'mail.driver'		=> 'smtp',
					'mail.host'			=> $settings['smtp_host'],
					'mail.port'			=> $settings['smtp_port'],
					'mail.encryption'	=> 'ssl',
					'mail.username'		=> $settings['smtp_username'],
					'mail.password'		=> ($settings['google_app_password'] && $settings['smtp_host'] == 'smtp.gmail.com') ? $settings['google_app_password'] : $settings['smtp_password'],
					'mail.from'			=> [
						'address'	=> $template->from_email,
						'name'		=> $template->from_name,
					]
				]);
			}else{
				config([
					'mail.driver'		=> 'sendmail',
					'mail.from'			=> [
						'address'	=> $template->from_email,
						'name'		=> $template->from_name,
					]
				]);
			}
			
			$search = [];
			$keys = array_keys($data);
			
			foreach($keys as $item){
				$search[] = '{'.$item.'}';
			}
			
			$template->content = str_replace($search, array_values($data), $template->content);
			
			if(!$to){
				$to = $settings['email_for_letters'];
			}
			
			$file = '';
			
			if(isset($data['file']) && $data['file']){
				$file = $data['file'];
			}
			
			Mail::send(
				'emails.raw', 
				array(
					'content' => $template->content
				), 
				function($message) use ($to, $template, $file){
					$message->from($template->from_email, $template->from_name);
					
					if(!is_array($to)){
						$message->to($to);
					}else{
						$message->to($to[0]);
						
						if(isset($to[1])){
							$message->cc($to[1]);
						}
						
						if(isset($to[2])){
							$message->bcc($to[2]);
						}
					}
					
					$message->subject($template->subject);
					
					if($file){
						$message->attach(
							storage_path('app/admin/'.$file), 
							[
								'as'	=> basename($file),
								//'mime'	=> 'application/pdf',
							]
						);
					}
				}
			);
			
			return true;
		}
		
		return false;
	}
    
    //
    
    function contact(Request $request){
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_send_feedback');
		
		$post = $request->all();
		
		$validator = Validator::make(
			$post,
			array(
				'name'              => 'required|min:2|max:40',
				'email'            	=> 'email',
				'message'        	=> 'required|min:10|max:300',
			),
			array(
				'name.required'     => trans('ajax_validation.enter_your_name'),
				'name.min'          => trans('ajax_validation.min_length'),
				'name.max'          => trans('ajax_validation.max_length'),
				
				'email.required'    => trans('ajax_validation.email_required'),
				'email.email'       => trans('ajax_validation.email_invalid'),
				
				'message.required'	=> trans('ajax_validation.required'),
				'message.min'     	=> trans('ajax_validation.min_length'),
				'message.max'   	=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$error = false;
			
			if(!$error){
				$file		= '';
				$file_name	= '';
				$file_type	= '';
				$file_src	= '';
				
				if(!empty($post['file'])){
					if(!is_dir(ROOT.'/storage/feedback')){
						mkdir(ROOT.'/storage/feedback');
					}
					
					$code = md5(uniqid(time()));
					
					// file_get_contents('php://input')
					
					$data		= explode(';base64,', $post['file']);
					$file_type	= str_replace('data:', '', $data[0]);
					$ext		= $this->getExtension($post['file_name']);
					
					if($ext && isset($this->_allow_files[$ext])){
						$file_src	= 'storage/feedback/'.$code.'.'.$ext;
						
						file_put_contents(ROOT.'/'.$file_src, base64_decode($data[1]));
						
						$data = null;
						
						$info = new \SplFileInfo(ROOT.'/'.$file_src);
						
						$size = $info->getSize();
						
						$mime = mime_content_type(ROOT.'/'.$file_src);
						
						if($size > $this->_max_size){
							unlink(ROOT.'/'.$file_src);
							
							$file		= '';
							$file_name	= '';
							$file_type	= '';
							$file_src	= '';
							
							$error = true;
							
							$msg = trans('ajax_validation.file_size_exceeded');
						}else{
							if($this->_check_mimes){
								$mimes = $this->_allow_files[$ext];
								
								if(is_array($mimes)){
									if(!in_array($mime, $mimes)){
										unlink(ROOT.'/'.$file_src);
										
										$file		= '';
										$file_name	= '';
										$file_type	= '';
										$file_src	= '';
										
										$error = true;
										
										$msg = trans('ajax_validation.unsupported_file_format');
									}
								}else{
									if($mimes != $mime){
										unlink(ROOT.'/'.$file_src);
										
										$file		= '';
										$file_name	= '';
										$file_type	= '';
										$file_src	= '';
										
										$error = true;
										
										$msg = trans('ajax_validation.unsupported_file_format');
									}
								}
							}
						}
						
						//$path = Storage::disk('public')->put('file.txt', 'Contents');
					}else{
						$file		= '';
						$file_name	= '';
						$file_type	= '';
						$file_src	= '';
						
						$error = true;
						
						$msg = trans('ajax_validation.unsupported_file_format');
					}
				}
			}
			
			if(!$error){
				$insert = Feedback::create([
					'name'		=> $post['name'],
					'email'		=> $post['email'],
					'message'	=> $post['message'],
					'file'		=> $file_src ? str_replace('storage/', '', $file_src) : '',
					'type'		=> $file_type,
				]);
				
				$this->sendEmail('feedback', null, [
					'id'	=> $insert->id,
					'name'	=> $post['name'],
					'email'	=> $post['email'],
					'text'	=> $post['message'],
					'url'	=> url('/admin/feedback/'.$insert->id.'/edit'),
					'file'	=> $file_src ? 'feedback/'.basename($file_src) : '',
				]);
				
				$status = true;
				$msg	= trans('ajax.success_send_feedback');
			}
		}else{
			$messages = $validator->messages();
			
			foreach($post as $k => $v){
				$error = $messages->first($k);
				
				if($error){
					$errors[$k] = $error;
				}
			}
		}
		
		return array(
			'status'    => $status,
			'msg'       => $msg,
			'errors'    => $errors
		);
	}
	
	function job(Request $request){
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_send_job');
		
		$post = $request->all();
		
		$validator = Validator::make(
			$post,
			array(
				'name'              => 'required|min:2|max:40',
				'email'            	=> 'email',
				'tel'             	=> 'required|min:10|max:20',
				'spec'           	=> 'required|min:5|max:100'
			),
			array(
				'name.required'     => trans('ajax_validation.enter_your_name'),
				'name.min'          => trans('ajax_validation.min_length'),
				'name.max'          => trans('ajax_validation.max_length'),

				'email.required'    => trans('ajax_validation.email_required'),
				'email.email'       => trans('ajax_validation.email_invalid'),
				
				'tel.required'     	=> trans('ajax_validation.phone_required'),
				'tel.min'          	=> trans('ajax_validation.min_length'),
				'tel.max'          	=> trans('ajax_validation.max_length'),
				
				'spec.required'		=> trans('ajax_validation.required'),
				'spec.min'     		=> trans('ajax_validation.min_length'),
				'spec.max'    		=> trans('ajax_validation.max_length')
			)
		);
		
		if($validator->passes()){
			$error = false;
			
			$post['tel'] = (string)preg_replace("/[^0-9]/", '', $post['tel']);
			
			$len = strlen($post['tel']);
			
			if($len < 10 || $len > 12){
				$error = true;
			}
			
			if(!$error){
				$file		= '';
				$file_name	= '';
				$file_type	= '';
				$file_src	= '';
				
				if(!empty($post['file'])){
					if(!is_dir(ROOT.'/storage/jobs')){
						mkdir(ROOT.'/storage/jobs');
					}
					
					$code = md5(uniqid(time()));
					
					// file_get_contents('php://input')
					
					$data		= explode(';base64,', $post['file']);
					$file_type	= str_replace('data:', '', $data[0]);
					$ext		= $this->getExtension($post['file_name']);
					
					if($ext && isset($this->_allow_files[$ext])){
						$file_src	= 'storage/jobs/'.$code.'.'.$ext;
						
						file_put_contents(ROOT.'/'.$file_src, base64_decode($data[1]));
						
						$data = null;
						
						$info = new \SplFileInfo(ROOT.'/'.$file_src);
						
						$size = $info->getSize();
						
						$mime = mime_content_type(ROOT.'/'.$file_src);
						
						if($size > $this->_max_size){
							unlink(ROOT.'/'.$file_src);
							
							$file		= '';
							$file_name	= '';
							$file_type	= '';
							$file_src	= '';
							
							$error = true;
							
							$msg = trans('ajax_validation.file_size_exceeded');
						}else{
							if($this->_check_mimes){
								$mimes = $this->_allow_files[$ext];
								
								if(is_array($mimes)){
									if(!in_array($mime, $mimes)){
										unlink(ROOT.'/'.$file_src);
										
										$file		= '';
										$file_name	= '';
										$file_type	= '';
										$file_src	= '';
										
										$error = true;
										
										$msg = trans('ajax_validation.unsupported_file_format');
									}
								}else{
									if($mimes != $mime){
										unlink(ROOT.'/'.$file_src);
										
										$file		= '';
										$file_name	= '';
										$file_type	= '';
										$file_src	= '';
										
										$error = true;
										
										$msg = trans('ajax_validation.unsupported_file_format');
									}
								}
							}
						}
						
						//$path = Storage::disk('public')->put('file.txt', 'Contents');
					}else{
						$file		= '';
						$file_name	= '';
						$file_type	= '';
						$file_src	= '';
						
						$error = true;
						
						$msg = trans('ajax_validation.unsupported_file_format');
					}
				}
			}
			
			if(!$error){
				//$insert_id = DB::table('feedback')->insertGetId([
				
				$insert = JobApps::create([
					'name'		=> $post['name'],
					'email'		=> $post['email'],
					'phone'		=> $post['tel'],
					'job'		=> $post['spec'],
					'file'		=> $file_src ? str_replace('storage/', '', $file_src) : '',
					'type'		=> $file_type,
				]);
				
				$this->sendEmail('job', null, [
					'id'	=> $insert->id,
					'name'	=> $post['name'],
					'email'	=> $post['email'],
					'phone'	=> $post['tel'],
					'job'	=> $post['spec'],
					'url'	=> url('/admin/jobs/'.$insert->id.'/edit'),
					'file'	=> $file_src ? 'jobs/'.basename($file_src) : '',
				]);
				
				$status = true;
				$msg	= trans('ajax.success_send_job');
			}
		}else{
			$messages = $validator->messages();
			
			foreach($post as $k => $v){
				$error = $messages->first($k);
				
				if($error){
					$errors[$k] = $error;
				}
			}
		}
		
		return array(
			'status'    => $status,
			'msg'       => $msg,
			'errors'    => $errors
		);
	}
}
