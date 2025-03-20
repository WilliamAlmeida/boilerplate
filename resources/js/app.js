import './bootstrap';

// import Alpine from 'alpinejs';

import Sortablejs from 'sortablejs';

window.Sortable = Sortablejs;

// window.Alpine = Alpine;

// Alpine.start();

document.addEventListener('livewire:init', () => {
    console.log('livewire:init');

    window.addEventListener('setFocus', function(e){
        if(!e.detail.length) return;

        setTimeout(function () {
            let detail = e.detail[0];
            let element;

            if(detail.query != undefined)   element = document.querySelector(detail.query);
            else if(detail.id != undefined) element = document.getElementById(detail.id);

            if(element) {
                if(detail.select != undefined && detail.select == true) element.select();

                let time = (detail.time != undefined) ? detail.time : 0;
                setTimeout(function () { element.focus() }, time);
            }
        }, 150);
    });
    
    document.addEventListener('keydown', function(event) {
        // Verifica se a tecla pressionada é 'm' e se a tecla Ctrl também está pressionada
        if (event.key === 'm' && event.ctrlKey) {
            Livewire.dispatch('openArtisanPanel');
        }
    });

    // Reconfigure os headers após navegações do Livewire
    document.addEventListener('livewire:navigated', () => {
        console.log('livewire:navigated');
    });

    // Toast event of Mary UI
    Livewire.on('toast', (event) => {
        const { type, title, description, position, icon, css, timeout } = event;

        // Set default values
        const toast = {
            type: type || 'info',
            title: title || 'Notification',
            description: description || null,
            position: position || 'toast-top',
            icon: icon || null,
            css: css || 'alert-info',
            timeout: timeout || 3000
        };

        // Handle different toast types with appropriate defaults
        if (toast.type === 'success' && !icon) {
            toast.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>';
            toast.css = 'alert-success';
        } else if (toast.type === 'warning' && !icon) {
            toast.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>';
            toast.css = 'alert-warning';
        } else if (toast.type === 'error' && !icon) {
            toast.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>';
            toast.css = 'alert-error';
        }else{
            toast.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>';
        }

        window.toast({toast: toast});
    });

    Livewire.on('dialog', (event) => {
        const { title, description, type, position, 
               confirmText, cancelText, confirmOptions, cancelOptions, 
               backdrop, blur, onConfirm, onCancel } = event;

        // Build dialog options
        const dialog = {
            title: title || 'Confirmation',
            description: description || 'Are you sure you want to proceed?',
            position: position || null,
            backdrop: backdrop !== undefined ? backdrop : true,
            blur: blur || false,
            css: type ? 'dialog-' + type : 'dialog-info',
            icon: null
        };

        // Handle different dialog types with appropriate defaults
        if (type === 'dialog-success') {
            dialog.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>';
        } else if (type === 'dialog-warning') {
            dialog.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>';
        } else if (type === 'dialog-error') {
            dialog.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>';
        }else{
            dialog.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>';
        }

        // Handle confirm options - support both simple text and full options
        if (confirmOptions) {
            dialog.confirmOptions = confirmOptions;
        } else if (confirmText || onConfirm) {
            dialog.confirmOptions = {
                text: confirmText || 'Ok',
                method: onConfirm || null
            };
        }
        
        // Handle cancel options - support both simple text and full options
        if (cancelOptions) {
            dialog.cancelOptions = cancelOptions;
        } else if (cancelText || onCancel) {
            dialog.cancelOptions = {
                text: cancelText || 'Cancel',
                method: onCancel || null
            };
        }

        window.dialog({dialog: dialog});
    });
});

document.addEventListener('livewire:initialized', () => {
    console.log('livewire:initialized');
});

window.copyToClipboard = (textToCopy) => {
    const textArea = document.createElement("textarea");
    textArea.value = textToCopy;
    textArea.style.position = "absolute";
    textArea.style.left = "-999999px";
    document.body.prepend(textArea);
    textArea.select();

    try {
        const successful = document.execCommand('copy');
        if (!successful) {
            console.error('Falha ao copiar para a área de transferência.');
            return false;
        }
        return true;
    } catch (error) {
        console.error('Falha ao copiar para a área de transferência:', error);
        return false;
    } finally {
        textArea.remove();
    }
}