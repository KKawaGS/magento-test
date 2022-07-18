<?php

namespace GateSoftware\GateGallery\Model;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;

class ImageFile
{
    const PATH = 'gate\gallery';

    private UploaderFactory $uploaderFactory;
    private Filesystem $filesystem;


    public function __construct(UploaderFactory $uploaderFactory, Filesystem $filesystem)
    {
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * @throws Exception
     */
    public function save(array $imageData): array
    {
        if (!file_exists($imageData['tmp_name'])) {
            $imageData['tmp_name'] = $imageData['path'] . '/' . $imageData['file'];
        }

        $fileUploader = $this->uploaderFactory->create(['fileId' => $imageData]);
        $fileUploader->setAllowRenameFiles(true);
        $fileUploader->setAllowCreateFolders(true);
        $fileUploader->validateFile();

        $imageData = $fileUploader->save($this->getAbsolutePath());
        $imageData['path'] = $this->getRelativePath() . $imageData['file'];

        return $imageData;
    }

    /**
     * @throws FileSystemException
     */
    private function getAbsolutePath(): string
    {
        $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        return $mediaDirectory->getAbsolutePath(self::PATH);
    }

    /**
     * @throws FileSystemException
     */
    private function getRelativePath(): string
    {
        $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        return $mediaDirectory->getRelativePath(self::PATH);
    }

}
