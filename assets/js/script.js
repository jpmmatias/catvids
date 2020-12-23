const menuBtn = document.querySelector('button.menuBtn')

const handleToggle =()=>{
    const main= document.querySelector('main#mainSectionContainer');
    const sidebar= document.querySelector('aside#sideNavContainer');
    sidebar.classList.toggle('open')
    main.classList.toggle('open')
    if (main.classList.contains('open')) {
        menuBtn.innerHTML='Fechar'
    } else{
        menuBtn.innerHTML='Menu'
    }
    
}

const notSignedIn =()=>{
    alert("Você precisa estar logado para fazer essa função")
}

menuBtn.addEventListener('click',handleToggle)
