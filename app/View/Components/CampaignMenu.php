<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CampaignMenu extends Component
{
    public $campaignGames;
    public $campaignTickets;
    public $campaignCoupons;
    public $campaignUrl;
    public $active;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($campaignGames, $campaignTickets, $campaignCoupons, $campaignUrl, $active)
    {
        $this->campaignGames = $campaignGames;
        $this->campaignTickets = $campaignTickets;
        $this->campaignCoupons = $campaignCoupons;
        $this->campaignUrl = $campaignUrl;
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.campaign-menu');
    }
}
