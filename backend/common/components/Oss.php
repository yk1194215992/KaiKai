<?php

namespace backend\common\components;

use OSS\OssClient;
use yii\base\Component;

/**
 * Class Oss
 * @package backend\common\components
 */
class Oss extends Component
{
    /**
     * @event Event an event that is triggered after a DB connection is established
     */
    const EVENT_AFTER_OPEN = 'afterOpen';

    /**
     * @var string accessKeyId
     */
    public $accessKeyId;

    /**
     * @var string accessKeySecret
     */
    public $accessKeySecret;

    /**
     * @var string endpoint
     */
    public $endpoint;

    /**
     * @var string bucket
     */
    public $bucket;

    /**
     * @var OssClient client
     */
    private $_client;


    /**
     * Establishes a Mongo connection.
     * It does nothing if a MongoDB connection has already been established.
     * @throws Exception if connection fails
     */
    public function open()
    {
        if ($this->_client === null) {
            try {
                $this->_client = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
            }
        }
    }

    /**
     * Initializes the DB connection.
     * This method is invoked right after the DB connection is established.
     * The default implementation triggers an [[EVENT_AFTER_OPEN]] event.
     */
    protected function initConnection()
    {
        $this->trigger(self::EVENT_AFTER_OPEN);
    }

    /**
     * Closes the OssClient.
     * It does nothing if the connection is already closed.
     */
    public function close()
    {
        if ($this->_client !== null) {

            Yii::trace('Closing OssClient', __METHOD__);

            $this->_client = null;
        }
    }

    /**
     * Get Bucket
     *
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * Set Bucket Name
     *
     * @param $bucket
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * Upload File
     *
     * @param $imgPath
     * @param $object
     *
     * @return mixed
     */
    public function uploadFile($imgPath, $object)
    {
        $this->open();

        try {
            return $this->_client->uploadFile($this->bucket, $imgPath, $object);
        } catch (OssException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
	
	/**
     * Upload File
     *
     * @param $imgPath
     * @param $object
     *
     * @return mixed
     */
    public function uploadFiles($imgPath, $objects)
    {
        $this->open();
        $filejh = false;
         try {
            foreach($imgPath as $key => $path){
                $filejh[$key] = $this->_client->uploadFile($this->bucket, $path, $objects[$key]);
            }
            return $filejh;
                } catch (OssException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
             }

       
    }
    /**
     * get_object_to_local_file
     *
     * 获取object
     * 将object下载到指定的文件
     *
     * @param OssClient $ossClient OSSClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function getObjectToLocalFile($object, $localfile)
    {
        // $object = "oss-php-sdk-test/download-test-object-name.txt";
        // $localfile = "download-test-object-name.txt";
        $this->open();

        $options = array(
            OssClient::OSS_FILE_DOWNLOAD => $localfile,
        );

        try{
            return $this->_client->getObject($this->bucket, $object, $options);
        } catch(OssException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
        }

    }

}
