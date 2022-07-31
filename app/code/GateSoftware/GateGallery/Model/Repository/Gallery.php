<?php

namespace GateSoftware\GateGallery\Model\Repository;

use Exception;
use GateSoftware\GateGallery\Model\GalleryFactory;
use GateSoftware\GateGallery\Model\Image;
use GateSoftware\GateGallery\Model\ImageFactory;
use GateSoftware\GateGallery\Model\ImageFile;
use GateSoftware\GateGallery\Model\ResourceModel\Gallery as GalleryResource;
use GateSoftware\GateGallery\Model\ResourceModel\Image as ImageResource;
use GateSoftware\GateGallery\Model\ResourceModel\Image\Collection;
use GateSoftware\GateGallery\Model\ResourceModel\Image\CollectionFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\FileSystemException;
use function Aws\boolean_value;

class Gallery
{
    private Collection $imageCollection;
    private GalleryFactory $galleryFactory;
    private GalleryResource $galleryResource;
    private ImageResource $imageResource;
    private ImageFactory $imageFactory;
    private ImageFile $imageFile;

    public function __construct(
        CollectionFactory $imageCollectionFactory,
        GalleryFactory $galleryFactory,
        GalleryResource $galleryResource,
        ImageResource $imageResource,
        ImageFactory $imageFactory,
        ImageFile $imageFile
    )
    {
        $this->imageCollection = $imageCollectionFactory->create();
        $this->galleryFactory = $galleryFactory;
        $this->galleryResource = $galleryResource;
        $this->imageResource = $imageResource;
        $this->imageFactory = $imageFactory;
        $this->imageFile = $imageFile;
    }

    /**
     * @throws AlreadyExistsException
     */
    public function saveGallery(array $data): \GateSoftware\GateGallery\Model\Gallery
    {
        $gallery = $this->galleryFactory->create();

        if(isset($data['id'])) {
            $this->galleryResource->load($gallery, $data['id']);
        }

        $gallery->setName($data['name']);
        $gallery->setDescription($data['description']);
        if($gallery->hasDataChanges()) {
            $this->galleryResource->save($gallery);
        }

        return $gallery;
    }

    public function getImages($galleryId): array
    {
        return $this->imageCollection
            ->addFieldToSelect('*')
            ->addFieldToFilter('gallery_id', ['in' => $galleryId])
            ->getItems();
    }

    /**
     * @throws AlreadyExistsException
     */
    private function saveImageToDb(array $imageData, $galleryId)
    {
        $image = $this->imageFactory->create();
        $image->setData([
            'name' => $imageData['name'],
            'type' => $imageData['type'],
            'size' => $imageData['size'],
            'previewType' => $imageData['previewType'],
            'path' => $imageData['path'],
            'visibility' => $imageData,
            'gallery_id' => $galleryId
        ]);

        $this->imageResource->save($image);
    }

    /**
     * @throws Exception
     */
    private function saveImageFile(array $imageData): array
    {
        return $this->imageFile->save($imageData);
    }

    /**
     * @throws AlreadyExistsException
     * @throws Exception
     */
    public function saveImage(array $imageData, $galleryId)
    {
        $image = $this->saveImageFile($imageData);
        $this->saveImageToDb($image, $galleryId);
    }

    public function saveTempImage(string $fieldName): array
    {
        return $this->imageFile->saveTempFile($fieldName);
    }

    /**
     * @throws Exception
     */
    public function deleteImageById($imageId)
    {
        $image = $this->imageFactory->create();
        $this->imageResource->load($image, $imageId);

        return $this->imageResource->delete($image);
    }

    /**
     * @throws FileSystemException
     */
    private function deleteImageFile(array $imageData)
    {
        $this->imageFile->delete($imageData);
    }

    public function deleteImage(array $imageData)
    {
        $this->deleteImageById($imageData['id']);
        $this->deleteImageFile($imageData);
    }

    /**
     * @throws Exception
     */
    public function deleteGalleryById($galleryId): \GateSoftware\GateGallery\Model\Gallery
    {
        $gallery = $this->galleryFactory->create();
        $this->galleryResource->load($gallery, $galleryId);

        foreach ($this->getImages($galleryId) as $image) {
            $this->deleteImageFile($image->getData());
        }

        $this->galleryResource->delete($gallery);

        return $gallery;
    }

    /**
     * @throws AlreadyExistsException
     */
    public function saveImageIfChanged(array $editImage)
    {
        $image = $this->imageCollection->getItemById($editImage['id']);
        if($image->getVisibility() !== $editImage['visibility']) {
            $image->setVisibility(boolean_value($editImage['visibility']));
        }

        if($image->hasDataChanges()) {
            $this->imageResource->save($image);
        }
    }

    public function checkIfGalleryExist($galleryId): bool
    {
        $gallery = $this->galleryFactory->create();
        $this->galleryResource->load($gallery, $galleryId);

        return $gallery->hasData();
    }
}
