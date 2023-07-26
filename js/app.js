let input = document.querySelector('input#input__file');

input.addEventListener('change', (e) => {
    document.querySelector('label.fake-file>div').innerText = input.files[0].name;
})