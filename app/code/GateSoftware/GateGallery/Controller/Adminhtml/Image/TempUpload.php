<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Image;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;

class TempUpload extends Action
{
    protected UploaderFactory $uploaderFactory;
    protected WriteInterface $mediaDirectory;
    protected StoreManagerInterface $storeManager;

    public function __construct(
        Context               $context,
        UploaderFactory       $uploaderFactory,
        Filesystem            $filesystem,
        StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->storeManager = $storeManager;
    }

    public function execute()
    {
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $fileUploader = $this->uploaderFactory->create(['fileId' => 'image']);
            $fileUploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
            $fileUploader->setAllowRenameFiles(true);
            $fileUploader->setAllowCreateFolders(true);
            $fileUploader->setFilesDispersion(false);
            $fileUploader->validateFile();
            $result = $fileUploader->save($this->mediaDirectory->getAbsolutePath('tmp/imageUploader/images'));
            $result['url'] = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                . 'tmp/imageUploader/images/' . ltrim(str_replace('\\', '/', $result['file']), '/');

            return $jsonResult->setData($result);
        } catch (LocalizedException $e) {
            return $jsonResult->setData(['errorcode' => 0, 'error' => $e->getMessage()]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            return $jsonResult->setData(['errorcode' => 0, 'error' => __('An error occured, try later')]);
        }
    }
}
