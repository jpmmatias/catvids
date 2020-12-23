const form = document.querySelector('form#uploadVidForm');
const loadingModal = document.querySelector('div#loadingModal');

form.addEventListener('submit',()=>{
    loadingModal.classList.add('show')
})

