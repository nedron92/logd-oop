<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Template.php
 * @author  Daniel Becker  <becker_leinad@hotmail.com>
 * @date    28.02.2015
 * @package game_core
 * @subpackage LOGD
 *
 * @description
 * This class will load and render the specific (standard)
 * template of the game
 */

class LOGD_Template {

	/**
	 * @var  string   Hold the standard template of the game
	 */
	private  $s_start_template = 'yar2';

	/**
	 * @var string  contain the directory in which the templates are located
	 */
	private $s_template_dir = 'templates';

	/**
	 * @var  string   Contain the name of the current used template
	 */
	private  $s_current_template_name = null;

	/**
	 * @var  string  Contain the hole template as a string for later replacing
	 */
	private  $s_current_template = null;

	/**
	 * @var  array  Hold the current html for the statsbar, devide from template
	 */
	private $a_stats_html = array();

	/**
	 * @var  array  Contain the output of all template-tags
	 */
	private $a_output = array();

	/**
	 * @var null|LOGD_Template  hold the current instance of the class.
	 */
	private static $o_instance = null;

	/**
	 * Get singleton instance of the class or create new one, if no exists.
	 *
	 * @param null|string $s_template_name  the name of the used template
	 * @param        bool $b_use_cache      A value to define if the template should use
	 *                                      the cache (true) or not(false)
	 *                                      default: true
	 *
	 * @return LOGD_Template|null           the instance
	 */
	public static function get_instance($s_template_name=null,$b_use_cache=true)
	{
		if ( null === self::$o_instance ) {
			self::$o_instance = new self( $s_template_name, $b_use_cache );
		}

		return self::$o_instance;
	}

	/**
	 * Constructor of the class.
	 * set a new current template-name if the param is not null
	 * and loaded it.
	 *
	 * @fallback: current_template_name = start_template
	 *
	 * @param null|string $s_template_name  the name of the used template
	 * @param        bool $b_use_cache      A value to define if the template should use
	 *                                      the cache (true) or not(false)
	 *                                      default: true
	 *
	 */
	private function __construct($s_template_name=null,$b_use_cache=true)
	{
		if(is_null($s_template_name)) {
			$this->s_current_template_name = $this->s_start_template;
		}else {
			$this->s_current_template_name = $s_template_name;
		}

		try {
			$this->load_template($this->s_current_template_name,$b_use_cache);
		}catch (LOGD_Exception $exc) {
			$exc->print_error();
		}
	}

	/**
	 * Defined the clone function as private, because of singleton pattern
	 */
	private function __clone()
	{}

