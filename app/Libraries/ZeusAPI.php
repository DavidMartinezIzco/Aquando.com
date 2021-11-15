<?php

error_reporting(E_ALL);

define('methodEncryption', 'aes-256-cbc');

function ConvertFromJson($myTime)
{
    return DateTime::createFromFormat("d-m-Y;H:i:s.u", $myTime);
}

function ConvertToJson($myTime)
{
    return substr($myTime->format("d-m-Y;H:i:s.u"), 0, 23);
}

class DateTimeAPI extends \DateTime implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return ConvertToJson($this);
    }
}



class LastKnownValues
{
    public $lastConnectionTime; // String with Date. Format (d-m-Y;H:i:s.u)
    public $RSSI;               // Integer
    public $PowerSupply;        // String
    public $values;             // Dictionary of string, float.
    
    function __construct() {

        $params = func_get_args();
        $num_params = func_num_args();

        switch($num_params)
        {
            case 0:
                call_user_func_array(array($this,"__constructWithoutParams"),$params);
                break;
            case 4:
                call_user_func_array(array($this,"__constructWithParams"),$params);
                break;
            default:
                throw new Exception("Constructor: number of parameters.");
                break;
        }            
    }
    
    private function __constructWithoutParams() {
        $this->lastConnectionTime = null;
        $this->RSSI = null;
        $this->PowerSupply = "";
        $this->values = null;
    }

    private function __constructWithParams($lastConnectionTime, $RSSI, $PowerSupply, $values) {
        $this->lastConnectionTime = $lastConnectionTime;
        $this->RSSI = $RSSI;
        $this->PowerSupply = $PowerSupply;
        $this->values = $values;
    }

}

class StationProperties
{
    public $Model;          // string
    public $Serial;         // string
    public $Latitude;       // string
    public $Longitude;      // string
    public $Group;          // string
    public $Reference1;     // string
    public $Reference2;     // string
    public $ChannelsNames;  // Array string
    public $ChannelsUnits;   // Array string
    public $ChannelsViews;   // Dictionary string, Array(of string)

    function __construct() {

        $params = func_get_args();
        $num_params = func_num_args();

        switch($num_params)
        {
            case 0:
                call_user_func_array(array($this,"__constructWithoutParams"),$params);
                break;
            case 10:
                call_user_func_array(array($this,"__constructWithParams"),$params);
                break;
            default:
                throw new Exception("Constructor: number of parameters.");
                break;
        }            
    }

    private function __constructWithoutParams()
    {
        $this->Model = "";          
        $this->Serial = "";         
        $this->Latitude = "";       
        $this->Longitude = "";      
        $this->Group = "";          
        $this->Reference1 = "";     
        $this->Reference2 = "";     
        $this->ChannelsNames = null;  
        $this->ChannelsUnits = null;   
        $this->ChannelsViews = null;   
    }

    private function __constructWithParams($Model, $Serial, $Latitude, $Longitude, $Group, $Reference1, $Reference2, $ChannelsNames, $ChannelsUnits, $ChannelsViews)
    {
        $this->Model = $Model;          
        $this->Serial = $Serial;         
        $this->Latitude = $Latitude;       
        $this->Longitude = $Longitude;      
        $this->Group = $Group;          
        $this->Reference1 = $Reference1;     
        $this->Reference2 = $Reference2;     
        $this->ChannelsNames = $ChannelsNames;  
        $this->ChannelsUnits = $ChannelsUnits;   
        $this->ChannelsViews = $ChannelsViews;   
    }
}

class Historical
{
    public $DateOfRecord;
    public $Reason;
    public $Value;
    public $ChannelID;

    function __construct() {

        $params = func_get_args();
        $num_params = func_num_args();

        switch($num_params)
        {
            case 0:
                call_user_func_array(array($this,"__constructWithoutParams"),$params);
                break;
            case 4:
                call_user_func_array(array($this,"__constructWithParams"),$params);
                break;
            default:
                throw new Exception("Constructor: number of parameters.");
                break;
        }            
    }   
    
    private function __constructWithoutParams() {
        $this->DateOfRecord = null;
        $this->Reason = "";
        $this->Value = null;
        $this->ChannelID = null;
    }
    
    private function __constructWithParams($DateOfRecord, $ChannelID, $Reason, $Value) {
        $this->DateOfRecord = $DateOfRecord;
        $this->ChannelID = $ChannelID;
        $this->Reason = $Reason;
        $this->Value = $Value;
    }
}

class Alarms
{
    public $StationID;
    public $DateOfRecord;
    public $Reason;
    public $ChannelID;
    public $AlarmText;
    public $RawValue;

    function __construct() {

        $params = func_get_args();
        $num_params = func_num_args();

        switch($num_params)
        {
            case 0:
                call_user_func_array(array($this,"__constructWithoutParams"),$params);
                break;
            case 6:
                call_user_func_array(array($this,"__constructWithParams"),$params);
                break;
            default:
                throw new Exception("Constructor: number of parameters.");
                break;
        }            
    } 
    
