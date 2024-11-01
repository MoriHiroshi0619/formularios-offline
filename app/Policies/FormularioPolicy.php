<?php
namespace App\Policies;

use App\Models\Usuario;
use Illuminate\Auth\Access\HandlesAuthorization;

/*
 * @deprecated
 * Criado para trabalhar com permissões
 * Más até o momento desse projeto, trabalhar com middlewares nas rotás já realiza as verificações necessárias
*/
class FormularioPolicy
{
    use HandlesAuthorization;

    public function viewAny(Usuario $user)
    {
        return $user->isProfessor() || $user->isAdmin();
    }

    public function view(Usuario $user)
    {
        return $user->isProfessor() || $user->isAdmin();
    }

    public function create(Usuario $user)
    {
        return $user->isProfessor() || $user->isAdmin();
    }

    public function update(Usuario $user)
    {
        return $user->isProfessor() || $user->isAdmin();
    }

    public function delete(Usuario $user)
    {
        return $user->isProfessor() || $user->isAdmin();
    }
}
