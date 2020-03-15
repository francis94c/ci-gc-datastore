# ci-gc-datastore

[![Google Cloud DataStore](https://res.cloudinary.com/francis94c/image/upload/c_scale,w_150/v1584279600/1200px-Google-Cloud-Datastore-Logo.svg.png)](https://cloud.google.com/datastore)

A Google Cloud DataStore Client Library for Code Igniter

This library enables you CRUD (Create, Read, Update & Delete) Entities on Google Cloud DataStore.

### Installation ###

Download and Install Splint from https://splint.cynobit.com/downloads/splint and run the below from the root of your Code Igniter project.
```bash
splint install francis94c/ci-gc-datastore
```

### Usage (Tutorial) ###

You need to download your Google Cloud Platform Credentials for a Service Account with access to the Google Cloud DataStore API and save it in the config folder `application/config/google-services.json`.

Remember to Git-Ignore the file if your project is Versioned using Git for obvious security reasons.

You also need to create a `gcd.php` file in the `application/config` folder, containing the below.

```php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['gcd'] = [
  'project_id' => '<project id>'
];
```

Git-Ignore this file as well as it contains a sensitive information, The Project ID for which the library will make Google Cloud DataStore requests in context to.

Then load the library anywhere (e.g a Controller), as below (Recommended).
```php
// Load Package
$this->load->package('cynobit/ci-gc-datastore');
```

If no Exceptions are thrown, everything's in order ;-).

#### Create an Entity and Save to Google Cloud DataStore ####

Creating an entity is easy. Entities always require a key.

A key is used to identify an entity in Google Cloud DataStore.

In some cases (Creating a new Entity) the key need not be complete.

Below is an Entity whose kind is `Country`, and has a couple of properties.

Notice they support method chaining.

```php
// Use Helper
$key = gcd_key('Country'); // Kind = 'Country'
$entity = gcd_entity($key);

$entity->setStringProperty('name', 'Nigeria')
  ->setStringProperty('continent', 'Africa')
  ->setIntegerProperty('number_of_states', 36);

// OR initialize the Classes Directly, They are spl_autoloaded.
$key = new GCDataStoreKey('Country');
$entity = new GCDataStoreEntity($key);

$entity->setStringProperty('name', 'Nigeria')
  ->setStringProperty('continent', 'Africa')
  ->setIntegerProperty('number_of_states', 36);
```
__Note:__ We merely created an entity but haven't saved or commited our new Entity to Google Cloud DataStore.

Changes to Google Cloud DataStore (GCD) are mostly done using Commits that contain mutations (the actual changes to the DataStore).

To save to GCD, we first create a commit, and then add mutations to it.

Mutations then contain our Entity or Entities.

Mutations additionally contain the type of change to make in GCD. e.g INSERT, UPDATE, UPSERT, & DELETE.

```php
$key = gcd_key('Country'); // Kind = 'Country'
$entity = gcd_entity($key);

$entity->setStringProperty('name', 'Nigeria')
  ->setStringProperty('continent', 'Africa')
  ->setStringProperty('code', 'NG')
  ->setStringProperty('currency', 'NGN')
  ->setIntegerProperty('number_of_states', 36);

$commit = gcd_commit(GCDataStoreCommit::MODE_NON_TRANSACTIONAL);
$commit->addMutation(
  gcd_mutation(GCDataStoreMutation::INSERT)->setEntity($entity) // INSERT Operation
);


$this->gcd->commit($commit); // Save to GCD.

// Or With the Objects Directly

$key = new GCDataStoreKey('Country');
$entity = new GCDataStoreEntity($key);

$entity->setStringProperty('name', 'Nigeria')
  ->setStringProperty('continent', 'Africa')
  ->setStringProperty('code', 'NG')
  ->setStringProperty('currency', 'NGN')
  ->setIntegerProperty('number_of_states', 36);

$commit = new GCDataStoreCommit(GCDataStoreCommit::MODE_NON_TRANSACTIONAL);
$muatation = new GCDataStoreMutation(GCDataStoreMutation::INSERT);
$mutation->setEntity($entity);
$commit->addMutation($mutation);

$this->gcd->commit($commit); // Save to GCD.
```

You can call the `$commit's` `addMutation` function as many times as required, to add multiple mutations
