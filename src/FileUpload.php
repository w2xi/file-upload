<?php 

// namespace W2xi\FileUpload;

class FileUpload extends \SplFileObject
{
    /**
     * the path to the file
     * @var string
     */
    protected string $filename;
    
    /**
     * allowed extensions
     * @var array
     */
    protected array $allowedExtensions = [
        '7z',
        'csv',
        'doc',
        'docx',
        'gif',
        'gz',
        'ical',
        'ics',
        'jpeg',
        'jpg',
        'json',
        'log',
        'm3u',
        'm4a',
        'm4v',
        'mkv',
        'mp3',
        'mp4',
        'ods',
        'odt',
        'ogg',
        'pdf',
        'png',
        'pps',
        'ppt',
        'pptx',
        'svg',
        'txt',
        'vcard',
        'vcf',
        'webm',
        'webp',
        'xls',
        'xlsx',
        'xml',
        'xspf',
        'zip'
    ];

    /**
     * uploaded file info
     * @var array
     */
    protected array $uploadedFileInfo;

    protected array $validate;

    protected $fileInfo; 
    
    /**
     * error message
     * @var string
     */
    protected string $errorMessage = '';

    public function __construct(string $filename, string $open_mode = 'r')
    {
        parent::__construct($filename, $open_mode);
        $this->filename = $this->getRealPath() ?: $this->getPathname();
    }

    public function move($targetDirectory)
    {
        if ( !$this->isValidUploadedFile() ){
            $this->errorMessage = 'illegal uploaded file';
            return false;
        }

        if ( !$this->check() ){
            return false;
        }

        $targetDirectory = rtrim($targetDirectory, DS) . DS;
        $saveName = date('Ymd') . DS . md5(microtime(true));
        $filename = $targetDirectory . $saveName;

        if ( !is_dir($targetDirectory) ){
            $this->errorMessage = 'invalid uplaoded directory';
            return false;
        }

        mkdir($targetDirectory, 0755, true);
  
        if ( !move_uploaded_file($this->filename, $filename) ){
            $this->errorMessage = 'uploaded file failed';
            return false;
        }

        $file = new self($filename);
        $file->setSaveName($saveName)->setUploadedFileInfo($this->fileInfo);

        return $file;
    }

    public function setUploadedFileInfo($uploadedFileInfo): self
    {
        $this->uploadedFileInfo = $uploadedFileInfo;

        return $this;
    }

    public function validate(array $rule = []): self
    {
        $this->validate = $rule;

        return $this;
    }

    public function getError(): string
    {
        return $this->errorMessage;
    }

    public function check(array $rule = []): bool
    {
        $rule = $rule ?: $this->validate;

        if ( !$this->checkErrorCode($this->uploadedFileInfo['error']) ){
            return false;
        }

        if ( !$this->checkExtension($rule['ext']) ){
            $this->errorMessage = 'invalid file extension';
            return false;
        }

        if ( !$this->checkSize($rule['size']) ){
            $this->errorMessage = 'invalid file size';
            return false;
        }

        return true;
    }

    public function getFileInfo($name = '')
    {
        return isset($this->fileInfo[$name]) ? $this->fileInfo[$name] : $this->fileInfo;
    }

    public function isValidUploadedFile()
    {
        return is_uploaded_file($this->filename);
    }

    public function checkExtension($allowedExtension)
    {
        if ( isset($allowedExtension) && is_string($allowedExtension) ){
            $allowedExtension = explode(',', $allowedExtension);
        }
        $allowedExtensions = $allowedExtension ?? $this->allowedExtensions;

        $extension = strtolower(pathinfo($this->getFileInfo('name'), PATHINFO_EXTENSION));

        echo $extension;
        die;

        return in_array($extension, $allowedExtensions, true);
    }

    public function checkSize($size)
    {
        if ( isset($size) && $this->getSize() > $size ){
            return false;
        }

        return true;
    }


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