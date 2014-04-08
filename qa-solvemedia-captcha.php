<?php


	if (!defined('QA_VERSION')) { 
		header('Location: ../');
		exit;
	}


	class qa_solvemedia_captcha {
	
		var $directory;
		
		function load_module($directory, $urltoroot)
		{
			$this->directory=$directory;
		}


		function admin_form()
		{
			$saved=false;

			if (qa_clicked('solvemediacaptcha_save_button'))
			{
				qa_opt('solvemediacaptcha_public_key', qa_post_text('solvemediacaptcha_public_key_field'));
				qa_opt('solvemediacaptcha_private_key', qa_post_text('solvemediacaptcha_private_key_field'));
				qa_opt('solvemediacaptcha_hash_key', qa_post_text('solvemediacaptcha_hash_key_field'));
				qa_opt('solvemediacaptcha_ssl_enable', (int)qa_post_text('solvemediacaptcha_ssl_enable_field'));

				$saved=true;
			}

			$form=array(
				'ok' => $saved ? 'SolveMediaCAPTCHA settings saved' : null,
				
				'fields' => array(
					'public' => array(
						'label' => 'SolveMedia CAPTCHA public (challenge) key:',
						'value' => qa_opt('solvemediacaptcha_public_key'),
						'tags' => 'name="solvemediacaptcha_public_key_field"',
						'error' => $this->keycaptcha_error_html('solvemediacaptcha_public_key', 'public (challenge) key'),
					),

					'private' => array(
						'label' => 'SolveMedia CAPTCHA private (verification) key:',
						'value' => qa_opt('solvemediacaptcha_private_key'),
						'tags' => 'name="solvemediacaptcha_private_key_field"',
						'error' => $this->keycaptcha_error_html('solvemediacaptcha_private_key', 'private (verification) key'),
					),

					'hash' => array(
						'label' => 'SolveMedia CAPTCHA hash (authentication) key:',
						'value' => qa_opt('solvemediacaptcha_hash_key'),
						'tags' => 'name="solvemediacaptcha_hash_key_field"',
						'error' => $this->keycaptcha_error_html('solvemediacaptcha_hash_key', 'hash (authentication) key'),
					),

					'ssl' => array(
						'label' => 'Send the CAPTCHA request over SSL',
						'type' => 'checkbox',
						'value' => (int)qa_opt('solvemediacaptcha_ssl_enable'),
						'tags' => 'name="solvemediacaptcha_ssl_enable_field"',
					),

				),

				'buttons' => array(
					array(
						'label' => 'Save Changes',
						'tags' => 'name="solvemediacaptcha_save_button"',
					),
				),
			);
			
			return $form;
		}
		

		function keycaptcha_error_html($key, $name)
		{
			if (!$this->is_non_empty($key))
			{
				return 'To use Solve Media CAPTCHA, you must <a href="http://solvemedia.com/">sign up</a> to get a '.$name.'.';
			}
			return null;
		}

		function allow_captcha()
		{
			return $this->is_non_empty('solvemediacaptcha_public_key') && $this->is_non_empty('solvemediacaptcha_private_key') && $this->is_non_empty('solvemediacaptcha_hash_key');
		}

		private function is_non_empty($str)
		{
			return strlen(trim(qa_opt($str)));
		}

		function form_html(&$qa_content, $error)
		{
			require_once $this->directory.'solvemedialib.php';
			return solvemedia_get_html(qa_opt('solvemediacaptcha_public_key'), NULL, (int)qa_opt('solvemediacaptcha_ssl_enable'));
		}

		function validate_post(&$error)
		{
			if (!empty($_POST['adcopy_challenge']) && !empty($_POST['adcopy_response']))
			{
				require_once $this->directory.'solvemedialib.php';
				
				$solvemedia_response = solvemedia_check_answer(qa_opt('solvemediacaptcha_private_key'), $_SERVER["REMOTE_ADDR"], $_POST["adcopy_challenge"], $_POST["adcopy_response"], qa_opt('solvemediacaptcha_hash_key'));
				if ($solvemedia_response->is_valid)
				{
					return true;
				}
				else
				{
					$error = $solvemedia_response->error;
				}
			}
			return false;
		}

	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/