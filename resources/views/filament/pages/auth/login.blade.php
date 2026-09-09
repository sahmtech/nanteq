<div class="aw-page min-h-screen w-full relative overflow-x-hidden flex items-center justify-center p-4 md:p-8 bg-gradient-to-br from-[#0083a0] via-[#005f73] to-[#0a2540]">
    <span class="aw-orb aw-orb-1"></span>
    <span class="aw-orb aw-orb-2"></span>
    <span class="aw-orb aw-orb-3"></span>

    @include('filament.pages.auth.partials.auth-corner-logo')

    <div class="aw-stack">

        <section class="aw-float w-full max-w-xl md:max-w-2xl bg-white rounded-3xl shadow-2xl p-8 md:p-10 my-auto z-10 border border-white/20">
            <h1 class="aw-title">{{ __('dashboard.login_heading') }}</h1>
            <p class="aw-subtitle">{{ __('dashboard.login_subheading') }}</p>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

            <div class="mt-8">
                <x-filament-panels::form wire:submit="authenticate">
                    {{ $this->form }}

                    <x-filament-panels::form.actions
                        :actions="$this->getCachedFormActions()"
                        :full-width="true"
                    />
                </x-filament-panels::form>
            </div>

            <p class="aw-footer">
                {{ __('dashboard.no_account') }}
                <a href="{{ url('/specialist/register') }}">{{ __('dashboard.create_specialist_account') }}</a>
            </p>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
        </section>
    </div>
</div>
