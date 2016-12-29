<?php
class StardomCityBase {

    function __construct() {
      $this->init();
    }

    private function init(){

      // add_filter( 'media_upload_newtab', array( 'My_Class', 'media_upload_callback' ) );  //static
      // add_filter( 'media_upload_newtab', array( $this, 'media_upload_callback' ) );  //instance
      // add_filter( 'the_title', function( $title ) { return '<strong>' . $title . '</strong>'; } ); //anonymous
    }
}
