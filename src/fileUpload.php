<?php 

namespace W2xi\FileUpload;

class FileUpload extends SplFileObject
{
    /**
     * get the path to the file
     * @var string
     */
    protected $filename;

    protected $allowedExtensions = [
        'png', 'jpg', 'jpeg', 'gif'
    ];
    /**
     * error message
     * @var string
     */
    protected $errorMessage = '';

    public function __construct(string $filename, string $open_mode = 'r')
    {
        parent::__construct($filename, $open_mode);
        $this->filename = $this->getRealPath() ?: $this->getPathname();
    }

    public function save()
    {

    }

    public function checkExtension()
    {

    }

    /**
     * 检查上传文件错误码
     * @param $errorCode
     * @return bool
     */
    public function checkErrorCode($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_OK:
                // pass
                break;
            case UPLOAD_ERR_INI_SIZE:
                $errorMessage = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $errorMessage = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $errorMessage = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $errorMessage = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $errorMessage = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $errorMessage = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $errorMessage = "File upload stopped by extension";
                break;
            default:
                $errorMessage = "Unknown upload error";
                break;
        }

        if ( isset($errorMessage) ){
            $this->errorMessage = $errorMessage;
            return false;
        }

        return true;
    }
}