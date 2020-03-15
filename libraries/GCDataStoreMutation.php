<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GCDataStoreMutation
{
  /**
   * [INSERT description]
   * @var string
   */
  const INSERT = 'insert';

  /**
   * [UPDATE description]
   * @var string
   */
  const UPDATE = 'update';

  /**
   * [private description]
   * @var [type]
   */
  private $type = self::INSERT;

  /**
   * [private description]
   * @var [type]
   */
  private $entity;

  /**
   * [__construct description]
   * @date  2020-03-15
   * @param string     $type [description]
   */
  public function __construct(string $type)
  {
    $this->type = $type;
  }

  /**
   * [setEntity description]
   * @date   2020-03-15
   * @param  GCDataStoreEntity   $entity [description]
   * @return GCDataStoreMutation         [description]
   */
  public function setEntity(GCDataStoreEntity $entity):GCDataStoreMutation
  {
    $this->entity = $entity;
    return $this;
  }

  /**
   * [toArray description]
   * @date   2020-03-15
   * @return array      [description]
   */
  public function toArray():array
  {
    return [$this->type => $this->entity->toArray()];
  }
}
