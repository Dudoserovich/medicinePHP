addEventListener(`change`, async e => {
    const $select = e.target;

    if (!$select.classList.contains(`custom-select`)) return;

    let href = $select.getAttribute('data-action')
    let customSelects = [...document.querySelectorAll('.custom-select')]
    // console.log(customSelects)

    let count = 0
    let newLocation = customSelects.map((customSelect) => {
        let hrefStart = count === 0 ? (href + '?') : '&';
        count++

        return hrefStart + `${customSelect.name}=${customSelect.value}`
    }).join('');

    $.ajax({
        type: "GET",
        url: newLocation,
        success: function (data) {
            window.location = newLocation
        }
    });
});

function setDisabled() {
    let firstCustomSelect = document.querySelector('.select-block :nth-child(1) select')
    let customSelects = document.querySelectorAll('.select-block :not(:nth-child(1))')

    customSelects.forEach((el) => {
        if (firstCustomSelect.selectedIndex === 0)
            el.classList.add('disabled-button')
        else el.classList.remove('disabled-button')
        firstCustomSelect.classList.remove('disabled-button')
    })
}

setDisabled()