<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Customer\Ui\Component;

class FilterFactory
{
    /**
     * @var \Magento\Framework\View\Element\UiComponentFactory
     */
    protected $componentFactory;

    /**
     * @var array
     */
    protected $filterMap = [
        'default' => 'filterInput',
        'select' => 'filterSelect',
        'boolean' => 'filterSelect',
        'multiselect' => 'filterSelect',
        'date' => 'filterDate',
    ];

    /**
     * @param \Magento\Framework\View\Element\UiComponentFactory $componentFactory
     */
    public function __construct(\Magento\Framework\View\Element\UiComponentFactory $componentFactory)
    {
        $this->componentFactory = $componentFactory;
    }

    /**
     * @param string $filterName
     * @param \Magento\Customer\Api\Data\AttributeMetadataInterface $attribute
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @return \Magento\Ui\Component\Listing\Columns\ColumnInterface
     */
    public function create($filterName, $attribute, $context)
    {
        $config = [
            'dataScope' => $filterName,
            'label' => __($attribute->getFrontendLabel()),
        ];
        if ($attribute->getOptions()) {
            $config['options'] = $this->getOptionsArray($attribute);
            $config['caption'] = __('Select...');
        }
        $arguments = [
            'data' => [
                'config' => $config,
            ],
            'context' => $context,
        ];

        return $this->componentFactory->create($filterName, $this->getFilterType($attribute), $arguments);
    }

    /**
     * @param \Magento\Customer\Api\Data\AttributeMetadataInterface $attribute
     * @return array
     */
    protected function getOptionsArray($attribute)
    {
        $options = [];
        foreach ($attribute->getOptions() as $option) {
            array_push(
                $options,
                [
                    'value' => $option->getValue(),
                    'label' => $option->getLabel()
                ]
            );
        }
        return $options;
    }

    /**
     * @param \Magento\Customer\Api\Data\AttributeMetadataInterface $attribute
     * @return string
     */
    protected function getFilterType($attribute)
    {
        return isset($this->filterMap[$attribute->getFrontendInput()])
            ? $this->filterMap[$attribute->getFrontendInput()]
            : $this->filterMap['default'];
    }
}
