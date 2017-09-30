<?php

/**
 * Created by PhpStorm.
 * User: cre8te
 * Date: 30/9/17
 * Time: 10:00 PM
 */
class feathers
{
  private $host = '';
  private $jwt = '';

  public function __construct($params)
  {
    if(substr($params['host'],-1)!='/'){
      $this->host = $params['host'].'/';
    } else {
      $this->host = $params['host'];
    }

    if(!empty($params['email'])){
      $result = $this->post($params['service'], [ "strategy"=>'local',"email"=>$params['email'],"password"=>$params['password'] ]);

      if(isset($result->code)){
        throw new Exception($result->code.':'.$result->message);
      } else {
        $this->jwt = $result->accessToken;
      };
    }

  }

  static function create($params = null){
    $defaults = [
        'host'=> 'http://localhost:3030',
        'service' => 'authentication',
        'email' => NULL,
        'password' => NULL
    ];
    $params = array_merge($defaults, $params);

    return new self($params);
  }

  public function headers(){
    $headers = array(
        'Content-Type: application/json',
        'Connection: Keep-Alive'
    );

    if(!empty($this->jwt)){
      $headers[] = 'Authorization: '.$this->jwt;
    }

    return $headers;
  }

  public function post($service, $data){
    $ch = curl_init($this->host.$service);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
  }

  public function patch($service, $id, $data,$query=[]){
    $queryString = '';
    foreach($query as $key => $value){
      $queryString .= '&'.$key.'='.$value;
    }

    $ch = curl_init($this->host.$service.'/'.$id.'?'.substr($queryString,1));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
  }

  public function update($service, $id, $data){
    $ch = curl_init($this->host.$service.'/'.$id);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
  }

  public function find($service, $query = []){
    $queryString = '';
    foreach($query as $key => $value){
      $queryString .= '&'.$key.'='.$value;
    }
    $ch = curl_init($this->host.$service.'?'.substr($queryString,1));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
  }

  public function get($service, $id){
    $ch = curl_init($this->host.$service.'/'.$id);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
  }
}