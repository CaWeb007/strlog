<?
namespace Caweb\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Web\Uri;

class ORD {
    private const MEDIA_TYPE = 'application/json';
    private const TEST_URL = 'https://api-sandbox.ord.vk.com';
    private const TEST_CREATE_CREATIVE_URL = 'https://api-sandbox.ord.vk.com/v2/creative/';
    private const TEST_API_TOKEN = '66810d8140904d51a7c97c9ed43ee962';
    private const TEST_CONTRACT_EXTERNAL_ID = '71hhnfdk1r-1ha4bc3ql';
    private const MAIN_URL = 'https://api.ord.vk.com';
    private const MAIN_CREATE_CREATIVE_URL = 'https://api.ord.vk.com/v2/creative/';
    private const MAIN_API_TOKEN = 'e650822f35074c2784f33967e7daa654';
    private const MAIN_CONTRACT_EXTERNAL_ID = 'te1jd2ev9f-1ha3j7s18';
    private $httpClient, $testMode;
    private $queryBody = array();
    private $siteUrl = null;
    private $externalId = '';
    public function __construct(bool $testMode = false){
        $this->httpClient = new HttpClient();
        $this->testMode = $testMode;
        $this->setSiteUrl();
        $this->setHeaders();
    }
    private function setSiteUrl(){
        $this->siteUrl = 'https://стройлогистика.рф';
    }
    private function setHeaders(){
        if ($this->testMode){
            $host = self::TEST_URL;
            $auth = 'Bearer '.self::TEST_API_TOKEN;
        }else{
            $host = self::MAIN_URL;
            $auth = 'Bearer '.self::MAIN_API_TOKEN;
        }
        $this->httpClient->setHeader('Host', $host);
        $this->httpClient->setHeader('Authorization', $auth);
        $this->httpClient->setHeader('Content-Type', self::MEDIA_TYPE);
    }
    public function setBody(array $body){
        if ($this->testMode){
            $body['contract_external_id'] = self::TEST_CONTRACT_EXTERNAL_ID;
        }else{
            $body['contract_external_id'] = self::MAIN_CONTRACT_EXTERNAL_ID;
        }
        $body['form'] = 'banner';
        $body['target_urls'] = array($this->siteUrl);
        $body['pay_type'] = 'cpc';
        $this->queryBody = Json::encode($body);
    }
    public function setExternalId(string $externalId = ''){
        if (empty($externalId))
            $externalId = md5(time());
        $this->externalId = $externalId;
    }
    public function doQuery(){
        $result = false;
        if ($this->testMode){
            $url = self::TEST_CREATE_CREATIVE_URL.$this->externalId;
        }else{
            $url = self::MAIN_CREATE_CREATIVE_URL.$this->externalId;
        }
        $result = $this->httpClient->query(HttpClient::HTTP_PUT, $url, $this->queryBody);
        if (!$result) throw new \Exception('Something wrong');
        return $result;
    }
    public function getMarker(){
        $result = $this->httpClient->getResult();
        if (empty($result))
            throw new \Exception('Empty result');
        $result = Json::decode($result);
        $marker = $result['marker'];
        $error = $result['error'];
        if ($error) throw new \Exception($error);
        return $marker;
    }
}