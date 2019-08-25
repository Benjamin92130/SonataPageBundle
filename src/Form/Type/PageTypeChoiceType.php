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

namespace Sonata\PageBundle\Form\Type;

use Sonata\PageBundle\Page\PageServiceManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Select a page type.
 *
 * @author Olivier Paradis <paradis.olivier@gmail.com>
 */
class PageTypeChoiceType extends AbstractType
{
    /**
     * @var PageServiceManagerInterface
     */
    protected $manager;

    public function __construct(PageServiceManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $defaults = [
            'choices' => $this->getPageTypes(),
            'choice_translation_domain' => false,
        ];

        // NEXT_MAJOR: Remove (when requirement of Symfony is >= 3.0)
        if (method_exists(FormTypeInterface::class, 'setDefaultOptions')) {
            $defaults['choices_as_values'] = true;
        }

        $resolver->setDefaults($defaults);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver): void
    {
        $this->configureOptions($resolver);
    }

    /**
     * @return string[]
     */
    public function getPageTypes()
    {
        $services = $this->manager->getAll();
        $types = [];
        foreach ($services as $id => $service) {
            $types[$service->getName()] = $id;
        }

        ksort($types);

        return $types;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'sonata_page_type_choice';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
