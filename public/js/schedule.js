function initDisabled() {
    // Обход по дням, если прошедшая дата, то нужно задать disabled

// Получаем текущий месяц, год и день
    let curMonth = new Date().toLocaleString('ru', {month: 'long'})
    curMonth = curMonth.charAt(0).toUpperCase() + curMonth.slice(1)
    let curYear = new Date().getFullYear()
    let curDay = new Date().getDate()
    curMonthYear = curMonth.toString() + ' ' + curYear.toString()

// узнаем дату, переданную в select месяца
    let selectDayMonthObj = document.querySelector('.select-block :first-child select')
    let selectDayMonth = selectDayMonthObj.options[selectDayMonthObj.selectedIndex].text

// Если дата (без дня) совпадает с текущей, все дни disabled
    let dayDocs = document.querySelectorAll('.btn')
    if (selectDayMonth < curMonthYear) {
        dayDocs.forEach((el) => {
            el.classList.add('disabled-button')
        })
        // иначе если даты совпадают, проходимся по каждой строке, проверяя
        // на прошедщие дни
    } else if (selectDayMonth === curMonthYear) {
        let rows = document.querySelectorAll('[column-key="' + 1 + '"]')
        let dayDocsArr = [...dayDocs]
        rows.forEach((row) => {
            if (row.textContent.trim() <= curDay) {
                let currDayDocs = dayDocsArr.filter(dayDoc => dayDoc.getAttribute('row-key') === row.getAttribute('row-key'))

                currDayDocs.forEach((el) => {
                    el.classList.add('disabled-button')
                })
            }
        })
    }
}

// submit function
function get_all_selected() {
    let matrixElements = document.querySelectorAll('.btn')

    let selectDayMonthObj = document.querySelector('.select-block :first-child select')
    let selectDayMonth = selectDayMonthObj.options[selectDayMonthObj.selectedIndex].text

    let postData = []
    // let formData = new FormData()
    matrixElements.forEach((matrixElement) => {
        if (matrixElement.getAttribute('select') === 'true') {
            let column_key = matrixElement.getAttribute('column-key')
            let row_key = matrixElement.getAttribute('row-key')

            let row = document.querySelector('[row-key="' + row_key + '"][column-key="' + 1 + '"]')
            let column = document.querySelector('[row-key="' + 1 + '"][column-key="' + column_key + '"]')

            let firstCustomSelect = document.querySelector('.select-block :nth-child(1) select')

            let n = firstCustomSelect.selectedIndex
            let monthYear = null
            if (n) monthYear = firstCustomSelect.options[n].value

            postData.push({
                "month_year": monthYear,
                "day": row.textContent.trim(),
                "column_id": column.getAttribute('key')
            })
        }
    })

    let hrefPost = window.location.origin + window.location.pathname + ('/new_' + window.location.pathname.slice(1))
    $.ajax({
        type: "POST",
        url: hrefPost,
        data: {'data': postData},
        dataType: "json",
        success: function (result) {
            alert(result);
            window.location.reload()
        }
    });

}

initDisabled()