<?php

class AmazonSDK {
    // Your Access Key ID, as taken from the Your Account page
    private $access_key_id = "";

    // Your Secret Key corresponding to the above ID, as taken from the Your Account page
    private $secret_key = "";

    // The region you are interested in
    private $endpoint = "webservices.amazon.com";

    private $uri = "/onca/xml";
    private $service = "AWSECommerceService";
	// Your amazon associate tag
    private $associate_tag = "";
	// ItemSearch, ItemLookup, etc.
    public $operation = "ItemSearch";
    public $search_index = "All";
    public $keywords = "";
    public $response_group = "Large,PromotionSummary";
    private $signed_url = '';
    private $default_params = array();

    public function __construct($params=array()) {
        $this->default_params = array(
            "Service" => $this->service,
            "Operation" => $this->operation,
            "AWSAccessKeyId" => $this->access_key_id,
            "AssociateTag" => $this->associate_tag,
            "SearchIndex" => $this->search_index,
            "Keywords" => $this->keywords,
            "ResponseGroup" => $this->response_group,
            "Availability"  =>"Available",
            //When SearchIndex equals All, Sort cannot be present
            // "Sort" => "salesrank",
        );
        if ($params && is_array($params)) {
            foreach ($params as $key => $value) {
                // 如果有重复的key，用传入的替换
                $this->default_params[$key] = $value;
            }
        }

        print_r($this->default_params);
    }
    
    public function get_signed_url() {
        // Set current timestamp if not set
        if (!isset($this->default_params["Timestamp"])) {
            $this->default_params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }

        // Sort the parameters by key
        ksort($this->default_params);

        $pairs = array();

        foreach ($this->default_params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }

        // Generate the canonical query
        $canonical_query_string = join("&", $pairs);

        // Generate the string to be signed
        $string_to_sign = "GET\n".$this->endpoint."\n".$this->uri."\n".$canonical_query_string;

        // Generate the signature required by the Product Advertising API
        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $this->secret_key, true));

        // Generate the signed URL
        $request_url = 'https://'.$this->endpoint.$this->uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

        echo "Signed URL: ".$request_url."\n";
        return $request_url;
    }
    
}