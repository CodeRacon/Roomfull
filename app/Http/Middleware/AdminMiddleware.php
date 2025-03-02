<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    // proof, if logged in user is admin
    if (!Auth::check()) {
      // if not: lead back to Landing-Page with error-message
      return redirect('/')->with('error', 'Zugriff verweigert. Du benötigst Admin-Rechte.');
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();
    if (!$user->isAdmin()) {
      return redirect('/')->with('error', 'Zugriff verweigert. Du benötigst Admin-Rechte.');
    }

    // if user is admin: grant access
    return $next($request);
  }
}
