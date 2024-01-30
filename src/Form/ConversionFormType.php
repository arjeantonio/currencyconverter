<?php

namespace App\Form;

use App\Repository\ExchangeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Regex;

class ConversionFormType extends AbstractType
{
    private $exchangeRepository;

    /**
     * __construct
     *
     * @param  mixed $exchangeRepository
     * @return void
     */
    public function __construct(ExchangeRepository $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
    }

    /**
     * buildForm
     *
     * @param  mixed $builder
     * @param  mixed $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currency', ChoiceType::class, [
                'choices' => $this->getCurrenciesOptions(),
                'constraints' => [
                    new Choice([
                        'choices' => $this->exchangeRepository->getCurrencyCodes(),
                        'message' => 'Please select a valid option.',
                    ]),
                ],
            ])
            ->add('amount', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^-?\d+([.,]\d*)?$/',
                        'message' => 'Please enter a valid numeric value.',
                    ]),
                ],
            ]);
    }

    /**
     * getCurrenciesOptions
     *
     * @return array
     */
    private function getCurrenciesOptions()
    {
        $currencyCodes = $this->exchangeRepository->getCurrencyCodes();

        $choices = [];
        foreach ($currencyCodes as $currencyCode) {
            $choices[$currencyCode] = $currencyCode;
        }

        return $choices;
    }
}
