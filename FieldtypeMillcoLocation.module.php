<?php namespace ProcessWire;
/**
 * MillcoLocation Fieldtype
 *
 * @author Bernhard Baumrock, 20.11.2019
 * @license Licensed under MIT
 * @link https://www.baumrock.com
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
