// modules/calculator.js

export const Calculator = {
    // Расчет нормы по формуле: округление(километры * норма / 100)
    calculateNormalValue(kilometres, normal) {
        const km = parseFloat(kilometres) || 0;
        const norm = parseFloat(normal) || 0;
        return Math.round((km * norm) / 100);
    },

    // Обновление всех расчетных полей норм
    updateCalcNormals() {
        const fields = {
            city: { km: 'kilometres_city', norm: 'city', target: 'calc_normal_city' },
            trail: { km: 'kilometres_trail', norm: 'trail', target: 'calc_normal_trail' },
            ground: { km: 'kilometres_ground', norm: 'ground', target: 'calc_normal_ground' },
            linear: { km: 'kilometres_linear', norm: 'linear', target: 'calc_normal_linear' }
        };

        for (const [key, config] of Object.entries(fields)) {
            const km = this.getFieldValue(config.km);
            const norm = window.NormsManager?.getNormal(config.norm) || 0;
            const result = this.calculateNormalValue(km, norm);
            this.setFieldValue(config.target, result);
        }

        // Cargo
        const cargoKm = this.getFieldValue('cargo');
        const cargoNorm = window.NormsManager?.getNormal('cargo') || 0;
        this.setFieldValue('calc_normal_cargo', this.calculateNormalValue(cargoKm, cargoNorm));

        // Pump
        const pumpValue = this.getFieldValue('pump');
        const pumpNorm = window.NormsManager?.getNormal('pump') || 0;
        this.setFieldValue('calc_normal_pump', this.calculateNormalValue(pumpValue, pumpNorm));
    },

    // Обновление км по путевке
    updateTicketKilometres() {
        const city = this.getFieldValue('kilometres_city');
        const trail = this.getFieldValue('kilometres_trail');
        const ground = this.getFieldValue('kilometres_ground');
        const linear = this.getFieldValue('kilometres_linear');

        const total = city + trail + ground + linear;
        this.setFieldValue('kilometres_ticket', total);

        this.updateCalcNormals();
        window.Validator?.checkKilometresMatch();
    },

    // Обновление cargo и cargo-no
    updateCargoNo() {
        const cargo = this.getFieldValue('cargo');
        const speedometer = this.getFieldValue('kilometres_speedometer');

        const cargoNo = speedometer - cargo;
        this.setFieldValue('cargo-no', cargoNo);

        this.updateCalcNormals();
        window.Validator?.checkKilometresMatch();
    },

    // Обновление выполненной работы
    updateCompletedWork() {
        const completedWork = this.getFieldValue('completed_work');
        const cargo = this.getFieldValue('cargo');

        const tonKm = completedWork * cargo;
        this.setFieldValue('completed_work_km', tonKm);
    },

    getFieldValue(id) {
        const el = document.getElementById(id);
        return parseFloat(el?.value) || 0;
    },

    setFieldValue(id, value) {
        const el = document.getElementById(id);
        if (el) el.value = value;
    }
};