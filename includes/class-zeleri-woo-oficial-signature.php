<?php
if ( ! class_exists( 'Zeleri_Woo_Oficial_Signature' ) ) {
	class Zeleri_Woo_Oficial_Signature {

    public function __construct() {
			
		}

    public function sortObjectKeys($object) {
      $sortedKeys = array_keys($object);
      sort($sortedKeys);
      $sortedObject = array();
      foreach ($sortedKeys as $key) {
        $sortedObject[$key] = $object[$key];
      }
      return $sortedObject;
    }
    
    public function generateSignature($input, $secret) {
      if (is_array($input)) {
        // If input is an array, use it directly
        $object = $input;
      } elseif (is_string($input)) {
        // If input is a string, parse it into an array
        $object = parseQueryString($input);
      } else {
        throw new Exception('Invalid input type. Expected array or string.');
      }
    
      $object = $this->sortObjectKeys($object);
      $message = '';
      foreach ($object as $property => $value) {
        if ($property === 'signature') continue;
        $message .= $property . json_encode($value);
      }
    
      $sha = hash_hmac('sha256', $message, $secret);
      return $sha;
    }
    
    public function getSignedObject($input, $secret) {
      if (is_array($input)) {
        // If input is an array, use it directly
        $object = $input;
      } elseif (is_string($input)) {
        // If input is a string, parse it into an array
        $object = parseQueryString($input);
      } else {
        throw new Exception('Invalid input type. Expected array or string.');
      }
    
      return array_merge($object, array('signature' => $this->generateSignature($object, $secret)));
    }
    
    public function parseQueryString($queryString) {
      parse_str($queryString, $params);
      return $params;
    }

  }

}
