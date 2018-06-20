<?php
/**
 * @author     Kristof Ringleff
 * @package    Fooman_PdfCore
 * @copyright  Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fuman\Core\Model\Config\Source;

class LogoPlacement extends \Fooman\PdfCore\Model\Config\Source\LogoPlacement
{
    const AUTO_LEFT = 'auto';
    const AUTO_CENTER = 'auto-center';
    const AUTO_RIGHT = 'auto-right';

    /**
     * supply dropdown choices for logo placement
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::AUTO_LEFT, 'label' => __('left')],
            ['value' => self::AUTO_CENTER, 'label' => __('center')],
            ['value' => self::AUTO_RIGHT, 'label' => __('right')]
        ];
    }
}
