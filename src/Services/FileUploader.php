<?php
/**
 * Created by PhpStorm.
 * User: wilder10
 * Date: 16/07/18
 * Time: 15:57
 */
namespace App\Services;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class FileUploader
{
    private $targetDirectory;
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }
    /**
     * @param UploadedFile|File $file
     * @return string
     */
    public function upload($file)
    {
        $fileName = uniqid() . '.' . $file->guessExtension();
        $file->move(
            $this->targetDirectory,
            $fileName
        );
        return $fileName;
    }
}