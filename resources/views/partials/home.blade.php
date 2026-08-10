@php
    $activeCampaigns = \App\Models\Campaign::active()->withSum('payments', 'paid_amount')->latest()->take(10)->get();
    $activePrograms = \App\Models\Program::active()->withSum('payments', 'paid_amount')->latest()->take(10)->get();    
    $heroSlides = $activeCampaigns->concat($activePrograms)->sortByDesc('created_at')->take(15);
@endphp

@include('partials.home.hero')
@include('partials.home.campaigns')
@include('partials.home.news')
@include('partials.home.articles')
@include('partials.home.chatbot')
@include('partials.home.scripts')
