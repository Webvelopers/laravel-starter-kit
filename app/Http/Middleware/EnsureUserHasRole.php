<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Models\UserRoleAssignment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserHasRole
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_if($user === null, Response::HTTP_FORBIDDEN);

        /** @var list<UserRole> $allowedRoles */
        $allowedRoles = array_values(array_map(
            UserRole::from(...),
            $roles,
        ));

        abort_unless(UserRoleAssignment::userHasAnyRole($user, $allowedRoles), Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
