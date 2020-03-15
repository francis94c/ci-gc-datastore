<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GCDataStoreKey
{
  private $partitionId = [];
  private $path = [];

  public function __construct(?string $kind=null, ?int $id=null, ?string $projectId=null)
  {
    if ($kind || $id) {
      $path = [];

      if ($kind) $path['kind'] = $kind;
      if ($id) $path['id'] = $id;

      $this->path[] = $path;
    }

    $this->partitionId['projectId'] = $projectId ?? get_instance()->gcd->getProjectId();
  }

  /**
   * [setPartitionId description]
   * @date   2020-03-15
   * @param  string         $projectId   [description]
   * @param  [type]         $namespaceId [description]
   * @return GCDataStoreKey              [description]
   */
  public function setPartitionId(string $projectId, ?string $namespaceId=null):GCDataStoreKey
  {
    $this->partitionId['projectId'] = $projectId;
    if ($namespaceId) $this->partitionId['namespaceId'] = $namespaceId;
    return $this;
  }

  /**
   * [addPath description]
   * @date   2020-03-15
   * @param  string         $kind [description]
   * @param  [type]         $id   [description]
   * @param  [type]         $name [description]
   * @return GCDataStoreKey       [description]
   */
  public function addPath(string $kind, ?int $id=null, ?string $name=null):GCDataStoreKey
  {
    $path = [];
    $path['kind'] = $kind;
    if ($id) $path['id'] = $id;
    if ($name && !$id) $path['name'] = $name;
    $this->path[] = $path;
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
      'partitionId' => $this->partitionId,
      'path'        => $this->path
    ];
  }
}
