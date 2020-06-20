<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\IntlBundle\Templating\Helper;

use Sonata\IntlBundle\Locale\LocaleDetectorInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Intl\Locales;
use Twig\Extra\Intl\IntlExtension;

/**
 * LocaleHelper displays culture information.
 *
 * @final since sonata-project/intl-bundle 2.x
 *
 * @author Thomas Rabaix <thomas.rabaix@ekino.com>
 */
class LocaleHelper extends BaseHelper
{
    /**
     * @var IntlExtension|null
     */
    private $intlExtension;

    /**
     * @param string $charset The output charset of the helper
     */
    public function __construct(string $charset, LocaleDetectorInterface $localeDetector, ?IntlExtension $intlExtension = null)
    {
        parent::__construct($charset, $localeDetector);

        $this->intlExtension = $intlExtension;

        // NEXT_MAJOR: Remove the ability to allow null values at argument 3 and remove the following lines in this method.
        if (null === $intlExtension) {
            @trigger_error(sprintf(
                'Not passing an instance of "%s" as argument 3 for "%s()" is deprecated since sonata-project/intl-bundle 2.x'
                .' and will throw an exception in version 3.x.',
                IntlExtension::class,
                __METHOD__
            ), E_USER_DEPRECATED);
        }
    }

    /**
     * @param string      $code
     * @param string|null $locale
     *
     * @return string
     */
    public function country($code, $locale = null)
    {
        if ($this->intlExtension) {
            return $this->fixCharset($this->intlExtension->getCountryName($code, $locale ?: $this->localeDetector->getLocale()));
        }

        return $this->fixCharset(Countries::getName($code, $locale ?: $this->localeDetector->getLocale()));
    }

    /**
     * @param string      $code
     * @param string|null $locale
     *
     * @return string
     */
    public function language($code, $locale = null)
    {
        if ($this->intlExtension) {
            $this->fixCharset($this->intlExtension->getLanguageName($code, $locale));
        }

        return $this->fixCharset(Languages::getName($code, $locale ?: $this->localeDetector->getLocale()));
    }

    /**
     * @param string      $code
     * @param string|null $locale
     *
     * @return string
     */
    public function locale($code, $locale = null)
    {
        if ($this->intlExtension) {
            $this->fixCharset($this->intlExtension->getLocaleName($code, $locale));
        }

        return $this->fixCharset(Locales::getName($code, $locale ?: $this->localeDetector->getLocale()));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonata_intl_locale';
    }
}
