<div class="aw-page min-h-screen w-full relative overflow-x-hidden flex items-center justify-center p-4 md:p-8 bg-gradient-to-br from-[#0083a0] via-[#005f73] to-[#0a2540]">
    <span class="aw-orb aw-orb-1"></span>
    <span class="aw-orb aw-orb-2"></span>
    <span class="aw-orb aw-orb-3"></span>

    <div class="aw-stack">
        <div class="aw-logo">
            <span class="aw-logo-badge">N</span>
            <p class="aw-logo-text">Nanteq</p>
        </div>

        <section @class([
            'aw-float w-full bg-white rounded-3xl shadow-2xl p-8 md:p-10 my-auto z-10 border border-white/20',
            'max-w-xl md:max-w-2xl' => $step !== 2,
            'aw-float-wide max-w-3xl md:max-w-4xl' => $step === 2,
        ])>
            @if ($step === 1)
                <h1 class="aw-title">{{ __('dashboard.specialist_register_heading') }}</h1>
                <p class="aw-subtitle">{{ __('dashboard.specialist_register_subheading') }}</p>

                <div class="aw-stepper">
                    @foreach ([1 => __('dashboard.step_basic_info'), 2 => __('dashboard.step_select_plan'), 3 => __('dashboard.step_specialist_code')] as $number => $label)
                        <div @class(['aw-step', 'is-active' => $step === $number, 'is-done' => $step > $number])>
                            <span class="aw-badge">{{ $number }}</span>
                            <span class="aw-step-label">{{ $label }}</span>
                        </div>
                        @if ($number < 3)
                            <span @class(['aw-line', 'is-done' => $step > $number])></span>
                        @endif
                    @endforeach
                </div>

                <form wire:submit="goToStepTwo" class="aw-space">
                    <div class="aw-grid aw-grid-2">
                        <div>
                            <label class="aw-label">{{ __('dashboard.full_name') }}</label>
                            <input wire:model="name" type="text" class="aw-input border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#0083a0] rounded-xl" />
                            @error('name') <p class="aw-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="aw-label">{{ __('dashboard.phone_number') }}</label>
                            <input wire:model="phone_number" type="text" class="aw-input border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#0083a0] rounded-xl" />
                            @error('phone_number') <p class="aw-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="aw-label">{{ __('dashboard.email') }}</label>
                        <input wire:model="email" type="email" class="aw-input border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#0083a0] rounded-xl" />
                        @error('email') <p class="aw-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="aw-grid aw-grid-2">
                        <div>
                            <label class="aw-label">{{ __('dashboard.password') }}</label>
                            <input wire:model="password" type="password" class="aw-input border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#0083a0] rounded-xl" />
                            @error('password') <p class="aw-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="aw-label">{{ __('dashboard.password_confirmation') }}</label>
                            <input wire:model="password_confirmation" type="password" class="aw-input border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#0083a0] rounded-xl" />
                        </div>
                    </div>

                    <button type="submit" class="aw-btn rounded-xl">{{ __('dashboard.next') }}</button>
                </form>
            @elseif ($step === 2)
                <h1 class="aw-title">{{ __('dashboard.specialist_register_heading') }}</h1>
                <p class="aw-subtitle">{{ __('dashboard.specialist_register_subheading') }}</p>

                <div class="aw-stepper">
                    @foreach ([1 => __('dashboard.step_basic_info'), 2 => __('dashboard.step_select_plan'), 3 => __('dashboard.step_specialist_code')] as $number => $label)
                        <div @class(['aw-step', 'is-active' => $step === $number, 'is-done' => $step > $number])>
                            <span class="aw-badge">{{ $number }}</span>
                            <span class="aw-step-label">{{ $label }}</span>
                        </div>
                        @if ($number < 3)
                            <span @class(['aw-line', 'is-done' => $step > $number])></span>
                        @endif
                    @endforeach
                </div>

                @php
                    $plans = $this->plans();
                    $recommendedId = $this->recommendedPlanId();
                    $planCount = $plans->count();
                @endphp
                <form wire:submit="register">
                    <div @class([
                        'aw-plans grid grid-cols-1 gap-4 my-6',
                        'md:grid-cols-2' => $planCount < 3,
                        'md:grid-cols-3' => $planCount >= 3,
                    ])>
                        @forelse ($plans as $plan)
                            @php $selected = (int) $plan_id === (int) $plan->id; @endphp
                            <label @class([
                                'aw-plan-card relative block cursor-pointer rounded-2xl border-2 p-6 transition duration-200',
                                'is-selected' => $selected,
                            ])>
                                <input type="radio" class="aw-only" wire:model.live="plan_id" value="{{ $plan->id }}" />

                                @if ($recommendedId === $plan->id)
                                    <span class="aw-plan-pill">{{ __('dashboard.plan_recommended') }}</span>
                                @endif

                                @if ($selected)
                                    <span class="aw-plan-check" aria-hidden="true">
                                        <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif

                                <h3 class="aw-plan-name text-lg font-bold text-gray-900">{{ $plan->name ?: __('dashboard.plan') }}</h3>
                                <p class="aw-plan-price">
                                    <span>SAR {{ number_format((float) $plan->price) }}</span>
                                    <small>{{ $this->planPeriodLabel($plan) }}</small>
                                </p>

                                <ul class="aw-plan-features">
                                    @foreach ($this->planFeatures($plan) as $feature)
                                        <li>
                                            <span class="aw-feature-check">✓</span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </label>
                        @empty
                            <p class="aw-plan-card" style="grid-column: 1 / -1; text-align: center;">{{ __('dashboard.no_plans_available') }}</p>
                        @endforelse
                    </div>
                    @error('plan_id') <p class="aw-error">{{ $message }}</p> @enderror

                    <div class="aw-plan-actions mt-8 flex gap-4">
                        <button type="button" wire:click="goToStepOne" class="aw-btn aw-btn-ghost rounded-xl">{{ __('dashboard.previous') }}</button>
                        <button type="submit" class="aw-btn rounded-xl">{{ __('dashboard.confirm_and_create_account') }}</button>
                    </div>
                </form>
            @else
                @include('filament.pages.auth.partials.specialist-pin-onboarding', [
                    'pin' => $specialist_code,
                ])
            @endif

            @if ($step < 3)
                <p class="aw-footer">
                    {{ __('dashboard.already_have_account') }}
                    <a href="{{ url('/admin/login') }}">{{ __('dashboard.login_heading') }}</a>
                </p>
            @endif
        </section>
    </div>
</div>
