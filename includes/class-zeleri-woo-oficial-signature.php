<?php
if ( ! class_exists( 'Zeleri_Woo_Oficial_Signature' ) ) {

    class Zeleri_Woo_Oficial_Signature {
        private $secret;

        public function __construct($secret) {
            $this->secret = $secret;
        }

        public function generate($input) {
            $object = parseInput($input);
            $sortedObject = sortObjectKeys($object);
            $message = concatenateObjectProperties($sortedObject);

            $hmac = hash_hmac('sha256', $message, $this->secret);
            return $hmac;
        }

        public function validate($data, $signature) {
            $object = parseInput($data);
            $sortedObject = sortObjectKeys($object);
            $message = concatenateObjectProperties($sortedObject);

            $hmac = hash_hmac('sha256', $message, $this->secret);
            $expectedSignature = $hmac;

            return $signature === $expectedSignature;
        }
    }

    function sortObjectKeys($object) {
        if (!is_array($object) || empty($object)) {
            return $object;
        }

        $sortedKeys = array_keys($object);
        sort($sortedKeys);

        $sortedObject = [];
        foreach ($sortedKeys as $key) {
            $sortedObject[$key] = $object[$key];
        }

        return $sortedObject;
    }

    function concatenateObjectProperties($object) {
        $message = '';
        foreach ($object as $key => $value) {
            if ($key === 'signature') continue;
            $message .= $key . json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        return $message;
    }

    function parseInput($input) {
        if (is_array($input)) {
            return $input;
        } elseif (is_string($input)) {
            return parse_str($input, $output) ? $output : [];
        } else {
            throw new Exception("Invalid input type. Expected object or string.");
        }
    }

}
