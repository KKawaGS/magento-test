<?php

namespace GateSoftware\GateGallery\Model;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\MediaStorage\Model\File\UploaderFactory;

class ImageFile
{
    const PATH = 'gate\gallery';

    private UploaderFactory $uploaderFactory;
    private Filesystem $filesystem;
    private File $file;

    public function __construct(UploaderFactory $uploaderFactory, Filesystem $filesystem, File $file)
    {
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
        $this->file = $file;
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

        $imageData = $fileUploader->save($this->getAbsolutePath(self::PATH));
        $imageData['path'] = $this->getRelativePath() . $imageData['file'];

        return $imageData;
    }

    /**
     * @throws FileSystemException
     */
    public function delete(array $imageData): ?bool
    {
        $path = $this->getAbsolutePath() . $imageData['path'];
        if ($this->file->isExists($path)) {
            return $this->file->deleteFile($path);
        }

        return false;
    }

    /**
     * @throws FileSystemException
     */
    private function getAbsolutePath($path=''): string
    {
        $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        return $mediaDirectory->getAbsolutePath($path);
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
