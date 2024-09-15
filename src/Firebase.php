<?php
namespace Firebase;
use Exception;

class Firebase
{
  private $url;
  
  function __construct($url=null)
  {
    if(isset($url))
    {
      $this->url = $url;
    }
    else
    {
      throw new Exception("Database URL must be specified");
    }
  }
  
  public function grab($url, $method, $par=null)
  {
    $headers = [
      "Content-Type: application/json"
    ];

    $options = [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_TIMEOUT => 120,
      CURLOPT_HTTPHEADER => $headers
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, $options);

    if(isset($par)){
      curl_setopt($ch, CURLOPT_POSTFIELDS, $par);
    }

    $html = curl_exec($ch);
    return $html;

    if(curl_errno($ch)){
      echo "Error: ". curl_error($ch);
    }
    curl_close($ch);
  }
  
  public function insert($table, $data)
  {
    $path = $this->url."/$table.json";
    $grab = $this->grab($path, "POST", json_encode($data));
    return $grab;
  }

  public function update($table, $uniqueID, $data)
  {
    $path = $this->url."/$table/$uniqueID.json";
    $grab = $this->grab($path, "PATCH", json_encode($data));
    return $grab;
  }
  
  public function delete($table, $uniqueID)
  {
    $path = $this->url."/$table/$uniqueID.json";
    $grab = $this->grab($path, "DELETE");
    return $grab;
  }
  
  public function retrieve($dbPath, $queryKey=null, $queryType=null, $queryVal =null)
  {
    if(isset($queryType) && isset($queryKey) && isset($queryVal))
    {
      $queryVal = urlencode($queryVal);
      
      if($queryType == "EQUAL")
      {
        $pars = "orderBy=\"$queryKey\"&equalTo=\"$queryVal\"";
      }
      elseif($queryType == "LIKE")
      {
        $pars = "orderBy=\"$queryKey\"&startAt=\"$queryVal\"";
      }
    }
    
    $pars = isset($pars) ? "?$pars" : "";
    $path = $this->url."/$dbPath.json$pars";
    $grab = $this->grab($path, "GET");
    return $grab;
  }
}
