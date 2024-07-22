<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Storage;

class FirebaseService
{
  protected $storage;

  public function __construct()
  {
    $serviceAccount = ServiceAccount::fromJsonFile(config('firebase.credentials'));
    $firebase = (new Factory)->withServiceAccount($serviceAccount);
    $this->storage = $firebase->createStorage();
  }

  public function uploadFile($filePath, $fileName)
  {
    $bucket = $this->storage->getBucket();
    $file = fopen($filePath, 'r');
    $bucket->upload($file, [
      'name' => $fileName
    ]);

    return $this->getFileUrl($fileName);
  }

  public function getFileUrl($fileName)
  {
    $bucket = $this->storage->getBucket();
    $object = $bucket->object($fileName);

    return $object->signedUrl(
      new \DateTime('1 year'), // URL expiration
      ['version' => 'v4']
    );
  }
}
