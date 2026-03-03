<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = service('session');

        if (!$session->get('id_utilisateur')) {
            return redirect()->to('/connexion')->with('error', 'Veuillez vous connecter.');
        }

        $userRole = $session->get('role');

        if ($arguments && !in_array($userRole, $arguments)) {
            return redirect()->to('/')->with('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après
    }
}
