const subscribe=(userTo,userFrom,button)=>{
    if (userTo==userFrom) {
        alert("Você não pode se inscrever em você mesmo");
    }

    $.post("ajax/subscribe.php",{userTo:userTo,userFrom:userFrom})
    .done(
        (data)=>{
            if (data != null) {
                $(button).toggleClass('sub unsub')
                let btnText = $(button).hasClass('sub') ? 'INSCREVA-SE' : 'INSCRITO'
                $(button).text(btnText + " "+ data)
            }else{
                alert('Algo deu errado')
            }
        }
    )
}