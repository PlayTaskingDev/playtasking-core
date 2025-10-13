<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\CampaignsTrait;

class CampaignHasCoupons
{
    use CampaignsTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $campaign = $this->get_current_campaign();
        if (!$this->has_content_type($campaign->id, 'coupons')) {
            return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        }

        return $next($request);
    }
}
