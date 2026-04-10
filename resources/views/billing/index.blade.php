@extends('layouts.app')

@section('title', 'Billing')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl" style="color: var(--text);">Billing</h1>
        <p class="mt-1" style="color: var(--muted);">Manage your subscription and billing details.</p>
    </div>

    {{-- Success Banner --}}
    @if(request('subscribed'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="rounded-xl p-5 mb-6"
            style="background-color: var(--surface); border: 2px solid #86efac;"
        >
            <p class="text-sm" style="color: #86efac;">
                You're now on the <strong>{{ $user->planName() }}</strong> plan. Welcome aboard!
            </p>
        </div>
    @endif

    {{-- Error Banner --}}
    @if(session('error'))
        <div class="rounded-xl p-5 mb-6" style="background-color: var(--surface); border: 2px solid #ef4444;">
            <p class="text-sm" style="color: #ef4444;">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Trial / Expired Banner --}}
    @if($user->onTrial())
        <div class="rounded-xl p-5 mb-6" style="background-color: var(--surface); border: 2px solid var(--accent);">
            <p class="text-sm" style="color: var(--text);">
                You're on a free trial &mdash; <strong>{{ $user->trialDaysRemaining() }} days remaining</strong>. Choose a plan below to keep access.
            </p>
        </div>
    @elseif($user->trialExpired())
        <div class="rounded-xl p-5 mb-6" style="background-color: var(--surface); border: 2px solid #ef4444;">
            <p class="text-sm" style="color: #ef4444;">
                Your trial has ended. Select a plan to restore access.
            </p>
        </div>
    @endif

    {{-- Current Plan --}}
    <div class="rounded-xl p-6 mb-8" style="background-color: var(--surface); border: 1px solid var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm mb-1" style="color: var(--muted);">Current Plan</p>
                <p class="text-2xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--accent);">
                    {{ $user->planName() }}
                </p>
                @if($user->plan)
                    <p class="text-sm mt-1" style="color: var(--muted);">{{ ucfirst($user->billing_period ?? 'monthly') }} billing</p>
                @endif
            </div>
            @if($user->plan)
                <form method="POST" action="/billing/portal">
                    @csrf
                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-lg text-sm font-semibold transition-all hover:opacity-80"
                        style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text);"
                    >
                        Manage Subscription
                    </button>
                </form>
            @endif
        </div>
    </div>

    <style>
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>

    {{-- Plans --}}
    <div x-cloak x-data="{ annual: false, loadingPlan: null }">

        {{-- Toggle --}}
        <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 2.5rem;">
            <span style="font-size: 14px; font-weight: 500; transition: color 0.2s;"
                  :style="!annual ? 'color: #f0f0ff' : 'color: rgba(240,240,255,0.4)'">
                Monthly
            </span>
            <div @click="annual = !annual"
                 style="width: 48px; height: 26px; border-radius: 13px; cursor: pointer; position: relative; transition: background 0.25s; flex-shrink: 0;"
                 :style="annual ? 'background: #00C2FF' : 'background: rgba(255,255,255,0.15)'">
                <div style="position: absolute; top: 3px; width: 20px; height: 20px; border-radius: 50%; background: white; transition: left 0.25s; box-shadow: 0 1px 4px rgba(0,0,0,0.3);"
                     :style="annual ? 'left: 25px' : 'left: 3px'">
                </div>
            </div>
            <span style="font-size: 14px; font-weight: 500; transition: color 0.2s;"
                  :style="annual ? 'color: #f0f0ff' : 'color: rgba(240,240,255,0.4)'">
                Annual
            </span>
            <span x-show="annual" x-transition x-cloak
                  style="font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px; background: rgba(0,194,255,0.15); color: #00C2FF; letter-spacing: 0.06em; text-transform: uppercase;">
                Save 20%
            </span>
        </div>

        {{-- Plan cards grid --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
            @php $isCurrentIndie = $user->plan === 'indie'; @endphp
            @php $isCurrentStudio = $user->plan === 'studio'; @endphp
            @php $isCurrentAgency = $user->plan === 'agency'; @endphp
            @php
                $planOrder = ['indie' => 1, 'studio' => 2, 'agency' => 3];
                $currentOrder = $planOrder[$user->plan] ?? 0;
            @endphp

            {{-- INDIE --}}
            <div style="background: #0e0e1a; border: 1px solid {{ $isCurrentIndie ? '#00C2FF' : 'rgba(255,255,255,0.07)' }}; border-radius: 16px; padding: 2rem; display: flex; flex-direction: column;">
                <div style="font-family: 'Bebas Neue', sans-serif; font-size: 22px; letter-spacing: 2px; color: rgba(240,240,255,0.5); margin-bottom: 0.5rem;">INDIE</div>
                <div style="margin-bottom: 1.5rem;">
                    <span style="font-family: 'Bebas Neue', sans-serif; font-size: 52px; color: #f0f0ff; line-height: 1;"
                          x-text="annual ? '$15' : '$19'">$19</span>
                    <span style="font-size: 13px; color: rgba(240,240,255,0.5);"
                          x-text="annual ? '/mo · billed $182/yr' : '/month'">/month</span>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0 0 2rem 0; flex: 1;">
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> 1 app
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Unlimited end users
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Full credit system
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Usage dashboard
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Community support
                    </li>
                </ul>
                @if($isCurrentIndie)
                    <div style="width: 100%; padding: 13px; border-radius: 8px; font-size: 14px; font-weight: 600; text-align: center; background: transparent; color: rgba(240,240,255,0.4); border: 1px solid rgba(255,255,255,0.1);">
                        Current Plan
                    </div>
                @else
                    <form method="POST" action="/billing/subscribe">
                        @csrf
                        <input type="hidden" name="plan" value="indie">
                        <input type="hidden" name="billing_period" :value="annual ? 'annual' : 'monthly'">
                        <button type="submit"
                                :disabled="loadingPlan === 'indie'"
                                @click="loadingPlan = 'indie'"
                                style="width: 100%; padding: 13px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; background: transparent; color: #f0f0ff; border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s;"
                                onmouseover="this.style.borderColor='#00C2FF'; this.style.color='#00C2FF';"
                                onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='#f0f0ff';">
                            <span x-show="loadingPlan !== 'indie'"
                                  x-text="annual ? 'Subscribe — $182/yr' : 'Subscribe — $19/mo'">
                                Subscribe — $19/mo
                            </span>
                            <span x-show="loadingPlan === 'indie'" x-cloak style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <svg style="width:14px;height:14px;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                </svg>
                                Redirecting...
                            </span>
                        </button>
                    </form>
                @endif
            </div>

            {{-- STUDIO (featured) --}}
            <div style="background: #0e0e1a; border: 1px solid #00C2FF; border-radius: 16px; padding: 2rem; display: flex; flex-direction: column; position: relative; box-shadow: 0 -4px 24px rgba(0,194,255,0.12);">
                <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: #00C2FF; color: #080810; font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; padding: 3px 14px; border-radius: 100px; white-space: nowrap;">
                    Most popular
                </div>
                <div style="font-family: 'Bebas Neue', sans-serif; font-size: 22px; letter-spacing: 2px; color: rgba(240,240,255,0.5); margin-bottom: 0.5rem;">STUDIO</div>
                <div style="margin-bottom: 1.5rem;">
                    <span style="font-family: 'Bebas Neue', sans-serif; font-size: 52px; color: #f0f0ff; line-height: 1;"
                          x-text="annual ? '$39' : '$49'">$49</span>
                    <span style="font-size: 13px; color: rgba(240,240,255,0.5);"
                          x-text="annual ? '/mo · billed $468/yr' : '/month'">/month</span>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0 0 2rem 0; flex: 1;">
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> 5 apps
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Unlimited end users
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Everything in Indie
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Multi-model pricing
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Embeddable billing portal
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Priority support
                    </li>
                </ul>
                @if($isCurrentStudio)
                    <div style="width: 100%; padding: 13px; border-radius: 8px; font-size: 14px; font-weight: 600; text-align: center; background: transparent; color: rgba(240,240,255,0.4); border: 1px solid rgba(255,255,255,0.1);">
                        Current Plan
                    </div>
                @else
                    <form method="POST" action="/billing/subscribe">
                        @csrf
                        <input type="hidden" name="plan" value="studio">
                        <input type="hidden" name="billing_period" :value="annual ? 'annual' : 'monthly'">
                        <button type="submit"
                                :disabled="loadingPlan === 'studio'"
                                @click="loadingPlan = 'studio'"
                                style="width: 100%; padding: 13px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; background: #00C2FF; color: #080810; border: none; transition: opacity 0.2s;"
                                onmouseover="this.style.opacity='0.85'"
                                onmouseout="this.style.opacity='1'">
                            <span x-show="loadingPlan !== 'studio'"
                                  x-text="annual ? 'Subscribe — $468/yr' : 'Subscribe — $49/mo'">
                                Subscribe — $49/mo
                            </span>
                            <span x-show="loadingPlan === 'studio'" x-cloak style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <svg style="width:14px;height:14px;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                </svg>
                                Redirecting...
                            </span>
                        </button>
                    </form>
                @endif
            </div>

            {{-- AGENCY --}}
            <div style="background: #0e0e1a; border: 1px solid {{ $isCurrentAgency ? '#00C2FF' : 'rgba(255,255,255,0.07)' }}; border-radius: 16px; padding: 2rem; display: flex; flex-direction: column;">
                <div style="font-family: 'Bebas Neue', sans-serif; font-size: 22px; letter-spacing: 2px; color: rgba(240,240,255,0.5); margin-bottom: 0.5rem;">AGENCY</div>
                <div style="margin-bottom: 1.5rem;">
                    <span style="font-family: 'Bebas Neue', sans-serif; font-size: 52px; color: #f0f0ff; line-height: 1;"
                          x-text="annual ? '$119' : '$149'">$149</span>
                    <span style="font-size: 13px; color: rgba(240,240,255,0.5);"
                          x-text="annual ? '/mo · billed $1,428/yr' : '/month'">/month</span>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0 0 2rem 0; flex: 1;">
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Unlimited apps
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Unlimited end users
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Everything in Studio
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> White-label portal
                    </li>
                    <li style="font-size: 14px; color: rgba(240,240,255,0.6); padding: 5px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="color: #00C2FF;">&#10003;</span> Dedicated support
                    </li>
                </ul>
                @if($isCurrentAgency)
                    <div style="width: 100%; padding: 13px; border-radius: 8px; font-size: 14px; font-weight: 600; text-align: center; background: transparent; color: rgba(240,240,255,0.4); border: 1px solid rgba(255,255,255,0.1);">
                        Current Plan
                    </div>
                @else
                    <form method="POST" action="/billing/subscribe">
                        @csrf
                        <input type="hidden" name="plan" value="agency">
                        <input type="hidden" name="billing_period" :value="annual ? 'annual' : 'monthly'">
                        <button type="submit"
                                :disabled="loadingPlan === 'agency'"
                                @click="loadingPlan = 'agency'"
                                style="width: 100%; padding: 13px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; background: transparent; color: #f0f0ff; border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s;"
                                onmouseover="this.style.borderColor='#00C2FF'; this.style.color='#00C2FF';"
                                onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='#f0f0ff';">
                            <span x-show="loadingPlan !== 'agency'"
                                  x-text="annual ? 'Subscribe — $1,428/yr' : 'Subscribe — $149/mo'">
                                Subscribe — $149/mo
                            </span>
                            <span x-show="loadingPlan === 'agency'" x-cloak style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <svg style="width:14px;height:14px;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                </svg>
                                Redirecting...
                            </span>
                        </button>
                    </form>
                @endif
            </div>

        </div>{{-- end grid --}}

    </div>{{-- end x-data --}}

    <p style="margin-top: 1.5rem; font-size: 14px; text-align: center; color: rgba(240,240,255,0.4);">
        Upgrading takes effect immediately. Downgrading takes effect at the end of your billing period.
    </p>
@endsection
