<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Invitation;

class HasInvitation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('get')) {

            /* No Invite */
            if (!$request->has('invitation_token')) {
                return abort(403);
            }

            /* Invite Token Invalid */
            $invitation_token = $request->get('invitation_token');

            try {
                $invitation = Invitation::where('invitation_token', $invitation_token)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect(route('login'))
                    ->with('error', 'Wrong invitation token! Please check your URL.');
            }

            /* Invite Account Registered */
            if (!is_null($invitation->registered_at)) {
                return redirect(route('login'))
                    ->with('error', 'The invitation link has already been used.');
            }
        }

        return $next($request);
    }
}
