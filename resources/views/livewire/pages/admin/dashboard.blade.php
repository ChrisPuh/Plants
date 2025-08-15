<?php

use App\Models\PlantRequest;
use App\Models\PlantContribution;
use Livewire\Volt\Component;

new class extends Component {
    public function mount(): void
    {
        $this->authorize('viewAny', PlantRequest::class);
    }

    public function with(): array
    {
        return [
            'pendingRequests' => PlantRequest::with('user')
                ->where('status', 'pending')
                ->latest()
                ->take(10)
                ->get(),
            'pendingContributions' => PlantContribution::with(['user', 'plant'])
                ->where('status', 'pending')
                ->latest()
                ->take(10)
                ->get(),
            'requestsCount' => PlantRequest::where('status', 'pending')->count(),
            'contributionsCount' => PlantContribution::where('status', 'pending')->count(),
        ];
    }

    public function approveRequest(PlantRequest $request): void
    {
        $this->authorize('update', $request);

        $request->approve(auth()->user(), 'Automatisch genehmigt');
        $plant = $request->createPlantFromRequest();

        session()->flash('message', "Request genehmigt und Pflanze '{$plant->name}' erstellt!");
    }

    public function rejectRequest(PlantRequest $request): void
    {
        $this->authorize('update', $request);

        $request->reject(auth()->user(), 'Abgelehnt über Admin Dashboard');

        session()->flash('message', 'Request abgelehnt.');
    }

    public function approveContribution(PlantContribution $contribution): void
    {
        $this->authorize('update', $contribution);

        $contribution->approve(auth()->user(), 'Automatisch genehmigt');
        $contribution->applyToPlant();

        session()->flash('message', "Beitrag genehmigt und auf Pflanze '{$contribution->plant->name}' angewendet!");
    }

    public function rejectContribution(PlantContribution $contribution): void
    {
        $this->authorize('update', $contribution);

        $contribution->reject(auth()->user(), 'Abgelehnt über Admin Dashboard');

        session()->flash('message', 'Beitrag abgelehnt.');
    }
}; ?>

<div>
    <x-plants.layout 
        heading="Admin Dashboard" 
        subheading="Verwalten Sie Plant Requests und Contributions"
        :show-navigation="false">

        @if (session()->has('message'))
            <x-alert type="success" class="mb-6">
                {{ session('message') }}
            </x-alert>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="card p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-text-primary">Pending Plant Requests</h3>
                        <p class="text-3xl font-bold text-info-600">{{ $requestsCount }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge {{ $requestsCount > 0 ? 'badge-warning' : 'badge-success' }}">
                            {{ $requestsCount > 0 ? 'Needs Review' : 'All Clear' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-text-primary">Pending Contributions</h3>
                        <p class="text-3xl font-bold text-success-600">{{ $contributionsCount }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge {{ $contributionsCount > 0 ? 'badge-warning' : 'badge-success' }}">
                            {{ $contributionsCount > 0 ? 'Needs Review' : 'All Clear' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if($requestsCount == 0 && $contributionsCount == 0)
            <div class="card p-8 text-center">
                <h3 class="text-lg font-medium text-text-primary mb-2">Keine ausstehenden Aufgaben</h3>
                <p class="text-text-secondary">Alle Plant Requests und Contributions wurden bearbeitet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Plant Requests -->
                <div class="card">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="text-lg font-medium text-text-primary">Pending Plant Requests</h3>
                    </div>

                    <div class="divide-y divide-border">
                        @forelse($pendingRequests as $request)
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-semibold text-text-primary">{{ $request->name }}</h4>
                                        @if($request->latin_name)
                                            <p class="text-sm text-text-secondary italic">{{ $request->latin_name }}</p>
                                        @endif
                                        <p class="text-xs text-text-muted mt-1">
                                            von {{ $request->user->name }} • {{ $request->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span class="badge badge-warning text-xs">Pending</span>
                                </div>

                                @if($request->description)
                                    <p class="text-sm text-text-secondary mb-3">{{ Str::limit($request->description, 100) }}</p>
                                @endif

                                <div class="surface-secondary rounded p-3 mb-4">
                                    <h5 class="text-xs font-medium text-text-secondary mb-1">Begründung:</h5>
                                    <p class="text-sm text-text-primary">{{ $request->reason }}</p>
                                </div>

                                <div class="flex gap-2">
                                    <button 
                                        wire:click="approveRequest({{ $request }})"
                                        wire:confirm="Request genehmigen und Pflanze erstellen?"
                                        class="btn-primary text-sm px-3 py-1.5"
                                    >
                                        Genehmigen
                                    </button>
                                    <button 
                                        wire:click="rejectRequest({{ $request }})"
                                        wire:confirm="Request ablehnen?"
                                        class="btn-secondary text-sm px-3 py-1.5 !text-error-600 !border-error-200 hover:!bg-error-50"
                                    >
                                        Ablehnen
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-text-muted">
                                Keine pending Plant Requests
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Plant Contributions -->
                <div class="card">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="text-lg font-medium text-text-primary">Pending Contributions</h3>
                    </div>

                    <div class="divide-y divide-border">
                        @forelse($pendingContributions as $contribution)
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-semibold text-text-primary">{{ $contribution->plant->name }}</h4>
                                        <p class="text-sm text-text-secondary">{{ $contribution->field_display_name }}</p>
                                        <p class="text-xs text-text-muted mt-1">
                                            von {{ $contribution->user->name }}
                                            • {{ $contribution->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span class="badge badge-warning text-xs">Pending</span>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div>
                                        <span class="text-xs font-medium text-text-secondary">Aktuell:</span>
                                        <p class="text-sm text-text-primary">{{ $contribution->current_value ?: 'Leer' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-text-secondary">Vorgeschlagen:</span>
                                        <p class="text-sm text-text-primary">{{ $contribution->proposed_value }}</p>
                                    </div>
                                </div>

                                @if($contribution->reason)
                                    <div class="surface-secondary rounded p-3 mb-4">
                                        <h5 class="text-xs font-medium text-text-secondary mb-1">Begründung:</h5>
                                        <p class="text-sm text-text-primary">{{ $contribution->reason }}</p>
                                    </div>
                                @endif

                                <div class="flex gap-2">
                                    <button 
                                        wire:click="approveContribution({{ $contribution }})"
                                        wire:confirm="Contribution genehmigen und anwenden?"
                                        class="btn-primary text-sm px-3 py-1.5"
                                    >
                                        Genehmigen
                                    </button>
                                    <button 
                                        wire:click="rejectContribution({{ $contribution }})"
                                        wire:confirm="Contribution ablehnen?"
                                        class="btn-secondary text-sm px-3 py-1.5 !text-error-600 !border-error-200 hover:!bg-error-50"
                                    >
                                        Ablehnen
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-text-muted">
                                Keine pending Contributions
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </x-plants.layout>
</div>

