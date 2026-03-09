<div class="campaign-menu grid grid-flow-col justify-items-center p-2 mb-3 rounded-lg" style="background-color: {{get_app_setting('header_background_color')}};">
    @if ($campaignGames)
        <a href="{{$campaignUrl}}">
            <img src="{{ $active == 'games' ? $campaignGames->icon_active : $campaignGames->icon }}" alt="{{ $campaignGames->name }}" title="{{ $campaignGames->name }}" class="w-24 h-24 {{$active == 'games' ? 'active' : ''}}">
        </a>
    @endif
    @if ($campaignTickets)
        <a href="{{ get_app_setting('ocr_ticket_active') ? route('tickets.ocr.create', ['tenant' => tenant('id')]) : route('ticketsdash.create', ['tenant' => tenant('id')]) }}">
            <img src="{{ $active == 'tickets' ? $campaignTickets->icon_active : $campaignTickets->icon }}" alt="{{ $campaignTickets->name }}" title="{{ $campaignTickets->name }}" class="w-24 h-24 {{$active == 'tickets' ? 'active' : ''}}">
        </a>
    @endif
    @if ($campaignCoupons)
        <a href="{{route('coupons.capture', ['tenant' => tenant('id')])}}">
            <img src="{{ $active == 'coupons' ? $campaignCoupons->icon_active : $campaignCoupons->icon }}" alt="{{ $campaignCoupons->name }}" title="{{ $campaignCoupons->name }}" class="w-24 h-24 {{$active == 'coupons' ? 'active' : ''}}">
        </a>
    @endif
    @if(get_app_setting('ranking_enabled'))
        <a href="{{route('ranking.index', ['tenant' => tenant('id')])}}">
            <img src="{{ $active == 'ranking' ? get_app_setting('ranking_icon_active') : get_app_setting('ranking_icon') }}" alt="{{ __('Ranking') }}" title="{{ __('Ranking') }}" class="w-24 h-24 {{$active == 'ranking' ? 'active' : ''}}">
        </a>
    @endif
</div>

@if (!is_null($campaignGames) && request()->routeIs('campaign.show') )
    @if (!is_null($campaignGames->game_banner_video))
        <div class="aspect-w-16 aspect-h-9 mb-6">
            <iframe src="{{$campaignGames->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    @endif
    @if(is_null($campaignGames->game_banner_video) && !is_null($campaignGames->section_banner))
        @if ($campaignGames->game_banner_url)
            <a href="{{ $campaignGames->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                <img src="{{$campaignGames->section_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
            </a>
        @else
            <img src="{{$campaignGames->section_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
        @endif
    @endif
@endif

@if (!is_null($campaignTickets) && request()->routeIs('ticketsdash.create') )
    @if (!is_null($campaignTickets->game_banner_video))
        <div class="aspect-w-16 aspect-h-9 mb-6">
            <iframe src="{{$campaignTickets->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    @endif
    @if(is_null($campaignTickets->game_banner_video) && !is_null($campaignTickets->section_banner))
        @if ($campaignTickets->game_banner_url)
            <a href="{{ $campaignTickets->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                <img src="{{$campaignTickets->section_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
            </a>
        @else
            <img src="{{$campaignTickets->section_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
        @endif
    @endif
@endif

@if (!is_null($campaignTickets) && request()->routeIs('tickets.ocr.create') )
    @if (!is_null($campaignTickets->game_banner_video))
        <div class="aspect-w-16 aspect-h-9 mb-6">
            <iframe src="{{$campaignTickets->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    @endif
    @if(is_null($campaignTickets->game_banner_video) && !is_null($campaignTickets->section_banner))
        @if ($campaignTickets->game_banner_url)
            <a href="{{ $campaignTickets->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                <img src="{{$campaignTickets->section_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
            </a>
        @else
            <img src="{{$campaignTickets->section_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
        @endif
    @endif
@endif

@if (!is_null($campaignCoupons) && request()->routeIs('coupons.capture') )
    @if (!is_null($campaignCoupons->game_banner_video))
        <div class="aspect-w-16 aspect-h-9 mb-6">
            <iframe src="{{$campaignCoupons->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    @endif
    @if(is_null($campaignCoupons->game_banner_video) && !is_null($campaignCoupons->section_banner))
        @if ($campaignCoupons->game_banner_url)
            <a href="{{ $campaignCoupons->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                <img src="{{$campaignCoupons->section_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
            </a>
        @else
            <img src="{{$campaignCoupons->section_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
        @endif
    @endif
@endif

@if (get_app_setting('ranking_banner') && request()->routeIs('ranking.index') )
    <img src="{{get_app_setting('ranking_banner')}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
@endif