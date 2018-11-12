<?php
/**
 * Created by Marcin.
 * Date: 12.11.2018
 * Time: 18:59
 */

namespace mrcnpdlk\Lib\Lank3;


use mrcnpdlk\Lib\Lank3\Model\SensorsStatusModel;

class Api
{
    private const XML_IX = 'xml/ix.xml';
    /**
     * @var \mrcnpdlk\Lib\Lank3\Config
     */
    private $oConfig;
    /**
     * @var \JsonMapper
     */
    private $mapper;

    public function __construct(Config $oConfig)
    {
        $this->oConfig                 = $oConfig;
        $this->mapper                  = new \JsonMapper();
        $this->mapper->bEnforceMapType = false;
    }

    /**
     * @return \mrcnpdlk\Lib\Lank3\Model\SensorsStatusModel
     * @throws \Exception
     */
    public function getRawSensorStatus(): SensorsStatusModel
    {
        try {
            /**
             * @var \SimpleXMLElement $sensorsXml
             */
            $sensorsXml = $this->callRequest(self::XML_IX);
            $mappedArr  = [];
            foreach ($sensorsXml as $sensor) {
                $mappedArr[$sensor->getName()] = (string)$sensor;
            }

            /** @noinspection PhpIncompatibleReturnTypeInspection */
            /** @noinspection PhpParamsInspection */
            return $this->mapper->map($mappedArr, new SensorsStatusModel());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string $urlSuffix
     * @param array  $data
     *
     * @return mixed
     * @throws \ErrorException
     * @throws \mrcnpdlk\Lib\Lank3\Exception
     */
    private function callRequest(string $urlSuffix, array $data = [])
    {
        $oCurl = $this->oConfig->getCurl();
        $url   = sprintf('%s/%s', $this->oConfig->getBasicUrl(), $urlSuffix);
        $oCurl->get(sprintf($url, $data));
        if ($oCurl->error) {
            throw new Exception(sprintf('%s [%s]', $oCurl->errorMessage, $url), $oCurl->errorCode);
        }

        return $oCurl->response;

    }

}
