<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Movie;
use AppBundle\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;


class MoviesController extends AbstractController
{
    // https://github.com/FriendsOfSymfony/FOSRestBundle/blob/master/Controller/ControllerTrait.php
    use ControllerTrait;

    /**
     * @Rest\View()
     */
    public function getMoviesAction()
    {
          $movies = $this->getDoctrine()->getRepository('AppBundle:Movie')->findAll();

          return $movies;
    }

     /**
      * @Rest\View(statusCode=201)
      * @ParamConverter("movie",   converter="fos_rest.request_body") # autoconvertion from json to movie object (see config)
      * @Rest\NoRoute()            # force to use manual route instead of auto routing
      */
    public function postMoviesAction(
        Movie $movie, ConstraintViolationListInterface $validationErrors
    ) {
        if (count($validationErrors) > 0) {
            // throw new HttpException(400, 'Invalid or incomplete data.');
            throw new ValidationException($validationErrors);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($movie);
        $em->flush();

        return $movie;
    }

    /**
     * Delete movie by id
     *
     * @Rest\View()
     */
    public function deleteMovieAction(?Movie $movie) // optional => API does not throw error exception automatically => can self speficy how it is handled
    {
        // $movie = $this->getDoctrine()->getRepository('AppBundle:Movie')->find($movieId); # would work without @Rest\View / - Movie => movieId
        $movie = $this->getDoctrine()->getRepository('AppBundle:Movie')->find($movie);

        if (null === $movie) {
            // create view from controllerTrait
            return $this->view(null, 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($movie);
        $em->flush();
    }

    /**
     * @Rest\View()
     */
    public function getMovieAction(?Movie $movie)
    {
        if (null === $movie) {
            return $this->view(null, 404);
        }

        return $movie;
    }
}
