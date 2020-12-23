const subscribe=(userTo,userFrom,button)=>{
    if (userTo==userFrom) {
        alert("Você não pode se inscrever em você mesmo");
    }

    $.post("ajax/subscribe.php")
    .done(
        ()=>{
            console.log('done');
        }
    )
}