    private function __constructWithoutParams() {
        $this->StationID = "";
        $this->DateOfRecord = null;
        $this->Reason = null;
        $this->ChannelID = null;
        $this->AlarmText = "";
        $this->RawValue = "";
    }
    
    private function __constructWithParams($StationID, $DateOfRecord, $reason, $ChannelID, $AlarmText, $RawValue) {
        $this->StationID = $StationID;
        $this->DateOfRecord = $DateOfRecord;
        $this->Reason = $reason;
        $this->ChannelID = $ChannelID;
        $this->AlarmText = $AlarmText;
        $this->RawValue = $RawValue;
    }
}

class KeyValue
{
    public $Key;
    public $Value;

    function __construct() {

        $params = func_get_args();
        $num_params = func_num_args();

        switch($num_params)
        {
            case 0:
                call_user_func_array(array($this,"__constructWithoutParams"),$params);
                break;
            case 2:
                call_user_func_array(array($this,"__constructWithParams"),$params);
                break;
            default:
                throw new Exception("Constructor: number of parameters.");
                break;
        }            
    } 

    private function __constructWithoutParams() {
        $this->Key = "";
        $this->Value = "";
    }
    
    private function __constructWithParams($Key, $Value) {
        $this->Key = $Key;
        $this->Value = $Value;
    }
}

class ProgrammableRelayPeriod
{
    public $Weekdays;
    public $ActivationTime;
    public $Duration;

    function __construct() {

        $params = func_get_args();
        $num_params = func_num_args();

        switch($num_params)
        {
            case 0:
                call_user_func_array(array($this,"__constructWithoutParams"),$params);
                break;
            case 3:
                call_user_func_array(array($this,"__constructWithParams"),$params);
                break;
            default:
                throw new Exception("Constructor: number of parameters.");
                break;
        }            
    } 

    private function __constructWithoutParams() {
        $this->Weekdays = array();
        $this->ActivationTime = "00:00";
        $this->Duration = 0;
    }
    
    private function __constructWithParams($Weekdays, $ActivationTime, $Duration) {
        $this->Weekdays = $Weekdays;
        $this->ActivationTime = $ActivationTime->format("H:i");
        $this->Duration = $Duration;
    }    
}


class HTTPResponse
{
    public $httpVersion;
    public $statusCode;
    public $reason;
    public $headers;
    public $payload; 
}


class Client 
{
    
    public $IP = "";
    public $Port = null;
    public $AuthAccount = "";
    public $Password = "";
    public $AuthPass = "";
    private $AuthToken = "";
    private $Created = false;
    private $SSL = null;
    private $ZeusAPILastError = "";
    private $APIVersion = "v1";

    function __construct($IP, $Port, $SSL, $AuthAccount, $Password, $AuthPass) {

        if (is_null($IP) || strlen(trim($IP)) == 0 || is_null($Port) || strlen(trim($Port)) == 0 || is_null($AuthAccount) || strlen(trim($AuthAccount)) == 0 || is_null($AuthPass) || strlen(trim($AuthPass)) == 0)
        {
            $this->ZeusAPILastError = "The input parameters are incorrect.";
            return;
            //throw new Exception("The input parameters are incorrect.");
        }

        $this->IP = $IP;
        $this->Port = $Port;
        $this->AuthAccount = $AuthAccount;
        $this->AuthPass = base64_decode($AuthPass);
        $this->SSL = $SSL;

        if (is_null($Password) || strlen(trim($Password)) == 0)
            $this->Password = "fJFYD42CMLEEKsUe";
        else
            $this->Password = $Password;

        if ($this->RequestAuthenticationToken() == false)
        {
            $this->IP = "";
            $this->Port = null;
            $this->AuthAccount = "";
            $this->Password = "";
            $this->AuthPass = "";
            $this->AuthToken = "";
            $this->SSL = null;
            $this->Created = false;
            return;
            //throw new Exception("Request Authentication incorrect.");
            
        }
        else
            $this->Created = true;
    }

    function IsCreated()
    {
        return $this->Created;
    }

    function GetZeusApiLastError()
    {
        return $this->ZeusAPILastError;
    }

    public function Stations()
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        $result = null;
        $proccessResponse = $this->CreateSendReceive("GET", "stations?status=all&Accept-Encoding=gzip", null, true, false, true);

