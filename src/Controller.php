<?php

namespace Collegeplannerpro\InterviewReport;

use Psr\Container\ContainerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Collegeplannerpro\InterviewReport\Mailer;

class Controller
{
    private \Twig\Environment $twig;
    private Repository $repository;

    public function __construct(ContainerInterface $container) {
        $this->twig = $container->get('twig');
        $this->repository = $container->get('repository');
    }

    public function home(Request $request, Response $response): Response
    {
        $response->getBody()->write($this->twig->render('home.html.twig'));
        return $response;
    }

    public function paymentsReport(Request $request, Response $response): Response
    {
        $invoiceResult = $this->repository->allInvoices();
        $invoices = [];

        while ($invoice = $invoiceResult->fetch_assoc()) {
            $invoice['payments'] = $this->repository->invoicePayments($invoice['invoice_id'])->fetch_all(MYSQLI_ASSOC);
            $invoices[] = $invoice;
        }

        $content = $this->twig->render('paymentsReport.html.twig', [
            'invoices' => $invoices,
        ]);

        $response->getBody()->write($content);
        return $response;
    }

    /**
     * Send contact payment reminder
     * 
     * @param  Request  $request                
     * @param  Response $response     
     *          
     * @return Response           
     */
    
    public function contactPaymentReminder(Request $request, Response $response): Response 
    {
        $queryParams = $request->getQueryParams();
        $mailer = new Mailer();
        $success = true;
        $errorMessage = '';

        $contactId = isset($queryParams['contact_id']) ? $queryParams['contact_id'] : null;
        
        // Check contact id is an integer
        if (filter_var($contactId, FILTER_VALIDATE_INT) === false) {   
            $success = false;
        } else {
            
            try {
                $contactDetails = $this->repository->contactDetails($contactId);
            
                // Assuming that if email address exists then it is valid
                if ($contactDetails && $contactDetails['email']) {
                    $mailerResult = $mailer->send([$contactDetails['email']], 'Payment due reminder', 'Please, please pay now!');
                    $success = $mailerResult->isSuccess;
                    
                    if (!$success) {
                        $errorMessage = $mailerResult->errorMessage;
                    }
                    
                } else {
                    $success = false;
                }
                
            } catch (\Exception $e) {
                $success = false;
            }
        }
        
        $content = json_encode(['success' => $success, 'error_message' => $errorMessage]);
        $response->getBody()->write($content);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
