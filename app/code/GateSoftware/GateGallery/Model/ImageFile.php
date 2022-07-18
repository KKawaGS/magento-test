<?php

namespace GateSoftware\GateGallery\Model;

use Exception;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;

class ImageFile
{
    const PATH = 'gate\gallery';
    const TEMP_PATH = 'gate\gallery\temp\\';

    private UploaderFactory $uploaderFactory;
    private Filesystem $filesystem;
    private File $file;
    private StoreManagerInterface $storeManager;

    public function __construct(UploaderFactory $uploaderFactory, Filesystem $filesystem, File $file, StoreManagerInterface $storeManager)
    {
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
        $this->file = $file;
        $this->storeManager = $storeManager;

    }

    /**
     * @throws Exception
     */
    public function save(array $imageData): array
    {
        $imageData['tmp_name'] = $imageData['path'] . '/' . $imageData['file'];

        $fileUploader = $this->uploaderFactory->create(['fileId' => $imageData]);
        $fileUploader->setAllowRenameFiles(true);
        $fileUploader->setAllowCreateFolders(true);
        $fileUploader->validateFile();

        $imageData = $fileUploader->save($this->getAbsolutePath(self::PATH));
        $imageData['path'] = $this->getRelativePath() . $imageData['file'];

        return $imageData;
    }

    public function saveTempFile(string $fieldName): array
    {
        $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);

        $fileUploader = $this->uploaderFactory->create(['fileId' => $fieldName]);
        $fileUploader->setAllowRenameFiles(true);
        $fileUploader->setAllowCreateFolders(true);
        $fileUploader->setFilesDispersion(false);
        $fileUploader->validateFile();
        $result = $fileUploader->save($mediaDirectory->getAbsolutePath(self::TEMP_PATH));
        $result['url'] = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
            . self::TEMP_PATH . ltrim(str_replace('\\', '/', $result['file']), '/');

        return $result;
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
    private function getAbsolutePath($path = ''): string
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
