<?php

namespace App\Livewire\Admin\Contratos;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Clientes;
use App\Models\Contratos;
use App\Models\Vendedores;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Enums\EnumContratoStatus;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
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

    public $arr_status = [];

    public function mount()
    {
        $this->arr_status = collect(EnumContratoStatus::cases())->mapWithKeys(fn($item) => [$item->value => $item->name]);
    }

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

                // Create or select the cliente
                $cliente = $this->createOrSelectCliente($normalizedData);

                // Create or select the vendedor
                $vendedor = $this->createOrSelectVendedor($normalizedData);

                try {
                    $status = EnumContratoStatus::from(Str::of($normalizedData['status'])->lower()->slug('-')->__toString())->value;
                } catch (\Throwable $th) {
                    $status = null;
                }

                // Map data to database fields
                $contractData = [
                    'cpf' => $normalizedData['cpf'],
                    'cliente' => $normalizedData['cliente'],
                    'cliente_id' => $cliente->id ?? null,
                    'pmt' => floatval($normalizedData['pmt']),
                    'prazo' => intval($normalizedData['prazo']),
                    'taxa_original' => floatval($normalizedData['taxa_original']),
                    'saldo_devedor' => floatval($normalizedData['saldo_devedor']),
                    'telefone' => $normalizedData['telefone'] ?? null,
                    'obs_1' => $normalizedData['obs_1'] ?? null,
                    'obs_2' => $normalizedData['obs_2'] ?? null,
                    'status' => $status,
                    'producao' => floatval($normalizedData['producao'] ?? 0),
                    'troco_cli' => floatval($normalizedData['troco_cli'] ?? 0),
                    'pos_venda' => $normalizedData['pos_venda'] ?? null,
                    'vendedor' => $normalizedData['vendedor'] ?? null,
                    'vendedor_id' => $vendedor->id ?? null,
                    'banco_perfil' => $normalizedData['banco'] ?? null,
                    'produto' => $normalizedData['produto'] ?? null,
                    'tabela' => $normalizedData['tabela'] ?? null,
                    'financiado' => $normalizedData['financiado'] ?? null,
                    // 'data_inclusao' => $normalizedData['data_inclusao'] ?? null,
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
            throw $th;
            $this->error('Error importing file: ' . $th->getMessage(), position: 'toast-bottom');
        }
    }

    private function createOrSelectCliente(array $values)
    {
        $cliente = Clientes::where('nome_fantasia', $values['cliente'])->first();

        if (!$cliente) {
            $cliente = Clientes::create([
                'tipo' => 'Físico',
                'cpf' => $values['cpf'],
                'nome_fantasia' => $values['cliente'],
                'razao' => $values['cliente'],
            ]);

            if(isset($values['telefone'])) {
                $cliente->numeros()->create([
                    'tipo' => 'c',
                    'numero' => $values['telefone'],
                ]);
            }

            if(isset($values['email'])) {
                $cliente->emails()->create([
                    'tipo' => '',
                    'email' => $values['email'],
                ]);
            }
        }

        return $cliente;
    }

    private function createOrSelectVendedor(array $values)
    {
        $vendedor = Vendedores::where('nome', $values['vendedor'])->first();

        if (!$vendedor) {
            $vendedor = Vendedores::create([
                'nome' => $values['vendedor'],
            ]);
        }

        return $vendedor;
    }

    public function render()
    {
        return view('livewire.admin.contratos.contratos-import');
    }
}
