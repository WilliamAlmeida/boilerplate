<div class="space-y-2" 
    x-data="{ 
        tipos: {{ json_encode($tipos_numeros) }},
        numeros: $wire.entangle('form.numeros'),
        newNumero: { tipo: '', numero: '' },

        addNumero() {
            if (!this.newNumero.numero) {
                return;
            }

            const found = this.numeros.filter(n => n.numero === this.newNumero.numero);
            if(found.length > 0) {
                alert('Este número já está cadastrado!');
                return;
            }

            this.numeros.push({...this.newNumero});
            this.newNumero.tipo = '';
            this.newNumero.numero = '';
        },
        
        removeNumero(index) {
            this.numeros.splice(index, 1);
        },
        
        getTipoName(tipo = '') {
            return this.tipos.find(t => t.id === tipo)?.name || '';
        }
    }"
>
    {{-- Números Section --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <x-select 
            label="Tipo de Número"
            x-model="newNumero.tipo"
            :options="$tipos_numeros"
            option-label="name"
            option-value="id"
            class="select-sm"
        />
        <x-input
            label="Número"
            x-model="newNumero.numero"
            type="text"
            x-mask="(99) 9999-99999"
            class="input-sm"
        />
        <div class="col-span-2 pr-2 lg:pr-0 lg:col-auto flex lg:items-end justify-end lg:justify-normal">
            <x-button 
                icon="o-plus"
                label="Adicionar"
                responsive
                x-on:click="addNumero"
                class="btn-primary btn-sm"
            />
        </div>
    </div>

    {{-- Lista de Números --}}
    <template x-for="(numero, index) in numeros" :key="index">
        <div class="flex gap-4 justify-between items-center bg-gray-50 p-2 rounded">
            <div class="*:text-sm">
                <div x-text="getTipoName(numero.tipo)"></div>
                <div x-text="numero.numero"></div>
            </div>
            <x-button 
                icon="o-trash"
                label="Remover"
                responsive
                x-on:click="removeNumero(index)"
                class="btn-error btn-sm"
            />
        </div>
    </template>
</div>