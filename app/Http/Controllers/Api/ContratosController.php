<?php

namespace App\Http\Controllers\Api;

use App\Models\Clientes;
use App\Models\Contratos;
use App\Models\Vendedores;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\EnumContratoStatus;
use App\Http\Resources\ResourceContrato;
use App\Http\Controllers\Api\BaseController;

/**
 * @tags Contratos
 */
class ContratosController extends BaseController
{
    /**
     * Create a new contract
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            /**
             * The client's CPF
             * @example 12345678901
             */
            'cpf' => 'required|string|max:14',
            /**
             * The client's name
             * @example John Doe
             */
            'cliente' => 'nullable|string|max:100',
            /**
             * The client ID in the database
             * @example 1
             * @default null
             */
            'cliente_id' => 'nullable|exists:clientes,id',
            /**
             * Monthly payment value
             * @example 500.00
             */
            'pmt' => 'required|numeric|decimal:0,2',
            /**
             * Contract term in months
             * @example 36
             */
            'prazo' => 'required|integer',
            /**
             * Original interest rate
             * @example 1.99
             */
            'taxa_original' => 'required|numeric|decimal:0,2',
            /**
             * Outstanding balance
             * @example 10000.00
             */
            'saldo_devedor' => 'required|numeric|decimal:0,2',
            /**
             * Client phone number
             * @example 11982184877
             * @default null
             */
            'telefone' => 'nullable|string|max:20',
            /**
             * First observation
             * @example Client prefers contact by email
             * @default null
             */
            'obs_1' => 'nullable|string',
            /**
             * Second observation
             * @example Special conditions applied
             * @default null
             */
            'obs_2' => 'nullable|string',
            /**
             * Contract status
             * @example aprovado
             */
            'status' => 'required|string|max:40',
            /**
             * Production value
             * @example 1000.00
             * @default 0
             */
            'producao' => 'nullable|numeric|decimal:0,2',
            /**
             * Client change
             * @example 100.00
             * @default null
             */
            'troco_cli' => 'nullable|numeric|decimal:0,2',
            /**
             * Post-sale information
             * @example Follow up after 30 days
             * @default null
             */
            'pos_venda' => 'nullable|string|max:50',
            /**
             * Seller name
             * @example Jane Smith
             * @default null
             */
            'vendedor' => 'nullable|string|max:100',
            /**
             * Seller ID in the database
             * @example 1
             * @default null
             */
            'vendedor_id' => 'nullable|exists:vendedores,id',
            /**
             * Bank profile
             * @example Banco do Brasil
             * @default null
             */
            'banco_perfil' => 'nullable|string|max:50',
            /**
             * Product
             * @example Loan
             * @default null
             */
            'produto' => 'nullable|string|max:50',
            /**
             * Table
             * @example 2.5
             * @default null
             */
            'tabela' => 'nullable|numeric|decimal:0,2',
            /**
             * Financed amount
             * @example 15000.00
             * @default 0
             */
            'financiado' => 'nullable|numeric|decimal:0,2',
        ]);

        try {
            // Process status if provided
            if(isset($validated['status'])) {
                try {
                    $validated['status'] = EnumContratoStatus::from(Str::of($validated['status'])->lower()->slug('-')->__toString())->value;
                } catch (\Throwable $th) {
                    $validated['status'] = null;
                }
            }

            // Create or select client if not provided by ID
            if(!isset($validated['cliente_id']) && isset($validated['cliente'])) {
                $validated['cliente_id'] = $this->createOrSelectClient(
                    $validated['cliente'], 
                    $validated['cpf'], 
                    $validated['telefone'] ?? null
                );
            }

            // Create or select vendedor if not provided by ID
            if(!isset($validated['vendedor_id']) && isset($validated['vendedor'])) {
                $validated['vendedor_id'] = $this->createOrSelectVendor($validated['vendedor']);
            }

            // Set data_inclusao
            $validated['data_inclusao'] = now();

            $contrato = Contratos::create($validated);

            return response()->json([
                'status' => 'success',
                'code' => 201,
                'data' => new ResourceContrato\ContratoResource($contrato)
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Create or select a client
     * 
     * @param string $clientName
     * @param string $cpf
     * @param string|null $phone
     * @return int Client ID
     */
    private function createOrSelectClient(string $clientName, string $cpf, ?string $phone): int
    {
        $cliente = Clientes::where('nome_fantasia', $clientName)->first();
        
        if (!$cliente) {
            $cliente = Clientes::create([
                'tipo' => 'Físico',
                'cpf' => $cpf,
                'nome_fantasia' => $clientName,
                'razao' => $clientName,
            ]);

            if($phone) {
                $cliente->numeros()->create([
                    'tipo' => 'c',
                    'numero' => $phone,
                ]);
            }
        }
        
        return $cliente->id;
    }

    /**
     * Create or select a vendor
     * 
     * @param string $vendorName
     * @return int|null Vendor ID
     */
    private function createOrSelectVendor(?string $vendorName): ?int
    {
        if (!$vendorName) {
            return null;
        }

        $vendedor = Vendedores::where('nome', $vendorName)->first();
        
        if (!$vendedor) {
            $vendedor = Vendedores::create([
                'nome' => $vendorName,
            ]);
        }
        
        return $vendedor->id;
    }
}