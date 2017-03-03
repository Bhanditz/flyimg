<?php

namespace Core\Controller;

use Core\Entity\Image;
use JonnyW\PhantomJs\Client;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends CoreController
{
    /**
     * @return string
     */
    public function indexAction()
    {
        return $this->render('Default/index.twig');
    }

    /**
     * @param string $options
     * @param null   $imageSrc
     * @return Response
     */
    public function uploadAction($options, $imageSrc = null)
    {
        try {
            $image = $this->getImageProcessor()->process($options, $imageSrc);
        } catch (\Exception $e) {
            return new Response($e->getMessage().' '.$e->getFile().' '.$e->getLine(), Response::HTTP_FORBIDDEN);
        }

        return $this->generateImageResponse($image);
    }

    /**
     * @param string $options
     * @param null   $imageSrc
     * @return Response
     */
    public function pathAction($options, $imageSrc = null)
    {
        try {
            $image = $this->getImageProcessor()->process($options, $imageSrc);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_FORBIDDEN);
        }

        return $this->generatePathResponse($image);
    }

    /**
     * @param string $options
     * @param null   $url
     * @return Response
     */
    public function grabAction($options, $url = null)
    {
        $client = Client::getInstance();
        $client->getEngine()->setPath('/usr/local/bin/phantomjs');
//
//        $width = 800;
//        $height = 600;
//        $top = 0;
//        $left = 0;

        /**
         * @see \JonnyW\PhantomJs\Http\CaptureRequest
         **/
        $request = $client->getMessageFactory()->createCaptureRequest($url, 'GET');
        $request->setOutputFile(TMP_DIR.rand().'.jpg');
//        $request->setViewportSize($width, $height);
//        $request->setCaptureDimensions($width, $height, $top, $left);
        /**
         * @see /JonnyW\PhantomJs\Http\Response
         **/
        $response = $client->getMessageFactory()->createResponse();

        // Send the request
        $client->send($request, $response);

        $response = new BinaryFileResponse($request->getOutputFile());

        return $response;
    }
}
