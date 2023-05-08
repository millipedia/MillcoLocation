<?php namespace ProcessWire;
/**
 * MillcoLocation Fieldtype
 *
 * @author Stephen @ millipedia
 * @license Licensed under MIT
 * @link https://millipedia.com
 */
class FieldtypeMillcoLocation extends FieldtypeText {

  public static function getModuleInfo() {
    return [
      'title' => 'MillcoLocation',
      'version' => '0.0.3',
      'summary' => 'Field that stores lat lng string',
      'icon' => 'map-marker',
      'installs' => ['InputfieldMillcoLocation'],
    ];
  }

  /**
   * Return the associated Inputfield
   * 
   * @param Page $page
   * @param Field $field
   * @return Inputfield
   *
   */
  public function getInputfield(Page $page, Field $field) {
    $inputField = $this->wire('modules')->get('InputfieldMillcoLocation');
    return $inputField;
  }
}
