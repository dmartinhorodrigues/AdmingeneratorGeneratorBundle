<?php

namespace Admingenerator\GeneratorBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class CsrfTokenExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('csrf_token', array($this, 'getCsrfToken')),
        );
    }

    public function getCsrfToken($intention)
    {
        if ($this->container->has('security.csrf.token_manager')) {
            $token = $this->container->get('security.csrf.token_manager')->getToken($intention)->getValue();
        } else {
            // BC for SF < 2.4
            $token = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken($intention)
            : null;
        }

        return $token;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'admingenerator_csrf';
    }
}
