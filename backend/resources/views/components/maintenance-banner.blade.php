@if(App\Models\SiteSetting::isMaintenanceMode() && auth()->check())
    <div class="fixed top-0 left-0 w-full z-[60] bg-amber-500 text-amber-950 py-1 text-center text-xs font-bold tracking-widest uppercase">
        MODO OFFLINE ATIVADO
    </div>
@endif
