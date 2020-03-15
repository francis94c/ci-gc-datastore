<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GCDataStoreKey
{
  private $partitionId = [];
  private $path = [];

  public function __construct(?string $kind=null, ?string $projectId=null)
  {
    if ($kind) $this->path['kind'] = $kind;
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
   * [setPath description]
   * @date   2020-03-15
   * @param  string         $kind [description]
   * @param  [type]         $id   [description]
   * @param  [type]         $name [description]
   * @return GCDataStoreKey       [description]
   */
  public function setPath(string $kind, ?int $id=null, ?string $name=null):GCDataStoreKey
  {
    $this->path['kind'] = $kind;
    if ($id) $this->path['id'] = $id;
    if ($name) $this->path['name'] = $name;
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
