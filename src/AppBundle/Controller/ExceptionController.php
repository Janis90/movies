<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\View\View;
use AppBundle\Exception\ValidationException;
use SensioLabs\Security\Exception\HttpException;

class ExceptionController extends Controller
{
    use ControllerTrait;

    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null)
    {
        if ($exception instanceof ValidationException) {
            return $this->getView($exception->getStatusCode(), json_decode($exception->getMessage(), true));
        }

        if ($exception instanceof HttpException) {
            return $this->getView($exception->getStatusCode(), $exception->getMessage());
        }

        /**
        * @var \Exception $exception
        */
        return $this->getView(null, $exception->getMessage());
    }

    private function getView(?int $statusCode, $message): View
    {
        $data = [
            'code' => $statusCode ?? 500,
            'message' => $message
        ];

        return $this->view($data, $statusCode ?? 500);
    }
}
