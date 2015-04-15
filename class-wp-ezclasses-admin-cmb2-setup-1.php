<?php
/** 
 * ez-Tizes the setup arg for WebDevStudio's plugin CMB2 (custom metaboxes and fields).
 *
 * Allows for the adding of validation args to the CMB2 setup array, as well as some other necessary (if not #BadAss) ez magic. 
 *
 * PHP version 5.3
 *
 * LICENSE: TODO
 *
 * @package WPezClasses
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.1
 * @license TODO
 */
 
/**
 * == Change Log ==
 *
 * == 0.5.1 - Wed 15 April 2015 ==
 * --- ADDED: prefix to main key of array output by method cmb2_setup_do(). This makes a given (ezCMB2) template easier to reuse withing a given application. Previously identical keys would be overwritten.
 *
 *
 * == 0.5.0 - Mon 1 Dec 2014 ==
 * --- Pop the champagne!
 */
 
/**
 * == TODO == 
 *
 *
 */

// No WP? Die! Now!!
if (!defined('ABSPATH')) {
	header( 'HTTP/1.0 403 Forbidden' );
    die();
}

if ( ! class_exists('Class_WP_ezClasses_Admin_CMB2_Setup_1') ) {
  class Class_WP_ezClasses_Admin_CMB2_Setup_1 extends Class_WP_ezClasses_Master_Singleton{
  
    private $_version;
	private $_url;
	private	$_path;
	private $_path_parent;
	private $_basename;
	private $_file;
  
    protected $_arr_init;
	protected $_str_prefix;
	protected $_arr_post_types;
	protected $_str_localization_domain;  // TODO
	  
	public function __construct() {
	  parent::__construct();
	}
		
	/**
	 *
	 */
	public function ez__construct($arr_args = ''){
	
	  $this->setup();
	 
	  $arr_init_defaults = $this->init_defaults();
	  $arr_todo = $this->cmb2_todo();
	  $this->_arr_init = WPezHelpers::ez_array_merge(array($arr_init_defaults, $arr_todo, $arr_args));
	  
	  $this->_str_prefix = $this->_arr_init['prefix'];
	  $this->_arr_post_types = $this->_arr_init['post_types'];
	  $this->_str_localization_domain = $this->_arr_init['localization_domain'];
	  
	  $int_priority = 10;
	  if ( isset($this->_arr_init['priority']) ){
	    $int_priority = $this->_arr_init['priority'];
	  }
	  
	  add_filter( 'cmb2_meta_boxes', array($this, 'cmb2_meta_boxes_filter'), $int_priority );
	 
	}
	
	/**
	 * 
	 */
	protected function setup(){
	
	  $this->_version = '0.5.0';
	  $this->_url = plugin_dir_url( __FILE__ );
	  $this->_path = plugin_dir_path( __FILE__ );
	  $this->_path_parent = dirname($this->_path);
	  $this->_basename = plugin_basename( __FILE__ );
	  $this->_file = __FILE__ ;
	}
	
	/**
	 *
	 */
	protected function init_defaults(){
	
	  $arr_defaults = array(
	  	'active'			 					=> false,	// currently NA
		'active_true'							=> false,	// currently NA - use the active true "filtering"
		'filters'								=> false, 	// currently NA
		'arr_arg_validation'					=> false, 	// currently NA
		);
	  return $arr_defaults;
	}
	
	/**
	 * at the very least you're gonna have to do these. 
	 */
	public function cmb2_todo(){
	
	  $arr_todo = array(
	    'prefix'						=> '_ez_cmb2_',  // you can also pass this in via the arr_args
		'post_types'					=> array( 'page', 'post' ),	
		'localization_domain'			=> 'TODO',
		'priority'						=> 10,
		);
		
	  return $arr_todo;
	}
	
/**
 * ================ THIS IS WHERE YOUR MAGIC HAPPENS =========================================
 */

	/**
	 * The metabox "meta control center". 
	 *
	 * Note the 'active' arg for simple on/off of a given meta box. You can also switch the 
	 * methods for 'meta' and 'fields'. This ideally make it easier to reuse meta boxes and 
	 * fields from a library and not recode all the time.
	 *
	 * Finally you can also define the prefix here. This prefix will overide any others. 
	 * That said, in theory you could make this prefix blank ('') and define the prefix 
	 * on a field by field basis when you define the fields. If ya want. I guess :)
	 *
	 * = = = IMPORTANT = = =
	 * The keys here (e.g., 'metabox_1') must be unqiue across *all* CMB2 setup definitions. If you
	 * structure your code so different CMB2 setups are in not all in one mega blob, this is good to know.
	 */
	public function metabox_active(){
	
	  $arr_metabox_active = array(
		
		'metabox_1' => array(
		  'active'	=> true,
		  'meta'	=> 'metabox_1_meta',
		  'fields'	=> 'metabox_1_fields',
		  'prefix'	=> '_TODO_', 			// you can override the default prefix here

		  ),
		'metabox_2' => array(
		  'active'	=> true,
		  'meta'	=> 'metabox_2_meta',
		  'fields'	=> 'metabox_1_fields',
											// no prefix here, then use the "global" default
		  ),
		);
	  return  $arr_metabox_active;
	} 
	
	/**
	 * The meta for the meta box gets "decoupled" from the fields.
	 */
	public function metabox_1_meta(){
	
	  $mb1_meta = array(
	    'cmb2' => array(
		  'id'            => 'test_metabox_XX',
		  'title'         => __( 'Test Metabox XX', 'cmb2' ),
		  // X - 'object_types'  => array( 'page', 'post' ), // Post type
		  'post_types'  	=> $this->_arr_post_types, // NEW & IMPROVED - this will be mapped to CMB2's 'object_types'
		  'context'       => 'normal',
		  'priority'      => 'high',
		  // X - 'show_names'    => true, // Show field names on the left
		  'layout'		=> 'col_two', 	// NEW & IMPROVED - this will be mapped to CMB2's 'show_names'
		  'cmb_styles' 	=> true, 		// false to disable the CMB stylesheet
		  // X - 'fields'	=> array(),		// NEW & IMPROVED - we don't need this here any more
		  ),
		);
	  return $mb1_meta;
	}
	
	public function metabox_2_meta(){
	
	  $mb2_meta = array(
	    'cmb2' => array(
		  'id'            => 'test_metabox_2',
		  'title'         => __( 'Test Metabox 2', 'cmb2' ),
		  'post_types'  	=> $this->_arr_post_types,
		  'context'       => 'normal',
		  'priority'      => 'high',
		  'layout'		=> 'col_one', 	// NEW & IMPROVED - this will be mapped to CMB2's 'show_names'		  
		  'cmb_styles' 	=> true, 		// false to disable the CMB stylesheet
		  ),
		);
	  return $mb2_meta;
	}
	
	/**
	 * Aside for creating a slightly smoother and more logically structured CMB2 setup 
	 * workflow, it is now possible to build a library of fields / groups of fields and 
	 * then be able to reuse them "on demand." 
	 *
	 * Yes, in theory this is also possible with plain ol' CMB2. But that mindset is baked
	 * into this classes. Validation is also part of the fun & games now. 
	 */
	public function metabox_1_fields(){
	
	  $mb1_fields = array(
	  
	    'mb1_f2' => array(
		  'active' => true, 	// NEW - not found in (nor actually used by) CMB2
		  'cmb2'	=> array(	// NEW (KINDA) - the cmb2 stuff gets its own spot. 
		    'label' => __( 'Test Text', 'cmb2' ),
			'desc' => __( 'field description (optional)', 'cmb2' ),
			'name'   => 'about_test_text',
			'type' => 'text',
			),
		  ),
	  
	    'mb1_g1'	=> array(
		  'active'		=> true,
		  'cmb2'			=> array(
		    'id'          => 'repeat_group',
			'type'        => 'group',
			'description' => __( 'Generates reusable form entries', 'cmb2' ),
			'options'     => array(
			  'group_title'   => __( 'Entry {#}', 'cmb2' ), // {#} gets replaced by row number
			  'add_button'    => __( 'Add Another Entry', 'cmb2' ),
			  'remove_button' => __( 'Remove Entry', 'cmb2' ),
			  'sortable'      => true, // beta
			  ),
			'fields' => array(
			// Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
			  'mb1_f1' => array(
			    'active'	=> true,   // NEW - not found in (nor actually used by) CMB2
				'cmb2'	=> array(  // NEW - cmb2-centric details get their own key
				  
				    // X - 'name'       => __( 'Test Text', 'cmb2' ),
				    'label'       => __( 'Test Text', 'cmb2' ),  // NEW & IMPROVED - this will be mapped to CMB2's 'name'
				    'desc'       => __( 'field description (optional)', 'cmb2' ),
				    // X - 'id'         => $prefix . 'test_text',
				    'name'         => 'test_text', // NEW & IMPROVED - this will be mapped to CMB2's 'id' + prefix is also added during cmb2_setup_fields()
				    'type'       => 'text',
				    //'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
				    // 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
				    // 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback paramete
				    // 'on_front'        => false, // Optionally designate a field to wp-admin only
				    // 'repeatable'      => true,
				    ),
				/**
				 * TODO - "integration" with the WPezClasses validation
				 */
				'validation' =>array(    // NEW & IMPROVED - not found in (nor actually used by) CMB2
				  'method_2'	=> array(
				    'active'	 	=> true,
					'object'		=> 'one',
					'method'		=> 'required',
					'arr_args'		=> array(),		// even if there are no args you must pass an empty array()
					),
				  ),
				),
		      ),
			),
		  ),
		);

	  $str_this_method = __FUNCTION__;
	  $str_this_method = $str_this_method . '_ezfilter';
	  if (method_exists($this, $str_this_method)){
	    $mb1_fields = $this->$str_this_method($mb1_fields);
	  }
	  
	  return $mb1_fields;
	}
	
	/**
	 * TODO - add a proper WP filter?
	 */
	public function metabox_1_fields_ezfilter($arr_args = array()){
	
	  return $arr_args;
	}
/**
 * ================ THAT'S IT - YOUR MAGIC COMPLETE =========================================
 */	
	
	/**
	 *
	 */
	public function cmb2_meta_boxes_filter($arr_metaboxes){
	
	  $arr_the_magic = $this->cmb2_setup_do();

	  $arr_metaboxes = WPezHelpers::ez_array_merge(array($arr_the_magic, $arr_metaboxes));
	  return $arr_metaboxes;
	}

	/**
	 * Take the ez CMB2 setup definitions and reworks them into CMB2 compatible.
	 */
	public function cmb2_setup_do(){

        $str_prefix = trim($this->_str_prefix);
	
	  $arr_return_cmb2_setup = array();
	  foreach ($this->metabox_active() as $str_key => $arr_args_active){
	  
	    $arr_this_mb = WPezHelpers::ez_array_merge( array($this->metabox_active_defaults(), $arr_args_active ));
	    if ($arr_this_mb['active'] === true && method_exists($this, $arr_this_mb['meta'] ) && method_exists($this,$arr_this_mb['fields'] ) ){
	   
	      $arr_meta = $this->$arr_this_mb['meta']();
		  // the meta is wrapped in an array('cmb2' => ...) in case other non-cmb2 keys need to be added in the future
		  $arr_meta = $arr_meta['cmb2'];
		  
		  // we want some fields! now!!
		  $arr_fields = $this->$arr_this_mb['fields']();
		  
		  // map the ez stuff to the CMB2
		  if ( isset($arr_meta['post_types']) ){
		    $arr_meta['object_types'] = $arr_meta['post_types'];
		    unset($arr_meta['post_types']);
		  }
		  if ( isset($arr_meta['layout']) ){
		    $arr_meta['show_names'] = $this->layout_map($arr_meta['layout']);
		    unset($arr_meta['layout']);
		  }
	
		  // boss. the fields. the fields.
		  $arr_meta_fields = $this->cmb2_setup_fields($arr_this_mb);
		  // do we have some fields
		  if ( ! empty ($arr_meta_fields) ){
		  	$arr_meta['fields'] = $arr_meta_fields;
			$arr_return_cmb2_setup[$str_prefix.$str_key] = $arr_meta;
		  }
	    } 
	  }
	  return $arr_return_cmb2_setup;
	}

	/**
	 * take the ez fields and reworks them into what CMB2 expects
	 */
	protected function cmb2_setup_fields($arr_this_mb = array()){

        $str_prefix = trim($this->_str_prefix);
	
	  $arr_fields = $this->$arr_this_mb['fields']();
	  
	  $arr_cmb2_fields_return = array();
	  foreach ($arr_fields as $str_key => $arr_args_field ){
	    // active?
		if ( isset($arr_args_field['active']) && $arr_args_field['active'] === true ){
		
		  // how bout some cmb2 stuff?
		  if ( isset($arr_args_field['cmb2']) ){
		     $arr_args_cmb2 = $arr_args_field['cmb2'];
		  } else {
		    // no cmb2? next!
		    continue;
		  }
		  
		  // what type of field? 
		  if ( isset($arr_args_cmb2['type']) && $arr_args_cmb2['type'] == 'group' ){
		  
		    // set the prefix
		    $arr_args_cmb2['id'] = $arr_this_mb['prefix'] . $arr_args_cmb2['id'];
		    if ( isset($arr_args_cmb2['fields']) ){
			
			  // rework the fields for CMB2
			  $arr_cmb2_group_fields = array();
			  foreach ( $arr_args_cmb2['fields'] as $str_key_2 => $arr_args_group_field ){
			 
				if ( isset($arr_args_group_field['active']) && $arr_args_group_field['active'] === true ){
				  // map ez to cmb2
				  if ( isset($arr_args_group_field['cmb2']['name']) ){	  
				    $arr_args_group_field['cmb2']['id'] = $arr_args_group_field['cmb2']['name'];
					unset($arr_args_group_field['cmb2']['name']);
				  }
				  // TODO - what if there's no 'name'? continue? 
				  
				  // map
				  if ( isset($arr_args_group_field['cmb2']['label']) ){
				    $arr_args_group_field['cmb2']['name'] = $arr_args_group_field['cmb2']['label'];
					unset($arr_args_group_field['cmb2']['label']);
				  }
				  // TODO - what if there's no 'label'? continue?
				  
				  $arr_cmb2_group_fields[] = $arr_args_group_field['cmb2'];
				}
			  }
			  // stick it on the queue
			  $arr_args_cmb2['fields'] = $arr_cmb2_group_fields;
			  $arr_cmb2_fields_return[] = $arr_args_cmb2;
			}

		  } else {
		    // NOT group
			// map
			if ( isset($arr_args_cmb2['name']) ){
			  $arr_args_cmb2['id'] = $arr_this_mb['prefix'] . $arr_args_cmb2['name'];
			  unset($arr_args_cmb2['name']);
		    }
			// map
			if ( isset($arr_args_cmb2['label']) ){
			  $arr_args_cmb2['name'] = $arr_args_cmb2['label'];
			  unset($arr_args_cmb2['label']);
		    }
			// stick in on the queue
		    $arr_cmb2_fields_return[] = $arr_args_cmb2;
		  }
		}
	  }
	  // all cleaned up and ready to send back
	  return $arr_cmb2_fields_return;
	}
	
	/**
	 * Maps the ez 'layout' value to something CMB2 'show_names' expects. 
	 */
	protected function layout_map($str_layout = 'col_two'){
	
	  $arr_layout_map = array(
	    'col_one'	=> false,
		'col_two'	=> true,
	  );
	  
	  $str_return = true;
	  if ( isset($arr_layout_map[$str_layout]) ){
	    $str_return = $arr_layout_map[$str_layout];
	  }
	  return $str_return;
	}
	
	/**
	 * Simple post-array_merge fallbacks, and thus isset() isn't really necessary.
	 */
	public function metabox_active_defaults(){
	
	  $arr_metabox_active_defaults = array(
		'active'	=> false,
		'meta'		=> false,
		'fields'	=> false,
		'prefix'	=> $this->_str_prefix,
	  );
	  return $arr_metabox_active_defaults;
	}
  }
} 

/**
 * And this is how you instantiate: 
 *
 * $obj_instantiate = Class_WP_ezClasses_TODO-FOLDER_TODO-Product_#::ez_new();
 */