	/**
	 * Load a new template by a given name.
	 * If the given name is null or the given template can't be found, use a fallback.
	 *
	 * @fallback    use the standard template
	 *
	 * @param null|string $s_template_name  the name of the used template
	 * @param        bool $b_use_cache      A value to define if the template should use
	 *                                      the cache (true) or not(false)
	 *                                      default: true
	 */
	public function load_template($s_template_name=null, $b_use_cache=true)
	{
		//  user standard template if nothing is given
		if (is_null($s_template_name)) $s_template_name = $this->s_start_template;

		// set the path to the template with following structure:
		//  (DIR)Templatename/templatename.{EXTENSION} relative to the template directory
		// The {Extension} will be set automatically, not need here directly.
		$s_template_path = $s_template_name.DIRECTORY_SEPARATOR.$s_template_name;

		try{
			//todo: Maybe write a log message which template can't be loaded!
			$s_path = LOGD::find_file($this->s_template_dir,$s_template_path);

			if(!$s_path){
				//Template not found! Check if the standard one wasn't found,
				//if yes throw exception
				if($s_template_name === $this->s_start_template)
				{
					throw new LOGD_Exception('Could not load the standard template "'.$s_template_name.'".
				Please check if its available in the template directory.','TEMPLATE_ERROR');
				}
				else self::load_template($this->s_start_template);

			}else{
				if($b_use_cache && Cache::getInstance()->exists('template_'.$s_template_name.'_html')
								&& Cache::getInstance()->exists('template_'.$s_template_name.'_stats'))
				{
					$this->s_current_template = Cache::getInstance()->get('template_'.$s_template_name.'_html');
					$this->a_stats_html = Cache::getInstance()->get('template_'.$s_template_name.'_stats');
				}else{
					if(!$b_use_cache && Cache::getInstance()->exists('template_'.$s_template_name.'_html')
					   && Cache::getInstance()->exists('template_'.$s_template_name.'_stats'))
					{
						Cache::getInstance()->delete_entry('template_'.$s_template_name.'_html');
						Cache::getInstance()->delete_entry('template_'.$s_template_name.'_stats');
					}
					//All ok! Load the template and buffer the output
					$a_global_template_variables = array(
						'template_directory' => MEDIA_URL.$this->s_template_dir.'/'.$s_template_name.'/',
						's_base_url' => dirname(BASE_URL).'/game_core/classes/',
					);

					extract($a_global_template_variables, EXTR_SKIP);
					ob_start();
					include $s_path;

					//Trim the buffered output and set it to a variable
					$s_full_template = trim(ob_get_clean());

					//Check if all needed tags are in the template string
					//todo write a extra method for all needed tags
					$a_needed_tags = array('title','navigation','motd','mail','petition','stats','stats-head',
						'stats-left','stats-right','game','onlineuser','copyright','pagegen',
						'version','source');

					$a_failed_tags = array();
					$i_key = 0;
					foreach($a_needed_tags as $tag)
					{
						if(!strpos($s_full_template,'{'.$tag.'}'))
						{
							$a_failed_tags[$i_key] = $tag;
							$i_key++;
						};
					}

					if(!is_null($a_failed_tags) && sizeof($a_failed_tags) !== 0)
					{
						$s_failed_tags = implode(' , ',$a_failed_tags);
						throw new LOGD_Exception('Following tags could not be found in your template,
					 but have to be in it: <br><b>'.$s_failed_tags.'</b><br>
					 Please check your template "'.$s_template_name.'"','TEMPLATE_TAG_ERROR');
					}



					//extract the special statsbar html of the template.
					$s_stats_html = substr($s_full_template,strpos($s_full_template,'<!--!stats-->')+13);
					$s_stats_html = substr($s_stats_html,0,strpos($s_stats_html,'<!--end!-->'));
					$a_stats_html = array();

					//replace the statsbar html with nothing! Because we don't need the standard one anymore.
					$s_full_template = str_replace($s_stats_html,null,$s_full_template);

					//explode all needed tags in the statsbar html (stats-head, stats-left, stets-right)
					foreach(explode('</div>',$s_stats_html) as $key => $value)
					{
						$value = trim($value);
						if(is_null($value) || $value === '') continue;
						$value = $value.'</div>'; //added the exploded </div> tag again, to avoid failures.

						$s_field_value = substr($value,strpos($value,'{')+1);
						$s_field_value = substr($s_field_value,0,strpos($s_field_value,'}'));
						$a_stats_html[$s_field_value] = $value;
					}

					//Set the current html of the template
					$this->s_current_template = $s_full_template;
					$this->a_stats_html = $a_stats_html;

					if($b_use_cache && !Cache::getInstance()->exists('template_'.$s_template_name.'_html')
					   && !Cache::getInstance()->exists('template_'.$s_template_name.'_stats'))
					{
						Cache::getInstance()->set('template_'.$s_template_name.'_html',$s_full_template,86400);
						Cache::getInstance()->set('template_'.$s_template_name.'_stats',$a_stats_html,86400);
					}
				}

				//load also all needed standard css and js.
				$this->load_standard_css_js();
			}

		} catch(LOGD_Exception $e) {
			$e->print_error();
		}

	}

	/**
	 * Print out the full current and correct output of the template,
	 * based on the current view.
	 * Do a full replacement of all needed things before.
	 *
	 * @param   bool    $b_return_output    Should the output be printed or returned?
	 * @return  null|string
	 */
	public function render($b_return_output = false)
	{
		$this->template_replace();
		if(!$b_return_output) {
			echo $this->s_current_template;
			return null;
		}else{
			return $this->s_current_template;
		}
	}

	/**
	 * Set the new/current output for the given itemname/template-tag and store it.
	 *
	 * @param string        $itemname   the name of the template-tag ('title' etc.)
	 * @param null|string   $value      the new output at the tag-place (concat each other)
	 * @param bool          $stats      is the tag name a statsbar one?
	 */
	public function set_output($itemname, $value=null, $stats=false)
	{
		if(!$stats)
		{
			if(strpos($this->s_current_template,$itemname) != false) {
				$this->a_output[$itemname].= $value;
			}
		} else {
			if(strpos($this->a_stats_html[$itemname],$itemname) != false) {
				$this->a_output['stats'].= str_replace("{"."$itemname"."}",$value,$this->a_stats_html[$itemname]);
			}
		}
	}


	/**
	 * Do a full template replacement with the stored current output
	 * from set_output before.
	 *
	 */
	public function template_replace()
	{
		$a_replace = $this->a_output;
		if(is_null($a_replace)) $a_replace = array();

		foreach($a_replace as $key => $value) {
			$this->s_current_template = str_replace("{"."$key"."}",$value,$this->s_current_template);
		}
	}

	/**
	 * Loading the defined standard css and js scripts
	 */
	private function load_standard_css_js()
	{
		//setting the array with all needed css/js scripts as standard
		$a_standard_scripts = array(
			'jQuery' => MEDIA_URL.'js/libs/jquery-2.1.3.js',
			'synchHeight-js' => MEDIA_URL.'js/plugin/syncheight/jquery.syncHeight.min.js',
			'bootstrap-js' => MEDIA_URL.'js/plugin/bootstrap/bootstrap.js',
			'popupoverlay' => MEDIA_URL.'js/plugin/popupoverlay/jquery.popupoverlay.js',

			'bootstrap' => MEDIA_URL.'css/bootstrap/bootstrap.css',
			$this->s_current_template_name => MEDIA_URL.$this->s_template_dir.'/'.$this->s_current_template_name.'/'.$this->s_current_template_name.'.css',
			'game-js' => MEDIA_URL.'js/'.$this->s_template_dir.'/'.$this->s_current_template_name .'/game.js',
			'main-js' => MEDIA_URL.'js/main.js',
		);

		foreach($a_standard_scripts as $scripts) {
			switch(pathinfo($scripts, PATHINFO_EXTENSION))
			{
				case 'css':
				{ echo '<link href="'.$scripts.'" rel="stylesheet" type="text/css">'; break; }
				case 'js':
				{ echo '<script src="'.$scripts.'"></script>'; }
			}
		}
	}
}