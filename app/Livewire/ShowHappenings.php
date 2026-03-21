<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Happening;
use App\Mail\HappeningRemarksNotification;
use Illuminate\Support\Facades\Mail;

class ShowHappenings extends Component
{
    public $happenings;
    public $selectedHappening = null;
    public $acceptedHappenings = [];
    public $remarks = '';

    public function mount()
    {
        $this->loadHappenings();
    }

    public function showDetails($id)
    {
        $this->selectedHappening = Happening::with(['status', 'theme'])->find($id);
        $this->remarks = $this->selectedHappening->remarks ?? '';
    }

    public function closeDetails()
    {
        $this->selectedHappening = null;
        $this->remarks = '';
    }

    public function saveRemarks()
    {
        if (!$this->selectedHappening) {
            return;
        }

        $this->selectedHappening->update(['remarks' => $this->remarks]);
        $this->sendRemarksEmail($this->selectedHappening);

        $this->loadHappenings();
        $this->dispatch('remarks-saved', 'Opmerkingen succesvol opgeslagen!');
        $this->closeDetails();
    }

    public function delete($id)
    {
        Happening::find($id)?->delete();

        if ($this->selectedHappening && $this->selectedHappening->id === $id) {
            $this->closeDetails();
        }

        $this->loadHappenings();
        $this->dispatch('happening-deleted', 'Evenement succesvol verwijderd.');
    }

    public function acceptHappening($id)
    {
        if (($key = array_search($id, $this->acceptedHappenings)) !== false) {
            unset($this->acceptedHappenings[$key]);
        } else {
            $this->acceptedHappenings[] = $id;
        }
    }

    public function render()
    {
        return view('livewire.show-happenings')
            ->layout('components.layouts.app', ['title' => 'Evenementen Beheer']);
    }

    /**
     * Helper-functie om de lijst met happenings opnieuw te laden.
     */
    private function loadHappenings(): void
    {
        $this->happenings = Happening::with(['status', 'theme'])->latest()->get();
    }

    /**
     * Helper-functie om de opmerkingen-e-mail te versturen.
     */
    private function sendRemarksEmail(Happening $happening): void
    {
        $happening->load('user');

        if (!$happening->user?->email) {
            \Log::warning('No user or email found for happening ' . $happening->id . '. Email not sent.');
            return;
        }

        try {
            // =========================================================================
            // TIJDELIJK VOOR TESTEN: Stuur de mail altijd naar het geconfigureerde adres
            // In een productie-omgeving zou je hier $happening->user->email gebruiken.
            // =========================================================================
            $testRecipient = config('mail.mailers.smtp.username');

            Mail::to($testRecipient)
                ->send(new HappeningRemarksNotification($happening, $this->remarks));

            \Log::info('Email sent for happening ' . $happening->id . ' to test recipient ' . $testRecipient);

        } catch (\Exception $e) {
            \Log::error('Failed to send email for happening ' . $happening->id . ': ' . $e->getMessage());
        }
    }
}
