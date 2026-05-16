// modules/normsManager.js

export const NormsManager = {
    normsData: [],

    init() {
        const normsElement = document.getElementById('military-norms-data');
        if (normsElement && normsElement.dataset.norms) {
            try {
                this.normsData = JSON.parse(normsElement.dataset.norms);
            } catch (e) {
                console.error('Ошибка парсинга данных норм:', e);
            }
        }
        return this.normsData;
    },

    getById(id) {
        return this.normsData.find(norm => norm.id == id);
    },

    loadBySelect() {
        const normSelect = document.getElementById('m_norm');
        if (!normSelect?.value) return;

        const selectedNorm = this.getById(normSelect.value);
        if (selectedNorm) {
            this.updateSpans(selectedNorm);
            // Обновляем расчеты
            if (window.Calculator) {
                window.Calculator.updateCalcNormals();
            }
        }
    },

    updateSpans(norm) {
        const fields = ['city', 'trail', 'ground', 'linear', 'cargo', 'pump'];
        fields.forEach(field => {
            const span = document.getElementById(`normal_${field}`);
            if (span) span.textContent = norm[field] || '0';
        });
    },

    getNormal(type) {
        const span = document.getElementById(`normal_${type}`);
        return parseFloat(span?.textContent) || 0;
    }
};