<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GCDataStoreEntity
{
  /**
   * [private description]
   * @var [type]
   */
  private $key;

  /**
   * [private description]
   * @var [type]
   */
  private $properties = [];

  /**
   * [__construct description]
   * @date  2020-03-15
   * @param GCDataStoreKey $key [description]
   */
  public function __construct(GCDataStoreKey $key)
  {
    $this->key = $key;
  }

  /**
   * [setStringProperty description]
   * @date   2020-03-15
   * @param  string            $key   [description]
   * @param  string            $value [description]
   * @return GCDataStoreEntity        [description]
   */
  public function setStringProperty(string $key, string $value):GCDataStoreEntity
  {
    $this->properties[$key] = [
      'stringValue' => $value
    ];
    return $this;
  }

  /**
   * [setIntegerProperty description]
   * @date   2020-03-15
   * @param  string            $key   [description]
   * @param  int               $value [description]
   * @return GCDataStoreEntity        [description]
   */
  public function setIntegerProperty(string $key, int $value):GCDataStoreEntity
  {
    $this->properties[$key] = [
      'integerValue' => $value
    ];
    return $this;
  }

  /**
   * [setNullProperty description]
   * @date   2020-03-15
   * @param  string            $key [description]
   * @return GCDataStoreEntity      [description]
   */
  public function setNullProperty(string $key):GCDataStoreEntity
  {
    $this->properties[$key] = [
      'nullValue' => null
    ];
    return $this;
  }

  /**
   * [setEntityProperty description]
   * @date   2020-03-15
   * @param  string            $key   [description]
   * @param  GCDataStoreEntity $value [description]
   * @return GCDataStoreEntity        [description]
   */
  public function setEntityProperty(string $key, GCDataStoreEntity $value):GCDataStoreEntity
  {
    $this->properties[$key] = [
      'entityValue' => $value
    ];
    return $this;
  }

  /**
   * [toArray description]
   * @date   2020-03-15
   * @return array      [description]
   */
  public function toArray():array
  {
    return [
      'key'        => $this->key->toArray(),
      'properties' => $this->properties
    ];
  }
}
