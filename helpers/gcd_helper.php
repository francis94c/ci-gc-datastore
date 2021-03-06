<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('gcd_commit')) {
  function gcd_commit(string $mode):GCDataStoreCommit
  {
    return new GCDataStoreCommit($mode);
  }
}

if (!function_exists('gcd_mutation')) {
  function gcd_mutation(string $type):GCDataStoreMutation
  {
    return new GCDataStoreMutation($type);
  }
}

if (!function_exists('gcd_key')) {
  function gcd_key(?string $kind=null, ?int $id=null):GCDataStoreKey
  {
    return new GCDataStoreKey($kind, $id);
  }
}

if (!function_exists('gcd_entity')) {
  function gcd_entity(GCDataStoreKey $key):GCDataStoreEntity
  {
    return new GCDataStoreEntity($key);
  }
}

if (!function_exists('gql_query')) {
  function gql_query(string $queryString, ?bool $allowLiterals=null):GQLQuery
  {
    return new GQLQuery($queryString, $allowLiterals);
  }
}
