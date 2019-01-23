<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Form\ConfigurationType;
use App\Repository\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConfigurationController
 * @package App\Controller
 * @Route("/configuration")
 */
class ConfigurationController extends AbstractController
{
    /**
     * @param Request $request
     * @param ConfigurationRepository $configurationRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/editOrUpdate", name="config_editOrUpdate", methods="GET|POST")
     */
    public function createOrUpdate(Request $request, ConfigurationRepository $configurationRepository)
    {
        // ---------- API_KEY_GOOGLE_MAPS ------------
        /** @var Configuration $apiKeyGoogleMaps */
        $apiKeyGoogleMaps = $configurationRepository->findOneByName('API_KEY_GOOGLE_MAPS');
        if ($apiKeyGoogleMaps == null) {
            $apiKeyGoogleMaps = new Configuration();
            $apiKeyGoogleMaps->setName('API_KEY_GOOGLE_MAPS');
        }

        $apiKeyDevHere = $configurationRepository->findOneByName('API_KEY_DEV_HERE');
        if ($apiKeyDevHere == null) {
            $apiKeyDevHere = new Configuration();
            $apiKeyDevHere->setName('API_KEY_DEV_HERE');
        }
        $appCodeDevHere = $configurationRepository->findOneByName('APP_CODE_DEV_HERE');
        if ($appCodeDevHere == null) {
            $appCodeDevHere = new Configuration();
            $appCodeDevHere->setName('APP_CODE_DEV_HERE');
        }

        $formApiKeyGoogleMaps = $this->createForm(ConfigurationType::class, $apiKeyGoogleMaps);
        $formApiKeyDevHere = $this->createForm(ConfigurationType::class, $apiKeyDevHere);
        $formAppCodeDevHere = $this->createForm(ConfigurationType::class, $appCodeDevHere);


        if ($request->getMethod() == 'POST') {
            $configuration = new Configuration();
            $form = $this->createForm(ConfigurationType::class, $configuration);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $name = $request->request->get('configuration')['name'];
                $value = $request->request->get('configuration')['value'];

                $lastConfiguration = $configurationRepository->findOneByName($name);
                if ($lastConfiguration == null) {
                    $lastConfiguration = new Configuration();
                }

                $lastConfiguration->setName($name);
                $lastConfiguration->setValue($value);
                $em->persist($lastConfiguration);
                $em->flush();

                return $this->redirectToRoute('config_editOrUpdate');
            }
        }

        return $this->render('configuration/edit.html.twig', [
            'formApiKeyGoogleMaps' => $formApiKeyGoogleMaps->createView(),
            'formApiKeyDevHere' => $formApiKeyDevHere->createView(),
            'formAppCodeDevHere' => $formAppCodeDevHere->createView(),
        ]);
    }

}
