<div class="space-y-2" 
    x-data="{ 
        tipos: {{ json_encode($tipos_emails) }},
        emails: $wire.entangle('form.emails'),
        newEmail: { tipo: '', email: '' },

        addEmail() {
            if (!this.newEmail.email) {
                return;
            }

            const found = this.emails.filter(e => e.email === this.newEmail.email);
            if(found.length > 0) {
                alert('Este e-mail já está cadastrado!');
                return;
            }

            this.emails.push({...this.newEmail});
            this.newEmail.tipo = '';
            this.newEmail.email = '';
        },
        
        removeEmail(index) {
            this.emails.splice(index, 1);
        },
        
        getTipoName(tipo = '') {
            return this.tipos.find(t => t.id === tipo)?.name || '';
        }
    }"
>
    {{-- Emails Section --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <x-select 
            label="Tipo de Email"
            x-model="newEmail.tipo"
            :options="$tipos_emails"
            option-label="name"
            option-value="id"
            class="select-sm"
        />
        <x-input
            label="E-mail"
            x-model="newEmail.email"
            type="email"
            class="input-sm"
        />
        <div class="col-span-2 pr-2 lg:pr-0 lg:col-auto flex lg:items-end justify-end lg:justify-normal">
            <x-button 
                icon="o-plus"
                label="Adicionar"
                responsive
                x-on:click="addEmail"
                class="btn-primary btn-sm"
            />
        </div>
    </div>

    {{-- Lista de Emails --}}
    <template x-for="(email, index) in emails" :key="index">
        <div class="flex gap-4 justify-between items-center bg-gray-50 p-2 rounded">
            <div class="*:text-sm">
                <div x-text="getTipoName(email.tipo)"></div>
                <div x-text="email.email"></div>
            </div>
            <div>
                <x-button 
                    icon="o-trash"
                    label="Remover"
                    responsive
                    x-on:click="removeEmail(index)"
                    class="btn-error btn-sm"
                />
            </div>
        </div>
    </template>
</div>