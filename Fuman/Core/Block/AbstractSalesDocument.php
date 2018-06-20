<?php
/**
 * @author     Kristof Ringleff
 * @package    Fooman_PdfCustomiser
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fuman\Core\Block;

abstract class AbstractSalesDocument extends \Fooman\PdfCustomiser\Block\AbstractSalesDocument
{
    const XML_PATH_OWNERADDRESS = 'sales_pdf/all/allowneraddress';
    const XML_PATH_PRINTCOMMENTS = 'sales_pdf/all/page/allprintcomments';
    const XML_PATH_DISPLAYBOTH = 'sales_pdf/all/displayboth';

    const LAYOUT_HANDLE= 'fooman_pdfcustomiser';
    const PDF_TYPE = '';

    

     /**
     * @var \Magento\Sales\Model\Order\Address\Renderer
     */
    protected $addressRenderer;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var \Magento\Payment\Helper\Data
     */
    protected $paymentHelper;

    /**
     * @var \Fooman\PdfCore\Helper\Logo
     */
    protected $logoHelper;

    /**
     * @var string
     */
    protected $integratedLabelsConfigPath;

    /**
     * @var \Magento\GiftMessage\Api\OrderRepositoryInterface
     */
    protected $giftMessageOrderRepo;

    /**
     * @var \Fooman\PdfCore\Helper\ParamKey
     */
    protected $paramKeyHelper;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $productFactory;

    /**
     * @var \Magento\Eav\Model\Entity\AttributeFactory
     */
    private $attributeFactory;

    /**
     * @var \Fooman\PdfCore\Model\IntegratedLabels\ContentFactory
     */
    private $integratedLabelsContentFactory;

    /**
     * @var \Fooman\PdfCustomiser\Model\DesignProvider
     */
    private $designProvider;

    /**
     * @var \Fooman\PdfCustomiser\Model\TemplateFileDecider
     */
    private $templateFileDecider;


    /**
     * AbstractSalesDocument constructor.
     *
     * @param \Magento\Backend\Block\Template\Context               $context
     * @param \Magento\Framework\Filter\Input\MaliciousCode         $maliciousCode
     * @param \Fooman\PdfCore\Model\Template                        $template
     * @param \Magento\Sales\Model\Order\Address\Renderer           $addressRenderer
     * @param \Magento\Payment\Helper\Data                          $paymentHelper
     * @param \Fooman\PdfCore\Helper\Logo                           $logoHelper
     * @param \Fooman\PdfCore\Model\IntegratedLabels\ContentFactory $integratedLabelsContentFactory
     * @param \Magento\Catalog\Model\ProductFactory                 $productFactory
     * @param \Magento\Eav\Model\Entity\AttributeFactory            $attributeFactory
     * @param \Magento\GiftMessage\Api\OrderRepositoryInterface     $giftMessageOrderRepo
     * @param \Magento\Framework\App\AreaList                       $areaList
     * @param \Fooman\PdfCore\Helper\ParamKey                       $paramKeyHelper
     * @param array                                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Filter\Input\MaliciousCode $maliciousCode,
        \Fooman\PdfCore\Model\Template $template,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Fooman\PdfCore\Helper\Logo $logoHelper,
        \Fooman\PdfCore\Model\IntegratedLabels\ContentFactory $integratedLabelsContentFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Eav\Model\Entity\AttributeFactory $attributeFactory,
        \Magento\GiftMessage\Api\OrderRepositoryInterface $giftMessageOrderRepo,
        \Magento\Framework\App\AreaList $areaList,
        \Fooman\PdfCore\Helper\ParamKey $paramKeyHelper,
        \Fooman\PdfCustomiser\Model\DesignProvider $designProvider,
        \Fooman\PdfCustomiser\Model\TemplateFileDecider $templateFileDecider,
        array $data = []
    ) {
        $this->timezone = $context->getLocaleDate();
        $this->addressRenderer = $addressRenderer;
        $this->paymentHelper = $paymentHelper;
        $this->logoHelper = $logoHelper;
        $this->integratedLabelsContentFactory = $integratedLabelsContentFactory;
        $this->productFactory = $productFactory;
        $this->attributeFactory = $attributeFactory;
        $this->giftMessageOrderRepo = $giftMessageOrderRepo;
        $this->paramKeyHelper = $paramKeyHelper;
        $this->designProvider = $designProvider;
        $this->templateFileDecider = $templateFileDecider;
        parent::__construct($context, $maliciousCode, $template, $addressRenderer, $paymentHelper, $logoHelper, $integratedLabelsContentFactory, $productFactory, $attributeFactory, $giftMessageOrderRepo, $areaList, $paramKeyHelper, $designProvider, $templateFileDecider, $data);
    }

    /**
     * @return bool
     */
    public function isLogoOnCenter()
    {
        return $this->logoHelper->isLogoOnCenter();
    }
}