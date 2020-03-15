<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GQLQuery
{
  private $queryString;
  private $allowLiterals;
  private $namedBindings = [];

  /**
   * [__construct description]
   * @date  2020-03-15
   * @param string     $queryString   [description]
   * @param [type]     $allowLiterals [description]
   */
  public function __construct(string $queryString, ?bool $allowLiterals=null)
  {
    $this->queryString = $queryString;
    if ($this->allowLiterals !== null) $this->allowLiterals = $allowLiterals;
  }

  /**
   * [bindStringValue description]
   * @date   2020-03-15
   * @param  string     $key   [description]
   * @param  string     $value [description]
   * @return GQLQuery          [description]
   */
  public function bindStringValue(string $key, string $value):GQLQuery
  {
    $this->namedBindings[$key] = ['value' => ['stringValue' => $value]];
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
      'partitionId' => [
        'projectId' => get_instance()->gcd->getProjectId()
      ],
      'gqlQuery' => [
        'queryString' => $this->queryString
      ]
    ];

    if ($this->allowLiterals !== null) $data['gqlQuery']['allowLiterals'] = $this->allowLiterals;
    if (!empty($this->namedBindings)) $data['gqlQuery']['namedBindings'] = $this->namedBindings;

    return $data;
  }

  /**
   * [query description]
   * @date   2020-03-15
   * @return [type]     [description]
   */
  public function query():?object
  {
    return get_instance()->gcd->gqlQuery($this);
  }
}
