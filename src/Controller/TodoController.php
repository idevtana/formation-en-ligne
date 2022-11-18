<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'todo')]
    public function index(Request $request): Response
    {
        // Afficher le tableau de Todo
        
        // sinon je l'initialise puis j'affiche

        $session = $request->getSession();

        if(!$session->has('todos')){
            $todos = [
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "La liste des todos viens d'être initialisée");
        }

        // si j'ai mon tableau de todo dans la session je ne fait que l'afficher
        return $this->render('todo/index.html.twig');
    }

    #[Route('/add/{name}/{content}', name: 'todo.add', defaults: ['content' => 'sf6'])]
    public function addTodo(Request $request, $name, $content): RedirectResponse
    {
        //Vérfier si j'ai mon tableau de todo dans la session
        $session = $request->getSession();

        // si oui
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(isset($todos[$name])){
                $this->addFlash('error', "Le toto d'id $name existe déjà dans la liste");    
            }           
            else{
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le toto d'id $name a été ajouté avec succès");    
            }
        }
        else{
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
        // si non
        // afficher une erreur et on va rediriger vers le controller initiale


    }

    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse
    {
        //Vérfier si j'ai mon tableau de todo dans la session
        $session = $request->getSession();

        // si oui
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(!isset($todos[$name])){
                $this->addFlash('error', "Le todo d'id $name n'existe pas dans la liste");    
            }           
            else{
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le toto d'id $name a été mis à jour avec succès");    
            }
        }
        else{
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
        // si non
        // afficher une erreur et on va rediriger vers le controller initiale


    }
    #[Route('/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse
    {
        //Vérfier si j'ai mon tableau de todo dans la session
        $session = $request->getSession();

        // si oui
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(!isset($todos[$name])){
                $this->addFlash('error', "Le todo d'id $name n'existe pas dans la liste");    
            }           
            else{
                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash('success', "Le toto d'id $name a été supprimé avec succès");    
            }
        }
        else{
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
        // si non
        // afficher une erreur et on va rediriger vers le controller initiale


    }
    #[Route('/reset', name: 'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse
    {
        $session = $request->getSession();

        $session->remove('todos');
        return $this->redirectToRoute('todo');
    }
}
