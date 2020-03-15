<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GCDataStoreCommit
{
  /**
   * [MODE_TRANSACTIONAL description]
   * @var string
   */
  const MODE_TRANSACTIONAL = 'TRANSACTIONAL';

  /**
   * [MODE_NON_TRANSACTIONAL description]
   * @var string
   */
  const MODE_NON_TRANSACTIONAL = 'NON_TRANSACTIONAL';

  /**
   * [private description]
   * @var [type]
   */
  private $mode = self::MODE_TRANSACTIONAL;
  private $mutations = [];
  private $transaction;

  /**
   * [__construct description]
   * @date  2020-03-14
   * @param string     $mode [description]
   */
  public function __construct(string $mode)
  {
    $this->mode = $mode;
  }

  /**
   * [addMutation description]
   * @date   2020-03-15
   * @param  GCDataStoreMutation $mutation [description]
   * @return GCDataStoreCommit             [description]
   */
  public function addMutation(GCDataStoreMutation $mutation):GCDataStoreCommit
  {
    $this->mutations[] = $mutation;
    return $this;
  }

  /**
   * [setTransaction description]
   * @date   2020-03-15
   * @param  string            $transaction [description]
   * @return GCDataStoreCommit              [description]
   */
  public function setTransaction(string $transaction):GCDataStoreCommit
  {
    $this->transaction = $transaction;
    return $this;
  }

  /**
   * [toArray description]
   * @date   2020-03-15
   * @return array      [description]
   */
  public function toArray():array
  {
    $data = [
      'mode'      => $this->mode,
      'mutations' => []
    ];

    foreach ($this->mutations as $mutation) {
      $data['mutations'][] = $mutation->toArray();
    }

    return $data;
  }
}
