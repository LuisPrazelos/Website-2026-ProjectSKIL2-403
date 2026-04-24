<?php

namespace App\Livewire;

use App\Models\Package;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PackageManager extends Component
{
    public $packages = [];
    public $showForm = false;
    public $editingPackageId = null;
    public $packageName = '';
    public $packageDescription = '';
    public $packagePrice = '';
    public $isStandard = false;
    public $searchQuery = '';

    public function mount()
    {
        $this->loadPackages();
    }

    public function loadPackages()
    {
        $query = Package::query();

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $this->packages = $query
            ->orderByDesc('is_standard')
            ->orderBy('name')
            ->get()
            ->map(fn (Package $package) => [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description,
                'price' => $package->price,
                'is_standard' => $package->is_standard,
                'created_at' => $package->created_at,
            ])
            ->toArray();
    }

    public function updatedSearchQuery()
    {
        $this->loadPackages();
    }

    public function updatedIsStandard($value)
    {
        if ($value) {
            $this->packagePrice = '0.00';
        }
    }

    public function openCreateForm()
    {
        $this->showForm = true;
        $this->editingPackageId = null;
        $this->packageName = '';
        $this->packageDescription = '';
        $this->packagePrice = '';
        $this->isStandard = false;
    }

    public function editPackage($packageId)
    {
        $package = Package::findOrFail($packageId);

        $this->editingPackageId = $package->id;
        $this->packageName = $package->name;
        $this->packageDescription = $package->description ?? '';
        $this->packagePrice = number_format((float) $package->price, 2, '.', '');
        $this->isStandard = $package->is_standard;
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->editingPackageId = null;
        $this->packageName = '';
        $this->packageDescription = '';
        $this->packagePrice = '';
        $this->isStandard = false;
    }

    public function savePackage()
    {
        $validated = $this->validate($this->rules());

        $newPrice = $validated['isStandard'] ? 0 : (float) $validated['packagePrice'];
        $today = now()->format('Y-m-d');

        if ($this->editingPackageId) {
            $package = Package::findOrFail($this->editingPackageId);
            $history = $package->price_history ?? [];

            // Only add to history if price has changed
            if ((float) $package->price !== $newPrice) {
                // Check if we already have an entry for today, if so update it, else append
                $foundToday = false;
                foreach ($history as &$entry) {
                    if ($entry['date'] === $today) {
                        $entry['price'] = $newPrice;
                        $foundToday = true;
                        break;
                    }
                }
                if (!$foundToday) {
                    $history[] = ['date' => $today, 'price' => $newPrice];
                }
            }

            $package->update([
                'name' => $validated['packageName'],
                'description' => $validated['packageDescription'] ?: null,
                'price' => $newPrice,
                'is_standard' => $validated['isStandard'],
                'price_history' => $history,
            ]);
            
            if ($validated['isStandard']) {
                Package::where('id', '!=', $package->id)->update(['is_standard' => false]);
            }

            $this->dispatch('package-updated', 'Pakket succesvol bijgewerkt!');
        } else {
            $package = Package::create([
                'name' => $validated['packageName'],
                'description' => $validated['packageDescription'] ?: null,
                'price' => $newPrice,
                'is_standard' => $validated['isStandard'],
                'price_history' => [['date' => $today, 'price' => $newPrice]],
            ]);

            if ($validated['isStandard']) {
                Package::where('id', '!=', $package->id)->update(['is_standard' => false]);
            }

            $this->dispatch('package-created', 'Pakket succesvol aangemaakt!');
        }

        $this->closeForm();
        $this->loadPackages();
    }

    public function deletePackage($packageId)
    {
        try {
            Package::findOrFail($packageId)->delete();
            $this->dispatch('package-deleted', 'Pakket succesvol verwijderd!');
            $this->loadPackages();
        } catch (\Exception $e) {
            $this->dispatch('error', 'Er is een fout opgetreden bij het verwijderen van het pakket.');
        }
    }

    public function render()
    {
        return view('livewire.package-manager')
            ->layout('components.layouts.app', ['title' => 'Pakketten Beheren']);
    }

    protected function rules(): array
    {
        return [
            'packageName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('packages', 'name')->ignore($this->editingPackageId),
            ],
            'packageDescription' => 'nullable|string',
            'packagePrice' => [
                Rule::requiredIf(!$this->isStandard),
                'nullable',
                'numeric',
                'min:0',
            ],
            'isStandard' => 'boolean',
        ];
    }
}
