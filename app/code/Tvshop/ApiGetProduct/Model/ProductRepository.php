<?php

namespace Tvshop\ApiGetProduct\Model;

use Tvshop\ApiGetProduct\Api\ConfigurableProductRepositoryInterface;
use Tvshop\ApiGetProduct\Api\ProductRepositoryInterface;
use Tvshop\ApiGetProduct\Api\Data\ProductInterfaceFactory;
use Tvshop\ApiGetProduct\Helper\ProductHelper;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var ProductInterfaceFactory
     */
    private $productInterfaceFactory;

    /**
     * @var ProductHelper
     */
    private $productHelper;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface;
     */
    private $productRepository;


    /**
     * ProductRepository constructor.
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param ProductInterfaceFactory $productInterfaceFactory
     * @param ProductHelper $ProductHelper
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        ProductInterfaceFactory $productInterfaceFactory,
        ProductHelper $productHelper
    ){
        $this->productInterfaceFactory = $productInterfaceFactory;
        $this->productRepository = $productRepository;
        $this->productHelper = $productHelper;
    }

    /**
     * @param int $id
     * @return Tvshop\ApiGetProduct\Api\Data\ProductInterface
     * @throws NoSuchEntityException
     */
    public function getProductById($id){
        /**
         * @var Tvshop\ApiGetProduct\Api\Data\ProductInterface $productInterface
         */
        $productInterface = $this->productInterfaceFactory->create();
        try{
            /**
             * @var Magento\Catalog\Api\Data\ProductInterface $product
             */
            $product = $this->productRepository->getById($id);
            $productInterface->setId($product->getId());
            $productInterface->setSku($product->getSku());
            $productInterface->setName($product->getName());
            $productInterface->setDescription($product->getDescription() ? $product->getDescription() : "");
            $productInterface->setPrice($this->productHelper->formatPrice($product->getPrice()));
            $productInterface->setImages($this->productHelper->getProductImagesArray($product));
            return $productInterface;
        }catch(NoSuchEntityException $e){
            throw NoSucEntityException::singleField('id',$id);
        }

    }
    
}

?>