        if(is_null($proccessResponse) == true)
            return null;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                {
                    $this->ZeusAPILastError = "Incorrect data received.";
                    return null;
                }
                else
                {
                    $result = json_decode($proccessResponse->payload, true);
                    $this->ZeusAPILastError = "";
                }
            }
        }

        return $result;
    }

    public function OnlineStation()
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        $result = null;
        $proccessResponse = $this->CreateSendReceive("GET", "stations?status=online&Accept-Encoding=gzip", null, true, false, true);

        if(is_null($proccessResponse) == true)
            return null;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                {
                    $this->ZeusAPILastError = "Incorrect data received.";
                    return null;
                }
                else
                {
                    $result = json_decode($proccessResponse->payload, true);
                    $this->ZeusAPILastError = "";
                }
            }
        }

        return $result;
    }

    // Ojo el valor devuelto es un Array de una clase estandar stdClass que es igual que nuestro Key,Value object, asiq nos lo ahorramos y se queda con ese por defecto.
    public function PendingMessages()
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        $result = null;
        $proccessResponse = $this->CreateSendReceive("GET", "stations/pending-messages?Accept-Encoding=gzip", null, true, false, true);

        if(is_null($proccessResponse) == true)
            return null;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                {
                    $this->ZeusAPILastError = "Incorrect data received.";
                    return null;
                }
                else
                {
                    $result = json_decode($proccessResponse->payload);
                    $this->ZeusAPILastError = "";
                }
            }
        }

        return $result;
    }

    public function GetStationProperties($stationID)
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        if ($this->IsNullOrWhiteSpace($stationID))
        {
            $this->ZeusAPILastError = "Incorrect input values: stationID.";
            return null; 
        }

        $result = null;
        $proccessResponse = $this->CreateSendReceive("GET", "stations/" . urlencode($stationID) . "/properties?Accept-Encoding=gzip", null, true, false, true);

        if(is_null($proccessResponse) == true)
            return null;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                {
                    $this->ZeusAPILastError = "Incorrect data received.";
                    return null;
                }
                else
                {
                    $result = json_decode($proccessResponse->payload);
                    $this->ZeusAPILastError = "";
                }
            }
        }

        return $result;
    }


    public function GetAllStationProperties()
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            
            return null;
        }

        $result = null;
        $proccessResponse = $this->CreateSendReceive("GET", "stations/properties?Accept-Encoding=gzip", null, true, false, true);

        if(is_null($proccessResponse) == true){
            echo "Object Client not created.";
            return null;
        }
            
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                {
                    echo "Object Client not created.";
            
                    $this->ZeusAPILastError = "Incorrect data received.";
                    return null;
                }
                else
                {
                    $result = json_decode($proccessResponse->payload);
                    $this->ZeusAPILastError = "";
                }
            }
        }

        return $result;
    }

    public function GetStationExtendedProperties($stationID)
    {
        if ($this->Created == false)
        {
            echo "error.";
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        if ($this->IsNullOrWhiteSpace($stationID))
        {
            echo "error.";
            $this->ZeusAPILastError = "Incorrect input values: stationID.";
            return null; 
        }

        $result = null;
        $proccessResponse = $this->CreateSendReceive("GET", "stations/" . urlencode($stationID) . "/extendedproperties?Accept-Encoding=gzip", null, true, false, true);

        if(is_null($proccessResponse) == true)
            return null;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                {
                    $this->ZeusAPILastError = "Incorrect data received.";
                    return null;
                }
                else
                {
                    
                    $result = json_decode($proccessResponse->payload, true);
                    $this->ZeusAPILastError = "";
                }
            }
        }

        return $result;
    }

    public function GetAllStationExtendedProperties()
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        $result = null;
        $proccessResponse = $this->CreateSendReceive("GET", "stations/extendedproperties?Accept-Encoding=gzip", null, true, false, true);

        if(is_null($proccessResponse) == true)
            return null;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                {
                    $this->ZeusAPILastError = "Incorrect data received.";
                    return null;
                }
                else
                {
                    $result = json_decode($proccessResponse->payload);
                    $this->ZeusAPILastError = "";
                }
            }
        }

        return $result;
    }

    // Las fechas deben ser del tipo DateTime.
    public function GetHistorical($stationID, $startDate, $endDate, $channels)
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        if ($this->IsNullOrWhiteSpace($stationID))
        {
            $this->ZeusAPILastError = "Incorrect input values: stationID.";
            return null; 
        }

        if (is_null($channels) || count($channels) == 0)
        {
            $this->ZeusAPILastError = "Incorrect input values: Unspecified channels.";
            return null;             
        }


        $channelView = "CUSTOM";
        $customChannelsQuery = "";

        foreach($channels as $c)
        {
            $customChannelsQuery = $customChannelsQuery . strval($c) . ",";
        }

        $customChannelsQuery = rtrim($customChannelsQuery,",");

        $correctURI = false;
        $result = null;
        $uri = "";

        try
        {
            $uri = "historical/" . urlencode($stationID) . "?Accept-Encoding=gzip&startDate=" . $startDate->format('d-m-Y;H:i:s') . "&endDate=" . $endDate->format('d-m-Y;H:i:s') . "&channelView=";
        }catch(Exception $e) {
            $this->ZeusAPILastError = "Incorrect input values: Date.";
            return null; 
        }


        if ($this->IsNullOrWhiteSpace($channelView) == false)
        {
            if ($channelView == "CUSTOM")
            {
                if($this->IsNullOrWhiteSpace($customChannelsQuery) == false)
                {
                    $uri = $uri . "CUSTOM&channels=" . $customChannelsQuery;
                    $correctURI = true;
                }
                else
                    $correctURI = false;
            }
            else
            {
                $uri = $uri . $channelView;
                $correctURI = true;
            }
        }
        else
            $correctURI = false;

        

        if($correctURI == true)
        {
            $proccessResponse = $this->CreateSendReceive("GET", $uri, null, true, false, true);

            if(is_null($proccessResponse) == true)
                return null;
            else
            {
                if ($proccessResponse->statusCode == "200")
                {
                    if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                    {
                        $this->ZeusAPILastError = "Incorrect data received.";
                        return null;
                    }
                    else
                    {
                        $result = json_decode($proccessResponse->payload);
                        $this->ZeusAPILastError = "";
                    }
                }
            }
        }
        else
        {
            $this->ZeusAPILastError = "Incorrect URI data.";
            return null;             
        }

        return $result;
    }



    public function GetAlarm($startDate, $endDate, $sinceThisRawValue = "")
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        $result = null;
        $correctURI = false;
        $uri = "alarms?Accept-Encoding=gzip&";

        if ($this->IsNullOrWhiteSpace($sinceThisRawValue) == true)
        {
            try
            {
                $uri = $uri . "startDate=" . $startDate->format('d-m-Y;H:i:s') . "&endDate=" . $endDate->format('d-m-Y;H:i:s');
                $correctURI = true;
            }catch(Exception $e) {
                $correctURI = false;
            }
        }
        else
        {
            $uri = $uri . "rawAlarm=" . $sinceThisRawValue;
            $correctURI = true;
        }

        if($correctURI == true)
        {
            $proccessResponse = $this->CreateSendReceive("GET", $uri, null, true, false, true);

            if(is_null($proccessResponse) == true)
                return null;
            else
            {
                if ($proccessResponse->statusCode == "200")
                {
                    if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                    {
                        $this->ZeusAPILastError = "Incorrect data received.";
                        return null;
                    }
                    else
                    {
                        $result = json_decode($proccessResponse->payload);
                        $this->ZeusAPILastError = "";
                    }
                }
            }
        }
        else
        {
            $this->ZeusAPILastError = "Incorrect URI data.";
            return null;   
        }

        return $result;
    }


    public function GetLastKnownValues($stationID)
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        if ($this->IsNullOrWhiteSpace($stationID))
        {
            $this->ZeusAPILastError = "Incorrect input values: stationID.";
            return null; 
        }

        $result = null;
        $proccessResponse = $this->CreateSendReceive("GET", "stations/" . urlencode($stationID) . "/lastknownvalues?Accept-Encoding=gzip", null, true, false, true);

        if(is_null($proccessResponse) == true)
            return null;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                {
                    $this->ZeusAPILastError = "Incorrect data received.";
                    return null;
                }
                else
                {
                    $result = json_decode($proccessResponse->payload);
                    $myArray = array();
                    foreach ($result->values as $key => $value) {
                        $myArray[strval($key)] = $value;
                    }
                    $result->values = $myArray;
                    $this->ZeusAPILastError = "";
                }
            }
        }

        return $result;
    }

    public function GetAllStationLastKnownValues()
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        $result = null;
        $proccessResponse = $this->CreateSendReceive("GET", "stations/lastknownvalues?Accept-Encoding=gzip", null, true, false, true);

        if(is_null($proccessResponse) == true)
            return null;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                {
                    $this->ZeusAPILastError = "Incorrect data received.";
                    return null;
                }
                else
                {
                    $result = json_decode($proccessResponse->payload);
                    
                    foreach($result as $element)
                    {
                        
                        $myArray = array();
                        foreach ($element->values as $key => $value) {
                            $myArray[strval($key)] = $value;
                        }
                        $element->values = $myArray;
                    }
                    
                    $this->ZeusAPILastError = "";
                }
            }
        }

        return $result;
    }


    public function GetConfigurationRelay($stationID, $output)
    {
        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return null;
        }

        if ($this->IsNullOrWhiteSpace($stationID))
        {
            $this->ZeusAPILastError = "Incorrect input values: stationID.";
            return null; 
        }

        $result = null;
        $proccessResponse = $this->CreateSendReceive("GET", "stations/" . urlencode($stationID) . "/configurationRelay?Accept-Encoding=gzip&output=" . strval($output), null, true, false, true);

        if(is_null($proccessResponse) == true)
            return null;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($proccessResponse->payload) == true)
                {
                    $this->ZeusAPILastError = "Incorrect data received.";
                    return null;
                }
                else
                {
                    $result = json_decode($proccessResponse->payload);
                    $this->ZeusAPILastError = "";
                }
            }
        }

        return $result;
    }


    public function SetConfigurationRelay($destination, $output, $myProgrammableRelayPeriod)
    {

        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return false;
        }

        if ($this->IsNullOrWhiteSpace($destination) == true || is_null($myProgrammableRelayPeriod) == true)
        {
            $this->ZeusAPILastError = "Incorrect input: destination|myProgrammableRelayPeriod empty.";
            return false; 
        }    

        if (count($myProgrammableRelayPeriod) > 4)
        {
             $this->ZeusAPILastError = "Incorrect input values: myProgrammableRelayPeriod elements.";
            return false;            
        }

        // Construimos el comando.
        $messageText = "PR" . strval($output) . "=";
        
        foreach ($myProgrammableRelayPeriod as &$element) {
            $messageText = $messageText . $element->ActivationTime . "-" . strval($element->Duration) . "@";
            foreach($element->Weekdays as $myDay) {
                if(preg_match('^[LMXJVSD]+$^', $myDay))
                {
                    $messageText = $messageText . $myDay;
                }
                else
                {
                    $this->ZeusAPILastError = "Incorrect input values: myProgrammableRelayPeriod.Weekdays format.";
                    return false;  
                }
            }
            $messageText = $messageText . " ";
        }


        for ($i=count($myProgrammableRelayPeriod);$i<4;$i++)
        {
            $messageText = $messageText . "00:00-0@ ";
        }

        $messageText = rtrim($messageText);

        $jsonDataString = json_encode(new KeyValue($destination, $messageText));

        if ($jsonDataString === false)
        {
            $this->ZeusAPILastError = "Incorrect input values: destination | messageText.";
            return false; 
        }

        $result = false;
        $uri =  "messages/message?Content-Encoding=gzip&bySMS=FALSE&dontWaitForResponse=TRUE";
        
        $proccessResponse = $this->CreateSendReceive("POST", $uri, $jsonDataString, true, true, true);

        if(is_null($proccessResponse) == true)
            $result = false;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                $this->ZeusAPILastError = "";
                $result = true; 
            }
            else
                $result = false; 
        }
 
        return $result;
    }


    public function SendMessage($destination, $messageText, $sendBySMS = false)
    {

        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return false;
        }

        if ($this->IsNullOrWhiteSpace($destination) == true || $this->IsNullOrWhiteSpace($messageText) == true)
        {
            $this->ZeusAPILastError = "Incorrect input values: String.";
            return false; 
        }    

        $jsonDataString = json_encode(new KeyValue($destination, $messageText));

        if ($jsonDataString === false)
        {
            $this->ZeusAPILastError = "Incorrect input values: destination | messageText.";
            return false; 
        }

        $result = false;
        $sendBySMS = ($sendBySMS) ? "TRUE" : "FALSE";
        $uri =  "messages/message?Content-Encoding=gzip&bySMS=" . $sendBySMS . "&dontWaitForResponse=FALSE";
        
        $proccessResponse = $this->CreateSendReceive("POST", $uri, $jsonDataString, true, true, true);

        if(is_null($proccessResponse) == true)
            $result = false;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                $this->ZeusAPILastError = "";
                $result = true; 
            }
            else
                $result = false; 
        }
 
        return $result;
    }


    public function SetHistorical($stationID, $historicalValues)
    {

        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return false;
        }

        if ($this->IsNullOrWhiteSpace($stationID) == true || is_null($historicalValues) == true || count($historicalValues) == 0)
        {
            $this->ZeusAPILastError = "Incorrect input values.";
            return false; 
        }    

        $jsonDataString = json_encode($historicalValues);

        if ($jsonDataString === false)
        {
            $this->ZeusAPILastError = "Incorrect input values: historicalValues.";
            return false; 
        }

        $result = false;        
        $proccessResponse = $this->CreateSendReceive("POST", "historical/" . urlencode($stationID) . "?Content-Encoding=gzip", $jsonDataString, true, true, true);

        if(is_null($proccessResponse) == true)
            $result = false;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                $this->ZeusAPILastError = "";
                $result = true; 
            }
            else
                $result = false; 
        }
 
        return $result;
    }


    public function SetAlarms($alarmValues)
    {

        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return false;
        }

        if (is_null($alarmValues) == true || count($alarmValues) == 0)
        {
            $this->ZeusAPILastError = "Incorrect input values: alarmValues.";
            return false; 
        }    

        $jsonDataString = json_encode($alarmValues);

        if ($jsonDataString === false)
        {
            $this->ZeusAPILastError = "Incorrect input values: alarmValues.";
            return false; 
        }

        $result = false;        
        $proccessResponse = $this->CreateSendReceive("POST", "alarms?Content-Encoding=gzip&rawAlarm=FALSE", $jsonDataString, true, true, true);

        if(is_null($proccessResponse) == true)
            $result = false;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                $this->ZeusAPILastError = "";
                $result = true; 
            }
            else
                $result = false; 
        }
 
        return $result;
    }    


    public function SetLastKnownValues($stationID, $values)
    {

        if ($this->Created == false)
        {
            $this->ZeusAPILastError = "Object Client not created.";
            return false;
        }

        if ($this->IsNullOrWhiteSpace($stationID) == true || is_null($values) == true)
        {
            $this->ZeusAPILastError = "Incorrect input values.";
            return false; 
        }    


        $jsonDataString = "{";
        $jsonDataString = $jsonDataString . "\"lastConnectionTime\":". "\"" . ConvertToJson($values->lastConnectionTime) . "\",";
        $jsonDataString = $jsonDataString . "\"RSSI\":". $values->RSSI . ",";
        $jsonDataString = $jsonDataString . "\"PowerSupply\":". "\"" . $values->PowerSupply . "\",";
        $jsonDataString = $jsonDataString . "\"values\":";
        $jsonDataString = $jsonDataString . "{";
        foreach($values->values as $key => $value)
            $jsonDataString = $jsonDataString . "\"" . $key . "\":". $value . ",";
        $jsonDataString  = rtrim($jsonDataString ,',');
        $jsonDataString = $jsonDataString . "}";
        $jsonDataString = $jsonDataString . "}";

        $result = false;        
        $proccessResponse = $this->CreateSendReceive("POST", "stations/" . urlencode($stationID) . "/lastknownvalues?Content-Encoding=gzip", $jsonDataString, true, true, true);

        if(is_null($proccessResponse) == true)
            $result = false;
        else
        {
            if ($proccessResponse->statusCode == "200")
            {
                $this->ZeusAPILastError = "";
                $result = true; 
            }
            else
                $result = false; 
        }
 
        return $result;
    }  

    private function RequestAuthenticationToken()
    {
        $result = false;

        $encryptedPass = $this->EncryptString_Aes($this->Password);
        if ($encryptedPass === false)
        {
            $this->ZeusAPILastError = "The data could not be encrypted.";
            $this->AuthToken = "";
            return false; 
        }

        $proccessResponse = new HTTPResponse();
        $proccessResponse = $this->CreateSendReceive("GET", "accounts/" . urlencode($this->AuthAccount) . "?authentication=" .  urlencode($encryptedPass), $this->Password, false, false, false, 1, true);

        if (is_null($proccessResponse) == false)
        {
            if ($proccessResponse->statusCode == "200")
            {
                if($this->IsNullOrWhiteSpace($proccessResponse->payload) == false)
                {
                    $this->AuthToken = $proccessResponse->payload;
                    $result = true;
                }
                else
                {
                    $this->AuthToken = "";
                    $result = false;
                }
            }
            else
            {
                $this->AuthToken = "";
                $result = false; 
            }
        }
        else
        {
            $this->AuthToken = "";
            $result = false;
        }

        return $result;
    }


    private function ProccessRespond($httpRespond, $decrypt, $decompress = false)
    {
        $myResponse = new HTTPResponse();

        if ($this->IsNullOrWhiteSpace($httpRespond))
            return null;
        else
        {
            $responseSplitLines = preg_split('/\r\n|\r|\n/', $httpRespond);
            if(is_null($responseSplitLines))
                return null;
            else
            {
                if(count($responseSplitLines) < 2)
                    return null;
            }


            // Procesamos el status-line
            $statusLineSplit = explode(" ", $responseSplitLines[0]);
            
            if(count($statusLineSplit) < 3)
                return null;

            $myResponse->httpVersion = $statusLineSplit[0];
            $myResponse->statusCode = $statusLineSplit[1];


            $reason = "";
            for ($j = 2; $j < count($statusLineSplit); $j++) {
                $reason = $reason . $statusLineSplit[$j] . " ";
            }
            $myResponse->reason = substr($reason, 0, strlen($reason) - 1);


            $myResponse->headers = array();
            $contador = 0;

            $size = count($responseSplitLines);
            for ($i = 1; $i < $size; $i++) {
                $line = $responseSplitLines[$i];
                if($line == "")
                    break;

                $myResponse->headers[substr($line, 0, strpos($line, ":"))] = substr($line, strpos($line, ":") + 2);
                $contador = $contador + 1;
            }



            $payload = "";
            if (($contador + 2) < count($responseSplitLines))
                $payload = $responseSplitLines[$contador + 2];
            else
                $payload = "";




            if ($myResponse->statusCode == "200")
            {
                if ($this->IsNullOrWhiteSpace($payload) == false)
                {
                    // Decrypt
                    $decryptPayload = "";
                    if ($decrypt == true)
                    {
                       $decryptPayload = $this->DecryptString_Aes($payload);
                        if ($decryptPayload === false)
                            return null; 
                    }
                    else
                    {
                        $decryptPayload = $payload;
                    }

                    //GZIP
                    if ($decompress == true)
                    {
                        $myResponse->payload = $this->DecompressString($decryptPayload);
                    }
                    else
                    {
                        $myResponse->payload = $decryptPayload;
                    }
                }
            }
            else
            {
                $myResponse->payload = $payload;
            }
        }
        return $myResponse;
    }

    private function CreateSendReceive($method, $resource, $payload, $AcceptEncoding, $ContentEncoding, $OptionDecrypt, $Attempts = 3, $AuthRequest = false)
    {

        $myResponse = new HTTPResponse();
        $intentos = 0;

        do
        {
            $myRequest = $this->CreateRequest($method, $resource, $payload, $AcceptEncoding, $ContentEncoding);
            if ($this->IsNullOrWhiteSpace($myRequest) == true)
            {
                $this->ZeusAPILastError = "Creating request failure.";
                return null;
            }


            if($this->SSL === true)
            {
                $stream_context = stream_context_create([ 'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false]]);

                $socket = stream_socket_client("ssl://" . $this->IP . ":" . $this->Port, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $stream_context);
                if ($socket === false)  
                {
                    $this->ZeusAPILastError = "Unable to connect with ZeusServer.";
                    echo " conexion no establecida  ";
                    return null;
                }

                // Enviamos por el socket el paquete creado.
                $myRequestBuffer = utf8_encode($myRequest);
                if(fwrite($socket, $myRequestBuffer, strlen($myRequestBuffer)) === FALSE)
                {
                    fclose($socket); 
                    $this->ZeusAPILastError = "Socket writting failure.";
                    return null;            
                }

                $myResponseBuffer = "";
                $myResponseString = "";
                do
                {
                    $myResponseBuffer = fread($socket, 2048);
                    $myResponseString = $myResponseString . $myResponseBuffer;
                }while($this->IsNullOrWhiteSpace($myResponseBuffer) == false);

                fclose($socket);
            }
            else
            {
                // Conectamos con el servidor.
                $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                if ($socket === false)  
                {
                    $this->ZeusAPILastError = "Unable to create socket.";
                    return null;
                }       
                    
                $result = socket_connect($socket, $this->IP, $this->Port);
                if ($result === false)
                {
                    $this->ZeusAPILastError = "Unable to connect with ZeusServer.";
                    echo " conexion no establecida  ";
                    return null;
                }

                // Enviamos por el socket el paquete creado.
                $myRequestBuffer = utf8_encode($myRequest);
                if(socket_write($socket, $myRequestBuffer, strlen($myRequestBuffer)) === FALSE)
                {
                    socket_close($socket); 
                    $this->ZeusAPILastError = "Socket writting failure.";
                    return null;            
                }

                $myResponseBuffer = "";
                $myResponseString = "";
                do
                {
                    $myResponseBuffer = socket_read($socket, 2048);
                    
                    $myResponseString = $myResponseString . $myResponseBuffer;
                }while($this->IsNullOrWhiteSpace($myResponseBuffer) == false);

                socket_close($socket);                
            }



            $myResponse = $this->ProccessRespond($myResponseString, $OptionDecrypt, $AcceptEncoding);

            if (is_null($myResponse) == true)
            {
                $intentos = $Attempts;
                $this->ZeusAPILastError = "Proccess ZeusServer Respond failure.";
                
                return null;
            }
            else
            {
               switch ($myResponse->statusCode) {
                    case "200":
                        $intentos = $Attempts;
                        $this->ZeusAPILastError = "";
                        break;

                    case "401":
                        $this->ZeusAPILastError = "Request Authentication failure.";
                        if ($AuthRequest == false)
                        {
                            $intentos = $intentos + 1;
                            $myResponse = null;
                            $this->RequestAuthenticationToken();
                        }
                        else
                        {
                            $myResponse = null;
                            $intentos = $Attempts;
                        }
                        break;
                    
                    case "400":
                        $this->ZeusAPILastError = $this->ZeusAPILastError . "Reason: Bad Request.";
                        if ($this->IsNullOrWhiteSpace($myResponse->payload) == false)
                            $this->ZeusAPILastError = $this->ZeusAPILastError . " -- Server message: " . $myResponse->payload;
                        $intentos = $Attempts;
                        $myResponse = null;
                        break;

                    case "403":
                        $this->ZeusAPILastError = $this->ZeusAPILastError . "Reason: permissions.";
                        if ($this->IsNullOrWhiteSpace($myResponse->payload) == false)
                            $this->ZeusAPILastError = $this->ZeusAPILastError . " -- Server message: " . $myResponse->payload;
                        $intentos = $Attempts;
                        $myResponse = null;
                        break;

                    case "404":
                        $this->ZeusAPILastError = $this->ZeusAPILastError . "Reason: The requested resource was not found.";
                        if ($this->IsNullOrWhiteSpace($myResponse->payload) == false)
                            $this->ZeusAPILastError = $this->ZeusAPILastError . " -- Server message: " . $myResponse->payload;
                        $intentos = $Attempts;
                        $myResponse = null;
                        break;

                    case "405":
                        $this->ZeusAPILastError = $this->ZeusAPILastError . "Reason: The method is not allowed.";
                        if ($this->IsNullOrWhiteSpace($myResponse->payload) == false)
                            $this->ZeusAPILastError = $this->ZeusAPILastError . " -- Server message: " . $myResponse->payload;
                        $intentos = $Attempts;
                        $myResponse = null;
                        break;

                    case "500":
                        $this->ZeusAPILastError = $this->ZeusAPILastError . "Reason: An internal error occurred on the server.";
                        if ($this->IsNullOrWhiteSpace($myResponse->payload) == false)
                            $this->ZeusAPILastError = $this->ZeusAPILastError . " -- Server message: " . $myResponse->payload;
                        $intentos = $Attempts;
                        $myResponse = null;
                        break;

                    default:
                        $this->ZeusAPILastError = $this->ZeusAPILastError . "Reason: Unknown error.";
                        if ($this->IsNullOrWhiteSpace($myResponse->payload) == false)
                            $this->ZeusAPILastError = $this->ZeusAPILastError . " -- Server message: " . $myResponse->payload;
                        $intentos = $Attempts;
                        $myResponse = null;
                        break;
                } 
            }
        }while($intentos < $Attempts);

        return $myResponse;

    }    

    private function CreateRequest($httpMethod, $URIResource, $payload, $AcceptEncoding, $ContentEncoding)
    {

        $request = "";

        if ($this->IsNullOrWhiteSpace($httpMethod) == false && $this->IsNullOrWhiteSpace($URIResource) == false)
        {
            if ($httpMethod === "GET" || $httpMethod === "POST" || $httpMethod === "DELETE" || $httpMethod === "PUT") 
            {
                $requestLine = "";
                $headers = "";
                $myPayload = "";

                $requestLine = $httpMethod . " /api/" .  $this->APIVersion . "/" . $URIResource . " HTTP/1.1";
                $headers = "User-Agent: ZeusClient ApiREST" .  "\r\n";
                $headers = $headers . "Date: " . gmdate('D, d M Y H:i:s T') .  "\r\n";

                if ($this->IsNullOrWhiteSpace($this->AuthToken) == false)
                    $headers = $headers . "Authorization: Bearer " . $this->AuthToken . "\r\n";

                /*
                if ($AcceptEncoding == true)
                    $headers = $headers . "Accept-Encoding: gzip" . "\r\n";

                if ($this->IsNullOrWhiteSpace($payload) == false && $ContentEncoding == true)
                    $headers = $headers . "Content-Encoding: gzip" . "\r\n";
                */

                $request = $requestLine .  "\r\n" . $headers ."\r\n";

                if ($this->IsNullOrWhiteSpace($payload) == false)
                {
                    $newPayload = "";
                    if ($ContentEncoding == true)
                        $newPayload = $this->CompressString($payload);
                    else
                        $newPayload = $payload;

                    $encryptedPayload = $this->EncryptString_Aes($newPayload);
                    if ($encryptedPayload === false)
                        $request = "";
                    else
                        $request = $request . $encryptedPayload;
                }
            }
        }
        return $request;
    }

    private function CompressString($text)
    {
        return base64_encode(gzencode(utf8_encode($text)));
    }


    private function DecompressString($compressedText)
    {
        return utf8_decode(gzdecode(base64_decode($compressedText)));
    }

    private function IsNullOrWhiteSpace($value)
    {
        if (is_null($value) || strlen(trim($value)) == 0)
            return true;
        else
            return false;
    }

    private function Base64Encode($plainText)
    {
        return base64_encode(utf8_encode($plainText));
    }

    private function Base64Decode($base64EncodeData)
    {
        return utf8_decode(base64_decode($base64EncodeData));
    }


    private function EncryptString_Aes($payloadToEncrypt)
    {

        // Si es vacio o nulo.
        if(is_null($payloadToEncrypt) || strlen(trim($payloadToEncrypt)) == 0)
            return false;

       // Generamos el vector iv de forma criptosegura para añadir aleatoriedad al mensaje.
        $iv = openssl_random_pseudo_bytes(16, $isCryptoStrong);

        // Si no es seguro devolvemos false
        if(!$isCryptoStrong)
            return false;

        // Encriptamos el mensaje.
        $msg = openssl_encrypt($payloadToEncrypt, methodEncryption, $this->AuthPass, OPENSSL_RAW_DATA, $iv);
        // Si ha habido algún error en la encryptación.
        if ($msg === false)
            return false;

        $encrypted = base64_encode($msg);
        // Si ha habido algún error en la codificación.
        if($encrypted === false)
            return false;

        // Añadimos al principo los 16 bytes del vector iv para poder desencriptar el mensaje (tener en cuenta que esto va en base64 por lo que son mas de 16 bytes).
        $myIV = base64_encode($iv);
        // Error en la codificación.
        if ($myIV === false)
            return false;

        $iv_with_encrypted = $myIV . $encrypted;

        // Devolvemos.
        return $iv_with_encrypted;
    }

    private function DecryptString_Aes($encryptedString)
    {
        // Si es vacio o nulo.
        if(is_null($encryptedString) || strlen(trim($encryptedString)) == 0)
            return FALSE;

        // Si el payload es menor que 25 significa que no contiene el vector IV y aparte el contenido.
        if (strlen($encryptedString) < 25)
            return FALSE;

        // Obtenemos el vector iv del mensaje.
        $iv = base64_decode(substr($encryptedString, 0, 24));
        if ($iv === FALSE)
            return FALSE;

        //echo "IV: " . substr($encryptedString, 0, 24) . "<br>";

        // Obtenemos el resto del mensaje que es lo q hay q desencriptar.
        $encrypted = base64_decode(substr($encryptedString, 24));
        if($encrypted === FALSE)
            return FALSE;

        //echo "Payload: " . substr($encryptedString, 24) . "<br>";

        // Desencriptamos.
        $decrypted = openssl_decrypt($encrypted, methodEncryption, $this->AuthPass, OPENSSL_RAW_DATA, $iv);
        if($decrypted === FALSE)
            return FALSE;

        // Devolvemos.
        return $decrypted;
    }    

}



?>
