window.addEventListener("click", (e) => {
    let target = e.target;

    if (target.classList.contains('btn')) {
        console.log(target)

        // устанавливаем цвет кнопки
        if (target.classList.contains('btn-success')) {
            // заменяем цвет кнопки
            target.classList.remove("btn-success");
            target.classList.add('btn-default');

            // ставим кнопке статус 'не выбрано'
            target.setAttribute('select', false)
        } else {
            target.classList.remove('btn-default');
            target.classList.add('btn-success');
            target.setAttribute('select', true)
        }
    }

    // выбор всего столбца
    if (target.classList.contains('doctors')) {
        let column_key = target.getAttribute('column-key');

        // устанавливаем цвет всех строк колонки врача
        let rows = document.querySelectorAll('[column-key="' + column_key + '"]')

        rows.forEach((row) => {
            if (row.classList.contains('btn-success') && !row.classList.contains('disabled-button')) {
                // заменяем цвет кнопки
                row.classList.remove("btn-success");
                row.classList.add('btn-default');

                // ставим кнопке статус 'не выбрано'
                row.setAttribute('select', false)
            } else if (!row.classList.contains('disabled-button')) {
                row.classList.remove('btn-default');
                row.classList.add('btn-success');
                row.setAttribute('select', true)
            }
        })
    }

    // Выбор всей строки
    if (target.classList.contains('days')) {
        let row_key = target.getAttribute('row-key');

        // устанавливаем цвет всех строк колонки врача
        let columns = document.querySelectorAll('[row-key="' + row_key + '"]')
        console.log(columns)

        columns.forEach((column) => {
            if (column.classList.contains('btn-success') && !column.classList.contains('disabled-button')) {
                // заменяем цвет кнопки
                column.classList.remove("btn-success");
                column.classList.add('btn-default');

                // ставим кнопке статус 'не выбрано'
                column.setAttribute('select', false)
            } else if (!column.classList.contains('disabled-button')) {
                column.classList.remove('btn-default');
                column.classList.add('btn-success');
                column.setAttribute('select', true)
            }
        })
    }
}, false)