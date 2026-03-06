<div
    x-data="{
        show: false,
        showCustomize: false,
        analytics: false,
        functional: false,
        init() {
            if (!localStorage.getItem('cookie-consent')) {
                this.show = true;
            }
        },
        acceptAll() {
            this.savePreferences({ essential: true, analytics: true, functional: true });
        },
        essentialOnly() {
            this.savePreferences({ essential: true, analytics: false, functional: false });
        },
        saveCustom() {
            this.savePreferences({ essential: true, analytics: this.analytics, functional: this.functional });
        },
        savePreferences(prefs) {
            localStorage.setItem('cookie-consent', JSON.stringify(prefs));
            this.show = false;
            this.showCustomize = false;
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-full opacity-0"
    x-cloak
    class="fixed inset-x-0 bottom-0 z-50 p-4"
    role="dialog"
    aria-label="{{ __('legal.cookies.title') }}"
>
    <div class="mx-auto max-w-4xl rounded-xl border border-white/10 bg-[#0b1022]/95 p-6 shadow-2xl backdrop-blur-sm">
        {{-- Main banner --}}
        <div x-show="!showCustomize">
            <p class="mb-4 text-sm leading-relaxed text-white/80">
                {{ __('legal.cookies.banner.message') }}
                <a href="{{ LaravelLocalization::localizeURL('/cookies') }}" class="text-ocean-light underline hover:text-white">
                    {{ __('legal.cookies.banner.learnMore') }}
                </a>
            </p>
            <div class="flex flex-wrap gap-3">
                <button
                    @click="acceptAll()"
                    class="rounded-lg bg-ocean px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-ocean-deep"
                >
                    {{ __('legal.cookies.banner.acceptAll') }}
                </button>
                <button
                    @click="essentialOnly()"
                    class="rounded-lg border border-white/20 px-5 py-2.5 text-sm font-medium text-white/80 transition-colors hover:border-white/40 hover:text-white"
                >
                    {{ __('legal.cookies.banner.essentialOnly') }}
                </button>
                <button
                    @click="showCustomize = true"
                    class="rounded-lg px-5 py-2.5 text-sm font-medium text-white/60 transition-colors hover:text-white/80"
                >
                    {{ __('legal.cookies.banner.customize') }}
                </button>
            </div>
        </div>

        {{-- Customize panel --}}
        <div x-show="showCustomize" x-cloak>
            <div class="space-y-4">
                {{-- Essential --}}
                <div class="flex items-center justify-between rounded-lg bg-white/5 px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-white">{{ __('legal.cookies.banner.essential') }}</p>
                        <p class="text-xs text-white/50">{{ __('legal.cookies.banner.essentialDesc') }}</p>
                    </div>
                    <div class="rounded-full bg-ocean/30 px-3 py-1 text-xs text-ocean-light">ON</div>
                </div>

                {{-- Analytics --}}
                <label class="flex cursor-pointer items-center justify-between rounded-lg bg-white/5 px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-white">{{ __('legal.cookies.banner.analytics') }}</p>
                        <p class="text-xs text-white/50">{{ __('legal.cookies.banner.analyticsDesc') }}</p>
                    </div>
                    <div class="relative">
                        <input type="checkbox" x-model="analytics" class="peer sr-only" />
                        <div class="h-6 w-11 rounded-full bg-white/20 transition-colors peer-checked:bg-ocean"></div>
                        <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white transition-transform peer-checked:translate-x-5"></div>
                    </div>
                </label>

                {{-- Functional --}}
                <label class="flex cursor-pointer items-center justify-between rounded-lg bg-white/5 px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-white">{{ __('legal.cookies.banner.functional') }}</p>
                        <p class="text-xs text-white/50">{{ __('legal.cookies.banner.functionalDesc') }}</p>
                    </div>
                    <div class="relative">
                        <input type="checkbox" x-model="functional" class="peer sr-only" />
                        <div class="h-6 w-11 rounded-full bg-white/20 transition-colors peer-checked:bg-ocean"></div>
                        <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white transition-transform peer-checked:translate-x-5"></div>
                    </div>
                </label>
            </div>

            <div class="mt-4 flex flex-wrap gap-3">
                <button
                    @click="saveCustom()"
                    class="rounded-lg bg-ocean px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-ocean-deep"
                >
                    {{ __('legal.cookies.banner.save') }}
                </button>
                <button
                    @click="showCustomize = false"
                    class="rounded-lg px-5 py-2.5 text-sm font-medium text-white/60 transition-colors hover:text-white/80"
                >
                    &larr;
                </button>
            </div>
        </div>
    </div>
</div>
