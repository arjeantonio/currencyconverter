<?php

namespace App\Controller;

use App\Form\ConversionFormType;
use App\Service\CurrencyConverterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConverterController extends AbstractController
{
    #[Route('/converter', name: 'app_converter', methods:['GET','POST','HEAD'])]
    public function index(Request $request, CurrencyConverterService $converterService): Response
    {
        $convertedValues = [];
        $form = $this->createForm(ConversionFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sourceCurrency = $form->get('currency')->getData();
            $amount = $form->get('amount')->getData();
            $amount = str_replace(',', '.', $amount);
            $convertedValues = $converterService->convertBySourceCurrency($sourceCurrency, $amount);
        }

        return $this->render('converter/index.html.twig', [
            'conversionForm' => $form->createView(),
            'convertedValues' => $convertedValues ?: [],
        ]);
    }
}
