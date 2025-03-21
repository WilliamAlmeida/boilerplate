<?php

namespace App\Livewire\Admin\Contratos;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Contratos;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ImportDatas\ImportContratosJob;

class ContratosImport extends Component
{
    public bool $myModal = false;
    use WithFileUploads;
    use Toast;

    #[Rule('required|max:1024')]
    public $file;

    // Progress tracking properties
    public $importing = false;
    public $importedCount = 0;
    public $totalRows = 0;
    public $currentRow = 0;
    public $errors = [];
    public $importComplete = false;

    #[On('import')]
    public function open()
    {
        $this->reset();

        $this->myModal = true;
    }

    public function import()
    {
        $this->validate();

        try {
            $this->reset(['importedCount', 'errors', 'importComplete', 'totalRows', 'currentRow']);
            $this->importing = true;

            $path = $this->file->store('contratos');
            $filePath = Storage::path($path);

            // Check if the file exists
            if (!file_exists($filePath)) {
                $this->error("Import file not found: {$filePath}", position: 'toast-bottom');
                $this->importing = false;
                return;
            }

            // Load the spreadsheet from the file
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Check if the spreadsheet has data
            if (count($rows) <= 1) {
                $this->error("No data found in the import file", position: 'toast-bottom');
                $this->importing = false;
                return;
            }

            // Get headers from the first row
            $headers = array_map('trim', $rows[0]);

            // filter headers with empty values
            $headers = array_filter($headers);

            // Required fields
            $requiredFields = ['CPF', 'CLIENTE', 'PMT', 'PRAZO', 'TAXA ORIGINAL', 'SALDO DEVEDOR'];
            $missingFields = array_diff($requiredFields, $headers);

            if (!empty($missingFields)) {
                $this->error("Missing required fields: " . implode(', ', $missingFields), position: 'toast-bottom');
                $this->importing = false;
                return;
            }

            $this->totalRows = count($rows) - 1; // Exclude header row

            // Process each row (skip header row)
            for ($i = 1; $i < count($rows); $i++) {
                $this->currentRow = $i;
                
                // Get row data and ensure it has the same number of elements as headers
                $rowValues = array_slice(array_pad($rows[$i], count($headers), null), 0, count($headers));
                $rowData = array_combine($headers, $rowValues);

                // Convert headers to lowercase for validation
                $normalizedData = [];
                foreach ($rowData as $key => $value) {
                    $normalizedKey = strtolower(str_replace(' ', '_', $key));
                    $normalizedData[$normalizedKey] = $value;
                }
                
                // Validate the data
                $validator = Validator::make($normalizedData, [
                    'cpf' => 'required|string',
                    'cliente' => 'required|string',
                    'pmt' => 'required|numeric',
                    'prazo' => 'required|integer',
                    'taxa_original' => 'required|numeric',
                    'saldo_devedor' => 'required|numeric',
                ]);

                if ($validator->fails()) {
                    $this->errors[] = "Row {$i}: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Map data to database fields
                $contractData = [
                    'cpf' => $normalizedData['cpf'],
                    'cliente' => $normalizedData['cliente'],
                    'pmt' => $normalizedData['pmt'],
                    'prazo' => $normalizedData['prazo'],
                    'taxa_original' => $normalizedData['taxa_original'],
                    'saldo_devedor' => $normalizedData['saldo_devedor'],
                    'telefone' => $normalizedData['telefone'] ?? null,
                    'banco' => $normalizedData['banco'] ?? null,
                    'obs_1' => $normalizedData['obs_1'] ?? null,
                    'obs_2' => $normalizedData['obs_2'] ?? null,
                    'status' => $normalizedData['status'] ?? null,
                    'producao' => $normalizedData['producao'] ?? 0,
                    'troco_cli' => $normalizedData['troco_cli'] ?? 0,
                    'pos_venda' => $normalizedData['pos_venda'] ?? null,
                    'vendedor' => $normalizedData['vendedor'] ?? null,
                    'data_inclusao' => now(),
                ];

                // Create the contract
                Contratos::create($contractData);
                $this->importedCount++;
            }

            $this->importComplete = true;
            $this->importing = false;

            if (count($this->errors) > 0) {
                $this->warning("Import completed with errors. {$this->importedCount} records imported, " . count($this->errors) . " errors.", position: 'toast-bottom');
            } else {
                $this->success("Import completed successfully. {$this->importedCount} records imported.", position: 'toast-bottom');
            }

            $this->dispatch('table:refresh');

        } catch (\Throwable $th) {
            $this->importing = false;
            $this->error('Error importing file: ' . $th->getMessage(), position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.admin.contratos.contratos-import');
    }
}
