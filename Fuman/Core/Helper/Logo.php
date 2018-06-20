<?php
/**
 * @author     Kristof Ringleff
 * @package    Fooman_PdfCore
 * @copyright  Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fuman\Core\Helper;

class Logo extends \Fooman\PdfCore\Helper\Logo
{
    const XML_PATH_PDF_LOGO = 'sales_pdf/all/logo/image';
    const XML_PATH_PDF_LOGO_PLACEMENT = 'sales_pdf/all/logo/placement';

    protected $logo = [];
    protected $logoPath = [];

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Fooman\PdfCore\Helper\Page
     */
    protected $pageHelper;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $file;

    /**
     * @var \Magento\Framework\Image\Factory
     */
    protected $imageFactory;

    protected $currentStoreId;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Filesystem         $filesystem
     * @param \Magento\Framework\Image\Factory      $imageFactory
     * @param \Magento\Framework\Filesystem\Io\File $file
     * @param \Fooman\PdfCore\Helper\Page           $pageHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\Factory $imageFactory,
        \Magento\Framework\Filesystem\Io\File $file,
        \Fooman\PdfCore\Helper\Page $pageHelper
    ) {
        parent::__construct($context, $filesystem, $imageFactory, $file, $pageHelper);
        $this->filesystem = $filesystem;
        $this->pageHelper = $pageHelper;
        $this->file = $file;
        $this->imageFactory = $imageFactory;
    }

    public function initLogo($storeId)
    {
        $this->currentStoreId = $storeId;
        if (!isset($this->logo[$storeId])) {
            $logo = $this->getLogoFilePath($storeId);
            if ($logo) {
                $this->logo[$storeId] = $this->imageFactory->create($logo);
                $this->logoPath[$storeId] = $logo;
            } else {
                $this->logo[$storeId] = false;
            }
        }
    }

    /**
     * @return bool|\Magento\Framework\Image
     */
    public function getLogo()
    {
        return $this->logo[$this->currentStoreId];
    }

    /**
     * @return bool|string
     */
    public function getLogoPath()
    {
        if ($this->logo[$this->currentStoreId]) {
            return $this->logoPath[$this->currentStoreId];
        } else {
            return false;
        }
    }

    /**
     * retrieve dimensions of logo
     * scaled to fit box 2.5cm and to half the page width
     * + original dimensions
     *
     * @param $maxHeight
     * @return array
     */
    public function getLogoDimensions($maxHeight = 25)
    {
        $width = $this->getLogo()->getOriginalWidth() / \Fooman\PdfCore\Model\Tcpdf\Defaults::FACTOR_PIXEL_PER_MM;
        $height = $this->getLogo()->getOriginalHeight() / \Fooman\PdfCore\Model\Tcpdf\Defaults::FACTOR_PIXEL_PER_MM;

        $maxWidth = ($this->pageHelper->getPageWidth() / 2) - $this->pageHelper->getSideMargins();

        //add some extra clearance if logo is on the left
        if (!$this->isLogoOnRight()) {
            $maxWidth -= 5;
        }

        $widthFactor = $width / $maxWidth;
        $heightFactor = $height / $maxHeight;

        $factor = max($widthFactor, $heightFactor);

        return [
            'orig_width'  => $width,
            'orig_height' => $height,
            'width'       => ($width / $factor) . 'mm',
            'height'      => ($height / $factor) . 'mm'
        ];
    }

    /**
     * @return bool
     */
    public function isLogoOnRight()
    {
        $logoPlacement = $this->scopeConfig->getValue(
            self::XML_PATH_PDF_LOGO_PLACEMENT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->currentStoreId
        );
        return $logoPlacement == \Fooman\PdfCore\Model\Config\Source\LogoPlacement::AUTO_RIGHT;
    }

    public function isLogoOnCenter()
    {
        $logoPlacement = $this->scopeConfig->getValue(
            self::XML_PATH_PDF_LOGO_PLACEMENT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->currentStoreId
        );
        return $logoPlacement == \Fooman\PdfCore\Model\Config\Source\LogoPlacement::AUTO_CENTER;
    }

    /**
     * get config value translated to absolute path to logo image
     *
     * @return bool|string
     */
    protected function getLogoFilePath()
    {
        $file = $this->scopeConfig->getValue(
            self::XML_PATH_PDF_LOGO,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->currentStoreId
        );
        $fullPath = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
            ->getAbsolutePath('pdf_logo/' . $file);
        if ($this->file->fileExists($fullPath)) {
            return $fullPath;
        } else {
            return false;
        }
    }
}
