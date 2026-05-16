// modules/navigation.js

export const Navigation = {
    openUrl(idModelMachine, monthParam, yearParam) {
        const prevBtn = document.getElementById('prevMonth');
        const nextBtn = document.getElementById('nextMonth');
        const monthElement = document.getElementById('currentMonthYear');

        if (!prevBtn || !nextBtn || !monthElement) {
            console.error('Не найдены элементы календаря');
            return;
        }

        let currentDate = new Date();
        currentDate.setFullYear(parseInt(yearParam));

        if (monthParam && monthParam !== '') {
            const monthNumber = parseInt(monthParam);
            if (!isNaN(monthNumber) && monthNumber >= 1 && monthNumber <= 12) {
                currentDate.setMonth(monthNumber - 1);
            }
        }

        const navigateToMonth = (offset) => {
            currentDate.setMonth(currentDate.getMonth() + offset);
            const monthForUrl = currentDate.getMonth() + 1;
            const yearForUrl = currentDate.getFullYear();
            window.location.href = `/military-ticket/${idModelMachine}/${monthForUrl}/${yearForUrl}`;
        };

        // Заменяем обработчики
        const newPrevBtn = prevBtn.cloneNode(true);
        const newNextBtn = nextBtn.cloneNode(true);
        prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
        nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);

        newPrevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            navigateToMonth(-1);
        });

        newNextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            navigateToMonth(1);
        });

        this.renderCalendar(currentDate, monthElement);
    },

    openUrlEditTicket(idModelMachine, monthParam, yearParam, id) {
        window.location.href = `/military-ticket/edit/${idModelMachine}/${monthParam}/${yearParam}/${id}`;
    },

    renderCalendar(date, monthElement) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const monthNames = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        monthElement.textContent = `${monthNames[month]} ${year}`;
    }
};