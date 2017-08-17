<?php

namespace Helpers;

class Layout {
	
	protected $scripts  = array(); 
	protected $styles   = array();
	protected static $instances = array();

	/**
	 * @param string $key
	 * @return static
	 */
	public static function instance( $key = 'default' ){		

		if( isset( static::$instances[ $key ] ) ){
			return static::$instances[ $key ];
		}
		
		return static::$instances[ $key ] = new static;
	}

	public static function absPath( $path ){

		$subdir = env( 'SUBDIR' ) ? '/'.env( 'SUBDIR' ): '';
		$path = substr( $path , 0 ,1 ) != '/' ? '/'.$path : $path;
		return $subdir.$path;
	}
	/**
	 * add single page scripts
	 * @param string $script_path
	 */
	public function addScript( $script_path , $key = null  ){
		if( $key ){
			$this->scripts[ $key ] = 	$script_path;
		}else{
			$this->scripts[] = 	$script_path;
		}
	}
	
	/**
	 * add style 
	 * @param string $script_path
	 */
	public function addStyle( $style_path ){
		$this->styles[] = 	$style_path;
	}
	
	public function getScripts(){
		return $this->scripts;
	}
	
	public function getStyles(){
		return $this->styles;
	}

	public function renderScript( $path ){

		//$attribs = isset( $script['attribs'] ) && count( $script['attribs'] ) ?  $script['attribs'] : '';
		//$path = $script['path'];

		if( substr( $path , 0, 4 ) == 'http' ){
			return '<script type="text/javascript" src="'.$path.'"></script>'."\r";
		}

		$subdir = env('SUBDIR') ? '/'.env('SUBDIR'): '';
		$path = substr( $path , 0 , 1 ) == '/' ? $path : '/'.$path;

		return '<script src="'.$subdir.$path.'"></script>'."\r\n";
	}

	public function renderStyle( $path ){
		$subdir = env('SUBDIR') ? '/'.env('SUBDIR'): '';
		$path 	= substr( $path , 0 , 1 ) == '/' ? $path : '/'.$path;

		return '<link rel="stylesheet" href="'.$subdir.$path.'">'."\r";
	}

	public function renderPageScripts(){
		$html = array();
		$s_array = [];

		foreach( $this->getScripts() as $script ){
			if( in_array( $script , $s_array) ){
				continue;
			}
			$s_array[] = $script;
			$html[] = $this->renderScript( $script );
		}

		return implode( "" , $html );
	}
	
	public function renderPageStyles(){
		$html = array();
		$s_array = [];

		foreach( $this->getStyles() as $style ){
			if( in_array( $style , $s_array) ){
				continue;
			}
			$s_array[] = $style;
			$html[] = $this->renderStyle( $style );
		}

		return implode( '' , $html );
	}

	public static function placeholderImage(){
		return '/images/placeholder.jpg';
	}

	public static function loadGoogleMap()
	{
		static::instance()->addScript( 'https://maps.googleapis.com/maps/api/js?key='.env('GOOGLE_API_KEY') );
	}

	public static function loadToastr()
	{
		static::instance()->addStyle( '/plugins/toastr/toastr.min.css' );
		static::instance()->addScript( '/plugins/toastr/toastr.min.js' );
	}
	public static function loadCkeditor()
	{
		static::instance()->addScript( '/plugins/ckeditor/ckeditor.js' );
	}

	public static function loadFileupload( $file_type = null ){
		static::instance()->addScript( '/plugins/fileupload/js/vendor/jquery.ui.widget.js' );
		static::instance()->addScript( '/plugins/fileupload/js/jquery.iframe-transport.js' );
		static::instance()->addScript( '/plugins/fileupload/js/jquery.fileupload.js' );
	}

	public static function loadBlockUI()
	{
		static::instance()->addScript( '/plugins/blockui/blockui.js' );
	}

	public static function loadVue( $version = '2.1.9')
	{
		if( env( 'APP_ENV' ) == 'production' ){
			static::instance()->addScript( '/plugins/vue/vue-'.$version.'.min.js' , 'vue');
		}else{
			static::instance()->addScript( '/plugins/vue/vue-'.$version.'.js' , 'vue' );
		}
	}

	public static function loadlodash( $version = '4.17.4')
	{
		static::instance()->addScript( '/plugins/lodash/lodash-'.$version.'.js' , 'vue' );
	}

	public static function loadTablr()
	{
		static::instance()->addStyle( '/plugins/tablr/tablr.css' );
		static::instance()->addScript( '/plugins/tablr/tablrn.js' );
	}

	public static function loadNotify()
	{
		static::instance()->addScript( '/plugins/notify/notify.js' , 'notify');
	}

	public static function loadBootSelect()
	{
		static::instance()->addStyle( '/plugins/bootselect/bootselect.css' );
		static::instance()->addScript( '/plugins/bootselect/bootselect.js' );
	}

	public static function loadJqueryUI()
	{
		//static::instance()->addStyle( '/css/jquery-ui/smoothness/jquery-ui.min.css' );
		//static::instance()->addScript( '/js/core/libraries/jquery_ui/jquery-ui.min.js' );
		static::instance()->addStyle( '/plugins/jquery-ui/jquery-ui.min.css' );
		static::instance()->addScript( '/plugins/jquery-ui/jquery-ui.min.js' );
	}

	public static function loadPagination( $design = 1 )
	{
		view()->addLocation( __DIR__.'/Views/' );
		$design = 'pagination'.$design;
		return view( 'Pagination.'.$design );
	}

}