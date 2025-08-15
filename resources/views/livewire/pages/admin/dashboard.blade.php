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

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <flux:heading size="xl">Admin Dashboard</flux:heading>
            <p class="mt-1 text-sm text-zinc-500">Verwalten Sie Plant Requests und Contributions</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-6">
                <flux:callout variant="success">
                    {{ session('message') }}
                </flux:callout>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-zinc-900">Pending Plant Requests</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $requestsCount }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <flux:badge size="lg" variant="{{ $requestsCount > 0 ? 'warning' : 'outline' }}">
                            {{ $requestsCount > 0 ? 'Needs Review' : 'All Clear' }}
                        </flux:badge>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-zinc-900">Pending Contributions</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $contributionsCount }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <flux:badge size="lg" variant="{{ $contributionsCount > 0 ? 'warning' : 'outline' }}">
                            {{ $contributionsCount > 0 ? 'Needs Review' : 'All Clear' }}
                        </flux:badge>
                    </div>
                </div>
            </div>
        </div>

        @if($requestsCount == 0 && $contributionsCount == 0)
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <h3 class="text-lg font-medium text-zinc-900 mb-2">Keine ausstehenden Aufgaben</h3>
                <p class="text-zinc-500">Alle Plant Requests und Contributions wurden bearbeitet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Plant Requests -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-zinc-200">
                        <h3 class="text-lg font-medium">Pending Plant Requests</h3>
                    </div>

                    <div class="divide-y divide-zinc-200">
                        @forelse($pendingRequests as $request)
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-semibold text-zinc-900">{{ $request->name }}</h4>
                                        @if($request->latin_name)
                                            <p class="text-sm text-zinc-600 italic">{{ $request->latin_name }}</p>
                                        @endif
                                        <p class="text-xs text-zinc-500 mt-1">
                                            von {{ $request->user->name }} • {{ $request->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <flux:badge size="sm" variant="warning">Pending</flux:badge>
                                </div>

                                @if($request->description)
                                    <p class="text-sm text-zinc-600 mb-3">{{ Str::limit($request->description, 100) }}</p>
                                @endif

                                <div class="bg-zinc-50 rounded p-3 mb-4">
                                    <h5 class="text-xs font-medium text-zinc-700 mb-1">Begründung:</h5>
                                    <p class="text-sm text-zinc-600">{{ $request->reason }}</p>
                                </div>

                                <div class="flex gap-2">
                                    <flux:button
                                        wire:click="approveRequest({{ $request }})"
                                        size="sm"
                                        variant="primary"
                                        wire:confirm="Request genehmigen und Pflanze erstellen?"
                                    >
                                        Genehmigen
                                    </flux:button>
                                    <flux:button
                                        wire:click="rejectRequest({{ $request }})"
                                        size="sm"
                                        variant="danger"
                                        wire:confirm="Request ablehnen?"
                                    >
                                        Ablehnen
                                    </flux:button>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-zinc-500">
                                Keine pending Plant Requests
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Plant Contributions -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-zinc-200">
                        <h3 class="text-lg font-medium">Pending Contributions</h3>
                    </div>

                    <div class="divide-y divide-zinc-200">
                        @forelse($pendingContributions as $contribution)
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-semibold text-zinc-900">{{ $contribution->plant->name }}</h4>
                                        <p class="text-sm text-zinc-600">{{ $contribution->field_display_name }}</p>
                                        <p class="text-xs text-zinc-500 mt-1">
                                            von {{ $contribution->user->name }}
                                            • {{ $contribution->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <flux:badge size="sm" variant="warning">Pending</flux:badge>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div>
                                        <span class="text-xs font-medium text-zinc-500">Aktuell:</span>
                                        <p class="text-sm text-zinc-700">{{ $contribution->current_value ?: 'Leer' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-zinc-500">Vorgeschlagen:</span>
                                        <p class="text-sm text-zinc-700">{{ $contribution->proposed_value }}</p>
                                    </div>
                                </div>

                                @if($contribution->reason)
                                    <div class="bg-zinc-50 rounded p-3 mb-4">
                                        <h5 class="text-xs font-medium text-zinc-700 mb-1">Begründung:</h5>
                                        <p class="text-sm text-zinc-600">{{ $contribution->reason }}</p>
                                    </div>
                                @endif

                                <div class="flex gap-2">
                                    <flux:button
                                        wire:click="approveContribution({{ $contribution }})"
                                        size="sm"
                                        variant="primary"
                                        wire:confirm="Contribution genehmigen und anwenden?"
                                    >
                                        Genehmigen
                                    </flux:button>
                                    <flux:button
                                        wire:click="rejectContribution({{ $contribution }})"
                                        size="sm"
                                        variant="danger"
                                        wire:confirm="Contribution ablehnen?"
                                    >
                                        Ablehnen
                                    </flux:button>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-zinc-500">
                                Keine pending Contributions
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

