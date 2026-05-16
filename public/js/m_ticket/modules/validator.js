// modules/validator.js

export const Validator = {
    checkKilometresMatch() {
        const ticketInput = document.getElementById('kilometres_ticket');
        const speedometerInput = document.getElementById('kilometres_speedometer');

        if (!ticketInput || !speedometerInput) return;

        const ticket = parseFloat(ticketInput.value) || 0;
        const speedometer = parseFloat(speedometerInput.value) || 0;

        if (ticket !== speedometer) {
            this.highlightFields([ticketInput, speedometerInput], true);
            this.showWarning('Значения "км по путевке" и "Всего" должны совпадать!');
        } else {
            this.highlightFields([ticketInput, speedometerInput], false);
            this.hideWarning();
        }
    },

    highlightFields(fields, highlight) {
        fields.forEach(field => {
            if (highlight) {
                field.classList.add('is-warning', 'border-warning');
            } else {
                field.classList.remove('is-warning', 'border-warning');
            }
        });
    },

    showWarning(text) {
        let warningDiv = document.getElementById('kilometres-warning');

        if (!warningDiv) {
            warningDiv = document.createElement('div');
            warningDiv.id = 'kilometres-warning';
            warningDiv.className = 'alert alert-warning alert-dismissible fade show mt-2';

            const fieldset = document.querySelector('#alert_checkKilometresMatch')?.closest('fieldset');
            if (fieldset) fieldset.appendChild(warningDiv);
        }

        warningDiv.innerHTML = `
            <i class="bi bi-exclamation-triangle-fill"></i> 
            ${text}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
    },

    hideWarning() {
        const warningDiv = document.getElementById('kilometres-warning');
        if (warningDiv) warningDiv.remove();
    }
};