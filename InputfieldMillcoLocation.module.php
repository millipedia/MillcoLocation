<?php namespace ProcessWire;

/**
 * Inputfield for picking a lat lng from a Leaflet map.
 *
 * @author Stephen @ millipedia
 * @license Licensed under MIT
 * @link https://millipedia.com
 * 
 */

class InputfieldMillcoLocation extends InputfieldText implements Module, ConfigurableModule {

  public static function getModuleInfo() {
    return [
      'title' => 'MillcoLocation', // Module Title
      'summary' => 'Pick a lat lng from a map', // Module Summary
      'version' => '0.0.5',
      'icon' => 'map-marker',
      'requires' => ['FieldtypeMillcoLocation'],
    ];
  }

  public $def_loc;

    /**
     * 
     */
    public function __construct() {
      
      $this->set('default_marker_position', '51.572119,-0.777283'); // Marlow!
      $this->set('read_only', 1);

      parent::__construct();

  }


  /**
   * Init module
   */
  public function init() {

    parent::init();
		$conf = $this->getModuleInfo();
		$version = (int) $conf['version'];

    $this->config->styles->add($this->config->urls->InputfieldMillcoLocation . "leaflet/leaflet.css?v={$version}");
    $this->config->styles->add($this->config->urls->InputfieldMillcoLocation . "leaflet_tweaks.css?v={$version}");
		$this->config->scripts->add($this->config->urls->InputfieldMillcoLocation . "leaflet/leaflet.js?v={$version}");

    // get our config field values
    $default_marker = $this->get('default_marker_position');
    $this->def_loc=$default_marker;


    // bd($this->get('default_marker_position'));

    // // ### THIS DOESN'T WORK ... not sure why yet.
    // $is_read_only = $this->getConfigInputfields()['read_only'];

    // if($is_read_only->checked()){
    //   //bd("chceked");
    //   $this->set('read_only' , 1);
    // }else{
    //   //bd("not chceked");
    //   $this->set('read_only' , 0);
    // }

        
  }

  /**
   * Render Inputfield
   *
   * @return string
   *
   */
  public function ___render() {

    $attrStr = $this->getAttributesString();
    
    $value=$this->attr('value');

    // String to loc will return our default location
    // if we don't have a sensible value from our field.

    $loc=$this->string_to_loc($value);

    $id = uniqid();

    $markup = '<div class="MillcoLocation" id="ml_' . $id . '" >';

      $markup.= '<div style="max-width:420px;margin-bottom:2rem;z-index:1;">';     

        $markup.='<div class="uk-inline">
        <button class="uk-form-icon uk-form-icon-flip millcol_clear" uk-icon="icon: trash"></button>';
        $markup.= '<input ' . $attrStr .' readonly/>';
        $markup.= '</div>';

      $markup.= '</div>';

      // this is the div we load the map into.
      $markup.='<div class="millco_map" id="map_' . $id . '" data-map_id="' . $id . '" data-map_lat="' . $loc['lat'] . '"  data-map_lng="' . $loc['lng'] . '" style="height:280px;max-width:420px;border:1px solid #666;background-color:#aaa;"></div>';
    $markup.= '</div>';

    // let's add an address lookup field.
    $markup.='<div class="millco_address_lookup uk-form-stacked uk-margin-small-top">';
    $markup.='<label class="uk-form-label">Address lookup</label>';
    // $markup.='<input type="text" class="millcol_lookup" value="">';

    $markup.='<div class="uk-inline">
        <button class="uk-form-icon uk-form-icon-flip millcol_lookup_butt" data-map_id="' . $id . '" href="#" uk-icon="icon: search"></button>
        <input class="uk-input millcol_lookup_field" type="text" aria-label="Lookup address">
</div>';

    $markup.='</button>';

    return $markup;
  }

  // Add config fields.
  function getModuleConfigInputfields(InputfieldWrapper $inputfields) {

    // Get the defaults and $inputfields wrapper we can add to
   // $inputfields = parent::___getConfigInputfields();
   
    // Add a new Inputfield to it
    $f = $this->wire('modules')->get('InputfieldText');
    $f->attr('name', 'default_marker_position');
    // $f->attr('initValue', 'default thing');  
    $f->attr('value',$this->get('default_marker_position'));
    
    $f->label = 'Default marker position';
    $inputfields->add($f);


    $ro = $this->wire('modules')->get('InputfieldCheckbox');
    $ro->attr('name', 'read_only');
    if($this->get('read_only')){
      $ro->checked(true);
    }else{
      $ro->checked(false);
    }

    
    $ro->label = 'Readonly text box';
    $ro->notes = 'Sets whether users can update the field directly. Might be handy to let users enter whatever they like but you might want to do some extra validation. ';
    $inputfields->add($ro);
    return $inputfields;
  }

  /**
   * Check we can get something sensible out of our string.
   * Doesnt do as much validation as it should yet.
   */
  public function string_to_loc($value){

    $location=array();

    // start with out defaults

    $def_loc=$this->def_loc;

    $def_loc_array=explode(',',$def_loc);


    $location['lat']=$def_loc_array[0];
    $location['lng']=$def_loc_array[1];

    // explode our string on a comma
    $string_array=explode(',',$value);

    if(is_array($string_array) && count($string_array) > 1){
      $location['lat']=(float)$string_array[0];
      $location['lng']=(float)$string_array[1];
    }
    
    return $location;

  }

}
